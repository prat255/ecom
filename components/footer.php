<?php $showSiteFooter = $showSiteFooter ?? true; ?>

<?php if ($showSiteFooter): ?>
<footer class="glass-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <span class="brand-logo">◈ AETHER</span>
            <p>Curated future-tech & lifestyle essentials crafted with translucent minimalism.</p>
        </div>
        <div class="footer-links">
            <a href="home.php">Collection</a>
            <a href="javascript:void(0)" onclick="openOrders()">Orders</a>
            <a href="javascript:void(0)" onclick="openCart()">Cart</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="footer-copy">
            &copy; <?= date('Y') ?> AETHER Store. All rights reserved.
        </div>
    </div>
</footer>
<?php endif; ?>

<?php if (!empty($inlineScript)): ?>
<script><?= $inlineScript ?></script>
<?php endif; ?>
<script src="script.js"></script>
</body>
</html>