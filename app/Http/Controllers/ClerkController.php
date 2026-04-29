<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FuelStock;
use App\Models\Order; 
use Carbon\Carbon;
use Illuminate\Support\Str; // KINI ANG IMPORTANTE: Para sa Str::random

class ClerkController extends Controller
{
    /**
     * I-display ang Dashboard
     */
    public function index()
    {
        // Kuhaon ang mga orders nga Pending ug Ready para sa table
        $orders = Order::whereIn('status', ['Pending', 'Ready'])
                        ->orderBy('status', 'desc') // Ready una ang makita
                        ->get();

        // Kuhaon ang mga counts para sa stats cards
        $pendingCount = Order::where('status', 'Pending')->count();
        $readyCount = Order::where('status', 'Ready')->count();
        $claimedTodayCount = Order::where('status', 'Claimed')
                                   ->whereDate('updated_at', Carbon::today())
                                   ->count();

        return view('clerk.dashboard', compact(
            'orders', 
            'pendingCount', 
            'readyCount', 
            'claimedTodayCount'
        ));
    }

    /**
     * Pag-create og New Walk-In Order
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'fuel_type' => 'required|in:Premium Gasoline,Regular Gasoline,Krude',
            'liters' => 'required|numeric|min:1',
            'pickup_time' => 'required',
        ]);

        $stock = FuelStock::firstOrCreate(
            ['fuel_type' => $request->fuel_type],
            ['quantity' => $this->defaultStockQuantity($request->fuel_type)]
        );

        if ($stock->quantity < $request->liters) {
            return back()->withErrors(['liters' => 'Not enough stock available for ' . $request->fuel_type . '.']);
        }

        $stock->decrement('quantity', $request->liters);

        Order::create([
            // Gigamit na nato ang Str::random(6) imbes nga str_random
            'order_id' => 'ORD-' . strtoupper(Str::random(6)), 
            'customer_name' => $request->customer_name,
            'fuel_type' => $request->fuel_type,
            'liters' => $request->liters,
            'pickup_time' => $request->pickup_time,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Order created successfully, dol!');
    }

    /**
     * Pag-update sa Status (Mark Ready / Mark Claimed)
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Logic: Pending -> Ready -> Claimed
        if ($order->status == 'Pending') {
            $order->status = 'Ready';
        } elseif ($order->status == 'Ready') {
            $order->status = 'Claimed';
        }
        
        $order->save();

        return back()->with('success', 'Order status updated to ' . $order->status . '!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $orders = Order::whereIn('status', ['Pending', 'Ready'])
            ->when($query, function ($orders) use ($query) {
                $orders->where(function ($orders) use ($query) {
                    $orders->where('order_id', 'like', "%{$query}%")
                        ->orWhere('customer_name', 'like', "%{$query}%")
                        ->orWhere('fuel_type', 'like', "%{$query}%");
                });
            })
            ->orderBy('status', 'desc')
            ->get();

        $pendingCount = Order::where('status', 'Pending')->count();
        $readyCount = Order::where('status', 'Ready')->count();
        $claimedTodayCount = Order::where('status', 'Claimed')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        return view('clerk.dashboard', compact(
            'orders',
            'pendingCount',
            'readyCount',
            'claimedTodayCount'
        ));
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
