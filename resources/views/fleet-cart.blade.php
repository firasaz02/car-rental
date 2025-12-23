@extends('layouts.app')
@section('title', 'Fleet Cart System')

@section('content')
<link href="{{ asset('css/fleet-modern.css') }}" rel="stylesheet">
<div class="fleet-modern-container user-fleet-modern">
    <div class="max-w-7xl mx-auto p-6 space-y-6 animate-slide-in-up">
        
        <!-- Fleet Header -->
        <div class="fleet-modern-header">
            <h1 class="fleet-modern-title">Fleet Cart System</h1>
            <p class="fleet-modern-subtitle">Select vehicles, confirm your choices, and manage your fleet operations</p>
            
            <!-- Fleet Statistics -->
            <div class="fleet-stats-modern">
                <div class="fleet-stat-modern-card">
                    <div class="fleet-stat-modern-number" id="total-vehicles">-</div>
                    <div class="fleet-stat-modern-label">Total Vehicles</div>
                </div>
                <div class="fleet-stat-modern-card">
                    <div class="fleet-stat-modern-number" id="cart-items">0</div>
                    <div class="fleet-stat-modern-label">Cart Items</div>
                </div>
                <div class="fleet-stat-modern-card">
                    <div class="fleet-stat-modern-number" id="confirmed-items">0</div>
                    <div class="fleet-stat-modern-label">Confirmed</div>
                </div>
                <div class="fleet-stat-modern-card">
                    <div class="fleet-stat-modern-number" id="pending-items">0</div>
                    <div class="fleet-stat-modern-label">Pending</div>
                </div>
            </div>
            
            <!-- Fleet Actions -->
            <div class="fleet-actions-modern">
                <button onclick="loadVehicles()" class="fleet-btn-modern fleet-btn-modern-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh Fleet
                </button>
                <button onclick="clearCart()" class="fleet-btn-modern fleet-btn-modern-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Clear Cart
                </button>
                <button onclick="confirmAllItems()" class="fleet-btn-modern fleet-btn-modern-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Confirm All
                </button>
            </div>
        </div>

        <!-- Cart System -->
        <div class="cart-modern-container">
            <div class="cart-modern-header">
                <h2 class="cart-modern-title">Fleet Cart</h2>
                <div class="cart-modern-count" id="cart-count">0 items</div>
            </div>
            
            <div class="cart-items-modern" id="cart-items-container">
                <div class="text-center py-12 text-white/70">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <h3 class="text-lg font-medium">Your cart is empty</h3>
                    <p class="text-sm">Add vehicles from the fleet below to get started</p>
                </div>
            </div>
            
            <div class="cart-modern-footer" id="cart-footer" style="display: none;">
                <div class="cart-modern-total">
                    Total: <span id="cart-total">0</span> vehicles
                </div>
                <div class="cart-modern-actions">
                    <button onclick="clearCart()" class="cart-modern-btn cart-modern-btn-clear">
                        Clear Cart
                    </button>
                    <button onclick="proceedToCheckout()" class="cart-modern-btn cart-modern-btn-checkout">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>

        <!-- Vehicle Fleet -->
        <div class="fleet-modern-container">
            <div class="fleet-modern-header">
                <h2 class="fleet-modern-title">Available Fleet</h2>
                <p class="fleet-modern-subtitle">Select vehicles to add to your cart</p>
            </div>
            
            <div class="vehicle-grid-modern" id="vehicle-grid">
                <!-- Vehicles will be loaded here -->
                <div class="loading-skeleton-modern h-32"></div>
                <div class="loading-skeleton-modern h-32"></div>
                <div class="loading-skeleton-modern h-32"></div>
                <div class="loading-skeleton-modern h-32"></div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="confirmation-modal-modern" id="confirmation-modal">
    <div class="confirmation-modal-modern-content">
        <div class="confirmation-modal-modern-header">
            <h3 class="confirmation-modal-modern-title" id="modal-title">Confirm Action</h3>
            <p class="confirmation-modal-modern-message" id="modal-message">Are you sure you want to proceed?</p>
        </div>
        <div class="confirmation-modal-modern-actions">
            <button onclick="closeModal()" class="confirmation-modal-modern-btn confirmation-modal-modern-btn-cancel">
                Cancel
            </button>
            <button onclick="confirmAction()" class="confirmation-modal-modern-btn confirmation-modal-modern-btn-confirm" id="modal-confirm-btn">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
