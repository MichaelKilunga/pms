<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ env('SKYPUSH_API_KEY') }}" name="api-key">
    <title>PILLPOINTONE | Next-Gen Pharmacy Management</title>
    <meta content="{{ csrf_token() }}" name="csrf-token">

    {{-- SEO CONFIG --}}
    <meta content="PILLPOINTONE (PILLPOINTONE) is Tanzania's leading cloud-based pharmacy management system. Manage inventory, sales, and staff across multiple branches with ease." name="description">
    <meta content="Pharmacist Software Tanzania, Medicine Inventory, Pharmacy POS, Mfumo wa Pharmacy, DLDM Tanzania, PillPointone" name="keywords">
    <meta content="SKYLINK SOLUTIONS" name="author">
    
    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --bg-light: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --glass: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text-dark);
            background-color: #fff;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .fw-bold {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        p, .text-muted {
            font-family: 'Inter', sans-serif;
        }

        /* Navbar Style */
        .navbar {
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            transition: all 0.3s;
        }

        .navbar-brand svg, .footer-logo svg {
            height: 45px;
            width: auto;
        }

        .navbar-brand img {
            height: 40px;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-dark) !important;
            padding: 0.5rem 1.25rem !important;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            /* padding: 160px 0 100px; */
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.1), transparent),
                        radial-gradient(circle at bottom left, rgba(6, 182, 212, 0.1), transparent);
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            line-height: 1.1;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #1e293b 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 600px;
            margin-bottom: 2.5rem;
        }

        .btn-premium {
            padding: 0.8rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: white;
            border: none;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
        }

        .btn-primary-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 30px -10px rgba(79, 70, 229, 0.5);
            color: white;
        }

        .hero-image-wrap {
            position: relative;
            z-index: 1;
        }

        .hero-image-wrap img {
            border-radius: 2rem;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.25);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Stats Section */
        .stats-wrap {
            padding: 40px;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-top: -60px;
            z-index: 10;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0;
        }

        /* Feature Cards */
        .feature-card {
            padding: 2.5rem;
            border-radius: 1.5rem;
            background: white;
            border: 1px solid #f1f5f9;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }

        .feature-card:hover .icon-box {
            transform: rotateY(180deg);
        }

        /* Section Header */
        .section-header {
            max-width: 700px;
            margin: 0 auto 4rem;
            text-align: center;
        }

        /* Pricing Section */
        .pricing-section {
            background-color: var(--bg-light);
            padding: 100px 0;
            border-radius: 4rem 4rem 0 0;
        }

        /* Calculator Styles */
        .calc-card {
            background: white;
            border-radius: 2rem;
            padding: 3rem;
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .calc-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        /* FAQs */
        .accordion-item {
            border: none;
            margin-bottom: 1rem;
            border-radius: 1rem !important;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .accordion-button {
            padding: 1.5rem;
            font-weight: 600;
            background: white !important;
        }

        .accordion-button:not(.collapsed) {
            color: var(--primary);
            box-shadow: none;
        }

        /* Footer */
        .footer-top {
            background: #0f172a;
            color: #94a3b8;
            padding: 80px 0 50px;
        }

        .footer-bottom {
            background: #020617;
            padding: 25px 0;
            color: #475569;
            font-size: 0.875rem;
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            margin-bottom: 0.75rem;
        }

        .footer-link:hover {
            color: white;
        }

        .social-link {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            color: white;
            margin-right: 0.75rem;
            transition: all 0.3s;
        }

        .social-link:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        /* Responsiveness */
        @media (max-width: 991.98px) {
            .hero-section { padding: 120px 0 60px; text-align: center; }
            .hero-subtitle { margin: 0 auto 2rem; }
            .hero-image-wrap { margin-top: 4rem; }
            .stats-wrap { margin-top: 0; margin-bottom: 3rem; }
        }
    </style>
</head>

<body>

    {{-- TAWK Chat --}}
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true; s1.src = 'https://embed.tawk.to/67810ca9af5bfec1dbe9b008/1ih81itsi';
            s1.charset = 'UTF-8'; s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <x-authentication-card-logo />
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#plans">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#contactModal">Support</a></li>
                </ul>
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-premium btn-primary-gradient">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-premium btn-link text-decoration-none text-dark">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-premium btn-primary-gradient">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 fw-bold">Cloud-Based Pharmacy ERP</span>
                    <h1 class="hero-title">The Smart way to manage your Pharmacy.</h1>
                    <p class="hero-subtitle">PILLPOINTONE streamlines your workflow, automates your inventory, and maximizes your profits with powerful, easy-to-use tools.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn btn-premium btn-primary-gradient btn-lg">Start Free Trial <i class="fas fa-arrow-right ms-2 fs-small"></i></a>
                        <button class="btn btn-premium btn-outline-dark btn-lg" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-play-circle me-2"></i> Book a Demo
                        </button>
                    </div>
                    <div class="mt-4 d-flex align-items-center gap-2 text-muted small">
                        <div class="d-flex -space-x-2">
                             <i class="fas fa-star text-warning"></i>
                             <i class="fas fa-star text-warning"></i>
                             <i class="fas fa-star text-warning"></i>
                             <i class="fas fa-star text-warning"></i>
                             <i class="fas fa-star text-warning"></i>
                        </div>
                        <span>Trusted by 500+ Pharamcies across Tanzania</span>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="hero-image-wrap px-4">
                        <img src="{{ asset('images/background/1.jpg') }}" alt="PillPointone Dashboard" class="img-fluid">
                        <div class="position-absolute bottom-0 start-0 translate-middle-y ms-2 bg-white p-3 rounded-4 shadow-lg d-none d-md-block" data-aos="zoom-in" data-aos-delay="400">
                             <div class="d-flex align-items-center gap-3">
                                 <div class="bg-success bg-opacity-10 text-success p-2 rounded-3"><i class="fas fa-arrow-up"></i></div>
                                 <div>
                                     <div class="fw-bold">+24%</div>
                                     <div class="small text-muted">Monthly Growth</div>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Section -->
    <div class="container">
        <div class="stats-wrap" data-aos="fade-up">
            <div class="row text-center g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3>100+</h3>
                        <p class="text-muted mb-0">Active Stores</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3>50k+</h3>
                        <p class="text-muted mb-0">Products Managed</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3>99.9%</h3>
                        <p class="text-muted mb-0">Server Uptime</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <h3>24/7</h3>
                        <p class="text-muted mb-0">Expert Support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Heading -->
    <section class="py-5 mt-5" id="features">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="display-5 fw-800">Everything you need to grow your Pharmacy business.</h2>
                <p class="text-muted lead">No more manual tracking. Get full visibility into your operations from anywhere, anytime.</p>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Lightning Fast POS</h4>
                        <p class="text-muted">Process sales in seconds, handle multiple payment methods, and print professional receipts instantly.</p>
                        <ul class="list-unstyled small mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> Offline mode support</li>
                            <li><i class="fas fa-check text-success me-2"></i> Mobile/Tablet compatible</li>
                        </ul>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Smart Inventory</h4>
                        <p class="text-muted">Automated stock tracking with low-stock alerts, expiration dates monitoring, and batch management.</p>
                        <ul class="list-unstyled small mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> Real-time stock alerts</li>
                            <li><i class="fas fa-check text-success me-2"></i> Multi-branch syncing</li>
                        </ul>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Advanced Analytics</h4>
                        <p class="text-muted">Deep insights into your sales performance, top products, and profit margins with beautiful charts.</p>
                        <ul class="list-unstyled small mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> Daily profit tracking</li>
                            <li><i class="fas fa-check text-success me-2"></i> Custom PDF reports</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Expanded Features -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="feature-card d-flex gap-4">
                        <div class="icon-box bg-info bg-opacity-10 text-info flex-shrink-0">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div>
                             <h4 class="fw-bold mb-2">Secure & Regular Backups</h4>
                             <p class="text-muted">Your data is encrypted and backed up every hour. No more worrying about server crashes or data loss.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="feature-card d-flex gap-4">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger flex-shrink-0">
                            <i class="fas fa-mobile-screen"></i>
                        </div>
                        <div>
                             <h4 class="fw-bold mb-2">Manage on the Go</h4>
                             <p class="text-muted">Whether you are at home or traveling, monitor your pharmacy performance via your smartphone in real-time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section" id="plans">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="text-primary fw-bold text-uppercase mb-2 d-block">Pricing Plans</span>
                <h2 class="display-5 fw-800">Choose the best for your business.</h2>
                <p class="text-muted lead">Transparent, flexible pricing for pharmacies of all sizes.</p>
            </div>

            <div class="tab-content" id="pricingTabContent">
                @php $activeMode = $systemSettings['pricing_mode'] ?? 'standard'; @endphp

                <!-- Standard Packages -->
                @if ($activeMode == 'standard')
                    <div class="row g-4 justify-content-center">
                        @foreach ($packages as $pkg)
                            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="card border-0 shadow-lg pricing-card h-100 rounded-4 overflow-hidden {{ $loop->iteration == 2 ? 'border-primary border-2' : '' }}">
                                    @if($loop->iteration == 2)
                                        <div class="bg-primary text-white text-center py-2 small fw-bold">MOST POPULAR</div>
                                    @endif
                                    <div class="card-body p-5">
                                        <div class="text-center mb-4">
                                            <h4 class="fw-bold text-dark">{{ $pkg->name }}</h4>
                                            <div class="my-4">
                                                <span class="display-5 fw-800 text-primary">TZS {{ number_format($pkg->price) }}</span>
                                                <span class="text-muted">/{{ $pkg->duration }} Days</span>
                                            </div>
                                        </div>
                                        <ul class="list-unstyled mb-5">
                                            <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle text-success me-3"></i><span>{{ $pkg->number_of_pharmacies }} Pharmacy Store</span></li>
                                            <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle text-success me-3"></i><span>{{ $pkg->number_of_users }} Staff Accounts</span></li>
                                            <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle text-success me-3"></i><span>Full Inventory Access</span></li>
                                            <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle text-success me-3"></i><span>Daily Sales Reports</span></li>
                                        </ul>
                                        <div class="d-grid">
                                            <a href="{{ route('register', ['pkg' => $pkg->id]) }}" class="btn btn-premium {{ $loop->iteration == 2 ? 'btn-primary-gradient' : 'btn-outline-primary' }}">Choose Plan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Dynamic Pricing Calculator -->
                @if ($activeMode == 'dynamic')
                    <div class="calc-card mx-auto" data-aos="zoom-in">
                        <div class="row align-items-center">
                            <div class="col-lg-7">
                                <h3 class="fw-bold mb-4">Calculate Your Custom Rate</h3>
                                <p class="text-muted mb-5">Pay based on your exact business size. Scale up or down as you grow.</p>
                                
                                <div class="row g-4">
                                    <div class="col-md-4">
                                         <label class="form-label fw-600">Medicines <small class="text-primary">(TZS {{ number_format($systemSettings['dynamic_rate_per_item'] ?? 100) }}/ea)</small></label>
                                         <div class="input-group">
                                             <span class="input-group-text bg-light border-0"><i class="fas fa-pills text-primary"></i></span>
                                             <input type="number" class="form-control bg-light border-0" id="dynamicItems" placeholder="e.g. 500">
                                         </div>
                                     </div>
                                     <div class="col-md-4">
                                         <label class="form-label fw-600">Staff <small class="text-primary">(TZS {{ number_format($systemSettings['dynamic_rate_per_staff'] ?? 5000) }}/ea)</small></label>
                                         <div class="input-group">
                                             <span class="input-group-text bg-light border-0"><i class="fas fa-users text-primary"></i></span>
                                             <input type="number" class="form-control bg-light border-0" id="dynamicStaff" placeholder="e.g. 2">
                                         </div>
                                     </div>
                                     <div class="col-md-4">
                                         <label class="form-label fw-600">Branches <small class="text-primary">(TZS {{ number_format($systemSettings['dynamic_rate_per_branch'] ?? 20000) }}/ea)</small></label>
                                         <div class="input-group">
                                             <span class="input-group-text bg-light border-0"><i class="fas fa-store text-primary"></i></span>
                                             <input type="number" class="form-control bg-light border-0" id="dynamicBranches" placeholder="e.g. 1">
                                         </div>
                                     </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-600">Add Premium Features</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            <div class="form-check custom-check p-0">
                                                <input type="checkbox" class="btn-check" id="addonWhatsapp" autocomplete="off">
                                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="addonWhatsapp"><i class="fab fa-whatsapp me-1"></i>WhatsApp ({{ number_format($systemSettings['upgrade_rate_whatsapp'] ?? 5000) }})</label>
                                            </div>
                                            <div class="form-check custom-check p-0">
                                                <input type="checkbox" class="btn-check" id="addonSms" autocomplete="off">
                                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="addonSms"><i class="fas fa-sms me-1"></i>SMS ({{ number_format($systemSettings['upgrade_rate_sms'] ?? 10000) }})</label>
                                            </div>
                                            <div class="form-check custom-check p-0">
                                                <input type="checkbox" class="btn-check" id="addonReports" autocomplete="off">
                                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="addonReports"><i class="fas fa-file-invoice me-1"></i>Reports ({{ number_format($systemSettings['upgrade_rate_reports'] ?? 15000) }})</label>
                                            </div>
                                            <div class="form-check custom-check p-0">
                                                <input type="checkbox" class="btn-check" id="addonStockManagement" autocomplete="off">
                                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="addonStockManagement"><i class="fas fa-boxes me-1"></i>Stock Mgmt ({{ number_format($systemSettings['upgrade_rate_stock_management'] ?? 10000) }})</label>
                                            </div>
                                            <div class="form-check custom-check p-0">
                                                <input type="checkbox" class="btn-check" id="addonStockTransfer" autocomplete="off">
                                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="addonStockTransfer"><i class="fas fa-exchange-alt me-1"></i>Transfers ({{ number_format($systemSettings['upgrade_rate_stock_transfer'] ?? 10000) }})</label>
                                            </div>
                                            <div class="form-check custom-check p-0">
                                                <input type="checkbox" class="btn-check" id="addonStaffManagement" autocomplete="off">
                                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="addonStaffManagement"><i class="fas fa-users-cog me-1"></i>Staff Mgmt ({{ number_format($systemSettings['upgrade_rate_staff_management'] ?? 5000) }})</label>
                                            </div>
                                            <div class="form-check custom-check p-0">
                                                <input type="checkbox" class="btn-check" id="addonReceipts" autocomplete="off">
                                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="addonReceipts"><i class="fas fa-print me-1"></i>Receipts ({{ number_format($systemSettings['upgrade_rate_receipts'] ?? 5000) }})</label>
                                            </div>
                                            <div class="form-check custom-check p-0">
                                                <input type="checkbox" class="btn-check" id="addonAnalytics" autocomplete="off">
                                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="addonAnalytics"><i class="fas fa-chart-line me-1"></i>Analytics ({{ number_format($systemSettings['upgrade_rate_analytics'] ?? 15000) }})</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 text-center mt-5 mt-lg-0">
                                <div class="bg-light bg-opacity-5 p-5 rounded-4 border border-dashed border-primary">
                                    <span class="text-muted small text-uppercase fw-bold ls-1">Estimated Monthly Cost</span>
                                    <div class="my-3">
                                        <span class="display-6 fw-800 text-primary">TZS <span id="dynamicTotal">0</span></span>
                                    </div>
                                    <a href="{{ route('register') }}" class="btn btn-premium btn-primary-gradient w-100 mt-3">Start Now</a>
                                    <p class="mt-4 small text-muted">*Final price may vary based on exact usage and configurations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if ($activeMode == 'profit_share')
                    <div class="calc-card mx-auto" style="max-width: 800px;" data-aos="zoom-in">
                         <div class="text-center mb-5">
                             <div class="icon-box bg-success bg-opacity-10 text-success mx-auto"><i class="fas fa-handshake"></i></div>
                             <h3 class="fw-bold mt-3">Profit Share Partnership</h3>
                             <p class="text-muted">Our success is tied to yours. We only win when you grow.</p>
                         </div>
                         <div class="row justify-content-center">
                             <div class="col-md-8">
                                 <label class="form-label fw-600">Expected Monthly Profit</label>
                                 <div class="input-group mb-4">
                                     <span class="input-group-text bg-light border-0">TZS</span>
                                     <input type="number" class="form-control form-control-lg bg-light border-0" id="profitInput" placeholder="1,000,000">
                                 </div>
                                 <div class="d-flex justify-content-between text-muted mb-4 px-2">
                                     <span>Platform Service Fee:</span>
                                     <span class="fw-bold text-dark">{{ $systemSettings['profit_share_percentage'] ?? 5 }}%</span>
                                 </div>
                                 <div class="bg-dark text-white p-4 rounded-4 d-flex justify-content-between align-items-center">
                                     <span>Your Estimated Platform Fee:</span>
                                     <span class="h4 fw-bold mb-0">TZS <span id="profitTotal">0</span></span>
                                 </div>
                                 <div class="text-center mt-5">
                                      <a href="{{ route('register') }}" class="btn btn-premium btn-primary-gradient px-5">Get Started Today</a>
                                 </div>
                             </div>
                         </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div id="calculator-settings" 
            data-rate-item="{{ $systemSettings['dynamic_rate_per_item'] ?? 100 }}"
            data-rate-staff="{{ $systemSettings['dynamic_rate_per_staff'] ?? 5000 }}"
            data-rate-branch="{{ $systemSettings['dynamic_rate_per_branch'] ?? 20000 }}"
            data-profit-share="{{ $systemSettings['profit_share_percentage'] ?? 5 }}"
            data-rate-whatsapp="{{ $systemSettings['upgrade_rate_whatsapp'] ?? 5000 }}"
            data-rate-sms="{{ $systemSettings['upgrade_rate_sms'] ?? 10000 }}"
            data-rate-reports="{{ $systemSettings['upgrade_rate_reports'] ?? 15000 }}"
            data-rate-stock-management="{{ $systemSettings['upgrade_rate_stock_management'] ?? 10000 }}"
            data-rate-stock-transfer="{{ $systemSettings['upgrade_rate_stock_transfer'] ?? 10000 }}"
            data-rate-staff-management="{{ $systemSettings['upgrade_rate_staff_management'] ?? 5000 }}"
            data-rate-receipts="{{ $systemSettings['upgrade_rate_receipts'] ?? 5000 }}"
            data-rate-analytics="{{ $systemSettings['upgrade_rate_analytics'] ?? 15000 }}">
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-100" id="faq">
        <div class="container py-5">
            <div class="section-header" data-aos="fade-up">
                <h2 class="display-5 fw-800">Frequently Asked Questions</h2>
                <p class="text-muted lead">Quick answers to common questions about PILLPOINTONE.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item" data-aos="fade-up">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                                    Is my data safe in the cloud?
                                </button>
                            </h2>
                            <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Yes, absolutely. We use industry-standard SSL encryption and host our servers on secure, multi-region cloud infrastructure with regular backups to ensure your business data is never lost.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" data-aos="fade-up">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                                    Can I manage multiple shops with one account?
                                </button>
                            </h2>
                            <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Yes! PILLPOINTONE is designed for multi-location management. You can see real-time performance and inventory for all your branches from a single unified dashboard.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" data-aos="fade-up">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                                    Do you provide training for staff?
                                </button>
                            </h2>
                            <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    We offer comprehensive onboarding and training sessions for you and your staff. Additionally, our 24/7 support team is always ready to help with any questions.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-5">
        <div class="container mb-5">
             <div class="bg-primary bg-opacity-10 p-5 rounded-5 text-center" data-aos="zoom-in">
                  <h2 class="display-6 fw-800 mb-4">Ready to automate your Pharmacy?</h2>
                  <p class="lead text-muted mb-5">Join 500+ successful pharmacies already using PILLPOINTONE today.</p>
                  <div class="d-flex justify-content-center gap-3">
                       <a href="{{ route('register') }}" class="btn btn-premium btn-primary-gradient btn-lg px-5">Start 14-Day Free Trial</a>
                  </div>
             </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-4">
                        <div class="footer-logo">
                            <x-authentication-card-logo />
                        </div>
                        <p class="mt-4 mb-4">Empowering pharmacies across Tanzania with smart, automated, and reliable cloud management solutions.</p>
                        <div class="d-flex">
                             <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                             <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                             <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                             <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <h5 class="text-white fw-bold mb-4">Product</h5>
                        <a href="#features" class="footer-link">Features</a>
                        <a href="#plans" class="footer-link">Pricing</a>
                        <a href="#" class="footer-link">Mobile App</a>
                        <a href="#" class="footer-link">Integrations</a>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <h5 class="text-white fw-bold mb-4">Company</h5>
                        <a href="#" class="footer-link">About Us</a>
                        <a href="#" class="footer-link">Contact</a>
                        <a href="#" class="footer-link">Privacy Policy</a>
                        <a href="#" class="footer-link">Terms of Use</a>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <h5 class="text-white fw-bold mb-4">Support</h5>
                        <p class="small text-muted# mb-3"><i class="fas fa-envelope me-2"></i> {{ config('mail.from.address') }}</p>
                        <p class="small text-muted# mb-3"><i class="fas fa-phone me-2"></i> {{ config('mail.from.address') }}</p>
                        <p class="small text-muted#"><i class="fas fa-location-dot me-2"></i> {{ config('mail.from.address') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container text-center">
                <p class="mb-0">© {{ date('Y') }} PILLPOINT. Developed with ❤️ by <a href="https://skylinksolutions.co.tz" class="text-muted fw-bold">SKYLINK SOLUTIONS</a></p>
            </div>
        </div>
    </footer>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                <form id="contactForm">
                    @csrf
                    <div class="modal-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-5 bg-primary text-white p-5 d-flex flex-column justify-content-center">
                                <h3 class="fw-bold mb-4">Get in Touch</h3>
                                <p class="opacity-75 mb-5">Have questions? Fill out the form and our team will get back to you within 24 hours.</p>
                                <div class="mb-4">
                                     <div class="small opacity-75">WhatsApp</div>
                                     <div class="fw-bold"><a href="https://wa.me/255742177328" class="text-white text-decoration-none">+255 742 177 328</a></div>
                                </div>
                                <div>
                                     <div class="small opacity-75">Email</div>
                                     <div class="fw-bold"><a href="mailto:pillpointone1@gmail.com" class="text-white text-decoration-none">pillpointone1@gmail.com</a></div>
                                </div>
                            </div>
                            <div class="col-lg-7 p-5">
                                <input type="text" name="nickname" style="display:none !important;" tabindex="-1" autocomplete="off">
                                <div class="mb-4">
                                     <label class="form-label small fw-bold">Full Name</label>
                                     <input type="text" name="name" class="form-control bg-light border-0 py-2" required placeholder="John Doe">
                                </div>
                                <div class="row mb-4">
                                     <div class="col-6">
                                          <label class="form-label small fw-bold">Email</label>
                                          <input type="email" name="email" class="form-control bg-light border-0 py-2" required placeholder="john@example.com">
                                     </div>
                                     <div class="col-6">
                                          <label class="form-label small fw-bold">Phone</label>
                                          <input type="text" name="phone" class="form-control bg-light border-0 py-2" required placeholder="07XXXXXXXX">
                                     </div>
                                </div>
                                <div class="mb-4">
                                     <label class="form-label small fw-bold">Service Interested In</label>
                                     <select name="service" class="form-select bg-light border-0 py-2" required>
                                         <option value="pharmacy">Pharmacy Management System</option>
                                         <option value="api">API Solutions</option>
                                         <option value="other">Other Inquiry</option>
                                     </select>
                                </div>
                                <div class="mb-4">
                                     <label class="form-label small fw-bold">Message</label>
                                     <textarea name="message" class="form-control bg-light border-0 py-2" rows="4" required placeholder="Tell us more about your pharmacy..."></textarea>
                                </div>
                                <div class="d-grid mt-4">
                                     <button type="submit" class="btn btn-premium btn-primary-gradient py-3" id="contactSubmitBtn">Send Message</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-out-cubic'
        });

        $(document).ready(function() {
            // Calculator Settings
            const settings = document.getElementById('calculator-settings').dataset;
            const rateItem = parseFloat(settings.rateItem);
            const rateStaff = parseFloat(settings.rateStaff);
            const rateBranch = parseFloat(settings.rateBranch);
            const profitSharePct = parseFloat(settings.profitShare);

            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-TZ').format(amount);
            }

            // Dynamic Calculator
            function calculateDynamic() {
                const items = parseInt($('#dynamicItems').val()) || 0;
                const staff = parseInt($('#dynamicStaff').val()) || 0;
                const branches = parseInt($('#dynamicBranches').val()) || 0;
                
                let total = (items * rateItem) + (staff * rateStaff) + (branches * rateBranch);

                if ($('#addonWhatsapp').is(':checked')) total += parseFloat(settings.rateWhatsapp);
                if ($('#addonSms').is(':checked')) total += parseFloat(settings.rateSms);
                if ($('#addonReports').is(':checked')) total += parseFloat(settings.rateReports);
                if ($('#addonStockManagement').is(':checked')) total += parseFloat(settings.rateStockManagement);
                if ($('#addonStockTransfer').is(':checked')) total += parseFloat(settings.rateStockTransfer);
                if ($('#addonStaffManagement').is(':checked')) total += parseFloat(settings.rateStaffManagement);
                if ($('#addonReceipts').is(':checked')) total += parseFloat(settings.rateReceipts);
                if ($('#addonAnalytics').is(':checked')) total += parseFloat(settings.rateAnalytics);

                $('#dynamicTotal').text(formatCurrency(total));
            }

            $('#dynamicItems, #dynamicStaff, #dynamicBranches, #addonWhatsapp, #addonSms, #addonReports, #addonStockManagement, #addonStockTransfer, #addonStaffManagement, #addonReceipts, #addonAnalytics').on('input change', calculateDynamic);

            // Profit Share Calculator
            function calculateProfit() {
                const profit = parseFloat($('#profitInput').val()) || 0;
                const fee = (profit * profitSharePct) / 100;
                $('#profitTotal').text(formatCurrency(fee));
            }

            $('#profitInput').on('input', calculateProfit);

            // AJAX Contact Form
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#contactSubmitBtn');
                btn.prop('disabled', true).text('Sending...');

                $.ajax({
                    url: '/contact-us',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message || 'Thank you! Your message has been sent.');
                        $('#contactModal').modal('hide');
                        $('#contactForm')[0].reset();
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Send Message');
                    }
                });
            });
        });
    </script>
</body>
</html>
