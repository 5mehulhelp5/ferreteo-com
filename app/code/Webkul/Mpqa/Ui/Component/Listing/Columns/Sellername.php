<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Mpqa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
    namespace Webkul\Mpqa\Ui\Component\Listing\Columns;

    use Magento\Framework\View\Element\UiComponent\ContextInterface;
    use Magento\Framework\View\Element\UiComponentFactory;
    use Magento\Ui\Component\Listing\Columns\Column;

class Sellername extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');
            foreach ($dataSource['data']['items'] as &$item) {
                if (($item['seller_name'])=="") {
                    $item['seller_name'] = "Admin";
                }
            }
        }
        return $dataSource;
    }
}
