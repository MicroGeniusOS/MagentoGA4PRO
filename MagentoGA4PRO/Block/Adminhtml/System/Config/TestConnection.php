<?php
/**
 * GA4Pro Analytics Test Connection Block
 */
namespace Magento\GA4Pro\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class TestConnection extends Field
{
    /**
     * Path to template file
     *
     * @var string
     */
    protected $_template = 'Magento_GA4Pro::system/config/test_connection.phtml';

    /**
     * Get Test Connection button HTML
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    /**
     * Get HTML ID for the button
     *
     * @return string
     */
    public function getHtmlId()
    {
        return 'ga4pro_test_connection_button';
    }

    /**
     * Get button label
     *
     * @return string
     */
    public function getButtonLabel()
    {
        return __('Test Connection');
    }

    /**
     * Get JSON encoded config for the test connection
     *
     * @return string
     */
    public function getTestConnectionConfig()
    {
        return json_encode([
            'url' => $this->getUrl('ga4pro/test/connection'),
            'elementId' => $this->getHtmlId()
        ]);
    }
}