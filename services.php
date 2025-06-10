<?php
session_start();
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="Explore our construction materials and professional car washing services at Trambakraj Enterprises in Nagpur.">
    <meta name="keywords" content="construction materials, car washing, Trambakraj, cement, steel, car detailing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <meta name="robots" content="index, follow">
    <title>Services - Trambakraj Enterprises</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css') ?>">
    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Service",
        "serviceType": "Construction Materials and Car Washing Services",
        "provider": {
            "@type": "Organization",
            "name": "Trambakraj Enterprises",
            "url": "https://www.trambakraj.com",
            "logo": "https://www.trambakraj.com/images/logo.png"
        },
        "areaServed": {
            "@type": "City",
            "name": "Nagpur",
            "addressRegion": "Maharashtra",
            "addressCountry": "IN"
        },
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Services",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Construction Material Supplier",
                        "description": "High-quality cement, steel, bricks, aggregates, plumbing, electrical, paints, and finishes."
                    }
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Car Washing Center",
                        "description": "Premium exterior wash, interior detailing, ceramic coating, and engine bay cleaning."
                    }
                }
            ]
        }
    }
    </script>
</head>
<body>
    <a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>
    <?php include 'nav.php'; ?>

    <main id="main-content" role="main">
        <section class="services-section py-5 mt-5" aria-labelledby="services-heading">
            <div class="container">
                <header class="section-header text-center mb-5">
                    <h1 id="services-heading" class="h2">Our Services</h1>
                    <p class="lead">Quality solutions for your construction and car care needs</p>
                </header>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <article class="card h-100" role="region" aria-labelledby="construction-card">
                            <img src="images/truck2.jpg" width="600" height="400" class="card-img-top" alt="Construction materials delivery truck" loading="lazy" decoding="async">
                            <div class="card-body">
                                <h2 id="construction-card" class="card-title h5">Construction Material Supplier</h2>
                                <p class="card-text">We provide a wide range of high-quality building materials to support your construction projects, including:</p>
                                <ul class="service-features list-unstyled">
                                     <li><i class="fas fa-check-circle text-primary"></i> 10Mm , 20Mm, 40Mm, 60Mm Gitti</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> MURUM</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> C. Sand</li>
                                    <li><i class="fas fa-check-circle text-primary"></i> Dust & GSB</li>
                                </ul>
                                <a href="#construction-details" class="btn btn-primary" aria-describedby="construction-card">Learn More</a>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 mb-4">
                        <article class="card h-100" role="region" aria-labelledby="carwash-card">
                            <img src="images/car3.jpg" width="600" height="400" class="card-img-top" alt="Car being washed at Trambakraj Enterprises" loading="lazy" decoding="async">
                            <div class="card-body">
                                <h2 id="carwash-card" class="card-title h5">Car Washing Center</h2>
                                <p class="card-text">Our professional car washing and detailing services keep your vehicle in pristine condition, including:</p>
                                <ul class="service-features list-unstyled">
                                    <li><i class="fas fa-check-circle text-primary me-2" aria-hidden="true"></i>Premium Exterior Wash</li>
                                    <li><i class="fas fa-check-circle text-primary me-2" aria-hidden="true"></i>Interior Detailing</li>
                                    <li><i class="fas fa-check-circle text-primary me-2" aria-hidden="true"></i>Ceramic Coating</li>
                                    <li><i class="fas fa-check-circle text-primary me-2" aria-hidden="true"></i>Engine Bay Cleaning</li>
                                </ul>
                                <a href="#carwash-details" class="btn btn-primary" aria-describedby="carwash-card">Learn More</a>
                                <a href="book_appointment.php?business=Car%20Wash" class="btn btn-outline-primary ms-2" aria-label="Book car washing appointment">Book Now</a>
                            </div>
                        </article>
                    </div>
                </div>
                <!-- Detailed Sections -->
                <div class="row mt-5">
                    <div class="col-12">
                        <section id="construction-details" aria-labelledby="construction-details-heading">
                            <h2 id="construction-details-heading">Construction Materials</h2>
                            <p>At Trambakraj Enterprises, we supply premium construction materials sourced from trusted manufacturers. Whether you’re building a residential, commercial, or industrial project, our extensive inventory ensures you have the right materials at competitive prices.</p>
                            <a href="contact.php?business=Construction" class="btn btn-primary mt-3" aria-label="Contact us for construction materials">Get a Quote</a>
                        </section>
                        <section id="carwash-details" class="mt-5" aria-labelledby="carwash-details-heading">
                            <h2 id="carwash-details-heading">Car Washing Services</h2>
                            <p>Our state-of-the-art car washing center uses eco-friendly products and advanced techniques to deliver a spotless finish. From quick exterior washes to comprehensive detailing, we cater to all your vehicle care needs.</p>
                            <a href="book_appointment.php?business=Car%20Wash" class="btn btn-primary mt-3" aria-label="Book car washing appointment">Schedule Now</a>
                        </section>
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