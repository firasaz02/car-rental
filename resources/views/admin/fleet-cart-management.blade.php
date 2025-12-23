@extends('layouts.app')
@section('title', 'Admin Fleet Cart Management')

@section('content')
<link href="{{ asset('css/fleet-modern.css') }}" rel="stylesheet">
<div class="fleet-modern-container admin-fleet-modern">
    <div class="max-w-7xl mx-auto p-6 space-y-6 animate-slide-in-up">
        
        <!-- Admin Header -->
        <div class="fleet-modern-header">
            <h1 class="fleet-modern-title">Admin Fleet Cart Management</h1>
            <p class="fleet-modern-subtitle">Monitor and manage user cart operations in real-time</p>
            
            <!-- Admin Statistics -->
            <div class="fleet-stats-modern">
                <div class="fleet-stat-modern-card">
                    <div class="fleet-stat-modern-number" id="total-users">-</div>
                    <div class="fleet-stat-modern-label">Active Users</div>
                </div>
                <div class="fleet-stat-modern-card">
                    <div class="fleet-stat-modern-number" id="total-carts">-</div>
                    <div class="fleet-stat-modern-label">Active Carts</div>
                </div>
                <div class="fleet-stat-modern-card">
                    <div class="fleet-stat-modern-number" id="pending-approvals">-</div>
                    <div class="fleet-stat-modern-label">Pending Approvals</div>
                </div>
                <div class="fleet-stat-modern-card">
                    <div class="fleet-stat-modern-number" id="completed-orders">-</div>
                    <div class="fleet-stat-modern-label">Completed Orders</div>
                </div>
            </div>
            
            <!-- Admin Actions -->
            <div class="fleet-actions-modern">
                <button onclick="refreshData()" class="fleet-btn-modern fleet-btn-modern-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh Data
                </button>
                <button onclick="exportData()" class="fleet-btn-modern fleet-btn-modern-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Data
                </button>
                <button onclick="clearAllNotifications()" class="fleet-btn-modern fleet-btn-modern-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Clear Notifications
                </button>
            </div>
        </div>

        <!-- Real-time Notifications -->
        <div class="cart-modern-container">
            <div class="cart-modern-header">
                <h2 class="cart-modern-title">Real-time Notifications</h2>
                <div class="cart-modern-count" id="notification-count">0 notifications</div>
            </div>
            
            <div class="cart-items-modern" id="notifications-container">
                <div class="text-center py-12 text-white/70">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 5h5l-5-5v5z"></path>
                    </svg>
                    <h3 class="text-lg font-medium">No notifications</h3>
                    <p class="text-sm">User cart activities will appear here in real-time</p>
                </div>
            </div>
        </div>

        <!-- User Cart Activities -->
        <div class="cart-modern-container">
            <div class="cart-modern-header">
                <h2 class="cart-modern-title">User Cart Activities</h2>
                <div class="flex space-x-2">
                    <select id="filter-status" class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                    </select>
                    <select id="filter-user" class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
                        <option value="all">All Users</option>
                    </select>
                </div>
            </div>
            
            <div class="cart-items-modern" id="activities-container">
                <!-- Activities will be loaded here -->
                <div class="loading-skeleton-modern h-32"></div>
                <div class="loading-skeleton-modern h-32"></div>
                <div class="loading-skeleton-modern h-32"></div>
            </div>
        </div>

        <!-- Fleet Management -->
        <div class="fleet-modern-container">
            <div class="fleet-modern-header">
                <h2 class="fleet-modern-title">Fleet Management</h2>
                <p class="fleet-modern-subtitle">Manage vehicles and monitor cart usage</p>
            </div>
            
            <div class="vehicle-grid-modern" id="admin-vehicle-grid">
                <!-- Vehicles will be loaded here -->
                <div class="loading-skeleton-modern h-32"></div>
                <div class="loading-skeleton-modern h-32"></div>
                <div class="loading-skeleton-modern h-32"></div>
                <div class="loading-skeleton-modern h-32"></div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Action Modal -->
