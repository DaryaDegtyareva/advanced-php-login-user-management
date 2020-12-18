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
				<div class="card-header"><h3 class="card-title">Edit Profile</h3><div class="card-options">
							<a href="<?php echo base_url("profile"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-chevron-left"></i> &nbsp;&nbsp;Back</a>
						</div></div>
				<form action="<?php echo base_url("profile/update/{$user->id}/{$siteGuard->csrf}"); ?>" method="POST" enctype="multipart/form-data">
                  <div class="card-body row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="inputName" class="control-label">Name</label>
								<div class="">
									<input type="text" class="form-control" id="inputName" name="name" placeholder="Name" value="<?php echo esc($user->name); ?>" required>
								</div>
							</div>
							
							  <div class="form-group">
								<label for="inputPhone" class="control-label">Phone</label>
								<div class="">
								  <input type="text" class="form-control" id="inputPhone" name="mobile" placeholder="Phone"  value="<?php echo esc($user->mobile); ?>" >
								</div>
							  </div>
							  
							  <div class="form-group">
								<label for="inputAddress" class="control-label">Address</label>
								<div class="">
								  <textarea class="form-control" id="inputAddress" name="address" placeholder="Address" ><?php echo esc($user->address); ?></textarea>
								</div>
							  </div>
							  
							  <div class="form-group">
								<label for="inputAbout" class="control-label">About Me</label>
								<div class="">
								  <textarea class="form-control" id="inputAbout" name="about" placeholder="About Me" rows="6" ><?php echo esc($user->about); ?></textarea>
								  <span id="wordcount"><?php $words = explode(" ", $user->about);  echo count($words); ?></span>/100 words
								</div>
							  </div>
							  <hr>
							  <div class="form-group">
								<label for="inputUsername" class="control-label">Username</label>
								<div class="">
								  <input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username" value="<?php echo esc($user->username); ?>" required <?php if(!$siteGuard->privilege('profile.change_username') ) { echo ' readonly'; } ?> >
								</div>
							</div>
							  
							  <div class="form-group">
								<label for="inputEmail" class="control-label">Email</label>
								<div class="">
								  <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email"  value="<?php echo esc($user->email); ?>" required <?php if(!$siteGuard->privilege('profile.change_email') ) { echo ' readonly'; } ?> >
								</div>
							  </div>
							  
							  <?php if($user->hybridauth_provider_uid == '' ) { ?>
							  
							   <div class="form-group">
								<label for="inputPassword" class="control-label">Current Password *</label>
								<div class="">
								  <input type="text" class=" form-control" name="old_password" placeholder=""  value="" <?php if(!$siteGuard->privilege('profile.change_password') ) { echo ' readonly'; } ?> required>
								</div>
							  </div>
							  
							  <hr>
							  <div class="form-group">
								<label for="inputPassword" class="control-label">New Password</label>
								<div class="">
								  <input type="text" class="password form-control" name="password" placeholder=""  value="" <?php if(!$siteGuard->privilege('profile.change_password') ) { echo ' readonly'; } ?>>
								</div>
								<div id="messages"></div>
							  </div>
							  <?php } ?>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<label for="inputProfilePic" class="control-label">Profile Picture</label>
							<div class="">
								
								<div class="col-10"><img src="<?php echo $userModel->get_avatar($user->id); ?>" id="img1"></div>
								
								<br>
								<input class="text-input" type="file" name="avatar[]" id="img1_upl"/>
								
							</div>
						  </div>
						  <?php if(isset($siteGuard->settings['2fa']) && $siteGuard->settings['2fa']  == 'on' && $user->hybridauth_provider_uid == '' ) { ?><hr>
						  <div class="form-group">
						Two Factor Authentication
						
						<label class="custom-switch" >
							<input type="checkbox" name="tfa_status" class="custom-switch-input" value="1" <?php if($user->tfa == '1') { echo 'checked'; } ?> >
							<span class="custom-switch-indicator"></span>
							<span class="custom-switch-description"><?php if($user->tfa == '1') { echo ' Enabled'; } else { echo ' Disabled'; } ?></span>
						</label><br>
						
						<a href="#me" class="show-tfa btn btn-secondary">Show Configurations</a>
						
					  </div>
						
						<div class="tfa-config card hide-this">
							<div class="card-body">
							<?php 
							$tfa = new \App\Libraries\Authenticator();
							if($user->tfa == '0') {
								$tfa_secret = $tfa->createSecret();
								if(isset($siteGuard->settings['site_name']) && $siteGuard->settings['site_name'] != '' ) { $site_name = $siteGuard->settings['site_name']; } else { $site_name = "SiteGuard"; }
								$qr_link = $tfa->GetQR("{$site_name} ({$user->username})", $tfa_secret);
							} else {
								$tfa_secret = $user->tfa_secret;
								if(isset($siteGuard->settings['site_name']) && $siteGuard->settings['site_name'] != '' ) { $site_name = $siteGuard->settings['site_name']; } else { $site_name = "SiteGuard"; }
								$qr_link = $tfa->GetQR("{$site_name} ({$user->username})", $tfa_secret);
							}
							?>
							<div class="row">
								<div class="col-7 separator-g-auth" >
								<u>Google Authenticator QRCode</u>:<br><br>
								<img src="<?php echo $qr_link; ?>" class="img-responsive">
								</div>
								<div class="col-5">
								<u>Backup Codes</u>:<br>
								<?php if($user->tfa_codes) { 
									$codes_arr = explode(',' , $user->tfa_codes);
									foreach($codes_arr as $code) {
										echo "<div class='bg-gray-lightest console p-3 mb-2' ><center>{$code}</center></div>";
									}
								}
								?>
								</div>
							</div>
							<?php
							echo "<input type=\"hidden\" name=\"tfa_secret\" value=\"{$tfa_secret}\" readonly/>";
							?>
							</div>
						  </div><?php } ?>
						</div>
                  </div>
				  <script>"use strict";
			  
				function readURL(input,targetid) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();
						reader.onload = function (e) {
							
							var _URL = window.URL || window.webkitURL;

							$("#" + targetid).attr('src', e.target.result);
							$("#" + targetid).on('load', function () {
							
							var img = new Image();
							img.src = _URL.createObjectURL(input.files[0]);
							img.onload = function () {
								
							$("#" + targetid).cropper('destroy');
							$("#" + targetid).cropper({
							 crop: function(e) {
								var croppedData = '{"x":"'+ e.x +'","y":"'+ e.y +'","width":"' + e.width + '","height":"' + e.height + '" }';
								$('#cropped').val(croppedData);
							  }
							});
							
							};
							
							});
						}
						reader.readAsDataURL(input.files[0]);
					}
					}

					$("#img1_upl").change(function(){
						readURL(this, 'img1');
					});
					var options = {
						onKeyUp: function (evt) {
							$(evt.target).pwstrength("outputErrorList");
						}
					};
					$('.password').pwstrength(options);
					$(".show-tfa").on("click", function(){
						$(".tfa-config").slideToggle();
					});
					
					
					$("#inputAbout").keyup(function() {
						var $this = $(this);
						var wordcount = $this.val().split(/\b[\s,\.-:;]*/).length;
						if (wordcount > 100) {
							$('#wordcount').html(100);
							charcount= $this.val().length;
							$this.attr("maxlength", charcount);
							$this.addClass('is-invalid');
							shortText = $.trim($this.val()).substring(0, charcount);
							$(this).val(shortText);
						} else {
							$('#wordcount').html(wordcount);
							$this.attr("maxlength", "");
							$this.removeClass('is-invalid');
						} 
					});

				</script>
		<div class="card-footer text-right">
				<div class="d-flex">
					<button type="submit" name="update_profile" class="btn btn-primary ml-auto">Update User</button>
				</div>
		</div>
		<?php 
		echo "<input type=\"hidden\" name=\"edit_id\" value=\"".$user->id."\" readonly/>"; 
		echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>"; 
		echo "<input type=\"hidden\" name=\"cropped\" id=\"cropped\" value=\"\" readonly/>";
		?></form>
                </div>
</div>
	</div>
</div>
</div>				