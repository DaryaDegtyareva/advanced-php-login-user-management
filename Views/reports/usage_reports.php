<?php if (isset($_GET['per_page']) && is_numeric($_GET['per_page']) ) {
		$per_page= $_GET['per_page'];
} else {
		$per_page='20';
}
?>
<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12">
				
				<div class="card">
					<div class="card-header">
					<h3 class="card-title">Usage Report</h3><?php if($siteGuard->privilege('usage_reports.power')) { ?><div class="card-options">
                      <a href="#me" class="show_filter btn btn-secondary ml-2"><i class="fa fa-filter"></i> &nbsp;&nbsp;Filter Reports</a>
					  <?php $params = $_GET; $page_param = htmlspecialchars(http_build_query($params), ENT_QUOTES); ?>
                      <a href="<?php echo base_url("usage_reports/clear/{$siteGuard->csrf}?{$page_param}"); ?>" class="btn btn-danger ml-2" onclick="return confirm('<?php echo siteGuard_msg('clear_usage-alert'); ?>');"><i class="fa fa-times"></i> &nbsp;&nbsp;Clear Reports</a>
                    </div><?php } ?>
					
					</div>
					<div class="card-body">
					
					<?php if($siteGuard->privilege('usage_reports.power')) { ?>
					<div class="filter hide-this">
						<form class="" action="<?php echo base_url("usage_reports"); ?>" method="GET" autocomplete="off" > 
							<select class="select2 select2-filter" id="" name="user_id" >
								<option value="" selected disabled>User..</option>
								<?php if(isset($_GET['user_id']) && $_GET['user_id'] != '') { ?><option value="">All users</option><?php }
							  
							  $groups = $groupModel->get_everything(" deleted = 0 ",  " id ASC ");
							  foreach($groups as $grp) {
								  echo "<optgroup label='{$grp->name}'>";
								  $users = $userModel->get_everything(" deleted = 0 AND prvlg_group = '{$grp->id}' " , " name ASC ");
								  if($users) {
									foreach($users as $user) {
										echo "<option value='{$user->id}' ";
											if(isset($_GET['user_id']) && $_GET['user_id'] == $user->id ) { echo " selected"; }
										echo " >{$user->name}</option>"; 
									}
								  }echo "</optgroup>";
							  } ?>
							</select><select class="select2 select2-filter" id="" name="action">
								<option value="" selected disabled>Action..</option>
								<?php if(isset($_GET['action']) && $_GET['action'] != '') { ?><option value="">All actions</option><?php } 
							  $actions = $logModel->get_actions();
							  foreach($actions as $action) {
									echo "<option value='{$action->action}' ";
											if(isset($_GET['action']) && $_GET['action'] == $action->action ) { echo " selected"; }
										echo " >{$action->action}</option>"; 
							  } ?>
							</select>
							
							<input type="text" class="form-control datepicker input-filter" name="from_date" placeholder="From date.." value="<?php if(isset($_GET['from_date']) && $_GET['from_date'] != "") { echo escape_value($_GET['from_date']); } ?>" >
							<input type="text" class="form-control datepicker input-filter " name="to_date" placeholder="To date.." value="<?php if(isset($_GET['to_date']) && $_GET['to_date'] != "") { echo escape_value($_GET['to_date']); } ?>" >
							
							<input type="text" class="form-control input-filter "  name="term" placeholder="Search Term.." value="<?php if(isset($_GET['term']) && $_GET['term'] != "") { echo escape_value($_GET['term']); } ?>" >
							<button type="submit" class="btn btn-secondary submit-filter" ><i class="fa fa-search"></i></button>
						</form><hr>
					</div><?php } ?>
					
					<div class="table-responsive">
                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                      <thead>
                        <tr>
                          <th class="text-center w-1"></th>
                          <th class="text-center w-1"><i class="fe fe-user"></i></th>
							<th>Name</th>
							<th>Event</th>
							<th>Message</th>							
							<th>Date</th>
							<th>I.P</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php 
						if (isset($_GET['page']) && is_numeric($_GET['page']) ) {
							$page= $_GET['page'];
						} else {
							$page=1;
						}
						
						
						$query=" id != 0 ";
						
					if($siteGuard->privilege('usage_reports.power')) {
						if(isset($_GET['action']) && !empty($_GET['action']) ) {
							$query .= " AND action LIKE '%". escape_value($_GET['action']) .  "%' ";
						}
						
						if(isset($_GET['term']) && !empty($_GET['term']) ) {
							$query .= " AND msg LIKE '%". escape_value($_GET['term']) .  "%' ";
						}
						
						
						if(isset($_GET['user_id']) && !empty($_GET['user_id']) ) {
							$query .= " AND  user_id = '" . escape_value($_GET['user_id']) . "' ";
						}

						if(isset($_GET['from_date']) && !empty($_GET['from_date']) && isset($_GET['to_date']) && !empty($_GET['to_date']) ) {
							$from = escape_value($_GET['from_date']);
							$to = escape_value($_GET['to_date']);
							$query .= " AND  DATE(done_at) >= DATE('{$from}') AND DATE(done_at) <= DATE('{$to}') ";
						}
					} else {
						$query = " user_id = {$current_user->id} ";
					}
						
						$total_count = $logModel->count_everything($query);
						$pagination = new \App\Libraries\Pagination($page, $per_page, $total_count);
						$all_logs = $logModel->get_everything($query,"id DESC", $per_page, $pagination->offset());
						
						$i= (($page-1) * $per_page) + 1;
						if($all_logs) {
						foreach($all_logs as $log) :
						?>
						<tr>
							<td class="text-center">
								<?php echo $i; ?>
							</td>
                          <?php
						  if($log->user_id && $userModel->exists("id",$log->user_id)) {
								$user = $userModel->get_specific_id($log->user_id);
						  ?>
						  <td class="text-center">
                            <div class="avatar d-block" style="background-image: url(<?php echo $userModel->get_avatar($user->id); ?>)">
                              <?php if($userModel->check_online($user->id)) { ?><span class="avatar-status bg-green"></span><?php } ?>
                            </div>
                          </td>
                          <td>
                            <div><a href="<?php echo base_url("profile/view/{$user->id}"); ?>" class="text-dark"><?php echo $user->name; ?></a></div>
                            <div class="small text-muted">
                              Registered: <?php echo date_descriptive($user->registered); ?>
                            </div>
                          </td>
						  <?php } else { ?>
						  
						  <td class="text-center">
                            <div class="avatar d-block" style="background-image: url('<?php echo base_url() ?>/SiteGuard/images/undefined.png')">
                            </div>
                          </td>
                          <td>
                            <div>Unidentified User</div>
                          </td>
						  
						  <?php } ?>
						  <td><?php echo $log->action; ?></td>
							<td class="fix-wrap"><?php echo $log->msg; ?></td>
							<td><?php echo full_date_descriptive($log->done_at); ?></td>
							<td class="fix-wrap"><?php echo $log->ip; 
							
							$details = json_decode(file_get_contents("https://ipinfo.io/{$log->ip}/json"));
										if($details) {
											$countries = json_decode(file_get_contents("http://country.io/names.json"), true);
											if(isset($details->country)) {
												echo "<br><img src='".base_url()."/SiteGuard/images/flags/".strtolower($details->country)."_64.png' class='flag-img'>";
												if(isset($details->city) && $details->city != "" ) { echo $details->city .' - '; }
												echo $countries[$details->country];
											}
										}
							
							?></td>
						</tr><?php
							$i++;
							endforeach;
						}
							?>
					</tbody>
					</table>
					</div>
					
					<?php
					
					if(isset($pagination) && $pagination->total_pages() > 1) {
					?>
					<center><div class="btn-group" >
					
							<?php
							if ($pagination->has_previous_page()) {
								$params = $_GET; $params['page'] = $pagination->previous_page(); $page_param = base_url('usage_reports/') .'?'. htmlspecialchars(http_build_query($params), ENT_QUOTES);
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
									$params = $_GET; $params['page'] = $p; $page_param = base_url('usage_reports/') .'?'. htmlspecialchars(http_build_query($params), ENT_QUOTES);
									echo "<a href=\"{$page_param}\" class=\"btn btn-outline-dark\" >{$p}</a>";
								}
							}
							if($pagination->has_next_page()) {
								$params = $_GET; $params['page'] = $pagination->next_page(); $page_param = base_url('usage_reports/') .'?'. htmlspecialchars(http_build_query($params), ENT_QUOTES);
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
					
				<script> "use strict";
					$(".show_filter").on("click", function(){
						$(".filter").slideToggle();
					});
					$('.select2').select2();
				</script>
					
					
					</div>
				</div>
			
			</div>
		</div>
	</div>
</div>