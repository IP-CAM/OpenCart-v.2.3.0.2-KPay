<?php
class ControllerExtensionPaymentKPay extends Controller
{
	public function index()
	{
		$this->load->language('extension/payment/kpay');
		
		# Fetch KPay configuration data start.
		$config_KPay_user_loginid=$this->config->get('kpay_user_loginid');
		$config_KPay_user_password=$this->config->get('kpay_user_password');
		$config_KPay_portal_key=$this->config->get('kpay_portal_key');
		# Fetch KPay configuration data end.
				
		$data['heading_title']=$this->language->get('heading_title');
		$data['button_confirm']=$this->language->get('button_confirm');
		$data['KPay_provider_url']=$this->language->get('KPay_provider_url');
		$KPay_provider_url=$data['KPay_provider_url'];
		$data['KPay_payment_url']=$this->language->get('KPay_payment_url');		
		$data['action']=$data['KPay_payment_url'];

		$this->load->model('checkout/order');
		
		$order_info=$this->model_checkout_order->getOrder($this->session->data['order_id']);

		if($order_info)
		{
			$data['item_name']=html_entity_decode($this->config->get('config_name'),ENT_QUOTES,'UTF-8');

			$data['products']=array();

			foreach($this->cart->getProducts() as $product)
			{
				$option_data=array();

				foreach($product['option'] as $option)
				{
					if($option['type']!='file')
					{
						$value=$option['value'];
					}
					else
					{
						$upload_info=$this->model_tool_upload->getUploadByCode($option['value']);
						
						if($upload_info)
						{
							$value=$upload_info['name'];
						}
						else
						{
							$value='';
						}
					}

					$option_data[]=array(
						'name'=>$option['name'],
						'value'=>(utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20).'..' : $value)
					);
				}

				$data['products'][]=array(
					'name'=>htmlspecialchars($product['name']),
					'model'=>htmlspecialchars($product['model']),
					'price'=>$this->currency->format($product['price'],$order_info['currency_code'],false,false),
					'quantity'=>$product['quantity'],
					'option'=>$option_data,
					'weight'=>$product['weight']
				);
			}

			$data['discount_amount_cart']=0;

			$total=$this->currency->format($order_info['total']-$this->cart->getSubTotal(),$order_info['currency_code'],false,false);

			if($total>0)
			{
				$data['products'][]=array(
					'name'     => $this->language->get('text_total'),
					'model'    => '',
					'price'    => $total,
					'quantity' => 1,
					'option'   => array(),
					'weight'   => 0
				);
			}
			else
			{
				$data['discount_amount_cart']-=$total;
			}
			
			$data['currency_code']=$order_info['currency_code'];
			$data['order_no']=html_entity_decode($order_info['order_id'], ENT_QUOTES, 'UTF-8');
			$data['buyer_first_name']=html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
			$data['buyer_last_name']=html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['address1']=html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
			$data['address2']=html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
			$data['city']=html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
			$data['zip']=html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
			$data['country']=$order_info['payment_iso_code_2'];
			$data['buyer_email']=$order_info['email'];
			$data['buyer_tel']=html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
			$data['order_amount']=html_entity_decode($order_info['total'], ENT_QUOTES, 'UTF-8');
			$data['order_payment_description']='Order no. '.$data['order_no'];
			$data['invoice']=$this->session->data['order_id'] . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['lc']=$this->session->data['language'];
			$data['return']=$this->url->link('checkout/success');
			$data['notify_url']=$this->url->link('extension/payment/kpay/callback','',true);
			$data['cancel_return']=$this->url->link('checkout/checkout', '', true);
			
			$data['KPay_animated_loader_image']=$this->language->get('KPay_animated_loader_image');
			$data['KPay_portal_key']=$config_KPay_portal_key;
			$data['KPay_order_no']=$data['order_no'];
			$data['KPay_order_amount']=$data['order_amount'];
			$data['KPay_buyer_name']=$data['buyer_first_name'].' '.$data['buyer_last_name'];
			$data['KPay_buyer_tel']=$data['buyer_tel'];
			$data['KPay_buyer_email']=$data['buyer_email'];
			$data['KPay_seller_name']='';
			$data['KPay_security_notice']=$this->language->get('KPay_security_notice');
			
			if(!empty($config_KPay_user_loginid) && !empty($config_KPay_user_password) && !empty($config_KPay_portal_key))
			{ # Fetch the seller name from KPay via API start.
				# Define the API data.
				$KPay_API_data=array(
				"UserLoginID"=>$config_KPay_user_loginid,
				"UserPassword"=>$config_KPay_user_password,
				"Category"=>"getSellerDetails",
				"PortalKey"=>$config_KPay_portal_key,
				);
				
				# Perform API operations.
				$KPay_API_operations=$this->KPay_API_operations($KPay_API_data);
				
				# Fetch the seller name.
				$data['KPay_seller_name']=$KPay_API_operations["BusinessName"];
			} # Fetch the seller name from KPay via API end.
			
			if(!empty($this->config->get('kpay_order_payment_description')))
				$data['KPay_order_payment_description']=$this->config->get('kpay_order_payment_description');
			else
				$data['KPay_order_payment_description']='';

			return $this->load->view('extension/payment/kpay',$data);
		}
	}

	public function callback()
	{ # Function callback start.
	
		$this->load->language('extension/payment/kpay');
		
		# Initialize the variables start.
		$order_no=0;
		$portal_key_is_ok=0;
		# Initialize the variables end.
		
		# Fetch the KPay configuration start.
		$config_KPay_API_url=$this->language->get('KPay_API_url');
		$config_KPay_receipt_url=$this->language->get('KPay_receipt_url');
		$config_KPay_user_loginid=$this->config->get('kpay_user_loginid');
		$config_KPay_user_password=$this->config->get('kpay_user_password');
		$config_KPay_portal_key=$this->config->get('kpay_portal_key');
		# Fetch the KPay configuration end.
		
		# Check whether we need a specific order transaction status response or all missing orders start.
		if(isset($this->request->post['portalKey']) && isset($this->request->post['orderNo']) && isset($this->request->post['orderAmount']) && isset($this->request->post['buyerName']) && isset($this->request->post['buyerEmail']) && isset($this->request->post['buyerTel']) && isset($this->request->post['orderDescription']) && isset($this->request->post['txnId']) && isset($this->request->post['txnEx']) && isset($this->request->post['txnStatus']))
		{ # Perform specific order transaction status response start.
			$KPay_portal_key=urldecode($this->request->post['portalKey']);
			$KPay_order_no=urldecode($this->request->post['orderNo']);
			$KPay_order_amount=urldecode($this->request->post['orderAmount']);
			$KPay_buyer_name=urldecode($this->request->post['buyerName']);
			$KPay_buyer_email=urldecode($this->request->post['buyerEmail']);
			$KPay_buyer_tel=urldecode($this->request->post['buyerTel']);
			$KPay_order_description=urldecode($this->request->post['orderDescription']);
			$KPay_FPX_txn_id=urldecode($this->request->post['txnId']);
			$KPay_txn_id=urldecode($this->request->post['txnEx']);
			$KPay_transaction_status=urldecode($this->request->post['txnStatus']);
			
			# Load the OC checkout model (method order).
			$this->load->model('checkout/order');
			
			# Fetch the order records from OC DB.
			$order_info=$this->model_checkout_order->getOrder($KPay_order_no);
			$payment_code=$order_info['payment_code'];
			
			# Security features : Verify the requested portal key with the config portal key.
			if($config_KPay_portal_key==$KPay_portal_key && $payment_code=='kpay')
			{ # Do only proceed to process if the portal key was verified, and order was processed using KPay start.
				# Define the API data.
				$KPay_API_data=array(
				"UserLoginID"=>$config_KPay_user_loginid,
				"UserPassword"=>$config_KPay_user_password,
				"Category"=>"getTransactionDetailsByOrderNumber",
				"PortalKey"=>$config_KPay_portal_key,
				"OrderNumber"=>$KPay_order_no,
				);
				
				# Perform API operations.
				$KPay_API_operations=$this->KPay_API_operations($KPay_API_data);
				
				# Process API response.
				$KPay_API_process_response=$this->KPay_API_process_response($KPay_API_operations);
				
				$this->KPay_check_all_orders(); # Also check for other missing orders.
				
				# Redirect to the KPay transaction receipt page.
				header('location:'.$config_KPay_receipt_url.'?txnId='.$KPay_FPX_txn_id.'&txnEx='.$KPay_txn_id);
			} # Do only proceed to process if the portal key was verified, and order was processed using KPay end.
			else
			exit('Sorry, there was invalid data provided hence we cannot continue processing your request.'); # Display error message for invalid result.
		} # Perform specific order transaction status response end.
		else
		{ # Perform all orders transaction status response start.
			# Check all orders latest transaction status.
			$this->KPay_check_all_orders();

			# Redirect to the main page.
			header('location:'.$this->config->get('config_url'));
		} # Perform all orders transaction status response end.
		# Check whether we need a specific order transaction status response or all missing orders end.
	} # Function callback end.
	
	public function KPay_check_all_orders()
	{
		$this->load->language('extension/payment/kpay');
		
		# Fetch the KPay configuration start.
		$config_KPay_API_url=$this->language->get('KPay_API_url');
		$config_KPay_receipt_url=$this->language->get('KPay_receipt_url');
		$config_KPay_user_loginid=$this->config->get('kpay_user_loginid');
		$config_KPay_user_password=$this->config->get('kpay_user_password');
		$config_KPay_portal_key=$this->config->get('kpay_portal_key');
		# Fetch the KPay configuration end.
		
		# Load KPay model.
		$this->load->model('extension/payment/kpay');
		
		# Fetch list of Missing Orders.
		$KPay_orders_missing_list=$this->model_extension_payment_kpay->listMissingOrders();
		
		if($KPay_orders_missing_list)
		{ # Do only process if there were Missing Orders start.
			
			foreach($KPay_orders_missing_list as $KPay_orders_missing_list_index=>$KPay_orders_missing_list_data)
			{ # Process each order start.
				foreach($KPay_orders_missing_list_data as $KPay_orders_missing_list_data_index=>$KPay_orders_missing_list_data_value)
					$KPay_order_no=$KPay_orders_missing_list_data_value;
				
				# Define the API data.
				$KPay_API_data=array(
				"UserLoginID"=>$config_KPay_user_loginid,
				"UserPassword"=>$config_KPay_user_password,
				"Category"=>"getTransactionDetailsByOrderNumber",
				"PortalKey"=>$config_KPay_portal_key,
				"OrderNumber"=>$KPay_order_no,
				);
					
				# Perform API operations.
				$KPay_API_operations=$this->KPay_API_operations($KPay_API_data);
				
				# Process API response.
				$KPay_API_process_response=$this->KPay_API_process_response($KPay_API_operations);
			} # Process each order end.
			
		} # Do only process if there were Missing Orders end.
		
		# No output returned.
	}
	
	public function KPay_API_process_response($KPay_API_data)
	{ # Function to process API response start.
		$this->load->language('extension/payment/kpay');
		
		$heading_title=$this->language->get('heading_title');
		$KPay_payment_url=$this->language->get('KPay_payment_url');
		$KPay_receipt_url=$this->language->get('KPay_receipt_url');
		
		# Load KPay model.
		$this->load->model('extension/payment/kpay');
		
		# Fetch list of order status.
		$KPay_order_status_list=$this->model_extension_payment_kpay->listOrderStatus();
			
		# Fetch each order status.
		foreach($KPay_order_status_list as $KPay_order_status_list_index=>$KPay_order_status_list_data)
		{
			foreach($KPay_order_status_list_data as $KPay_order_status_list_data_index=>$KPay_order_status_list_data_value)
			{
				if($KPay_order_status_list_data_index=='order_status_id')
					$OC_order_status_id=$KPay_order_status_list_data_value;
				if($KPay_order_status_list_data_index=='language_id')
					$OC_order_status_language_id=$KPay_order_status_list_data_value;
				if($KPay_order_status_list_data_index=='name')
					$OC_order_status_name=$KPay_order_status_list_data_value;
			}
			$OC_order_status[$OC_order_status_name]=$OC_order_status_id;
		}
		
		# Initialize variables.
		$order_need_to_update=0;
		$order_comment='';
		$KPay_order_no=$KPay_API_data['OrderNumber'];
		$KPay_order_no_simplified=$KPay_API_data['OrderNumberSimplified'];
		
		# Match the KPay transaction status with the OpenCart status.
		if($KPay_API_data['TransactionStatus']=='Successful')
		{
			$order_need_to_update=1;
			$order_status_id=$OC_order_status['Processing'];
			$order_comment='<font style="color:#080;"><b>Thank you for your payment.</b></font><br>'.$heading_title.' Transaction ID : <a href="'.$KPay_receipt_url.'?txnId='.$KPay_API_data['FPXTransactionID'].'&txnEx='.$KPay_API_data['TransactionID'].'" title="Click here to view transaction receipt at '.$heading_title.'." target="KPayWindow">'.$KPay_API_data['TransactionID'].'</a><br>FPX Transaction ID : <a href="'.$KPay_receipt_url.'?txnId='.$KPay_API_data['FPXTransactionID'].'&txnEx='.$KPay_API_data['TransactionID'].'" title="Click here to view transaction receipt at '.$heading_title.'." target="KPayWindow">'.$KPay_API_data['FPXTransactionID'].'</a>';
		}
		elseif($KPay_API_data['TransactionStatus']=='Unsuccessful')
		{
			$order_need_to_update=1;
			$order_status_id=$OC_order_status['Failed'];
			$order_comment='<font style="color:#a00;"><b>Sorry, your payment was unsuccessful.</b></font><br>Reason : <font style="color:#a00;"><b>'.$KPay_API_data['TransactionDescription'].'</b></font><br>'.$heading_title.' Transaction ID : <a href="'.$KPay_receipt_url.'?txnId='.$KPay_API_data['FPXTransactionID'].'&txnEx='.$KPay_API_data['TransactionID'].'" title="Click here to view transaction receipt at '.$heading_title.'." target="KPayWindow">'.$KPay_API_data['TransactionID'].'</a><br>FPX Transaction ID : <a href="'.$KPay_receipt_url.'?txnId='.$KPay_API_data['FPXTransactionID'].'&txnEx='.$KPay_API_data['TransactionID'].'" title="Click here to view transaction receipt at '.$heading_title.'." target="KPayWindow">'.$KPay_API_data['FPXTransactionID'].'</a><br><br><a href="'.$KPay_payment_url.'?order='.$KPay_order_no_simplified.'" style="background:#635;color:#ff0;font-weight:bold;text-decoration:none;font-size:12px;padding:5px;padding-left:12px;padding-right:12px;border-radius:5px;box-shadow:0px 0px 1px 1px #333 inset;" title="Click here to make another payment for this order." target="KPayWindow">Pay again</a>';
		}
		elseif($KPay_API_data['TransactionStatus']=='Pending')
		{
			$order_need_to_update=1;
			$order_status_id=$OC_order_status['Pending'];
			$order_comment='<font style="font-weight:bold;">Your transaction is still pending.</font>';
		}
		elseif($KPay_API_data['TransactionStatus']=='New')
			$order_need_to_update=0;
		else
		{
			$order_need_to_update=1;
			$order_status_id=$OC_order_status['Failed'];
			$order_comment=$KPay_API_data['Reason'];
		}
		
		# Update the corresponding order based on the API response.
		if($order_need_to_update)
		{ # Update order if needed start.
			$this->load->model('checkout/order');
			$this->model_checkout_order->addOrderHistory($KPay_order_no,$order_status_id,$order_comment,$notify=true,$override=false);
		} # Update order if needed end.
		
		# No output returned.
	} # Function to process API response end.
	
	public function KPay_API_operations($KPay_API_data)
	{ # Function to communicate with KPay API start.
		$this->load->language('extension/payment/kpay');
		
		# Fetch the KPay configuration start.
		$config_KPay_API_url=$this->language->get('KPay_API_url');
		$config_KPay_receipt_url=$this->language->get('KPay_receipt_url');
		$config_KPay_user_loginid=$this->config->get('kpay_user_loginid');
		$config_KPay_user_password=$this->config->get('kpay_user_password');
		$config_KPay_portal_key=$this->config->get('kpay_portal_key');
		# Fetch the KPay configuration end.
		
		# Request for the latest transaction response from the server (API request).
		$KPay_curl_output=$this->KPay_API_cURL($KPay_API_data);
		
		# Fetch the API response.
		$KPay_curl_output_response=$this->KPay_API_cURL_response($KPay_curl_output);
		
		# Translate the API response.
		$KPay_curl_output_result=$KPay_curl_output_response["Result"];
		$KPay_curl_output_reason=$KPay_curl_output_response["Reason"];
		$KPay_curl_output_data_mode=$KPay_curl_output_response["DataMode"];
		$KPay_order_number='';
		if(isset($KPay_curl_output_response["OrderNumber"]) && $KPay_curl_output_response["OrderNumber"])
		{
			$KPay_order_number=$KPay_curl_output_response["OrderNumber"];
			$KPay_order_number_simplified=$KPay_curl_output_response["OrderNumberSimplified"];
		}
		else
		$KPay_order_number=0;
		if($KPay_curl_output_response["TransactionID"])
		$KPay_transaction_id=$KPay_curl_output_response["TransactionID"];
		if($KPay_curl_output_response["TransactionStatus"])
		$KPay_transaction_status=$KPay_curl_output_response["TransactionStatus"];
		if($KPay_curl_output_response["TransactionDescription"])
		$KPay_transaction_description=$KPay_curl_output_response["TransactionDescription"];
		if($KPay_curl_output_response["FPXTransactionID"])
		$KPay_FPX_transaction_id=$KPay_curl_output_response["FPXTransactionID"];
		if($KPay_curl_output_response["BusinessName"])
		$KPay_curl_seller_name=$KPay_curl_output_response["BusinessName"];
		
		if($KPay_order_number)
		{
			# Update order status based on the latest transaction response.
			# Prepare the order data to be updated.
			$KPay_curl_output_response=array(
			"APIResult"=>$KPay_curl_output_result,
			"OrderNumber"=>$KPay_order_number,
			"OrderNumberSimplified"=>$KPay_order_number_simplified,
			"TransactionID"=>$KPay_transaction_id,
			"TransactionStatus"=>$KPay_transaction_status,
			"TransactionDescription"=>$KPay_transaction_description,
			"FPXTransactionID"=>$KPay_FPX_transaction_id);
		}
		
		return $KPay_curl_output_response;
	} # Function to communicate with KPay API end.
	
	public function KPay_API_cURL($KPay_API_data)
	{ # Function to connect to KPay API start.
		$this->load->language('extension/payment/kpay');
		
		# Fetch the KPay configuration start.
		$config_KPay_API_url=$this->language->get('KPay_API_url');
		$config_KPay_API_user_agent=$this->language->get('KPay_API_user_agent');
		$config_KPay_receipt_url=$this->language->get('KPay_receipt_url');
		$config_KPay_user_loginid=$this->config->get('kpay_user_loginid');
		$config_KPay_user_password=$this->config->get('kpay_user_password');
		$config_KPay_portal_key=$this->config->get('kpay_portal_key');
		# Fetch the KPay configuration end.

		# Fetch the API data.
		$KPay_loginid=$KPay_API_data["UserLoginID"];
		$KPay_password=$KPay_API_data["UserPassword"];
		$KPay_API_category=$KPay_API_data["Category"];
		$KPay_portal_key=$KPay_API_data["PortalKey"];
		$KPay_API_order_number='';
		if(isset($KPay_API_data["OrderNumber"]))
		$KPay_API_order_number=$KPay_API_data["OrderNumber"];
		else
		$KPay_API_order_number=0;

		# Use API call getTransactionDetailsByOrderNumber start.
		$KPay_API_data=array(
		"UserLoginID"=>rawurlencode($KPay_loginid),
		"UserPassword"=>rawurlencode($KPay_password),
		"Category"=>rawurlencode($KPay_API_category),
		"PortalKey"=>rawurlencode($KPay_portal_key),
		"OrderNumber"=>rawurlencode($KPay_API_order_number),
		);
		# Use API call getTransactionDetailsByOrderNumber end.

		# Count number of data to be POSTed.
		$KPay_API_data_count=count($KPay_API_data);

		$KPay_API_data_fields=""; # Initialize the data to be POSTed.
		foreach($KPay_API_data as $KPay_API_data_key=>$KPay_API_data_value)
		$KPay_API_data_fields.=$KPay_API_data_key.'='.$KPay_API_data_value.'&';
		rtrim($KPay_API_data_fields,'&');

		# cURL section start.
		$KPay_curl_output="";
		$KPay_curl=curl_init();
		curl_setopt($KPay_curl,CURLOPT_URL,$config_KPay_API_url);
		curl_setopt($KPay_curl,CURLOPT_USERAGENT,$config_KPay_API_user_agent);
		curl_setopt($KPay_curl,CURLOPT_POST,true);
		curl_setopt($KPay_curl,CURLOPT_POSTFIELDS,$KPay_API_data_fields);
		curl_setopt($KPay_curl,CURLOPT_RETURNTRANSFER,true);
		$KPay_curl_output=curl_exec($KPay_curl);
		curl_close($KPay_curl);
		# cURL section end.

		return $KPay_curl_output;
	} # Function to connect to KPay API end.

	public function KPay_API_cURL_response($KPay_curl_output)
	{ # Function to fetch the KPay API response start.
		# Decode JSON output to PHP object.
		$KPay_curl_output_object=json_decode($KPay_curl_output);

		# Initialize the output variables.
		$KPay_curl_output_reason="";
		$KPay_order_number="";
		$KPay_order_number_simplified="";
		$KPay_transaction_id="";
		$KPay_transaction_status="";
		$KPay_transaction_description="";
		$KPay_FPX_transaction_id="";
		$KPay_curl_output_result="";
		$KPay_curl_output_data_mode="";
		$KPay_curl_seller_name="";

		foreach($KPay_curl_output_object as $KPay_curl_output_object_data=>$KPay_curl_output_object_value)
		{ # Loop through each object start.

			if(is_object($KPay_curl_output_object_value))
			{ # If the return value is sub-object, loop through each sub-object start.

				foreach($KPay_curl_output_object_value as $KPay_curl_output_data=>$KPay_curl_output_value)
					{ # Fetch specific API response data start.
					if(urldecode($KPay_curl_output_data)=="Reason")
					$KPay_curl_output_reason=urldecode($KPay_curl_output_value);
					if(urldecode($KPay_curl_output_data)=="OrderNumber")
					$KPay_order_number=urldecode($KPay_curl_output_value);
					if(urldecode($KPay_curl_output_data)=="OrderNumberSimplified")
					$KPay_order_number_simplified=urldecode($KPay_curl_output_value);
					if(urldecode($KPay_curl_output_data)=="TransactionID")
					$KPay_transaction_id=urldecode($KPay_curl_output_value);
					if(urldecode($KPay_curl_output_data)=="TransactionStatus")
					$KPay_transaction_status=urldecode($KPay_curl_output_value);
					if(urldecode($KPay_curl_output_data)=="TransactionDescription")
					$KPay_transaction_description=urldecode($KPay_curl_output_value);
					if(urldecode($KPay_curl_output_data)=="FPXTransactionID")
					$KPay_FPX_transaction_id=urldecode($KPay_curl_output_value);
					if(urldecode($KPay_curl_output_data)=="BusinessName")
					$KPay_curl_seller_name=urldecode($KPay_curl_output_value);
					} # Fetch specific API response data end.

			} # If the return value is sub-object, loop through each sub-object end.
			else
			{ # Display normal object output start.

				if(urldecode($KPay_curl_output_object_data)=="Result")
				$KPay_curl_output_result=urldecode($KPay_curl_output_object_value);
				if(urldecode($KPay_curl_output_object_data)=="DataMode")
				$KPay_curl_output_data_mode=urldecode($KPay_curl_output_object_value);

			} # Display normal object output end.

		} # Loop through each object end.

		# Prepare the output to be returned.
		$KPay_curl_output_response_array=array(
		"Result"=>$KPay_curl_output_result,
		"Reason"=>$KPay_curl_output_reason,
		"DataMode"=>$KPay_curl_output_data_mode,
		"OrderNumber"=>$KPay_order_number,
		"OrderNumberSimplified"=>$KPay_order_number_simplified,
		"TransactionID"=>$KPay_transaction_id,
		"TransactionStatus"=>$KPay_transaction_status,
		"TransactionDescription"=>$KPay_transaction_description,
		"FPXTransactionID"=>$KPay_FPX_transaction_id,
		"BusinessName"=>$KPay_curl_seller_name,
		);

		return $KPay_curl_output_response_array;
	} # Function to fetch the KPay API response end.
}
