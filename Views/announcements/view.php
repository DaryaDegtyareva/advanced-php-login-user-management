<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12">
			<div class="card">
		<div class="card-header">
		<h3 class="card-title">Manage Announcements</h3><div class="card-options">
			<a href="#me" class="show_filter btn btn-secondary ml-2"><i class="fa fa-filter"></i> &nbsp;&nbsp;Filter Results</a>
			<a href="<?php echo base_url("announcements/create"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-plus"></i> &nbsp;&nbsp;Add New Announcement</a>
		</div>
		
		</div>
		<div class="card-body">
		
		<div class="filter hide-this" >
			<form class="" action="<?php echo base_url("announcements"); ?>" method="GET" autocomplete="off" > 
				<select class="select2 select2-filter" id="" name="group">
					<option value="" selected disabled>Visible by..</option>
					<?php if(isset($_GET['group']) && $_GET['group'] != '') { ?><option value="">All access levels</option><?php }
				  
				  $groups = $groupModel->get_everything(" deleted = 0 ORDER BY id ASC");
				  foreach($groups as $grp) {
					  echo "<option value='{$grp->id}' ";
								if(isset($_GET['group']) && $_GET['group'] == $grp->id ) { echo " selected"; }
							echo " >{$grp->name}</option>"; 
					}
				  ?>
				</select>
				<input type="text" class="form-control datepicker input-filter" name="from_date" placeholder="From.." value="<?php if(isset($_GET['from_date']) && $_GET['from_date'] != "") { echo escape_value($_GET['from_date']); } ?>" >
				<input type="text" class="form-control datepicker input-filter" name="to_date" placeholder="To.." value="<?php if(isset($_GET['to_date']) && $_GET['to_date'] != "") { echo escape_value($_GET['to_date']); } ?>" >
				
				<input type="text" class="form-control input-filter" name="message" placeholder="Name.." value="<?php if(isset($_GET['message']) && $_GET['message'] != "") { echo escape_value($_GET['name']); } ?>" >
				<button type="submit" class="btn btn-secondary submit-filter" ><i class="fa fa-search"></i></button>
			</form><hr>
		</div>
		
		<div class="table-responsive users-table" >
		<table class="table table-hover table-outline table-vcenter card-table">
		  <thead>
			<tr>
			  <th class="text-center w-1"></th>
			  <th class="text-center w-1"><i class="fe fe-user"></i></th>
			  <th>User</th>
			  <th>Message</th>
			  <th>Visibility</th>
			  <th>Created at</th>
			  <th>Expires at</th>
			  <th>Seen</th>
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
			$per_page= 20;
			
			$query = ' id != 0 ';
			
			if(isset($_GET['user_id']) && is_numeric($_GET['user_id']) ) {
				$query .= " AND user_id  = '". escape_value($_GET['user_id']) .  "' ";
			}
			
			if(isset($_GET['message']) && !empty($_GET['message']) ) {
				$query .= " AND (title LIKE '%". escape_value($_GET['message']) .  "%' OR message LIKE '%". escape_value($_GET['message']) .  "%' ) ";
			}
			
			
			if(isset($_GET['group']) && is_numeric($_GET['group']) ) {
				$query .= " AND  visible_to LIKE '%-" . escape_value($_GET['group']) . "-%' ";
			}

			if(isset($_GET['from_date']) && !empty($_GET['from_date']) && isset($_GET['to_date']) && !empty($_GET['to_date']) ) {
				$from = escape_value($_GET['from_date']);
				$to = escape_value($_GET['to_date']);
				$query .= " AND  DATE(created_at) >= DATE('{$from}') AND DATE(created_at) <= DATE('{$to}') ";
			}
			
			$total_count = $annModel->count_everything(" {$query} ");
			$pagination = new \App\Libraries\Pagination($page, $per_page, $total_count);
			$all_msgs = $annModel->get_everything(" {$query} ORDER BY id ASC LIMIT {$per_page} OFFSET {$pagination->offset()} ");
			
			$i= (($page-1) * $per_page) + 1;
			if($all_msgs) {
				foreach($all_msgs as $msg) {
					$user = $userModel->get_specific_id($msg->user_id);
					
						$groups_arr = explode("," , $msg->visible_to);
						$visibility = "";
						if(!empty($groups_arr)) {
							foreach($groups_arr as $group) {
								$group_id = str_replace('-' , '', $group);
								$group = $groupModel->get_specific_id($group_id);
								if(!empty($group->name)) {
									$visibility .= "<span class='tag tag-gray-dark'>{$group->name}</span>&nbsp;";
								}
							}
						}
					
					$expires = 'Never';
					$expired = false;
					if($msg->expire_after != 'never') {
						$deadline = strtotime($msg->expire_after, strtotime($msg->created_at));
						$expires = strftime("%B %d, %Y", $deadline);
						if(time() > $deadline) {
							$expired = true;
						}
					}
					
					$icon = array("primary" => "bell", "danger" => "alert-triangle", "success" => "check", "warning" => "alert-circle", "secondary" => "volume-2", "light" => "info");
					
		  ?>
			<tr class="<?php if($expired) { echo 'bg-red-lightest'; } ?>">
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
			  <td><?php echo $msg->name; ?></td>
			  <td><?php echo $visibility; ?></td>
			  <td><?php echo date_descriptive($msg->created_at); ?></td>
			  <td><?php echo $expires; ?></td>
			  <td><?php echo $msg->seen. ' times'; ?></td>
			  
			  <td class="text-center">
				<div class="btn-group">
				<a href="#me" data-toggle="modal" data-target="#announcement-<?php echo $msg->id; ?>" class="btn btn-primary btn-sm"><i class="fe fe-search"></i> View</a>
				<?php if($siteGuard->privilege("announcements.update")) { ?><a href="<?php echo base_url("announcements/update/{$msg->id}/{$siteGuard->csrf}"); ?>" class="btn btn-warning btn-sm"><i class="fe fe-edit-2"></i> Edit</a><?php } else { ?><a href="#me" class="btn btn-warning btn-sm disabled"><i class="fe fe-edit-2"></i> Edit</a><?php } ?>
				<?php if($siteGuard->privilege("announcements.delete")) { ?><a href="<?php echo base_url("announcements/delete/{$msg->id}/{$siteGuard->csrf}"); ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo siteGuard_msg('delete-alert'); ?>');" ><i class="fe fe-x"></i> Delete</a><?php } else { ?><a href="#me" class="btn btn-danger btn-sm disabled"><i class="fe fe-x"></i> Delete</a><?php } ?>
				</div>
			  </td>
			</tr>
			<!-- Modal -->
		<div class="modal fade" id="announcement-<?php echo $msg->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">View announcement: <?php echo $msg->name; ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true"></span>
				</button>
			  </div>
			  <div class="modal-body">
				<div class="alert alert-icon alert-<?php echo $msg->type; ?>">
				<i class="fe fe-<?php echo $icon[$msg->type]; ?> mr-2" aria-hidden="true"></i> <?php 
				$message = html_entity_decode(urldecode($msg->message)); 
				echo strip_tags($message,'<b><i><u><p><a><img>');
				?>
				</div>
			  </div>
			</div>
		  </div>
		</div>
			<?php $i++; } } else { ?>
			<tr><td colspan="9"><br><h3 class='text-muted text-center no-decoration'><i class='fe fe-shield'></i> No announcements found</h3></td></tr>
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
					$params = $_GET; $params['page'] = $pagination->previous_page(); $page_param = htmlspecialchars(http_build_query($params), ENT_QUOTES);
					echo "<a href=\"".base_url("announcements")."?{$page_param}\" class=\"btn btn-outline-dark\" ><i class=\"fa fa-chevron-left\"></i></a>";
				} else {
				?>
				<a href="#me" class="btn btn-outline-dark disabled"><i class="fa fa-chevron-left"></i></a>
				<?php
				}
				
				for($p=1; $p <= $pagination->total_pages(); $p++) {
					if($p == $page) {
						echo "<a href=\"#me\" class=\"btn btn-dark\" type=\"button\">{$p}</a>";
					} else {
						$params = $_GET; $params['page'] = $p; $page_param = htmlspecialchars(http_build_query($params), ENT_QUOTES);
						echo "<a href=\"".base_url("announcements")."?{$page_param}\" class=\"btn btn-outline-dark\" >{$p}</a>";
					}
				}
				if($pagination->has_next_page()) {
					$params = $_GET; $params['page'] = $pagination->next_page(); $page_param = htmlspecialchars(http_build_query($params), ENT_QUOTES);
					echo " <a href=\"".base_url("announcements")."?{$page_param}\" class=\"btn btn-outline-dark\" data-page=\"{$pagination->next_page()}\" type=\"button\"><i class=\"fa fa-chevron-right\"></i></a> ";
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