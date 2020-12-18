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
				<div class="card-header"><h3 class="card-title">View Profile</h3>
			<div class="card-options">
			<?php if($user->id == $current_user->id && $siteGuard->privilege('profile.update') ) { ?>
					<a href="<?php echo base_url("profile/update/{$user->id}/{$siteGuard->csrf}"); ?>" class="btn btn-primary ml-2"><i class="fa fa-pencil"></i> &nbsp;&nbsp;Edit Profile</a>
					<?php if($user->hybridauth_provider_uid == '') { ?><a href="<?php echo base_url("profile/close/{$user->id}/{$siteGuard->csrf}"); ?>" class="btn btn-danger ml-2"><i class="fa fa-times"></i> &nbsp;&nbsp;Close Account</a><?php } ?>
			<?php } if($siteGuard->privilege("users.impersonate") && !$siteGuard->get_impersonate() && $user->id != $current_user->id ) { ?>
				<a href="<?php echo base_url("users/impersonate/{$user->id}/{$siteGuard->csrf}"); ?>" class="btn btn-secondary ml-2" onclick="return confirm('<?php echo siteGuard_msg('impersonate-alert'); ?>');" ><i class="fe fe-alert-triangle" ></i> &nbsp;&nbsp;Impersonate</a>
			<?php } ?></div>
			</div>
			<div class="card-body row">
				<div class="col-md-6">
					<table class='table table-hover profile-table' > 
						<tbody>
							<tr>
								<td class= 'profile-item'><b><i class="fe fe-user"></i> Name:</b></td>
								<td class= 'profile-value'><?php echo $user->name; ?></td>
							</tr><?php if($user->id == $current_user->id || $current_user->prvlg_group == '1') { ?><tr>
								<td class= 'profile-item'><b><i class="fe fe-phone"></i> Phone:</b></td>
								<td class= 'profile-value'><?php echo $user->mobile; ?></td>
							</tr><?php } ?><tr>
								<td class= 'profile-item'><b><i class="fe fe-home"></i> Address:</b></td>
								<td class= 'profile-value'><?php echo $user->address; ?></td>
							</tr><tr>
								<td class= 'profile-item'><b><i class="fe fe-alert-circle"></i> About:</b></td>
								<td class= 'profile-value'><?php echo $user->about; ?></td>
							</tr><tr>
								<td class= 'profile-item'><b><i class="fe fe-power"></i> Registered at:</b></td>
								<td class= 'profile-value'><?php echo date_descriptive($user->registered); ?></td>
							</tr>
						</tbody>
					</table> 
				</div>
				
				<div class="col-md-6">
							<table class='table table-hover profile-table' > 
								<tbody><tr>
								<td class= 'profile-item'><b><i class="fa fa-envelope-o"></i> Email:</b></td>
								<td class= 'profile-value'><?php echo $user->email; ?></td>
							</tr><tr>
								<td class= 'profile-item'><b><i class="fe fe-lock"></i> Username:</b></td>
								<td class= 'profile-value'><?php echo $user->username; ?></td>
							</tr><?php if($user->id == $current_user->id || $current_user->prvlg_group == '1') {
							if(isset($siteGuard->settings['2fa']) && $siteGuard->settings['2fa']  == 'on' ) { ?>
							<tr>
								<td class= 'profile-item'><b><i class="fe fe-shield"></i> Two factor authentication:</b></td>
								<td class= 'profile-value'><?php if($user->tfa) { echo '<i class="fa fa-toggle-on"></i> Enabled'; } else { echo '<i class="fa fa-toggle-off"></i> Disabled'; } ?></td>
							</tr><?php }if(isset($siteGuard->settings['api']) && $siteGuard->settings['api']  == 'on' && $user->api_key ) { ?>
							<tr>
								<td class= 'profile-item'><b><i class="fe fe-code"></i> User API Key:</b></td>
								<td class= 'profile-value'>
								<a href="#me" class="btn btn-secondary btn-sm toggle_api_key"><i class='fe fe-search'></i> Show Key</a>
								<div class="hide-this api_key"><?php if($user->api_key) { echo mjencode($user->api_key, $siteGuard->settings['api_salt']); } ?></div>
								</td>
							</tr><?php }} ?></tbody>
							</table>
				</div>
			</div>
		<script>"use strict";
			$(".toggle_api_key").on("click", function(){
				$(this).hide(); $('.api_key').slideToggle();
			});
	  </script>	
		
			</div>
			
		</div>
	</div>
</div>
</div>