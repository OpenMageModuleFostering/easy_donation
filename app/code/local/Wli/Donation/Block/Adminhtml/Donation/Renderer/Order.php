<?php
class Wli_Donation_Block_Adminhtml_Donation_Renderer_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
      * @return Distributor Name 
      */	
    public function render(Varien_Object $row)
    {
		
		$order_id = $row['order_id'];
		$orderId = Mage::getModel('sales/order')
                ->loadByIncrementId($order_id)
                ->getEntityId();
                $order_id_link = Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/view', array('order_id' => $orderId));
		
                $html = '<div>';
		$html .= '<span>'?> <a href="<?php echo $order_id_link;?>" title="Order Id"><?php echo $order_id;?></a><?php '</span>';
		$html .= '</div>';
                return $html;

    }
}
?>
