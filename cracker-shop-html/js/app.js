/**
 * Full E-Commerce JavaScript Application
 * Sri Kannan Pattasu Ulagam (HTML Version)
 */

// Product Inventory Data (Real Cracker Photos)
const PRODUCTS = [
    {
        id: 1,
        name: "10cm Electric Sparklers (10 Pcs)",
        cat: "sparklers",
        catName: "Electric Sparklers",
        mrp: 150,
        price: 75,
        unit: "Pack of 10",
        stock: 200,
        image: "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&q=80",
        desc: "Bright golden wire sparklers suitable for kids and family celebrations. Low smoke formulation."
    },
    {
        id: 2,
        name: "30cm Color Sparklers (5 Pcs)",
        cat: "sparklers",
        catName: "Electric Sparklers",
        mrp: 300,
        price: 149,
        unit: "Pack of 5",
        stock: 150,
        image: "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&q=80",
        desc: "Extra-long multi-color sparklers offering long burning time and brilliant red, green, and gold sparks."
    },
    {
        id: 3,
        name: "Big Asoka Ground Chakkar (10 Pcs)",
        cat: "chakkars",
        catName: "Ground Chakkars",
        mrp: 400,
        price: 199,
        unit: "Box of 10",
        stock: 120,
        image: "https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?w=600&q=80",
        desc: "High-speed spinning ground wheel emitting bright white and red starry sparks."
    },
    {
        id: 4,
        name: "Special Wheel Chakkar Deluxe",
        cat: "chakkars",
        catName: "Ground Chakkars",
        mrp: 550,
        price: 280,
        unit: "Box of 10",
        stock: 80,
        image: "https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?w=600&q=80",
        desc: "Heavy-duty long spinning ground wheel with dual color changing fireworks effect."
    },
    {
        id: 5,
        name: "Flower Pots Special (10 Pcs)",
        cat: "flower-pots",
        catName: "Flower Pots",
        mrp: 450,
        price: 220,
        unit: "Box of 10",
        stock: 100,
        image: "https://images.unsplash.com/photo-1498931299472-f7a63a5a1cfa?w=600&q=80",
        desc: "Classic golden fountain erupting up to 10 feet into the air."
    },
    {
        id: 6,
        name: "Color Change Fountain Deluxe (2 Pcs)",
        cat: "flower-pots",
        catName: "Flower Pots",
        mrp: 700,
        price: 399,
        unit: "Pack of 2",
        stock: 60,
        image: "https://images.unsplash.com/photo-1498931299472-f7a63a5a1cfa?w=600&q=80",
        desc: "Tri-stage color changing giant fountain reaching 15 feet high with crackling stars."
    },
    {
        id: 7,
        name: "Whistling Sky Rockets (10 Pcs)",
        cat: "rockets",
        catName: "Sky Rockets",
        mrp: 650,
        price: 329,
        unit: "Box of 10",
        stock: 90,
        image: "https://images.unsplash.com/photo-1513151233558-d860c5398176?w=600&q=80",
        desc: "High velocity aerial rockets with a thrilling whistling sound and golden rain finish."
    },
    {
        id: 8,
        name: "12 Shot Multi-Color Sky Shell",
        cat: "rockets",
        catName: "Sky Rockets",
        mrp: 1200,
        price: 699,
        unit: "Box of 1",
        stock: 50,
        image: "https://images.unsplash.com/photo-1513151233558-d860c5398176?w=600&q=80",
        desc: "Repeater aerial repeat shell discharging 12 colorful stars with loud crackles in sequence."
    },
    {
        id: 9,
        name: "28 Chora Sound Crackers",
        cat: "sound",
        catName: "Sound Crackers",
        mrp: 250,
        price: 120,
        unit: "Box of 1",
        stock: 150,
        image: "https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?w=600&q=80",
        desc: "Loud traditional sound cracker strip for inauguration and festive celebration."
    },
    {
        id: 10,
        name: "1000 Wala Heavy Garland Cracker",
        cat: "sound",
        catName: "Sound Crackers",
        mrp: 1800,
        price: 999,
        unit: "Box of 1",
        stock: 40,
        image: "https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?w=600&q=80",
        desc: "Long continuous garland cracker producing impressive loud burst sequence."
    },
    {
        id: 11,
        name: "Diwali Family Dhamaka Combo Pack",
        cat: "combos",
        catName: "Gift Boxes",
        mrp: 3500,
        price: 1799,
        unit: "Super Box",
        stock: 30,
        image: "https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=600&q=80",
        desc: "Complete family assortment box containing 25 distinct varieties of sparklers, pots, chakkars & rockets."
    },
    {
        id: 12,
        name: "Kids Delight Safe Mini Combo",
        cat: "combos",
        catName: "Gift Boxes",
        mrp: 1500,
        price: 799,
        unit: "Combo Pack",
        stock: 45,
        image: "https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=600&q=80",
        desc: "Child-friendly low-sound sparklers, mini pots, pencil sparklers, and pop-pops assortment."
    }
];

