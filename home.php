<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$products = [];
$featured = [];
$res = $conn->query("SELECT * FROM products ORDER BY id ASC");
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
    if ((int)$row['is_featured'] === 1) {
        $featured[] = $row;
    }
}

if (count($featured) < 3) {
    $featured = array_slice($products, 0, 3);
}

$cartCount = array_sum($_SESSION['cart'] ?? []);

$pageTitle = 'AETHER // Glassmorphism E-Commerce';
$bodyClass = '';
require 'components/header.php';
require 'components/navbar.php';
?>

<main class="container">
    <!-- =|===|= Showcase Banner Component -->
    <?php require 'components/banner.php'; ?>

    <!-- Product Catalog -->
    <section class="catalog-section">
        <div class="catalog-header">
            <h2>Explore Collection</h2>
            <div class="filter-pills" id="categoryFilters">
                <button class="pill active" data-cat="All">All</button>
                <button class="pill" data-cat="Audio">Audio</button>
                <button class="pill" data-cat="Wearables">Wearables</button>
                <button class="pill" data-cat="Tech">Tech</button>
                <button class="pill" data-cat="Lifestyle">Lifestyle</button>
            </div>
        </div>

        <div class="product-grid" id="productGrid">
            <?php foreach ($products as $item): ?>
                <?php include 'components/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php
require 'components/modals.php';

$showSiteFooter = true;
$inlineScript = 'const FEATURED_ITEMS = ' . json_encode(array_values($featured), JSON_HEX_APOS | JSON_HEX_QUOT) . ';';
require 'components/footer.php';
?>