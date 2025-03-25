<?php
/**
 * GA4Pro Analytics Export Frequency Options
 */
namespace Magento\GA4Pro\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class ExportFrequency implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'daily', 'label' => __('Daily')],
            ['value' => 'weekly', 'label' => __('Weekly')],
            ['value' => 'monthly', 'label' => __('Monthly')],
            ['value' => 'manual', 'label' => __('Manual Only')]
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
            'daily' => __('Daily'),
            'weekly' => __('Weekly'),
            'monthly' => __('Monthly'),
            'manual' => __('Manual Only')
        ];
    }
}