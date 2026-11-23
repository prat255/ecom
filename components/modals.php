<!-- Product Detail Modal -->
<div class="modal-backdrop" id="productModal">
    <div class="glass-card modal-box product-detail-modal">
        <button type="button" class="modal-close" onclick="closeModal('productModal')">✕</button>
        <div class="modal-grid" id="productModalContent"></div>
    </div>
</div>

<!-- Orders History Modal -->
<div class="modal-backdrop" id="ordersModal">
    <div class="glass-card modal-box orders-modal">
        <div class="modal-header">
            <h3>Your Order History</h3>
            <button type="button" class="modal-close static" onclick="closeModal('ordersModal')">✕</button>
        </div>
        <div class="orders-list" id="ordersList">
            <p class="muted">Loading orders...</p>
        </div>
    </div>
</div>

<!-- Slide-Over Cart & Checkout Drawer -->
<div class="drawer-backdrop" id="cartBackdrop" onclick="closeCart()"></div>
<aside class="glass-drawer" id="cartDrawer">
    <div class="drawer-header">
        <h3>Shopping Cart</h3>
        <button type="button" class="modal-close static" onclick="closeCart()">✕</button>
    </div>

    <div class="drawer-body" id="cartItemsContainer"></div>

    <div class="drawer-footer" id="cartFooter">
        <div class="total-row">
            <span>Total</span>
            <strong id="cartTotal">$0.00</strong>
        </div>
        <form id="checkoutForm" class="checkout-form">
            <input type="text" id="shippingAddress" placeholder="Enter delivery address (Street, City, ZIP)" required>
            <button type="submit" class="btn-primary full-width">Place Order →</button>
        </form>
    </div>
</aside>

<!-- Toast Notification -->
<div class="glass-toast" id="toast"></div>