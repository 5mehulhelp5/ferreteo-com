<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Marketplace
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\Marketplace\Ui\Component\Listing\Columns\Frontend;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class OrderStatus.
 */
class Translate extends Column
{
    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
              
                    if($item[$fieldName] == 1){
                        $gender = __('Male');
                    }elseif($item[$fieldName]==2){
                        $gender = __('Female');
                    }else{
                        $gender = __('Not Specified');

                    }
                    $item[$fieldName] = $gender;
               
            }
        }

        return $dataSource;
    }
}
