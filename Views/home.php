<div class="my-3 my-md-5">	
	<div class="container">
		<div class="page-header">
              <h1 class="page-title">
                <?php echo greeting(); ?>, <?php $name = explode(' ' , $current_user->name); echo $name[0]; ?>!
              </h1>
            </div>
			
		<?php $announcements = $annModel->get_everything( " visible_to LIKE '%-{$current_user->prvlg_group}-%' " );
		if($announcements) {
			foreach($announcements as $msg) {
			if($annModel->isValid($msg->id)) {
				$icon = array("primary" => "bell", "danger" => "alert-triangle", "success" => "check", "warning" => "alert-circle", "secondary" => "volume-2", "light" => "info");
		?>
		
		<div class="alert alert-icon alert-<?php echo $msg->type; ?> alert-dismissible">
		<button type="button" class="close" data-dismiss="alert"></button>
			<i class="fe fe-<?php echo $icon[$msg->type]; ?> mr-2" aria-hidden="true"></i> <?php 
			$message = html_entity_decode(urldecode($msg->message)); 
			echo strip_tags($message,'<b><i><u><p><a><img>');
			?>
		</div>
		
		<?php $annModel->seen($msg->id); }}} ?>
			
			
			
		<?php if(file_exists(FCPATH.'/install/') && $siteGuard->only_for('1') ) { ?><div class="alert alert-warning"><i class='fe fe-alert-triangle'></i> Warning! <b>Installation Script</b> detected, please remove <span class='tag console'>install</span> folder to prevent any security breaches.</div><?php } ?>
		<div class="row row-cards">
			<?php if($siteGuard->only_for('1')) { ?>
			<div class="col-sm-6 col-lg-3">
                <div class="card">
                  <div class="row">
					  <div class="col-5 dashboard-icon">
						<i class="fe fe-users text-blue"></i>
					  </div>
					  <div class="col-7 text-right pt-5 pr-7">
						<a href="<?php echo base_url('users'); ?>" class='no-decoration'>
						<div class="h1 m-1 text-blue"><?php echo $userModel->count_everything(' disabled = 0 AND deleted = 0 '); ?></div>
						<div class="text-muted mb-4">Total Users</div></a>
					  </div>
                </div>
                </div>
              </div><div class="col-sm-6 col-lg-3">
                <div class="card">
                  <div class="row">
					  <div class="col-5 dashboard-icon">
						<i class="fe fe-rss text-green"></i>
					  </div>
					  <div class="col-7 text-right pt-5 pr-7">
						<a href="<?php echo base_url("active_sessions"); ?>" class='no-decoration'>
						<div class="h1 m-1 text-green"><?php $time_check = time() - 300; 
						$onlineModel = new \App\Models\Online();
						echo $onlineModel->count_everything(" time > '{$time_check}' " ); ?></div>
						<div class="text-muted mb-4">Currently Online</div></a>
					  </div>
                </div>
                </div>
              </div><div class="col-sm-6 col-lg-3">
                <div class="card">
                  <div class="row">
					  <div class="col-5 dashboard-icon">
						<i class="fe fe-shield-off text-red"></i>
					  </div>
					  <div class="col-7 text-right pt-5 pr-7">
						<a href="<?php echo base_url("users?status=banned"); ?>" class='no-decoration'>
						<div class="h1 m-1 text-red"><?php echo $userModel->count_everything(' disabled = 1 AND deleted = 0 '); ?></div>
						<div class="text-muted mb-4">Banned Users</div></a>
					  </div>
                </div>
                </div>
              </div>
				<div class="col-sm-6 col-lg-3">
                <div class="card">
                  <div class="row">
					  <div class="col-5 dashboard-icon">
						<i class="fe fe-alert-triangle text-orange"></i>
					  </div>
					  <div class="col-7 text-right pt-5 pr-7">
						<a href="<?php echo base_url("users?status=pending"); ?>" class='no-decoration'>
						<div class="h1 m-1 text-orange"><?php echo $userModel->count_everything(' pending = 1 AND deleted = 0  '); ?></div>
						<div class="text-muted mb-4">Pending Users</div></a>
					  </div>
                </div>
                </div>
              </div>
			  
			  
			  <div class="col-lg-9 col-sm-12">
				<div class="card">
                  <div class="card-body p-3 text-center">
						<div id="chart-registrations" ></div>
					</div>
				</div>
				<?php $months = array();
					$new = array();
					
					for($i = 0; $i < 6 ; $i++) {
						$months[] = strftime("%B" , strtotime("-{$i} Month" , time())); 
						$new[] = $userModel->count_everything(' hybridauth_provider_name = "" AND  DATE_FORMAT(registered, "%m-%Y") = "' . strftime("%m-%Y" , strtotime("-{$i} Month" , time())) 	 . '"  ');
						$new2[] = $userModel->count_everything(' hybridauth_provider_name = "facebook" AND  DATE_FORMAT(registered, "%m-%Y") = "' . strftime("%m-%Y" , strtotime("-{$i} Month" , time())) 	 . '"  ');
						$new3[] = $userModel->count_everything(' hybridauth_provider_name = "google" AND  DATE_FORMAT(registered, "%m-%Y") = "' . strftime("%m-%Y" , strtotime("-{$i} Month" , time())) 	 . '"  ');
					}
					
					$months = array_reverse($months);
					$new = array_reverse($new);
					$new2 = array_reverse($new2);
					$new3 = array_reverse($new3);
				?>
			<script> "use strict";
                  	$(document).ready(function(){
                  		var chart = c3.generate({
                  			bindto: '#chart-registrations', // id of chart wrapper
                  			data: {
                  				columns: [
                  				    // each columns data
                  					['data1', <?php echo '"' . implode('","' , $new) . '"'; ?>],
                  					['data2', <?php echo '"' . implode('","' , $new2) . '"'; ?>],
                  					['data3', <?php echo '"' . implode('","' , $new3) . '"'; ?>]
                  				],
                  				type: 'line', // default type of chart
                  				colors: {
                  					'data1': tabler.colors["green"],
                  					'data2': tabler.colors["blue"],
                  					'data3': tabler.colors["red"]
                  				},
                  				names: {
                  				    // name of each serie
                  					'data1': 'Registrations',
                  					'data2': 'Facebook',
                  					'data3': 'Google'
                  				}
                  			},
                  			axis: {
                  				x: {
                  					type: 'category',
                  					// name of each category
                  					categories: [<?php echo '"' . implode('","' , $months) . '"'; ?>]
                  				},
                  			},
                  			legend: {
                                  show: false, //hide legend
                  			},
                  			padding: {
                  				bottom: 0,
                  				top: 0
                  			},
                  		});
                  	});
                  
                </script>
			
		</div>
		<div class="col-lg-3 col-sm-12">
			<div class="card">
			  <div class="card-body registration-box">
			  <h3 class="card-title">New Registrations</h3>
					<table class="table table-hover table-outline table-vcenter">
						<?php $all_users = $userModel->get_everything(" deleted = 0 " , "registered DESC ", 5); 
						if($all_users) {
							foreach($all_users as $user) { ?>
							<tr><td class="text-center">
                            <div class="avatar d-block" style="background-image: url(<?php echo $userModel->get_avatar($user->id); ?>)">
                              <?php if($userModel->check_online($user->id)) { ?><span class="avatar-status bg-green"></span><?php } ?>
                            </div>
                          </td>
                          <td>
                            <div><a href="<?php echo base_url("profile/view/{$user->id}"); ?>" class="text-dark"><?php echo $user->name; ?></a></div>
                            <div class="small text-muted">
                              <?php echo date_descriptive($user->registered); ?>
                            </div>
                          </td></tr>
							<?php }
						} else {
							echo "<p class='h4 text-center text-gray pt-8'><br><i class='fe fe-shield'></i> No users found!</p>";
						}
						?>
					</table>
				</div>
			</div>
		</div>
			  
			  
			  
			  <?php } else { ?>
			<div class="col-sm-12 col-lg-3">
                <div class="card">
                  <div class="row">
					  <div class="col-5 dashboard-icon">
						<i class="fe fe-users text-blue"></i>
					  </div>
					  <div class="col-7 text-right pt-5 pr-7">
						<a href="<?php echo base_url("profile"); ?>" class='no-decoration'>
							<div class="h3 text-gray mt-3">My<br><span class='text-dark'>Profile</span></div>
						</a>
					  </div>
                </div>
                </div>
              
			
                <div class="card">
                  <div class="row">
					  <div class="col-5 dashboard-icon">
						<i class="fe fe-rss text-green"></i>
					  </div>
					  <div class="col-7 text-right pt-5 pr-7">
						<a href="<?php echo base_url("usage_reports"); ?>" class='no-decoration'>
							<div class="h3 text-gray mt-3">My<br><span class='text-dark'>Activity</span></div>
						</a>
					  </div>
                </div>
                </div>
				
				<div class="card">
                  <div class="row">
					  <div class="col-5 dashboard-icon">
						<i class="fe fe-log-out text-red"></i>
					  </div>
					  <div class="col-7 text-right pt-5 pr-7">
						<a href="<?php echo base_url("logout"); ?>" class='no-decoration'>
							<div class="h3 text-gray mt-3">Sign<br><span class='text-dark'>Out</span></div>
						</a>
					  </div>
                </div>
                </div>
              </div>
			  
			  <div class="col-sm-12 col-lg-9">
			  
			  <div class="card">
                  <div class="card-body p-3 text-center">
						<div id="chart-activity" class="dashboard-chart" ></div>
					</div>
				</div>
				<?php $months = array();
					$new = array();
					$log = new \App\Models\SiteGuardLog();
					for($i = 0; $i < 6 ; $i++) {
						$months[] = strftime("%B" , strtotime("-{$i} Month" , time())); 
						$new[] = $log->count_everything(' user_id = "'.$current_user->id.'" AND DATE_FORMAT(done_at, "%m-%Y") = "' . strftime("%m-%Y" , strtotime("-{$i} Month" , time())) 	 . '" ');
					}
					
					$months = array_reverse($months);
					$new = array_reverse($new);
				?>
			<script> "use strict";
                  
                  	$(document).ready(function(){
                  		var chart = c3.generate({
                  			bindto: '#chart-activity', // id of chart wrapper
                  			data: {
                  				columns: [
                  				    // each columns data
                  					['data1', <?php echo '"' . implode('","' , $new) . '"'; ?>]
                  				],
                  				type: 'line', // default type of chart
                  				colors: {
                  					'data1': tabler.colors["blue"]
                  				},
                  				names: {
                  				    // name of each serie
                  					'data1': 'My activity'
                  				}
                  			},
                  			axis: {
                  				x: {
                  					type: 'category',
                  					// name of each category
                  					categories: [<?php echo '"' . implode('","' , $months) . '"'; ?>]
                  				},
                  			},
                  			legend: {
                                  show: false, //hide legend
                  			},
                  			padding: {
                  				bottom: 0,
                  				top: 0
                  			},
                  		});
                  	});
		
                </script>
				
              </div>
			
			  <?php } ?>
		
		
		</div>
	</div>
</div>