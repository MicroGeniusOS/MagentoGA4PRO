<?php
/**
 * GA4Pro Analytics System Config Workaround Helper
 */
namespace Magento\GA4Pro\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class SystemConfigWorkaround extends AbstractHelper
{
    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * Constructor
     *
     * @param Context $context
     * @param WriterInterface $configWriter
     */
    public function __construct(
        Context $context,
        WriterInterface $configWriter
    ) {
        $this->configWriter = $configWriter;
        parent::__construct($context);
    }

    /**
     * Set configuration value programmatically
     *
     * This is a workaround method that can be used when admin configuration
     * system.xml has issues
     *
     * @param string $path
     * @param mixed $value
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setConfigValue($path, $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->configWriter->save($path, $value, $scope, $scopeId);
    }

    /**
     * Initialize default configuration values programmatically
     * This can be called from a controller or setup script
     *
     * @return void
     */
    public function initializeDefaultConfiguration()
    {
        // These match the paths in our config.xml but can be set programmatically
        $defaults = [
            'magento_ga4pro/general/enabled' => 0,
            'magento_ga4pro/general/measurement_id' => '',
            'magento_ga4pro/general/api_secret' => '',
            'magento_ga4pro/general/debug_mode' => 0,
            'magento_ga4pro/events/track_pageviews' => 1,
            'magento_ga4pro/events/track_product_views' => 1,
            'magento_ga4pro/events/track_add_to_cart' => 1,
            'magento_ga4pro/events/track_checkout' => 1, 
            'magento_ga4pro/events/track_purchases' => 1,
            'magento_ga4pro/enhanced/enable_enhanced' => 1,
            'magento_ga4pro/enhanced/include_tax' => 1,
            'magento_ga4pro/enhanced/include_shipping' => 1,
            // New custom events section
            'magento_ga4pro/custom_events/enable_custom_events' => 0,
            'magento_ga4pro/custom_events/custom_events_config' => '[]',
            // New data import/export section
            'magento_ga4pro/data_management/enable_import_export' => 0,
            'magento_ga4pro/data_management/export_format' => 'json',
            'magento_ga4pro/data_management/export_frequency' => 'manual'
        ];

        foreach ($defaults as $path => $value) {
            $this->setConfigValue($path, $value);
        }
    }

