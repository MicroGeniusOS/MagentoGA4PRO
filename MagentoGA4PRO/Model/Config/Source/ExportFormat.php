<?php
/**
 * GA4Pro Analytics Export Format Options
 */
namespace Magento\GA4Pro\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class ExportFormat implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'json', 'label' => __('JSON')],
            ['value' => 'csv', 'label' => __('CSV')],
            ['value' => 'xml', 'label' => __('XML')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'json' => __('JSON'),
            'csv' => __('CSV'),
            'xml' => __('XML')
        ];
    }
}