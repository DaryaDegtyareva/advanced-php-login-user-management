</div><footer class="footer">
<div class="container">
<div class="row align-items-center">
<div class="col-8 mt-3 mt-lg-0 text-dark">
<?php if(isset($siteGuard->settings['footer'])) { echo $siteGuard->settings['footer']; } ?>
</div>
<div class="col-4 mt-3 mt-lg-0 text-right d-none d-md-block">
Copyright © 2020 <a href="https://codecanyon.net/item/siteguard-codeigniter-advanced-php-login-user-management-script/26712839" target="_blank">SiteGuard</a></small>
</div>
</div>
</div>
</footer>
<?php $assets_location = base_url().'/SiteGuard/'; ?>
<script src="<?php echo $assets_location; ?>js/core.js"></script>
<script src="<?php echo $assets_location; ?>js/tabler.js"></script>
<script src="<?php echo $assets_location; ?>js/jquery-sortable.js"></script>
<script src="<?php echo $assets_location; ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $assets_location; ?>plugins/charts-c3/js/d3.v3.min.js"></script>
<script src="<?php echo $assets_location; ?>plugins/charts-c3/js/c3.min.js"></script>
<script src="<?php echo $assets_location; ?>plugins/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo $assets_location; ?>plugins/cropper/cropper.min.js"></script>
<script> "use strict";

	function generateSwal(title, type, message) {
		swal(title, message, type);
	}

<?php
	if (isset($_GET['edit']) && isset($_GET['msg']) && $_GET['edit'] == "success") {
	$status_msg = escape_value($_GET['msg']);				
?>
		generateSwal("Success!","success","<?php echo $status_msg ?>");
<?php
	}
	if (isset($_GET['edit']) && isset($_GET['msg']) && $_GET['edit'] == "fail"  ) {
		$status_msg = escape_value($_GET['msg']);
?>
		generateSwal("Error!","error","<?php echo $status_msg ?>");
<?php 
	}
	
	if ($siteGuard->session->getFlashdata('errors')) {
?>
		swal({
			title: "Error!",
			type: "error",
			text: `<?php echo implode('<br>',$siteGuard->session->getFlashdata('errors')); ?>`,
			html: true
		});
<?php 
	}
?>
</script>