<?php
// file: [Vendor]/[ModuleName]/Observer/AddCategoryLayoutUpdateHandleObserver.php
namespace Magebees\SubcategoryListing\Observer;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Layout as Layout;
use Magento\Framework\View\Layout\ProcessorInterface as LayoutProcessor;
/**
 *  AddCategoryLayoutUpdateHandleObserver
 */
class AddCategoryLayoutUpdateHandleObserver implements ObserverInterface
{
    private $registry;
    private $helper;

    public function __construct(
        Registry $registry,
        \Magebees\SubcategoryListing\Helper\Data $helper
        )
    {
        $this->registry = $registry;
        $this->helper = $helper;
    }

    public function execute(EventObserver $observer)
    {

        if($this->helper->isEnabled()){
            $event = $observer->getEvent();
            $actionName = $event->getData('full_action_name');
            /** @var CategoryModel|null $category **/
            $category = $this->registry->registry('current_category');

            if ($category && $actionName === 'catalog_category_view') 
            {
                $SubCatListStatus = $category->getShowSubcategoriesListing();
                $SubCatListLocation = $category->getMbsubcatlistlocation();

                $pageLayout = $this->helper->getPageLayout();

                $layout = $event->getData('layout');
                $layoutUpdate = $layout->getUpdate();

                if($SubCatListStatus || $SubCatListStatus == ""){
                    if($pageLayout){
                        $layoutUpdate->addHandle('catalog_category_onecolumn');
                    }else{
                        if($SubCatListLocation == 1 || $SubCatListLocation==""){
                            $layoutUpdate->addHandle('catalog_category_view_before');
                        }else{
                            $layoutUpdate->addHandle('catalog_category_view_after');
                        }
                    }
                }
                //if ($category->getData('display_mode') === CategoryModel::DM_MIXED) {
                    //$layoutUpdate->addHandle(static::LAYOUT_HANDLE_NAME);
                //}
            }
        }
    }
}