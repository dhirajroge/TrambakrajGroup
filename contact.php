<?php
include 'db_connect.php';
$business = isset($_GET['business']) ? $_GET['business'] : '';
$success = isset($_GET['success']) && $_GET['success'] == '1';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Trambakraj Enterprises for construction materials and car washing services in Nagpur.">
    <meta name="keywords" content="contact, Trambakraj, construction materials, car washing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <title>Contact Us - Trambakraj Enterprises</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <section class="contact-section py-5 mt-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2>Contact Us</h2>
                <p class="lead">We’re here to assist you with your construction and car washing needs</p>
            </div>
             <!-- Map and Contact Info -->
    <section class="map-contact py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h3 class="mb-4">Our Locations</h3>
                    <div class="map-container mb-4">
                        <!-- Replace with actual Google Maps embed URL for your locations -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3721.23456789!2d79.123456789!3d21.123456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjHCsDA3JzI0LjQiTiA3OcKwMDcnMjQuNiJF!5e0!3m2!1sen!2sin!4v1234567890" 
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" aria-label="Map showing Trambakraj Enterprises locations"></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3 class="mb-4">Quick Contact</h3>
                    <form id="quickContactForm" action="submit_contact.php" method="POST">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" class="form-control" name="phone" placeholder="Your Phone">
                        </div>
                        <div class="mb-3">
                            <select class="form-select" name="business" required>
                                <option value="">Select Business</option>
                                <option value="Construction">Construction Materials</option>
                                <option value="Car Wash">Car Washing Services</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="message" rows="3" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Bootstrap form validation
        (function () {
            'use strict';
            const form = document.getElementById('quickContactForm');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        })();
    </script>
    <script src="js/script.js"></script>
</body>
</html>