// App State Management
let cart = JSON.parse(localStorage.getItem('sk_cart')) || [];
let orders = JSON.parse(localStorage.getItem('sk_orders')) || [];

// Price Formatter Helper
function formatPrice(amount) {
    return '₹ ' + parseFloat(amount).toFixed(2);
}

// Discount Calculator
function getDiscount(mrp, price) {
    return Math.round(((mrp - price) / mrp) * 100);
}

// DOM Elements
document.addEventListener('DOMContentLoaded', () => {
    renderProducts(PRODUCTS);
    updateCartUI();
    renderOrdersUI();

    // Category Filter Buttons
    document.querySelectorAll('#categoryFilterBtns .filter-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('#categoryFilterBtns .filter-btn').forEach(b => {
                b.classList.remove('btn-danger', 'active');
                b.classList.add('btn-outline-danger');
            });
            e.target.classList.remove('btn-outline-danger');
            e.target.classList.add('btn-danger', 'active');

            const category = e.target.dataset.cat;
            if (category === 'all') {
                renderProducts(PRODUCTS);
            } else {
                const filtered = PRODUCTS.filter(p => p.cat === category);
                renderProducts(filtered);
            }
        });
    });

    // Live Search Event
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            const filtered = PRODUCTS.filter(p => 
                p.name.toLowerCase().includes(query) || 
                p.desc.toLowerCase().includes(query) ||
                p.catName.toLowerCase().includes(query)
            );
            renderProducts(filtered);
        });
    }

    // Checkout Form Submit Handler
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if (cart.length === 0) return;

            const name = document.getElementById('custName').value;
            const phone = document.getElementById('custPhone').value;
            const address = document.getElementById('custAddress').value;
            const city = document.getElementById('custCity').value;
            const state = document.getElementById('custState').value;
            const pincode = document.getElementById('custPincode').value;

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const orderNum = 'ORD-' + Math.floor(100000 + Math.random() * 900000);

            const newOrder = {
                orderNum: orderNum,
                date: new Date().toLocaleString(),
                name: name,
                phone: phone,
                address: `${address}, ${city}, ${state} - ${pincode}`,
                total: subtotal,
                items: [...cart],
                status: 'Processing'
            };

            orders.unshift(newOrder);
            localStorage.setItem('sk_orders', JSON.stringify(orders));

            // Clear Cart
            cart = [];
            localStorage.setItem('sk_cart', JSON.stringify(cart));
            updateCartUI();
            renderOrdersUI();

            // Hide Modal
            const checkoutModal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
            if (checkoutModal) checkoutModal.hide();

            alert(`🎉 Order Placed Successfully!\n\nOrder Number: ${orderNum}\nRecipient: ${name}\nTotal Amount: ${formatPrice(subtotal)}\n\nYour fireworks package is being prepared for dispatch!`);
        });
    }
});

