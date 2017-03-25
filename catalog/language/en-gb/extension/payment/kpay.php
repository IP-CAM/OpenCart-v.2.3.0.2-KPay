<?php
// Text
$_['heading_title']				='KPay';
$_['text_title']				='FPX<br><img src="catalog/view/image/payment/kpay/Kpay-FPX.jpg" alt="" title="FPX" style="border: 1px solid #EEEEEE;" />';
$_['text_testmode']				='Warning: The payment gateway is in \'Sandbox Mode\'. Your account will not be charged.';
$_['text_total']				='Shipping, Handling, Discounts & Taxes';
$_['KPay_animated_loader_image']		='<img src="catalog/view/image/payment/kpay/loader.gif" alt="Loading..." style="border: 1px solid #EEEEEE;" />';
$_['KPay_security_notice_GoogleChrome']		='Green bar in Google Chrome';
$_['KPay_security_notice_MozillaFirefox']	='Green bar in Mozilla Firefox';
$_['KPay_security_notice']			='<div align="center" style="color:#a00;font-size:15px;font-weight:bold;">
			<p>IMPORTANT!!! DO ONLY PROCEED with online payment if you see the <font style="color:#0a0;">green bar</font> as shown below after clicking on the <font style="color:#a46497;">Continue</font> button;</p>
			<p><img alt="'.$_["KPay_security_notice_GoogleChrome"].'" src="catalog/view/image/payment/kpay/EVSSLGoogleChrome.jpg"> '.$_["KPay_security_notice_GoogleChrome"].'</p>
			<p><img alt="'.$_["KPay_security_notice_MozillaFirefox"].'" src="catalog/view/image/payment/kpay/EVSSLMozillaFirefox.jpg"> '.$_["KPay_security_notice_MozillaFirefox"].'</p>
			<p>Please report any scam to abuse@k-ict.org</p>
			</div>';

# KPay API config start.
$_['KPay_provider_name']			='Konsortium ICT Pantai Timur';
$_['KPay_provider_url']				='https://www.k-ict.org/';
$_['KPay_url']					=$_['KPay_provider_url'].'kpg/';
$_['KPay_payment_url']				=$_['KPay_url'].'payment.php';
$_['KPay_receipt_url']				=$_['KPay_url'].'receipt.php';
$_['KPay_API_url']				=$_['KPay_url'].'API.php';
$_['KPay_API_user_agent']			=$_['KPay_url'].'API.php';
$_['KPay_API_client_name']			='KPG';
$_['KPay_API_client_type']			='APIclient';
$_['KPay_API_client_version']			='v1.1';
$_['KPay_API_user_agent']			=$_['KPay_API_client_name']." ".$_['KPay_API_client_type']." ".$_['KPay_API_client_version'];
# KPay API config end.
