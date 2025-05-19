<?php
namespace Magecomp\Cityandregionmanager\Ui\Component\Listing\Column\Zip;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column as ParentColumn;
use Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;

class Gridactions extends ParentColumn
{
    const DATA_DELETE       = 'magecomp_cityandregionmanager/zip/delete';
    const DATA_EDIT         = 'magecomp_cityandregionmanager/zip/edit';

    protected $actionUrlBuilder;

    protected $urlBuilder;

    private $editUrl;

    private $deleteUrl;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl   = self::DATA_EDIT,
        $deleteUrl = self::DATA_DELETE
    )
    {
        $this->urlBuilder       = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
        $this->editUrl          = $editUrl;
        $this->deleteUrl        = $deleteUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $item['entity_id']]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl($this->deleteUrl, ['id' => $item['entity_id']]),
                        'label' => __('Delete')
                    ];
                }
            }
        }
        return $dataSource;
    }
}