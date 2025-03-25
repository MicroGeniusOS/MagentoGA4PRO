<?php
/**
 * GA4Pro Analytics Demo Page
 * This page demonstrates the GA4Pro module functionality in a simplified environment
 */

// Set page title and basic styling
$pageTitle = 'GA4Pro Analytics for Magento - Demo';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        h1 {
            color: #2271b1;
            margin-top: 0;
        }
        h2 {
            color: #2271b1;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
        }
        .product {
            display: inline-block;
            width: calc(33% - 20px);
            margin-right: 20px;
            margin-bottom: 20px;
            vertical-align: top;
        }
        .product:nth-child(3n) {
            margin-right: 0;
        }
        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .product h3 {
            margin-top: 10px;
        }
        .price {
            color: #2271b1;
            font-weight: bold;
            font-size: 1.2em;
        }
        .button {
            display: inline-block;
            background-color: #2271b1;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            border: none;
        }
        .button:hover {
            background-color: #135e96;
        }
        .feature-list {
            list-style-type: none;
            padding-left: 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:before {
            content: "✓";
            color: #2271b1;
            margin-right: 8px;
        }
        .tabs {
            margin-bottom: 20px;
        }
        .tab {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f5f5f5;
            cursor: pointer;
            border-radius: 5px 5px 0 0;
        }
        .tab.active {
            background-color: #2271b1;
            color: white;
        }
        .tab-content {
            display: none;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 5px 5px 5px;
        }
        .tab-content.active {
            display: block;
        }
        code {
            background-color: #f5f5f5;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
        }
        .dashboard-preview {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .event-log {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            max-height: 200px;
            overflow-y: auto;
            font-family: monospace;
        }
        footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
        <p>A comprehensive Google Analytics 4 (GA4) integration for Magento Commerce, fully compatible with PHP 8.3+</p>
    </header>

    <div class="tabs">
        <div class="tab active" data-tab="overview">Overview</div>
        <div class="tab" data-tab="features">Features</div>
        <div class="tab" data-tab="demo">Live Demo</div>
        <div class="tab" data-tab="installation">Installation</div>
    </div>

    <div class="tab-content active" id="overview">
        <div class="card">
            <h2>GA4Pro Analytics Extension</h2>
            <p>GA4Pro is a powerful Google Analytics 4 (GA4) extension for Magento Commerce, designed to provide comprehensive tracking capabilities with enhanced ecommerce features. It's built to be fully compatible with PHP 8.3+ and integrates seamlessly with the Magento Commerce platform.</p>
            
            <h3>Why Choose GA4Pro?</h3>
            <ul class="feature-list">
                <li>Complete GA4 integration with enhanced ecommerce support</li>
                <li>PHP 8.3+ compatibility ensures future-proof operation</li>
                <li>Comprehensive dashboard in Magento admin</li>
                <li>Custom event tracking capability</li>
                <li>Advanced data export functionality</li>
                <li>Easy installation and configuration</li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="features">
        <div class="card">
            <h2>Key Features</h2>
            
            <h3>Core Tracking Capabilities</h3>
            <ul class="feature-list">
                <li>Page view tracking across your Magento store</li>
                <li>Product view tracking with detailed product data</li>
                <li>Add to cart event tracking with quantity and price</li>
                <li>Checkout step tracking for funnel analysis</li>
                <li>Purchase conversion tracking with transaction details</li>
                <li>User engagement metrics including session duration</li>
            </ul>
            
            <h3>Enhanced Ecommerce Support</h3>
            <ul class="feature-list">
                <li>Detailed product impression tracking in category pages</li>
                <li>Product click tracking from listings to detail pages</li>
                <li>Cart interaction tracking including removes and updates</li>
                <li>Promotion view and click tracking</li>
                <li>Revenue tracking with tax and shipping options</li>
            </ul>
            
            <h3>Admin Dashboard</h3>
            <ul class="feature-list">
                <li>Comprehensive analytics overview</li>
                <li>Real-time tracking status monitoring</li>
                <li>Direct link to Google Analytics 4 reporting</li>
                <li>Configuration status and recommendations</li>
            </ul>
            
            <h3>Custom Events</h3>
            <ul class="feature-list">
                <li>Create and manage custom event tracking</li>
                <li>Define custom parameters for events</li>
                <li>Track user-specific actions across your store</li>
            </ul>
            
            <h3>Data Export</h3>
            <ul class="feature-list">
                <li>Export tracking data in multiple formats (JSON, CSV, XML)</li>
                <li>Scheduled exports with configurable frequency</li>
                <li>Custom date range selection for exports</li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="demo">
        <div class="card">
            <h2>Live Demo - Product Catalog</h2>
            <p>This demo simulates a Magento product catalog with GA4 tracking. Each interaction will generate GA4 events that can be seen in the event log below.</p>
            
            <div class="product">
                <img src="https://via.placeholder.com/300x200" alt="Product 1">
                <h3>Premium Smartwatch</h3>
                <p>Advanced fitness tracking, heart rate monitoring, and smart notifications.</p>
                <p class="price">$249.99</p>
                <button class="button view-product" data-product-id="1001" data-product-name="Premium Smartwatch" data-price="249.99">View Details</button>
                <button class="button add-to-cart" data-product-id="1001" data-product-name="Premium Smartwatch" data-price="249.99">Add to Cart</button>
            </div>
            
            <div class="product">
                <img src="https://via.placeholder.com/300x200" alt="Product 2">
                <h3>Wireless Earbuds</h3>
                <p>True wireless earbuds with active noise cancellation and long battery life.</p>
                <p class="price">$179.99</p>
                <button class="button view-product" data-product-id="1002" data-product-name="Wireless Earbuds" data-price="179.99">View Details</button>
                <button class="button add-to-cart" data-product-id="1002" data-product-name="Wireless Earbuds" data-price="179.99">Add to Cart</button>
            </div>
            
            <div class="product">
                <img src="https://via.placeholder.com/300x200" alt="Product 3">
                <h3>Smart Home Hub</h3>
                <p>Control all your smart home devices with voice commands and automation.</p>
                <p class="price">$129.99</p>
                <button class="button view-product" data-product-id="1003" data-product-name="Smart Home Hub" data-price="129.99">View Details</button>
                <button class="button add-to-cart" data-product-id="1003" data-product-name="Smart Home Hub" data-price="129.99">Add to Cart</button>
            </div>
            
            <div style="clear: both;"></div>
            
            <h3>Checkout Simulation</h3>
            <div>
                <button class="button checkout-step" data-step="1">Begin Checkout</button>
                <button class="button checkout-step" data-step="2">Enter Shipping</button>
                <button class="button checkout-step" data-step="3">Enter Payment</button>
                <button class="button purchase">Complete Purchase</button>
            </div>
            
            <h3>Event Log</h3>
            <div class="event-log" id="eventLog">
                <div>GA4 Event logging initialized...</div>
            </div>
        </div>
    </div>

    <div class="tab-content" id="installation">
        <div class="card">
            <h2>Installation Instructions</h2>
            
            <h3>Via Composer (Recommended)</h3>
            <pre><code>composer require magento/ga4pro
bin/magento module:enable Magento_GA4Pro
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:clean</code></pre>
            
            <h3>Manual Installation</h3>
            <ol>
                <li>Download the latest release from the GitHub repository</li>
                <li>Extract the contents to app/code/Magento/GA4Pro/ directory in your Magento installation</li>
                <li>Run the following commands:
                    <pre><code>bin/magento module:enable Magento_GA4Pro
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:clean</code></pre>
                </li>
            </ol>
            
            <h3>Configuration</h3>
            <ol>
                <li>Log in to your Magento Admin panel</li>
                <li>Navigate to Stores > Configuration > Magento > GA4Pro Analytics</li>
                <li>Enter your Google Analytics 4 Measurement ID (format: G-XXXXXXXXXX)</li>
                <li>Configure desired tracking options and event settings</li>
                <li>Save configuration</li>
            </ol>
            
            <h3>Dashboard Access</h3>
            <p>After installation, access the GA4Pro Analytics Dashboard from Marketing > GA4 Analytics > Dashboard in your Magento Admin panel.</p>
            
            <div class="dashboard-preview">
                <h4>Dashboard Preview</h4>
                <p>The GA4Pro Analytics Dashboard provides a comprehensive overview of your tracking status, enabled events, and direct links to your Google Analytics 4 reports.</p>
            </div>
        </div>
    </div>

    <footer>
        <p>GA4Pro Analytics for Magento Commerce &copy; 2025 | PHP 8.3+ Compatible</p>
    </footer>

    <!-- GA4 Tracking Code Implementation -->
    <script>
        // Initialize dataLayer
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        // Mock GA4 functionality for demo purposes
        gtag('js', new Date());
        gtag('config', 'G-DEMO12345'); // Demo measurement ID
        
        // Log events to the event log element
        function logEvent(eventName, params) {
            const logElement = document.getElementById('eventLog');
            const logEntry = document.createElement('div');
            
            // Format the event for display
            const timestamp = new Date().toLocaleTimeString();
            let paramsText = '';
            
            if (params) {
                paramsText = JSON.stringify(params, null, 2);
            }
            
            logEntry.innerHTML = `<strong>${timestamp}</strong> - Event: <span style="color:#2271b1">${eventName}</span> ${params ? '<br>Params: ' + paramsText : ''}`;
            
            // Add to log and scroll to bottom
            logElement.appendChild(logEntry);
            logElement.scrollTop = logElement.scrollHeight;
            
            // Also send to console for debugging
            console.log(`GA4 Event: ${eventName}`, params);
            
            // Push to dataLayer (simulating GA4)
            gtag('event', eventName, params);
        }
        
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Log page view
            logEvent('page_view', {
                page_title: 'GA4Pro Demo Page',
                page_location: window.location.href,
                page_path: window.location.pathname
            });
            
            // Handle tab switching
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs and content
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Show corresponding content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                    
                    // Log navigation event
                    logEvent('navigation', {
                        tab_name: tabId
                    });
                });
            });
            
            // Product view event
            const viewButtons = document.querySelectorAll('.view-product');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const productName = this.getAttribute('data-product-name');
                    const price = parseFloat(this.getAttribute('data-price'));
                    
                    logEvent('view_item', {
                        currency: 'USD',
                        value: price,
                        items: [{
                            item_id: productId,
                            item_name: productName,
                            price: price
                        }]
                    });
                });
            });
            
            // Add to cart event
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const productName = this.getAttribute('data-product-name');
                    const price = parseFloat(this.getAttribute('data-price'));
                    
                    logEvent('add_to_cart', {
                        currency: 'USD',
                        value: price,
                        items: [{
                            item_id: productId,
                            item_name: productName,
                            price: price,
                            quantity: 1
                        }]
                    });
                });
            });
            
            // Checkout step events
            const checkoutStepButtons = document.querySelectorAll('.checkout-step');
            checkoutStepButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const stepIndex = parseInt(this.getAttribute('data-step'));
                    let stepTitle;
                    
                    switch(stepIndex) {
                        case 1:
                            stepTitle = 'Begin Checkout';
                            break;
                        case 2:
                            stepTitle = 'Shipping Information';
                            break;
                        case 3:
                            stepTitle = 'Payment Information';
                            break;
                    }
                    
                    logEvent('begin_checkout', {
                        currency: 'USD',
                        value: 559.97, // Total of all products
                        items: [
                            {
                                item_id: '1001',
                                item_name: 'Premium Smartwatch',
                                price: 249.99,
                                quantity: 1
                            },
                            {
                                item_id: '1002',
                                item_name: 'Wireless Earbuds',
                                price: 179.99,
                                quantity: 1
                            },
                            {
                                item_id: '1003',
                                item_name: 'Smart Home Hub',
                                price: 129.99,
                                quantity: 1
                            }
                        ],
                        checkout_step: stepIndex,
                        checkout_option: stepTitle
                    });
                });
            });
            
            // Purchase event
            const purchaseButton = document.querySelector('.purchase');
            purchaseButton.addEventListener('click', function() {
                logEvent('purchase', {
                    transaction_id: 'T-' + Math.floor(Math.random() * 1000000),
                    value: 559.97,
                    tax: 45.00,
                    shipping: 15.00,
                    currency: 'USD',
                    items: [
                        {
                            item_id: '1001',
                            item_name: 'Premium Smartwatch',
                            price: 249.99,
                            quantity: 1
                        },
                        {
                            item_id: '1002',
                            item_name: 'Wireless Earbuds',
                            price: 179.99,
                            quantity: 1
                        },
                        {
                            item_id: '1003',
                            item_name: 'Smart Home Hub',
                            price: 129.99,
                            quantity: 1
                        }
                    ]
                });
            });
        });
    </script>
</body>
</html>