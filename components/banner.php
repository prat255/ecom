<?php if (!empty($featured) && count($featured) >= 3): ?>
<section class="showcase-section">
    <div class="section-meta">
        <span class="eyebrow">FEATURED SPOTLIGHT</span>
        <span class="hint">Click side panels to focus</span>
    </div>

    <div class="glass-card banner-triplet" id="featuredBanner">
        <!-- Left Panel (=) -->
        <div class="banner-slot slot-side" data-slot="0" onclick="rotateBanner(0)">
            <img src="<?= htmlspecialchars($featured[0]['image_url']) ?>" alt="<?= htmlspecialchars($featured[0]['name']) ?>">
            <div class="slot-overlay">
                <span class="slot-tag"><?= htmlspecialchars($featured[0]['category']) ?></span>
                <h4><?= htmlspecialchars($featured[0]['name']) ?></h4>
            </div>
        </div>

        <!-- Vertical Glass Divider (|) -->
        <div class="banner-divider"></div>

        <!-- Center Hero Panel (===) -->
        <div class="banner-slot slot-center" data-slot="1">
            <img src="<?= htmlspecialchars($featured[1]['image_url']) ?>" alt="<?= htmlspecialchars($featured[1]['name']) ?>">
            <div class="slot-overlay center-overlay">
                <div class="center-info">
                    <span class="slot-tag"><?= htmlspecialchars($featured[1]['category']) ?></span>
                    <h2><?= htmlspecialchars($featured[1]['name']) ?></h2>
                    <p><?= htmlspecialchars($featured[1]['description']) ?></p>
                </div>
                <div class="center-cta">
                    <span class="hero-price">$<?= number_format($featured[1]['price'], 2) ?></span>
                    <button type="button" class="btn-primary" onclick="addToCart(<?= (int)$featured[1]['id'] ?>)">+ Add to Cart</button>
                </div>
            </div>
        </div>

        <!-- Vertical Glass Divider (|) -->
        <div class="banner-divider"></div>

        <!-- Right Panel (=) -->
        <div class="banner-slot slot-side" data-slot="2" onclick="rotateBanner(2)">
            <img src="<?= htmlspecialchars($featured[2]['image_url']) ?>" alt="<?= htmlspecialchars($featured[2]['name']) ?>">
            <div class="slot-overlay">
                <span class="slot-tag"><?= htmlspecialchars($featured[2]['category']) ?></span>
                <h4><?= htmlspecialchars($featured[2]['name']) ?></h4>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
