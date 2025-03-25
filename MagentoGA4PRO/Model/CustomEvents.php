<?php
/**
 * GA4Pro Analytics Custom Events Model
 */
namespace Magento\GA4Pro\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;

class CustomEvents
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     * @param Json $json
     * @param Config $config
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        Json $json,
        Config $config
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->json = $json;
        $this->config = $config;
    }

    /**
     * Get all registered custom events
     *
     * @param int|null $storeId
     * @return array
     */
    public function getCustomEvents($storeId = null)
    {
        $eventsJson = $this->scopeConfig->getValue(
            'magento_ga4pro/custom_events/custom_events_config',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (empty($eventsJson)) {
            return [];
        }

        try {
            $events = $this->json->unserialize($eventsJson);
            return is_array($events) ? $events : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Save custom event configuration
     *
     * @param array $events
     * @param string $scope
     * @param int $scopeId
     * @return bool
     */
    public function saveCustomEvents(array $events, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        try {
            $eventsJson = $this->json->serialize($events);
            $this->configWriter->save(
                'magento_ga4pro/custom_events/custom_events_config',
                $eventsJson,
                $scope,
                $scopeId
            );

            // Enable custom events if we have events
            if (!empty($events) && !$this->config->isCustomEventsEnabled()) {
                $this->configWriter->save(
                    'magento_ga4pro/custom_events/enable_custom_events',
                    1,
                    $scope,
                    $scopeId
                );
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add or update a custom event
     *
     * @param string $eventName Event name
     * @param array $eventData Event parameters
     * @param int|null $storeId
     * @return bool
     */
    public function saveCustomEvent($eventName, array $eventData, $storeId = null)
    {
        $events = $this->getCustomEvents($storeId);
        
        // Create or update the event
        $found = false;
        foreach ($events as &$event) {
            if (isset($event['event_name']) && $event['event_name'] === $eventName) {
                $event = array_merge($event, $eventData);
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $eventData['event_name'] = $eventName;
            $events[] = $eventData;
        }
        
        return $this->saveCustomEvents($events, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Delete a custom event
     *
     * @param string $eventName
     * @param int|null $storeId
     * @return bool
     */
    public function deleteCustomEvent($eventName, $storeId = null)
    {
        $events = $this->getCustomEvents($storeId);
        
        foreach ($events as $key => $event) {
            if (isset($event['event_name']) && $event['event_name'] === $eventName) {
                unset($events[$key]);
                break;
            }
        }
        
        // Re-index array
        $events = array_values($events);
        
        return $this->saveCustomEvents($events, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get custom event JavaScript code
     *
     * @param int|null $storeId
     * @return string
     */
    public function getCustomEventsJsCode($storeId = null)
    {
        if (!$this->config->isCustomEventsEnabled()) {
            return '';
        }

        $events = $this->getCustomEvents($storeId);
        if (empty($events)) {
            return '';
        }

        $jsCode = "/* GA4 Custom Events */\n";
        foreach ($events as $event) {
            if (empty($event['event_name']) || empty($event['trigger_selector'])) {
                continue;
            }

            $eventName = $event['event_name'];
            $selector = $event['trigger_selector'];
            $triggerEvent = isset($event['trigger_event']) ? $event['trigger_event'] : 'click';
            
            // Build parameters JSON
            $paramString = '{}';
            if (!empty($event['event_parameters'])) {
                $paramString = json_encode($event['event_parameters']);
            }
            
            $jsCode .= "document.querySelectorAll('{$selector}').forEach(function(element) {\n";
            $jsCode .= "    element.addEventListener('{$triggerEvent}', function() {\n";
            $jsCode .= "        if (typeof gtag === 'function') {\n";
            $jsCode .= "            gtag('event', '{$eventName}', {$paramString});\n";
            $jsCode .= "            if (" . ($this->config->isDebugMode() ? 'true' : 'false') . ") {\n";
            $jsCode .= "                console.log('GA4 Custom Event: {$eventName}', {$paramString});\n";
            $jsCode .= "            }\n";
            $jsCode .= "        }\n";
            $jsCode .= "    });\n";
            $jsCode .= "});\n";
        }
        
        return $jsCode;
    }
}