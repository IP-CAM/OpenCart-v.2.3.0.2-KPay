<?php
class ControllerExtensionPaymentKPay extends Controller
{
	private $error=array();

	public function index()
	{
		$this->load->language('extension/payment/kpay');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		
		if(($this->request->server['REQUEST_METHOD']=='POST') && $this->validate())
		{
			$this->model_setting_setting->editSetting('kpay',$this->request->post);

			$this->session->data['success']=$this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension','token='.$this->session->data['token'].'&type=payment',true));
		}
		
		$data['heading_title']=$this->language->get('heading_title');
		$data['KPay_url']=$this->language->get('KPay_url');
		$data['KPay_MD5_hash_url']=$this->language->get('KPay_MD5_hash_url');
		$base_url_explode=explode('admin/',$this->url->link('extension/payment/kpay/callback','',true));
		$base_url_prefix=$base_url_explode[0];
		$base_url_suffix=$base_url_explode[1];
		$callback_url=$base_url_prefix.$base_url_suffix;
		$data['KPay_order_payment_description_array']=array(
		array(
		'Simple field',
		'<div align="right" style="color:#ddf;"><i>Suitable for general sales.<br>Eg; Selling shirts, car accessories, and retail items.</i></div>',
		'Type01',
		$base_url_prefix.'image/payment/kpay/PaymentDescription001.jpg',
		),
		array(
		'Year and month (YYYY/MM) with simple field',
		'<div align="right" style="color:#ddf;"><i>Suitable for monthly-basis billing cycle.<br>Eg; Tuition fee, and monthly maintenance.</i></div>',
		'Type02',
		$base_url_prefix.'image/payment/kpay/PaymentDescription002.jpg',
		),
		array(
		'Date (YYYY/MM/DD) with simple field',
		'<div align="right" style="color:#ddf;"><i>Suitable for specific date billing cycle.<br>Eg; Hotel booking, and bus tickets.</i></div>',
		'Type03',
		$base_url_prefix.'image/payment/kpay/PaymentDescription003.jpg',
		));
		$data['KPay_OC_callback_url']=$callback_url;
		$data['text_edit']=$this->language->get('text_edit');
		$data['text_enabled']=$this->language->get('text_enabled');
		$data['text_disabled']=$this->language->get('text_disabled');
		$data['text_yes']=$this->language->get('text_yes');
		$data['text_no']=$this->language->get('text_no');
		$data['entry_status']=$this->language->get('entry_status');
		$data['entry_user_loginid']=$this->language->get('entry_user_loginid');
		$data['entry_user_password']=$this->language->get('entry_user_password');
		$data['entry_portal_key']=$this->language->get('entry_portal_key');
		$data['entry_order_payment_description']=$this->language->get('entry_order_payment_description');
		$data['entry_sort_order']=$this->language->get('entry_sort_order');
		$data['help_user_loginid']=$this->language->get('help_user_loginid');
		$data['help_user_password']=$this->language->get('help_user_password');
		$data['help_portal_key']=$this->language->get('help_portal_key');
		$data['help_order_payment_description']=$this->language->get('help_order_payment_description');
		$data['help_test']=$this->language->get('help_test');
		$data['button_save']=$this->language->get('button_save');
		$data['button_cancel']=$this->language->get('button_cancel');
		$data['tab_general']=$this->language->get('tab_general');
		
		if(isset($this->error['warning']))
			$data['error_warning']=$this->error['warning'];
		else
			$data['error_warning']='';
		if(isset($this->error['user_loginid']))
			$data['error_user_loginid']=$this->error['user_loginid'];
		else
			$data['error_user_loginid']='';
		if(isset($this->error['user_password']))
			$data['error_user_password']=$this->error['user_password'];
		else
			$data['error_user_password']='';
		if(isset($this->error['portal_key']))
			$data['error_portal_key']=$this->error['portal_key'];
		else
			$data['error_portal_key']='';

		$data['breadcrumbs']=array();

		$data['breadcrumbs'][]=array(
			'text'=>$this->language->get('text_home'),
			'href'=>$this->url->link('common/dashboard','token='.$this->session->data['token'],true)
		);

		$data['breadcrumbs'][]=array(
			'text'=>$this->language->get('text_extension'),
			'href'=>$this->url->link('extension/extension','token='.$this->session->data['token'].'&type=payment',true)
		);

		$data['breadcrumbs'][]=array(
			'text'=>$this->language->get('heading_title'),
			'href'=>$this->url->link('extension/payment/kpay','token='.$this->session->data['token'],true)
		);

		$data['action']=$this->url->link('extension/payment/kpay','token='.$this->session->data['token'],true);

		$data['cancel']=$this->url->link('extension/extension','token='.$this->session->data['token'].'&type=payment',true);

		if(isset($this->request->post['kpay_user_loginid']))
			$data['kpay_user_loginid']=$this->request->post['kpay_user_loginid'];
		else
			$data['kpay_user_loginid']=$this->config->get('kpay_user_loginid');
		if(isset($this->request->post['kpay_user_password']))
			$data['kpay_user_password']=$this->request->post['kpay_user_password'];
		else
			$data['kpay_user_password']=$this->config->get('kpay_user_password');
		if(isset($this->request->post['kpay_portal_key']))
			$data['kpay_portal_key']=$this->request->post['kpay_portal_key'];
		else
			$data['kpay_portal_key']=$this->config->get('kpay_portal_key');
		if(isset($this->request->post['kpay_order_payment_description']))
			$data['kpay_order_payment_description']=$this->request->post['kpay_order_payment_description'];
		else
			$data['kpay_order_payment_description']=$this->config->get('kpay_order_payment_description');
		if(isset($this->request->post['kpay_sort_order']))
			$data['kpay_sort_order']=$this->request->post['kpay_sort_order'];
		else
			$data['kpay_sort_order']=$this->config->get('kpay_sort_order');
		
		$this->load->model('localisation/order_status');

		$data['order_statuses']=$this->model_localisation_order_status->getOrderStatuses();

		if(isset($this->request->post['kpay_geo_zone_id']))
			$data['kpay_geo_zone_id']=$this->request->post['kpay_geo_zone_id'];
		else
			$data['kpay_geo_zone_id']=$this->config->get('kpay_geo_zone_id');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones']=$this->model_localisation_geo_zone->getGeoZones();

		if(isset($this->request->post['kpay_status']))
			$data['kpay_status']=$this->request->post['kpay_status'];
		else
			$data['kpay_status']=$this->config->get('kpay_status');
		if(isset($this->request->post['KPay_sort_order']))
			$data['kpay_sort_order']=$this->request->post['kpay_sort_order'];
		else
			$data['kpay_sort_order']=$this->config->get('kpay_sort_order');

		$data['header']=$this->load->controller('common/header');
		$data['column_left']=$this->load->controller('common/column_left');
		$data['footer']=$this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/kpay',$data));
	}

	private function validate()
	{
		if(!$this->user->hasPermission('modify','extension/payment/kpay'))
			$this->error['warning']=$this->language->get('error_permission');
		if(!$this->request->post['kpay_user_loginid'])
			$this->error['user_loginid']=$this->language->get('error_user_loginid');
		if(!$this->request->post['kpay_user_password'])
			$this->error['user_password']=$this->language->get('error_user_password');
		if(!$this->request->post['kpay_portal_key'])
			$this->error['portal_key']=$this->language->get('error_portal_key');
		return !$this->error;
	}
}
