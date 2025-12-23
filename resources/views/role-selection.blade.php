<!DOCTYPE html>
<html lang="en" 
      x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true'
      }"
      x-init="
        $watch('darkMode', val => {
          localStorage.setItem('darkMode', val);
          document.documentElement.classList.toggle('dark', val);
        });
        if (darkMode) document.documentElement.classList.add('dark');
      "
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Tracking System - Choose Your Role</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .car-rental-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 25%, #06b6d4 50%, #10b981 75%, #f59e0b 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }
        
        .car-rental-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .car-rental-bg::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M30 30c0-16.569 13.431-30 30-30v60c-16.569 0-30-13.431-30-30z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }
        
        .dark .car-rental-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #334155 50%, #475569 75%, #64748b 100%);
        }
        
        .car-themed-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .car-themed-card h3 {
            color: #000000 !important;
            font-weight: 700 !important;
        }
        
        .car-themed-card p {
            color: #1a1a1a !important;
            font-weight: 500 !important;
        }
        
        .car-themed-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }
        
        .car-themed-card:hover::before {
            left: 100%;
        }
        
        .car-themed-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 
                0 35px 70px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }
        
        .dark .car-themed-card {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(71, 85, 105, 0.3);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        
        .dark .car-themed-card h3 {
            color: #ffffff !important;
        }
        
        .dark .car-themed-card p {
            color: #e5e7eb !important;
        }
        
        .dark .car-themed-card:hover {
            box-shadow: 
                0 35px 70px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        
        .car-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
        
        .admin-icon {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
        }
        
        .chauffeur-icon {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
        }
        
        .user-icon {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
        }
        
        .form-step {
            display: none;
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .form-step.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, #e5e7eb 0%, #3b82f6 50%, #e5e7eb 100%);
            border-radius: 2px;
            z-index: 1;
        }
        
        .step {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 2;
            background: #f8fafc;
            color: #64748b;
            border: 4px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .step.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-color: #3b82f6;
            transform: scale(1.15);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        }
        
        .step.completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-color: #10b981;
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }
        
        .step.completed::after {
            content: '✓';
            position: absolute;
            font-size: 1.4rem;
            font-weight: bold;
        }
        
        .car-input {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
        }
        
        .car-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
        }
        
        .dark .car-input {
            background: rgba(30, 41, 59, 0.9);
            border-color: #475569;
            color: #f1f5f9;
        }
        
        .dark .car-input:focus {
            border-color: #3b82f6;
            background: rgba(30, 41, 59, 1);
        }
        
        .car-button {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 12px;
        }
        
        .car-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }
        
        .car-button:hover::before {
            left: 100%;
        }
        
        .car-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .car-button:active {
            transform: translateY(-1px);
        }
        
        .tab-button {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border-radius: 12px 12px 0 0;
        }
        
        .tab-button.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3);
        }
        
        .tab-button::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 4px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2px;
        }
        
        .tab-button.active::after {
            width: 100%;
        }
        
        .fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in-right {
            animation: slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }
        
        .message-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);
            border-radius: 12px;
        }
        
        .message-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            box-shadow: 0 15px 35px rgba(239, 68, 68, 0.3);
            border-radius: 12px;
        }
        
        .dark .message-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .dark .message-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .car-rental-title {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .car-rental-subtitle {
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .floating-cars {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        
        .floating-cars::before {
            content: '🚗';
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: float 20s infinite linear;
            top: 20%;
            left: -10%;
        }
        
        .floating-cars::after {
            content: '🚙';
            position: absolute;
            font-size: 1.5rem;
            opacity: 0.1;
            animation: float 25s infinite linear reverse;
            top: 60%;
            right: -10%;
        }
        
        @keyframes float {
            0% {
                transform: translateX(-100px);
            }
            100% {
                transform: translateX(calc(100vw + 100px));
            }
        }
        
        /* Policy Modal Styles */
        .policy-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .policy-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .policy-modal {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            transform: scale(0.9) translateY(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        
        .policy-modal-overlay.active .policy-modal {
            transform: scale(1) translateY(0);
        }
        
        .dark .policy-modal {
            background: rgba(15, 23, 42, 0.98);
            border: 1px solid rgba(71, 85, 105, 0.3);
        }
        
        .policy-modal-header {
            padding: 2rem;
            border-bottom: 2px solid rgba(226, 232, 240, 0.5);
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            text-align: center;
        }
        
        .dark .policy-modal-header {
            border-bottom-color: rgba(71, 85, 105, 0.5);
        }
        
        .policy-modal-content {
            padding: 2rem;
            overflow-y: auto;
            flex: 1;
            color: #1f2937;
        }
        
        .dark .policy-modal-content {
            color: #f1f5f9;
        }
        
        .policy-section {
            margin-bottom: 2rem;
        }
        
        .policy-section h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1e40af;
            display: flex;
            align-items: center;
        }
        
        .dark .policy-section h3 {
            color: #60a5fa;
        }
        
        .policy-section p,
        .policy-section ul {
            line-height: 1.7;
            margin-bottom: 1rem;
            color: #4b5563;
        }
        
        .dark .policy-section p,
        .dark .policy-section ul {
            color: #cbd5e1;
        }
        
        .policy-section ul {
            padding-left: 1.5rem;
            list-style-type: disc;
        }
        
        .policy-section li {
            margin-bottom: 0.5rem;
        }
        
        .policy-modal-footer {
            padding: 1.5rem 2rem;
            border-top: 2px solid rgba(226, 232, 240, 0.5);
            background: rgba(249, 250, 251, 0.5);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .dark .policy-modal-footer {
            border-top-color: rgba(71, 85, 105, 0.5);
            background: rgba(30, 41, 59, 0.5);
        }
        
        .policy-checkbox {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            user-select: none;
        }
        
        .policy-checkbox input[type="checkbox"] {
            width: 1.25rem;
            height: 1.25rem;
            cursor: pointer;
            accent-color: #3b82f6;
        }
        
        .policy-checkbox label {
            cursor: pointer;
            font-weight: 500;
            color: #374151;
        }
        
        .dark .policy-checkbox label {
            color: #e5e7eb;
        }
        
        .policy-accept-btn {
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            opacity: 0.5;
            pointer-events: none;
        }
        
        .policy-accept-btn:enabled {
            opacity: 1;
            pointer-events: all;
        }
        
        .policy-accept-btn:enabled:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        }
        
        .policy-accept-btn:enabled:active {
            transform: translateY(0);
        }
        
        @media (max-width: 640px) {
            .policy-modal {
                max-height: 95vh;
                border-radius: 16px;
            }
            
            .policy-modal-header {
                padding: 1.5rem;
            }
            
            .policy-modal-content {
                padding: 1.5rem;
            }
            
            .policy-modal-footer {
                padding: 1rem 1.5rem;
                flex-direction: column;
            }
            
            .policy-accept-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body class="car-rental-bg min-h-screen">
    <!-- Policy Agreement Modal -->
    <div id="policy-modal-overlay" class="policy-modal-overlay">
        <div class="policy-modal">
            <div class="policy-modal-header">
                <h2 class="text-3xl font-bold mb-2">📋 Privacy Policy & Terms of Service</h2>
                <p class="text-blue-100">Please review our policies before proceeding</p>
            </div>
            
            <div class="policy-modal-content">
                <!-- Privacy Policy Section -->
                <div class="policy-section">
                    <h3>
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Privacy Policy
                    </h3>
                    <p>
                        We are committed to protecting your privacy and personal information. This privacy policy explains how we collect, use, and safeguard your data when you use our car rental system.
                    </p>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">1. Information We Collect</h4>
                    <ul>
                        <li><strong>Personal Information:</strong> Name, email address, phone number, date of birth, and address</li>
                        <li><strong>Account Information:</strong> Username, password, and role selection</li>
                        <li><strong>Booking Information:</strong> Vehicle rental history, booking dates, and preferences</li>
                        <li><strong>Location Data:</strong> Vehicle tracking information for operational purposes</li>
                        <li><strong>Professional Information:</strong> License numbers and expiry dates (for chauffeurs)</li>
                    </ul>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">2. How We Use Your Information</h4>
                    <ul>
                        <li>To provide and improve our car rental services</li>
                        <li>To process bookings and manage vehicle assignments</li>
                        <li>To communicate with you about your bookings and account</li>
                        <li>To ensure vehicle safety and fleet management</li>
                        <li>To comply with legal obligations and prevent fraud</li>
                    </ul>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">3. Data Security</h4>
                    <p>
                        We implement industry-standard security measures to protect your personal information. This includes encryption, secure servers, and restricted access to personal data. However, no method of transmission over the internet is 100% secure.
                    </p>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">4. Your Rights</h4>
                    <ul>
                        <li>Access your personal data at any time</li>
                        <li>Request correction of inaccurate information</li>
                        <li>Request deletion of your account and data</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Withdraw consent at any time</li>
                    </ul>
                </div>
                
                <!-- Terms of Service Section -->
                <div class="policy-section">
                    <h3>
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Terms of Service
                    </h3>
                    <p>
                        By using our car rental system, you agree to comply with and be bound by the following terms and conditions.
                    </p>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">1. Account Responsibilities</h4>
                    <ul>
                        <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                        <li>You must provide accurate and complete information during registration</li>
                        <li>You are responsible for all activities that occur under your account</li>
                        <li>You must notify us immediately of any unauthorized access</li>
                    </ul>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">2. Role-Specific Responsibilities</h4>
                    <ul>
                        <li><strong>Administrators:</strong> Responsible for system management, user oversight, and ensuring service quality</li>
                        <li><strong>Chauffeurs:</strong> Must maintain valid licenses, follow traffic laws, and provide professional service</li>
                        <li><strong>Customers:</strong> Must provide accurate booking information and comply with rental agreements</li>
                    </ul>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">3. Service Usage</h4>
                    <ul>
                        <li>You agree to use the service only for lawful purposes</li>
                        <li>You will not interfere with or disrupt the service or servers</li>
                        <li>You will not attempt to gain unauthorized access to any part of the system</li>
                        <li>You will not use the service to transmit harmful code or malware</li>
                    </ul>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">4. Limitation of Liability</h4>
                    <p>
                        Our service is provided "as is" without warranties of any kind. We are not liable for any indirect, incidental, or consequential damages arising from your use of the service.
                    </p>
                    
                    <h4 class="font-semibold text-lg mt-4 mb-2">5. Modifications</h4>
                    <p>
                        We reserve the right to modify these terms at any time. Continued use of the service after changes constitutes acceptance of the modified terms.
                    </p>
                </div>
            </div>
            
            <div class="policy-modal-footer">
                <div class="policy-checkbox">
                    <input type="checkbox" id="policy-accept-checkbox">
                    <label for="policy-accept-checkbox">I have read and agree to the Privacy Policy and Terms of Service</label>
                </div>
                <button id="policy-accept-btn" class="policy-accept-btn" disabled>
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Accept & Continue
                </button>
            </div>
        </div>
    </div>
    
    <!-- Floating Car Animation -->
    <div class="floating-cars"></div>
    
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-6xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center fade-in">
                <h1 class="text-5xl font-bold car-rental-title mb-6">
                    🚗 Premium Car Rental System
                </h1>
                <p class="text-2xl car-rental-subtitle font-medium">
                    Choose your role to start your journey
                </p>
            </div>

            <!-- Role Selection Cards -->
            <div id="role-selection" class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 hidden">
                <div class="car-themed-card rounded-3xl p-8 text-center cursor-pointer" data-role="admin">
                    <div class="w-24 h-24 mx-auto mb-6 admin-icon rounded-3xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-black dark:text-white mb-4" style="color: #000000;">Administrator</h3>
                    <p class="text-black dark:text-gray-300 mb-6 text-lg" style="color: #1a1a1a; font-weight: 500;">Manage fleet operations, bookings, and system administration</p>
                    <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-lg">
                        🛡️ Admin Access
                    </div>
                </div>

                <div class="car-themed-card rounded-3xl p-8 text-center cursor-pointer" data-role="chauffeur">
                    <div class="w-24 h-24 mx-auto mb-6 chauffeur-icon rounded-3xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-black dark:text-white mb-4" style="color: #000000;">Professional Driver</h3>
                    <p class="text-black dark:text-gray-300 mb-6 text-lg" style="color: #1a1a1a; font-weight: 500;">Join our team of professional chauffeurs</p>
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-lg">
                        🚙 Driver Access
                    </div>
                </div>

                <div class="car-themed-card rounded-3xl p-8 text-center cursor-pointer" data-role="user">
                    <div class="w-24 h-24 mx-auto mb-6 user-icon rounded-3xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-black dark:text-white mb-4" style="color: #000000;">Premium Customer</h3>
                    <p class="text-black dark:text-gray-300 mb-6 text-lg" style="color: #1a1a1a; font-weight: 500;">Book luxury vehicles for your transportation needs</p>
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-lg">
                        👤 Customer Access
                    </div>
                </div>
            </div>

            <!-- Authentication Form -->
            <div id="auth-form" class="car-themed-card rounded-3xl p-8 max-w-4xl mx-auto hidden">
                <div class="text-center mb-8">
                    <h2 id="form-title" class="text-3xl font-bold text-gray-800 dark:text-white mb-2"></h2>
                    <p id="form-subtitle" class="text-gray-600 dark:text-gray-300"></p>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" data-step="1">1</div>
                    <div class="step" data-step="2">2</div>
                    <div class="step" data-step="3">3</div>
                </div>

                <!-- Toggle between Login and Register -->
                <div class="flex border-b border-gray-200 dark:border-gray-600 mb-8">
                    <button id="login-tab" class="flex-1 py-4 px-6 text-center font-medium text-gray-500 dark:text-gray-400 tab-button rounded-t-xl">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Sign In
                    </button>
                    <button id="register-tab" class="flex-1 py-4 px-6 text-center font-medium text-gray-500 dark:text-gray-400 tab-button rounded-t-xl">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Sign Up
                    </button>
                </div>

                <div class="p-8">
                    <!-- Login Form -->
                    <form id="login-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    Email Address
                                </label>
                                <input type="email" id="login-email" class="car-input w-full px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your email" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Password
                                </label>
                                <input type="password" id="login-password" class="car-input w-full px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your password" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full car-button bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 font-semibold hover:from-blue-700 hover:to-purple-700 transition duration-200 shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign In
                        </button>
                    </form>

                    <!-- Comprehensive Registration Form -->
                    <form id="register-form" class="space-y-6 hidden">
                        <!-- Step 1: Personal Information -->
                        <div class="form-step active" data-step="1">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-8 flex items-center slide-in-right">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                Personal Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                                    <input type="text" id="register-name" class="car-input w-full px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your full name" required>
                                </div>
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email Address *</label>
                                    <input type="email" id="register-email" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your email" required>
                                </div>
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                    <input type="tel" id="register-phone" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="+216 XX XXX XXX">
                                </div>
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                                    <input type="date" id="register-dob" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" max="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password *</label>
                                    <input type="password" id="register-password" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Minimum 8 characters" required minlength="8">
                                    <p class="text-xs text-gray-500 mt-1">Must contain at least 8 characters</p>
                                </div>
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Confirm Password *</label>
                                    <input type="password" id="register-password-confirm" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Confirm your password" required minlength="8">
                                </div>
                            </div>

                            <div class="flex justify-end mt-8">
                                <button type="button" class="next-step car-button bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition duration-200 shadow-lg">
                                    Next: Contact Information
                                    <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Contact Information -->
                        <div class="form-step" data-step="2">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-8 flex items-center slide-in-right">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                Contact Information
                            </h3>

                            <div class="grid grid-cols-1 gap-6">
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Address</label>
                                    <textarea id="register-address" rows="3" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your full address"></textarea>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">City</label>
                                    <input type="text" id="register-city" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your city">
                                </div>
                                <div class="slide-in-right">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Country</label>
                                    <select id="register-country" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                        <option value="">Select Country</option>
                                        <option value="Tunisia">Tunisia</option>
                                        <option value="Algeria">Algeria</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Libya">Libya</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 p-6 rounded-2xl mt-8">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-6 flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    Emergency Contact
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="slide-in-right">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Emergency Contact Name</label>
                                        <input type="text" id="register-emergency-contact" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Emergency contact name">
                                    </div>
                                    <div class="slide-in-right">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Emergency Phone</label>
                                        <input type="tel" id="register-emergency-phone" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Emergency contact phone">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" class="prev-step car-button bg-gradient-to-r from-gray-500 to-gray-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-gray-600 hover:to-gray-700 transition duration-200 shadow-lg">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Previous
                                </button>
                                <button type="button" class="next-step car-button bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition duration-200 shadow-lg">
                                    Next: Professional Details
                                    <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Professional Information (for chauffeurs) -->
                        <div class="form-step" data-step="3">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-8 flex items-center slide-in-right">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                                    </svg>
                                </div>
                                Professional Information
                            </h3>

                            <!-- Professional details for chauffeurs -->
                            <div id="chauffeur-professional" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="slide-in-right">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">License Number</label>
                                        <input type="text" id="register-license-number" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="e.g., TUN123456789">
                                    </div>
                                    <div class="slide-in-right">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">License Expiry Date</label>
                                        <input type="date" id="register-license-expiry" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="mt-8">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Additional Notes</label>
                                <textarea id="register-notes" rows="4" class="car-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Any additional information or notes..."></textarea>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" class="prev-step car-button bg-gradient-to-r from-gray-500 to-gray-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-gray-600 hover:to-gray-700 transition duration-200 shadow-lg">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Previous
                                </button>
                                <button type="submit" class="car-button bg-gradient-to-r from-green-600 to-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-blue-700 transition duration-200 shadow-lg">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Complete Registration
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Message Display -->
                <div id="message" class="hidden mt-6 p-4 rounded-xl"></div>
            </div>
        </div>
    </div>

    <script>
        let selectedRole = '';
        let currentStep = 1;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);

        // Policy acceptance handling
        const POLICY_ACCEPTED_KEY = 'car_rental_policy_accepted';
        const policyModalOverlay = document.getElementById('policy-modal-overlay');
        const policyCheckbox = document.getElementById('policy-accept-checkbox');
        const policyAcceptBtn = document.getElementById('policy-accept-btn');
        const roleSelection = document.getElementById('role-selection');

        // Check if policy has been accepted
        function hasAcceptedPolicy() {
            return localStorage.getItem(POLICY_ACCEPTED_KEY) === 'true';
        }

        // Show policy modal
        function showPolicyModal() {
            policyModalOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        // Hide policy modal
        function hidePolicyModal() {
            policyModalOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
            roleSelection.classList.remove('hidden');
            roleSelection.classList.add('fade-in');
        }

        // Initialize policy check on page load
        function initPolicyCheck() {
            if (!hasAcceptedPolicy()) {
                // Show policy modal first
                setTimeout(() => {
                    showPolicyModal();
                }, 300); // Small delay for smooth transition
                roleSelection.classList.add('hidden');
            } else {
                // Policy already accepted, show role selection
                policyModalOverlay.classList.remove('active');
                roleSelection.classList.remove('hidden');
            }
        }

        // Handle checkbox change
        policyCheckbox.addEventListener('change', function() {
            if (this.checked) {
                policyAcceptBtn.disabled = false;
            } else {
                policyAcceptBtn.disabled = true;
            }
        });

        // Handle accept button click
        policyAcceptBtn.addEventListener('click', function() {
            if (policyCheckbox.checked) {
                // Store acceptance in localStorage
                localStorage.setItem(POLICY_ACCEPTED_KEY, 'true');
                // Hide modal and show role selection
                hidePolicyModal();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initPolicyCheck();
        });

        // Role selection
        document.querySelectorAll('.car-themed-card[data-role]').forEach(card => {
            card.addEventListener('click', function() {
                selectedRole = this.dataset.role;
                console.log('Role selected:', selectedRole);
                showAuthForm();
            });
        });

        function showAuthForm() {
            document.getElementById('role-selection').classList.add('hidden');
            document.getElementById('auth-form').classList.remove('hidden');
            document.getElementById('auth-form').classList.add('fade-in');
            
            const titles = {
                'admin': 'Administrator Access',
                'chauffeur': 'Professional Driver',
                'user': 'Customer Registration'
            };
            
            const subtitles = {
                'admin': 'Sign in to access administrative controls',
                'chauffeur': 'Join our team of professional drivers',
                'user': 'Create your account to start booking vehicles'
            };
            
            document.getElementById('form-title').textContent = titles[selectedRole];
            document.getElementById('form-subtitle').textContent = subtitles[selectedRole];
            
            // Show/hide professional information based on role
            const chauffeurProfessional = document.getElementById('chauffeur-professional');
            if (selectedRole === 'chauffeur') {
                chauffeurProfessional.style.display = 'block';
            } else {
                chauffeurProfessional.style.display = 'none';
            }
        }

        // Tab switching
        document.getElementById('login-tab').addEventListener('click', function() {
            this.classList.add('active');
            this.classList.remove('text-gray-500', 'dark:text-gray-400');
            this.classList.add('text-white');
            document.getElementById('register-tab').classList.remove('active');
            document.getElementById('register-tab').classList.add('text-gray-500', 'dark:text-gray-400');
            document.getElementById('register-tab').classList.remove('text-white');
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
        });

        document.getElementById('register-tab').addEventListener('click', function() {
            this.classList.add('active');
            this.classList.remove('text-gray-500', 'dark:text-gray-400');
            this.classList.add('text-white');
            document.getElementById('login-tab').classList.remove('active');
            document.getElementById('login-tab').classList.add('text-gray-500', 'dark:text-gray-400');
            document.getElementById('login-tab').classList.remove('text-white');
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('login-form').classList.add('hidden');
        });

        // Step navigation
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('next-step')) {
                console.log('Next step clicked, current step:', currentStep);
                if (validateStep(currentStep)) {
                    currentStep++;
                    console.log('Moving to step:', currentStep);
                    showStep(currentStep);
                } else {
                    console.log('Step validation failed for step:', currentStep);
                }
            } else if (e.target.classList.contains('prev-step')) {
                console.log('Previous step clicked, current step:', currentStep);
                currentStep--;
                console.log('Moving to step:', currentStep);
                showStep(currentStep);
            }
        });

        function showStep(step) {
            console.log('showStep called with step:', step);
            document.querySelectorAll('.form-step').forEach(formStep => {
                formStep.classList.remove('active');
            });
            const targetStep = document.querySelector(`[data-step="${step}"]`);
            if (targetStep) {
                targetStep.classList.add('active');
                console.log('Step', step, 'is now active');
            } else {
                console.log('Step element not found for step:', step);
            }
            
            document.querySelectorAll('.step').forEach(stepEl => {
                stepEl.classList.remove('active', 'completed');
                const stepNum = parseInt(stepEl.dataset.step);
                if (stepNum < step) {
                    stepEl.classList.add('completed');
                } else if (stepNum === step) {
                    stepEl.classList.add('active');
                }
            });
        }

        function validateStep(step) {
            console.log('Validating step:', step);
            const stepElement = document.querySelector(`[data-step="${step}"]`);
            if (!stepElement) {
                console.log('Step element not found for step:', step);
                return true; // Skip validation for non-existent steps
            }
            const requiredFields = stepElement.querySelectorAll('[required]');
            console.log('Required fields found:', requiredFields.length);
            
            for (let field of requiredFields) {
                console.log('Checking field:', field.id, 'value:', field.value);
                if (!field.value.trim()) {
                    console.log('Field validation failed:', field.id);
                    showMessage('Please fill in all required fields.', 'error');
                    field.focus();
                    return false;
                }
            }
            
            // Email validation
            if (step === 1) {
                const email = document.getElementById('register-email').value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                console.log('Email validation - email:', email, 'valid:', emailRegex.test(email));
                if (!emailRegex.test(email)) {
                    showMessage('Please enter a valid email address.', 'error');
                    return false;
                }
            }
            
            // Password validation
            if (step === 1) {
                const password = document.getElementById('register-password').value;
                const confirmPassword = document.getElementById('register-password-confirm').value;
                
                console.log('Password validation - password length:', password.length, 'match:', password === confirmPassword);
                
                if (password !== confirmPassword) {
                    showMessage('Passwords do not match.', 'error');
                    return false;
                }
                
                if (password.length < 8) {
                    showMessage('Password must be at least 8 characters long.', 'error');
                    return false;
                }
            }
            
            console.log('Step', step, 'validation passed');
            return true;
        }

        // Login form submission
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check if role is selected
            if (!selectedRole) {
                showMessage('Please select a role first.', 'error');
                return;
            }
            
            console.log('Login attempt:', {
                email: document.getElementById('login-email').value,
                role: selectedRole
            });
            
            const formData = {
                email: document.getElementById('login-email').value,
                password: document.getElementById('login-password').value,
                role: selectedRole
            };

            fetch('/role-selection/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            })
            .then(async response => {
                console.log('Login response status:', response.status);
                const contentType = response.headers.get('content-type');
                
                if (!response.ok) {
                    let errorMessage = 'Login failed. Please try again.';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = `HTTP error! status: ${response.status}`;
                    }
                    throw new Error(errorMessage);
                }
                
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, might be a redirect
                    const text = await response.text();
                    console.warn('Non-JSON response:', text);
                    return { success: false, message: 'Unexpected response format' };
                }
            })
            .then(data => {
                console.log('Login response:', data);
                if (data && data.success && data.redirect_url) {
                    showMessage(data.message || 'Login successful!', 'success');
                    // Redirect immediately to avoid session issues
                    console.log('Redirecting to:', data.redirect_url);
                    window.location.href = data.redirect_url;
                } else {
                    const errorMsg = data?.message || 'Login failed. Please check your credentials.';
                    console.error('Login failed:', errorMsg, data);
                    showMessage(errorMsg, 'error');
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                let errorMsg = 'An error occurred. Please try again.';
                if (error.message) {
                    errorMsg += ' Error: ' + error.message;
                }
                showMessage(errorMsg, 'error');
            });
        });

        // Registration form submission
        document.getElementById('register-form').addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Registration form submitted!');
            
            // Validate all steps before submission
            let allStepsValid = true;
            for (let step = 1; step <= 3; step++) {
                if (!validateStep(step)) {
                    allStepsValid = false;
                    showStep(step); // Show the step with validation errors
                    break;
                }
            }
            
            if (!allStepsValid) {
                return;
            }
            
            const formData = {
                name: document.getElementById('register-name').value,
                email: document.getElementById('register-email').value,
                password: document.getElementById('register-password').value,
                password_confirmation: document.getElementById('register-password-confirm').value,
                role: selectedRole,
                phone: document.getElementById('register-phone').value || null,
                date_of_birth: document.getElementById('register-dob').value || null,
                address: document.getElementById('register-address').value || null,
                city: document.getElementById('register-city').value || null,
                country: document.getElementById('register-country').value || null,
                emergency_contact: document.getElementById('register-emergency-contact').value || null,
                emergency_phone: document.getElementById('register-emergency-phone').value || null,
                license_number: document.getElementById('register-license-number').value || null,
                license_expiry: document.getElementById('register-license-expiry').value || null,
                notes: document.getElementById('register-notes').value || null
            };

            console.log('Form data being submitted:', formData);
            console.log('Selected role:', selectedRole);

            // Check if role is selected
            if (!selectedRole) {
                showMessage('Please select a role first.', 'error');
                return;
            }

            fetch('/role-selection/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            })
            .then(async response => {
                console.log('Registration response status:', response.status);
                const contentType = response.headers.get('content-type');
                
                if (!response.ok) {
                    let errorMessage = 'Registration failed. Please try again.';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                        if (errorData.errors) {
                            const errorList = Object.values(errorData.errors).flat().join(', ');
                            errorMessage = errorMessage + ': ' + errorList;
                        }
                    } catch (e) {
                        errorMessage = `HTTP error! status: ${response.status}`;
                    }
                    throw new Error(errorMessage);
                }
                
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    const text = await response.text();
                    console.warn('Non-JSON response:', text);
                    return { success: false, message: 'Unexpected response format' };
                }
            })
            .then(data => {
                console.log('Registration response:', data);
                if (data && data.success) {
                    showMessage(data.message || 'Registration successful!', 'success');
                    setTimeout(() => {
                        console.log('Redirecting to:', data.redirect_url);
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    }, 2000);
                } else {
                    showMessage(data?.message || 'Registration failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Registration error:', error);
                let errorMsg = 'An error occurred. Please try again.';
                if (error.message) {
                    errorMsg += ' Error: ' + error.message;
                }
                showMessage(errorMsg, 'error');
            });
        });

        function showMessage(message, type) {
            const messageEl = document.getElementById('message');
            messageEl.className = `mt-6 p-4 rounded-xl ${type === 'success' ? 'message-success' : 'message-error'}`;
            messageEl.textContent = message;
            messageEl.classList.remove('hidden');
            messageEl.classList.add('fade-in');
            
            setTimeout(() => {
                messageEl.classList.add('hidden');
                messageEl.classList.remove('fade-in');
            }, 5000);
        }
    </script>
</body>
</html>