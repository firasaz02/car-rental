<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Settings - car location</title>
    <link href="{{ asset('css/modern-blue-white.css') }}" rel="stylesheet">
  </head>
  <body>
    @include('partials._navbar')
    
    <div class="container">
      <div class="header">
        <h1>⚙️ Settings</h1>
        <p style="color: #7f8c8d; margin: 0;">Configure your vehicle location system</p>
      </div>

      <!-- Settings Tabs -->
      <div class="settings-tabs">
        <button class="tab-button active" onclick="showTab('general')">General</button>
        <button class="tab-button" onclick="showTab('notifications')">Notifications</button>
        <button class="tab-button" onclick="showTab('security')">Security</button>
        <button class="tab-button" onclick="showTab('api')">API Settings</button>
      </div>

      <!-- General Settings -->
      <div id="general-tab" class="settings-tab active">
        <div class="form-container">
          <h2>General Settings</h2>
          <form id="general-settings">
            <div class="form-group">
              <label>System Name</label>
              <input type="text" name="system_name" value="Car Tracking System" placeholder="Enter system name">
            </div>
            
            <div class="form-group">
              <label>Default Map Center (Latitude)</label>
              <input type="number" name="default_lat" value="36.8065" step="0.0001" placeholder="Enter latitude">
            </div>
            
            <div class="form-group">
              <label>Default Map Center (Longitude)</label>
              <input type="number" name="default_lng" value="10.1815" step="0.0001" placeholder="Enter longitude">
            </div>
            
            <div class="form-group">
              <label>Default Map Zoom Level</label>
              <input type="number" name="default_zoom" value="12" min="1" max="20" placeholder="Enter zoom level">
            </div>
            
            <div class="form-group">
              <label>Update Interval (seconds)</label>
              <select name="update_interval">
                <option value="5">5 seconds</option>
                <option value="10" selected>10 seconds</option>
                <option value="30">30 seconds</option>
                <option value="60">1 minute</option>
              </select>
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="auto_refresh" checked> Enable Auto Refresh
              </label>
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save Settings</button>
              <button type="button" class="btn btn-secondary" onclick="resetGeneralSettings()">Reset</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Notifications Settings -->
      <div id="notifications-tab" class="settings-tab">
        <div class="form-container">
          <h2>Notification Settings</h2>
          <form id="notification-settings">
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="email_notifications" checked> Email Notifications
              </label>
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="speed_alerts" checked> Speed Alerts
              </label>
            </div>
            
            <div class="form-group">
              <label>Speed Alert Threshold (km/h)</label>
              <input type="number" name="speed_threshold" value="80" min="1" max="200" placeholder="Enter speed threshold">
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="location_alerts"> Location Alerts
              </label>
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="maintenance_alerts" checked> Maintenance Alerts
              </label>
            </div>
            
            <div class="form-group">
              <label>Email Address</label>
              <input type="email" name="email_address" placeholder="Enter email address">
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Security Settings -->
      <div id="security-tab" class="settings-tab">
        <div class="form-container">
          <h2>Security Settings</h2>
          <form id="security-settings">
            <div class="form-group">
              <label>Current Password</label>
              <input type="password" name="current_password" placeholder="Enter current password">
            </div>
            
            <div class="form-group">
              <label>New Password</label>
              <input type="password" name="new_password" placeholder="Enter new password">
            </div>
            
            <div class="form-group">
              <label>Confirm New Password</label>
              <input type="password" name="confirm_password" placeholder="Confirm new password">
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="two_factor" checked> Enable Two-Factor Authentication
              </label>
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="session_timeout" checked> Auto Logout After Inactivity
              </label>
            </div>
            
            <div class="form-group">
              <label>Session Timeout (minutes)</label>
              <input type="number" name="session_timeout_minutes" value="30" min="5" max="480" placeholder="Enter timeout in minutes">
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Update Security</button>
            </div>
          </form>
        </div>
      </div>

      <!-- API Settings -->
      <div id="api-tab" class="settings-tab">
        <div class="form-container">
          <h2>API Settings</h2>
          <form id="api-settings">
            <div class="form-group">
              <label>API Base URL</label>
              <input type="url" name="api_base_url" value="http://127.0.0.1:8000/api/v1" placeholder="Enter API base URL">
            </div>
            
            <div class="form-group">
              <label>API Key</label>
              <input type="text" name="api_key" placeholder="Enter API key" readonly>
              <small style="color: #7f8c8d;">Your API key for external access</small>
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="api_enabled" checked> Enable API Access
              </label>
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="rate_limiting" checked> Enable Rate Limiting
              </label>
            </div>
            
            <div class="form-group">
              <label>Rate Limit (requests per minute)</label>
              <input type="number" name="rate_limit" value="60" min="1" max="1000" placeholder="Enter rate limit">
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save API Settings</button>
              <button type="button" class="btn btn-secondary" onclick="generateApiKey()">Generate New API Key</button>
            </div>
          </form>
        </div>
      </div>

      <!-- System Information -->
      <div class="system-info">
        <h2>System Information</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
          <div class="info-card">
            <h4>Version</h4>
            <p>1.0.0</p>
          </div>
          <div class="info-card">
            <h4>Last Updated</h4>
            <p id="last-updated">2025-10-10</p>
          </div>
          <div class="info-card">
            <h4>Database Status</h4>
            <p style="color: #27ae60;">Connected</p>
          </div>
          <div class="info-card">
            <h4>Server Status</h4>
            <p style="color: #27ae60;">Online</p>
          </div>
        </div>
      </div>
    </div>

    <script>
      function showTab(tabName) {
        // Hide all tabs
        const tabs = document.querySelectorAll('.settings-tab');
        tabs.forEach(tab => tab.classList.remove('active'));
        
        // Remove active class from all buttons
        const buttons = document.querySelectorAll('.tab-button');
        buttons.forEach(button => button.classList.remove('active'));
        
        // Show selected tab
        document.getElementById(tabName + '-tab').classList.add('active');
        event.target.classList.add('active');
      }

      function resetGeneralSettings() {
        document.getElementById('general-settings').reset();
        document.querySelector('input[name="auto_refresh"]').checked = true;
        document.querySelector('select[name="update_interval"]').value = '10';
      }

      function generateApiKey() {
        const apiKey = 'api_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now().toString(36);
        document.querySelector('input[name="api_key"]').value = apiKey;
        alert('New API key generated: ' + apiKey);
      }

      // Form submission handlers
      document.getElementById('general-settings').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('General settings saved successfully!');
      });

      document.getElementById('notification-settings').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Notification settings saved successfully!');
      });

      document.getElementById('security-settings').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Security settings updated successfully!');
      });

      document.getElementById('api-settings').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('API settings saved successfully!');
      });

      // Generate initial API key
      document.addEventListener('DOMContentLoaded', function() {
        generateApiKey();
      });
    </script>

    <style>
      .settings-tabs {
        display: flex;
        gap: 0;
        margin-bottom: 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
      }
      
      .tab-button {
        flex: 1;
        padding: 1rem 2rem;
        border: none;
        background: #f8f9fa;
        color: #7f8c8d;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
      }
      
      .tab-button:hover {
        background: #e9ecef;
        color: #2c3e50;
      }
      
      .tab-button.active {
        background: white;
        color: #3498db;
        border-bottom-color: #3498db;
      }
      
      .settings-tab {
        display: none;
      }
      
      .settings-tab.active {
        display: block;
      }
      
      .system-info {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-top: 2rem;
      }
      
      .system-info h2 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
        font-weight: 600;
      }
      
      .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid #3498db;
        text-align: center;
      }
      
      .info-card h4 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-weight: 600;
      }
      
      .info-card p {
        color: #7f8c8d;
        margin: 0;
        font-size: 1.1rem;
      }
    </style>
  </body>
</html>
