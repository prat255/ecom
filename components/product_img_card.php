<?php $itemJson = json_encode($item, JSON_HEX_APOS | JSON_HEX_QUOT); ?>
<div class="product-img-wrap" onclick='openProductModal(<?= $itemJson ?>)'>
    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" loading="lazy">
    <span class="category-chip"><?= htmlspecialchars($item['category']) ?></span>
    <div class="quick-view-hint">Quick View</div>
</div>