<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            /*background-color: #f8f9fa;*/
            background-color: white;
        }

        .hero {
            color: white;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            animation: slideshow 30s infinite;
            z-index: -1;
        }

        .scroll-indicator {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 18px;
            color: white;
            animation: bounce 2s infinite;
            cursor: pointer;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translate(-50%, 0);
            }

            50% {
                transform: translate(-50%, 10px);
            }
        }

        @keyframes slideshow {
            0% {
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/background/1.jpg') }}');
            }

            /*33% {*/
            /*    background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/background/2.jpg') }}');*/
            /*}*/
            66% {
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/background/3.jpg') }}');
            }

            100% {
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/background/1.jpg') }}');
            }
        }

        .features {
            padding: 60px 0;
        }

        .subscription-plans {
            background-color: #f8f9fa;
            padding: 60px 0;
        }

        .subscription-plans .card {
            transition: transform 0.3s;
        }

        .subscription-plans .card:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- TAWK START --}}
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/67810ca9af5bfec1dbe9b008/1ih81itsi';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    {{-- TAWK END --}}


    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Pharmacy Management System (PILLPOINT)</h1>
            <p class="lead">A powerful solution for managing your Pharmacies</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg me-2">Go to Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Get Started</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
            @endauth
        </div>
        <div class="scroll-indicator" onclick="scrollToContent()">⬇ Scroll to Learn More</div>
    </div>

    <!-- Features Section -->
    <section class="features bg-light py-5" id="features">
        <div class="container text-center">
            <div class="shrink-0 flex items-center">
            </div>
            <h2 class="mb-5 fw-bold text-primary">Why Choose Our System?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-cart-check text-danger me-2 fs-4"></i>Point of Sale (POS) Integration
                            </h5>
                            <p class="card-text text-muted">Facilitates seamless billing and checkout processes, Tracks
                                sales transactions and generates receipts, Supports multiple payment methods (cash,
                                Mobile payments).</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-bar-chart-line text-danger me-2 fs-4"></i>Reporting and Analytics
                            </h5>
                            <p class="card-text text-muted">Provides real-time sales, profit, and inventory reports,
                                Analyzes customer buying patterns for targeted marketing, Generates custom reports for
                                business insights.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-box-seam text-danger me-2 fs-4"></i>Expiry and Batch Tracking
                            </h5>
                            <p class="card-text text-muted">Ensures tracking of medicines based on batch numbers and
                                expiry dates, Flags products nearing expiry for timely action, Helps prevent the sale of
                                expired medicines.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- VIDEO -->
            {{-- <div class="mt-4">
                    <iframe 
                        width="560" 
                        height="315" 
                        src="https://www.youtube.com/embed/YOUR_VIDEO_ID" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                    </iframe>
                </div> --}}


            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-shop text-danger me-2 fs-4"></i>Multi-Store Management
                            </h5>
                            <p class="card-text text-muted">Synchronizes data across multiple pharmacy locations,
                                Centralizes inventory, sales, and financial records, Facilitates inter-branch stock
                                transfers.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-archive text-danger me-2 fs-4"></i>Inventory Management
                            </h5>
                            <p class="card-text text-muted">Tracks stock levels of medicines, Sends alerts for low stock
                                and expiry dates, Supports batch tracking for accurate inventory handling.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-cloud-upload text-danger me-2 fs-4"></i>Cloud Integration
                            </h5>
                            <p class="card-text text-muted">Allows access to the system remotely, Ensures secure storage
                                of sensitive data, Facilitates system updates and backups automatically.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Subscription Plans Section -->
    {{-- CHeck if is user logged in and is agent --}}
    @if (Auth::check() && Auth::user()->role == 'agent')
        <section class="subscription-plans" id="plans">
            <div class="container">
                <h2 class="mb-4 text-center">Our Subscription Plans</h2>

                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('register', ['package_id' => 1]) }}" class="btn btn-primary">Start</a>
                            <h5 class="mb-0 me-2 px-2">Trial Plan free 14 Days!</h5>
                        </div>
                    </div>
                </div>


                <div class="row g-4">
                    <div class="col-md-12 text-center">
                        <hr />
                    </div>
                </div>

                <div class="row g-4">

                    @foreach ($packages as $package)
                        <div class="col-md-3">
                            <div
                                class="card h-100 border-{{ $loop->iteration % 2 == 0 ? 'primary' : 'success' }} border-{{ $loop->iteration % 3 == 0 ? 'danger' : 'success#' }} border-{{ $loop->iteration == 4 ? 'warning' : 'success#' }}">
                                <div
                                    class="card-header bg-{{ $loop->iteration % 2 == 0 ? 'primary' : 'success' }} bg-{{ $loop->iteration % 3 == 0 ? 'danger' : 'success#' }} bg-{{ $loop->iteration == 4 ? 'warning' : 'success#' }} text-white text-center">
                                    <h5>{{ $package->name }}</h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title" style="color: green;">Tshs.
                                        {{ number_format($package->price) }}
                                        / Month</h6>
                                    <p class="card-text text-center">{{ $package->description }}</p>
                                    <ul class="list-unstyled ">
                                        <li>✔ {{ $package->number_of_pharmacists }} pharmacist per 1 Pharmacy</li>
                                        <li>✔ {{ $package->number_of_owner_accounts }} Owner account</li>
                                        <li>✔ {{ $package->number_of_admin_accounts }} Admin account</li>
                                        <li>✔ {{ $package->number_of_pharmacies }} pharmacy</li>
                                        <li>✔ {{ $package->number_of_medicines }} Medicines per Pharmacy</li>
                                        <li>✔ In App Notification </li>
                                        @if ($package->email_notification)
                                            <li>✔ Email Notification </li>
                                        @endif
                                        @if ($package->sms_notifications)
                                            <li>✔ SMS Notifications </li>
                                        @endif
                                        @if ($package->whatsapp_chat)
                                            <li>✔ WhatsApp Chat </li>
                                        @endif
                                        @if ($package->reports)
                                            <li>✔ Sales Reporting </li>
                                        @endif
                                        @if ($package->analytics)
                                            <li>✔ Sales Analytics </li>
                                        @endif
                                        @if ($package->receipts)
                                            <li>✔ Receipts </li>
                                        @endif
                                        @if ($package->stock_management)
                                            <li>✔ Stocks Management </li>
                                        @endif
                                        @if ($package->staff_management)
                                            <li>✔ Staffs Management </li>
                                        @endif
                                        @if ($package->stock_transfer)
                                            <li>✔ Stocks Transfer </li>
                                        @endif
                                        <li>✔ Free Online support</li>
                                        <li>✔ Works on PC, Mac, mobile and Tablet</li>
                                    </ul>
                                </div>
                                <div class="text-center m-2">
                                    <a href="{{ route('register', ['package_id' => $package->id]) }}"
                                        class="btn btn-{{ $loop->iteration % 2 == 0 ? 'primary' : 'success' }} btn-{{ $loop->iteration == 4 ? 'warning' : 'success#' }} btn-{{ $loop->iteration % 3 == 0 ? 'danger' : 'success#' }}  text-{{ $loop->iteration == 4 ? 'light' : 'dark#' }}  ">Choose
                                        Plan</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        {{-- Display our agents, show their profile picture, name, phone and email,   in a row scrolling horizontally --}}
        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-4">Our Agents</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <img src="{{ asset('storage/' . App\Models\User::where('id',1)->first()->profile_photo_path) }}"
                                    alt="Picture" class="img-fluid rounded-circle mb-3"
                                    style="width: 100px; height: 100px;">
                                <h5 class="card-title">{{ App\Models\User::where('id',1)->first()->name }}</h5>
                                <p class="card-text">{{ App\Models\User::where('id',1)->first()->phone }}</p>
                                <p class="card-text">{{ App\Models\User::where('id',1)->first()->email }}</p>
                                {{-- show number of  clients --}}
                                <p class="card-text">Number of Clients: {{ App\Models\User::where('id',1)->first()->agent->count() }}</p>
                            </div>
                        </div>
                    </div>
                    @foreach ($agents as $agent)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{ asset('storage/' . $agent->profile_photo_path) }}"
                                        alt="Picture" class="img-fluid rounded-circle mb-3"
                                        style="width: 100px; height: 100px;">
                                    <h5 class="card-title">{{ $agent->name }}</h5>
                                    <p class="card-text">{{ $agent->phone }}</p>
                                    <p class="card-text">{{ $agent->email }}</p>
                                    {{-- show number of  clients --}}
                                    <p class="card-text">Number of Clients: {{ $agent->agent->count() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Call to Action Section -->
    <section class="py-5 text-center">
        <div class="container">
            <h3 class="mb-4">Join thousands of businesses growing with Our System!</h3>
            <a href="{{ route('register') }}" class="btn btn-success btn-lg">Sign Up Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2024 {{ env('APP_NAME') }}. All rights reserved.</p>
            <p>Built by <a href="https://skylinksolutions.com" class="text-light">SKYLINK SOLUTIONS</a></p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function scrollToContent() {
            const featuresSection = document.getElementById('features');
            featuresSection.scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>
</body>

</html>
