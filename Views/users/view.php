<?php
if (isset($_GET['per_page']) && is_numeric($_GET['per_page']) ) {
	$per_page= $_GET['per_page'];
} else {
	$per_page= 20;
}
?>
<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12">
			<div class="card">
					<div class="card-header">
					<h3 class="card-title">Manage Users</h3><div class="card-options">
						<a href="#me" class="show_filter btn btn-secondary ml-2"><i class="fa fa-filter"></i> &nbsp;&nbsp;Filter Users</a>
						<a href="<?php echo base_url("users/create/"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-plus"></i> &nbsp;&nbsp;Add New User</a>
                    </div>
					
					</div>
					<div class="card-body">
					
					<div class="filter hide-this" >
						<form class="" action="<?php echo base_url("users"); ?>" method="GET" autocomplete="off" > 
							<select class="select2 select2-filter" id="" name="group">
								<option value="" selected disabled>Access Level..</option>
								<?php if(isset($_GET['group']) && $_GET['group'] != '') { ?><option value="">All users</option><?php }
							  
							  $groups = $groupModel->get_everything(" deleted = 0 ");
							  foreach($groups as $grp) {
								  echo "<option value='{$grp->id}' ";
											if(isset($_GET['group']) && $_GET['group'] == $grp->id ) { echo " selected"; }
										echo " >{$grp->name}</option>"; 
								}
							  ?>
							</select><select class="select2 select2-filter" id="" name="status" >
								<option value="" selected disabled>Account Status..</option>
								<?php if(isset($_GET['status']) && $_GET['status'] != '') { ?><option value="">All users</option><?php } 
							  $statuses = array("active" => "Active Users", "banned" => "Banned Users" , "pending" => "Pending Approval", "closed" => "Closed accounts");
							  foreach($statuses as $status => $display) {
									echo "<option value='{$status}' ";
											if(isset($_GET['status']) && $_GET['status'] == $status) { echo " selected"; }
										echo " >{$display}</option>"; 
							  } ?>
							</select>
							
							<input type="text" class="form-control datepicker input-filter" name="from_date" placeholder="Registered From.." value="<?php if(isset($_GET['from_date']) && $_GET['from_date'] != "") { echo escape_value($_GET['from_date']); } ?>" >
							<input type="text" class="form-control datepicker input-filter" name="to_date" placeholder="Registered To.." value="<?php if(isset($_GET['to_date']) && $_GET['to_date'] != "") { echo escape_value($_GET['to_date']); } ?>" >
							
							<input type="text" class="form-control input-filter" name="name" placeholder="Name.." value="<?php if(isset($_GET['name']) && $_GET['name'] != "") { echo escape_value($_GET['name']); } ?>" >
							<button type="submit" class="btn btn-secondary submit-filter" ><i class="fa fa-search"></i></button>
						</form><hr>
					</div>
					
					<div class="table-responsive users-table" >
                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                      <thead>
                        <tr>
                          <th class="text-center w-1"></th>
                          <th class="text-center w-1"><i class="fe fe-user"></i></th>
                          <th>User</th>
                          <th>Username</th>
                          <th>Email</th>
                          <th>Access Level</th>
							<?php if(isset($siteGuard->settings['2fa']) && $siteGuard->settings['2fa']  == 'on' ) {?>
							<th>Two Factor <br>Authentication</th><?php } ?>
                          <th class="text-center"><i class="fe fe-settings"></i> Options</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php 
						if (isset($_GET['page']) && is_numeric($_GET['page']) ) {
							$page= $_GET['page'];
						} else {
							$page=1;
						}
						
						$query = '';
						
						if(isset($_GET['status']) && !empty($_GET['status']) ) {
							switch ($_GET['status']) {
								case 'active' :
									$query .= " AND disabled = 0 AND pending = 0 ";
								break;
								case 'pending' :
									$query .= " AND pending = 1 ";
								break;
								
								case 'banned' :
									$query .= " AND disabled = 1 ";
								break;
								
								case 'closed' :
									$query .= " AND closed = 1 ";
								break;
							}
						}
						
						if(isset($_GET['name']) && !empty($_GET['name']) ) {
							$query .= " AND name LIKE '%". escape_value($_GET['name']) .  "%' ";
						}
						
						
						if(isset($_GET['group']) && is_numeric($_GET['group']) ) {
							$query .= " AND  prvlg_group = '" . escape_value($_GET['group']) . "' ";
						}

						if(isset($_GET['from_date']) && !empty($_GET['from_date']) && isset($_GET['to_date']) && !empty($_GET['to_date']) ) {
							$from = escape_value($_GET['from_date']);
							$to = escape_value($_GET['to_date']);
							$query .= " AND  DATE(registered) >= DATE('{$from}') AND DATE(registered) <= DATE('{$to}') ";
						}
						
						$total_count = $userModel->count_everything(" deleted = 0 {$query} ");
						$pagination = new \App\Libraries\Pagination($page, $per_page, $total_count);
						$all_users = $userModel->get_everything(" deleted = 0 {$query} " , "id ASC" , $per_page , $pagination->offset() );
						
						$i= (($page-1) * $per_page) + 1;
						if($all_users) {
							foreach($all_users as $user) {
					  ?>
                        <tr class="<?php if($user->disabled || $user->closed) { echo 'bg-red-lightest'; } ?>">
							<td class="text-center">
								<?php echo $i; ?>
							</td>
                          <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(<?php echo $userModel->get_avatar($user->id); ?>)">
                              <?php if($userModel->check_online($user->id)) { ?><span class="avatar-status bg-green"></span><?php } ?>
                            </div>
                          </td>
                          <td>
                            <div><a href="<?php echo base_url("profile/view/{$user->id}/{$siteGuard->csrf}"); ?>" class="text-dark"><?php echo $user->name; ?></a></div>
                            <div class="small text-muted">
                              Registered: <?php echo date_descriptive($user->registered); ?>
                            </div>
                          </td>
                          <td><?php echo '<div>'.$user->username.'</div>'; 
						  if($user->disabled) {
							  echo "<div class='tag tag-danger'>Banned
										  <span class='tag-addon'><i class='fe fe-slash'></i></span>
										</div>";
						  }
						  if($user->pending) {
							  echo "<div class='tag tag-warning'>Pending Approval
										  <span class='tag-addon'><i class='fe fe-alert-triangle'></i></span>
										</div>";
						  }
						  if($user->closed) {
							  echo "<div class='tag tag-danger'>Closed
										  <span class='tag-addon'><i class='fe fe-lock'></i></span>
										</div>";
						  }
						  ?></td>
                          <td><?php echo $user->email; ?></td>
                          <td><?php $group = $groupModel->get_specific_id($user->prvlg_group); echo $group->name; ?></td>
                          <?php if(isset($siteGuard->settings['2fa']) && $siteGuard->settings['2fa']  == 'on' ) {?><td class="text-center"><?php if($user->tfa == '1') {
								echo "<span class='badge badge-success'>Enabled</span>";
						  } else {
								echo "<span class='badge badge-danger'>Disabled</span>";
						  } ?></td><?php } ?>
						  <td class="text-center">
							<div class="btn-group">
							<?php if($siteGuard->privilege("profile.update") && $user->closed == '1') { ?><a href="<?php echo base_url("users/restore/{$user->id}/{$siteGuard->csrf}"); ?>" class="btn btn-success btn-sm" onclick="return confirm('<?php echo siteGuard_msg('activation-alert'); ?>');"><i class="fe fe-refresh-cw"></i> Reactivate</a><?php } ?>
							<?php if($siteGuard->privilege("profile.update") && $user->pending == '1') { ?><a href="<?php echo base_url("users/activate/{$user->id}/{$siteGuard->csrf}"); ?>" class="btn btn-success btn-sm" onclick="return confirm('<?php echo siteGuard_msg('activation-alert'); ?>');"><i class="fe fe-check"></i> Activate</a><?php } ?>
							<?php if($siteGuard->privilege("profile.read")) { ?><a href="<?php echo base_url("profile/view/{$user->id}/{$siteGuard->csrf}"); ?>" class="btn btn-primary btn-sm"><i class="fe fe-user"></i> Profile</a><?php } else { ?><a href="#me" class="btn btn-primary btn-sm disabled"><i class="fe fe-user"></i> Profile</a><?php } ?>
							<?php if($siteGuard->privilege("users.update")) { ?><a href="<?php echo base_url("users/update/{$user->id}/{$siteGuard->csrf}"); ?>" class="btn btn-warning btn-sm"><i class="fe fe-edit-2"></i> Edit</a><?php } else { ?><a href="#me" class="btn btn-warning btn-sm disabled"><i class="fe fe-edit-2"></i> Edit</a><?php } ?>
							<?php if($siteGuard->privilege("users.delete")) { ?><a href="<?php echo base_url("users/delete/{$user->id}/{$siteGuard->csrf}"); ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo siteGuard_msg('delete-alert'); ?>');" ><i class="fe fe-x"></i> Delete</a><?php } else { ?><a href="#me" class="btn btn-danger btn-sm disabled"><i class="fe fe-x"></i> Delete</a><?php } ?>
                            <?php if($current_user->prvlg_group == '1') { ?><div class="item-action dropdown">
							<a href="javascript:void(0)" data-toggle="dropdown" class="btn btn-secondary btn-sm btn-icon"><i class="fe fe-more-vertical"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<?php if($siteGuard->privilege("users.impersonate") && !$siteGuard->get_impersonate() && $user->id != $current_user->id ) { ?><a href="<?php echo base_url("users/impersonate/{$user->id}/{$siteGuard->csrf}"); ?>" class="dropdown-item" onclick="return confirm('<?php echo siteGuard_msg('impersonate-alert'); ?>');" ><i class="dropdown-icon fe fe-alert-triangle"></i> Impersonate </a><?php } ?>
									<?php if($user->disabled == '1') { ?>
									<?php if($siteGuard->privilege("users.update")) { ?><a href="<?php echo base_url("users/unban/{$user->id}/{$siteGuard->csrf}"); ?>" class="dropdown-item" onclick="return confirm('<?php echo siteGuard_msg('activation-alert'); ?>');"><i class="dropdown-icon fe fe-shield-off"></i> Unban User </a><?php } ?>
									<?php } elseif($user->closed == '1') {} else { ?>
									<?php if($siteGuard->privilege("users.update")) { ?><a href="<?php echo base_url("users/ban/{$user->id}/{$siteGuard->csrf}"); ?>" class="dropdown-item" onclick="return confirm('<?php echo siteGuard_msg('ban-alert'); ?>');"><i class="dropdown-icon fe fe-shield-off"></i> Ban User </a><?php } ?>
									<?php } ?>
									<?php if($siteGuard->privilege("usage_reports.read")) { ?><a href="<?php echo base_url("usage_reports?user_id={$user->id}"); ?>" class="dropdown-item"><i class="dropdown-icon fe fe-list"></i> Reports </a><?php } ?>
									<?php if($userModel->check_online($user->id) && $siteGuard->privilege("active_sessions.read") ) { ?><div class="dropdown-divider"></div>
									<a href="<?php echo base_url("active_sessions?user={$user->id}"); ?>" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> View Session</a><?php } ?>
								</div></div><?php } ?>
                            </div>
                          </td>
                        </tr>
						<?php $i++; } } else { ?>
						<tr><td colspan="8"><br><h3 class='text-muted text-center no-decoration'><i class='fe fe-shield'></i> No users found!</h3></td></tr>
						<?php } ?>
					</tbody>
					</table>
					</div>
				<script> "use strict";
				$(".show_filter").on("click", function(){
					$(".filter").slideToggle();
				});
				$('.select2').select2();
				</script>
					<?php
					
					if(isset($pagination) && $pagination->total_pages() > 1) {
					?>
					<center><div class="btn-group" >
					
							<?php
							if ($pagination->has_previous_page()) {
								$params = $_GET; $params['page'] = $pagination->previous_page(); $page_param = base_url('users') . '?'. htmlspecialchars(http_build_query($params), ENT_QUOTES);
								echo "<a href=\"{$page_param}\" class=\"btn btn-outline-dark\" ><i class=\"fa fa-chevron-left\"></i></a>";
							} else {
							?>
							<a href="#me" class="btn btn-outline-dark disabled"><i class="fa fa-chevron-left"></i></a>
							<?php
							}
							
							for($p=1; $p <= $pagination->total_pages(); $p++) {
								if($p == $page) {
									echo "<a href=\"#me\" class=\"btn btn-dark\" type=\"button\">{$p}</a>";
								} else {
									$params = $_GET; $params['page'] = $p; $page_param = base_url('users') . '?'.htmlspecialchars(http_build_query($params), ENT_QUOTES);
									echo "<a href=\"{$page_param}\" class=\"btn btn-outline-dark\" >{$p}</a>";
								}
							}
							if($pagination->has_next_page()) {
								$params = $_GET; $params['page'] = $pagination->next_page(); $page_param = base_url('users') . '?'. htmlspecialchars(http_build_query($params), ENT_QUOTES);
								echo " <a href=\"{$page_param}\" class=\"btn btn-outline-dark\" data-page=\"{$pagination->next_page()}\" type=\"button\"><i class=\"fa fa-chevron-right\"></i></a> ";
							} else {
							?>
							<a class="btn btn-outline-dark disabled" type="button"><i class="fa fa-chevron-right"></i></a>
							<?php
							}
							?>
					
					</div></center><br>
					<?php
					}
					?>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>