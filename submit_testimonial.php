<?php
session_start();
include 'db_connect.php';

$success = false;
$errors = [];

// CSRF token validation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    error_log("CSRF validation failed for testimonial submission.", 0);
    $errors[] = 'Invalid session. Please try again.';
}

// Regenerate CSRF token
try {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
} catch (Exception $e) {
    error_log("CSRF token regeneration failed: " . $e->getMessage(), 0);
    $_SESSION['csrf_token'] = hash('sha256', uniqid(mt_rand(), true));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    // Sanitize and validate input
    $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING));
    $position = trim(filter_var($_POST['position'] ?? '', FILTER_SANITIZE_STRING)) ?: '';
    $content = trim(filter_var($_POST['content'] ?? '', FILTER_SANITIZE_STRING));
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 5]
    ]);

    // Validate required fields
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($content)) {
        $errors[] = 'Testimonial content is required.';
    }
    if ($rating === false) {
        $errors[] = 'Please select a valid rating (1-5 stars).';
    }

    // Handle file upload
    $image_name = null;
    $upload_dir = 'Uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    if (!is_writable($upload_dir)) {
        $errors[] = 'Uploads directory is not writable. Please contact support.';
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        $file = $_FILES['image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Error uploading image.';
        } elseif (!in_array($file['type'], $allowed_types)) {
            $errors[] = 'Only JPEG, PNG, or GIF images are allowed.';
        } elseif ($file['size'] > $max_size) {
            $errors[] = 'Image size must be less than 2MB.';
        } else {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $image_name = uniqid('testimonial_', true) . '.' . $ext;
            $destination = $upload_dir . $image_name;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                $errors[] = 'Failed to upload image.';
                $image_name = null;
            } else {
                // Verify the file was actually created
                if (!file_exists($destination)) {
                    $errors[] = 'Image upload verification failed.';
                    $image_name = null;
                }
            }
        }
    }

    // Insert into database if no errors
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO testimonials (name, position, content, rating, image, approved, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("sssis", $name, $position, $content, $rating, $image_name);

            if ($stmt->execute()) {
                $stmt->close();
                $success = true;
                header("Location: submit_testimonial.php?success=1");
                exit;
            }
            throw new Exception("Execute failed: " . $stmt->error);
        } catch (Exception $e) {
            if (isset($stmt)) {
                $stmt->close();
            }
            // Delete the uploaded file if database insert failed
            if (!empty($image_name) && file_exists($upload_dir . $image_name)) {
                unlink($upload_dir . $image_name);
            }
            error_log("Testimonial submission failed: " . $e->getMessage(), 0);
            $errors[] = 'Failed to save testimonial. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="Submit your testimonial for Trambakraj Enterprises' construction and car washing services in Nagpur.">
    <meta name="keywords" content="submit testimonial, Trambakraj, construction, car washing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <meta name="robots" content="index, follow">
    <title>Submit Testimonial - Trambakraj Enterprises</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css') ?>">
    <style>
        .testimonial-submit-section {
            padding-bottom: 3rem;
        }
        #testimonialForm {
            background: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        @media (max-width: 767.98px) {
            .testimonial-submit-section {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
            #testimonialForm {
                padding: 1.5rem;
            }
            .section-header h2 {
                font-size: 1.8rem;
            }
            .btn-primary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="sr-only sr-only-focusable">Skip to main content</a>
    <?php include 'nav.php'; ?>

    <main id="main-content">
        <section class="testimonial-submit-section py-5 mt-5" aria-labelledby="testimonial-heading">
            <div class="container">
                <div class="section-header text-center mb-5">
                    <h2 id="testimonial-heading">Share Your Experience</h2>
                    <p class="lead">We value your feedback. Tell us about your experience with our services.</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Thank you for your testimonial! It will be reviewed and published soon.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <form id="testimonialForm" action="submit_testimonial.php" method="POST" enctype="multipart/form-data" class="row g-3" novalidate>
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required aria-describedby="nameFeedback">
                                <div id="nameFeedback" class="invalid-feedback">Please enter your name.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label">Position/Title (Optional)</label>
                                <input type="text" class="form-control" id="position" name="position" aria-describedby="positionFeedback">
                                <div id="positionFeedback" class="text-muted">E.g., Homeowner, Client</div>
                            </div>
                            <div class="col-12">
                                <label for="content" class="form-label">Your Testimonial <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="content" name="content" rows="5" required aria-describedby="contentFeedback"></textarea>
                                <div id="contentFeedback" class="invalid-feedback">Please provide your testimonial.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                <select class="form-select" id="rating" name="rating" required aria-describedby="ratingFeedback">
                                    <option value="">Select Rating</option>
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                                <div id="ratingFeedback" class="invalid-feedback">Please select a rating.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Profile Image (Optional)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/gif" aria-describedby="imageFeedback">
                                <div id="imageFeedback" class="invalid-feedback">Please upload a valid image (JPEG, PNG, or GIF, max 2MB).</div>
                                <small class="text-muted">Max size: 2MB</small>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary btn-lg">Submit Testimonial</button>
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
    <script defer>
        // Bootstrap form validation and image checks
        (function () {
            'use strict';
            const form = document.getElementById('testimonialForm');
            const imageInput = document.getElementById('image');

            imageInput.addEventListener('change', function () {
                const file = imageInput.files[0];
                if (file) {
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    
                    if (!allowedTypes.includes(file.type)) {
                        imageInput.setCustomValidity('Only JPEG, PNG, or GIF images are allowed.');
                        imageInput.classList.add('is-invalid');
                    } else if (file.size > maxSize) {
                        imageInput.setCustomValidity('Image size must be less than 2MB.');
                        imageInput.classList.add('is-invalid');
                    } else {
                        imageInput.setCustomValidity('');
                        imageInput.classList.remove('is-invalid');
                    }
                }
            });

            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        })();
    </script>
    <script defer src="js/script.js?v=<?= filemtime('js/script.js') ?>"></script>
</body>
</html>