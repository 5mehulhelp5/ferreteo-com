<?php

namespace Magecomp\Cityandregionmanager\Plugin\Block\Cart;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magecomp\Cityandregionmanager\Model\Source\Stateoptions;
use Magecomp\Cityandregionmanager\Model\Config as Newconfig;
use Magecomp\Cityandregionmanager\Helper\Data as Helperdata;

class LayoutProcessor
{
    protected $configHelper;
    protected $dataHelper;
    protected $directoryHelper;

    protected $_stateOption;
    protected $_config;
    protected $magecomphelper;
	protected $merger;

    public function __construct(
        Stateoptions $stateOption,
        Newconfig $config,
        DirectoryHelper $directoryHelper,
        Helperdata $magecomphelper,
		\Magento\Checkout\Block\Checkout\AttributeMerger $merger
    ) {
        $this->directoryHelper = $directoryHelper;
        $this->_stateOption    = $stateOption;
        $this->_config         = $config;
		$this->merger = $merger;
        $this->magecomphelper         = $magecomphelper;
    }
    public function afterProcess(
        \Magento\Checkout\Block\Cart\LayoutProcessor $subject, array $jsLayout)
    {
        if($this->magecomphelper->isModuleEnabled())
        {
            $elements = [
            'city' => [
                'visible' => true,
                'formElement' => 'input',
                'label' => __('City'),
                'value' =>  null
            ],
            'country_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('Country'),
                'options' => [],
                'value' => null
            ],
            'region_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('State/Province'),
                'options' => [],
                'value' => null
            ],
            'postcode' => [
                'visible' => true,
                'formElement' => 'input',
                'label' => __('Zip/Postal Code'),
                'value' => null
            ]
        ];
		if (isset($jsLayout['components']['block-summary']['children']['block-shipping']['children']
            ['address-fieldsets']['children'])
        ) {
            $fieldSetPointer = &$jsLayout['components']['block-summary']['children']['block-shipping']
            ['children']['address-fieldsets']['children'];
            $fieldSetPointer = $this->merger->merge($elements, 'checkoutProvider', 'shippingAddress', $fieldSetPointer);
            $fieldSetPointer['region_id']['config']['skipValidation'] = true;

			$region = $this->_stateOption->getStates();

			$regionOptions[] = ['label' => __('Please Select..'), 'value' => ''];
			foreach ($region as $field) {
				$regionOptions[] = ['label' => $field['states_name'], 'value' => $field['states_name']];
			}
			unset($fieldSetPointer['region_id']);
			$fieldSetPointer['region']=[
                        'component' => 'Magento_Ui/js/form/element/select',
                        'config' => [
                            'customScope' => 'shippingAddress',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/select',
                            'id' => 'drop-down',
                            'additionalClasses' => 'state-drop-down',
                        ],
                        'dataScope' => 'shippingAddress.region',
                        'label' => __('State/Province'),
                        'provider' => 'checkoutProvider',
                        'visible' => true,
                        'validation' => ['required-entry' => false],
                        'sortOrder' => 111,
                        'id' => 'state-drop-down',
						'options' => $regionOptions
                    ];

			$fieldSetPointer['city']=[
                        'component' => 'Magento_Ui/js/form/element/select',
                        'config' => [
                            'customScope' => 'shippingAddress',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/select',
                            'id' => 'drop-down',
                            'additionalClasses' => 'city-drop-down',
                        ],
                        'dataScope' => 'shippingAddress.city',
                        'label' => __('City'),
                        'provider' => 'checkoutProvider',
                        'visible' => true,
                        'validation' => ['required-entry' => false],
                        'sortOrder' => 113,
                        'id' => 'city-drop-down',
                        'options' => [
                            [
                                'value' => '',
                                'label' => __('Please select...'),
                            ]
                        ]
                    ];
                      if($this->magecomphelper->isZipcodeEnabled()){
                    $fieldSetPointer['postcode']=[
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'config' => [
                            'customScope' => 'shippingAddress',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input',
                            'id' => 'drop-down',
                            'additionalClasses' => 'postcode-text-box',
                        ],
                        'dataScope' => 'shippingAddress.postcode',
                        'label' => __('Zip/Postal Code'),
                        'provider' => 'checkoutProvider',
                        'visible' => true,
                        'validation' => ['required-entry' => false],
                        'sortOrder' => 114,
                        'id' => 'postcode-text-box',
                    ];
                    }else{
			$fieldSetPointer['postcode']=[
                        'component' => 'Magento_Ui/js/form/element/select',
                        'config' => [
                            'customScope' => 'shippingAddress',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/select',
                            'id' => 'drop-down',
                            'additionalClasses' => 'postcode-drop-down',
                        ],
                        'dataScope' => 'shippingAddress.postcode',
                        'label' => __('Zip/Postal Code'),
                        'provider' => 'checkoutProvider',
                        'visible' => true,
                        'validation' => ['required-entry' => false],
                        'sortOrder' => 114,
                        'id' => 'postcode-drop-down',
                        'options' => [
                            [
                                'value' => '',
                                'label' => __('Please select...'),
                            ]
                        ]
                    ];
                }
        }
        }
        return $jsLayout;
    }
}
