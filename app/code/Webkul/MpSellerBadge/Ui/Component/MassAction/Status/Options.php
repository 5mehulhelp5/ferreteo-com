<?php
/**
 * @category   Webkul
 * @package    Webkul_MpSellerBadge
 * @author     Webkul Software Private Limited
 * @copyright  Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerBadge\Ui\Component\MassAction\Status;
 
use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;
 
/**
 * Class Options
 * This contains statuses available that can be used for the massaction.
 */
class Options
{

    public function toOptionArray()
    {
        $states= ['1'=>'enable','0'=>'disable'];
    }
}
