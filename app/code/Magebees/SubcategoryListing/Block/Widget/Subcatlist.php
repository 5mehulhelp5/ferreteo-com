<?php
namespace Magebees\SubcategoryListing\Block\Widget;
use Magento\Framework\View\Element\Template;
class Subcatlist extends Template implements \Magento\Widget\Block\BlockInterface
{	
    protected $_template = "widget/subcatlist.phtml";

    public function addData(array $arr)
    {
        $this->_data = array_merge($this->_data, $arr);
    }
    public function setData($key, $value = null)
    {
        $this->_data[$key] = $value;
    }
    public function _toHtml()
    {
        if ($this->getData('template')) {
            $this->setTemplate($this->getData('template'));
        }
        return parent::_toHtml();
    }
}