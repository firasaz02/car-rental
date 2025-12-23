@extends('layouts.app')
@section('title', 'Reports & Analytics')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="modern-card p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Reports & Analytics</h1>
                <p class="text-lg text-gray-600">Vehicle location insights and comprehensive analytics</p>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="modern-card p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Report Filters
        </h2>
        
        <form id="report-form" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label for="date-range" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select id="date-range" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="year">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            
            <div>
                <label for="vehicle-filter" class="block text-sm font-medium text-gray-700 mb-2">Vehicle</label>
                <select id="vehicle-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <option value="all">All Vehicles</option>
                </select>
            </div>
            
            <div>
                <label for="report-type" class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                <select id="report-type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <option value="summary">Summary Report</option>
                    <option value="detailed">Detailed Report</option>
                    <option value="speed">Speed Analysis</option>
                    <option value="route">Route Analysis</option>
                    <option value="fuel">Fuel Efficiency</option>
                    <option value="maintenance">Maintenance Report</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="button" onclick="generateReport()" class="w-full btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <!-- Report Results -->
    <div id="report-results" style="display: none;" class="space-y-6">
        <!-- Report Summary -->
        <div class="modern-card p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Report Summary
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600 mb-1">Total Distance</p>
                            <p class="text-3xl font-bold text-blue-900" id="total-distance">0 km</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 mb-1">Total Time</p>
                            <p class="text-3xl font-bold text-green-900" id="total-time">0 hours</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600 mb-1">Average Speed</p>
                            <p class="text-3xl font-bold text-purple-900" id="avg-speed-report">0 km/h</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl border border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-orange-600 mb-1">Fuel Efficiency</p>
                            <p class="text-3xl font-bold text-orange-900" id="fuel-efficiency">0 L/100km</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Performance Table -->
        <div class="modern-card p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                </svg>
                Vehicle Performance
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Speed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Speed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody id="performance-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="modern-card p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 text-pink-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Performance Charts
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Speed Distribution</h3>
                    <div class="chart-container">
                        <canvas id="speedChart" width="400" height="200"></canvas>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Distance Traveled</h3>
                    <div class="chart-container">
                        <canvas id="distanceChart" width="400" height="200"></canvas>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Vehicle Status</h3>
                    <div class="chart-container">
                        <canvas id="statusChart" width="400" height="200"></canvas>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Fuel Efficiency</h3>
                    <div class="chart-container">
                        <canvas id="fuelChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- No Data Message -->
    <div id="no-data-message" class="modern-card p-12 text-center">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reports Available</h3>
        <p class="text-gray-600 mb-6">Select filters and generate a report to view analytics</p>
        <button onclick="generateReport()" class="btn btn-primary">
            Generate Sample Report
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadVehicles();
});

