<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
					<h3 class="card-title">Manage Active Sessions</h3>
					</div>
					<div class="card-body">
					<div class="table-responsive" >
                    <table class="table table-hover table-outline table-vcenter text-nowrap datatable">
                      <thead>
                        <tr>
                          <th class="text-center w-1"></th>
						  <th class="text-center w-1"><i class="fe fe-user"></i></th>
                          <th>User</th>
                          <th>Last Seen</th>
                          <th>Current Page</th>
                          <th>IP</th>
                          <th class="text-center"><i class="fe fe-settings"></i></th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php 
						$time_check = time() - 3000;
						$limit = '';
						if(isset($_GET['user']) && is_numeric($_GET['user'] )) {
							$limit = " AND user_id = '{$_GET['user']}' ";
						}
						$online = $onlineModel->get_everything(" time > '{$time_check}' {$limit} ");
						if($online) {
							$i = 1;
							foreach($online as $onl) {
							$user = $userModel->get_specific_id($onl->user_id);
							?>
								<tr>
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
									<?php 
									$my_session = session_id();
									if($user->id == $current_user->id && $my_session == $onl->session) { ?><span class="tag tag-success">This is you!</span><?php } else { ?>
									<div class="small text-muted">
									  Registered: <?php echo date_descriptive($user->registered); ?>
									</div><?php } ?>
								  </td>
									
									<td><?php echo $date = strftime("%Y-%m-%d %I:%M %p", $onl->time); echo "<br>(". date_ago($date) . ")"; ?></td>
									<td class="fix-wrap"><?php echo $onl->current_page; ?></td>
									<td class="fix-wrap"><?php if($onl->ip) {
										echo $onl->ip;
									
										$details = json_decode(file_get_contents("https://ipinfo.io/{$onl->ip}/json"));
										if($details) {
											$countries = json_decode(file_get_contents("http://country.io/names.json"), true);
											if(isset($details->country)) {
												echo "<br><img src='".base_url()."/SiteGuard/images/flags/".strtolower($details->country)."_64.png' class='flag-img'>";
												if(isset($details->city) && $details->city != "" ) { echo $details->city .' - '; }
												echo $countries[$details->country];
											}
										}
										} else { echo "N/A"; }
									
									?></td>
									<td><div class="btn-group">
											<a href="<?php echo base_url("active_sessions/revoke/{$onl->id}/{$siteGuard->csrf}"); ?>" class="btn btn-danger btn-sm"  onclick="return confirm('<?php echo siteGuard_msg('revoke-alert'); ?>');" ><i class="fe fe-x"></i> Revoke</a>
									</div></td>
								</tr>
						<?php }} ?>
					  </tbody>
					  </table>
					  <script> "use strict";
                      $('.datatable').DataTable();
                    </script>
					  </div>
					  
					 </div>
					 </div></div>
				
		</div>
	</div>
</div>