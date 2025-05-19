<?php

namespace Magecomp\Cityandregionmanager\Plugin\Block\Checkout;

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

    public function __construct(
        Stateoptions $stateOption,
        Newconfig $config,
        DirectoryHelper $directoryHelper,
        Helperdata $magecomphelper

    ) {
        $this->directoryHelper = $directoryHelper;
        $this->_stateOption    = $stateOption;
        $this->_config         = $config;
        $this->magecomphelper         = $magecomphelper;
    }
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $jsLayout)
    {

        if($this->magecomphelper->isModuleEnabled())
        {
            if ($this->_config->getEnableExtensionYesNo()) {
                if ($jsLayout['components']['checkout']['children']['steps']
                ['children']['shipping-step']['children']['shippingAddress']
                ) {

                    $shippingAddressFieldSet = $jsLayout['components']['checkout']['children']['steps']
                    ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
                    $region = $this->_stateOption->getStates();

                    $regionOptions[] = ['label' => __('Please Select..'), 'value' => ''];
                    foreach ($region as $field) {
                        $regionOptions[] = ['label' => $field['states_name'], 'value' => $field['states_name']];
                    }
                    unset($shippingAddressFieldSet['region_id']);
                    $shippingAddressFieldSet['region'] = '';
                    $jsLayout['components']['checkout']['children']['steps']
                    ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $shippingAddressFieldSet;
                    $jsLayout['components']['checkout']['children']['steps']
                    ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['region'] = [
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
                        'validation' => ['required-entry' => true],
                        'sortOrder' => 75,
                        'id' => 'state-drop-down',
                        'options' => $regionOptions
                    ];
                    $jsLayout['components']['checkout']['children']['steps']
                    ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = [
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
                        'validation' => ['required-entry' => true],
                        'sortOrder' => 80,
                        'id' => 'city-drop-down',
                        'options' => [
                            [
                                'value' => '',
                                'label' => __('Please select...'),
                            ]
                        ]
                    ];

                    if ($this->magecomphelper->isZipcodeEnabled()) {

                        $jsLayout['components']['checkout']['children']['steps']
                        ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode'] = [
                            'component' => 'Magento_Ui/js/form/element/abstract',
                            'config' => [
                                'customScope' => 'shippingAddress',
                                'template' => 'ui/form/field',
                                'elementTmpl' => 'ui/form/element/input',
                                'id' => 'zip-text',
                                'additionalClasses' => 'postcode-text-box',
                            ],
                            'dataScope' => 'shippingAddress.postcode',
                            'label' => __('Zip/Postal Code'),
                            'provider' => 'checkoutProvider',
                            'visible' => true,
                            'sortOrder' => 90,
                            'id' => 'postcode-text-box',
                        ];
                    } else {
                        $jsLayout['components']['checkout']['children']['steps']
                        ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode'] = [
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
                            'validation' => ['required-entry' => true],
                            'sortOrder' => 85,
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
        }
        return $jsLayout;
    }
}
