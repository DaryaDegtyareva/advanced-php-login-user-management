<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Add New Announcement</h3><div class="card-options">
							<a href="<?php echo base_url("announcements/create"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-chevron-left"></i> &nbsp;&nbsp;Back</a>
						</div>
					</div>
		
		<form action="<?php echo base_url("announcements/create"); ?>" method="POST" enctype='multipart/form-data' >
		<div class="card-body row">
			
			<div class="col-6">
				<div class="form-group">
                    <label for="inputName" class="control-label">Name</label>
                    <div class="">
						<input type="text" class="form-control" id="inputName" name="name" placeholder="Name" value="" required>
                    </div>
				</div>	
				<div class="form-group">
                    <label for="inputExpire" class="control-label">Expire after</label>
                    <div class="">
						<?php 
							$options = array("never" => "Never");
							for($i = 1 ; $i < 7 ; $i++) {
								if($i == 1) {
									$options["+{$i} day"] = "{$i} Day";
								} else {
									$options["+{$i} day"] = "{$i} Days";
								}
							}
							for($i = 1 ; $i < 4 ; $i++) {
								if($i == 1) {
									$options["+{$i} week"] = "{$i} Week";
								} else {
									$options["+{$i} week"] = "{$i} Weeks";
								}
							}
							for($i = 1 ; $i <= 12 ; $i++) {
								if($i == 1) {
									$options["+{$i} month"] = "{$i} Month";
								} else {
									$options["+{$i} month"] = "{$i} Months";
								}
							}
						?>
						<select class="form-control select2" name="expire_after" >
							<?php foreach($options as $key => $value) {
								echo "<option value='{$key}'>{$value}</option>";
							} ?>
						</select>
                    </div>
				</div>	
			</div>
			<div class="col-6">
				  <div class="form-group">
                    <label for="inputType" class="control-label">Color</label>
                    <div class="">
						<?php $options = array("primary" => "Blue" ,
													"success" => "Green" ,
													"danger" => "Red" ,
													"warning" => "Orange" ,
													"secondary" => "Grey" ,
													"light" => "White" ,
												);
						?>
						<select class="form-control select2" name="type" >
							<?php foreach($options as $key => $value) {
								echo "<option value='{$key}'>{$value}</option>";
							} ?>
						</select>
                    </div>
				</div>
				<div class="form-group">
                    <label for="inputVisibility" class="control-label">Visible to</label>
                    <div class="">
						<?php 
							$groups = $groupModel->get_everything(" deleted = 0 ");
						?>
						<select class="form-control select2" name="visible_to[]" multiple>
							<?php foreach($groups as $group) {
								echo "<option value='-{$group->id}-'>{$group->name}</option>";
							} ?>
						</select>
                    </div>
				</div>	
		  </div>
		  <div class="col-12">
			<div class="form-group">
				<label for="inputMessage" class="control-label">Message</label>
				<div class="">
					<textarea name="message" class="summernote"></textarea>
				</div>
			</div>
		  </div>
		  </div>
		<div class="card-footer text-right">
		  <div class="d-flex">
			<button type="submit" name="add_announcement" class="btn btn-primary ml-auto">Add New Announcement</button>
		  </div>
		</div>
		<?php 
		echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>"; 
		?></form>
		
		<script src="<?php echo base_url(); ?>/SiteGuard/plugins/summernote/summernote-bs4.js"></script>
		 <script> 
			"use strict";
			function generateSwal(title, type, message) {
				swal(title, message, type);
			}
			$('.select2').select2();
			$('.summernote').summernote({
				callbacks : {
	            onImageUpload: function(image) {
					sendFile(image[0]);
				}
			}
			});
			function sendFile(image) {
				var data = new FormData();
				data.append("data", 'summernote-inline-uploader');
				data.append("id", <?php echo $current_user->id; ?>);
				data.append("hash", '<?php echo $siteGuard->csrf; ?>');
				data.append("img", image);
				data.append("upl_img", true);
				$.ajax({
					data: data,
					type: "POST",
					url: "<?php echo base_url("announcements"); ?>",
					cache: false,
					contentType: false,
					processData: false,
					success: function(result) {
						if(result.type =='success') {
							$('.summernote').summernote("insertImage", result.message);
						} else {
							generateSwal(result.title,result.type,result.message);
						}
					},
					error: function(result) {
						generateSwal(result.title,result.type,result.message);
					}
				});
			}
		</script>
					</div>
				</div>
		</div>
	</div>
</div>