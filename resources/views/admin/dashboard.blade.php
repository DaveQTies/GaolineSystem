@extends('layouts.app', ['active' => 'admin'])

@section('title', 'Admin Dashboard')
@section('header_title', 'Admin Dashboard')
@section('header_subtitle', 'System administration and analytics')

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

    <div id="overview" class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <div class="bg-white p-5 sm:p-6 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4 sm:gap-5 transition-transform hover:scale-[1.02]">
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-xl sm:text-2xl shadow-inner shrink-0">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Total Reservations</p>
                    <select data-stat-select="reservations" class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-black uppercase text-slate-500 outline-none focus:border-amber-500">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <h3 id="reservationTotal" class="text-3xl sm:text-4xl font-black text-slate-800">{{ $stats['reservations']['daily'] }}</h3>
                <span id="reservationTrend" class="text-emerald-500 text-xs font-bold"><i class="fas fa-caret-up"></i> +12% Today</span>
            </div>
        </div>

        <div class="bg-white p-5 sm:p-6 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4 sm:gap-5 transition-transform hover:scale-[1.02]">
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl sm:text-2xl shadow-inner shrink-0">
                <i class="fas fa-peso-sign"></i>
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Total Revenue</p>
                    <select data-stat-select="revenue" class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-black uppercase text-slate-500 outline-none focus:border-amber-500">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <h3 id="revenueTotal" class="text-2xl sm:text-4xl font-black text-slate-800 break-words">{{ $stats['revenue']['daily'] }}</h3>
                <span id="revenueTrend" class="text-emerald-500 text-xs font-bold"><i class="fas fa-caret-up"></i> +8% Today</span>
            </div>
        </div>

        <div class="bg-white p-5 sm:p-6 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4 sm:gap-5 transition-transform hover:scale-[1.02]">
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-xl sm:text-2xl shadow-inner shrink-0">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Low Stock Alerts</p>
                <h3 class="text-3xl sm:text-4xl font-black text-slate-800">{{ $stats['low_stock'] }}</h3>
                <span class="text-red-500 text-[10px] font-black uppercase">Action Needed</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
        <div id="reservations" class="lg:col-span-2 bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-8 sm:mb-10 gap-4">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Daily Reservations</h3>
                    <p class="text-xs text-slate-400">Last 7 days performance</p>
                </div>
                <button class="w-10 h-10 bg-[#2a4d7d] text-white rounded-xl flex items-center justify-center hover:bg-slate-700 transition-colors">
                    <i class="fas fa-chart-line"></i>
                </button>
            </div>

            <div class="h-56 sm:h-64 flex items-end gap-2 sm:gap-4 px-1 sm:px-4 border-b border-slate-100 pb-2 overflow-x-auto">
                @foreach ($reservationChart['labels'] as $dayIndex => $label)
                    <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end">
                        <div class="w-full min-w-[36px] h-full bg-slate-50 rounded-t-lg flex items-end justify-center gap-1 px-1 overflow-hidden">
                            @foreach ($reservationChart['series'] as $fuelType => $counts)
                                @php
                                    $count = $counts[$dayIndex] ?? 0;
                                    $height = $count > 0 ? max(8, ($count / $reservationChart['max']) * 100) : 2;
                                @endphp
                                <div
                                    class="w-full max-w-[18px] rounded-t-md transition-all duration-500 {{ $reservationChart['colors'][$fuelType] }}"
                                    style="height: {{ $height }}%"
                                    title="{{ $fuelType }}: {{ $count }} reservations"
                                ></div>
                            @endforeach
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
            <div class="flex flex-wrap justify-center gap-4 sm:gap-8 mt-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <span class="text-xs font-bold text-slate-500">Premium Gasoline</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#2a4d7d]"></span>
                    <span class="text-xs font-bold text-slate-500">Regular Gasoline</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-slate-500">Krude</span>
                </div>
            </div>
        </div>

        <div id="inventory" class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-800">Inventory Restock</h3>
                <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-amber-200">
                    <i class="fas fa-box"></i>
                </div>
            </div>

            <form action="{{ route('admin.updateStock') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Fuel Type</label>
                    <select name="fuel_type" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-amber-500 transition-all text-sm font-bold text-slate-700">
                        <option value="Premium Gasoline">Premium Gasoline</option>
                        <option value="Regular Gasoline">Regular Gasoline</option>
                        <option value="Krude">Krude</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Quantity (Liters)</label>
                    <input type="number" name="quantity" placeholder="0" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-amber-500 transition-all text-sm font-bold" required>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-[10px] font-black text-slate-400 uppercase mb-3">Current Stock Levels</p>
                    @foreach ($stocks as $stock)
                        <div class="flex justify-between items-center {{ !$loop->first ? 'mt-2' : '' }}">
                            <span class="text-xs font-bold text-slate-600">{{ $stock->fuel_type }}</span>
                            <span class="text-xs font-black {{ $stock->quantity < 3000 ? 'text-red-500' : 'text-emerald-600' }}">
                                {{ number_format($stock->quantity) }}L
                            </span>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-amber-200 transition-all">
                    Add Stock
                </button>
            </form>
        </div>
    </div>

    <div id="users" class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 sm:p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="text-xl font-black text-slate-800">User Management</h3>
                <p class="text-xs text-slate-400 font-medium tracking-wide">Manage system users and permissions</p>
            </div>
            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search users..." class="pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-amber-500 text-xs w-full md:w-64 transition-all">
                </form>
                <button onclick="toggleModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 shadow-lg shadow-emerald-100 transition-all">
                    <i class="fas fa-user-plus"></i> Add User
                </button>
            </div>
        </div>

        <div class="overflow-x-auto soft-scrollbar">
            <div class="border-b border-slate-100 bg-slate-50 px-5 py-2 text-[10px] font-black uppercase tracking-widest text-slate-400 md:hidden">
                Swipe sideways to view all columns
            </div>
            <table class="w-full min-w-[960px] text-left">
                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Name</th>
                        <th class="px-8 py-5">Email</th>
                        <th class="px-8 py-5">Role</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Last Sign In</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm font-medium">
                    @forelse ($users as $user)
                        @php
                            $isLastActiveAdmin = $user->role === 'Admin' && $user->status === 'Active' && $activeAdminCount <= 1;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5 font-black text-slate-700">{{ $user->name }}</td>
                            <td class="px-8 py-5 text-slate-400">{{ $user->email }}</td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase {{ $user->role == 'Customer' ? 'bg-blue-100 text-blue-600' : ($user->role == 'Clerk' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $user->status == 'Active' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-red-500' }}"></div>
                                    <span class="text-[10px] font-black uppercase {{ $user->status == 'Active' ? 'text-emerald-600' : 'text-red-500' }}">{{ $user->status }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @if($user->last_login_at)
                                    <div class="font-bold text-slate-700">{{ $user->last_login_at->timezone('Asia/Manila')->format('M d, Y') }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $user->last_login_at->timezone('Asia/Manila')->format('h:i A') }}</div>
                                @else
                                    <span class="text-xs font-bold text-slate-400">Not yet</span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex justify-end gap-2 flex-wrap">
                                    <button type="button" onclick="toggleModal('editUserModal{{ $user->id }}')" class="text-[#2a4d7d] hover:bg-blue-50 px-3 py-2 rounded-lg text-[10px] font-black uppercase transition-all">Edit</button>
                                    <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" @disabled($isLastActiveAdmin && $user->status === 'Active') title="{{ $isLastActiveAdmin ? 'Last active Admin cannot be deactivated' : '' }}" class="text-amber-600 hover:bg-amber-50 px-3 py-2 rounded-lg text-[10px] font-black uppercase transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent">
                                            {{ $user->status == 'Active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return {{ $isLastActiveAdmin ? 'false' : 'confirm(window.t(\'Delete this user?\'))' }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" @disabled($isLastActiveAdmin) title="{{ $isLastActiveAdmin ? 'Last active Admin cannot be deleted' : '' }}" class="text-red-500 hover:bg-red-50 px-3 py-2 rounded-lg text-[10px] font-black uppercase transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent">Delete</button>
                                    </form>
                                </div>
                                @if($isLastActiveAdmin)
                                    <p class="mt-2 text-right text-[10px] font-bold text-slate-400">Last active Admin is protected.</p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-sm font-bold text-slate-400">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="addUserModal" class="fixed inset-0 z-[60] hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] w-full max-w-md p-6 sm:p-10 shadow-2xl relative">
            <button onclick="toggleModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
            <h2 class="text-2xl font-black text-slate-800 mb-6">Add New User</h2>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Full Name</label>
                    <input type="text" name="name" required placeholder="e.g. Juan Dela Cruz" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Email Address</label>
                    <input type="email" name="email" required placeholder="email@example.com" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Account Role</label>
                    <select name="role" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold outline-none focus:border-amber-500">
                        <option value="Customer">Customer</option>
                        <option value="Clerk">Clerk</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-emerald-500 text-white font-black py-4 rounded-2xl mt-4 shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition-all">
                    Save User
                </button>
            </form>
        </div>
    </div>

    @foreach ($users as $user)
        @php
            $isLastActiveAdmin = $user->role === 'Admin' && $user->status === 'Active' && $activeAdminCount <= 1;
        @endphp
        <div id="editUserModal{{ $user->id }}" class="fixed inset-0 z-[60] hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] w-full max-w-md p-6 sm:p-10 shadow-2xl relative">
                <button type="button" onclick="toggleModal('editUserModal{{ $user->id }}')" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
                <h2 class="text-2xl font-black text-slate-800 mb-6">Edit User</h2>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Full Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Email Address</label>
                        <input type="email" name="email" value="{{ $user->email }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Account Role</label>
                        <select name="role" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold outline-none focus:border-amber-500">
                            <option value="Customer" @selected($user->role === 'Customer') @disabled($isLastActiveAdmin)>Customer</option>
                            <option value="Clerk" @selected($user->role === 'Clerk') @disabled($isLastActiveAdmin)>Clerk</option>
                            <option value="Admin" @selected($user->role === 'Admin')>Admin</option>
                        </select>
                        @if($isLastActiveAdmin)
                            <p class="mt-2 text-[11px] font-bold text-amber-600">This is the only active Admin, so it must stay Admin until another Admin is added or promoted.</p>
                        @endif
                    </div>
                    <button type="submit" class="w-full bg-amber-500 text-white font-black py-4 rounded-2xl mt-4 shadow-lg shadow-amber-100 hover:bg-amber-600 transition-all">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        const statValues = {
            reservations: @json($stats['reservations']),
            revenue: @json($stats['revenue']),
        };

        const statTrends = {
            reservations: {
                daily: '<i class="fas fa-caret-up"></i> +12% Today',
                weekly: '<i class="fas fa-caret-up"></i> +18% This Week',
                yearly: '<i class="fas fa-caret-up"></i> +24% This Year',
            },
            revenue: {
                daily: '<i class="fas fa-caret-up"></i> +8% Today',
                weekly: '<i class="fas fa-caret-up"></i> +15% This Week',
                yearly: '<i class="fas fa-caret-up"></i> +21% This Year',
            },
        };

        document.querySelectorAll('[data-stat-select]').forEach(select => {
            select.addEventListener('change', () => {
                const statName = select.dataset.statSelect;
                const period = select.value;
                const totalId = statName === 'reservations' ? 'reservationTotal' : 'revenueTotal';
                const trendId = statName === 'reservations' ? 'reservationTrend' : 'revenueTrend';

                document.getElementById(totalId).textContent = statValues[statName][period];
                document.getElementById(trendId).innerHTML = statTrends[statName][period];
                window.translatePage();
            });
        });

        function toggleModal(id = 'addUserModal') {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }
    </script>
@endsection
