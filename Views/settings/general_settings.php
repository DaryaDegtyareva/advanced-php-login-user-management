<div class="my-3 my-md-5">	
	<div class="container">
		
		<form class="" action="<?php echo base_url("general_settings"); ?>" method="post" autocomplete="off" enctype="multipart/form-data" > 
		
		<div class="row">
		
			<div class="col-6">
				<div class="card">
				<div class="card-status bg-blue"></div>
					<div class="card-header">
					<h3 class="card-title">General Settings</h3>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="inputName">Site Name</label>							
							<input type="text" class="form-control" id="inputName" name="site_name" placeholder="SiteGuard..." value="<?php echo $siteGuard->settings['site_name']; ?>" required>
						</div>
						
						<div class="form-group">
							<label for="inputURL">Site public index URL <i class="text-gray">(for redirections outside control panel)</i></label>
							<input type="text" class="form-control" id="inputURL" name="public_index" placeholder="public index path..." value="<?php echo urldecode($siteGuard->settings['public_index']); ?>">
						</div>
						<br>
						<div class="form-group">
							<label for="" class="mr-6">Social Logins</label>
							<label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="social_login" value="on" <?php if($siteGuard->settings['social_login'] == "on" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">On</div>
                          </label>
						  <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="social_login" value="off" <?php if($siteGuard->settings['social_login'] == "off" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">Off</div>
                          </label>
						</div>
						
						<div class="form-group ">
							<label for="" class="mr-6">Two factor<br>Authentication</label>
							<label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="2fa" value="on" <?php if($siteGuard->settings['2fa'] == "on" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">On</div>
                          </label>
						  <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="2fa" value="off" <?php if($siteGuard->settings['2fa'] == "off" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">Off</div>
                          </label>
						</div>
						
						<div class="form-group ">
							<label for="" class="mr-6">Disable accounts<br>after failed attempts</label>
							<label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="disable_after" value="on" <?php if($siteGuard->settings['disable_after'] == "on" ) { echo " checked"; } ?> >
                            <div class="custom-control-label">On</div>
                          </label>
						  <label class="custom-control custom-radio custom-control-inline" class="mr-6">
                            <input type="radio" class="custom-control-input" name="disable_after" value="off" <?php if($siteGuard->settings['disable_after'] == "off" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">Off</div>
                          </label>
						  <input type="text" class="col-1" name="attempts" placeholder="" value="<?php echo $siteGuard->settings['attempts']; ?>" >&nbsp; attempts
						</div>
						
					</div>
				</div>
				
				<div class="card">
				<div class="card-status bg-green"></div>
					<div class="card-header">
					<h3 class="card-title">Look & Feel</h3>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="">App Logo <small class='text-muted'>(240x64 pixel)</small></label>
								<?php 
								if(isset($siteGuard->settings['logo']) && is_numeric($siteGuard->settings['logo']) ) {
									$fileModel = new \App\Models\SiteGuardFile();
									$img = $fileModel->image_path($siteGuard->settings['logo']);
									$logo_url = base_url().'/'.$img;
									$logo_url_path = FCPATH.'/'.$img;
									if (!file_exists($logo_url_path)) {
										$logo_url = base_url().'/SiteGuard/images/logo.png';
									}
								} else {
									$logo_url = base_url().'/SiteGuard/images/logo.png';
								}
								?>
								<div class="col-6">
								<img src="<?php echo $logo_url; ?>" id="img1" style="">
								</div><br>
								<?php if(isset($siteGuard->settings['logo']) && is_numeric($siteGuard->settings['logo']) ) { ?>
									<a href="#me" class="btn btn-sm btn-danger mr-3 reset-logo">Reset Logo</a>
								<?php } ?>
								<input class="text-input" type="file" name="logo[]" id="img1_upl"/>
								<br>
						</div>
						
						<div class="form-group">
							<label for="footer">Footer</label>
							<input type="text" class="form-control" id="footer" name="footer" placeholder="" value="<?php if(isset($siteGuard->settings['footer'])) { echo $siteGuard->settings['footer']; } ?>">
						</div>
						<br>
					</div>
				</div>
				<p class="ml-2 text-muted"> Your script Version: <?php echo $siteGuard->version; ?></p>
			</div><div class="col-6">
				<div class="card">
				<div class="card-status bg-green"></div>
					<div class="card-header ">
					<h3 class="card-title">Registration Settings</h3>
					</div>
					<div class="card-body">
						<div class="form-group ">
							<label for="inputName" class="mr-6">Visitor's Registration</label>
							<label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="registration" value="on" <?php if($siteGuard->settings['registration'] == "on" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">On</div>
                          </label>
						  <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="registration" value="off" <?php if($siteGuard->settings['registration'] == "off" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">Off</div>
                          </label>
						</div>
						<div class="form-group">
							<label for="inputGroup2">Account activation</label>
							<select class="form-control" id="inputGroup2" name="registration_activate">
							  <option value="admin_approval" <?php if(isset($siteGuard->settings['registration_activate']) && $siteGuard->settings['registration_activate'] == 'admin_approval' ) { echo "selected"; } ?> >After admin approval</option>
							  <option value="self_activation" <?php if(isset($siteGuard->settings['registration_activate']) && $siteGuard->settings['registration_activate'] == 'self_activation' ) { echo "selected"; } ?> >Mail self activation</option>
							</select>
						</div>
						<div class="form-group">
							<label for="inputGroup">Entry Group</label>
							<select class="form-control" id="inputGroup" name="registration_group">
							  <?php $groups = $groupModel->get_everything(" id != 1 AND deleted = 0 "); foreach($groups as $grp) { echo "<option value='{$grp->id}' ";
							  if($siteGuard->settings['registration_group'] == $grp->id ) { echo " selected"; }
							  echo " >{$grp->name}</option>"; } ?>
							</select>
						</div>
						
						
					</div>
				</div><div class="card">
				<div class="card-status bg-red"></div>
					<div class="card-header">
					<h3 class="card-title">API Settings</h3>
					</div>
					<div class="card-body">
						<div class="form-group ">
							<label for="inputName" class="mr-6">API Calls</label>
							<label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="api" value="on"  <?php if($siteGuard->settings['api'] == "on" ) { echo " checked"; } ?> >
                            <div class="custom-control-label">On</div>
                          </label>
						  <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="api" value="off" <?php if($siteGuard->settings['api'] == "off" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">Off</div>
                          </label>
						</div>
						<div class="form-group">
							<label for="inputName">Secret API Key&nbsp;&nbsp; <?php if($siteGuard->privilege("general_settings.update")) { ?>
							<a href="#me" class="generate-password btn btn-secondary">Generate</a>
						<?php } ?></label>
						<div class="row">
							<div class="col-8">
								<input type="<?php if($siteGuard->privilege("general_settings.update")) { echo 'text'; } else { echo 'password'; } ?>" class="api_key form-control" name="api_key" placeholder=""  value="<?php echo $siteGuard->settings['api_key']; ?>" <?php if(!$siteGuard->privilege("general_settings.update")) { echo 'readonly'; } ?> >
							</div>
							<div class="col-4">
								<input type="<?php if($siteGuard->privilege("general_settings.update")) { echo 'text'; } else { echo 'password'; } ?>" class="api_salt form-control" name="api_salt" placeholder=""  value="<?php echo $siteGuard->settings['api_salt']; ?>" <?php if(!$siteGuard->privilege("general_settings.update")) { echo 'readonly'; } ?> >
							</div>
						</div>
						</div>
						<?php if($siteGuard->settings['api'] == "on" ) { ?>
						<div class="form-group">
							<label for="inputName">Public API Key</label>
							<pre><?php echo mjencode($siteGuard->settings['api_key'], $siteGuard->settings['api_salt']); ?></pre>
						</div><?php } ?>
					</div>
				</div>
				
				
				<div class="card">
				<div class="card-status bg-orange"></div>
					<div class="card-header">
					<h3 class="card-title">SMTP Settings</h3>
					</div>
					<div class="card-body">
						<div class="form-group ">
							<label for="inputName" class="mr-6">Use SMTP</label>
							<label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="smtp" value="on"  <?php if(isset($siteGuard->settings['smtp']) && $siteGuard->settings['smtp'] == "on" ) { echo " checked"; } ?> >
                            <div class="custom-control-label">On</div>
                          </label>
						  <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="smtp" value="off" <?php if(!isset($siteGuard->settings['smtp']) || $siteGuard->settings['smtp'] == "off" ) { echo " checked"; } ?>>
                            <div class="custom-control-label">Off</div>
                          </label>
						</div>
						
						<div class="form-group ">
							<div class="row">
								<div class="col-4">
									<input type="text" class="form-control" name="smtphost" placeholder="SMTP Host..." value="<?php if(isset($siteGuard->settings['smtphost'])) { echo urldecode($siteGuard->settings['smtphost']); } ?>" >
								</div>
								<div class="col-4">
									<input type="text" class="form-control" name="smtpport" placeholder="SMTP Port..." value="<?php if(isset($siteGuard->settings['smtpport'])) { echo $siteGuard->settings['smtpport']; } ?>" >
								</div>
								<div class="col-4">
									<select class="form-control" id="inputGroup2" placeholder="Security.." name="smtpsecure">
									  <option value="tls" <?php if(isset($siteGuard->settings['smtpsecure']) && $siteGuard->settings['smtpsecure'] == 'tls' ) { echo "selected"; } ?> >TLS</option>
									  <option value="ssl" <?php if(isset($siteGuard->settings['smtpsecure']) && $siteGuard->settings['smtpsecure'] == 'ssl' ) { echo "selected"; } ?> >SSL</option>
									</select>
								</div>
							</div>
							<div class="row pt-2">
								<div class="col-6">
									<input type="text" class="form-control" name="smtpusername" placeholder="SMTP Username..." value="<?php if(isset($siteGuard->settings['smtpusername'])) { echo $siteGuard->settings['smtpusername']; } ?>" >
								</div>
								<div class="col-6">
									<input type="password" class="form-control" name="smtppassword" placeholder="SMTP Password..." value="<?php if(isset($siteGuard->settings['smtppassword'])) { echo $siteGuard->settings['smtppassword']; } ?>" >
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
			</div>
			</div>
		
		<div class="card-footer text-right">
			<div class="d-flex">
				<button type="submit" name="update_settings" class="btn btn-primary ml-auto">Update Settings</button>
			</div>
		</div>
			
			
		<?php echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>";  
		echo "<input type=\"hidden\" name=\"cropped\" id=\"cropped\" value=\"\" readonly/>";
		echo "<input type=\"hidden\" name=\"reset-logo\" id=\"reset-logo\" value=\"\" readonly/>";
		?>
			</form>
			
			<script> "use strict";
		  
				$(".generate-password").on("click", function(){
					$('.api_key').val(password_generator(15));
					$('.api_salt').val(password_generator(6));
				});
				
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
				
				$(".reset-logo").on("click", function(){
					if(confirm('Are you sure you want to delete current logo?')) {
						$('#img1').attr('src', '<?php echo base_url(); ?>/SiteGuard/images/logo.png');
						$('#reset-logo').val('1');
						$(this).hide();
					}
				});
		  
				</script>
			
			
		
	</div>
</div>