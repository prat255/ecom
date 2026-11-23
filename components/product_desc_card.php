<?php $itemJson = json_encode($item, JSON_HEX_APOS | JSON_HEX_QUOT); ?>
<div class="product-body">
    <div class="product-top">
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <span class="price">$<?= number_format($item['price'], 2) ?></span>
    </div>
    <p class="product-desc"><?= htmlspecialchars($item['description']) ?></p>
    <div class="product-actions">
        <button type="button" class="btn-glass" onclick='openProductModal(<?= $itemJson ?>)'>View</button>
        <button type="button" class="btn-primary" onclick="addToCart(<?= (int)$item['id'] ?>)">Add to Cart</button>
    </div>
</div>