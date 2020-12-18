<?php 
$assets_location = base_url().'/SiteGuard/';
$next = 'index';
if(isset($_GET['next']) && $_GET['next'] != '' ) {
	$next = escape_value($_GET['next']);
}
$type = 'login';
if(isset($_GET['type']) && $_GET['type'] == 'forgot-password' ) {
	$type = 'forgot-password';
} elseif(isset($_GET['type']) && $_GET['type'] == 'register' && $siteGuard->settings['registration'] == 'on' ) {
	$type = 'register';
} elseif(isset($_GET['type']) && $_GET['type'] == 'activation-link' && isset($siteGuard->settings['registration_activate']) && $siteGuard->settings['registration_activate'] == 'self_activation' ) {
	$type = 'activation-link';
}
?>
<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" type="image/png" href="<?php echo $assets_location; ?>images/shield.png"/>
    <title>Login | SiteGuard ®</title>
    <link rel="stylesheet" href="<?php echo $assets_location; ?>/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700&amp;subset=latin-ext">
    <!-- Dashboard Core -->
    <link href="<?php echo $assets_location; ?>css/dashboard.css" rel="stylesheet" />
    <script src="<?php echo $assets_location; ?>js/vendors/jquery-3.2.1.min.js"></script>
    <script src="<?php echo $assets_location; ?>js/vendors/bootstrap.bundle.min.js"></script>
  </head>
  <body class="">
    <div class="page">
      <div class="page-single">
        <div class="container">
          <div class="row">
            <div class="col col-login mx-auto">
              <div class="text-center mb-6">
                <img src="<?php echo $assets_location; ?>images/logo.png" class="h-8" alt="">
              </div>
              <form class="card" action="<?php echo base_url('login'); ?>" method="post" autocomplete="off" > 
                <div class="card-body p-6">
					
					<?php
						if (isset($_GET['edit']) && isset($_GET['msg']) && $_GET['edit'] == "success") :
						$status_msg = escape_value($_GET['msg']);				
					?>
						<div class="alert alert-success">
							<i class="fa fa-ok"></i> <strong>Success!</strong>&nbsp;&nbsp;<?php echo $status_msg; ?>
						</div>
					<?php
						endif; 	
						
						if (isset($_GET['edit']) && isset($_GET['msg']) && $_GET['edit'] == "fail" || isset($error_message) && $error_message != '' || $siteGuard->session->getFlashdata('errors') ) :
						if(isset($error_message) && $error_message != '' ) {
							$status_msg= $error_message;
						} elseif(isset($_GET['msg'])) {
							$status_msg = escape_value($_GET['msg']);
						} elseif($siteGuard->session->getFlashdata('errors')) {
							$status_msg = implode('<br>', $siteGuard->session->getFlashdata('errors'));
						}
					?>
						<div class="alert alert-danger">
							<i class="fa fa-times"></i> <strong>Error!</strong>&nbsp;&nbsp;<?php echo $status_msg; ?>
						</div>
						
					<?php 
						endif;
					?>
					
					<?php
					
			if($type == 'login') {
					if($two_factor_step == true) {
					?>
					<div class="card-title"><center>Two Factor Authentication</center></div>
					<div class="form-group">
						<label class="form-label">OTP code</label>
						<input type="text" class="form-control" name="otp" placeholder="PIN Code.." required autocomplete='off' ><hr>
						<center><a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en' target='_blank' class='text-dark'><img src='<?php echo $assets_location; ?>images/google-authenticator-logo.png' class='img-g-auth'> Google Authenticator</a></center>
				  </div>
					<div class="hide-this">
					<?php } ?>
                  <div class="card-title">Login to your account</div>
					<?php if($siteGuard->settings['social_login'] == 'on') { ?><div class="form-group pl-0 pr-0"><a href="<?php echo base_url("login?provider=facebook"); ?>" class="btn btn-secondary social-btn" ><i class="fa fa-facebook mr-2"></i>Facebook</a>
					<a href="<?php echo base_url("login?provider=google"); ?>" class="btn btn-secondary social-btn" ><i class="fa fa-google mr-2"></i>Google</a></div><?php } ?>
                  <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo esc($username); ?>" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo esc($password); ?>" required>
                  </div>
                  <div class="form-group">
                    <label class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" name="remember-me" value="1" 	<?php if($remember == true) { echo 'checked'; } ?>/>
						<span class="custom-control-label">Remember me</span>
						<a href="<?php echo base_url("login?type=forgot-password"); ?>" class="float-right small">forgot password?</a>
                    </label>
					</div><?php if($two_factor_step == true) {  ?></div><?php } ?>
                  <div class="form-footer">
                    <button type="submit" name= "enterlogin" class="btn btn-primary btn-block">Sign in</button>
                  </div>
				  
			<?php } elseif($type == 'activation-link') { ?>
			  <div class="card-title">Resend activation link<a href='<?php echo base_url("login"); ?>' class='btn btn-sm btn-secondary ml-2'><i class='fa fa-chevron-left'></i> back</a></div>
			  <div class="form-group">
				<label class="form-label">Email</label>
				<input type="email" class="form-control" name="activation-email" placeholder="Your email address.." value="" required>
			  </div>
				<div class="form-footer">
                    <button type="submit" name= "activation_link" class="btn btn-primary btn-block">Resend Email</button>
				</div>
			
			<?php } elseif($type == 'forgot-password') { ?>
			  <div class="card-title">Forget your password<a href='<?php echo base_url("login"); ?>' class='btn btn-sm btn-secondary ml-2'><i class='fa fa-chevron-left'></i> back</a></div>
			  <div class="form-group">
				<label class="form-label">Email</label>
				<input type="email" class="form-control" name="forgot-email" placeholder="Your email address.." value="" required>
			  </div>
				<div class="form-footer">
                    <button type="submit" name= "forgot_pass" class="btn btn-primary btn-block">Reset Password</button>
				</div>
			
			<?php } elseif($type == 'register') { ?>
			 <div class="card-title">Register new account<a href='<?php echo base_url('login'); ?>' class='btn btn-sm btn-secondary ml-2'><i class='fa fa-chevron-left'></i> back</a></div>
			  <div class="form-group">
				<label class="form-label">Name</label>
				<input type="text" class="form-control" name="name" placeholder="Your name.." value="" required>	
			  </div>
				
				<div class="form-group">
					<label class="form-label">Email</label>
					<input type="email" class="form-control" name="reg-email" placeholder="Your email address.." value="" required>	
				</div>
				
				<div class="form-group">
					<label class="form-label">Username</label>
					<input type="text" class="form-control" name="reg-username" placeholder="Your username.. (min. 4 characters)" value="" required>	
				</div>
				
				<div class="form-group">
					<label class="form-label">Password</label>
					<input type="password" class="form-control" name="reg-password" placeholder="Your password.. (min. 6 characters)" value="" required>	
				</div>
					<label class="custom-control custom-checkbox ">
						<input type="checkbox" class="custom-control-input" name="terms" value="1" required>
						<span class="custom-control-label">I accept <a href="#me" data-toggle="modal" data-target="#tosModal" class="btn-link text-decoration-none mt-1" style="font-size:16px;">terms & conditions</a></span> 
					</label>
					
					
					<!-- Modal -->
					<div class="modal fade" id="tosModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Terms & Conditions</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true"></span>
							</button>
						  </div>
						  <div class="modal-body">
							<?php include_once(FCPATH.'SiteGuard/includes/terms-of-service.php'); ?>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						  </div>
						</div>
					  </div>
					</div>
					
				<hr>
					<div class="g-recaptcha" data-sitekey="<?php echo $siteGuard->config['captcha']['sitekey']; ?>" style="transform:scale(0.97);transform-origin:0;-webkit-transform:scale(0.97);
					transform:scale(0.97);-webkit-transform-origin:0 0;transform-origin:0 0;"></div>
		
				<div class="form-footer">
                    <button type="submit" name= "register-account" class="btn btn-primary btn-block">Register New Account</button>
				</div>
			<script src='https://www.google.com/recaptcha/api.js'></script>
			<?php } ?>
                </div>

	    <?php echo "<input type=\"hidden\" name=\"next\" value=\"".$next."\" readonly/>"; ?>
	    <?php echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>"; ?>
	    
              </form>
              <?php if($siteGuard->settings['registration'] == 'on' && $type != 'register' ) { ?><div class="text-center text-muted">
                Don't have account yet? <a href="<?php echo base_url("login?type=register"); ?>">Sign up</a>
              </div><?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>