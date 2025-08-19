<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Text Analyzer - AI Powered</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .btn-hero {
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .stats-counter {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-robot text-primary me-2"></i>
                Text Analyzer
            </a>

            <div class="navbar-nav ms-auto">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/analyzer') }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-house me-1"></i>
                            Home
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i>
                                Sign Up
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Analyze Your Text with the Power of
                        <span class="text-warning">Artificial Intelligence</span>
                    </h1>
                    <p class="lead mb-5">
                        Get a full analysis of your text: sentiment detection, keyword extraction, and automatic summaries.
                        Powered by advanced Gemini AI technology.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('analyzer.create') }}" class="btn btn-warning btn-hero">
                                <i class="bi bi-gear me-2"></i>
                                Start Analysis Now
                            </a>

                        @else
                            <a href="{{ route('register') }}" class="btn btn-warning btn-hero">
                                <i class="bi bi-rocket me-2"></i>
                                Start for Free
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-hero">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <div class="floating-animation">
                        <i class="bi bi-cpu" style="font-size: 15rem; opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Powerful Text Analysis Features</h2>
                <p class="lead text-muted">Discover everything our advanced app can offer</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                                        <div class="card feature-card h-100 text-center p-4 bg-light  rounded-30 ">

                        <div class="card-body">
                            <i class="bi bi-cpu text-primary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Comprehensive Smart Analysis</h5>
                            <p class="text-muted">
                                Deep text analysis using advanced AI to understand meaning and context.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                                        <div class="card feature-card h-100 text-center p-4 bg-light  rounded-30 ">

                        <div class="card-body">
                            <i class="bi bi-heart text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Sentiment Analysis</h5>
                            <p class="text-muted">
                                Detect emotions and sentiments expressed in the text—positive, negative, or neutral.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                                        <div class="card feature-card h-100 text-center p-4 bg-light  rounded-30 ">

                        <div class="card-body">
                            <i class="bi bi-tags text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Keyword Extraction</h5>
                            <p class="text-muted">
                                Automatically extract key words and concepts the text focuses on.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                                        <div class="card feature-card h-100 text-center p-4 bg-light  rounded-30 ">

                        <div class="card-body">
                            <i class="bi bi-file-text text-info" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Automatic Summarization</h5>
                            <p class="text-muted">
                                Get a concise and useful summary of long texts to save time and effort.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                                        <div class="card feature-card h-100 text-center p-4 bg-light  rounded-30 ">

                        <div class="card-body">
                            <i class="bi bi-shield-check text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Secure Storage</h5>
                            <p class="text-muted">
                                Save your texts and analyses securely and access them anytime.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                                        <div class="card feature-card h-100 text-center p-4 bg-light  rounded-30 ">

                        <div class="card-body">
                            <i class="bi bi-graph-up text-primary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Advanced Statistics</h5>
                            <p class="text-muted">
                                Track your stats and analyze your text performance with helpful charts.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold">Numbers That Speak for Themselves</h2>
            </div>
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stats-counter">99%</div>
                    <h6 class="text-muted">Analysis Accuracy</h6>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stats-counter">< 3s</div>
                    <h6 class="text-muted">Processing Speed</h6>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stats-counter">24/7</div>
                    <h6 class="text-muted">Always Available</h6>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stats-counter">∞</div>
                    <h6 class="text-muted">Free Texts</h6>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-4">Ready to Analyze Your Texts?</h2>
            <p class="lead mb-4">Join us today and discover the power of AI in text analysis</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-warning btn-hero">
                    <i class="bi bi-rocket me-2"></i>
                    Start for Free Now
                </a>
            @else
                <a href="{{ route('analyzer.create') }}" class="btn btn-warning btn-hero">
                    <i class="bi bi-gear me-2"></i>
                    Analyze a New Text
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue text-white py-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6">
                    <h5  style="color: rgb(143, 138, 138)" >Text Analyzer</h5>
                    <p class="text-muted">Powered by Gemini AI</p>
                </div>
                <div class="col-md-6">
                    <p style="color: rgb(143, 138, 138)" class="mb-0">&copy; {{ date('Y') }} All Rights Reserved</p>
                    <small class="text-muted">Developed with Laravel & Bootstrap</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function animateCounter() {
            const counters = document.querySelectorAll('.stats-counter');
            counters.forEach(counter => {
                const target = counter.innerText.replace(/[^\d]/g, '');
                if(target) {
                    let current = 0;
                    const increment = target / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if(current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        counter.innerText = Math.floor(current) + (counter.innerText.includes('%') ? '%' : '');
                    }, 40);
                }
            });
        }
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });
        document.querySelector('.stats-counter').closest('section') &&
        observer.observe(document.querySelector('.stats-counter').closest('section'));
    </script>
</body>
</html>
