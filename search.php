<?php
include 'db_connect.php';
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];

if ($query) {
    // Static search results (extend with database query later)
    $results = [
        ['title' => 'Construction Materials', 'desc' => 'High-quality cement, steel, and aggregates.', 'link' => 'services.php#construction'],
        ['title' => 'Car Washing Services', 'desc' => 'Professional car wash and detailing.', 'link' => 'services.php#carwash']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Search for construction materials and car washing services at Trambakraj Enterprises in Nagpur.">
    <meta name="keywords" content="search, Trambakraj, construction materials, car washing, Nagpur">
    <meta name="author" content="Trambakraj Enterprises">
    <title>Search Results - Trambakraj Enterprises</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <section class="search-section py-5">
        <div class="container">
            <h2 class="text-center mb-5">Search Results for "<?= htmlspecialchars($query) ?>"</h2>
            <?php if ($query && !empty($results)): ?>
                <div class="row">
                    <?php foreach ($results as $result): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($result['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($result['desc']) ?></p>
                                <a href="<?= htmlspecialchars($result['link']) ?>" class="btn btn-primary">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($query): ?>
                <p class="text-center">No results found for "<?= htmlspecialchars($query) ?>". Try a different search term.</p>
            <?php else: ?>
                <p class="text-center">Please enter a search query.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>