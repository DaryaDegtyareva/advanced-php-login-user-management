<?php 
$assets_location = base_url().'/SiteGuard/';
?>
<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12">
			<div class="card">
					<div class="card-header">
					<h3 class="card-title">Add New User</h3><div class="card-options">
						<a href="<?php echo base_url("users"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-chevron-left"></i> &nbsp;&nbsp;Back</a>
                    </div>
					</div>
					
					
	<form action="<?php echo base_url('users/create/'); ?>" method="POST" enctype='multipart/form-data' >
		<div class="card-body row">
			
			<div class="col-6">
				<div class="form-group">
                    <label for="inputName" class="control-label">Name</label>
                    <div class="">
						<input type="text" class="form-control" id="inputName" name="name" placeholder="Name" value="" required>
                    </div>
				</div>
				
				  <div class="form-group">
                    <label for="inputPhone" class="control-label">Phone</label>
                    <div class="">
                      <input type="text" class="form-control" id="inputPhone" name="mobile" placeholder="Phone"  value="" >
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label for="inputAddress" class="control-label">Address</label>
                    <div class="">
                      <textarea class="form-control" id="inputAddress" name="address" placeholder="Address" ></textarea>
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label for="inputAbout" class="control-label">About User</label>
                    <div class="">
                      <textarea class="form-control" id="inputAbout" name="about" placeholder="About User" rows="4" ></textarea>
					  <span id="wordcount">0</span>/100 words
                    </div>
                  </div>
				  
				  
				  <hr>
				  
				  
				  <div class="form-group">
                    <label for="inputProfilePic" class="control-label">Profile Picture</label>
                    <div class="">
						
						<div class="col-8" ><img src="<?php echo $assets_location; ?>images/avatar.jpg" id="img1"></div>
						
						<br>
						<input class="text-input" type="file" name="avatar[]" id="img1_upl"/>
                    </div>
                  </div>
			</div>
			<div class="col-6">
				  
				  <div class="form-group">
                    <label for="inputUsername" class="control-label">Privileges</label>
                    <div class="">
                      <select name="prvlg_group" class="selectpicker form-control">
							<?php $prvlg = $groupModel->get_everything(" deleted = 0 " ); ?>
							<?php if($prvlg) {
								$prvlg= array_reverse($prvlg);
								foreach($prvlg as $g) {
									echo "<option value='{$g->id}'>{$g->name}</option>";
							}} ?>
						</select>
                    </div>
                  </div>
                  <br>
				  
				  <div class="form-group">
                    <label for="inputUsername" class="control-label">Username</label>
                    <div class="">
                      <input type="text" class="form-control" id="inputUsername" placeholder="Username (4 - 16 characters)" name="username" value="" required>
                    </div>
                  </div>
                  
				  <div class="form-group">
                    <label for="inputEmail" class="control-label">Email</label>
                    <div class="">
                      <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email"  value="" required>
                    </div>
                  </div>
                  
				  <div class="form-group">
                    <label for="inputPassword" class="control-label">Password</label>
					<div class="row">
						<div class="col-10">
						  <input type="text" class="password form-control" name="password" placeholder="Password (min. 6 characters)"  value="" required>
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
						  <input type="text" class="text form-control api_key" name="api_key" placeholder="Key.."  value="" >
						</div>
						<div class="col-2">
							<a href="#me" class="generate-api btn btn-secondary">Generate</a>
						</div>
					</div>
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
						$("#inputAbout").keyup(function() {
							var $this = $(this);
							var wordcount = $this.val().split(/\b[\s,\.-:;]*/).length;
							if (wordcount > 100) {
								$('#wordcount').html(100);
								var charcount= $this.val().length;
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
			<button type="submit" name="add_user" class="btn btn-primary ml-auto">Add New User</button>
		  </div>
		</div>
		<?php 
		echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>"; 
		echo "<input type=\"hidden\" name=\"cropped\" id=\"cropped\" value=\"\" readonly/>";
		?></form>
					
					
					
				</div>
			</div>
		</div>
	</div>
</div>