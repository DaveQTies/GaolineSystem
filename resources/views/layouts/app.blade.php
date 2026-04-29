<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Krude Gas - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .soft-scrollbar::-webkit-scrollbar { height: 8px; width: 8px; }
        .soft-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 999px; }
        .soft-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        .soft-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-slate-100 font-sans antialiased text-slate-800">
    @php
        $activeSection = $active ?? 'admin';
        $navItems = [
            'admin' => [
                ['label' => 'Overview', 'icon' => 'fa-chart-pie', 'href' => route('admin.dashboard')],
                ['label' => 'Reservations', 'icon' => 'fa-chart-column', 'href' => route('admin.dashboard') . '#reservations'],
                ['label' => 'Inventory', 'icon' => 'fa-boxes-stacked', 'href' => route('admin.dashboard') . '#inventory'],
                ['label' => 'Users', 'icon' => 'fa-users-gear', 'href' => route('admin.dashboard') . '#users'],
            ],
            'clerk' => [
                ['label' => 'Orders', 'icon' => 'fa-list-check', 'href' => route('clerk.dashboard') . '#orders'],
                ['label' => 'Walk-In Order', 'icon' => 'fa-plus', 'href' => route('clerk.dashboard') . '#new-order'],
            ],
            'customer' => [
                ['label' => 'New Order', 'icon' => 'fa-gas-pump', 'href' => route('customer.dashboard') . '#new-order'],
                ['label' => 'My Orders', 'icon' => 'fa-receipt', 'href' => route('customer.dashboard') . '#my-orders'],
            ],
        ][$activeSection] ?? [];
    @endphp

    <div class="flex min-h-screen md:h-screen overflow-hidden">
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-[#1f3b63] text-white shadow-2xl shadow-slate-950/20 transition-transform duration-300 md:static md:w-64 md:translate-x-0 md:shadow-none">
            <div class="p-5 sm:p-6 flex items-center gap-3">
                <div class="w-11 h-11 bg-amber-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-950/20">
                    <i class="fas fa-gas-pump text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Krude Gas</h1>
                    <p class="text-[10px] text-slate-300 uppercase tracking-widest">Pre-Order System</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 px-3">
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}" class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/5 text-amber-300 transition group-hover:bg-amber-500 group-hover:text-white">
                            <i class="fas {{ $item['icon'] }} text-sm"></i>
                        </span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 p-2 rounded-lg bg-white/5">
                    <div
                        class="w-8 h-8 rounded-full bg-amber-400 flex items-center justify-center text-xs font-bold text-[#23426e]">
                        JD</div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-xs font-semibold truncate">Juan Dela Cruz</p>
                        <p class="text-[10px] text-slate-400 tracking-wide uppercase">@yield('role_name', 'User')</p>
                    </div>
                    <a href="/"
                        class="text-slate-400 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg"
                        title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </aside>

        <div id="sidebarOverlay" class="fixed inset-0 z-30 hidden bg-slate-950/50 md:hidden"></div>

        <main class="flex-1 flex flex-col overflow-y-auto min-w-0">
            <header class="bg-[#2a4d7d]/95 backdrop-blur text-white px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap justify-between items-center gap-3 sticky top-0 z-20 shadow-sm shadow-slate-900/10">
                <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                    <button id="sidebarToggle" type="button" class="md:hidden text-white w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center"><i class="fas fa-bars"></i></button>
                    <div>
                        <h2 class="font-bold text-base sm:text-lg leading-tight">@yield('header_title')</h2>
                        <p class="text-[11px] sm:text-xs text-slate-300">@yield('header_subtitle')</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 ml-auto">
                    <div class="hidden sm:flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-xs font-bold text-slate-100">
                        <i class="fas fa-clock text-amber-300"></i>
                        <span id="liveClock" data-live-clock data-no-translate>Loading time...</span>
                    </div>
                    <div class="flex bg-white/10 rounded-lg p-1">
                        <button type="button" data-lang-button="en" class="px-3 py-1 text-xs font-bold rounded-md">EN</button>
                        <button type="button" data-lang-button="fil" class="px-3 py-1 text-xs font-bold rounded-md">FIL</button>
                    </div>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        const translations = {
            'Pre-Order System': 'Pre-Order System',
            'User': 'User',
            'Logout': 'Logout',
            'Loading time...': 'Loading time...',

            'Admin Dashboard': 'Admin Dashboard',
            'System administration and analytics': 'System admin at analytics',
            'Total Reservations': 'Kabuuang Reservations',
            'Total Revenue': 'Kabuuang Revenue',
            'Low Stock Alerts': 'Low Stock Alerts',
            'Action Needed': 'Kailangan ng Action',
            'Daily': 'Daily',
            'Weekly': 'Weekly',
            'Yearly': 'Yearly',
            'Daily Reservations': 'Daily Reservations',
            'Last 7 days performance': 'Performance nitong last 7 days',
            'Day': 'Araw',
            'Inventory Restock': 'Inventory Restock',
            'Fuel Type': 'Uri ng Fuel',
            'Quantity (Liters)': 'Dami (Liters)',
            'Current Stock Levels': 'Current Stock Levels',
            'Add Stock': 'Add Stock',
            'User Management': 'User Management',
            'Manage system users and permissions': 'Manage users at permissions',
            'Name': 'Pangalan',
            'Email': 'Email',
            'Role': 'Role',
            'Status': 'Status',
            'Last Sign In': 'Last Sign In',
            'Actions': 'Actions',
            'Add User': 'Add User',
            'Add New User': 'Add New User',
            'Edit User': 'Edit User',
            'Full Name': 'Full Name',
            'Email Address': 'Email Address',
            'Account Role': 'Account Role',
            'Save User': 'Save User',
            'Save Changes': 'Save Changes',
            'Edit': 'Edit',
            'Deactivate': 'Deactivate',
            'Activate': 'Activate',
            'Delete': 'Delete',
            'Delete this user?': 'I-delete ang user na ito?',
            'No users found.': 'Walang users na nahanap.',
            'Not yet': 'Wala pa',
            'Customer': 'Customer',
            'Clerk': 'Clerk',
            'Admin': 'Admin',
            'Active': 'Active',
            'Inactive': 'Inactive',

            'Clerk Dashboard': 'Clerk Dashboard',
            'Pending': 'Pending',
            'Ready': 'Ready',
            'Claimed Today': 'Claimed Today',
            'Manage Orders': 'Manage Orders',
            'Order ID': 'Order ID',
            'Details': 'Details',
            'Mark Ready': 'Mark Ready',
            'Mark Claimed': 'Mark Claimed',
            'New Walk-In Order': 'New Walk-In Order',
            'Create Order': 'Create Order',

            'Order Fuel': 'Order Fuel',
            'Customer Dashboard': 'Customer Dashboard',
            'Manage your fuel pre-orders': 'Manage ng fuel pre-orders mo',
            'New Pre-Order': 'New Pre-Order',
            'Customer Name': 'Customer Name',
            'Pickup Time Slot': 'Pickup Time Slot',
            'Submit Pre-Order': 'Submit Pre-Order',
            'My Orders': 'My Orders',
            'Fuel': 'Fuel',
            'Liters': 'Liters',
            'Pickup': 'Pickup',
            'Placed At': 'Placed At',
            'Order Age': 'Order Age',
            'Just now': 'Just now',
            'No orders yet.': 'Wala pang orders.',
            'Quick Info': 'Quick Info',
            'Premium Gasoline Price': 'Premium Gasoline Price',
            'Regular Gasoline Price': 'Regular Gasoline Price',
            'Krude Price': 'Krude Price',
            'Notice': 'Notice',
            'Pre-orders must be placed at least 2 hours before pickup time.': 'Mag pre-order at least 2 hours bago ang pickup time.',

            'Premium Gasoline': 'Premium Gasoline',
            'Regular Gasoline': 'Regular Gasoline',
            'Krude': 'Krude',
            'Any Time': 'Any Time',
            'Select Pickup Time': 'Pumili ng Pickup Time',
            'Select time slot': 'Pumili ng time slot',

            '+12% Today': '+12% Today',
            '+18% This Week': '+18% This Week',
            '+24% This Year': '+24% This Year',
            '+8% Today': '+8% Today',
            '+15% This Week': '+15% This Week',
            '+21% This Year': '+21% This Year',
        };

        const placeholderTranslations = {
            'Search users...': 'Search users...',
            'Search Order ID...': 'Search Order ID...',
            'e.g. Juan Dela Cruz': 'e.g. Juan Dela Cruz',
            'email@example.com': 'email@example.com',
            'Customer Name': 'Customer Name',
            'Enter your name': 'Ilagay ang name mo',
            'Liters': 'Liters',
            '0': '0',
        };

        function translateTextNode(node, language) {
            const originalText = node.__enText ?? node.nodeValue.trim();
            if (!originalText) {
                return;
            }

            node.__enText = originalText;

            const translated = language === 'fil' ? translations[originalText] : originalText;
            if (!translated) {
                return;
            }

            const leadingSpace = node.nodeValue.match(/^\s*/)[0];
            const trailingSpace = node.nodeValue.match(/\s*$/)[0];
            node.nodeValue = leadingSpace + translated + trailingSpace;
        }

        function translateAttributes(language) {
            document.querySelectorAll('[placeholder]').forEach(element => {
                element.dataset.enPlaceholder = element.dataset.enPlaceholder || element.getAttribute('placeholder');
                const original = element.dataset.enPlaceholder;
                element.setAttribute('placeholder', language === 'fil' ? (placeholderTranslations[original] || original) : original);
            });

            document.querySelectorAll('[title]').forEach(element => {
                element.dataset.enTitle = element.dataset.enTitle || element.getAttribute('title');
                const original = element.dataset.enTitle;
                element.setAttribute('title', language === 'fil' ? (translations[original] || original) : original);
            });
        }

        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function setSidebarOpen(isOpen) {
            sidebar.classList.toggle('-translate-x-full', !isOpen);
            sidebarOverlay.classList.toggle('hidden', !isOpen);
        }

        sidebarToggle?.addEventListener('click', () => setSidebarOpen(true));
        sidebarOverlay?.addEventListener('click', () => setSidebarOpen(false));

        function formatLiveClock() {
            const clock = document.getElementById('liveClock');

            if (!clock) {
                return;
            }

            clock.textContent = new Intl.DateTimeFormat('en-PH', {
                timeZone: 'Asia/Manila',
                month: 'short',
                day: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
            }).format(new Date());
        }

        function formatOrderAge() {
            document.querySelectorAll('[data-order-time]').forEach(element => {
                const placedAt = new Date(element.dataset.orderTime);
                const seconds = Math.max(0, Math.floor((Date.now() - placedAt.getTime()) / 1000));
                const minutes = Math.floor(seconds / 60);
                const hours = Math.floor(minutes / 60);
                const days = Math.floor(hours / 24);

                let label = 'Just now';

                if (days > 0) {
                    label = `${days} day${days > 1 ? 's' : ''} ago`;
                } else if (hours > 0) {
                    label = `${hours} hour${hours > 1 ? 's' : ''} ago`;
                } else if (minutes > 0) {
                    label = `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
                }

                element.textContent = label;
                element.__enText = label;
            });
        }

        function applyLanguage(language) {
            const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
                acceptNode(node) {
                    if (['SCRIPT', 'STYLE'].includes(node.parentElement?.tagName)) {
                        return NodeFilter.FILTER_REJECT;
                    }

                    if (node.parentElement?.closest('[data-no-translate]')) {
                        return NodeFilter.FILTER_REJECT;
                    }

                    return node.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
                }
            });

            const textNodes = [];
            while (walker.nextNode()) {
                textNodes.push(walker.currentNode);
            }

            textNodes.forEach(node => translateTextNode(node, language));
            translateAttributes(language);

            document.querySelectorAll('[data-lang-button]').forEach(button => {
                button.classList.toggle('bg-amber-500', button.dataset.langButton === language);
                button.classList.toggle('text-white', button.dataset.langButton === language);
            });

            document.documentElement.lang = language === 'fil' ? 'fil' : 'en';
            localStorage.setItem('krudeLanguage', language);
        }

        window.t = function (text) {
            return localStorage.getItem('krudeLanguage') === 'fil' ? (translations[text] || text) : text;
        };

        window.translatePage = function () {
            applyLanguage(localStorage.getItem('krudeLanguage') || 'en');
        };

        document.querySelectorAll('[data-lang-button]').forEach(button => {
            button.addEventListener('click', () => applyLanguage(button.dataset.langButton));
        });

        document.addEventListener('DOMContentLoaded', () => {
            formatLiveClock();
            formatOrderAge();
            window.translatePage();

            setInterval(formatLiveClock, 1000);
            setInterval(() => {
                formatOrderAge();
                window.translatePage();
            }, 60000);
        });
    </script>
</body>

</html>
