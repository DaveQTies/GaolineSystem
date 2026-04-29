<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krude Gas - Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .role-active { border-color: #f59e0b !important; background-color: #fef3c7 !important; transform: scale(1.02); }
        .role-active i.fa-check-circle { display: block !important; }
        .role-card.role-needed {
            animation: rolePulse 0.9s ease-in-out 2;
        }
        @keyframes rolePulse {
            0%, 100% { border-color: #e2e8f0; box-shadow: none; }
            50% { border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.18); }
        }
    </style>
</head>
<body class="bg-[#1e3a5f] flex items-center justify-center min-h-screen p-3 sm:p-4">

    <div class="bg-white w-full max-w-5xl rounded-[1.5rem] sm:rounded-[2.5rem] shadow-2xl flex overflow-hidden md:min-h-[600px] ring-1 ring-white/20">
        
        <div class="hidden md:flex md:w-1/2 bg-[#23426e] p-12 flex-col justify-between text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-amber-400"></div>
                <div class="absolute -bottom-24 left-10 h-72 w-72 rounded-full bg-sky-300"></div>
            </div>
            <div class="relative">
            <div class="mb-10">
                <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-gas-pump text-2xl"></i>
                </div>
                <h1 class="text-4xl font-black">Krude Gas</h1>
                <p class="text-[10px] opacity-60 tracking-[0.2em] uppercase font-bold">Pre-Order System</p>
            </div>
            <h2 class="text-3xl font-bold mb-6">Welcome Back!</h2>
            <p class="text-slate-300 mb-10 text-sm leading-relaxed">Streamline your fuel ordering process with our advanced pre-order management system.</p>
            </div>
            <div class="relative grid grid-cols-3 gap-3 text-center">
                <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-lg font-black text-amber-300">24/7</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-300">Orders</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-lg font-black text-amber-300">3</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-300">Fuel Types</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-lg font-black text-amber-300">Live</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-300">Tracking</p>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-5 sm:p-8 md:p-14">
            <div class="mb-6 flex items-center gap-3 md:hidden">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500 text-white">
                    <i class="fas fa-gas-pump"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-800">Krude Gas</h1>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pre-Order System</p>
                </div>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 mb-2">Sign In</h2>
            <p class="text-slate-400 text-sm mb-6 sm:mb-8 font-medium">Select your role and enter your credentials</p>

            @if($errors->any())
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-bold text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form id="loginForm" action="{{ route('login.redirect') }}" method="POST" autocomplete="on" class="space-y-5">
                @csrf
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Select Your Role</label>
                    
                    <button type="button" data-role="customer" onclick="selectRole('customer', this)" class="role-card w-full text-left p-3 border-2 border-slate-100 rounded-2xl flex items-center gap-4 cursor-pointer transition-all hover:border-amber-200">
                        <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center transition-all icon-box">
                            <i class="fas fa-th-large text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-800 text-sm">Customer</p>
                            <p class="text-[10px] text-slate-500">Place and manage your fuel pre-orders</p>
                        </div>
                        <i class="fas fa-check-circle text-emerald-500 text-lg hidden"></i>
                    </button>

                    <button type="button" data-role="clerk" onclick="selectRole('clerk', this)" class="role-card w-full text-left p-3 border-2 border-slate-100 rounded-2xl flex items-center gap-4 cursor-pointer transition-all hover:border-amber-200">
                        <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center transition-all icon-box">
                            <i class="fas fa-user-group text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-800 text-sm">Clerk</p>
                            <p class="text-[10px] text-slate-500">Manage orders and walk-in customers</p>
                        </div>
                        <i class="fas fa-check-circle text-emerald-500 text-lg hidden"></i>
                    </button>

                    <button type="button" data-role="admin" onclick="selectRole('admin', this)" class="role-card w-full text-left p-3 border-2 border-slate-100 rounded-2xl flex items-center gap-4 cursor-pointer transition-all hover:border-amber-200">
                        <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center transition-all icon-box">
                            <i class="fas fa-user-shield text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-800 text-sm">Admin</p>
                            <p class="text-[10px] text-slate-500">Full system access and management</p>
                        </div>
                        <i class="fas fa-check-circle text-emerald-500 text-lg hidden"></i>
                    </button>
                </div>

                <input type="hidden" name="role" id="selectedRole" value="">

                <div class="space-y-4">
                    <input type="email" name="email" id="emailInput" autocomplete="username" placeholder="Email Address" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-amber-500 text-sm" required>
                    <input type="password" name="password" id="passwordInput" autocomplete="current-password" placeholder="Password" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:border-amber-500 text-sm" required>
                </div>

                <label class="flex items-center gap-3 text-sm font-bold text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" id="rememberInput" value="1" class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-4 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-2">
                    Sign In <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    <div id="rolePopup" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm">
        <div class="w-full max-w-sm overflow-hidden rounded-[2rem] bg-white shadow-2xl">
            <div class="bg-[#2a4d7d] px-6 py-5 text-white">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500 shadow-lg shadow-amber-900/20">
                        <i class="fas fa-gas-pump text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-200">Role Required</p>
                        <h3 class="text-xl font-black">Pili muna ng role</h3>
                    </div>
                </div>
            </div>
            <div class="space-y-5 p-6">
                <p class="text-sm font-semibold leading-relaxed text-slate-600">
                    Para ma-redirect ka sa tamang dashboard, select ka muna ng Customer, Clerk, or Admin.
                </p>
                <button type="button" onclick="closeRolePopup()" class="w-full rounded-2xl bg-amber-500 py-4 text-sm font-black text-white shadow-xl shadow-amber-100 transition hover:bg-amber-600">
                    Got it, choose role
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentRole = '';
        const rolePopup = document.getElementById('rolePopup');
        const emailInput = document.getElementById('emailInput');
        const passwordInput = document.getElementById('passwordInput');
        const rememberInput = document.getElementById('rememberInput');
        const rememberKey = 'krudeLoginRemember';

        function selectRole(role, element) {
            currentRole = role;
            document.getElementById('selectedRole').value = role;

            // Remove active style from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('role-active');
                card.querySelector('.icon-box').classList.replace('bg-amber-500', 'bg-slate-100');
                card.querySelector('.icon-box').classList.replace('text-white', 'text-slate-400');
            });

            // Add active style to selected card
            element.classList.add('role-active');
            element.querySelector('.icon-box').classList.replace('bg-slate-100', 'bg-amber-500');
            element.querySelector('.icon-box').classList.replace('text-slate-400', 'text-white');

            saveRememberedLogin();
        }

        function selectRememberedRole(role) {
            const roleCard = document.querySelector(`[data-role="${role}"]`);

            if (roleCard) {
                selectRole(role, roleCard);
            }
        }

        function loadRememberedLogin() {
            let remembered = null;

            try {
                remembered = JSON.parse(localStorage.getItem(rememberKey) || 'null');
            } catch (error) {
                localStorage.removeItem(rememberKey);
            }

            if (!remembered) {
                return;
            }

            rememberInput.checked = true;
            emailInput.value = remembered.email || '';
            passwordInput.value = remembered.password || '';

            if (remembered.role) {
                selectRememberedRole(remembered.role);
            }
        }

        function saveRememberedLogin() {
            if (!rememberInput.checked) {
                localStorage.removeItem(rememberKey);
                return;
            }

            localStorage.setItem(rememberKey, JSON.stringify({
                email: emailInput.value,
                password: passwordInput.value,
                role: currentRole,
            }));
        }

        function showRolePopup() {
            rolePopup.classList.remove('hidden');
            rolePopup.classList.add('flex');

            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('role-needed');
                void card.offsetWidth;
                card.classList.add('role-needed');
            });
        }

        function closeRolePopup() {
            rolePopup.classList.add('hidden');
            rolePopup.classList.remove('flex');
            document.querySelector('.role-card').focus();
        }

        rolePopup.addEventListener('click', function(e) {
            if (e.target === rolePopup) {
                closeRolePopup();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !rolePopup.classList.contains('hidden')) {
                closeRolePopup();
            }
        });

        document.getElementById('loginForm').onsubmit = function(e) {
            if (!currentRole) {
                e.preventDefault();
                showRolePopup();
                return;
            }

            saveRememberedLogin();
        };

        rememberInput.addEventListener('change', function() {
            if (!rememberInput.checked) {
                localStorage.removeItem(rememberKey);
                return;
            }

            saveRememberedLogin();
        });

        emailInput.addEventListener('input', function() {
            saveRememberedLogin();
        });

        passwordInput.addEventListener('input', function() {
            saveRememberedLogin();
        });

        loadRememberedLogin();
    </script>
</body>
</html>