    /**
     * Set GA4 Measurement ID programmatically
     *
     * @param string $measurementId
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setMeasurementId($measurementId, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/general/measurement_id', $measurementId, $scope, $scopeId);
        // Also enable the module when measurement ID is set
        $this->setConfigValue('magento_ga4pro/general/enabled', 1, $scope, $scopeId);
    }

    /**
     * Set API Secret programmatically
     *
     * @param string $apiSecret
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setApiSecret($apiSecret, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/general/api_secret', $apiSecret, $scope, $scopeId);
    }

    /**
     * Enable or disable debug mode programmatically
     *
     * @param bool $enabled
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setDebugMode($enabled, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/general/debug_mode', $enabled ? 1 : 0, $scope, $scopeId);
    }
    
    /**
     * Enable or disable custom events programmatically
     *
     * @param bool $enabled
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setCustomEventsEnabled($enabled, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/custom_events/enable_custom_events', $enabled ? 1 : 0, $scope, $scopeId);
    }
    
    /**
     * Set custom events configuration
     *
     * @param string $configJson JSON format configuration for custom events
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setCustomEventsConfig($configJson, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/custom_events/custom_events_config', $configJson, $scope, $scopeId);
    }
    
    /**
     * Enable or disable data import/export programmatically
     *
     * @param bool $enabled
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setDataImportExportEnabled($enabled, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/data_management/enable_import_export', $enabled ? 1 : 0, $scope, $scopeId);
    }
    
    /**
     * Set data export format programmatically
     *
     * @param string $format One of: json, csv, xml
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setDataExportFormat($format, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/data_management/export_format', $format, $scope, $scopeId);
    }
    
    /**
     * Set data export frequency programmatically
     *
     * @param string $frequency One of: daily, weekly, monthly, manual
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setDataExportFrequency($frequency, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/data_management/export_frequency', $frequency, $scope, $scopeId);
    }
    
    /**
     * Set data export path programmatically
     *
     * @param string $path Directory path for exported files
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function setDataExportPath($path, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->setConfigValue('magento_ga4pro/data_management/export_path', $path, $scope, $scopeId);
    }
    
    /**
     * Export GA4 data to the specified format
     *
     * @param string $format Format override (if not using configured format)
     * @return string Path to the exported file
     */
    public function exportData($format = null)
    {
        // Get config values
        $isEnabled = $this->scopeConfig->getValue(
            'magento_ga4pro/data_management/enable_import_export',
            ScopeInterface::SCOPE_STORE
        );
        
        if (!$isEnabled) {
            throw new \Exception('Data export is not enabled in configuration');
        }
        
        // Use provided format or get from config
        if ($format === null) {
            $format = $this->scopeConfig->getValue(
                'magento_ga4pro/data_management/export_format',
                ScopeInterface::SCOPE_STORE
            );
        }
        
        $exportPath = $this->scopeConfig->getValue(
            'magento_ga4pro/data_management/export_path',
            ScopeInterface::SCOPE_STORE
        );
        
        // If no export path is configured, use Magento's var directory
        if (empty($exportPath)) {
            $exportPath = 'var/export/ga4pro';
        }
        
        // Generate filename with timestamp
        $timestamp = date('Ymd_His');
        $filename = $exportPath . '/ga4pro_export_' . $timestamp . '.' . $format;
        
        // Create export directory if it doesn't exist
        if (!is_dir($exportPath)) {
            mkdir($exportPath, 0755, true);
        }
        
        // Get the data to export (as an example, this would be GA4 data)
        $exportData = $this->prepareExportData();
        
        // Export the data in the requested format
        switch ($format) {
            case 'json':
                file_put_contents($filename, json_encode($exportData, JSON_PRETTY_PRINT));
                break;
            case 'csv':
                $this->exportToCsv($filename, $exportData);
                break;
            case 'xml':
                $this->exportToXml($filename, $exportData);
                break;
            default:
                throw new \Exception('Unsupported export format: ' . $format);
        }
        
        return $filename;
    }
    
    /**
     * Prepare data for export
     * 
     * @return array
     */
    protected function prepareExportData()
    {
        // This would retrieve data from GA4 API or local database
        // For now, we'll return sample structure
        return [
            'metadata' => [
                'generated_at' => date('Y-m-d H:i:s'),
                'measurement_id' => $this->scopeConfig->getValue('magento_ga4pro/general/measurement_id', ScopeInterface::SCOPE_STORE),
                'store_name' => $this->scopeConfig->getValue('general/store_information/name', ScopeInterface::SCOPE_STORE)
            ],
            'statistics' => [
                'pageviews' => 0,
                'events' => 0,
                'conversions' => 0,
                'revenue' => 0
            ],
            'top_products' => [],
            'events_summary' => []
        ];
    }
    
    /**
     * Export data to CSV format
     * 
     * @param string $filename
     * @param array $data
     * @return void
     */
    protected function exportToCsv($filename, array $data)
    {
        $file = fopen($filename, 'w');
        
        // Write metadata
        fputcsv($file, ['Metadata Key', 'Value']);
        foreach ($data['metadata'] as $key => $value) {
            fputcsv($file, [$key, $value]);
        }
        
        fputcsv($file, []); // Empty line as separator
        
        // Write statistics
        fputcsv($file, ['Statistic', 'Value']);
        foreach ($data['statistics'] as $key => $value) {
            fputcsv($file, [$key, $value]);
        }
        
        fclose($file);
    }
    
    /**
     * Export data to XML format
     * 
     * @param string $filename
     * @param array $data
     * @return void
     */
    protected function exportToXml($filename, array $data)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><ga4_export></ga4_export>');
        
        // Add metadata
        $metadata = $xml->addChild('metadata');
        foreach ($data['metadata'] as $key => $value) {
            $metadata->addChild($key, $value);
        }
        
        // Add statistics
        $statistics = $xml->addChild('statistics');
        foreach ($data['statistics'] as $key => $value) {
            $statistics->addChild($key, $value);
        }
        
        $xml->asXML($filename);
    }
}