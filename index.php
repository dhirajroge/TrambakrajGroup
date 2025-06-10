<?php
session_start();
require_once 'db_connect.php';

// Check database connection
if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error(), 0);
    header("HTTP/1.1 500 Internal Server Error");
    die(json_encode(['error' => 'Database connection failed. Please try again later.']));
}

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        error_log("CSRF token generation failed: " . $e->getMessage(), 0);
        $_SESSION['csrf_token'] = hash('sha256', uniqid(mt_rand(), true));
    }
}

// Fetch testimonials
$testimonials = [];
try {
    $stmt = $conn->prepare("SELECT name, position, content, rating, image FROM testimonials WHERE approved = 1 ORDER BY created_at DESC LIMIT 3");
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $testimonials = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        error_log("Failed to prepare testimonial query: " . $conn->error, 0);
    }
} catch (Exception $e) {
    error_log("Testimonial query failed: " . $e->getMessage(), 0);
}

// Dynamic metadata
$page_title = htmlspecialchars("Trimbakraj Enterprises - Construction & Car Washing", ENT_QUOTES, 'UTF-8');
$page_description = htmlspecialchars("Quality construction materials and car washing services in Nagpur since 1989.", ENT_QUOTES, 'UTF-8');
$page_image = filter_var("https://www.trambakraj.com/images/logo.png", FILTER_VALIDATE_URL) ?: 'https://www.trimbakraj.com/images/fallback.jpg';
$page_url = filter_var("https://www.trimbakraj.com", FILTER_VALIDATE_URL) ?: '';

$newsletter_success = isset($_GET['newsletter_success']) && $_GET['newsletter_success'] === '1';

// Close connection
if ($conn) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://www.googletagmanager.com https://embed.tawk.to https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data: https://www.trambakraj.com https://*.instagram.com; font-src 'self' https://cdnjs.cloudflare.com https://use.fontawesome.com; connect-src 'self' https://www.trambakraj.com https://api.tawk.to https://*.instagram.com;">
    <meta name="description" content="<?= $page_description ?>">
    <meta name="keywords" content="construction materials, car washing, Trambakraj, cement, steel, car detailing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    
    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Trambakraj Enterprises",
        "url": "https://www.trambakraj.com",
        "logo": "https://www.trambakraj.com/images/logo.png",
        "contactPoint": [
            {
                "@type": "ContactPoint",
                "telephone": "+919307652301",
                "contactType": "Customer Service",
                "areaServed": "IN",
                "availableLanguage": ["English", "Hindi"]
            }
        ],
        "sameAs": [
            "https://instagram.com/trimbakraj_89",
            "https://instagram.com/trimbakraj_car.washing89"
        ]
    }
    </script>

    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></noscript>

    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://use.fontawesome.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $page_title ?>">
    <meta property="og:description" content="<?= $page_description ?>">
    <meta property="og:image" content="<?= $page_image ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?= $page_url ?>">
    <meta property="og:site_name" content="Trambakraj Enterprises">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $page_title ?>">
    <meta name="twitter:description" content="<?= $page_description ?>">
    <meta name="twitter:image" content="<?= $page_image ?>">

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    <title><?= $page_title ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css') ?>">
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="sr-only sr-only-focusable">Skip to main content</a>

    <?php include 'nav.php'; ?>

    <main id="main-content">
        <!-- Hero Section -->
        <section class="hero-section" role="banner" aria-label="Company Services Showcase">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <picture>
                            <source srcset="images/truck2.jpg" type="image/jpg">
                            <source srcset="images/truck2.jpg" type="image/jpg">
                            <img src="images/truck2.jpg" 
                                 srcset="images/truck2-mobile.jpg 576w, images/truck2.jpg 1200w" 
                                 sizes="(max-width: 576px) 576px, 1200px"
                                 class="d-block w-100" alt="Premium Construction Materials" loading="lazy" decoding="async">
                        </picture>
                        <div class="carousel-caption">
                            <h1>Premium Construction Materials</h1>
                            <p>Quality materials for all your construction needs</p>
                            <div class="mt-3">
                                <a href="services.php#construction" class="btn btn-primary btn-lg" role="button" aria-label="Explore construction materials">Explore Materials</a>
                                <a href="contact.php?business=Construction" class="btn btn-outline-light btn-lg mx-2" role="button" aria-label="Get a quote for construction materials">Get Quote</a>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <picture>
                            <source srcset="images/car.jpg" type="image/jpg">
                            <source srcset="images/car.jpg" type="image/jpg">
                            <img src="images/car.jpg" 
                                 srcset="images/car-mobile.jpg 576w, images/car.jpg 1200w" 
                                 sizes="(max-width: 576px) 576px, 1200px"
                                 class="d-block w-100" alt="Professional Car Washing" loading="lazy" decoding="async">
                        </picture>
                        <div class="carousel-caption">
                            <h1>Professional Car Washing</h1>
                            <p>Make your car shine like new</p>
                            <div class="mt-3">
                                <a href="book_appointment.php" class="btn btn-primary btn-lg" role="button" aria-label="Book a car wash appointment">Book Now</a>
                                <a href="services.php#carwash" class="btn btn-outline-light btn-lg mx-2" role="button" aria-label="Learn more about car washing services">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="carousel-item">
    <picture>
        <source srcset="images/hero3.avif" type="image/avif">
        <source srcset="images/hero3.webp" type="image/webp">
        <img src="images/hero3.jpg" 
             srcset="images/hero3-mobile.jpg 576w, images/hero3.jpg 1200w" 
             sizes="(max-width: 576px) 576px, 1200px"
             class="d-block w-100" alt="Trusted Since 2025" loading="lazy" decoding="async">
    </picture>
    <div class="carousel-caption">
        <h1>Trusted Since 2025</h1>
        <p>Serving the Nagpur community with passion and commitment</p>
        <div class="mt-3">
            <a href="about.php" class="btn btn-primary btn-lg" role="button" aria-label="Learn about our story">Our Story</a>
            <a href="testimonials.php" class="btn btn-outline-light btn-lg mx-2" role="button" aria-label="View customer reviews">See Reviews</a>
        </div>
    </div>
