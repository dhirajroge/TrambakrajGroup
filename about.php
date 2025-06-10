<?php
// Start session (optional, only if needed for other features)
session_start();

// Include database connection (optional, only if used)
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="Learn about Trambakraj Enterprises, a trusted provider of construction materials and car washing services in Nagpur, founded in 2025.">
    <meta name="keywords" content="Trambakraj Enterprises, about us, construction materials, car washing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <meta name="robots" content="index, follow">
    <title>About Us - Trambakraj Enterprises</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css') ?>">
    
    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AboutPage",
        "name": "About Trambakraj Enterprises",
        "description": "Learn about Trambakraj Enterprises, a trusted provider of construction materials and car washing services in Nagpur, founded in 2025.",
        "url": "https://www.trambakraj.com/about.php",
        "mainEntity": {
            "@type": "Organization",
            "name": "Trambakraj Enterprises",
            "foundingDate": "2025",
            "logo": "https://www.trambakraj.com/images/logo.png",
            "contactPoint": [
                {
                    "@type": "ContactPoint",
                    "telephone": "+919307652301",
                    "contactType": "Customer Service",
                    "areaServed": "IN",
                    "availableLanguage": ["English", "Hindi"]
                }
            ]
        }
    }
    </script>

    <style>
        :root {
    --primary: #007bff;
    --border-radius: 0.375rem;
    --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.timeline {
    position: relative;
    margin: 0 auto;
    padding: 2rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 4px;
    background: var(--primary);
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    margin: 2rem 0;
    width: 100%;
    text-align: center;
}

.timeline-content {
    background: #fff;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    max-width: 500px;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .timeline::before {
        left: 10px;
    }
    .timeline-item {
        text-align: left;
    }
    .timeline-content {
        margin-left: 30px;
    }
}
    </style>
</head>
<body>
    <a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>
    <?php include 'nav.php'; ?>

    <main id="main-content" role="main">
        <section class="about-section py-5 mt-5" aria-labelledby="about-heading">
            <div class="container">
                <header class="section-header text-center mb-5">
                    <h1 id="about-heading" class="h2">About Trambakraj Enterprises</h1>
                    <p class="lead">Building Excellence in Nagpur Since 2025</p>
                </header>
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4">
                        <h2>Our Story</h2>
                        <p>Launched in 2025, Trambakraj Enterprises is a fresh and dynamic company in Nagpur, dedicated to delivering top-quality construction materials and professional car washing services. Our mission is to set a new benchmark for reliability, innovation, and customer satisfaction in the region.</p>
                        <p>From premium cement, steel, and aggregates for construction projects to eco-friendly car detailing services, we are committed to exceeding client expectations with every interaction.</p>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <img src="images/Trambakraj.jpg" class="img-fluid rounded shadow" alt="Trambakraj Enterprises team working on construction and car washing services" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-lg-4 mb-4">
                        <article class="card h-100 text-center" role="region" aria-labelledby="mission-card">
                            <div class="card-body">
                                <h3 id="mission-card" class="card-title">Our Mission</h3>
                                <p class="card-text">To deliver top-quality construction materials and automotive services that empower our clients to build and maintain with confidence.</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <article class="card h-100 text-center" role="region" aria-labelledby="vision-card">
                            <div class="card-body">
                                <h3 id="vision-card" class="card-title">Our Vision</h3>
                                <p class="card-text">To become Nagpur’s leading provider of construction and car care solutions, recognized for innovation and trust.</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <article class="card h-100 text-center" role="region" aria-labelledby="values-card">
                            <div class="card-body">
                                <h3 id="values-card" class="card-title">Our Values</h3>
                                <p class="card-text">Integrity, excellence, and client satisfaction are at the heart of everything we do.</p>
                            </div>
                        </article>
                    </div>
                </div>
                <!-- Timeline Section -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h2 class="text-center mb-4">Our Journey</h2>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <h3>2025 - Founded</h3>
                                    <p>Trambakraj Enterprises was established in Nagpur, offering high-quality construction materials and car washing services with a focus on customer excellence.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script defer src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script defer src="js/script.js?v=<?= filemtime('js/script.js') ?>"></script>
</body>
</html>