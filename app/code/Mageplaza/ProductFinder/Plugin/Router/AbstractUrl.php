<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Mageplaza
 * @package   Mageplaza_ProductFinder
 * @copyright Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license   https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductFinder\Plugin\Router;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Theme\Block\Html\Pager;
use Mageplaza\ProductFinder\Helper\Data as HelperData;
use Mageplaza\ProductFinder\Model\ResourceModel\Rule;

/**
 * Class AbstractUrl
 * @package Mageplaza\ProductFinder\Plugin\Router
 */
abstract class AbstractUrl
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var Rule
     */
    protected $resourceRule;

    /**
     * @var Pager
     */
    protected $htmlPagerBlock;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * AbstractUrl constructor.
     *
     * @param HelperData $helperData
     * @param Http $request
     * @param Rule $resourceRule
     * @param Pager $htmlPagerBlock
     * @param UrlInterface $url
     */
    public function __construct(
        HelperData $helperData,
        Http $request,
        Rule $resourceRule,
        Pager $htmlPagerBlock,
        UrlInterface $url
    ) {
        $this->helperData     = $helperData;
        $this->request        = $request;
        $this->resourceRule   = $resourceRule;
        $this->htmlPagerBlock = $htmlPagerBlock;
        $this->url            = $url;
    }

    /**
     * @param string $result
     * @param array $query
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function createUrl($result, $query = [])
    {
        if (!$this->helperData->isEnabled()
            || $this->request->getFullActionName() !== 'mpproductfinder_finder_find') {
            return $result;
        }

        $urlParams           = $this->getUrlParams();
        $urlParams['_query'] = $query;

        $ruleId = $this->helperData->getRuleId();
        $rule   = $this->resourceRule->getRuleById($ruleId);

        return $this->helperData->customRouter(
            $rule['result_url'],
            $ruleId,
            $this->url->getUrl($rule['result_url'], $urlParams)
        );
    }

    /**
     * @return array
     */
    public function getUrlParams()
    {
        return [
            '_current'     => true,
            '_escape'      => true,
            '_use_rewrite' => true
        ];
    }
}
