<?php $pages = array();
$navigation_pages = unserialize(base64_decode($navigation->value));
foreach($navigation_pages as $page) {
	if($page['type'] == 'section') {
		$sections[] = $page['name'];
		if(isset($page['children'][0]) && is_array($page['children'][0]) && !empty($page['children'][0]) ) {
			foreach($page['children'][0] as $child) {
				if($child['type'] == 'page') { $pages[] = $child['name']; }
			}
		}
	} else {
		$pages[] = $page['name'];
	}
}

$predefined = array('Add User' => 'users.create' ,
														'Update User' => 'users.update' ,
														'Delete User' => 'users.delete' ,
														'Impersonate (admins only!)' => 'users.impersonate',
														'View all usage reports' => 'usage_reports.power',
														'Clear usage reports' => 'usage_reports.delete',
														'Revoke active sessions' => 'active_sessions.revoke',
														'Update Privileges' => 'privileges.update',
														'Update Settings' => 'general_settings.update',
														'Add new access level' => 'access_levels.create',
														'Update access level' => 'access_levels.update',
														'Delete access level' => 'access_levels.delete',
														'Update Profile' => 'profile.update',
														'Update Username' => 'profile.change_username',
														'Update Profile Email' => 'profile.change_email',
														'Update Profile Password' => 'profile.change_password',
														'Add new announcement' => 'announcements.create',
														'Update announcement' => 'announcements.update',
														'Delete announcement' => 'announcements.delete',
									);
if($privileges->value) { $privileges_array= unserialize($privileges->value); } else { $privileges_array = array(); }
$privileges_array = array_merge($predefined, $privileges_array);
?>
<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
					<h3 class="card-title">Update Privileges Group (<?php echo $this_obj->name; ?>)</h3><div class="card-options">
					  <a href="<?php echo base_url("access_levels"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-chevron-left"></i> &nbsp;&nbsp;Back</a>
					</div>
					
					</div>
					<form action="<?php echo base_url('access_levels/update/'.$this_obj->id.'/'.$siteGuard->csrf); ?>" method="POST">
					<div class="card-body">
						<div class="form-group">
							<label for="inputName">Name</label>
							<input type="text" class="form-control" id="inputName" name="name" placeholder="Name..." value="<?php echo esc($this_obj->name); ?>" required>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label for="inputName">Max. simultaneous sessions per user</label>
									<div class="input-group d-flex">
										 <select class="custom-select chained-select" style="flex: 0.5">
											<option value="0">Unlimited</option>
											<option value="1" <?php if($this_obj->max_connections) { echo 'selected'; } ?>>Limited</option>
										</select>
									<div class="input-group-append" style="flex: 2">
										<input type="number" class="form-control" name="max_connections" placeholder="Number of active sessions.." value="<?php echo $this_obj->max_connections; ?>" <?php if(!$this_obj->max_connections) { echo 'disabled'; } ?> >
									</div>
									</div>
								</div>
								<div class="col-md-6">
									<label for="inputName">Index page</label>
									<div class="input-group d-flex">
										 <select class="custom-select chained-select" style="flex: 0.5">
											<option value="0">Default</option>
											<option value="1" <?php if($this_obj->default_index) { echo 'selected'; } ?> >Custom</option>
										</select>
									<div class="input-group-append" style="flex: 2">
										<input type="text" class="form-control" name="default_index" placeholder="Index page URL (http://...)" value="<?php echo urldecode($this_obj->default_index); ?>" <?php if(!$this_obj->default_index) { echo 'disabled'; } ?> >
									</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								
								<div class="card">
									<div class="card-status bg-gray-dark"></div>
									<div class="card-header">
									<h3 class="card-title">Sections</a>
									</div>
									<div class="card-body">
										<?php  foreach($sections as $k => $v) { ?><label class="custom-control custom-checkbox ">
											<input type="checkbox" class="custom-control-input" name="privileges[]" value="<?php echo $v; ?>" <?php
											if($siteGuard->group_privilege($v , $this_obj->id)) {
												echo ' checked';
											}
											?>>
											<span class="custom-control-label"><?php echo $v; ?></span>
										  </label><?php } ?>
									</div>
								</div>
								
							</div>
							<div class="col-4">
								
								<div class="card">
									<div class="card-status bg-green"></div>
									<div class="card-header">
									<h3 class="card-title">Pages</a>
									</div>
									<div class="card-body">
										<?php  foreach($pages as $k => $v) { ?><label class="custom-control custom-checkbox ">
											<input type="checkbox" class="custom-control-input" name="privileges[]" value="<?php echo $v; ?>.read"<?php
											if($siteGuard->group_privilege($v.'.read' , $this_obj->id)) {
												echo ' checked';
											}
											?>>
											<span class="custom-control-label"><?php echo $v; ?></span>
										  </label><?php } ?>
									</div>
								</div>
								
							</div>
							<div class="col-4">
								
								<div class="card">
									<div class="card-status bg-red"></div>
									<div class="card-header">
									<h3 class="card-title">Privileges</a>
									</div>
									<div class="card-body">
										<?php  foreach($privileges_array as $k => $v) { ?><label class="custom-control custom-checkbox ">
											<input type="checkbox" class="custom-control-input" name="privileges[]" value="<?php echo $v; ?>"<?php
											if($siteGuard->group_privilege($v , $this_obj->id)) {
												echo ' checked';
											}
											?>>
											<span class="custom-control-label"><?php echo $k; ?></span>
										  </label><?php } ?>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="card-footer text-right">
					  <div class="d-flex">
						<button type="submit" name="update_group" class="btn btn-primary ml-auto">Update Privileges Group</button>
					  </div>
					</div>
					<?php 
						echo "<input type=\"hidden\" name=\"edit_id\" value=\"".$this_obj->id."\" readonly/>";
						echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>";
					?>
					</form>
					<script> "use strict";
                  $(document).ready(function(){
					$(".chained-select").change(function() {
						var val = $(this).val();
						if(val == 1) {
							$(this).closest(".input-group").find('input').prop('disabled', false);
						} else {
							$(this).closest(".input-group").find('input').prop('disabled', true);
						}
					});
				  });
				  </script>
				</div>
				</div>
		</div>
	</div>
</div>