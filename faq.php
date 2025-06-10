<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Frequently Asked Questions about Trambakraj Enterprises’ construction and car washing services in Nagpur.">
    <meta name="keywords" content="FAQ, Trambakraj, construction materials, car washing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <title>FAQ - Trambakraj Enterprises</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>
</head>
<body>
    <?php include 'nav.php'; ?>

    <section class="faq-section py-5" aria-label="Frequently Asked Questions">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2>Frequently Asked Questions</h2>
                <p class="lead">Find answers to common questions about our services</p>
            </div>
            <div class="faq-item">
                <div class="faq-question d-flex justify-content-between align-items-center" role="button" aria-expanded="false" aria-controls="faq1">
                    <span>What types of construction materials do you supply?</span>
                    <i class="faq-toggle fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer d-none" id="faq1">
                    <p>We supply cement, steel, bricks, aggregates, plumbing, electrical supplies, and eco-friendly paints.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question d-flex justify-content-between align-items-center" role="button" aria-expanded="false" aria-controls="faq2">
                    <span>How can I book a car wash appointment?</span>
                    <i class="faq-toggle fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer d-none" id="faq2">
                    <p>You can book an appointment through our <a href="book_appointment.php">Book Appointment</a> page or call us at +91 99601 19418.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question d-flex justify-content-between align-items-center" role="button" aria-expanded="false" aria-controls="faq3">
                    <span>Do you offer bulk discounts for construction materials?</span>
                    <i class="faq-toggle fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer d-none" id="faq3">
                    <p>Yes, we offer competitive pricing for bulk orders. Contact us at 9307652301 for a quote.</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>