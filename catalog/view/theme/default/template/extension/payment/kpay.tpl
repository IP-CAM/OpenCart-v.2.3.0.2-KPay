<div id="KPayLoaderDiv" style="position:fixed;z-index:500;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.25);background-image:url('catalog/view/image/payment/kpay/loader.gif');background-repeat:no-repeat;background-position:center;display:none;"></div>
<form id="KPayPaymentForm" action="<?php echo $action; ?>" method="post">
<input type="hidden" name="portal_key" value="<?php echo $KPay_portal_key; ?>" />
<input type="hidden" name="order_no" value="<?php echo $KPay_order_no; ?>" />
<input type="hidden" name="amount" value="<?php echo $KPay_order_amount; ?>" />
<input type="hidden" name="buyer_name" value="<?php echo $KPay_buyer_name; ?>" />
<input type="hidden" name="buyer_email" value="<?php echo $KPay_buyer_email; ?>" />
<input type="hidden" name="buyer_tel" value="<?php echo $KPay_buyer_tel; ?>" />
<input type="hidden" name="description" id="description" />
<table align="center" width="80%" style="background:#eee;border:0px;">
<?php
if(!empty($KPay_seller_name))
{
?>
<tr align="left" valign="top" style="background:#eee;">
<td><div style="font-size:25px;color:#0087cb;font-weight:normal;padding:25px;padding-bottom:0px;">Complete your order information</div>
<div style="border-bottom:1px dotted #ccc;color:#936;font-size:15px;font-weight:normal;padding:25px 20px 0px 25px;">Below are the information that will be submitted for Online Payment.</div>
</td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 5px 25px;"><font style="color:#630;">&#10148; Order number</font>
<br><span id="KPayOrderNumber" style="color:#008;"><?php echo $KPay_order_no; ?></span></td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 5px 25px;"><font style="color:#630;">&#10148; Amount</font>
<br><span id="KPayOrderAmount" style="color:#008;">MYR <?php echo number_format($KPay_order_amount,2,'.',','); ?></span></td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 5px 25px;"><font style="color:#630;">&#10148; Seller</font>
<br><span id="KPaySellerName" style="color:#008;"><?php echo $KPay_seller_name; ?></span></td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 5px 25px;"><font style="color:#630;">&#10148; Buyer name</font>
<br><span id="KPayOrderBuyerName" style="color:#008;"><?php echo $KPay_buyer_name; ?></span></td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 5px 25px;"><font style="color:#630;">&#10148; Buyer tel. no.</font>
<br><span id="KPayOrderBuyerTel" style="color:#008;"><?php echo $KPay_buyer_tel; ?></span></td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 5px 25px;"><font style="color:#630;">&#10148; Buyer email</font>
<br><span id="KPayOrderBuyerEmail" style="color:#008;"><?php echo $KPay_buyer_email; ?></span></td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 25px 25px;"><font style="color:#630;">&#10148; Payment for</font>
<?php
if($KPay_order_payment_description=='Type02')
{
?>
<br><input id="KPayOrderDescriptionYear" name="KPayOrderDescriptionYear" type="text" style="width:auto;text-align:center;border:1px solid #ccc;padding:2px;" size="4" placeholder="YYYY" value="<?php echo date('Y'); ?>">
<select id="KPayOrderDescriptionMonth" name="KPayOrderDescriptionMonth" style="width:auto;text-align:center;border-radius:1px;border:1px solid #ccc;width:80px;">
<option value="">Month</option>
<?php
for($a=1;$a<=12;$a++) # There are only 12 months.
{
if($a<10)
$a='0'.$a;
?>
<option value="<?php echo date('M',strtotime(date('Y').'-'.$a.'-01')); ?>"><?php echo date('M',strtotime(date('Y').'-'.$a.'-01')); ?></option>
<?php
}
?>
</select>
<input id="KPayOrderDescription" name="KPayOrderDescription" type="text" style="width:auto;" size="40" placeholder="Describe your payment here.">
</td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 25px 25px;">
<div align="center" style="margin-top:-20px;">
<div id="KPay_error_message" align="center" style="padding:5px;font-size:15px;background:#800;color:#fff;display:none;font-weight:bold;">&nbsp;</div>
<?php echo $KPay_security_notice; ?>
<input type="button" class="btn btn-primary" value="<?php echo $button_confirm; ?>" onclick="if(document.getElementById('KPayOrderDescriptionYear').value && document.getElementById('KPayOrderDescriptionYear').value.length==4 && /^[0-9]*$/g.test(document.getElementById('KPayOrderDescriptionYear').value) && document.getElementById('KPayOrderDescriptionMonth').value && document.getElementById('KPayOrderDescriptionMonth').value.length==3 && /^[a-zA-Z]*$/g.test(document.getElementById('KPayOrderDescriptionMonth').value) && document.getElementById('KPayOrderDescription').value) { document.getElementById('description').value=document.getElementById('KPayOrderDescriptionYear').value+'/'+document.getElementById('KPayOrderDescriptionMonth').value+'/'+document.getElementById('KPayOrderDescription').value; document.getElementById('KPay_error_message').style.display='none'; document.getElementById('KPayLoaderDiv').style.display='block'; document.getElementById('KPayPaymentForm').submit(); } else { document.getElementById('KPay_error_message').innerHTML='Please provide the description (YEAR, MONTH, and notes) for your payment.'; document.getElementById('KPay_error_message').style.display='block'; document.getElementById('KPayOrderDescriptionYear').focus(); };">
</div>
</td>
</tr>
<?php
}
elseif($KPay_order_payment_description=='Type03')
{
?>
<br><input id="KPayOrderDescriptionYear" name="KPayOrderDescriptionYear" type="text" style="width:auto;text-align:center;border:1px solid #ccc;padding:2px;" size="4" placeholder="YYYY" value="<?php echo date('Y'); ?>">
<select id="KPayOrderDescriptionMonth" name="KPayOrderDescriptionMonth" style="width:auto;text-align:center;border-radius:1px;border:1px solid #ccc;width:80px;">
<option value="">Month</option>
<?php
for($a=1;$a<=12;$a++) # There are only 12 months.
{
if($a<10)
$a='0'.$a;
?>
<option value="<?php echo date('M',strtotime(date('Y').'-'.$a.'-01')); ?>"><?php echo date('M',strtotime(date('Y').'-'.$a.'-01')); ?></option>
<?php
}
?>
</select>
<select id="KPayOrderDescriptionDay" name="KPayOrderDescriptionDay" style="width:auto;text-align:center;border-radius:1px;border:1px solid #ccc;width:80px;">
<option value="">Day</option>
<?php
for($a=1;$a<=31;$a++) # There are only maximum 31 days.
{
if($a<10)
$a='0'.$a;
?>
<option value="<?php echo $a; ?>"><?php echo $a; ?></option>
<?php
}
?>
</select>
<input id="KPayOrderDescription" name="KPayOrderDescription" type="text" style="width:auto;" size="40" placeholder="Describe your payment here.">
</td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 25px 25px;">
<div align="center" style="margin-top:-20px;">
<div id="KPay_error_message" align="center" style="padding:5px;font-size:15px;background:#800;color:#fff;display:none;font-weight:bold;">&nbsp;</div>
<?php echo $KPay_security_notice; ?>
<input type="button" class="btn btn-primary" value="<?php echo $button_confirm; ?>" onclick="if(document.getElementById('KPayOrderDescriptionYear').value && document.getElementById('KPayOrderDescriptionYear').value.length==4 && /^[0-9]*$/g.test(document.getElementById('KPayOrderDescriptionYear').value) && document.getElementById('KPayOrderDescriptionMonth').value && document.getElementById('KPayOrderDescriptionMonth').value.length==3 && /^[a-zA-Z]*$/g.test(document.getElementById('KPayOrderDescriptionMonth').value) && document.getElementById('KPayOrderDescriptionDay').value && document.getElementById('KPayOrderDescriptionDay').value.length==2 && /^[0-9]*$/g.test(document.getElementById('KPayOrderDescriptionDay').value) && document.getElementById('KPayOrderDescription').value) { document.getElementById('description').value=document.getElementById('KPayOrderDescriptionYear').value+'/'+document.getElementById('KPayOrderDescriptionMonth').value+'/'+document.getElementById('KPayOrderDescriptionDay').value+'/'+document.getElementById('KPayOrderDescription').value; document.getElementById('KPay_error_message').style.display='none'; document.getElementById('KPayLoaderDiv').style.display='block'; document.getElementById('KPayPaymentForm').submit(); } else { document.getElementById('KPay_error_message').innerHTML='Please provide the description (DATE, and notes) for your payment.'; document.getElementById('KPay_error_message').style.display='block'; document.getElementById('KPayOrderDescriptionYear').focus(); };">
</div>
</td>
</tr>
<?php
}
else
{
?>
<br><input id="KPayOrderDescription" name="KPayOrderDescription" type="text" style="width:auto;" size="40" placeholder="Describe your payment here." value="Order no. <?php echo $KPay_order_no; ?>">
</td>
</tr>
<tr align="left" valign="top" style="background:#eee;">
<td style="border:0px;font-size:16px;padding:5px 25px 25px 25px;">
<div align="center" style="margin-top:-20px;">
<div id="KPay_error_message" align="center" style="padding:5px;font-size:15px;background:#800;color:#fff;display:none;font-weight:bold;">&nbsp;</div>
<?php echo $KPay_security_notice; ?>
<input type="button" class="btn btn-primary" value="<?php echo $button_confirm; ?>" onclick="if(document.getElementById('KPayOrderDescription').value) { document.getElementById('description').value=document.getElementById('KPayOrderDescription').value; document.getElementById('KPay_error_message').style.display='none'; document.getElementById('KPayLoaderDiv').style.display='block'; document.getElementById('KPayPaymentForm').submit(); } else { document.getElementById('KPay_error_message').innerHTML='Please provide the description notes for your payment.'; document.getElementById('KPay_error_message').style.display='block'; document.getElementById('KPayOrderDescriptionYear').focus(); };">
</div>
</td>
</tr>
<?php
}
?>
<?php
}
else
{
?>
<tr align="left" valign="top" style="background:#eee;">
<td colspan="2"><div style="font-size:25px;color:#cb8700;font-weight:normal;padding:25px;padding-bottom:0px;">Invalid seller information</div>
<div style="color:#936;font-size:14px;font-weight:normal;padding:25px 25px 0px 25px;">Sorry, we are unable to continue due to the invalid seller information.</div>
<div style="color:#936;font-size:14px;font-weight:normal;padding:15px 25px 25px 25px;">If you are the store owner, please request for <?php echo $heading_title; ?> seller registration by sending email to <a href="mailto:sales@k-ict.org">sales@k-ict.org</a></div>
<div align="center" style="padding:0px 0px 25px 0px;">
<input type="button" class="btn btn-primary" value="Visit provider site" onclick="window.open('<?php echo $KPay_provider_url; ?>');">
</div>
</td>
</tr>
<?php
}
?>
</table>
</form>
