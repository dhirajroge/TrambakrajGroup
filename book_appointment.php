<?php
session_start();
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

$business = isset($_GET['business']) ? filter_var($_GET['business'], FILTER_SANITIZE_STRING) : '';
$success = isset($_GET['success']) && $_GET['success'] == '1';
$email_error = isset($_GET['email_error']) ? filter_var($_GET['email_error'], FILTER_SANITIZE_STRING) : '';
$error = isset($_GET['error']) ? filter_var($_GET['error'], FILTER_SANITIZE_STRING) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="Book an appointment for construction materials or car washing services with Trambakraj Enterprises in Nagpur.">
    <meta name="keywords" content="book appointment, Trambakraj, construction materials, car washing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <meta name="robots" content="index, follow">
    <title>Book Appointment - Trambakraj Enterprises</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css') ?>">
</head>
<body>
    <a href="#main-content" class="sr-only sr-only-focusable">Skip to main content</a>
    <?php include 'nav.php'; ?>

    <main id="main-content">
        <section class="appointment-section py-5 mt-5" aria-labelledby="appointment-heading">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <h2 id="appointment-heading">Book an Appointment</h2>
                    <p class="lead">Schedule a consultation or service with us</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Appointment booked successfully! We'll contact you soon.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($email_error): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($email_error, ENT_QUOTES, 'UTF-8') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <form id="appointmentForm" action="submit_appointment.php" method="POST" class="row g-3" novalidate>
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required aria-describedby="nameFeedback">
                                <div id="nameFeedback" class="invalid-feedback">Please enter your name.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" aria-describedby="emailFeedback">
                                <div id="emailFeedback" class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone (Optional)</label>
                                <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9+\-\(\)\s]{10,15}" aria-describedby="phoneFeedback">
                                <div id="phoneFeedback" class="invalid-feedback">Please enter a valid phone number.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="business" class="form-label">Select Business</label>
                                <select class="form-select" id="business" name="business" required aria-describedby="businessFeedback">
                                    <option value="" <?= !$business ? 'selected' : '' ?>>Select Business</option>
                                    <option value="Construction" <?= $business === 'Construction' ? 'selected' : '' ?>>Construction Materials</option>
                                    <option value="Car Wash" <?= $business === 'Car Wash' ? 'selected' : '' ?>>Car Washing Services</option>
                                </select>
                                <div id="businessFeedback" class="invalid-feedback">Please select a business.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="appointment_date" class="form-label">Preferred Date & Time</label>
                                <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" required aria-describedby="dateFeedback">
                                <div id="dateFeedback" class="invalid-feedback">Please select a valid date and time.</div>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Additional Information (Optional)</label>
                                <textarea class="form-control" id="message" name="message" rows="5" aria-describedby="messageFeedback"></textarea>
                                <div id="messageFeedback" class="invalid-feedback">Please provide additional information if needed.</div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">Book Appointment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script defer src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script defer src="js/script.js?v=<?= filemtime('js/script.js') ?>"></script>
    <script>
        // Bootstrap form validation and dynamic date setting
        (function () {
            'use strict';
            const form = document.getElementById('appointmentForm');
            const dateInput = document.getElementById('appointment_date');

            // Set minimum date to current date/time
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            dateInput.min = now.toISOString().slice(0, 16);

            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);

            // Real-time phone validation
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', () => {
                const phonePattern = /^[0-9+\-\(\)\s]{10,15}$/;
                phoneInput.setCustomValidity(phoneInput.value && !phonePattern.test(phoneInput.value) ? 'Invalid phone number.' : '');
            });
        })();
    </script>
</body>
</html>