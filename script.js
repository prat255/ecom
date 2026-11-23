document.addEventListener('DOMContentLoaded', () => {
    // 1. Auth Landing Page Tab Switcher
    const authTabs = document.querySelector('.auth-tabs');
    if (authTabs) {
        const tabBtns = authTabs.querySelectorAll('.tab-btn');
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.target;
                authTabs.dataset.active = target;
                tabBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                if (target === 'login') {
                    signupForm.classList.remove('active');
                    loginForm.classList.add('active');
                } else {
                    loginForm.classList.remove('active');
                    signupForm.classList.add('active');
                }
            });
        });
    }

    // 2. Category Filtering with Smooth Animation
    const filterContainer = document.getElementById('categoryFilters');
    if (filterContainer) {
        const pills = filterContainer.querySelectorAll('.pill');
        const cards = document.querySelectorAll('.product-card');

        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                pills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                const cat = pill.dataset.cat;

                cards.forEach(card => {
                    if (cat === 'All' || card.dataset.category === cat) {
                        card.style.display = 'flex';
                        card.style.animation = 'none';
                        card.offsetHeight;
                        card.style.animation = 'fadeUp 0.45s cubic-bezier(0.22, 1, 0.36, 1)';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // 3. Cart & Orders Triggers
    const openCartBtn = document.getElementById('openCartBtn');
    if (openCartBtn) {
        openCartBtn.addEventListener('click', openCart);
    }

    const openOrdersBtn = document.getElementById('openOrdersBtn');
    if (openOrdersBtn) {
        openOrdersBtn.addEventListener('click', openOrders);
    }

    // 4. Checkout Form Submit
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const address = document.getElementById('shippingAddress').value.trim();
            const formData = new FormData();
            formData.append('action', 'order');
            formData.append('address', address);

            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.status === 'ok') {
                document.getElementById('shippingAddress').value = '';
                closeCart();
                refreshCartUI();
                showToast(data.message);
            } else {
                showToast(data.message || 'Could not place order.');
            }
        });
    }
});

// ================= =|===|= BANNER ROTATION =================
let bannerOrder = [0, 1, 2];

function rotateBanner(clickedSlot) {
    if (typeof FEATURED_ITEMS === 'undefined' || FEATURED_ITEMS.length < 3) return;

    const temp = bannerOrder[1];
    bannerOrder[1] = bannerOrder[clickedSlot];
    bannerOrder[clickedSlot] = temp;

    const banner = document.getElementById('featuredBanner');
    const slots = banner.querySelectorAll('.banner-slot');

    banner.classList.remove('banner-swap-anim');
    void banner.offsetWidth;
    banner.classList.add('banner-swap-anim');

    bannerOrder.forEach((itemIdx, slotIdx) => {
        const item = FEATURED_ITEMS[itemIdx];
        const slotEl = slots[slotIdx];
        slotEl.querySelector('img').src = item.image_url;
        slotEl.querySelector('img').alt = item.name;

        if (slotIdx === 1) {
            slotEl.querySelector('.slot-tag').textContent = item.category;
            slotEl.querySelector('h2').textContent = item.name;
            slotEl.querySelector('p').textContent = item.description;
            slotEl.querySelector('.hero-price').textContent = `$${parseFloat(item.price).toFixed(2)}`;
            slotEl.querySelector('button').setAttribute('onclick', `addToCart(${item.id})`);
        } else {
            slotEl.querySelector('.slot-tag').textContent = item.category;
            slotEl.querySelector('h4').textContent = item.name;
        }
    });
}

// ================= PRODUCT QUICK VIEW MODAL =================
function openProductModal(item) {
    const content = document.getElementById('productModalContent');
    content.innerHTML = `
        <img src="${item.image_url}" alt="${item.name}">
        <div>
            <span class="slot-tag">${item.category}</span>
            <h2 style="margin: 0.5rem 0;">${item.name}</h2>
            <p class="hero-price" style="margin-bottom: 1rem;">$${parseFloat(item.price).toFixed(2)}</p>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">${item.description}</p>
            <button type="button" class="btn-primary full-width" onclick="addToCart(${item.id}); closeModal('productModal');">
                + Add to Cart
            </button>
        </div>
    `;
    document.getElementById('productModal').classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

// ================= CART & ORDERS API =================
async function addToCart(productId) {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('product_id', productId);

    const res = await fetch('api.php', { method: 'POST', body: formData });
    const data = await res.json();

    if (data.status === 'ok') {
        updateCartDOM(data);
        const badge = document.getElementById('cartBadge');
        badge.classList.remove('bump');
        void badge.offsetWidth;
        badge.classList.add('bump');
        showToast('Added to cart');
    }
}

async function updateQty(productId, delta) {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('product_id', productId);
    formData.append('delta', delta);

    const res = await fetch('api.php', { method: 'POST', body: formData });
    const data = await res.json();
    if (data.status === 'ok') {
        updateCartDOM(data);
    }
}

async function refreshCartUI() {
    const res = await fetch('api.php?action=get_cart');
    const data = await res.json();
    if (data.status === 'ok') {
        updateCartDOM(data);
    }
}

function updateCartDOM(data) {
    document.getElementById('cartBadge').textContent = data.count;
    document.getElementById('cartTotal').textContent = `$${parseFloat(data.total).toFixed(2)}`;

    const container = document.getElementById('cartItemsContainer');
    const footer = document.getElementById('cartFooter');

    if (!data.items.length) {
        container.innerHTML = `<p style="color: var(--text-muted); text-align: center; margin-top: 2rem;">Your glass cart is empty.</p>`;
        footer.style.display = 'none';
        return;
    }

    footer.style.display = 'block';
    container.innerHTML = data.items.map(item => `
        <div class="cart-item">
            <img src="${item.image_url}" alt="${item.name}">
            <div class="cart-item-info">
                <h4>${item.name}</h4>
                <span>$${parseFloat(item.subtotal).toFixed(2)}</span>
            </div>
            <div class="qty-controls">
                <button type="button" class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
                <span>${item.qty}</span>
                <button type="button" class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
            </div>
        </div>
    `).join('');
}

function openCart() {
    refreshCartUI();
    document.getElementById('cartBackdrop').classList.add('open');
    document.getElementById('cartDrawer').classList.add('open');
}

function closeCart() {
    document.getElementById('cartBackdrop').classList.remove('open');
    document.getElementById('cartDrawer').classList.remove('open');
}

async function openOrders() {
    document.getElementById('ordersModal').classList.add('open');
    const list = document.getElementById('ordersList');
    list.innerHTML = `<p style="color: var(--text-muted);">Loading your orders...</p>`;

    const res = await fetch('api.php?action=get_orders');
    const data = await res.json();

    if (data.status === 'ok' && data.orders.length) {
        list.innerHTML = data.orders.map(order => `
            <div class="order-card">
                <div class="order-card-top">
                    <span>Order #${order.id}</span>
                    <span style="color: var(--accent-1);">$${parseFloat(order.total_amount).toFixed(2)}</span>
                </div>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                    Ship to: ${order.shipping_address} • ${order.created_at}
                </p>
                <div style="font-size: 0.86rem;">
                    ${order.items.map(i => `${i.quantity}× ${i.name}`).join(', ')}
                </div>
            </div>
        `).join('');
    } else {
        list.innerHTML = `<p style="color: var(--text-muted);">No orders placed yet.</p>`;
    }
}

function showToast(msg) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.classList.add('show');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => toast.classList.remove('show'), 2600);
}