<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact - car location</title>
    <link href="{{ asset('css/modern-blue-white.css') }}" rel="stylesheet">
  </head>
  <body>
    @include('partials._navbar')
    
    <div class="container">
      <div class="header">
        <h1>📞 Contact Us</h1>
        <p style="color: #7f8c8d; margin: 0;">Get in touch with our team</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
        <!-- Contact Form -->
        <div class="form-container">
          <h2>Send us a Message</h2>
          <form id="contact-form">
            <div class="form-group">
              <label>Full Name *</label>
              <input type="text" name="full_name" required placeholder="Enter your full name">
            </div>
            
            <div class="form-group">
              <label>Email Address *</label>
              <input type="email" name="email" required placeholder="Enter your email address">
            </div>
            
            <div class="form-group">
              <label>Phone Number</label>
              <input type="tel" name="phone" placeholder="Enter your phone number">
            </div>
            
            <div class="form-group">
              <label>Company</label>
              <input type="text" name="company" placeholder="Enter your company name">
            </div>
            
            <div class="form-group">
              <label>Subject *</label>
              <select name="subject" required>
                <option value="">Select a subject</option>
                <option value="general">General Inquiry</option>
                <option value="support">Technical Support</option>
                <option value="sales">Sales Question</option>
                <option value="feature">Feature Request</option>
                <option value="bug">Bug Report</option>
                <option value="other">Other</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Message *</label>
              <textarea name="message" rows="5" required placeholder="Enter your message here..."></textarea>
            </div>
            
            <div class="form-group checkbox">
              <label>
                <input type="checkbox" name="newsletter" checked> Subscribe to our newsletter
              </label>
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Send Message</button>
              <button type="reset" class="btn btn-secondary">Clear Form</button>
            </div>
          </form>
        </div>

        <!-- Contact Information -->
        <div class="contact-info">
          <h2>Get in Touch</h2>
          
          <div class="contact-methods">
            <div class="contact-method">
              <div class="contact-icon">📧</div>
              <div class="contact-details">
                <h3>Email</h3>
                <p>info@cartracking.com</p>
                <p>support@cartracking.com</p>
              </div>
            </div>
            
            <div class="contact-method">
              <div class="contact-icon">📞</div>
              <div class="contact-details">
                <h3>Phone</h3>
                <p>+216 12 345 678</p>
                <p>+216 98 765 432</p>
              </div>
            </div>
            
            <div class="contact-method">
              <div class="contact-icon">📍</div>
              <div class="contact-details">
                <h3>Address</h3>
                <p>123 Technology Street</p>
                <p>Tunis, Tunisia 1000</p>
              </div>
            </div>
            
            <div class="contact-method">
              <div class="contact-icon">🕒</div>
              <div class="contact-details">
                <h3>Business Hours</h3>
                <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                <p>Saturday: 10:00 AM - 2:00 PM</p>
                <p>Sunday: Closed</p>
              </div>
            </div>
          </div>

          <!-- Social Media -->
          <div class="social-media">
            <h3>Follow Us</h3>
            <div class="social-links">
              <a href="#" class="social-link">📘 Facebook</a>
              <a href="#" class="social-link">🐦 Twitter</a>
              <a href="#" class="social-link">💼 LinkedIn</a>
              <a href="#" class="social-link">📷 Instagram</a>
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Section -->
      <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-list">
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
              <h3>How does the vehicle tracking system work?</h3>
              <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
              <p>Our system uses GPS technology to track vehicle locations in real-time. Data is transmitted to our servers and displayed on our web-based dashboard, allowing you to monitor your fleet from anywhere.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
              <h3>What types of vehicles can be tracked?</h3>
              <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
              <p>Our system can track cars, trucks, vans, motorcycles, and other vehicles equipped with GPS devices. We support various vehicle types and can customize the solution for your specific needs.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
              <h3>Is my data secure?</h3>
              <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes, we use enterprise-grade security measures including data encryption, secure servers, and regular backups. Your data is protected and only accessible to authorized users.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
              <h3>Do you offer technical support?</h3>
              <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
              <p>Yes, we provide 24/7 technical support via email, phone, and live chat. Our support team is available to help you with any questions or issues you may have.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Simulate form submission
        alert('Thank you for your message! We will get back to you within 24 hours.');
        
        // Reset form
        this.reset();
        document.querySelector('input[name="newsletter"]').checked = true;
      });

      function toggleFAQ(element) {
        const faqItem = element.parentElement;
        const answer = faqItem.querySelector('.faq-answer');
        const toggle = element.querySelector('.faq-toggle');
        
        if (answer.style.display === 'block') {
          answer.style.display = 'none';
          toggle.textContent = '+';
          faqItem.classList.remove('active');
        } else {
          // Close all other FAQs
          document.querySelectorAll('.faq-item').forEach(item => {
            item.querySelector('.faq-answer').style.display = 'none';
            item.querySelector('.faq-toggle').textContent = '+';
            item.classList.remove('active');
          });
          
          // Open current FAQ
          answer.style.display = 'block';
          toggle.textContent = '-';
          faqItem.classList.add('active');
        }
      }
    </script>

    <style>
      .contact-info {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      }
      
      .contact-info h2 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
        font-weight: 600;
      }
      
      .contact-methods {
        margin-bottom: 2rem;
      }
      
      .contact-method {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        border-left: 4px solid #3498db;
      }
      
      .contact-icon {
        font-size: 2rem;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
      }
      
      .contact-details h3 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-weight: 600;
      }
      
      .contact-details p {
        color: #7f8c8d;
        margin: 0.2rem 0;
        font-size: 0.95rem;
      }
      
      .social-media {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid #3498db;
      }
      
      .social-media h3 {
        color: #2c3e50;
        margin-bottom: 1rem;
        font-weight: 600;
      }
      
      .social-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
      }
      
      .social-link {
        color: #3498db;
        text-decoration: none;
        padding: 0.5rem;
        border-radius: 5px;
        transition: all 0.3s ease;
        font-weight: 500;
      }
      
      .social-link:hover {
        background: rgba(52, 152, 219, 0.1);
        transform: translateX(5px);
      }
      
      .faq-section {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      }
      
      .faq-section h2 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
        font-weight: 600;
      }
      
      .faq-item {
        margin-bottom: 1rem;
        border: 2px solid #ecf0f1;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
      }
      
      .faq-item:hover {
        border-color: #3498db;
      }
      
      .faq-item.active {
        border-color: #3498db;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
      }
      
      .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        cursor: pointer;
        transition: all 0.3s ease;
      }
      
      .faq-question:hover {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
      }
      
      .faq-question h3 {
        color: #2c3e50;
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
      }
      
      .faq-toggle {
        font-size: 1.5rem;
        color: #3498db;
        font-weight: bold;
        transition: transform 0.3s ease;
      }
      
      .faq-answer {
        display: none;
        padding: 1.5rem;
        background: white;
        border-top: 1px solid #ecf0f1;
      }
      
      .faq-answer p {
        color: #7f8c8d;
        margin: 0;
        line-height: 1.6;
      }
      
      textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #ecf0f1;
        border-radius: 10px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: all 0.3s ease;
      }
      
      textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
      }
      
      @media (max-width: 768px) {
        .container > div:first-of-type {
          grid-template-columns: 1fr;
        }
        
        .social-links {
          flex-direction: row;
          flex-wrap: wrap;
        }
      }
    </style>
  </body>
</html>
