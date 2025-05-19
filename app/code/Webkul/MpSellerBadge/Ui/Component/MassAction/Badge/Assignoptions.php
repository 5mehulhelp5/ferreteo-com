<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Ui\Component\MassAction\Badge;

use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;
use Webkul\MpSellerBadge\Model\ResourceModel\Badge\CollectionFactory;

/**
 * Class Options
 */
class Assignoptions implements JsonSerializable
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Additional options params
     *
     * @var array
     */
    protected $data;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Base URL for subactions
     *
     * @var string
     */
    protected $urlPath;

    /**
     * Param name for subactions
     *
     * @var string
     */
    protected $param;

    /**
     * Additional params for subactions
     *
     * @var array
     */
    protected $additionalData = [];

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->data = $data;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Get action options
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $options = [];
        if ($this->options === null) {
            $badgeColl = $this->collectionFactory->create()->addFieldToFilter(
                'status',
                ['eq'=>1]
            );
            $i=0;
            if (empty($badgeColl)) {
                return $this->options;
            }
            foreach ($badgeColl as $key => $badge) {
                $options[$i]['value']=$badge->getEntityId();
                $options[$i]['label']=$badge->getBadgeName();
                $i++;
            }
            $this->prepareData();
            if (!empty($options)) {
                foreach ($options as $option) {
                    $this->options[$option['value']] = [
                        'type' => 'badge_' . $option['value'],
                        'label' => $option['label'],
                    ];

                    if ($this->urlPath && $this->param) {
                        $this->options[$option['value']]['url'] = $this->urlBuilder->getUrl(
                            $this->urlPath,
                            [$this->param => $option['value']]
                        );
                    }

                    $this->options[$option['value']] = array_merge_recursive(
                        $this->options[$option['value']],
                        $this->additionalData
                    );
                }
            
                $this->options = array_values($this->options);
            }
        }
        return $this->options;
    }

    /**
     * Prepare addition data for subactions
     *
     * @return void
     */
    protected function prepareData()
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->param = $value;
                    break;
                case 'confirm':
                    foreach ($value as $messageName => $message) {
                        $this->additionalData[$key][$messageName] = (string) new Phrase($message);
                    }
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
