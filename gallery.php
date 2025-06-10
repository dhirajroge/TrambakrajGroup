<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View our gallery of construction projects and car washing services at Trambakraj Enterprises in Nagpur.">
    <meta name="keywords" content="gallery, construction projects, car washing, Trambakraj, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <title>Gallery - Our Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <section class="gallery-section py-5 mt-4">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2>Our Work</h2>
                <p class="lead">Explore our completed projects and services</p>
            </div>
            <div class="row">
                <?php
                // Sample images (can be stored in a database for dynamic content)
                $images = [
                    ['src' => 'images/project1.jpg', 'alt' => 'Residential Complex', 'title' => 'Residential Complex', 'desc' => '20-floor residential building in Nagpur.'],
                    ['src' => 'images/project2.jpg', 'alt' => 'Car Detailing', 'title' => 'Car Detailing', 'desc' => 'Premium ceramic coating application.'],
                    ['src' => 'images/project3.jpg', 'alt' => 'Construction Site', 'title' => 'Construction Site', 'desc' => 'Supplying materials for a commercial project.'],
                    ['src' => 'images/carwash1.jpg', 'alt' => 'Car Wash', 'title' => 'Car Wash', 'desc' => 'Exterior wash and wax service.'],
                ];
                foreach ($images as $index => $image):
                ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($image['src']) ?>" class="card-img-top" alt="<?= htmlspecialchars($image['alt']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($image['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($image['desc']) ?></p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#imageModal<?= $index ?>">
                                View Larger
                            </button>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="imageModal<?= $index ?>" tabindex="-1" aria-labelledby="imageModalLabel<?= $index ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel<?= $index ?>"><?= htmlspecialchars($image['title']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="<?= htmlspecialchars($image['src']) ?>" class="img-fluid" alt="<?= htmlspecialchars($image['alt']) ?>">
                                    <p class="mt-3"><?= htmlspecialchars($image['desc']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>