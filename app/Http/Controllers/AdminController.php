<?php

namespace App\Http\Controllers;

use App\Models\FuelStock;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private const FUEL_PRICES = [
        'Premium Gasoline' => 68.50,
        'Regular Gasoline' => 62.00,
        'Krude' => 65.00,
    ];

    public function index()
    {
        $this->ensureFuelStocks();

        $users = User::query()
            ->when(request('q'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        $stats = [
            'reservations' => [
                'daily' => (string) $this->ordersForPeriod('daily')->count(),
                'weekly' => (string) $this->ordersForPeriod('weekly')->count(),
                'yearly' => (string) $this->ordersForPeriod('yearly')->count(),
            ],
            'revenue' => [
                'daily' => $this->formatMoney($this->revenueForPeriod('daily')),
                'weekly' => $this->formatMoney($this->revenueForPeriod('weekly')),
                'yearly' => $this->formatMoney($this->revenueForPeriod('yearly')),
            ],
            'low_stock' => FuelStock::where('quantity', '<', 3000)->count(),
        ];

        $stockOrder = array_keys(self::FUEL_PRICES);
        $stocks = FuelStock::all()->sortBy(fn ($stock) => array_search($stock->fuel_type, $stockOrder))->values();

        $reservationChart = $this->reservationChartData();
        $activeAdminCount = $this->activeAdminCount();

        return view('admin.dashboard', compact('stats', 'users', 'stocks', 'reservationChart', 'activeAdminCount'));
    }

    public function updateStock(Request $request)
    {
        $validated = $request->validate([
            'fuel_type' => 'required|in:Premium Gasoline,Regular Gasoline,Krude',
            'quantity' => 'required|numeric|min:1',
        ]);

        $stock = FuelStock::firstOrCreate(
            ['fuel_type' => $validated['fuel_type']],
            ['quantity' => 0]
        );

        $stock->increment('quantity', $validated['quantity']);

        return back()->with('success', 'Stock updated successfully for ' . $request->fuel_type);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:Customer,Clerk,Admin',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => 'Active',
            'password' => Hash::make('password123'),
        ]);

        return back()->with('success', 'User ' . $validated['name'] . ' has been saved.');
    }

    public function manageUsers()
    {
        return $this->index();
    }

    public function managePrices()
    {
        return $this->index();
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:Customer,Clerk,Admin',
        ]);

        if ($this->isLastActiveAdmin($user) && $validated['role'] !== 'Admin') {
            return back()->withErrors(['role' => 'You cannot remove the last active Admin. Add or promote another Admin first.']);
        }

        $user->update($validated);

        return back()->with('success', 'User updated successfully.');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ($this->isLastActiveAdmin($user) && $user->status === 'Active') {
            return back()->withErrors(['status' => 'You cannot deactivate the last active Admin. Add or promote another Admin first.']);
        }

        $user->status = $user->status === 'Active' ? 'Inactive' : 'Active';
        $user->save();

        return back()->with('success', 'User status updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($this->isLastActiveAdmin($user)) {
            return back()->withErrors(['user' => 'You cannot delete the last active Admin. Add or promote another Admin first.']);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    private function activeAdminCount(): int
    {
        return User::where('role', 'Admin')->where('status', 'Active')->count();
    }

    private function isLastActiveAdmin(User $user): bool
    {
        return $user->role === 'Admin'
            && $user->status === 'Active'
            && $this->activeAdminCount() <= 1;
    }

    private function ensureFuelStocks(): void
    {
        collect([
            'Premium Gasoline' => 8500,
            'Regular Gasoline' => 2300,
            'Krude' => 5100,
        ])->each(function ($quantity, $fuelType) {
            FuelStock::firstOrCreate(
                ['fuel_type' => $fuelType],
                ['quantity' => $quantity]
            );
        });
    }

    private function ordersForPeriod(string $period)
    {
        return Order::query()
            ->when($period === 'daily', fn ($query) => $query->whereDate('created_at', Carbon::today()))
            ->when($period === 'weekly', fn ($query) => $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]))
            ->when($period === 'yearly', fn ($query) => $query->whereYear('created_at', Carbon::now()->year));
    }

    private function revenueForPeriod(string $period): float
    {
        return $this->ordersForPeriod($period)
            ->get()
            ->sum(fn (Order $order) => (float) $order->liters * (self::FUEL_PRICES[$order->fuel_type] ?? 0));
    }

    private function formatMoney(float $amount): string
    {
        return 'PHP ' . number_format($amount, 2);
    }

    private function reservationChartData(): array
    {
        $fuelTypes = array_keys(self::FUEL_PRICES);
        $days = collect(range(6, 0))->map(fn ($daysAgo) => Carbon::today()->subDays($daysAgo));

        $series = collect($fuelTypes)
            ->mapWithKeys(fn ($fuelType) => [$fuelType => []])
            ->all();

        $labels = [];
        $max = 0;

        foreach ($days as $day) {
            $labels[] = $day->format('D');

            foreach ($fuelTypes as $fuelType) {
                $count = Order::whereDate('created_at', $day)
                    ->where('fuel_type', $fuelType)
                    ->count();

                $series[$fuelType][] = $count;
                $max = max($max, $count);
            }
        }

        return [
            'labels' => $labels,
            'series' => $series,
            'max' => max($max, 1),
            'colors' => [
                'Premium Gasoline' => 'bg-amber-500 group-hover:bg-amber-600',
                'Regular Gasoline' => 'bg-[#2a4d7d] group-hover:bg-[#23426e]',
                'Krude' => 'bg-emerald-500 group-hover:bg-emerald-600',
            ],
        ];
    }
}