// Global Cart State
let cart = [];
let vehicles = [];
let pendingAction = null;

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadVehicles();
    updateCartDisplay();
});

// Load vehicles from API
async function loadVehicles() {
    try {
        const response = await fetch('/api/v1/vehicles');
        const data = await response.json();
        
        if (data.success) {
            vehicles = data.data;
            renderVehicles();
            updateStatistics();
        } else {
            showError('Failed to load vehicles');
        }
    } catch (error) {
        console.error('Error loading vehicles:', error);
        showError('Error loading vehicles');
    }
}

// Render vehicles in the grid
function renderVehicles() {
    const container = document.getElementById('vehicle-grid');
    container.innerHTML = '';
    
    if (vehicles.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12 text-white/70">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium">No vehicles available</h3>
                <p class="text-sm">Check back later for new vehicles</p>
            </div>
        `;
        return;
    }
    
    vehicles.forEach(vehicle => {
        const isInCart = cart.some(item => item.id === vehicle.id);
        const cartItem = cart.find(item => item.id === vehicle.id);
        
        const card = document.createElement('div');
        card.className = 'vehicle-card-modern animate-fade-in-scale';
        card.innerHTML = `
            <div class="vehicle-card-modern-header">
                ${vehicle.image_url ? 
                    `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number}" class="vehicle-image-modern" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` :
                    ''
                }
                <div class="vehicle-image-modern-placeholder" style="display: ${vehicle.image_url ? 'none' : 'flex'}">
                    ${getVehicleIcon(vehicle.vehicle_type)}
                </div>
                <div class="vehicle-info-modern">
                    <h3>${vehicle.vehicle_number}</h3>
                    <p>${vehicle.driver_name || 'No driver assigned'}</p>
                </div>
            </div>
            
            <div class="vehicle-details-modern">
                <div class="vehicle-detail-modern">
                    <svg class="vehicle-detail-modern-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>${vehicle.vehicle_type}</span>
                </div>
                <div class="vehicle-detail-modern">
                    <svg class="vehicle-detail-modern-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>${vehicle.phone || 'No phone'}</span>
                </div>
                <div class="vehicle-detail-modern">
                    <svg class="vehicle-detail-modern-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                    <span>${vehicle.latest_location ? 'Tracked' : 'No location'}</span>
                </div>
                <div class="vehicle-detail-modern">
                    <svg class="vehicle-detail-modern-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>${vehicle.latest_location ? new Date(vehicle.latest_location.recorded_at).toLocaleTimeString() : 'N/A'}</span>
                </div>
            </div>
            
            <div class="vehicle-status-modern ${vehicle.is_active ? 'vehicle-status-modern-active' : 'vehicle-status-modern-inactive'}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                ${vehicle.is_active ? 'Active' : 'Inactive'}
            </div>
            
            <div class="vehicle-actions-modern">
                ${isInCart ? 
                    `<button onclick="removeFromCart(${vehicle.id})" class="vehicle-action-btn-modern vehicle-action-btn-modern-secondary">
                        Remove from Cart
                    </button>
                    ${cartItem && !cartItem.confirmed ? 
                        `<button onclick="confirmCartItem(${vehicle.id})" class="vehicle-action-btn-modern vehicle-action-btn-modern-primary">
                            Confirm
                        </button>` : 
                        cartItem && cartItem.confirmed ? 
                        `<span class="vehicle-action-btn-modern vehicle-action-btn-modern-primary" style="background: var(--success-gradient); cursor: default;">
                            ✓ Confirmed
                        </span>` : ''
                    }` :
                    `<button onclick="addToCart(${vehicle.id})" class="vehicle-action-btn-modern vehicle-action-btn-modern-primary">
                        Add to Cart
                    </button>`
                }
            </div>
        `;
        
        container.appendChild(card);
    });
}

// Add vehicle to cart
function addToCart(vehicleId) {
    const vehicle = vehicles.find(v => v.id === vehicleId);
    if (!vehicle) return;
    
    const cartItem = {
        id: vehicle.id,
        vehicle_number: vehicle.vehicle_number,
        driver_name: vehicle.driver_name,
        vehicle_type: vehicle.vehicle_type,
        phone: vehicle.phone,
        is_active: vehicle.is_active,
        image_url: vehicle.image_url,
        added_at: new Date().toISOString(),
        confirmed: false
    };
    
    cart.push(cartItem);
    updateCartDisplay();
    renderVehicles();
    showSuccess(`${vehicle.vehicle_number} added to cart`);
}

// Remove vehicle from cart
function removeFromCart(vehicleId) {
    const vehicle = vehicles.find(v => v.id === vehicleId);
    cart = cart.filter(item => item.id !== vehicleId);
    updateCartDisplay();
    renderVehicles();
    showSuccess(`${vehicle.vehicle_number} removed from cart`);
}

// Confirm cart item
function confirmCartItem(vehicleId) {
    const cartItem = cart.find(item => item.id === vehicleId);
    if (cartItem) {
        cartItem.confirmed = true;
        cartItem.confirmed_at = new Date().toISOString();
        updateCartDisplay();
        renderVehicles();
        showSuccess(`${cartItem.vehicle_number} confirmed`);
        
        // Notify admin immediately
        notifyAdmin('item_confirmed', cartItem);
    }
}

// Update cart display
function updateCartDisplay() {
    const container = document.getElementById('cart-items-container');
    const footer = document.getElementById('cart-footer');
    const countElement = document.getElementById('cart-count');
    const totalElement = document.getElementById('cart-total');
    
    countElement.textContent = `${cart.length} item${cart.length !== 1 ? 's' : ''}`;
    totalElement.textContent = cart.length;
    
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-white/70">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <h3 class="text-lg font-medium">Your cart is empty</h3>
                <p class="text-sm">Add vehicles from the fleet below to get started</p>
            </div>
        `;
        footer.style.display = 'none';
    } else {
        container.innerHTML = cart.map(item => `
            <div class="cart-item-modern">
                ${item.image_url ? 
                    `<img src="${item.image_url}" alt="${item.vehicle_number}" class="cart-item-modern-image">` :
                    `<div class="cart-item-modern-image" style="background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: bold;">
                        ${getVehicleIcon(item.vehicle_type)}
                    </div>`
                }
                <div class="cart-item-modern-info">
                    <h4>${item.vehicle_number}</h4>
                    <p>${item.driver_name || 'No driver'} • ${item.vehicle_type}</p>
                    <small class="text-white/60">Added: ${new Date(item.added_at).toLocaleString()}</small>
                    ${item.confirmed ? `<small class="text-green-300">✓ Confirmed: ${new Date(item.confirmed_at).toLocaleString()}</small>` : ''}
                </div>
                <div class="cart-item-modern-actions">
                    <button onclick="removeFromCart(${item.id})" class="cart-item-modern-btn cart-item-modern-btn-remove">
                        Remove
                    </button>
                    ${!item.confirmed ? 
                        `<button onclick="confirmCartItem(${item.id})" class="cart-item-modern-btn cart-item-modern-btn-confirm">
                            Confirm
                        </button>` : 
                        `<span class="cart-item-modern-btn" style="background: var(--success-gradient); cursor: default;">
                            Confirmed
                        </span>`
                    }
                </div>
            </div>
        `).join('');
        footer.style.display = 'flex';
    }
    
    updateStatistics();
}

