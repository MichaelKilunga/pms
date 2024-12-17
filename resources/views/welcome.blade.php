<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your System - Business and Client Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
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
            0%, 100% {
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
            33% {
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/background/2.jpg') }}');
            }
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

    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Pharmacy Management System</h1>
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
    <section class="features bg-light" id="features">
        <div class="container text-center">
            <h2 class="mb-4">Why Choose Our System?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Efficient Client Management</h5>
                            <p class="card-text">Manage your clients and services easily in one platform.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Comprehensive Analytics</h5>
                            <p class="card-text">Gain valuable insights into your business operations.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Flexible Solutions</h5>
                            <p class="card-text">Tailored to suit industries like healthcare, real estate, and more.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Subscription Plans Section -->
    <section class="subscription-plans" id="plans">
        <div class="container text-center">
            <h2 class="mb-4">Our Subscription Plans</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5>Basic Plan</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title text-success">10,000Tsh/month</h6>
                            <p class="card-text">Perfect for startups and small businesses.</p>
                            <ul class="list-unstyled">
                                <li>✔ Client Management</li>
                                <li>✔ Basic Analytics</li>
                                <li>✔ Email Support</li>
                            </ul>
                            <a href="#" class="btn btn-primary">Choose Plan</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-success">
                        <div class="card-header bg-success text-white">
                            <h5>Pro Plan</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title text-success">20,000Tsh/month</h6>
                            <p class="card-text">For growing businesses with advanced needs.</p>
                            <ul class="list-unstyled">
                                <li>✔ Everything in Basic</li>
                                <li>✔ Advanced Analytics</li>
                                <li>✔ Priority Support</li>
                            </ul>
                            <a href="#" class="btn btn-success">Choose Plan</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5>Enterprise Plan</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title text-success">30,000Tsh/month</h6>
                            <p class="card-text">Best for large organizations with custom needs.</p>
                            <ul class="list-unstyled">
                                <li>✔ Everything in Pro</li>
                                <li>✔ Custom Integrations</li>
                                <li>✔ Dedicated Support</li>
                            </ul>
                            <a href="#" class="btn btn-warning">Choose Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            <p class="mb-0">&copy; 2024 Your System. All rights reserved.</p>
            <p>Built by <a href="https://yourwebsite.com" class="text-light">Your Company</a></p>
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
