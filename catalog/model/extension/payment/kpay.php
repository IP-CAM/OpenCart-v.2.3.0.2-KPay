<?php
class ModelExtensionPaymentKPay extends Model
{
	public function getMethod($address,$total)
	{
		$this->load->language('extension/payment/kpay');

		$status=true;

		$currencies=array(
			'MYR',
			'USD',
		);

		if(!in_array(strtoupper($this->session->data['currency']),$currencies))
			$status = false;

		$method_data=array();

		if($status)
		{
			$method_data=array(
				'code'=>'kpay',
				'title'=>$this->language->get('text_title'),
				'terms'=>'',
				'sort_order'=>$this->config->get('kpay_sort_order')
			);
		}

		return $method_data;
	}
	
	function listMissingOrders()
	{ # Function listOrders start.
		$order_query=$this->db->query("SELECT o.`order_id` AS order_id FROM `".DB_PREFIX."order` o WHERE o.`payment_code`='kpay' AND o.`order_status_id`=0 ORDER BY o.`order_id` ASC"); # Check for order that has been attempted to be paid using KPay with the order status labeled as 'Missing Orders'.
		$order_rows=$order_query->num_rows;
		if($order_rows)
			return $order_query->rows;
		else
			return false;
	} # Function listOrders end.
	
	function listOrderStatus()
	{ # Function listOrderStatus start.
		$order_query=$this->db->query("SELECT * FROM `".DB_PREFIX."order_status` os ORDER BY os.`order_status_id` ASC"); # Check for order that has been attempted to be paid using KPay with the order status labeled as 'Missing Orders'.
		$order_rows=$order_query->num_rows;
		if($order_rows)
			return $order_query->rows;
		else
			return false;
	} # Function listOrderStatus end.
}
