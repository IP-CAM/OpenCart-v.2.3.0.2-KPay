<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
<div class="container-fluid">
<div class="pull-right">
<button type="submit" form="form-kpay-std-uk" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
<h1><?php echo $heading_title; ?></h1>
<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>
</div>
</div>
<div class="container-fluid">
<?php if (isset($error['error_warning'])) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
<button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
</div>
<div class="panel-body">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-kpay" class="form-horizontal">
<div class="tab-content">
<div class="tab-pane active" id="tab-general">
<div class="form-group">
<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
<div class="col-sm-10">
<select name="kpay_status" id="input-status" class="form-control">
<?php if ($kpay_status) { ?>
<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
<option value="0"><?php echo $text_disabled; ?></option>
<?php } else { ?>
<option value="1"><?php echo $text_enabled; ?></option>
<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
<?php } ?>
</select>
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="entry-user-loginid"><span data-toggle="tooltip" title="<?php echo $help_user_loginid; ?>"><?php echo $entry_user_loginid; ?></span></label>
<div class="col-sm-10">
<input type="text" name="kpay_user_loginid" value="<?php echo $kpay_user_loginid; ?>" placeholder="<?php echo $help_user_loginid; ?>" id="entry-user-loginid" class="form-control"/>
<i>Your <a href="<?php echo $KPay_url; ?>" target="KPayWindow"><?php echo $heading_title; ?></a> User Login ID <b>MUST</b> be hashed using MD5 algorithm.</i><br>&nbsp;
<div><a title="Click here to hash your <?php echo $heading_title; ?> Login ID." href="<?php echo $KPay_MD5_hash_url; ?>" target="KICTwindow" style="text-decoration:underline;cursor:pointer;width:auto;background:#300;color:#fff;padding:3px;padding-left:20px;padding-right:20px">Hash your user login ID, copy, and paste it in the above field.</a></div>
<?php if ($error_user_loginid) { ?>
<div class="text-danger"><?php echo $error_user_loginid; ?></div>
<?php } ?>
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="entry-user-password"><span data-toggle="tooltip" title="<?php echo $help_user_password; ?>"><?php echo $entry_user_password; ?></span></label>
<div class="col-sm-10">
<input type="password" name="kpay_user_password" value="<?php echo $kpay_user_password; ?>" placeholder="<?php echo $help_user_password; ?>" id="entry-user-password" class="form-control"/>
<i>Your <a href="<?php echo $KPay_url; ?>" target="KPayWindow"><?php echo $heading_title; ?></a> User Password <b>MUST</b> be hashed using MD5 algorithm.</i><br>&nbsp;
<div><a title="Click here to hash your <?php echo $heading_title; ?> User Password." href="<?php echo $KPay_MD5_hash_url; ?>" target="KICTwindow" style="text-decoration:underline;cursor:pointer;width:auto;background:#300;color:#fff;padding:3px;padding-left:20px;padding-right:20px">Hash your user password, copy, and paste it in the above field.</a></div>
<?php if ($error_user_password) { ?>
<div class="text-danger"><?php echo $error_user_password; ?></div>
<?php } ?>
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="entry-portal-key"><span data-toggle="tooltip" title="<?php echo $help_portal_key; ?>"><?php echo $entry_portal_key; ?></span></label>
<div class="col-sm-10">
<input type="text" name="kpay_portal_key" value="<?php echo $kpay_portal_key; ?>" placeholder="<?php echo $help_portal_key; ?>" id="entry-portal-key" class="form-control"/>
<i>Copy your Portal key from <a href="<?php echo $KPay_url; ?>" target="KPayWindow"><?php echo $heading_title; ?></a> and paste in the field.</i><br>&nbsp;
<div style="background:#358;color:#fff;padding:10px" align="left">Please config the <font style="color:#ff0"><?php echo $heading_title; ?> Receipt URL</font> as <font style="color:#ff0"><?php echo $KPay_OC_callback_url; ?></font></div>
<?php if ($error_portal_key) { ?>
<div class="text-danger"><?php echo $error_portal_key; ?></div>
<?php } ?>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label" for="input-order-payment-description"><span data-toggle="tooltip" title="<?php echo $help_order_payment_description; ?>"><?php echo $entry_order_payment_description; ?></span></label>
<div class="col-sm-10">
<select name="kpay_order_payment_description" id="input-order-payment-description" class="form-control" onchange="highlightPreview(this.options[selectedIndex].id);">
<?php for($a=0;$a<count($KPay_order_payment_description_array);$a++)
{ ?>
<option id="<?php echo $a; ?>" value="<?php echo $KPay_order_payment_description_array[$a][2]; ?>" <?php if($kpay_order_payment_description==$KPay_order_payment_description_array[$a][2]) echo 'selected'; ?>><?php echo $KPay_order_payment_description_array[$a][0]; ?></option>
<?php } ?>
</select>
<br><div style="background:#300;color:#fff;padding:3px;padding-left:10px">Choose the Order Payment Description field that suits your selling as shown in the screenshots below. Click on the thumbnail to enlarge image.</div><br>
<?php for($a=0;$a<count($KPay_order_payment_description_array);$a++)
{
?>
<div id="previewSample<?php echo $a; ?>Div" style="background: #830 none repeat scroll 0% 0%;color: #FF0;padding: 3px 3px 3px 10px;width: 350px;<?php if($kpay_order_payment_description!=$KPay_order_payment_description_array[$a][2]) echo 'opacity:0.5;'; ?>"><?php echo $KPay_order_payment_description_array[$a][0].$KPay_order_payment_description_array[$a][1]; ?></div><img id="previewImg<?php echo $a; ?>" alt="<?php echo $KPay_order_payment_description_array[$a][0]; ?>" src="<?php echo $KPay_order_payment_description_array[$a][3]; ?>" style="width:350px;<?php if($kpay_order_payment_description!=$KPay_order_payment_description_array[$a][2]) echo 'opacity:0.5;'; ?>"><br>&nbsp;
<?php
}
?>
</div>
<script>
function highlightPreview(typeNo)
{
<?php
for($a=0;$a<count($KPay_order_payment_description_array);$a++)
{
?>
document.getElementById('previewSample'+<?php echo $a; ?>+'Div').style.opacity='0.5';
document.getElementById('previewImg'+<?php echo $a; ?>).style.opacity='0.5';
<?php
}
?>
document.getElementById('previewSample'+typeNo+'Div').style.opacity='1.0';
document.getElementById('previewImg'+typeNo).style.opacity='1.0';
}
</script>
</div>
<div class="form-group">
<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
<div class="col-sm-10">
<input type="text" name="kpay_sort_order" value="<?php echo $kpay_sort_order; ?>" id="entry-sort-order" class="form-control"/>
</div>
</div>
</div>
</div>
<div class="pull-right">
<button type="submit" form="form-kpay-std-uk" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
</form>
</div>
</div>
</div>
</div>
<?php echo $footer; ?>