</div>

                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous Slide">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next Slide">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </section>

        <!-- Quick Stats -->
        <section class="quick-stats py-5" aria-label="Company Statistics">
            <div class="container">
                <div class="row gx-4 gy-4 justify-content-center text-center">
                    <div class="col-5 col-md-3">
                        <div class="card p-3">
                            <h3 class="count mb-2 text-primary fw-bold" data-target="10">0</h3>
                            <p class="text-secondary fw-semibold">Years Experience</p>
                        </div>
                    </div>
                    <div class="col-5 col-md-3">
                        <div class="card p-3">
                            <h3 class="count mb-2 text-primary fw-bold" data-target="50">0</h3>
                            <p class="text-secondary fw-semibold">Happy Customers</p>
                        </div>
                    </div>
                    <div class="col-5 col-md-3">
                        <div class="card p-3">
                            <h3 class="count mb-2 text-primary fw-bold" data-target="15">0</h3>
                            <p class="text-secondary fw-semibold">Projects Completed</p>
                        </div>
                    </div>
                    <div class="col-5 col-md-3">
                        <div class="card p-3">
                            <h3 class="mb-2 text-primary fw-bold">24/7</h3>
                            <p class="text-secondary fw-semibold">Customer Support</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Overview -->
        <section class="services py-5">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <h2>Our Businesses</h2>
                    <p>Quality services for all your needs</p>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <picture>
                                <source srcset="images/truck3.jpg" type="image/jpg">
                                <source srcset="images/truck3.jpg" type="image/jpg">
                                <img src="images/truck3.jpg" class="card-img-top" alt="Construction Materials" loading="lazy" decoding="async">
                            </picture>
                            <div class="card-body">
                                <h5 class="card-title">Construction Material Supplier</h5>
                                <p class="card-text">All types of building construction materials and general order supplies including:</p>
                                <ul class="service-features list-unstyled">
                                    <li><i class="fas fa-check-circle text-primary"></i> 10Mm , 20Mm, 40Mm, 60Mm Gitti</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> MURUM</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> C. Sand</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> Dust & GSB</li>
                                </ul>
                                <a href="services.php#construction" class="btn btn-primary" role="button" aria-label="Learn more about construction materials">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <picture>
                                <source srcset="images/car3.jpg" type="image/jpg">
                                <source srcset="images/car3.jpg" type="image/jpg">
                                <img src="images/car3.jpg" class="card-img-top" alt="Car Washing Services" loading="lazy" decoding="async">
                            </picture>
                            <div class="card-body">
                                <h5 class="card-title">Car Washing Center</h5>
                                <p class="card-text">Professional car washing and auto detailing services including:</p>
                                <ul class="service-features list-unstyled">
                                    <li><i class="fas fa-check-circle text-primary"></i> Premium Exterior Wash</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> Interior Detailing</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> Ceramic Coating</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> Engine Bay Cleaning</li>
                                </ul>
                                <a href="services.php#carwash" class="btn btn-primary" role="button" aria-label="Learn more about car washing services">Learn More</a>
                                <a href="book_appointment.php" class="btn btn-outline-primary ms-2" role="button" aria-label="Book a car wash appointment">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Business Cards Section -->
        <section class="business-cards py-5 bg-light">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <h2>Our Business Cards</h2>
                    <p class="lead">Contact us for your specific needs</p>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="business-card card shadow h-100">
                            <div class="card-header text-center bg-primary text-white">
                                <picture>
                                    <source srcset="images/logo.jpg" type="image/jpg">
                                    <source srcset="images/logo.jpg" type="image/jpg">
                                    <img src="images/logo.png" width="120" height="60" alt="Trambakraj Construction Logo" class="business-logo" loading="lazy" decoding="async">
                                </picture>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">Trambakraj Construction</h3>
                                <p class="card-subtitle mb-2 text-muted">All Types Of Building Construction Material & General Order Suppliers & Civil Contractor</p>
                                <p class="tagline"><i class="fas fa-quote-left"></i> हनुमंत सदा सहायते <i class="fas fa-quote-right"></i></p>
                                <ul class="contact-info list-unstyled">
                                    <li><i class="fas fa-location-dot"></i> <strong>Address:</strong> Wanadongri, Hingna Road, Nagpur</li>
                                    <li><i class="fas fa-phone"></i> <strong>Contact:</strong> 9307652301 / 7218902219 / 7218260010</li>
                                    <li><i class="fas fa-envelope"></i> <strong>Email:</strong> trimbakraj.construction89@gmail.com</li>
                                    <li><i class="fab fa-instagram"></i> <strong>Instagram:</strong> <a href="https://instagram.com/trimbakraj__construction_89" aria-label="Trambakraj Construction Instagram">@trimbakraj_89</a></li>
                                </ul>
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="contact.php?business=Construction" class="btn btn-primary" role="button" aria-label="Contact Trambakraj Construction">Get in Touch</a>
                                    <a href="tel:9307652301" class="btn btn-success" role="button" aria-label="Call Trambakraj Construction"><i class="fas fa-phone"></i> Call Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="business-card card shadow h-100">
                            <div class="card-header text-center bg-primary text-white">
                                <picture>
                                    <source srcset="images/logo.jpg" type="image/jpg">
                                    <source srcset="images/logo.jpg" type="image/jpg">
                                    <img src="images/logo.png" width="120" height="60" alt="We Wash Your Car Logo" class="business-logo" loading="lazy" decoding="async">
                                </picture>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">We Wash Your Car - Wagner & Spencer</h3>
                                <p class="card-subtitle mb-2 text-muted">Car Wash & Auto Detailing Specialists</p>
                                <p class="tagline"><i class="fas fa-quote-left"></i> हनुमंत सदा सहायते <i class="fas fa-quote-right"></i></p>
                                <ul class="contact-info list-unstyled">
                                    <li><i class="fas fa-location-dot"></i> <strong>Address:</strong> Kavadas Road, Hingna, Nagpur</li>
                                    <li><i class="fas fa-phone"></i> <strong>Contact:</strong> +91 99601 19418</li>
                                    <li><i class="fas fa-envelope"></i> <strong>Email:</strong> trimbakraj.car.washing89@gmail.com</li>
                                    <li><i class="fab fa-instagram"></i> <strong>Instagram:</strong> <a href="https://instagram.com/trimbakraj_car.washing89" aria-label="Trambakraj Car Wash Instagram">@trimbakraj_car.washing89</a></li>
                                </ul>
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="book_appointment.php" class="btn btn-primary" role="button" aria-label="Book a car wash appointment">Book Appointment</a>
                                    <a href="tel:9960119418" class="btn btn-success" role="button" aria-label="Call We Wash Your Car"><i class="fas fa-phone"></i> Call Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Projects/Blog -->
        <section class="recent-projects py-5 bg-light">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <h2>Recent Projects & Updates</h2>
                    <p class="lead">See our latest work and news</p>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <picture>
                                <source srcset="images/truck.jpg" type="image/jpg">
                                <source srcset="images/truck.jpg" type="image/jpg">
                                <img src="images/truck.jpg" class="card-img-top" alt="Residential Complex Project" loading="lazy" decoding="async">
                            </picture>
                            <div class="card-body">
                                <h5 class="card-title">Residential Complex</h5>
                                <p class="card-text">Completed a 20-floor residential building in Nagpur with our premium materials.</p>
                                <a href="gallery.php" class="btn btn-sm btn-primary" role="button" aria-label="View residential complex gallery">View Gallery</a>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Posted on May 15, 2025</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <picture>
                                <source srcset="images/car2.jpg" type="image/jpg">
                                <source srcset="images/car2.jpg" type="image/jpg">
                                <img src="images/car2.jpg" class="card-img-top" alt="Premium Car Detailing Service" loading="lazy" decoding="async">
                            </picture>
                            <div class="card-body">
                                <h5 class="card-title">Premium Car Detailing</h5>
                                <p class="card-text">Now offering ceramic coating services for long-lasting car protection.</p>
                                <a href="blog.php#ceramic-coating" class="btn btn-sm btn-primary" role="button" aria-label="Read about ceramic coating services">Read More</a>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Posted on May 10, 2025</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <picture>
                                <source srcset="images/truck3.jpg" type="image/avif">
                                <source srcset="images/truck3.jpg" type="image/jpg">
                                <img src="images/truck3.jpg" class="card-img-top" alt="Eco-Friendly Construction Materials" loading="lazy" decoding="async">
                            </picture>
                            <div class="card-body">
                                <h5 class="card-title">New Product Line</h5>
                                <p class="card-text">Introducing eco-friendly construction materials to our inventory.</p>
                                <a href="services.php#new-products" class="btn btn-sm btn-primary" role="button" aria-label="View eco-friendly products">View Products</a>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Posted on May 5, 2025</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="gallery.php" class="btn btn-primary" role="button" aria-label="View all projects">View All Projects</a>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
      <!-- Testimonials Section -->