<div class="confirmation-modal-modern" id="admin-action-modal">
    <div class="confirmation-modal-modern-content">
        <div class="confirmation-modal-modern-header">
            <h3 class="confirmation-modal-modern-title" id="admin-modal-title">Admin Action</h3>
            <p class="confirmation-modal-modern-message" id="admin-modal-message">Are you sure you want to proceed?</p>
        </div>
        <div class="confirmation-modal-modern-actions">
            <button onclick="closeAdminModal()" class="confirmation-modal-modern-btn confirmation-modal-modern-btn-cancel">
                Cancel
            </button>
            <button onclick="confirmAdminAction()" class="confirmation-modal-modern-btn confirmation-modal-modern-btn-confirm" id="admin-modal-confirm-btn">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
// Admin Global State
let notifications = [];
let activities = [];
let vehicles = [];
let users = [];
let pendingAdminAction = null;

// Initialize admin dashboard
document.addEventListener('DOMContentLoaded', function() {
    initializeAdminDashboard();
    setupRealTimeUpdates();
});

// Initialize admin dashboard
async function initializeAdminDashboard() {
    await Promise.all([
        loadNotifications(),
        loadActivities(),
        loadVehicles(),
        loadUsers()
    ]);
    
    updateAdminStatistics();
    renderActivities();
    renderVehicles();
    setupFilters();
}

// Setup real-time updates
function setupRealTimeUpdates() {
    if (typeof Pusher !== 'undefined') {
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
        
        // Subscribe to admin notifications
        const channel = pusher.subscribe('admin-notifications');
        
        channel.bind('client-cart-update', function(data) {
            handleRealTimeUpdate(data);
        });
        
        // Subscribe to cart activities
        const activitiesChannel = pusher.subscribe('cart-activities');
        activitiesChannel.bind('cart-activity', function(data) {
            addActivity(data);
        });
    }
}

// Handle real-time updates
function handleRealTimeUpdate(data) {
    const notification = {
        id: Date.now(),
        type: data.action,
        user_id: data.user_id,
        data: data.data,
        timestamp: data.timestamp,
        read: false
    };
    
    notifications.unshift(notification);
    updateNotificationsDisplay();
    updateAdminStatistics();
    
    // Show toast notification
    showToast(`New ${data.action} from user ${data.user_id}`, 'info');
}

