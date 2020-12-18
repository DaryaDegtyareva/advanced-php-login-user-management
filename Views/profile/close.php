<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="card card-profile card-profile-body">
                  <div class="card-header" style="background-image: url(<?php echo base_url(); ?>/SiteGuard/images/profile-bg.jpg);"></div>
                  <div class="card-body text-center">
					<img class="card-profile-img" src="<?php echo $userModel->get_avatar($user->id); ?>">
					<h3 class="mb-3"><?php echo $user->name; ?><?php if($userModel->check_online($user->id)) { ?>&nbsp;&nbsp;<small><i class="fa fa-circle text-success"></i></small><?php } ?><br><small class='text-muted'><?php echo "@".$user->username; ?></small></h3>
                    <p class="mb-4">
						<?php echo $user->about; ?>
                    </p>
					<a href="<?php echo base_url("usage_reports/?user_id={$user->id}"); ?>" class="btn btn-secondary" target="_blank"><i class="fe fe-list"></i> View Usage Reports</a>
                  </div>
                </div>
			</div>
			<div class="col-md-8" >
				<div class="card card-profile-body" >
					<div class="card-header"><h3 class="card-title">Close Account</h3><div class="card-options">
							<a href="<?php echo base_url("profile"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-chevron-left"></i> &nbsp;&nbsp;Back</a>
						</div></div>
			<div class="card-body text-center h3 " >
				<img src="<?php echo base_url(); ?>/SiteGuard/images/closed.png" class='col-3 pb-5'><br>
				Are you sure you want to close your account?
			</div>
			<form action="<?php echo base_url("profile/close/{$user->id}/{$siteGuard->csrf}"); ?>" method="POST" >
			<center>Please enter your password: <br><input type="password" class="form-control col-4" name="old_password" placeholder=""  value="" required>
			<br><button type="submit" name="close_profile" class="btn btn-primary ml-auto">Close Account</button></center></p>
			<?php 
			echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>";
			echo "<input type=\"hidden\" name=\"edit_id\" value=\"".$user->id."\" readonly/>"; 
		?></form>
							
					
				</div>
			
		</div>
	</div>
</div>
</div>