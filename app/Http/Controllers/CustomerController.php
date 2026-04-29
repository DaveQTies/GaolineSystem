<?php

namespace App\Http\Controllers;

use App\Models\FuelStock;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customerEmail = session('login_email');

        $orders = Order::query()
            ->when(
                $customerEmail,
                fn ($query) => $query->where('customer_email', $customerEmail),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->latest()
            ->take(10)
            ->get();

        return view('customer.dashboard', compact('orders', 'customerEmail'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'fuel_type' => 'required|in:Premium Gasoline,Regular Gasoline,Krude',
            'liters' => 'required|numeric|min:1',
            'pickup_time' => 'required|string|max:255',
        ]);

        $stock = FuelStock::firstOrCreate(
            ['fuel_type' => $validated['fuel_type']],
            ['quantity' => $this->defaultStockQuantity($validated['fuel_type'])]
        );

        if ($stock->quantity < $validated['liters']) {
            return back()->withErrors(['liters' => 'Not enough stock available for ' . $validated['fuel_type'] . '.']);
        }

        $stock->decrement('quantity', $validated['liters']);

        Order::create([
            'order_id' => 'ORD-' . strtoupper(Str::random(6)),
            'customer_name' => $validated['customer_name'],
            'customer_email' => session('login_email'),
            'fuel_type' => $validated['fuel_type'],
            'liters' => $validated['liters'],
            'pickup_time' => $validated['pickup_time'],
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Pre-order submitted successfully.');
    }

    private function defaultStockQuantity(string $fuelType): int
    {
        return match ($fuelType) {
            'Premium Gasoline' => 8500,
            'Regular Gasoline' => 2300,
            'Krude' => 5100,
            default => 0,
        };
    }
}
