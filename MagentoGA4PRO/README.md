# GA4Pro Analytics for Magento Commerce

GA4Pro is a comprehensive Google Analytics 4 (GA4) integration for Magento Commerce, providing advanced ecommerce tracking capabilities fully compatible with PHP 8.3+.

## Features

- **Google Analytics 4 Integration**: Full support for GA4's enhanced ecommerce tracking
- **PHP 8.3+ Compatible**: Designed to work with the latest PHP versions
- **Magento Commerce Compatible**: Fully tested with Magento Commerce
- **Easy Configuration**: Simple setup through Magento admin panel
- **Comprehensive Tracking**: Track page views, product views, add to cart, checkout steps, purchases
- **Enhanced Ecommerce**: Detailed product and transaction data
- **Debug Mode**: Easily troubleshoot tracking issues
- **Admin Dashboard**: Comprehensive analytics overview in Magento admin
- **Custom Events**: Create and manage custom event tracking
- **Data Export**: Export tracking data in multiple formats (JSON, CSV, XML)

## Requirements

- Magento Commerce 2.4.x
- PHP 8.3 or higher
- Google Analytics 4 property and measurement ID

## Installation

### Via Composer (Recommended)

```bash
composer require magento/ga4pro
bin/magento module:enable Magento_GA4Pro
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:clean
```

### Manual Installation

1. Download the latest release from the GitHub repository
2. Extract the contents to `app/code/Magento/GA4Pro/` directory in your Magento installation
3. Run the following commands:

```bash
bin/magento module:enable Magento_GA4Pro
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:clean
```

## Configuration

1. Log in to your Magento Admin panel
2. Navigate to **Stores > Configuration > Magento > GA4Pro Analytics**
3. Enter your Google Analytics 4 Measurement ID (format: G-XXXXXXXXXX)
4. Configure desired tracking options and event settings
5. Save configuration

## Usage

### Admin Dashboard

After installation, access the GA4Pro Analytics Dashboard from **Marketing > GA4 Analytics > Dashboard** in your Magento Admin panel.

The dashboard provides:
- Comprehensive tracking status overview
- List of enabled events
- Configuration recommendations
- Direct links to your GA4 reports

### Data Export

Export GA4 tracking data in multiple formats:
1. Go to **Marketing > GA4 Analytics > Data Export**
2. Select your desired format (JSON, CSV, XML)
3. Choose a date range
4. Click "Export Data"

### Custom Events

Configure custom events to track specific user interactions:
1. Go to **Stores > Configuration > Magento > GA4Pro Analytics > Custom Events**
2. Enable custom event tracking
3. Define your custom events with parameters
4. Save configuration

## Event Tracking

The following events are tracked by default:

| Event Name | Description |
|------------|-------------|
| page_view | Triggered when a page is viewed |
| view_item | Triggered when a product detail page is viewed |
| add_to_cart | Triggered when a product is added to cart |
| begin_checkout | Triggered when checkout process begins |
| purchase | Triggered when a purchase is completed |

## Enhanced Features

### Server-Side Tracking

For enhanced security and reliability, you can configure server-side tracking:
1. Enter your GA4 API Secret in the module configuration
2. Enable server-side tracking
3. Configure the events you want to track server-side

### User Engagement Metrics

Track detailed user engagement metrics:
- Session duration
- Page scroll depth
- Outbound link clicks
- File downloads

## Troubleshooting

### Testing Connection

1. Go to **Stores > Configuration > Magento > GA4Pro Analytics**
2. Enter your GA4 Measurement ID
3. Click "Test Connection" to verify connectivity

### Debug Mode

Enable debug mode to see detailed tracking information in browser console:
1. Go to **Stores > Configuration > Magento > GA4Pro Analytics > General**
2. Set "Debug Mode" to "Yes"
3. Save configuration

## Support

For issues, feature requests, or questions, please open an issue on the GitHub repository.
