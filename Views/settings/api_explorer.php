<div class="my-3 my-md-5">	
	<div class="container">
		
		<form class="" action="<?php echo base_url("api_explorer"); ?>" method="post" autocomplete="off" > 
		
		<div class="row">
		
			<div class="col-6">
				<div class="card">
				<div class="card-status bg-red"></div>
					<div class="card-header">
					<h3 class="card-title">API Request</h3>
					</div>
					<div class="card-body">
						
					<table class="table ">
                      <thead>
                        <tr>
                          <th class="pl-0" >Model</th>
                          <th>Function</th>
                          <th >Target</th>
                          <th class="pr-0" >property</th>
                        </tr>
                      </thead>
                      <tbody><tr>
                        <td class="pl-0">
                          <select class="custom-select" id="model">
                            <option value="" selected></option>
                            <option value="Auth">Authentication</option>
                            <option value="User">User</option>
                          </select>
                        </td>
                        <td>
							<select class="custom-select" id="function">
								<option value="" selected></option>
								<optgroup label="Auth">
									<option value="login">Login</option>
									<option value="logout">Logout</option>
									<option value="register">Register</option>
									<option value="reset-password">Reset-Password</option>
								</optgroup>
								<optgroup label="User">
									<option value="get">Get</option>
									<option value="update">Update</option>
									<option value="find">Find</option>
									<option value="privilege">Privilege check</option>
									<option value="page">Page access check</option>
								</optgroup>
                          </select>
                        <td>
                          <input type="text" class="form-control" id="object_id"></td>
                        </td>
                        <td class="pr-0">
                          <input type="text" class="form-control" id="property" ></td>
                        </td>
                      </tr>
					  
					  <tr class="hide-this" id="var103">
						<td class="">POST variables:</td>
						<td><input type="text" class="form-control" id="var1" placeholder=""></td>
						<td><input type="text" class="form-control" id="var2" placeholder=""></td>
						<td><input type="text" class="form-control" id="var3" placeholder=""></td>
					  </tr>
					   <tr class="hide-this" id="var407">
						<td><input type="text" class="form-control" id="var4" placeholder=""></td>
						<td><input type="text" class="form-control" id="var5" placeholder=""></td>
						<td><input type="text" class="form-control" id="var6" placeholder=""></td>
						<td><input type="text" class="form-control" id="var7" placeholder=""></td>
					  </tr>
					  
                    </tbody></table>
						
						
						<div class="form-group public_api">
							<label for="inputName">Public API Key&nbsp;&nbsp;&nbsp;&nbsp;<small class="text-gray">[POST: api_key]</small></label>
							<input type="text" class="form-control" id="" name="public_api_key" placeholder="SiteGuard..." value="<?php echo mjencode($siteGuard->settings['api_key'], $siteGuard->settings['api_salt']); ?>" readonly>
						</div>
						<div class="form-group user_api hide-this">
							<label for="inputName">User API Key&nbsp;&nbsp;&nbsp;&nbsp;<small class="text-gray">[POST: api_key]</small></label>
							<input type="text" class="form-control" id="user_api" name="user_api_key" placeholder="User API Key..." value="" >
						</div><div class="form-group">
							<label for="inputName">JWT Token&nbsp;&nbsp;&nbsp;&nbsp;<small class="text-gray">Header: [Authorization: Bearer xxx]</small></label>
							<div class="row">
								<div class="col-10">
									<input type="text" class="form-control jwt" id="jwt" name="jwt" placeholder="JWT token..." value="" required readonly>
								</div>
								<div class="col-2">
								<a href="#me" class="generate-jwt btn btn-secondary">Generate</a>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="inputName">Method</label>							
							<input type="text" class="form-control" id="" name="api_method" placeholder="SiteGuard..." value="POST" readonly>
						</div>
						
					</div>
		<div class="card-footer text-right">
				<a href="#me" class="explore_api btn btn-primary">Submit</a>
		</div>
			
				</div>
			</div><div class="col-6">
				<div class="card" >
				<div class="card-status bg-green"></div>
					<div class="card-header ">
					<h3 class="card-title">API Response</h3>
					</div>
					<div class="card-body row" id="">
						<div class="col-12" >
							<div class="form-group">
								<label for="">Request URL</label>							
								<input type="text" class="form-control" id="api_url" name="url" placeholder="" value="" readonly>
							</div>
						</div><div class="col-6" >
							<div class="form-group">
								<label for="">Status Code</label>							
								<input type="text" class="form-control" id="api_code" name="code" placeholder="" value="" readonly>
							</div>
						</div><div class="col-6" >
						<div class="form-group">
							<label for="">Request Status</label>
							<input type="text" class="form-control" id="api_status" name="status" placeholder="" value="" readonly>
						</div>
						</div>
						<div class="col-12" class="api_response_box">
							<label for="">Response</label>
								<pre id="api_response"></pre>
						</div>
					</div>
				</div>
			</div>
			</div>
			<?php echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>";  ?>
			</form>
			
			<script> "use strict";
				$("select[id='function']").find("optgroup, option").hide();
				$("select[id='model']").on("change", function() {
					$("select[id='function']").val([]);
					$("select[id='function']").find("optgroup, option").hide().filter("[label='" + this.value + "'], [label='" + this.value + "'] > *").show();
				});
				$("select[id='function']").on("change", function() {
							
							if($(this).val() == 'login') {
								$("#var103").show();
								$("#var407").hide();
								$("#var1").attr("placeholder", "Username..");
								$("#var2").attr("placeholder", "Password..");
								$("#var3").show();
								$("#var3").attr("placeholder", "OTP..");
								$(".public_api").show();
								$(".user_api").hide();
							} else {
								$("#var3").show();
								$("#var4").show();
								$("#var6").show();
								$("#var7").show();
								$("#var103").hide();
								$("#var407").hide();
									if($(this).val() == 'register') {
										$("#var103").show();
										$("#var407").show();
										$("#var1").attr("placeholder", "Name..");
										$("#var2").attr("placeholder", "Email..");
										$("#var3").hide();
										$("#var4").attr("placeholder", "Username..");
										$("#var5").attr("placeholder", "Password..");
										$("#var6").attr("placeholder", "Confirm Password..");
										$("#var7").hide();
										$(".public_api").show();
										$(".user_api").hide();
									} else {
										$("#var103").hide();
										$("#var407").hide();
										$("#var3").show();
										$("#var4").show();
										$("#var6").show();
										$("#var7").show();
										if($(this).val() == 'update') {
											$("#var103").show();
											$("#var407").show();
											$("#var1").attr("placeholder", "Name..");
											$("#var2").attr("placeholder", "Phone..");
											$("#var3").attr("placeholder", "Address..");
											$("#var4").attr("placeholder", "Email..");
											$("#var5").attr("placeholder", "Password..");
											$("#var6").attr("placeholder", "banned? 1/0");
											$("#var7").attr("placeholder", "tfa? 1/0");
											$(".public_api").hide();
											$(".user_api").show();
										} else {
											$(".public_api").show();
											$(".user_api").hide();
											$("#var103").hide();
											$("#var407").hide();
											$("#var3").show();
											$("#var4").show();
											$("#var6").show();
											$("#var7").show();
											if($(this).val() == 'reset-password') {
												$("#var103").show();
												$("#var407").show();
												$("#var1").attr("placeholder", "Current Password..");
												$("#var2").attr("placeholder", "New Password..");
												$("#var3").attr("placeholder", "Re-type new password..");
												$("#var5").attr("placeholder", "OTP..");
												
												$("#var4").hide();
												$("#var6").hide();
												$("#var7").hide();
												
												$(".public_api").hide();
												$(".user_api").show();
											} else {
												$(".public_api").show();
												$(".user_api").hide();
												$("#var103").hide();
												$("#var407").hide();
												$("#var3").show();
												$("#var4").show();
												$("#var6").show();
												$("#var7").show();
											}
										}
								}
							}
						});
						$(".generate-jwt").on("click", function(){
							
							var req2 = 'prepare_public_jwt/';
							var ajaxData2 = { api_key: "<?php echo mjencode($siteGuard->settings['api_key'], $siteGuard->settings['api_salt']); ?>" };
							
							if($("#function").val().length !== 0 && $("#function").val() == 'update') {
								ajaxData2.api_key= $("#user_api").val();
								req2 = 'prepare_user_jwt/';
								if( $("#object_id").val().length !== 0 ) {
									req2 += $("#object_id").val() + '/';
								}	
							}
							if($("#function").val().length !== 0 && $("#function").val() == 'reset-password') {
								ajaxData2.api_key= $("#user_api").val();
							}
							$.ajax({
								type: 'POST',
								url: "<?php echo base_url(); ?>/api/"+req2,
								data: ajaxData2,
								dataType: 'json',
								success: function (data) {
									$('.jwt').val(data.response);
								}
							});
						});
						$(".explore_api").on("click", function(){
							
						var req = '';
						
							 if( $("#model").val().length !== 0 ) {
								 req += $("#model").val() + '/';
							 }
							
							if($("#function").val().length !== 0 && $("#function").val() == 'logout') {
								 if(confirm("Warning, you WILL be logged out! continue?")) {
									if( $("#function").val().length !== 0 ) {
										 req += $("#function").val() + '/';
									 }
								 }
							 } else {
								if( $("#function").val().length !== 0 ) {
									 req += $("#function").val() + '/';
								 }
							 }
							 
							if( $("#object_id").val().length !== 0 ) {
								 req += $("#object_id").val() + '/';
							 }
							if( $("#property").val().length !== 0 ) {
								 req += $("#property").val();
							 }
							
							
							var ajaxData = {
								 api_key: '<?php echo mjencode($siteGuard->settings['api_key'], $siteGuard->settings['api_salt']); ?>'
							};
							if($("#function").val().length !== 0 && $("#function").val() == 'login') {
								  ajaxData.username = $("#var1").val(),
								  ajaxData.password = $("#var2").val(),
								  ajaxData.otp = $("#var3").val()
							  }
							if($("#function").val().length !== 0 && $("#function").val() == 'register') {
								  ajaxData.name = $("#var1").val(),
								  ajaxData.email = $("#var2").val(),
								  ajaxData.username = $("#var4").val(),
								  ajaxData.password = $("#var5").val(),
								  ajaxData.confirm_password = $("#var6").val()
							  }
							if($("#function").val().length !== 0 && $("#function").val() == 'update') {
								  ajaxData.name = $("#var1").val(),
								  ajaxData.phone = $("#var2").val(),
								  ajaxData.address = $("#var3").val(),
								  ajaxData.email = $("#var4").val(),
								  ajaxData.password = $("#var5").val(),
								  ajaxData.banned = $("#var6").val(),
								  ajaxData.tfa = $("#var7").val()
							  }
							if($("#function").val().length !== 0 && $("#function").val() == 'reset-password') {
								  ajaxData.current_password = $("#var1").val(),
								  ajaxData.new_password = $("#var2").val(),
								  ajaxData.confirm_new_password = $("#var3").val(),
								  ajaxData.otp = $("#var5").val()
							  }
					
					$.ajax({
						beforeSend: function(xhr) {
							xhr.setRequestHeader('Authorization', 'Bearer ' + $(".jwt").val());
						},
						type: 'POST',
						url: "<?php echo base_url('api');  ?>/" + req,
						data: ajaxData,
						dataType: 'json',
						success: function (data) {
							$('#api_url').val(data.link);
							$('#api_code').val(data.code);
							$('#api_status').val(data.status);
							$('#api_response').text(JSON.stringify(data.response, null, 4));
						}
					});
					
					
					
					
				});
				</script>
			
			
		
	</div>
</div>