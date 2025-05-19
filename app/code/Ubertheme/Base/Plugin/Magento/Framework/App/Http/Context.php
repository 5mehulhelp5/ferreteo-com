<?php
/**
 * Copyright © 2016 Ubertheme.com All rights reserved.
 */
namespace Ubertheme\Base\Plugin\Magento\Framework\App\Http;

use Magento\Framework\App\Http\Context as HttpContext;

class Context
{
    public function beforeGetVaryString(HttpContext $subject)
    {
        $detect = new \Mobile_Detect();
        $device = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
        $subject->setValue('user_device', $device, 'default');
    }
}
