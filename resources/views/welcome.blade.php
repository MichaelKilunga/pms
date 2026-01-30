<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ env('SKYPUSH_API_KEY') }}" name="api-key">
    <title>{{ env('APP_NAME') }}</title>
    <meta content="{{ csrf_token() }}" name="csrf-token">

    {{-- META CONFIG --}}
    <meta
        content="Pharmacy Management System (PILLPOINTONE) is a comprehensive solution for managing pharmacies, including inventory, sales, and customer management."
        name="description">
    <meta
        content="Online Pharmacy, Pharmacy Management System, Mfumo wa Pharmacy Tanzania, Mfumo wa Pharmacy Morogoro, DLDM system, Mfumo wa duka la dawa, Hospital Management, Dawa, Mauzo ya Dawa, Pharmacy Software, Pharmacy POS System, Pharmacy Inventory Management, Medicine Sales System, Drug Store Software, Mfumo wa Hospitali, Pharmacy Accounting System, Dawa za Hospitali, Maduka ya Dawa Tanzania, Medical Store Management, Digital Pharmacy System, Pharmaceutical Management System, E-Pharmacy Tanzania, Pharmacy Sales Tracking, Pharmacy Stock Management, Mfumo wa Mauzo ya Dawa, Pharmacy Billing Software, Tanzania Pharmacy Software, Healthcare Management System, Mfumo wa Afya Tanzania, Pharmacy Dar es Salaam, Pharmacy Dodoma, Pharmacy Arusha, Pharmacy Mwanza, Pharmacy Mbeya, Pharmacy Morogoro, Pharmacy Zanzibar, Pharmacy Kilimanjaro, Pharmacy Tanga, Mfumo wa Mauzo ya Madawa, Pharmacy ERP System, Pharmacy Cloud Software, Retail Pharmacy Management, Hospital and Pharmacy System, Medicine Distribution Software, Mfumo wa Usimamizi wa Dawa, Tanzania E-Health System, Health Information System Tanzania, Pharmacy Business Software, Pharmacy Store Tanzania, Medical Shop Software, Maduka ya dawa Morogoro, Mfumo wa duka la dawa Tanzania, Digital Health Tanzania, Pharmacy Management, POS, Inventory, Sales, Customer Management, Multi-Store, Cloud Integration"
        name="keywords">
    <meta content="SKYLINK SOLUTIONS" name="author">
    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- //bootstrap 5 link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- jQuery script --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            <h1 class="display-4 fw-bold">Welcome to Pharmacy Management System (PILLPOINTONE)</h1>
            <p class="lead">A powerful solution for managing your Pharmacies</p>
            @auth
                <a class="btn btn-primary btn-lg me-2 mt-3" href="{{ route('dashboard') }}">Go to Dashboard</a>
            @else
                {{-- onclick="return (confirm('Still under maintanance!') && false)" --}}
                <a class="btn btn-primary btn-lg me-2 mt-3" href="{{ route('register') }}"><i
                        class="bi bi-person-plus"></i> Become Agent</a>
                <a class="btn btn-outline-light btn-lg mt-3" href="{{ route('login') }}"><i
                        class="bi bi-box-arrow-in-right"></i> Login</a>
            @endauth
            <button class="btn btn-primary btn-lg mt-3" data-bs-target="#contactModal" data-bs-toggle="modal"
                type="button"><i class="bi bi-chat"></i> Request Trial</button>
        </div>
        <div class="scroll-indicator" onclick="scrollToContent()">⬇ Scroll to Learn More</div>
    </div>

    <!-- Features Section -->
    <section class="features bg-light py-5" id="features">
        <div class="container text-center">
            <div class="flex shrink-0 items-center">
            </div>
            <h2 class="fw-bold text-primary mb-5">Why Choose Our System?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-cart-check text-danger fs-4 me-2"></i>Point of Sale (POS)
                                Integration
                            </h5>
                            <p class="card-text text-muted">Facilitates seamless billing and checkout processes,
                                Tracks
                                sales transactions and generates receipts, Supports multiple payment methods (cash,
                                Mobile payments).</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-bar-chart-line text-danger fs-4 me-2"></i>Reporting and Analytics
                            </h5>
                            <p class="card-text text-muted">Provides real-time sales, profit, and inventory reports,
                                Analyzes customer buying patterns for targeted marketing, Generates custom reports
                                for
                                business insights.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-box-seam text-danger fs-4 me-2"></i>Expiry and Batch Tracking
                            </h5>
                            <p class="card-text text-muted">Ensures tracking of medicines based on batch numbers and
                                expiry dates, Flags products nearing expiry for timely action, Helps prevent the
                                sale of
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
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-shop text-danger fs-4 me-2"></i>Multi-Store Management
                            </h5>
                            <p class="card-text text-muted">Synchronizes data across multiple pharmacy locations,
                                Centralizes inventory, sales, and financial records, Facilitates inter-branch stock
                                transfers.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-archive text-danger fs-4 me-2"></i>Inventory Management
                            </h5>
                            <p class="card-text text-muted">Tracks stock levels of medicines, Sends alerts for low
                                stock
                                and expiry dates, Supports batch tracking for accurate inventory handling.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success d-flex align-items-center">
                                <i class="bi bi-cloud-upload text-danger fs-4 me-2"></i>Cloud Integration
                            </h5>
                            <p class="card-text text-muted">Allows access to the system remotely, Ensures secure
                                storage
                                of sensitive data, Facilitates system updates and backups automatically.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Quick contact links --}}
    <div class="mt-2 text-center">
        <a class="btn btn-success btn-sm"
            href="https://wa.me/{{ config('support.whatsapp', env('SUPPORT_WHATSAPP')) }}?text={{ urlencode('Hello, I need help with...') }}"
            target="_blank">WhatsApp</a>
        <a class="btn btn-outline-secondary btn-sm"
            href="mailto:{{ config('support.email', env('SUPPORT_EMAIL')) }}">{{ config('support.email', env('SUPPORT_EMAIL')) }}</a>
        <a class="btn btn-outline-secondary btn-sm" href="{{ config('support.website', env('SUPPORT_WEBSITE')) }}"
            target="_blank">Website</a>
        {{-- normal call --}}
        <a class="btn btn-outline-secondary btn-sm"
            href="tel:{{ config('support.phone', env('SUPPORT_PHONE')) }}">Call</a>
    </div>

    <!-- Contact Us Modal -->
    <div aria-hidden="true" aria-labelledby="contactModalLabel" class="modal fade" id="contactModal" role="dialog"
        tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="contactForm">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="contactModalLabel">Contact Us</h5>
                        <span aria-label="Close" class="close" data-bs-dismiss="modal"
                            onmouseover="this.style.cursor='pointer'">
                            <span aria-hidden="true">&times;</span>
                        </span>
                    </div>

                    <div class="modal-body">

                        <div class="form-group" style="display:none !important;">
                            <input autocomplete="off" class="form-control" name="nickname" type="text"
                                value="" />
                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-6">
                                <label>Full Name</label>
                                <input class="form-control" name="name" required type="text"
                                    placeholder="John Doe" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email Address</label>
                                <input class="form-control" name="email" required type="email"
                                    placeholder="john@example.com" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input class="form-control" name="phone" required type="text" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Location</label>
                                <input class="form-control" name="location" required type="text" />
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label>Service</label>
                            <select class="form-control" name="service" required>
                                <option value="">Select a service</option>
                                <option value="pharmacy">Pharmacy Management System</option>
                                <option value="api">API's</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <label>Describe your issue</label>
                            <textarea class="form-control" name="message" placeholder="I need to upgrade package..." required rows="4"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-primary" id="contactSubmitBtn" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast (position fixed bottom-right) -->
    <div aria-atomic="true" aria-live="polite" style="position: fixed; bottom: 1rem; right: 1rem; z-index: 1080;">
        <div class="toast" data-delay="6000" id="contactToast" style="min-width: 250px;">
            <div class="toast-header">
                <strong class="mr-auto">Contact</strong>
                <small class="text-muted">now</small>
                <button class="close mb-1 ml-2" data-dismiss="toast" type="button">&times;</button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <!-- Subscription Plans Section -->
    {{-- CHeck if is user logged in and is agent --}}
    {{-- @if (Auth::check() && Auth::user()->role == 'agent') --}}
    <section class="subscription-plans bg-light py-5" id="plans">
        <div class="container">
            <h2 class="fw-bold text-primary mb-5 text-center">Transparent Pricing Calculator</h2>

            <!-- Pricing Mode Switcher (Removed for single active mode) -->

            <div class="tab-content" id="pricingTabContent">

                @php $activeMode = $systemSettings['pricing_mode'] ?? 'standard'; @endphp

                <!-- Standard Packages -->
                @if ($activeMode == 'standard')
                    <div class="tab-pane fade show active" id="standard" role="tabpanel">
                        <div class="row g-4 justify-content-center">
                            @foreach ($packages as $pkg)
                                <div class="col-md-4">
                                    <div class="card h-100 border-0 shadow-sm hover-scale transition-all">
                                        <div class="card-header bg-transparent border-0 pt-4 text-center">
                                            <h4 class="fw-bold text-primary">{{ $pkg->name }}</h4>
                                            <h2 class="display-6 fw-bold my-3">TZS {{ number_format($pkg->price) }}
                                            </h2>
                                            <span
                                                class="badge bg-light text-dark rounded-pill border">{{ $pkg->duration }}
                                                Days</span>
                                        </div>
                                        <div class="card-body p-4">
                                            <ul class="list-unstyled mb-4">
                                                <li class="mb-2"><i
                                                        class="bi bi-check-circle-fill text-success me-2"></i>{{ $pkg->number_of_pharmacies }}
                                                    Pharmacy</li>
                                                <li class="mb-2"><i
                                                        class="bi bi-check-circle-fill text-success me-2"></i>{{ $pkg->number_of_users }}
                                                    Staff Accounts</li>
                                                <li class="mb-2"><i
                                                        class="bi bi-check-circle-fill text-success me-2"></i>Real-time
                                                    Analytics</li>
                                            </ul>
                                            <div class="d-grid">
                                                <a href="{{ route('register') }}"
                                                    class="btn btn-outline-primary rounded-pill">Choose
                                                    {{ $pkg->name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Dynamic Pricing -->
                @if ($activeMode == 'dynamic')
                    <div class="tab-pane fade show active" id="dynamic" role="tabpanel">
                        <div class="card border-0 shadow-lg mx-auto" style="max-width: 800px;">
                            <div class="card-body p-5">
                                <h4 class="text-center mb-4">Calculate Your Dynamic Rate</h4>
                                <p class="text-muted text-center mb-4">Pay based on your business size. Fair and
                                    transparent.</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Number of Items</label>
                                        <input type="number" class="form-control" id="dynamicItems"
                                            placeholder="e.g. 500">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Number of Staff</label>
                                        <input type="number" class="form-control" id="dynamicStaff"
                                            placeholder="e.g. 2">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Number of Branches</label>
                                        <input type="number" class="form-control" id="dynamicBranches"
                                            placeholder="e.g. 1">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="fw-bold mb-2">Add-ons</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="addonWhatsapp">
                                                <label class="form-check-label" for="addonWhatsapp">WhatsApp
                                                    Alerts</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="addonSms">
                                                <label class="form-check-label" for="addonSms">SMS Alerts</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-light p-4 rounded-3 mt-4 text-center">
                                    <span class="d-block text-muted small text-uppercase fw-bold">Estimated Monthly
                                        Cost</span>
                                    <h2 class="fw-bold text-primary mb-0">TZS <span id="dynamicTotal">0</span></h2>
                                    <small class="text-muted fst-italic mt-2 d-block">*Final price may vary based on
                                        exact usage</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Profit Share -->
                @if ($activeMode == 'profit_share')
                    <div class="tab-pane fade show active" id="profit" role="tabpanel">
                        <div class="card border-0 shadow-lg mx-auto" style="max-width: 600px;">
                            <div class="card-body p-5 text-center">
                                <i class="bi bi-graph-up-arrow display-4 text-success mb-3"></i>
                                <h4 class="mb-3">Profit Share Model</h4>
                                <p class="text-muted mb-4">We grow when you grow. Pay a small percentage of your
                                    monthly profit.</p>

                                <div class="mb-4 text-start">
                                    <label class="form-label fw-bold">Estimated Monthly Profit (TZS)</label>
                                    <input type="number" class="form-control form-control-lg" id="profitInput"
                                        placeholder="Enter amount...">
                                </div>

                                <div
                                    class="d-flex justify-content-between align-items-center bg-light p-3 rounded mb-3">
                                    <span>Platform Fee Rate:</span>
                                    <span
                                        class="fw-bold">{{ $systemSettings['profit_share_percentage'] ?? 5 }}%</span>
                                </div>

                                <div class="bg-primary text-white p-4 rounded-3 mt-4">
                                    <span class="d-block opacity-75 small text-uppercase fw-bold">Your Estimated
                                        Fee</span>
                                    <h2 class="fw-bold mb-0">TZS <span id="profitTotal">0</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- Pass PHP settings to JS -->
        <div id="calculator-settings" data-rate-item="{{ $systemSettings['dynamic_rate_per_item'] ?? 100 }}"
            data-rate-staff="{{ $systemSettings['dynamic_rate_per_staff'] ?? 5000 }}"
            data-rate-branch="{{ $systemSettings['dynamic_rate_per_branch'] ?? 20000 }}"
            data-profit-share="{{ $systemSettings['profit_share_percentage'] ?? 5 }}">
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const settings = document.getElementById('calculator-settings').dataset;
            const rateItem = parseFloat(settings.rateItem);
            const rateStaff = parseFloat(settings.rateStaff);
            const rateBranch = parseFloat(settings.rateBranch);
            const profitSharePct = parseFloat(settings.profitShare);

            // Dynamic Calculator
            function calculateDynamic() {
                const items = parseInt(document.getElementById('dynamicItems').value) || 0;
                const staff = parseInt(document.getElementById('dynamicStaff').value) || 0;
                const branches = parseInt(document.getElementById('dynamicBranches').value) || 0;

                let total = (items * rateItem) + (staff * rateStaff) + (branches * rateBranch);

                // Add-ons (Dummy values for demo if not in settings)
                if (document.getElementById('addonWhatsapp').checked) total += 5000;
                if (document.getElementById('addonSms').checked) total += 10000;

                document.getElementById('dynamicTotal').textContent = new Intl.NumberFormat().format(total);
            }

            ['dynamicItems', 'dynamicStaff', 'dynamicBranches', 'addonWhatsapp', 'addonSms'].forEach(id => {
                document.getElementById(id)?.addEventListener('input', calculateDynamic);
                document.getElementById(id)?.addEventListener('change', calculateDynamic);
            });

            // Profit Share Calculator
            function calculateProfit() {
                const profit = parseFloat(document.getElementById('profitInput').value) || 0;
                const fee = (profit * profitSharePct) / 100;
                document.getElementById('profitTotal').textContent = new Intl.NumberFormat().format(fee);
            }

            document.getElementById('profitInput')?.addEventListener('input', calculateProfit);
        });
    </script>


    <style>
        .calculator-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .calculator-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
    </style>

    <!-- Call to Action Section -->
    <section class="py-5 text-center">
        <div class="container">
            <h3 class="mb-4">Join thousands of businesses growing with Our System!</h3>
            <a class="btn btn-success btn-lg" href="{{ route('register') }}"
                onclick="return (confirm('Still under maintanance!') && false)">Become Agent</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2024 {{ env('APP_NAME') }}. All rights reserved.</p>
            <p>Built by <a class="text-light" href="https://skylinksolutions.co.tz">SKYLINK SOLUTIONS</a></p>
        </div>
    </footer>

    <!-- AJAX + JS -->
    <script>
        $(document).ready(function() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-API-KEY': $('meta[name="api-key"]').attr('content'),
                }
            });

            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var btn = $('#contactSubmitBtn');

                btn.prop('disabled', true);
                btn.text('Sending...');

                $.ajax({
                    url: '/contact-us',
                    method: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.status === 'success') {
                            alert(response.message ||
                                'Message sent. We will contact you shortly.');

                            // reset form fields
                            form[0].reset();

                            // hide modal (Bootstrap)
                            $('#contactModal').modal('hide');
                        } else {
                            // API returned a 200 with a non-success status
                            alert((response && response.message) ? response.message :
                                'An error occurred. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        var msg = 'An error occurred. Please try again.';
                        if (xhr && xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                                msg = xhr.responseJSON.errors[firstKey][0];
                            }
                        } else if (xhr && xhr.statusText) {
                            msg = xhr.statusText;
                        }

                        alert(msg);
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Send');
                    }
                });
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function scrollToContent() {
            const featuresSection = document.getElementById('features');
            featuresSection.scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Price Calculator Logic
        $(document).ready(function() {
            // Fetch dynamic rates from DB
            const systemRate =
                {{ \App\Models\SystemSetting::where('key', 'system_use_rate')->value('value') ?? 100 }};
            // Divisor logic removed as per new formula

            function calculatePrice(n, rate) {
                if (!n || n < 0) return 0;
                // New Formula: n * rate
                return n * rate;
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-TZ').format(amount);
            }

            // System Use Calculator
            $('#systemItems').on('input keyup change', function() {
                let val = $(this).val();
                let n = val ? parseInt(val) : 0;
                let price = calculatePrice(n, systemRate);
                $('#systemPrice').text(formatCurrency(price));
            });

            // Stocking Price Calculator (Rate updated to 250)
            $('#stockingItems').on('input keyup change', function() {
                let val = $(this).val();
                let n = val ? parseInt(val) : 0;
                let price = calculatePrice(n, 250);
                $('#stockingPrice').text(formatCurrency(price));
            });
        });
    </script>

    <!-- Add these CDN links (put in head/footer as needed) -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script> --}}

</body>

</html>
