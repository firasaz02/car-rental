<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About - car location</title>
    <link href="{{ asset('css/modern-blue-white.css') }}" rel="stylesheet">
  </head>
  <body>
    @include('partials._navbar')
    
    <div class="container">
      <div class="header">
        <h1>ℹ️ About</h1>
        <p style="color: #7f8c8d; margin: 0;">Learn more about our vehicle tracking system</p>
      </div>

      <!-- Hero Section -->
      <div class="hero-section">
        <div class="hero-content">
          <h2>car location - Advanced Vehicle Management</h2>
          <p>Our comprehensive fleet location solution provides real-time monitoring, analytics, and management capabilities for your vehicles. Built with modern technology and designed for maximum reliability and efficiency.</p>
        </div>
        <div class="hero-image">
          <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); height: 200px; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white;">
            <div style="text-align: center;">
              <div style="font-size: 4rem; margin-bottom: 1rem;">🚗</div>
              <p style="font-size: 1.2rem; margin: 0;">Fleet Management</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Features Section -->
      <div class="features-section">
        <h2>Key Features</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
          <div class="feature-card">
            <div class="feature-icon">📍</div>
            <h3>Real-time Location</h3>
            <p>Monitor vehicle locations in real-time with GPS precision and live updates.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Analytics & Reports</h3>
            <p>Comprehensive analytics and detailed reports for better fleet management.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">⚡</div>
            <h3>Speed Monitoring</h3>
            <p>Track vehicle speeds and receive alerts for speeding violations.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">🔔</div>
            <h3>Smart Alerts</h3>
            <p>Get notified about important events and maintenance requirements.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">📱</div>
            <h3>Mobile Friendly</h3>
            <p>Access your fleet data from any device with our responsive design.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Secure & Reliable</h3>
            <p>Enterprise-grade security with reliable data storage and backup.</p>
          </div>
        </div>
      </div>

      <!-- Technology Stack -->
      <div class="tech-section">
        <h2>Technology Stack</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
          <div class="tech-item">
            <div class="tech-icon">🐘</div>
            <h4>Laravel</h4>
            <p>PHP Framework</p>
          </div>
          
          <div class="tech-item">
            <div class="tech-icon">🗄️</div>
            <h4>SQLite</h4>
            <p>Database</p>
          </div>
          
          <div class="tech-item">
            <div class="tech-icon">🎨</div>
            <h4>CSS3</h4>
            <p>Styling</p>
          </div>
          
          <div class="tech-item">
            <div class="tech-icon">⚡</div>
            <h4>JavaScript</h4>
            <p>Frontend Logic</p>
          </div>
          
          <div class="tech-item">
            <div class="tech-icon">🌐</div>
            <h4>REST API</h4>
            <p>API Architecture</p>
          </div>
          
          <div class="tech-item">
            <div class="tech-icon">📡</div>
            <h4>WebSocket</h4>
            <p>Real-time Updates</p>
          </div>
        </div>
      </div>

      <!-- Team Section -->
      <div class="team-section">
        <h2>Our Team</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
          <div class="team-member">
            <div class="member-avatar">👨‍💻</div>
            <h3>Development Team</h3>
            <p>Backend & Frontend Development</p>
            <div class="member-skills">
              <span class="skill-tag">PHP</span>
              <span class="skill-tag">Laravel</span>
              <span class="skill-tag">JavaScript</span>
            </div>
          </div>
          
          <div class="team-member">
            <div class="member-avatar">🎨</div>
            <h3>Design Team</h3>
            <p>UI/UX Design & Frontend</p>
            <div class="member-skills">
              <span class="skill-tag">CSS3</span>
              <span class="skill-tag">HTML5</span>
              <span class="skill-tag">Design</span>
            </div>
          </div>
          
          <div class="team-member">
            <div class="member-avatar">🔧</div>
            <h3>DevOps Team</h3>
            <p>Infrastructure & Deployment</p>
            <div class="member-skills">
              <span class="skill-tag">Server</span>
              <span class="skill-tag">Database</span>
              <span class="skill-tag">Security</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact CTA -->
      <div class="contact-cta">
        <h2>Ready to Get Started?</h2>
        <p>Contact us today to learn more about our vehicle tracking solutions.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/contact" class="btn btn-primary">Contact Us</a>
          <a href="/dashboard" class="btn btn-secondary">View Dashboard</a>
        </div>
      </div>
    </div>

    <style>
      .hero-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
        align-items: center;
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
      }
      
      .hero-content h2 {
        color: #2c3e50;
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
      }
      
      .hero-content p {
        color: #7f8c8d;
        font-size: 1.1rem;
        line-height: 1.6;
      }
      
      .features-section {
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
      }
      
      .features-section h2 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 2rem;
        font-weight: 600;
      }
      
      .feature-card {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
      }
      
      .feature-card:hover {
        transform: translateY(-5px);
        border-color: #3498db;
        box-shadow: 0 8px 30px rgba(52, 152, 219, 0.3);
      }
      
      .feature-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
      }
      
      .feature-card h3 {
        color: #2c3e50;
        margin-bottom: 1rem;
        font-weight: 600;
      }
      
      .feature-card p {
        color: #7f8c8d;
        margin: 0;
      }
      
      .tech-section {
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
      }
      
      .tech-section h2 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 2rem;
        font-weight: 600;
      }
      
      .tech-item {
        text-align: center;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        border-left: 4px solid #3498db;
      }
      
      .tech-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
      }
      
      .tech-item h4 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-weight: 600;
      }
      
      .tech-item p {
        color: #7f8c8d;
        margin: 0;
        font-size: 0.9rem;
      }
      
      .team-section {
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
      }
      
      .team-section h2 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 2rem;
        font-weight: 600;
      }
      
      .team-member {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
      }
      
      .team-member:hover {
        transform: translateY(-5px);
        border-color: #3498db;
        box-shadow: 0 8px 30px rgba(52, 152, 219, 0.3);
      }
      
      .member-avatar {
        font-size: 4rem;
        margin-bottom: 1rem;
      }
      
      .team-member h3 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-weight: 600;
      }
      
      .team-member p {
        color: #7f8c8d;
        margin-bottom: 1rem;
      }
      
      .member-skills {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
      }
      
      .skill-tag {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
      }
      
      .contact-cta {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      }
      
      .contact-cta h2 {
        margin-bottom: 1rem;
        font-weight: 600;
      }
      
      .contact-cta p {
        margin-bottom: 2rem;
        opacity: 0.9;
        font-size: 1.1rem;
      }
      
      @media (max-width: 768px) {
        .hero-section {
          grid-template-columns: 1fr;
          text-align: center;
        }
        
        .contact-cta div {
          flex-direction: column;
        }
      }
    </style>
  </body>
</html>
