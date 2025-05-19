<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorRegistration
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorRegistration\Ui\Component\MassAction\Group;
 
use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;
use Webkul\MpVendorRegistration\Model\ResourceModel\VendorRegistrationGroup\CollectionFactory;
 
/**
 * Class Options
 */
class AssignGroup implements JsonSerializable
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
    protected $dataArray;
 
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
    protected $paramName;
 
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
        $this->dataArray = $data;
        $this->urlBuilder = $urlBuilder;
    }
 
    /**
     * Get action options
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $i=0;
        if ($this->options === null) {
            // get the massaction data from the database table
            $model = $this->collectionFactory->create()->addFieldToFilter('group_status', ['eq'=>1])
            ->addFieldToFilter('group_code', ['neq' => 'addressinfo']);
            if ($model->getSize() == 0) {
                return $this->options;
            }
            //make a array of massaction
            foreach ($model as $key => $group) {
                $options[$i]['value']=$group->getEntityId();
                $options[$i]['label']=$group->getGroupName();
                $i++;
            }
            $this->prepareData();
            foreach ($options as $optionCodeData) {
                $this->options[$optionCodeData['value']] = [
                    'type' => 'badge_' . $optionCodeData['value'],
                    'label' => $optionCodeData['label'],
                ];
 
                if ($this->urlPath && $this->paramName) {
                    $this->options[$optionCodeData['value']]['url'] = $this->urlBuilder->getUrl(
                        $this->urlPath,
                        [$this->paramName => $optionCodeData['value']]
                    );
                }
 
                $this->options[$optionCodeData['value']] = array_merge_recursive(
                    $this->options[$optionCodeData['value']],
                    $this->additionalData
                );
            }
             
            // return the massaction data
            $this->options = array_values($this->options);
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
        foreach ($this->dataArray as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
