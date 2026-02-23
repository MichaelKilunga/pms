@section('title', 'Join PILLPOINT')
@section('meta_description', 'Register for PILLPOINT Pharmacy Management System. Manage your pharmacy with ease or become an agent.')
@section('meta_keywords', 'Register, Pharmacy Management, Agent Registration, PILLPOINT')

<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
            --primary-glow: #4f46e5;
            --secondary-glow: #06b6d4;
            --text-main: #1e293b;
        }

        .dark {
            --glass-bg: rgba(15, 23, 42, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top left, #eef2ff, #f0f9ff);
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        .dark body {
            background: radial-gradient(circle at top left, #0f172a, #020617);
        }

        .bg-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
            filter: blur(100px);
        }

        .mesh-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 40%;
            height: 40%;
            background: var(--primary-glow);
            border-radius: 50%;
            animation: move 20s infinite alternate;
        }

        .mesh-2 {
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 40%;
            height: 40%;
            background: var(--secondary-glow);
            border-radius: 50%;
            animation: move 25s infinite alternate-reverse;
        }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(100px, 100px) scale(1.2); }
        }

        .reg-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .reg-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 1100px;
            display: flex;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .reg-info {
            flex: 1;
            padding: 3rem;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(6, 182, 212, 0.1));
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--text-main);
        }

        @media (max-width: 992px) {
            .reg-info { display: none; }
            .reg-card { max-width: 500px; }
        }

        .reg-form-section {
            flex: 1.2;
            padding: 3rem;
        }

        .form-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .form-subtitle {
            color: #64748b;
            margin-bottom: 2rem;
        }

        .input-group {
            margin-bottom: 1.25rem;
        }

        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .custom-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 0.75rem;
            transition: all 0.2s;
            color: var(--text-main);
        }

        .dark .custom-input {
            background: rgba(15, 23, 42, 0.5);
        }

        .custom-input:focus {
            outline: none;
            border-color: var(--primary-glow);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: rgba(255, 255, 255, 0.8);
        }

        .custom-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
        }

        .reg-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white;
            font-weight: 600;
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .reg-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.4);
            filter: brightness(1.1);
        }

        .reg-btn:active {
            transform: translateY(0);
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-link:hover {
            color: var(--primary-glow);
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .feature-icon {
            background: white;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-right: 1rem;
            color: var(--primary-glow);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .feature-text h4 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .feature-text p {
            font-size: 0.875rem;
            opacity: 0.8;
            line-height: 1.5;
        }
        
        .role-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .role-option {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--glass-border);
            border-radius: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        
        .role-option input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .role-option.active {
            border-color: var(--primary-glow);
            background: rgba(79, 70, 229, 0.05);
        }
        
        .role-option i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #94a3b8;
        }
        
        .role-option.active i {
            color: var(--primary-glow);
        }
        
        .role-option label {
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="bg-mesh">
        <div class="mesh-1"></div>
        <div class="mesh-2"></div>
    </div>

    <div class="reg-container">
        <div class="reg-card">
            <!-- Left Side: Information -->
            <div class="reg-info">
                <div class="mb-10">
                    <x-authentication-card-logo />
                </div>
                <h1 class="text-4xl font-bold mb-6 tracking-tight">Streamline Your Pharmacy with PILLPOINT</h1>
                
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="feature-text">
                        <h4>Real-time Analytics</h4>
                        <p>Track sales, inventory, and profits with intuitive dashboards and automated reports.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-boxes-stacked"></i></div>
                    <div class="feature-text">
                        <h4>Smart Inventory</h4>
                        <p>Never run out of stock with intelligent low-stock alerts and expiration tracking.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-users-gear"></i></div>
                    <div class="feature-text">
                        <h4>Agent Network</h4>
                        <p>Join our growing network of agents across Tanzania and manage multiple pharmacies.</p>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-sm opacity-60">© {{ date('Y') }} PILLPOINT. Built for modern pharmacy management.</p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="reg-form-section">
                <div class="text-center lg:hidden mb-8">
                    <x-authentication-card-logo />
                </div>
                
                <h2 class="form-title">Join Today</h2>
                <p class="form-subtitle">Create your account to get started with your 14-day free trial.</p>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-group">
                        <label class="input-label" for="name">Full Name</label>
                        <input id="name" class="custom-input" type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe" />
                    </div>

                    <div class="input-group">
                        <label class="input-label">Register As</label>
                        <div class="role-selector">
                            <div class="role-option {{ old('role', 'owner') == 'owner' ? 'active' : '' }}" onclick="selectRole('owner')">
                                <input type="radio" name="role" id="role-owner" value="owner" {{ old('role', 'owner') == 'owner' ? 'checked' : '' }}>
                                <i class="fas fa-hospital-user"></i>
                                <label for="role-owner">Pharmacy Owner</label>
                            </div>
                            <div class="role-option {{ old('role') == 'agent' ? 'active' : '' }}" onclick="selectRole('agent')">
                                <input type="radio" name="role" id="role-agent" value="agent" {{ old('role') == 'agent' ? 'checked' : '' }}>
                                <i class="fas fa-user-tie"></i>
                                <label for="role-agent">Agent</label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="input-group">
                            <label class="input-label" for="email">Email Address</label>
                            <input id="email" class="custom-input" type="email" name="email" :value="old('email')" required placeholder="john@example.com" />
                        </div>

                        <div class="input-group">
                            <label class="input-label" for="phone_number">Phone Number</label>
                            <input id="phone_number" class="custom-input" type="tel" name="phone_number" :value="old('phone_number')" required placeholder="07XXXXXXXX" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="input-group">
                            <label class="input-label" for="password">Password</label>
                            <input id="password" class="custom-input" type="password" name="password" required placeholder="••••••••" />
                        </div>

                        <div class="input-group">
                            <label class="input-label" for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" class="custom-input" type="password" name="password_confirmation" required placeholder="••••••••" />
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <label class="flex items-center cursor-pointer">
                                <x-checkbox name="terms" id="terms" required class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline hover:text-indigo-600">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline hover:text-indigo-600">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </span>
                            </label>
                        </div>
                    @endif

                    <button type="submit" class="reg-btn">
                        Create Account
                    </button>

                    <a class="login-link" href="{{ route('login') }}">
                        Already have an account? <span class="font-semibold text-indigo-600">Sign in</span>
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectRole(role) {
            const ownerOption = document.querySelector('.role-option:first-child');
            const agentOption = document.querySelector('.role-option:last-child');
            const ownerRadio = document.getElementById('role-owner');
            const agentRadio = document.getElementById('role-agent');

            if (role === 'owner') {
                ownerOption.classList.add('active');
                agentOption.classList.remove('active');
                ownerRadio.checked = true;
            } else {
                agentOption.classList.add('active');
                ownerOption.classList.remove('active');
                agentRadio.checked = true;
            }
        }
    </script>
</x-guest-layout>