// Clear cart
function clearCart() {
    if (cart.length === 0) return;
    
    showConfirmationModal(
        'Clear Cart',
        `Are you sure you want to remove all ${cart.length} items from your cart?`,
        () => {
            cart = [];
            updateCartDisplay();
            renderVehicles();
            showSuccess('Cart cleared');
        }
    );
}

// Confirm all items
function confirmAllItems() {
    const unconfirmedItems = cart.filter(item => !item.confirmed);
    if (unconfirmedItems.length === 0) {
        showInfo('All items are already confirmed');
        return;
    }
    
    showConfirmationModal(
        'Confirm All Items',
        `Are you sure you want to confirm all ${unconfirmedItems.length} unconfirmed items?`,
        () => {
            unconfirmedItems.forEach(item => {
                item.confirmed = true;
                item.confirmed_at = new Date().toISOString();
                notifyAdmin('item_confirmed', item);
            });
            updateCartDisplay();
            renderVehicles();
            showSuccess(`Confirmed ${unconfirmedItems.length} items`);
        }
    );
}

// Proceed to checkout
function proceedToCheckout() {
    const unconfirmedItems = cart.filter(item => !item.confirmed);
    if (unconfirmedItems.length > 0) {
        showConfirmationModal(
            'Checkout with Unconfirmed Items',
            `You have ${unconfirmedItems.length} unconfirmed items. Do you want to proceed anyway?`,
            () => {
                processCheckout();
            }
        );
    } else {
        processCheckout();
    }
}

