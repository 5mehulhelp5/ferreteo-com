<?php
namespace Magecomp\Cityandregionmanager\Block\Checkout;

use Magento\Checkout\Helper\Data;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Magecomp\Cityandregionmanager\Helper\Data as Helperdata;
class LayoutProcessor extends \Magento\Checkout\Block\Checkout\LayoutProcessor
{
    private $attributeMetadataDataProvider;
    protected $attributeMapper;
    protected $merger;
    private $options;
    private $checkoutDataHelper;
    private $storeManager;
    private $shippingConfig;
    protected $magecomphelper;

    public function __construct(
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider,
        \Magento\Ui\Component\Form\AttributeMapper $attributeMapper,
        \Magento\Checkout\Block\Checkout\AttributeMerger $merger,
        \Magento\Customer\Model\Options $options = null,
        Data $checkoutDataHelper = null,
        \Magento\Shipping\Model\Config $shippingConfig = null,
        StoreManagerInterface $storeManager = null,
        Helperdata $magecomphelper
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->attributeMapper = $attributeMapper;
        $this->merger = $merger;
        $this->options = $options ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Customer\Model\Options::class);
        $this->checkoutDataHelper = $checkoutDataHelper ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(Data::class);
        $this->shippingConfig = $shippingConfig ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Shipping\Model\Config::class);
        $this->storeManager = $storeManager ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(StoreManagerInterface::class);
        $this->magecomphelper = $magecomphelper;
    }
    private function getAddressAttributes()
    {
        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );

        $elements = [];
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();
            if ($attribute->getIsUserDefined()) {
                continue;
            }
            $elements[$code] = $this->attributeMapper->map($attribute);
            if (isset($elements[$code]['label'])) {
                $label = $elements[$code]['label'];
                $elements[$code]['label'] = __($label);
            }
        }
        return $elements;
    }
    private function convertElementsToSelect($elements, $attributesToConvert)
    {
        $codes = array_keys($attributesToConvert);
        foreach (array_keys($elements) as $code) {
            if (!in_array($code, $codes)) {
                continue;
            }
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $options = call_user_func($attributesToConvert[$code]);
            if (!is_array($options)) {
                continue;
            }
            $elements[$code]['dataType'] = 'select';
            $elements[$code]['formElement'] = 'select';

            foreach ($options as $key => $value) {
                $elements[$code]['options'][] = [
                    'value' => $key,
                    'label' => $value,
                ];
            }
        }

        return $elements;
    }
    public function process($jsLayout)
    {
        $attributesToConvert = [
            'prefix' => [$this->options, 'getNamePrefixOptions'],
            'suffix' => [$this->options, 'getNameSuffixOptions'],
        ];

        $elements = $this->getAddressAttributes();
        $elements = $this->convertElementsToSelect($elements, $attributesToConvert);
        // The following code is a workaround for custom address attributes
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children'])) {
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children'] = $this->processPaymentChildrenComponents(
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children'],
                $elements
            );
        }
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['step-config']['children']['shipping-rates-validation']['children'])) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['step-config']['children']['shipping-rates-validation']['children'] =
                $this->processShippingChildrenComponents(
                    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                    ['step-config']['children']['shipping-rates-validation']['children']
                );
        }

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'])) {
            $fields = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'shippingAddress',
                $fields
            );
        }
        return $jsLayout;
    }
    private function processShippingChildrenComponents($shippingRatesLayout)
    {
        $activeCarriers = $this->shippingConfig->getActiveCarriers(
            $this->storeManager->getStore()->getId()
        );
        foreach (array_keys($shippingRatesLayout) as $carrierName) {
            $carrierKey = str_replace('-rates-validation', '', $carrierName);
            if (!array_key_exists($carrierKey, $activeCarriers)) {
                unset($shippingRatesLayout[$carrierName]);
            }
        }
        return $shippingRatesLayout;
    }

    private function processPaymentChildrenComponents(array $paymentLayout, array $elements)
    {
        if (!isset($paymentLayout['payments-list']['children'])) {
            $paymentLayout['payments-list']['children'] = [];
        }

        if (!isset($paymentLayout['afterMethods']['children'])) {
            $paymentLayout['afterMethods']['children'] = [];
        }

        // The if billing address should be displayed on Payment method or page
        if ($this->checkoutDataHelper->isDisplayBillingOnPaymentMethodAvailable()) {
            $paymentLayout['payments-list']['children'] =
                array_merge_recursive(
                    $paymentLayout['payments-list']['children'],
                    $this->processPaymentConfiguration(
                        $paymentLayout['renders']['children'],
                        $elements
                    )
                );
        } else {
            $component['billing-address-form'] = $this->getBillingAddressComponent('shared', $elements);
            $paymentLayout['afterMethods']['children'] =
                array_merge_recursive(
                    $component,
                    $paymentLayout['afterMethods']['children']
                );
        }

        return $paymentLayout;
    }

    private function processPaymentConfiguration(array &$configuration, array $elements)
    {
        $output = [];
        foreach ($configuration as $paymentGroup => $groupConfig) {
            foreach ($groupConfig['methods'] as $paymentCode => $paymentComponent) {
                if (empty($paymentComponent['isBillingAddressRequired'])) {
                    continue;
                }
                $output[$paymentCode . '-form'] = $this->getBillingAddressComponent($paymentCode, $elements);
            }
            unset($configuration[$paymentGroup]['methods']);
        }

        return $output;
    }

    private function getBillingAddressComponent($paymentCode, $elements)
    {
        if($this->magecomphelper->isZipcodeEnabled()){
            $zipCodes = ['component' => 'Magento_Ui/js/form/element/post-code',
                         'config' => ['customScope' => 'billingAddress',
                                    'template' => 'ui/form/field',
                                    'elementTmpl' => 'ui/form/element/input',
                                    'id' => 'zip-text',
                                    'additionalClasses' => 'postcode-text-box',
            ]];
        }
        else
        {
            $zipCodes =[ 'component' => 'Magento_Ui/js/form/element/select',
                'config' => [
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/select',
                    'additionalClasses' => 'postcode-drop-down',
                ],
                'validation' => ['required-entry' => true]
            ];
        }
        return [
            'component' => 'Magento_Checkout/js/view/billing-address',
            'displayArea' => 'billing-address-form-' . $paymentCode,
            'provider' => 'checkoutProvider',
            'deps' => 'checkoutProvider',
            'dataScopePrefix' => 'billingAddress' . $paymentCode,
            'billingAddressListProvider' => '${$.name}.billingAddressList',
            'sortOrder' => 1,
            'children' => [
                'billingAddressList' => [
                    'component' => 'Magento_Checkout/js/view/billing-address/list',
                    'displayArea' => 'billing-address-list',
                    'template' => 'Magento_Checkout/billing-address/list'
                ],
                'form-fields' => [
                    'component' => 'uiComponent',
                    'displayArea' => 'additional-fieldsets',
                    'children' => $this->merger->merge(
                        $elements,
                        'checkoutProvider',
                        'billingAddress' . $paymentCode,
                        [
                            'country_id' => [
                                'sortOrder' => 115,
                            ],
                            'region_id' => [
                                'visible' => false,
                            ],
                            'region' => [
                                'component' => 'Magento_Ui/js/form/element/select',
                                'config' => [
                                    'template' => 'ui/form/field',
                                    'elementTmpl' => 'ui/form/element/select',
                                    'id' => 'drop-down',
                                    'additionalClasses' => 'state-drop-down',
                                ],
                                'validation' => [
                                    'required-entry' => true,
                                ],
                                'sortOrder' => 75
                            ],
                            'city' => [
                                'component' => 'Magento_Ui/js/form/element/select',
                                'config' => [
                                    'template' => 'ui/form/field',
                                    'elementTmpl' => 'ui/form/element/select',
                                    'id' => 'city-drop-down',
                                    'additionalClasses' => 'city-drop-down',
                                ],
                                'validation' => [
                                    'required-entry' => true,
                                ],
                                'sortOrder' => 80
                            ],
                            'postcode' => $zipCodes,
                            'company' => [
                                'validation' => [
                                    'min_text_length' => 0,
                                ],
                            ],
                            'fax' => [
                                'validation' => [
                                    'min_text_length' => 0,
                                ],
                            ],
                            'telephone' => [
                                'config' => [
                                    'tooltip' => [
                                        'description' => __('For delivery questions.'),
                                    ],
                                ],
                            ],
                        ]
                    ),
                ],
            ],
        ];
    }
}
