{{-- Orders FAB Button (commented - using bottom nav instead)
<button class="orders-fab-btn" title="My Orders" style="display: none;">
    <i class="bi bi-bag-fill"></i>
</button>
--}}

<!-- Orders Bottom Sheet -->
<div class="orders-overlay"></div>
<div class="orders-section">
    <div class="orders-header">
        <h3 class="orders-header-title"><i class="bi bi-receipt"></i> My Orders</h3>
        <button class="orders-close-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="orders-body">
        <div class="orders-empty">
            <i class="bi bi-bag-x"></i>
            <p>No orders yet</p>
            <span>Your placed orders will appear here</span>
        </div>
    </div>
</div>

{{-- Bottom Cart Bar (commented - using bottom nav instead)
<div class="cart-bar" style="display: none;">
    <div class="cart-info">
        <span class="cart-items-count">0 Items</span>
        <span class="cart-total">₹0</span>
    </div>
    <button class="view-cart-btn">
        View Cart
        <i class="bi bi-arrow-right"></i>
    </button>
</div>
--}}

<!-- Cart Section (Bottom Sheet) -->
<div class="cart-overlay"></div>
<div class="cart-section">
    <div class="cart-header">
        <h3 class="cart-title">Your Cart</h3>
        <div class="cart-header-actions">
            <button class="cart-fullscreen-btn" title="Fullscreen">
                <i class="bi bi-arrows-fullscreen"></i>
            </button>
            <button class="cart-close-btn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
    <div class="cart-items-list"></div>
    <div class="cart-footer">
        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span class="subtotal-amount">₹0</span>
            </div>
            <div class="summary-row cart-discount-row" style="display:none;">
                <span>Discount</span>
                <span class="discount-amount" style="color:#16a34a;font-weight:600;">-₹0</span>
            </div>
            <div class="summary-row delivery-fee-row" style="{{ ($store->delivery_charg ?? 0) > 0 && $store->order_type != 3 ? '' : 'display:none;' }}">
                <span>Delivery Fee</span>
                <span class="delivery-fee-amount">₹{{ $store->delivery_charg ?? 0 }}</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span class="total-amount">₹0</span>
            </div>
        </div>
        <div class="checkout-buttons">
            @if($store->order_type == 3)
            <button class="checkout-btn order-type-btn active" data-type="dine-in"><i class="bi bi-shop"></i> Dine-In</button>
            <button class="delivery-btn order-type-btn" data-type="delivery"><i class="bi bi-truck"></i> Delivery</button>
            @elseif($store->order_type == 2)
            <button class="delivery-btn"><i class="bi bi-truck"></i> Delivery</button>
            @else
            <button class="checkout-btn"><i class="bi bi-shop"></i> Dine-In</button>
            @endif
        </div>
    </div>
</div>

<!-- Today's Specials Bottom Sheet -->
<div class="specials-popup-overlay"></div>
<div class="specials-popup">
    <div class="specials-popup-header">
        <h3><i class="bi bi-stars"></i> Today's Special</h3>
        <button class="specials-popup-close"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="specials-popup-body">
        <div class="specials-empty">
            <i class="bi bi-stars"></i>
            <p>No specials today</p>
            <span>Check back later for amazing deals!</span>
        </div>
    </div>
</div>

<!-- Bottom Navigation -->
<nav class="bottom-nav">
    <button class="bottom-nav-item active" data-action="home">
        <div class="bottom-nav-icon-wrap">
            <i class="bi bi-house-door"></i>
            <i class="bi bi-house-door-fill bottom-nav-icon-active"></i>
        </div>
        <span>{{$store->menu_buttom_home ?? 'Home'}}</span>
    </button>
    <button class="bottom-nav-item" data-action="specials">
        <div class="bottom-nav-icon-wrap">
            <i class="bi bi-stars"></i>
            <i class="bi bi-stars bottom-nav-icon-active"></i>
        </div>
        <span>{{$store->menu_buttom_special ?? 'Specials'}}</span>
    </button>
    <button class="bottom-nav-item" data-action="cart">
        <div class="bottom-nav-icon-wrap">
            <i class="bi bi-bag"></i>
            <i class="bi bi-bag-fill bottom-nav-icon-active"></i>
            <span class="bottom-nav-badge" style="display: none;">0</span>
        </div>
        <span>{{$store->menu_buttom_cart ?? 'Cart'}}</span>
    </button>
    <button class="bottom-nav-item" data-action="orders">
        <div class="bottom-nav-icon-wrap">
            <i class="bi bi-receipt"></i>
            <i class="bi bi-receipt-cutoff bottom-nav-icon-active"></i>
        </div>
        <span>{{$store->menu_buttom_orders ?? 'Orders'}}</span>
    </button>
</nav>
