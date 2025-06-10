<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable display in production

include 'db_connect.php';

// Generate CSRF token if not set
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
    $stmt = $conn->prepare("SELECT id, name, content, position, image, rating, created_at FROM testimonials WHERE approved = 1 ORDER BY created_at DESC LIMIT 50");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception("Get result failed: " . $stmt->error);
    }
    $testimonials = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    error_log("Testimonial fetch error: " . $e->getMessage(), 0);
    $error_message = "Unable to load testimonials at this time.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="Read customer testimonials for Trambakraj Enterprises' construction and car washing services in Nagpur.">
    <meta name="keywords" content="testimonials, customer reviews, Trambakraj, construction, car washing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <meta name="robots" content="index, follow">
    <title>Testimonials - Trambakraj Enterprises</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css') ?>">
    <style>
        .testimonial-card { 
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
            margin-bottom: 1.5rem;
            height: 100%;
        }
        .testimonial-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); 
        }
        .default-avatar {
            background-color: #f8f9fa;
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-size: 2rem; 
            color: #6c757d; 
            border: 2px solid #dee2e6;
        }
        .testimonial-rating {
            font-size: 1.2rem;
        }
        .card-footer {
            background-color: rgba(0,0,0,0.03);
        }
        @media (max-width: 767.98px) {
            .testimonial-card {
                max-width: 100%;
                margin-left: auto;
                margin-right: auto;
            }
            .section-header h2 {
                font-size: 1.8rem;
            }
            .col-md-4 {
                margin-bottom: 1rem;
            }
        }
    </style>
    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Testimonials - Trambakraj Enterprises",
        "description": "Read customer testimonials for Trambakraj Enterprises' construction and car washing services in Nagpur.",
        "url": "https://www.trambakraj.com/testimonials.php",
        "mainEntity": {
            "@type": "ItemList",
            "itemListElement": [
                <?php foreach ($testimonials as $index => $testimonial): ?>
                {
                    "@type": "Review",
                    "author": {
                        "@type": "Person",
                        "name": "<?= htmlspecialchars($testimonial['name'], ENT_QUOTES, 'UTF-8') ?>"
                    },
                    "reviewBody": "<?= htmlspecialchars($testimonial['content'], ENT_QUOTES, 'UTF-8') ?>",
                    "reviewRating": {
                        "@type": "Rating",
                        "ratingValue": "<?= (int)($testimonial['rating'] ?? 0) ?>",
                        "bestRating": "5"
                    },
                    "datePublished": "<?= date('c', strtotime($testimonial['created_at'])) ?>"
                }<?= $index < count($testimonials) - 1 ? ',' : '' ?>
                <?php endforeach; ?>
            ]
        }
    }
    </script>
</head>
<body>
    <a href="#main-content" class="sr-only sr-only-focusable">Skip to main content</a>
    <?php include 'nav.php'; ?>

    <main id="main-content">
        <section class="testimonials-section py-5 mt-5" aria-labelledby="testimonials-heading">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <h2 id="testimonials-heading">Customer Testimonials</h2>
                    <p class="lead">Hear what our clients have to say about us</p>
                </div>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <?php if (empty($testimonials)): ?>
                        <div class="col-12 text-center">
                            <p class="text-muted">No testimonials available yet. Be the first to share your experience!</p>
                            <a href="submit_testimonial.php" class="btn btn-primary mt-3" role="button" aria-label="Share your testimonial">Share Your Experience</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($testimonials as $testimonial): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 text-center shadow-sm testimonial-card" aria-labelledby="testimonial-<?= htmlspecialchars($testimonial['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="card-body">
                                    <div class="testimonial-rating mb-3" aria-label="Rating: <?= (int)($testimonial['rating'] ?? 0) ?> stars">
                                        <?php
                                        $rating = (int)($testimonial['rating'] ?? 0);
                                        for ($i = 0; $i < 5; $i++) {
                                            echo $i < $rating
                                                ? '<i class="fas fa-star text-warning" aria-hidden="true"></i>'
                                                : '<i class="far fa-star text-warning" aria-hidden="true"></i>';
                                        }
                                        ?>
                                    </div>
                                    <p class="lead" id="testimonial-<?= htmlspecialchars($testimonial['id'], ENT_QUOTES, 'UTF-8') ?>">
                                        &quot;<?= htmlspecialchars($testimonial['content'], ENT_QUOTES, 'UTF-8') ?>&quot;
                                    </p>
                                    <div class="testimonial-author mt-3">
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
                                                 alt="Profile image of <?= htmlspecialchars($testimonial['name'], ENT_QUOTES, 'UTF-8') ?>" 
                                                 class="rounded-circle mb-2" width="80" height="80"
                                                 loading="lazy" decoding="async"
                                                 onerror="this.classList.add('d-none');this.nextElementSibling.classList.remove('d-none');">
                                            <div class="rounded-circle mb-2 default-avatar d-none" style="width:80px;height:80px;">
                                                <?= strtoupper(substr($testimonial['name'], 0, 1)) ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="rounded-circle mb-2 default-avatar" style="width:80px;height:80px;">
                                                <?= strtoupper(substr($testimonial['name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <h5><?= htmlspecialchars($testimonial['name'], ENT_QUOTES, 'UTF-8') ?></h5>
                                        <p class="text-muted"><?= htmlspecialchars($testimonial['position'] ?: 'Customer', ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <small class="text-muted">Posted on <?= date('F j, Y', strtotime($testimonial['created_at'])) ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="submit_testimonial.php" class="btn btn-primary" role="button" aria-label="Share your testimonial">Share Your Experience</a>
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