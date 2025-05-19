<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Acx\ZoomEnvios\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\ValidatorException;

/**
 * Represents a config URL that may point to a ZoomEnvios endpoint
 */
class ZoomUrl extends Value
{
    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        $host = parse_url((string)$this->getValue(), \PHP_URL_HOST);

        if (!empty($host) && !preg_match('/(?:.+\.|^)zoomenvios\.com$/i', $host)) {
            throw new ValidatorException(__('ZoomEnvios API endpoint URL\'s must use zoomenvios.com'));
        }

        return parent::beforeSave();
    }
}
