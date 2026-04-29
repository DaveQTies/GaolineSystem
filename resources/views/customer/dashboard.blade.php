@extends('layouts.app', ['active' => 'customer'])
@section('title', 'Order Fuel')
@section('header_title', 'Customer Dashboard')
@section('header_subtitle', 'Manage your fuel pre-orders')

@section('content')
@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-2xl font-bold">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-2xl font-bold">
        {{ $errors->first() }}
    </div>
@endif

<div class="space-y-6 lg:space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
    <div class="lg:col-span-2">
        <div id="new-order" class="bg-white p-5 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-800">New Pre-Order</h3>
                <i class="fas fa-calendar-alt text-amber-500 text-xl"></i>
            </div>
            <form action="{{ route('customer.orders.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Customer Name</label>
                    <input type="text" name="customer_name" required placeholder="Enter your name" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl">
                </div>
                @if(!empty($customerEmail))
                    <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-xs font-bold text-blue-700">
                        Orders will be saved under {{ $customerEmail }}
                    </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Fuel Type</label>
                    <select name="fuel_type" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="Premium Gasoline">Premium Gasoline</option>
                        <option value="Regular Gasoline">Regular Gasoline</option>
                        <option value="Krude">Krude</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Quantity (Liters)</label>
                    <input type="number" name="liters" min="1" step="0.01" required placeholder="0" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pickup Time Slot</label>
                    <select name="pickup_time" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="" selected disabled>Select time slot</option>
                        <option value="Any Time">Any Time</option>
                        <option value="08:00 AM - 09:00 AM">08:00 AM - 09:00 AM</option>
                        <option value="09:00 AM - 10:00 AM">09:00 AM - 10:00 AM</option>
                        <option value="10:00 AM - 11:00 AM">10:00 AM - 11:00 AM</option>
                        <option value="11:00 AM - 12:00 PM">11:00 AM - 12:00 PM</option>
                        <option value="12:00 PM - 01:00 PM">12:00 PM - 01:00 PM</option>
                        <option value="01:00 PM - 02:00 PM">01:00 PM - 02:00 PM</option>
                        <option value="02:00 PM - 03:00 PM">02:00 PM - 03:00 PM</option>
                        <option value="03:00 PM - 04:00 PM">03:00 PM - 04:00 PM</option>
                        <option value="04:00 PM - 05:00 PM">04:00 PM - 05:00 PM</option>
                        <option value="05:00 PM - 06:00 PM">05:00 PM - 06:00 PM</option>
                        <option value="06:00 PM - 07:00 PM">06:00 PM - 07:00 PM</option>
                    </select>
                </div>
                <button class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-amber-200 transition-all">
                    Submit Pre-Order
                </button>
            </form>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i> Quick Info
            </h3>
            <div class="space-y-3">
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-600">Premium Gasoline Price</span>
                    <span class="text-lg font-black text-amber-600">PHP 68.50/L</span>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-600">Regular Gasoline Price</span>
                    <span class="text-lg font-black text-blue-600">PHP 62.00/L</span>
                </div>
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-600">Krude Price</span>
                    <span class="text-lg font-black text-emerald-600">PHP 65.00/L</span>
                </div>
            </div>
        </div>
        
        <div class="bg-amber-500 text-white p-6 rounded-2xl shadow-lg">
            <p class="text-xs font-bold opacity-80 uppercase mb-2">Notice</p>
            <p class="text-sm font-medium">Pre-orders must be placed at least 2 hours before pickup time.</p>
        </div>
    </div>
    </div>

    <div id="my-orders" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden w-full">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="font-bold text-slate-800">My Orders</h3>
                <p class="text-xs font-medium text-slate-400">Full order history uses the whole page width.</p>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $orders->count() }} Orders</span>
        </div>
        <div class="overflow-x-auto soft-scrollbar">
        <div class="border-b border-slate-100 bg-slate-50 px-5 py-2 text-[10px] font-black uppercase tracking-widest text-slate-400 md:hidden">
            Swipe sideways to view all columns
        </div>
        <table class="w-full min-w-[980px] text-left text-sm">
            <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400">
                <tr>
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Fuel</th>
                    <th class="px-6 py-3">Liters</th>
                    <th class="px-6 py-3">Pickup</th>
                    <th class="px-6 py-3">Placed At</th>
                    <th class="px-6 py-3">Order Age</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($orders as $order)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-bold">{{ $order->order_id }}</td>
                    <td class="px-6 py-4">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4">{{ $order->fuel_type }}</td>
                    <td class="px-6 py-4">{{ $order->liters }}L</td>
                    <td class="px-6 py-4">{{ $order->pickup_time }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-700">{{ $order->created_at->timezone('Asia/Manila')->format('M d, Y') }}</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $order->created_at->timezone('Asia/Manila')->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span data-order-time="{{ $order->created_at->timezone('Asia/Manila')->toIso8601String() }}" data-no-translate class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-black uppercase text-slate-500">
                            {{ $order->created_at->timezone('Asia/Manila')->diffForHumans() }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold">{{ $order->status }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-slate-400 font-bold">No orders yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
