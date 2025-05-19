<?php
namespace Magebees\SubcategoryListing\Model\Category\Attribute\Source;

class Mbsubcatlistlocation extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $_optionsData;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        $this->_optionsData = $options;
    }

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => '1', 'label' => __('Before Content')],
                ['value' => '2', 'label' => __('After Content')]
            ];
        }
        return $this->_options;
    }
}