// Render Products Grid
function renderProducts(items) {
    const container = document.getElementById('productsGridContainer');
    if (!container) return;

    if (items.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5 glass-card">
                <i class="bi bi-emoji-frown display-1 text-muted"></i>
                <h4 class="mt-3 fw-bold">No Fireworks Found</h4>
                <p class="text-muted">Try selecting a different category or search term.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = items.map(p => {
        const discount = getDiscount(p.mrp, p.price);
        return `
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card h-100">
                    ${discount > 0 ? `<span class="discount-badge">${discount}% OFF</span>` : ''}
                    <div class="card-img-wrapper" onclick="openProductModal(${p.id})">
                        <img src="${p.image}" class="card-img-top" alt="${p.name}">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-light text-secondary border mb-2 fs-8">${p.catName}</span>
                            <h6 class="fw-bold text-dark mb-1 text-truncate-2" onclick="openProductModal(${p.id})" style="cursor:pointer;">
                                ${p.name}
                            </h6>
                            <small class="text-muted d-block mb-2 fs-8">${p.unit}</small>
                        </div>
                        
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="price-offer">${formatPrice(p.price)}</span>
                                <span class="price-mrp">${formatPrice(p.mrp)}</span>
                            </div>

                            <button class="btn btn-danger btn-add-cart w-100 rounded-pill fw-semibold shadow-sm" onclick="addToCart(${p.id})">
                                <i class="bi bi-cart-plus me-1"></i> Add To Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Add Item to Cart
function addToCart(productId) {
    const product = PRODUCTS.find(p => p.id === productId);
    if (!product) return;

    const existing = cart.find(item => item.id === productId);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            qty: 1
        });
    }

    localStorage.setItem('sk_cart', JSON.stringify(cart));
    updateCartUI();
}

// Update Cart Quantity
function updateCartQty(productId, delta) {
    const item = cart.find(i => i.id === productId);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) {
            cart = cart.filter(i => i.id !== productId);
        }
        localStorage.setItem('sk_cart', JSON.stringify(cart));
        updateCartUI();
    }
}

// Update Cart Drawer UI
function updateCartUI() {
    const badge = document.getElementById('cartBadgeCount');
    const container = document.getElementById('cartItemsList');
    const subtotalEl = document.getElementById('cartSubtotal');
    const grandTotalEl = document.getElementById('cartGrandTotal');
    const checkoutBtn = document.getElementById('btnOpenCheckout');

    const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

    if (badge) badge.innerText = totalQty;

    if (container) {
        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                    <h5 class="fw-bold mt-3">Your Cart is Empty</h5>
                    <p class="text-muted fs-7">Add crackers from catalog to checkout.</p>
                </div>
            `;
            if (checkoutBtn) checkoutBtn.disabled = true;
        } else {
            container.innerHTML = cart.map(item => `
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <img src="${item.image}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="fw-bold mb-0 fs-7">${item.name}</h6>
                            <small class="text-danger fw-semibold">${formatPrice(item.price)}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-secondary px-2 py-0" onclick="updateCartQty(${item.id}, -1)">-</button>
                        <span class="fw-bold fs-7">${item.qty}</span>
                        <button class="btn btn-sm btn-outline-secondary px-2 py-0" onclick="updateCartQty(${item.id}, 1)">+</button>
                    </div>
                </div>
            `).join('');
            if (checkoutBtn) checkoutBtn.disabled = false;
        }
    }

    if (subtotalEl) subtotalEl.innerText = formatPrice(subtotal);
    if (grandTotalEl) grandTotalEl.innerText = formatPrice(subtotal);
}

// Open Product Quick View Modal
function openProductModal(productId) {
    const p = PRODUCTS.find(item => item.id === productId);
    if (!p) return;

    const discount = getDiscount(p.mrp, p.price);
    const body = document.getElementById('productModalBody');
    if (!body) return;

    body.innerHTML = `
        <div class="row g-4 align-items-center">
            <div class="col-md-6 text-center">
                <img src="${p.image}" class="img-fluid rounded-3" style="max-height: 300px;">
            </div>
            <div class="col-md-6">
                <span class="badge bg-danger-subtle text-danger border mb-2 px-3 py-1 fs-7">${p.catName}</span>
                <h4 class="fw-extrabold text-dark mb-2">${p.name}</h4>
                <small class="text-muted d-block mb-3">Unit: <strong>${p.unit}</strong></small>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="price-offer display-6 text-danger">${formatPrice(p.price)}</span>
                    <span class="price-mrp fs-5">${formatPrice(p.mrp)}</span>
                    <span class="badge bg-success px-3 py-2 ms-auto">${discount}% OFF</span>
                </div>

                <p class="text-muted fs-7 mb-4">${p.desc}</p>

                <button class="btn btn-danger btn-lg w-100 rounded-pill fw-bold shadow" onclick="addToCart(${p.id}); bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();">
                    <i class="bi bi-cart-plus me-1"></i> Add To Cart
                </button>
            </div>
        </div>
    `;

    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
}

// Render Order History Modal UI
function renderOrdersUI() {
    const container = document.getElementById('ordersHistoryContainer');
    if (!container) return;

    if (orders.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-bag-x display-1 text-muted"></i>
                <h5 class="fw-bold mt-3">No Past Orders Found</h5>
                <p class="text-muted fs-7">Your completed orders will appear here.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = orders.map(o => `
        <div class="card border mb-3 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <strong class="text-dark me-2">${o.orderNum}</strong>
                    <small class="text-muted">${o.date}</small>
                </div>
                <div>
                    <span class="badge bg-success me-2">${o.status}</span>
                    <strong class="text-danger">${formatPrice(o.total)}</strong>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted fs-7 mb-2"><strong>Deliver To:</strong> ${o.name} (${o.phone}) - ${o.address}</p>
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            ${o.items.map(i => `
                                <tr>
                                    <td>${i.name}</td>
                                    <td>${formatPrice(i.price)}</td>
                                    <td>${i.qty}</td>
                                    <td class="fw-bold text-danger">${formatPrice(i.price * i.qty)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `).join('');
}
