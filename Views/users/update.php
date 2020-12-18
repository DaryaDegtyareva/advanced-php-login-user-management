<?php $assets_location = base_url().'/SiteGuard/'; ?>
<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12">
			<div class="card">
					<div class="card-header">
					<h3 class="card-title">Update User: <?php echo $this_obj->name; ?></h3><div class="card-options">
						<a href="<?php echo base_url("users"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-chevron-left"></i> &nbsp;&nbsp;Back</a>
                    </div>
					</div>
					
					
	<form action="<?php echo base_url('users/update/'.$this_obj->id.'/'.$siteGuard->csrf); ?>" method="POST" enctype='multipart/form-data' >
		<div class="card-body row">
			
			<div class="col-6">
				<div class="form-group">
                    <label for="inputName" class="control-label">Name</label>
                    <div class="">
						<input type="text" class="form-control" id="inputName" name="name" placeholder="Name" value="<?php echo esc($this_obj->name); ?>" required>
                    </div>
				</div>
				
				  <div class="form-group">
                    <label for="inputPhone" class="control-label">Phone</label>
                    <div class="">
                      <input type="text" class="form-control" id="inputPhone" name="mobile" placeholder="Phone"  value="<?php echo esc($this_obj->mobile); ?>" >
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label for="inputAddress" class="control-label">Address</label>
                    <div class="">
                      <textarea class="form-control" id="inputAddress" name="address" placeholder="Address" ><?php echo esc($this_obj->address); ?></textarea>
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label for="inputAbout" class="control-label">About User</label>
                    <div class="">
                      <textarea class="form-control" id="inputAbout" name="about" placeholder="About User" rows="4"><?php echo esc($this_obj->about); ?></textarea>
					  <span id="wordcount"><?php $words = explode(" ", $this_obj->about);  echo count($words); ?></span>/100 words
                    </div>
                  </div>
				  
				  
				  <hr>
				  
				  
				  <div class="form-group">
                    <label for="inputProfilePic" class="control-label">Profile Picture</label>
                    <div class="">
						
						<div class="col-8" ><img src="<?php echo $userModel->get_avatar($this_obj->id); ?>" id="img1"></div>
						
						<br>
						<input class="text-input" type="file" name="avatar[]" id="img1_upl"/><br/>
						
                    </div>
                  </div>
			</div>
			<div class="col-6">
				  
				  <div class="form-group">
                    <label for="inputUsername" class="control-label">Privileges</label>
                    <div class="">
                      <select name="prvlg_group" class="selectpicker form-control">
							<?php $prvlg = $groupModel->get_everything(" deleted = 0 "); ?>
							<?php if($prvlg) {
								$prvlg= array_reverse($prvlg);
								foreach($prvlg as $g) {
									echo "<option value='{$g->id}' ";
										if($this_obj->prvlg_group == $g->id) { echo ' selected'; }
									echo ">{$g->name}</option>";
							}} ?>
						</select>
                    </div>
                  </div>
                  <br>
				  
				  <div class="form-group">
                    <label for="inputUsername" class="control-label">Username</label>
                    <div class="">
                      <input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username" value="<?php echo esc($this_obj->username); ?>" required <?php if($current_user->prvlg_group != '1' || !$siteGuard->privilege('profile.change_username') ) { echo ' readonly'; } ?> >
                    </div>
                  </div>
                  
				  <div class="form-group">
                    <label for="inputEmail" class="control-label">Email</label>
                    <div class="">
                      <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email"  value="<?php echo esc($this_obj->email); ?>" required <?php if($current_user->prvlg_group != '1' || !$siteGuard->privilege('profile.change_email') ) { echo ' readonly'; } ?> >
                    </div>
                  </div>
                  
				  <div class="form-group">
                    <label for="inputPassword" class="control-label">Password</label>
					<div class="row">
						<div class="col-10">
						  <input type="text" class="password form-control" name="password" placeholder="Unchanged"  value="" <?php if($current_user->prvlg_group != '1' || !$siteGuard->privilege('profile.change_password') ) { echo ' readonly'; } ?>>
						</div>
						<div class="col-2">
							<a href="#me" class="generate-password btn btn-secondary">Generate</a>
						</div>
					</div>
					
					<div id="messages"></div>
                  </div>
				  
				  <?php if(isset($siteGuard->settings['api']) && $siteGuard->settings['api'] == 'on' ) {?>
					<hr>
					<div class="form-group">
                    <label for="inputApi" class="control-label">User API Key</label>
                    <div class="">
                      <div class="row">
						<div class="col-10">
						  <input type="text" class="text form-control api_key" name="api_key" placeholder="Key.."  value="<?php if($this_obj->api_key) { echo $this_obj->api_key; } ?>" >
						</div>
						<div class="col-2">
							<a href="#me" class="generate-api btn btn-secondary">Generate</a>
						</div>
					</div>
                    </div>
                  </div>
					<?php } ?>
				  
				<hr>
					<div class="form-group">
						<label class="custom-switch pl-0" >
							<input type="checkbox" name="disabled" class="custom-switch-input custom-switch-input-red" value="1" <?php if($this_obj->disabled == '1') { echo 'checked'; } ?> >
							<span class="custom-switch-indicator custom-switch-indicator-red"></span>
							<span class="custom-switch-description">Ban User</span>
						</label>
						
					  </div>
						<div class="form-group">
						<label class="custom-switch pl-0" >
							<input type="checkbox" name="closed" class="custom-switch-input custom-switch-input-red" value="1" <?php if($this_obj->closed == '1') { echo 'checked'; } ?> >
							<span class="custom-switch-indicator custom-switch-indicator-red"></span>
							<span class="custom-switch-description">Close Account</span>
						</label>
						
					  </div>
						
					<?php 
					if(isset($siteGuard->settings['2fa']) && $siteGuard->settings['2fa']  == 'on' ) {
					?>
					<div class="form-group">
						Two Factor Authentication
						
						<label class="custom-switch" >
							<input type="checkbox" name="tfa_status" class="custom-switch-input" value="1" <?php if($this_obj->tfa == '1') { echo 'checked'; } ?> >
							<span class="custom-switch-indicator"></span>
							<span class="custom-switch-description"><?php if($this_obj->tfa == '1') { echo ' Enabled'; } else { echo ' Disabled'; } ?></span>
						</label>
						<a href="#me" class="show-tfa btn btn-secondary pull-right">Show Configurations</a>
						
					  </div>
						
						<div class="tfa-config card hide-this" >
							<div class="card-body">
							<?php 
							$tfa = new \App\Libraries\Authenticator();
							if($this_obj->tfa == '0') {
								$tfa_secret = $tfa->createSecret();
								if(isset($siteGuard->settings['site_name']) && $siteGuard->settings['site_name'] != '' ) { $site_name = $siteGuard->settings['site_name']; } else { $site_name = "SiteGuard"; }
								$qr_link = $tfa->GetQR("{$site_name} ({$this_obj->username})", $tfa_secret);
							} else {
								if(isset($siteGuard->settings['site_name']) && $siteGuard->settings['site_name'] != '' ) { $site_name = $siteGuard->settings['site_name']; } else { $site_name = "SiteGuard"; }
								
								$tfa_secret = $this_obj->tfa_secret;
								$qr_link = $tfa->GetQR("{$site_name} ({$this_obj->username})", $tfa_secret);
							}
							?>
							<div class="row">
								<div class="col-6 separator-g-auth" >
								<u>Google Authenticator QRCode</u>:<br><br>
								<img src="<?php echo $qr_link; ?>" class="img-responsive">
								</div>
								<div class="col-1 " ></div>
								<div class="col-5">
								<u>Backup Codes</u>:<br>
								<?php if($this_obj->tfa_codes) { 
									$codes_arr = explode(',' , $this_obj->tfa_codes);
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
						</div>
						<?php } ?>
						
				  <script> "use strict";
				  
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
						$(".generate-password").on("click", function(){
							$('.password').val(password_generator());
							$(".password").pwstrength("forceUpdate");
						});
						$(".generate-api").on("click", function(){
							$('.api_key').val(password_generator());	
						});
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
							} else {
								$('#wordcount').html(wordcount);
								$this.attr("maxlength", "");
								$this.removeClass('is-invalid');
							} 
						});
						
				  
				</script>
		  </div>
		  </div>
		<div class="card-footer text-right">
		  <div class="d-flex">
			<button type="submit" name="update_user" class="btn btn-primary ml-auto">Update User</button>
		  </div>
		</div>
		<?php 
		echo "<input type=\"hidden\" name=\"edit_id\" value=\"".$this_obj->id."\" readonly/>"; 
		echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>"; 
		echo "<input type=\"hidden\" name=\"cropped\" id=\"cropped\" value=\"\" readonly/>";
		?></form>
					
					
					
				</div>
			</div>
		</div>
	</div>
</div>