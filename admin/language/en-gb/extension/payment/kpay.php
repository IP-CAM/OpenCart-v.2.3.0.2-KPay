<?php
// Heading
$_['heading_title']			='KPay';

// Text
$_['text_extension']			='Extensions';
$_['text_success']			='Success: You have modified KPay account details!';
$_['text_edit']                      	='Edit KPay';
$_['text_enabled']			='Enabled';
$_['text_kpay']				='<a target="KICTwindow" href="https://www.k-ict.org/"><img src="view/image/payment/kpay.png" alt="K-ICT Payment Gateway (KPay)" title="KPay" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_authorization']		='Authorization';
$_['text_sale']				='Sale'; 

// Entry
$_['entry_user_loginid']		='KPay User Login ID';
$_['entry_user_password']		='KPay User Password';
$_['entry_portal_key']			='KPay Portal Key';
$_['entry_order_payment_description']	='Order Payment Description';
$_['entry_email']			='E-Mail';
$_['entry_test']			='Sandbox Mode';
$_['entry_transaction']			='Transaction Method';
$_['entry_debug']			='Debug Mode';
$_['entry_total']			='Total';
$_['entry_canceled_reversal_status']	='Canceled Reversal Status';
$_['entry_completed_status']		='Completed Status';
$_['entry_denied_status']		='Denied Status';
$_['entry_expired_status']		='Expired Status';
$_['entry_failed_status']		='Failed Status';
$_['entry_pending_status']		='Pending Status';
$_['entry_processed_status']		='Processed Status';
$_['entry_refunded_status']		='Refunded Status';
$_['entry_reversed_status']		='Reversed Status';
$_['entry_voided_status']		='Voided Status';
$_['entry_geo_zone']			='Geo Zone';
$_['entry_status']			='Status';
$_['entry_sort_order']			='Sort Order';

// Tab
$_['tab_general']			='General';
$_['tab_order_status']       		='Order Status';

// Help
$_['help_user_loginid']			='Provide your MD5 hashed KPay user login ID.';
$_['help_user_password']		='Provide your MD5 hashed KPay user password.';
$_['help_portal_key']			='Provide your KPay Portal Key.';
$_['help_order_payment_description']	='Select your desired Order Payment Description type.';

$_['help_test']				='Use the live or testing (sandbox) gateway server to process transactions?';
$_['help_debug']			='Logs additional information to the system log';
$_['help_total']			='The checkout total the order must reach before this payment method becomes active';

# KPay API config start.
$_['KPay_provider_name']		='Konsortium ICT Pantai Timur';
$_['KPay_provider_url']			='https://www.k-ict.org/';
$_['KPay_url']				=$_['KPay_provider_url'].'kpg/';
$_['KPay_payment_url']			=$_['KPay_url'].'payment.php';
$_['KPay_receipt_url']			=$_['KPay_url'].'receipt.php';
$_['KPay_API_url']			=$_['KPay_url'].'API.php';
$_['KPay_MD5_hash_url']			=$_['KPay_provider_url'].'v4/online-security/md5-hash/';
$_['KPay_callback_url']			='?route=extension/payment/kpay/callback';
# KPay API config end.

// Error
$_['error_permission']			='Warning: You do not have permission to modify payment KPay!';
$_['error_user_loginid']		='KPay user login ID is required.';
$_['error_user_password']		='KPay user password is required.';
$_['error_portal_key']			='KPay Portal Key is required.';
$_['error_email']			='E-Mail required!';
