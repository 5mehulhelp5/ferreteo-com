<?php
namespace Magecomp\Cityandregionmanager\Block\Adminhtml\System\Config\Buttons;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Importziplistbutton extends Genericbutton implements ButtonProviderInterface
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $button = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
            ->setData([
                'id'        => 'import_zip_list',
                'label'     => __('Import')
            ]);
        return $button->toHtml();
    }

    public function path()
    {
        return $this->getUrl('magecomp_cityandregionmanager/importZipList/import');
    }

    public function getButtonData()
    {
        return [
            'label' => __('Import'),
            'class' => 'import primary',
            'on_click' => sprintf("location.href = '%s';", $this->path()),
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'import']],
                'form-role' => 'import',
            ],
            'sort_order' => 90,
        ];
    }
}