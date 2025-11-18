<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- META CONFIG --}}
    <meta name="description" content="Pharmacy Management System (PILLPOINTONE) is a comprehensive solution for managing pharmacies, including inventory, sales, and customer management.">
    <meta name="keywords" content="Online Pharmacy, Pharmacy Management System, Mfumo wa Pharmacy Tanzania, Mfumo wa Pharmacy Morogoro, DLDM system, Mfumo wa duka la dawa, Hospital Management, Dawa, Mauzo ya Dawa, Pharmacy Software, Pharmacy POS System, Pharmacy Inventory Management, Medicine Sales System, Drug Store Software, Mfumo wa Hospitali, Pharmacy Accounting System, Dawa za Hospitali, Maduka ya Dawa Tanzania, Medical Store Management, Digital Pharmacy System, Pharmaceutical Management System, E-Pharmacy Tanzania, Pharmacy Sales Tracking, Pharmacy Stock Management, Mfumo wa Mauzo ya Dawa, Pharmacy Billing Software, Tanzania Pharmacy Software, Healthcare Management System, Mfumo wa Afya Tanzania, Pharmacy Dar es Salaam, Pharmacy Dodoma, Pharmacy Arusha, Pharmacy Mwanza, Pharmacy Mbeya, Pharmacy Morogoro, Pharmacy Zanzibar, Pharmacy Kilimanjaro, Pharmacy Tanga, Mfumo wa Mauzo ya Madawa, Pharmacy ERP System, Pharmacy Cloud Software, Retail Pharmacy Management, Hospital and Pharmacy System, Medicine Distribution Software, Mfumo wa Usimamizi wa Dawa, Tanzania E-Health System, Health Information System Tanzania, Pharmacy Business Software, Pharmacy Store Tanzania, Medical Shop Software, Maduka ya dawa Morogoro, Mfumo wa duka la dawa Tanzania, Digital Health Tanzania, Pharmacy Management, POS, Inventory, Sales, Customer Management, Multi-Store, Cloud Integration">
    <meta name="author" content="SKYLINK SOLUTIONS">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

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
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg me-2 mt-3">Go to Dashboard</a>
            @else
            {{-- onclick="return (confirm('Still under maintanance!') && false)" --}}
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2 mt-3"><i class="bi bi-person-plus"></i> Become Agent</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg mt-3"><i class="bi bi-box-arrow-in-right"></i> Login</a>
            @endauth
            <button type="button" class="btn btn-primary btn-lg mt-3"  data-bs-toggle="modal" data-bs-target="#contactModal"><i class="bi bi-chat"></i> Request Trial</button>
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


    {{-- Quick contact links --}}
    <div class="mt-2 text-center">
        <a class="btn btn-success btn-sm" target="_blank" href="https://wa.me/{{ config('support.whatsapp', env('SUPPORT_WHATSAPP')) }}?text={{ urlencode('Hello, I need help with...') }}">WhatsApp</a>
        <a class="btn btn-outline-secondary btn-sm" href="mailto:{{ config('support.email', env('SUPPORT_EMAIL')) }}">{{ config('support.email', env('SUPPORT_EMAIL')) }}</a>
        <a class="btn btn-outline-secondary btn-sm" href="{{ config('support.website', env('SUPPORT_WEBSITE')) }}" target="_blank">Website</a>
        {{-- normal call --}}
        <a class="btn btn-outline-secondary btn-sm" href="tel:{{ config('support.phone', env('SUPPORT_PHONE')) }}">Call</a>
    </div>

    <!-- Contact Us Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="contactForm">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="contactModalLabel">Contact Us</h5>
                        <span class="close" onmouseover="this.style.cursor='pointer'" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </span>
                    </div>

                    <div class="modal-body">
                        
                        <div class="form-group" style="display:none !important;">
                            <input type="text" name="nickname" value="" class="form-control" autocomplete="off" />
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input name="phone" required type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Location</label>
                                <input name="location" type="text" required class="form-control" />
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label>Service</label>
                            <select name="service" class="form-control" required>
                                <option value="">Select a service</option>
                                <option value="pharmacy">Pharmacy Management System</option>
                                <option value="api">API's</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <label>Describe your issue</label>
                            <textarea name="message" rows="4" class="form-control" required placeholder="I need to upgrade package..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="contactSubmitBtn" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast (position fixed bottom-right) -->
    <div aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 1rem; right: 1rem; z-index: 1080;">
    <div id="contactToast" class="toast" data-delay="6000" style="min-width: 250px;">
        <div class="toast-header">
        <strong class="mr-auto">Contact</strong>
        <small class="text-muted">now</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
        </div>
        <div class="toast-body"></div>
    </div>
    </div>

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
        <section hidden class="hidden py-5">
            <div class="container">
                <h2 class="text-center mb-4 text-primary h3">Our Agents</h2>
                {{-- make the row scrollable horizontally --}}
                <div class="row flex-nowrap overflow-auto">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body d-flex">
                                <img src="{{ asset(App\Models\User::where('id', 1)->first()->profile_photo_path ? 'storage/' . App\Models\User::where('id', 1)->first()->profile_photo_path : 'storage/default.png') }}"
                                    alt="Picture" class="img-fluid rounded-circle mb-3"
                                    style="width: 100px; height: 100px;">
                                <div>
                                    <h5 class="card-title">{{ App\Models\User::where('id', 1)->first()->name }}
                                    </h5>
                                    <p class="card-text">
                                        {{ App\Models\User::where('id', 1)->first()->phone }}<br>
                                        <span
                                            class="text-primary">{{ App\Models\User::where('id', 1)->first()->email }}</span>
                                        <br> Clients:
                                        {{ App\Models\User::where('id', 1)->first()->agent->count() }}
                                        {{-- put button to whatsApp chat with this agent --}}
                                        <a href="https://wa.me/{{ App\Models\User::where('id', 1)->first()->phone }}"
                                            class="btn btn-success btn-sm"><i class="bi bi-chat" ></i> Chat</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($agents as $agent)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body d-flex">
                                    <img src="{{ asset($agent->profile_photo_path ? 'storage/' . $agent->profile_photo_path : 'storage/default.png') }}"
                                        alt="Picture" class="img-fluid rounded-circle mb-3"
                                        style="width: 100px; height: 100px;">
                                    <div>
                                        <h5 class="card-title">{{ $agent->name }}</h5>
                                        <p class="card-text">{{ $agent->phone }} <br>
                                            <span class="text-primary">{{ $agent->email }}</span>
                                            <br> Clients: {{ $agent->agent->count() }}
                                            {{-- put button to whatsApp chat with this agent --}}
                                            <a href="https://wa.me/{{ $agent->phone }}" target="_blank"
                                                class="btn btn-success btn-sm"><i class="bi bi-chat" ></i> Chat</a>
                                        </p>
                                    </div>
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
            <a onclick="return (confirm('Still under maintanance!') && false)" href="{{ route('register') }}" class="btn btn-success btn-lg">Become Agent</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2024 {{ env('APP_NAME') }}. All rights reserved.</p>
            <p>Built by <a href="https://skylinksolutions.co.tz" class="text-light">SKYLINK SOLUTIONS</a></p>
        </div>
    </footer>

    <!-- AJAX + JS -->
    <script>
        $(document).ready(function () {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#contactForm').on('submit', function (e) {
                e.preventDefault();

                var form = $(this);
                var btn  = $('#contactSubmitBtn');

                btn.prop('disabled', true);
                btn.text('Sending...');

                $.ajax({
                    url: '/contact-us',
                    method: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response && response.status === 'success') {
                            alert(response.message || 'Message sent. We will contact you shortly.');

                            // reset form fields
                            form[0].reset();

                            // hide modal (Bootstrap)
                                $('#contactModal').modal('hide');
                        } else {
                            // API returned a 200 with a non-success status
                            alert((response && response.message) ? response.message : 'An error occurred. Please try again.');
                        }
                    },
                    error: function (xhr) {
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
                    complete: function () {
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
    </script>


    <!-- Add these CDN links (put in head/footer as needed) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script> --}}

</body>

</html>