<section class="testimonials py-5 bg-light" id="testimonials">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="display-5 fw-bold">What Our Customers Say</h2>
            <p class="lead text-muted">Hear from our satisfied clients</p>
        </div>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
            <!-- Carousel Indicators -->
            <?php if (!empty($testimonials) && count($testimonials) > 1): ?>
                <div class="carousel-indicators">
                    <?php foreach ($testimonials as $index => $testimonial): ?>
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="carousel-inner">
                <?php if (empty($testimonials)): ?>
                    <div class="carousel-item active">
                        <div class="testimonial-card text-center p-4 p-lg-5">
                            <p class="fs-5 mb-4">No testimonials available yet. Be the first to share your experience!</p>
                            <a href="submit_testimonial.php" class="btn btn-primary px-4 py-2" role="button" aria-label="Submit a testimonial">Submit a Testimonial</a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($testimonials as $index => $testimonial): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="testimonial-card text-center p-4 p-lg-5">
                                <div class="rating mb-4" aria-label="Rating: <?= (int)($testimonial['rating'] ?? 0) ?> stars">
                                    <?php echo str_repeat('<i class="fas fa-star text-warning mx-1"></i>', (int)($testimonial['rating'] ?? 0)); ?>
                                    <?php echo str_repeat('<i class="far fa-star text-warning mx-1"></i>', 5 - (int)($testimonial['rating'] ?? 0)); ?>
                                </div>
                                <p class="testimonial-text fs-5 fst-italic px-3 px-md-5 mb-4">"<?= htmlspecialchars($testimonial['content'] ?? '', ENT_QUOTES, 'UTF-8') ?>"</p>
                                <div class="author d-flex align-items-center justify-content-center">
                                    <?php
                                    $imagePath = '';
                                    if (!empty($testimonial['image'])) {
                                        $imageFile = 'Uploads/' . $testimonial['image'];
                                        if (file_exists($imageFile)) {
                                            $imagePath = $imageFile;
                                        } else {
                                            error_log("Image not found: " . $imageFile);
                                        }
                                    }
                                    ?>
                                    <?php if (!empty($imagePath)): ?>
                                        <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>" 
                                             alt="Profile image of <?= htmlspecialchars($testimonial['name'] ?? 'Anonymous', ENT_QUOTES, 'UTF-8') ?>" 
                                             class="rounded-circle me-3" width="60" height="60"
                                             loading="lazy" decoding="async"
                                             onerror="this.src='images/default-avatar.jpg'; this.classList.remove('d-none'); this.nextElementSibling?.classList.add('d-none');">
                                        <div class="rounded-circle me-3 default-avatar d-none" style="width:60px;height:60px;">
                                            <?= strtoupper(substr($testimonial['name'] ?? 'A', 0, 1)) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="rounded-circle me-3 default-avatar" style="width:60px;height:60px;">
                                            <?= strtoupper(substr($testimonial['name'] ?? 'A', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-start">
                                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($testimonial['name'] ?? 'Anonymous', ENT_QUOTES, 'UTF-8') ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($testimonial['position'] ?? 'Customer', ENT_QUOTES, 'UTF-8') ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Carousel Controls -->
            <?php if (!empty($testimonials) && count($testimonials) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" aria-label="Previous Slide">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" aria-label="Next Slide">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5">
            <a href="testimonials.php" class="btn btn-outline-primary me-2" role="button" aria-label="View all testimonials">View All Testimonials</a>
            <a href="submit_testimonial.php" class="btn btn-primary" role="button" aria-label="Submit a testimonial">Share Your Experience</a>
        </div>
    </div>
</section>

        <!-- Newsletter Signup -->
        <section class="newsletter py-5 bg-primary text-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h3>Subscribe to Our Newsletter</h3>
                        <p class="mb-0">Get updates on special offers and news from both our businesses.</p>
                    </div>
                    <div class="col-md-6">
                        <form id="newsletterForm" action="submit_newsletter.php" method="POST" class="d-flex">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="email" class="form-control me-2" id="newsletterEmail" name="email" placeholder="Enter your email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" aria-label="Email for newsletter subscription">
                            <button type="submit" class="btn btn-light">Subscribe</button>
                        </form>
                        <div id="newsletterMessage" class="mt-3">
                            <?php if ($newsletter_success): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    Thank you for subscribing to our newsletter!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Social Media Feed -->
        <section class="social-feed py-5">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <h2>Follow Us on Social Media</h2>
                    <p class="lead">Stay updated with our latest news and offers</p>
                </div>
                <div class="d-flex justify-content-center mb-4">
                    <a href="https://instagram.com/trimbakraj__construction_89" class="btn btn-instagram mx-2" aria-label="Follow Trambakraj Construction on Instagram">
                        <i class="fab fa-instagram"></i> Construction Instagram
                    </a>
                    <a href="https://instagram.com/trimbakraj_car.washing89" class="btn btn-instagram mx-2" aria-label="Follow Trambakraj Car Wash on Instagram">
                        <i class="fab fa-instagram"></i> Car Wash Instagram
                    </a>
                </div>
            </div>
        </section>

        <!-- Live Chat (Tawk.to Integration) -->
        <script>
            var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
            (function(){
                var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/your_property_id/default'; // Replace with your Tawk.to property ID
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>

        <!-- Back to Top Button -->
        <a href="#" id="backToTop" class="back-to-top" aria-label="Back to Top">
            <i class="fas fa-arrow-up"></i>
        </a>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script defer src="js/script.js?v=<?= filemtime('js/script.js') ?>"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const counters = document.querySelectorAll(".count");
        const speed = 100; // speed kam zyada kar sakte ho

        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute("data-target");
                const count = +counter.innerText;
                const increment = Math.ceil(target / speed);

                if (count < target) {
                    counter.innerText = count + increment;
                    setTimeout(updateCount, 100);
                } else {
                    counter.innerText = target + "+"; // + lagana hai to
                }
            };

            updateCount();
        });
    });
</script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX', {
            'anonymize_ip': true,
            'cookie_flags': 'SameSite=None;Secure'
        });
    </script>
</body>
</html>