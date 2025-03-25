/**
 * Google Analytics 4 tracking script
 */
define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, customerData) {
    'use strict';

    return {
        /**
         * Initialize GA4 tracking
         */
        initialize: function () {
            if (!window.gtag || !window.ga4ProConfig || !window.ga4ProConfig.measurementId) {
                return;
            }

            this.initEventListeners();
            this.trackCartEvents();
        },
        
        /**
         * Legacy init method for backwards compatibility
         */
        init: function (config) {
            this.initialize();
        },

        /**
         * Initialize event listeners
         */
        initEventListeners: function () {
            // Listen for add to cart events via AJAX
            $(document).on('ajax:addToCart', function(event, data) {
                if (data.productIds && data.productIds.length) {
                    this.trackAddToCart(data.productIds[0], data.productInfo[data.productIds[0]].qty || 1);
                }
            }.bind(this));

            // Listen for add to cart button clicks
            $(document).on('click', 'button.tocart', function(e) {
                const productId = $(e.currentTarget).closest('form').find('input[name="product"]').val();
                const qty = $(e.currentTarget).closest('form').find('input[name="qty"]').val() || 1;
                
                if (productId) {
                    // Save product data for potential AJAX response handling
                    $(e.currentTarget).data('ga4pro-product-id', productId);
                    $(e.currentTarget).data('ga4pro-qty', qty);
                }
            });

            // Track checkout steps
            if (window.location.pathname.indexOf('checkout') !== -1) {
                this.trackCheckout();
            }
        },

        /**
         * Track add to cart events
         * 
         * @param {string} productId
         * @param {number} qty
         */
        trackAddToCart: function (productId, qty) {
            if (!window.gtag) {
                return;
            }

            // Get product data via AJAX since we only have the ID
            $.ajax({
                url: '/ga4pro/product/data',
                type: 'GET',
                data: {
                    id: productId
                },
                dataType: 'json',
                success: function (response) {
                    if (response && response.success && response.data) {
                        gtag('event', 'add_to_cart', {
                            items: [{
                                item_id: response.data.sku,
                                item_name: response.data.name,
                                price: response.data.price,
                                quantity: qty
                            }]
                        });

                        if (window.ga4ProConfig && window.ga4ProConfig.debugMode) {
                            console.log('GA4Pro: Add to Cart Event', {
                                items: [{
                                    item_id: response.data.sku,
                                    item_name: response.data.name,
                                    price: response.data.price,
                                    quantity: qty
                                }]
                            });
                        }
                    }
                }
            });
        },

        /**
         * Track cart events from customer data
         */
        trackCartEvents: function () {
            const cartData = customerData.get('cart');
            
            cartData.subscribe(function (cart) {
                if (cart && cart.items && cart.items.length) {
                    // Could implement view_cart event here if needed
                }
            });
        },

        /**
         * Track checkout steps
         */
        trackCheckout: function () {
            const cartData = customerData.get('cart');
            
            cartData.subscribe(function (cart) {
                if (!cart || !cart.items || !cart.items.length) {
                    return;
                }

                const items = [];
                let value = 0;

                cart.items.forEach(function (item) {
                    items.push({
                        item_id: item.product_sku,
                        item_name: item.product_name,
                        price: item.product_price_value,
                        quantity: item.qty
                    });
                    
                    value += (item.product_price_value * item.qty);
                });

                gtag('event', 'begin_checkout', {
                    items: items,
                    value: value
                });

                if (window.ga4ProConfig && window.ga4ProConfig.debugMode) {
                    console.log('GA4Pro: Begin Checkout Event', {
                        items: items,
                        value: value
                    });
                }

                // Listen for checkout step changes
                $(document).on('checkout.step.change', function (e, step) {
                    gtag('event', 'checkout_progress', {
                        items: items,
                        checkout_step: step
                    });

                    if (window.ga4ProConfig && window.ga4ProConfig.debugMode) {
                        console.log('GA4Pro: Checkout Progress Event', {
                            items: items,
                            checkout_step: step
                        });
                    }
                });
            });
        }
    };
});
