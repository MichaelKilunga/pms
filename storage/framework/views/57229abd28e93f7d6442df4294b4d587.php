<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="<?php echo e(env('SKYPUSH_API_KEY')); ?>" name="api-key">
    <title><?php echo e(env('APP_NAME')); ?></title>
    <meta content="<?php echo e(csrf_token()); ?>" name="csrf-token">

    
    <meta
        content="Pharmacy Management System (PILLPOINTONE) is a comprehensive solution for managing pharmacies, including inventory, sales, and customer management."
        name="description">
    <meta
        content="Online Pharmacy, Pharmacy Management System, Mfumo wa Pharmacy Tanzania, Mfumo wa Pharmacy Morogoro, DLDM system, Mfumo wa duka la dawa, Hospital Management, Dawa, Mauzo ya Dawa, Pharmacy Software, Pharmacy POS System, Pharmacy Inventory Management, Medicine Sales System, Drug Store Software, Mfumo wa Hospitali, Pharmacy Accounting System, Dawa za Hospitali, Maduka ya Dawa Tanzania, Medical Store Management, Digital Pharmacy System, Pharmaceutical Management System, E-Pharmacy Tanzania, Pharmacy Sales Tracking, Pharmacy Stock Management, Mfumo wa Mauzo ya Dawa, Pharmacy Billing Software, Tanzania Pharmacy Software, Healthcare Management System, Mfumo wa Afya Tanzania, Pharmacy Dar es Salaam, Pharmacy Dodoma, Pharmacy Arusha, Pharmacy Mwanza, Pharmacy Mbeya, Pharmacy Morogoro, Pharmacy Zanzibar, Pharmacy Kilimanjaro, Pharmacy Tanga, Mfumo wa Mauzo ya Madawa, Pharmacy ERP System, Pharmacy Cloud Software, Retail Pharmacy Management, Hospital and Pharmacy System, Medicine Distribution Software, Mfumo wa Usimamizi wa Dawa, Tanzania E-Health System, Health Information System Tanzania, Pharmacy Business Software, Pharmacy Store Tanzania, Medical Shop Software, Maduka ya dawa Morogoro, Mfumo wa duka la dawa Tanzania, Digital Health Tanzania, Pharmacy Management, POS, Inventory, Sales, Customer Management, Multi-Store, Cloud Integration"
        name="keywords">
    <meta content="SKYLINK SOLUTIONS" name="author">
    <!-- Favicon -->
    <link href="<?php echo e(asset('favicon.ico')); ?>" rel="shortcut icon" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    
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
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo e(asset('images/background/1.jpg')); ?>');
            }

            /*33% {*/
            /*    background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo e(asset('images/background/2.jpg')); ?>');*/
            /*}*/
            66% {
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo e(asset('images/background/3.jpg')); ?>');
            }

            100% {
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo e(asset('images/background/1.jpg')); ?>');
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
    

    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Pharmacy Management System (PILLPOINTONE)</h1>
            <p class="lead">A powerful solution for managing your Pharmacies</p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <a class="btn btn-primary btn-lg me-2 mt-3" href="<?php echo e(route('dashboard')); ?>">Go to Dashboard</a>
            <?php else: ?>
                
                <a class="btn btn-primary btn-lg me-2 mt-3" href="<?php echo e(route('register')); ?>"><i
                        class="bi bi-person-plus"></i> Become Agent</a>
                <a class="btn btn-outline-light btn-lg mt-3" href="<?php echo e(route('login')); ?>"><i
                        class="bi bi-box-arrow-in-right"></i> Login</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

    
    <div class="mt-2 text-center">
        <a class="btn btn-success btn-sm"
            href="https://wa.me/<?php echo e(config('support.whatsapp', env('SUPPORT_WHATSAPP'))); ?>?text=<?php echo e(urlencode('Hello, I need help with...')); ?>"
            target="_blank">WhatsApp</a>
        <a class="btn btn-outline-secondary btn-sm"
            href="mailto:<?php echo e(config('support.email', env('SUPPORT_EMAIL'))); ?>"><?php echo e(config('support.email', env('SUPPORT_EMAIL'))); ?></a>
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(config('support.website', env('SUPPORT_WEBSITE'))); ?>"
            target="_blank">Website</a>
        
        <a class="btn btn-outline-secondary btn-sm"
            href="tel:<?php echo e(config('support.phone', env('SUPPORT_PHONE'))); ?>">Call</a>
    </div>

    <!-- Contact Us Modal -->
    <div aria-hidden="true" aria-labelledby="contactModalLabel" class="modal fade" id="contactModal" role="dialog"
        tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="contactForm">
                    <?php echo csrf_field(); ?>
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
    
    
    <section class="subscription-plans" id="plans">
        <div class="container">
            <h2 class="fw-bold text-primary mb-5 text-center">Estimate Your Price</h2>

            <div class="row justify-content-center g-4">
                <!-- System Use Price Calculator -->
                <div class="col-md-5">
                    <div class="card h-100 calculator-card border-0 shadow-lg">
                        <div class="card-header bg-primary py-4 text-center text-white">
                            <h4 class="mb-0"><i class="bi bi-laptop me-2"></i>System Use</h4>
                            <small class="opacity-75">Monthly Subscription</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary" for="systemItems">Number of Items
                                    / Medicines</label>
                                <input class="form-control form-control-lg border-primary shadow-sm" id="systemItems"
                                    min="0" placeholder="e.g. 500" type="number">
                                <small class="text-muted">Enter the total number of distinct items in your
                                    pharmacy.</small>
                            </div>
                            <hr class="my-4">
                            <div class="text-center">
                                <p class="text-secondary mb-1">Estimated Price</p>
                                <h2 class="fw-bold text-success mb-0">Tshs. <span id="systemPrice">0</span></h2>
                                <small class="text-muted">/ Month</small>
                            </div>
                            <div class="d-grid mt-4 gap-2">
                                <a class="btn btn-primary btn-lg mt-3" data-bs-target="#contactModal"
                                    data-bs-toggle="modal" type="button">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stocking Price Calculator -->
                <div class="col-md-5">
                    <div class="card h-100 calculator-card border-0 shadow-lg">
                        <div class="card-header bg-success py-4 text-center text-white">
                            <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Stocking Service</h4>
                            <small class="opacity-75">One-time / Service Fee</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary" for="stockingItems">Number of
                                    Items / Medicines</label>
                                <input class="form-control form-control-lg border-success shadow-sm"
                                    id="stockingItems" min="0" placeholder="e.g. 500" type="number">
                                <small class="text-muted">Enter the total number of items to be stocked.</small>
                            </div>
                            <hr class="my-4">
                            <div class="text-center">
                                <p class="text-secondary mb-1">Estimated Price</p>
                                <h2 class="fw-bold text-primary mb-0">Tshs. <span id="stockingPrice">0</span></h2>
                                <small class="text-muted">Total Cost</small>
                            </div>
                            <div class="d-grid mt-4 gap-2">
                                <a class="btn btn-success btn-lg mt-3" data-bs-target="#contactModal"
                                    data-bs-toggle="modal" type="button">Request Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            <a class="btn btn-success btn-lg" href="<?php echo e(route('register')); ?>"
                onclick="return (confirm('Still under maintanance!') && false)">Become Agent</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2024 <?php echo e(env('APP_NAME')); ?>. All rights reserved.</p>
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
                <?php echo e(\App\Models\SystemSetting::where('key', 'system_use_rate')->value('value') ?? 100); ?>;
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
    
    

</body>

</html>
<?php /**PATH D:\DEVELOPMENT\pms\resources\views/welcome.blade.php ENDPATH**/ ?>