// Process checkout
async function processCheckout() {
    try {
        const response = await fetch('/api/v1/cart/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                items: cart,
                user_id: {{ auth()->id() ?? 'null' }}
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Checkout completed successfully!');
            cart = [];
            updateCartDisplay();
            renderVehicles();
            
            // Notify admin
            notifyAdmin('checkout_completed', { items: cart, user_id: {{ auth()->id() ?? 'null' }} });
        } else {
            showError(data.message || 'Checkout failed');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        showError('Checkout failed. Please try again.');
    }
}

// Update statistics
function updateStatistics() {
    document.getElementById('total-vehicles').textContent = vehicles.length;
    document.getElementById('cart-items').textContent = cart.length;
    document.getElementById('confirmed-items').textContent = cart.filter(item => item.confirmed).length;
    document.getElementById('pending-items').textContent = cart.filter(item => !item.confirmed).length;
}

// Show confirmation modal
function showConfirmationModal(title, message, onConfirm) {
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-message').textContent = message;
    pendingAction = onConfirm;
    
    const modal = document.getElementById('confirmation-modal');
    modal.classList.add('show');
}

// Close modal
function closeModal() {
    const modal = document.getElementById('confirmation-modal');
    modal.classList.remove('show');
    pendingAction = null;
}

// Confirm action
function confirmAction() {
    if (pendingAction) {
        pendingAction();
    }
    closeModal();
}

// Notify admin
function notifyAdmin(action, data) {
    // Send real-time notification to admin
    if (window.pusher) {
        const channel = window.pusher.subscribe('admin-notifications');
        channel.trigger('client-cart-update', {
            action: action,
            data: data,
            user_id: {{ auth()->id() ?? 'null' }},
            timestamp: new Date().toISOString()
        });
    }
    
    // Also send to server
    fetch('/api/v1/admin/notify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action,
            data: data,
            user_id: {{ auth()->id() ?? 'null' }}
        })
    }).catch(error => console.error('Admin notification failed:', error));
}

// Utility functions
function getVehicleIcon(type) {
    const icons = {
        'Car': '🚙',
        'Truck': '🚛',
        'Van': '🚐',
        'Motorcycle': '🏍️',
        'Bus': '🚌',
        'Taxi': '🚕',
        'SUV': '🚗',
        'Sedan': '🚘',
        'Pickup': '🛻',
        'Emergency': '🚑'
    };
    return icons[type] || '🚙';
}

function showSuccess(message) {
    showToast(message, 'success');
}

function showError(message) {
    showToast(message, 'error');
}

function showInfo(message) {
    showToast(message, 'info');
}

function showToast(message, type) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg animate-slide-in-right`;
    toast.textContent = message;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// Initialize Pusher for real-time updates
if (typeof Pusher !== 'undefined') {
    window.pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });
}
</script>
@endsection
