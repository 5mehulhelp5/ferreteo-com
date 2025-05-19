<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_ProductUnitsAndQuantities
 */

/**
 * Copyright © 2019 Aitoc. All rights reserved.
 */

namespace Aitoc\ProductUnitsAndQuantities\Model\BaseModelRepositoryTraits;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Trait RepositorySaveTrait
 */
trait RepositoryDeleteTrait
{

    /**
     * @return AbstractDb
     */
    abstract protected function getResourceModel();

    /**
     * @param object $interface
     * @return AbstractModel
     */
    abstract protected function interfaceToModel($interface);

    /**
     * @param object $interface
     * @throws CouldNotDeleteException
     */
    public function delete($interface)
    {
        $model = $this->interfaceToModel($interface);

        try {
            $this->getResourceModel()->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }
}