function loadVehicles() {
    fetch('/api/v1/vehicles')
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                const vehicleSelect = document.getElementById('vehicle-filter');
                data.data.forEach(vehicle => {
                    const option = document.createElement('option');
                    option.value = vehicle.id;
                    option.textContent = vehicle.vehicle_number + ' - ' + vehicle.driver_name;
                    vehicleSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.log('Error loading vehicles:', error));
}

function generateReport() {
    const dateRange = document.getElementById('date-range').value;
    const vehicleFilter = document.getElementById('vehicle-filter').value;
    const reportType = document.getElementById('report-type').value;
    
    // Show loading
    document.getElementById('report-results').style.display = 'block';
    document.getElementById('no-data-message').style.display = 'none';
    
    // Simulate report generation
    setTimeout(() => {
        generateSampleReport();
        createCharts();
    }, 1000);
}

function createCharts() {
    // Speed Distribution Chart
    const speedCtx = document.getElementById('speedChart').getContext('2d');
    createBarChart(speedCtx, {
        labels: ['0-30 km/h', '31-60 km/h', '61-90 km/h', '91+ km/h'],
        data: [2, 5, 3, 1],
        colors: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c'],
        title: 'Speed Distribution'
    });

    // Distance Chart
    const distanceCtx = document.getElementById('distanceChart').getContext('2d');
    createLineChart(distanceCtx, {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        data: [120, 150, 180, 200, 160, 90, 110],
        color: '#3498db',
        title: 'Weekly Distance'
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    createPieChart(statusCtx, {
        labels: ['Active', 'Inactive', 'Maintenance'],
        data: [8, 2, 1],
        colors: ['#2ecc71', '#e74c3c', '#f39c12'],
        title: 'Vehicle Status'
    });

    // Fuel Efficiency Chart
    const fuelCtx = document.getElementById('fuelChart').getContext('2d');
    createBarChart(fuelCtx, {
        labels: ['TUN-1234', 'TUN-5678', 'TUN-9012', 'TUN-3456'],
        data: [8.2, 7.5, 9.1, 8.8],
        colors: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c'],
        title: 'Fuel Efficiency (L/100km)'
    });
}

function createBarChart(ctx, config) {
    const { labels, data, colors, title } = config;
    const maxValue = Math.max(...data);
    
    // Clear canvas
    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
    
    // Chart dimensions
    const padding = 40;
    const chartWidth = ctx.canvas.width - 2 * padding;
    const chartHeight = ctx.canvas.height - 2 * padding;
    const barWidth = chartWidth / labels.length * 0.8;
    const barSpacing = chartWidth / labels.length * 0.2;
    
    // Draw bars
    labels.forEach((label, index) => {
        const barHeight = (data[index] / maxValue) * chartHeight;
        const x = padding + index * (barWidth + barSpacing);
        const y = padding + chartHeight - barHeight;
        
        // Draw bar
        ctx.fillStyle = colors[index % colors.length];
        ctx.fillRect(x, y, barWidth, barHeight);
        
        // Draw value
        ctx.fillStyle = '#2c3e50';
        ctx.font = '12px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(data[index], x + barWidth/2, y - 5);
        
        // Draw label
        ctx.fillText(label, x + barWidth/2, ctx.canvas.height - 10);
    });
    
    // Draw title
    ctx.fillStyle = '#2c3e50';
    ctx.font = 'bold 14px Arial';
    ctx.textAlign = 'center';
    ctx.fillText(title, ctx.canvas.width/2, 20);
}

function createLineChart(ctx, config) {
    const { labels, data, color, title } = config;
    const maxValue = Math.max(...data);
    
    // Clear canvas
    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
    
    // Chart dimensions
    const padding = 40;
    const chartWidth = ctx.canvas.width - 2 * padding;
    const chartHeight = ctx.canvas.height - 2 * padding;
    
    // Draw line
    ctx.strokeStyle = color;
    ctx.lineWidth = 3;
    ctx.beginPath();
    
    labels.forEach((label, index) => {
        const x = padding + (index * chartWidth) / (labels.length - 1);
        const y = padding + chartHeight - (data[index] / maxValue) * chartHeight;
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.stroke();
    
    // Draw points
    ctx.fillStyle = color;
    labels.forEach((label, index) => {
        const x = padding + (index * chartWidth) / (labels.length - 1);
        const y = padding + chartHeight - (data[index] / maxValue) * chartHeight;
        
        ctx.beginPath();
        ctx.arc(x, y, 4, 0, 2 * Math.PI);
        ctx.fill();
        
        // Draw value
        ctx.fillStyle = '#2c3e50';
        ctx.font = '12px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(data[index], x, y - 10);
        
        // Draw label
        ctx.fillText(label, x, ctx.canvas.height - 10);
    });
    
    // Draw title
    ctx.fillStyle = '#2c3e50';
    ctx.font = 'bold 14px Arial';
    ctx.textAlign = 'center';
    ctx.fillText(title, ctx.canvas.width/2, 20);
}

function createPieChart(ctx, config) {
    const { labels, data, colors, title } = config;
    const total = data.reduce((sum, value) => sum + value, 0);
    
    // Clear canvas
    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
    
    // Chart dimensions
    const centerX = ctx.canvas.width / 2;
    const centerY = ctx.canvas.height / 2;
    const radius = Math.min(centerX, centerY) - 40;
    
    let currentAngle = 0;
    
    // Draw pie slices
    data.forEach((value, index) => {
        const sliceAngle = (value / total) * 2 * Math.PI;
        
        ctx.fillStyle = colors[index % colors.length];
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
        ctx.closePath();
        ctx.fill();
        
        // Draw label
        const labelAngle = currentAngle + sliceAngle / 2;
        const labelX = centerX + Math.cos(labelAngle) * (radius + 20);
        const labelY = centerY + Math.sin(labelAngle) * (radius + 20);
        
        ctx.fillStyle = '#2c3e50';
        ctx.font = '12px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(`${labels[index]}: ${value}`, labelX, labelY);
        
        currentAngle += sliceAngle;
    });
    
    // Draw title
    ctx.fillStyle = '#2c3e50';
    ctx.font = 'bold 14px Arial';
    ctx.textAlign = 'center';
    ctx.fillText(title, centerX, 20);
}

function generateSampleReport() {
    // Sample data for demonstration
    document.getElementById('total-distance').textContent = '1,247 km';
    document.getElementById('total-time').textContent = '24.5 hours';
    document.getElementById('avg-speed-report').textContent = '51 km/h';
    document.getElementById('fuel-efficiency').textContent = '8.2 L/100km';
    
    // Generate sample performance data
    const tableBody = document.getElementById('performance-table-body');
    tableBody.innerHTML = '';
    
    const sampleData = [
        { vehicle: 'TUN-1234', driver: 'Ahmed Ben Ali', distance: '456 km', avgSpeed: '45 km/h', maxSpeed: '78 km/h', status: 'Active' },
        { vehicle: 'TUN-5678', driver: 'Fatma Trabelsi', distance: '791 km', avgSpeed: '58 km/h', maxSpeed: '85 km/h', status: 'Active' },
        { vehicle: 'TUN-9012', driver: 'Mohamed Khelil', distance: '234 km', avgSpeed: '42 km/h', maxSpeed: '65 km/h', status: 'Maintenance' },
        { vehicle: 'TUN-3456', driver: 'Aicha Mansouri', distance: '567 km', avgSpeed: '52 km/h', maxSpeed: '82 km/h', status: 'Active' }
    ];
    
    sampleData.forEach(row => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50';
        tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${row.vehicle}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.driver}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.distance}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.avgSpeed}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.maxSpeed}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${row.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                    ${row.status}
                </span>
            </td>
        `;
        tableBody.appendChild(tr);
    });
}
</script>
@endsection