// Load notifications
async function loadNotifications() {
    try {
        const response = await fetch('/api/v1/admin/notifications');
        const data = await response.json();
        
        if (data.success) {
            notifications = data.data;
            updateNotificationsDisplay();
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

// Load activities
async function loadActivities() {
    try {
        const response = await fetch('/api/v1/admin/activities');
        const data = await response.json();
        
        if (data.success) {
            activities = data.data;
            renderActivities();
        }
    } catch (error) {
        console.error('Error loading activities:', error);
    }
}

// Load vehicles
async function loadVehicles() {
    try {
        const response = await fetch('/api/v1/vehicles');
        const data = await response.json();
        
        if (data.success) {
            vehicles = data.data;
            renderVehicles();
        }
    } catch (error) {
        console.error('Error loading vehicles:', error);
    }
}

// Load users
async function loadUsers() {
    try {
        const response = await fetch('/api/v1/admin/users');
        const data = await response.json();
        
        if (data.success) {
            users = data.data;
            updateUserFilter();
        }
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

// Update notifications display
function updateNotificationsDisplay() {
    const container = document.getElementById('notifications-container');
    const countElement = document.getElementById('notification-count');
    
    countElement.textContent = `${notifications.length} notification${notifications.length !== 1 ? 's' : ''}`;
    
    if (notifications.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-white/70">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 5h5l-5-5v5z"></path>
                </svg>
                <h3 class="text-lg font-medium">No notifications</h3>
                <p class="text-sm">User cart activities will appear here in real-time</p>
            </div>
        `;
    } else {
        container.innerHTML = notifications.map(notification => `
            <div class="cart-item-modern ${notification.read ? 'opacity-60' : ''}">
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                    ${getNotificationIcon(notification.type)}
                </div>
                <div class="cart-item-modern-info">
                    <h4>${getNotificationTitle(notification.type)}</h4>
                    <p>User ID: ${notification.user_id} • ${new Date(notification.timestamp).toLocaleString()}</p>
                    <small class="text-white/60">${JSON.stringify(notification.data)}</small>
                </div>
                <div class="cart-item-modern-actions">
                    <button onclick="markAsRead(${notification.id})" class="cart-item-modern-btn cart-item-modern-btn-confirm">
                        Mark Read
                    </button>
                    <button onclick="approveAction(${notification.id})" class="cart-item-modern-btn cart-item-modern-btn-confirm">
                        Approve
                    </button>
                </div>
            </div>
        `).join('');
    }
}

// Render activities
function renderActivities() {
    const container = document.getElementById('activities-container');
    const filteredActivities = getFilteredActivities();
    
    if (filteredActivities.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-white/70">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium">No activities found</h3>
                <p class="text-sm">User cart activities will appear here</p>
            </div>
        `;
    } else {
        container.innerHTML = filteredActivities.map(activity => `
            <div class="cart-item-modern">
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center text-white font-bold">
                    ${getActivityIcon(activity.type)}
                </div>
                <div class="cart-item-modern-info">
                    <h4>${activity.user_name || `User ${activity.user_id}`}</h4>
                    <p>${getActivityDescription(activity)} • ${new Date(activity.created_at).toLocaleString()}</p>
                    <small class="text-white/60">Status: ${activity.status}</small>
                </div>
                <div class="cart-item-modern-actions">
                    <button onclick="viewActivityDetails(${activity.id})" class="cart-item-modern-btn cart-item-modern-btn-confirm">
                        View Details
                    </button>
                    ${activity.status === 'pending' ? 
                        `<button onclick="approveActivity(${activity.id})" class="cart-item-modern-btn cart-item-modern-btn-confirm">
                            Approve
                        </button>` : ''
                    }
                </div>
            </div>
        `).join('');
    }
}

// Render vehicles for admin
function renderVehicles() {
    const container = document.getElementById('admin-vehicle-grid');
    
    if (vehicles.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12 text-white/70">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium">No vehicles available</h3>
                <p class="text-sm">Add vehicles to the fleet</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = vehicles.map(vehicle => `
        <div class="vehicle-card-modern animate-fade-in-scale">
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
                <button onclick="editVehicle(${vehicle.id})" class="vehicle-action-btn-modern vehicle-action-btn-modern-primary">
                    Edit
                </button>
                <button onclick="deleteVehicle(${vehicle.id})" class="vehicle-action-btn-modern vehicle-action-btn-modern-secondary">
                    Delete
                </button>
                <button onclick="viewVehicleUsage(${vehicle.id})" class="vehicle-action-btn-modern vehicle-action-btn-modern-secondary">
                    Usage
                </button>
            </div>
        </div>
    `).join('');
}

// Admin action functions
function markAsRead(notificationId) {
    const notification = notifications.find(n => n.id === notificationId);
    if (notification) {
        notification.read = true;
        updateNotificationsDisplay();
        
        // Update on server
        fetch(`/api/v1/admin/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    }
}

function approveAction(notificationId) {
    const notification = notifications.find(n => n.id === notificationId);
    if (notification) {
        showAdminConfirmationModal(
            'Approve Action',
            `Are you sure you want to approve this ${notification.type} action?`,
            () => {
                // Process approval
                processApproval(notification);
                notifications = notifications.filter(n => n.id !== notificationId);
                updateNotificationsDisplay();
                showSuccess('Action approved successfully');
            }
        );
    }
}

function approveActivity(activityId) {
    const activity = activities.find(a => a.id === activityId);
    if (activity) {
        showAdminConfirmationModal(
            'Approve Activity',
            `Are you sure you want to approve this activity?`,
            () => {
                // Process activity approval
                processActivityApproval(activity);
                showSuccess('Activity approved successfully');
            }
        );
    }
}

function processApproval(notification) {
    // Send approval to server
    fetch('/api/v1/admin/approve-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            notification_id: notification.id,
            action: notification.type,
            data: notification.data
        })
    });
}

function processActivityApproval(activity) {
    // Send activity approval to server
    fetch('/api/v1/admin/approve-activity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            activity_id: activity.id
        })
    });
}

// Utility functions
function getNotificationIcon(type) {
    const icons = {
        'item_confirmed': '✓',
        'checkout_completed': '🛒',
        'cart_updated': '🔄',
        'item_added': '+',
        'item_removed': '-'
    };
    return icons[type] || '📢';
}

function getNotificationTitle(type) {
    const titles = {
        'item_confirmed': 'Item Confirmed',
        'checkout_completed': 'Checkout Completed',
        'cart_updated': 'Cart Updated',
        'item_added': 'Item Added',
        'item_removed': 'Item Removed'
    };
    return titles[type] || 'Notification';
}

function getActivityIcon(type) {
    const icons = {
        'cart_operation': '🛒',
        'vehicle_selection': '🚗',
        'confirmation': '✓',
        'checkout': '💳'
    };
    return icons[type] || '📋';
}

function getActivityDescription(activity) {
    return `${activity.type} - ${activity.description || 'No description'}`;
}

function getFilteredActivities() {
    const statusFilter = document.getElementById('filter-status').value;
    const userFilter = document.getElementById('filter-user').value;
    
    return activities.filter(activity => {
        const statusMatch = statusFilter === 'all' || activity.status === statusFilter;
        const userMatch = userFilter === 'all' || activity.user_id == userFilter;
        return statusMatch && userMatch;
    });
}

function updateUserFilter() {
    const select = document.getElementById('filter-user');
    select.innerHTML = '<option value="all">All Users</option>' +
        users.map(user => `<option value="${user.id}">${user.name}</option>`).join('');
}

function setupFilters() {
    document.getElementById('filter-status').addEventListener('change', renderActivities);
    document.getElementById('filter-user').addEventListener('change', renderActivities);
}

function updateAdminStatistics() {
    document.getElementById('total-users').textContent = users.length;
    document.getElementById('total-carts').textContent = activities.filter(a => a.status === 'active').length;
    document.getElementById('pending-approvals').textContent = notifications.filter(n => !n.read).length;
    document.getElementById('completed-orders').textContent = activities.filter(a => a.status === 'completed').length;
}

function refreshData() {
    initializeAdminDashboard();
    showSuccess('Data refreshed');
}

function exportData() {
    const data = {
        notifications: notifications,
        activities: activities,
        vehicles: vehicles,
        users: users,
        export_date: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `fleet-admin-data-${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    URL.revokeObjectURL(url);
    
    showSuccess('Data exported successfully');
}

function clearAllNotifications() {
    showAdminConfirmationModal(
        'Clear All Notifications',
        'Are you sure you want to clear all notifications?',
        () => {
            notifications = [];
            updateNotificationsDisplay();
            showSuccess('All notifications cleared');
        }
    );
}

function showAdminConfirmationModal(title, message, onConfirm) {
    document.getElementById('admin-modal-title').textContent = title;
    document.getElementById('admin-modal-message').textContent = message;
    pendingAdminAction = onConfirm;
    
    const modal = document.getElementById('admin-action-modal');
    modal.classList.add('show');
}

function closeAdminModal() {
    const modal = document.getElementById('admin-action-modal');
    modal.classList.remove('show');
    pendingAdminAction = null;
}

function confirmAdminAction() {
    if (pendingAdminAction) {
        pendingAdminAction();
    }
    closeAdminModal();
}

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
</script>
@endsection
