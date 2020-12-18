<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
		<div class="col-12">
				<div class="card">
					<div class="card-header">
					<h3 class="card-title">Manage Access Levels</h3><div class="card-options">
                      <a href="<?php echo base_url("access_levels/create"); ?>" class="btn btn-secondary ml-2"><i class="fa fa-plus"></i> &nbsp;&nbsp;Add New Group</a>
                    </div>
					
					</div>
					<div class="card-body">
					<div class="table-responsive" >
                    <table class="table table-hover table-outline table-vcenter text-nowrap datatable">
                      <thead>
                        <tr>
                          <th class="text-center w-1"></th>
                          <th>Privilege Group</th>
                          <th>Users</th>
                          <th>Privileges</th>
                          <th class="text-center"><i class="fe fe-settings"></i></th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php 
						$groups = $groupModel->get_everything(" deleted = 0 ");
						if($groups) {
							$i = 1;
							foreach($groups as $grp) {
							$users = "";
							$privileges = "";
							$group_users = $userModel->get_everything( " prvlg_group = '{$grp->id}' AND deleted = 0" , "name ASC",  "10" );
							
							if($group_users) {
								$m = 1;
								foreach($group_users as $group_user) {
									if($m <= 10) {
										$users .= "<span class='tag tag-azure'>{$group_user->name}</span>&nbsp;";
										$m++;
									}
								}
								if(count($group_users) > 10) {
									$diff = count($group_users) - 10;
									$users .= " (+{$diff} more)";
								}
							} else {
								$users = "<span class='tag'>No users found</span>";
							}
							
							$privileges_array= explode(',' , $grp->privileges);
							foreach($privileges_array as $prvlg) {
								$prvlg = str_replace('-', '', $prvlg);
								if(strpos($prvlg,'.read') !== false) {
									$privileges .= "<span class='tag tag-green'>{$prvlg}</span>&nbsp;";
								} elseif (strpos($prvlg,'_section') !== false) {
									$privileges .= "<span class='tag tag-gray-dark'>{$prvlg}</span>&nbsp;";
								} else {
									$privileges .= "<span class='tag tag-red'>{$prvlg}</span>&nbsp;";
								}
							}
							
							?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $grp->name; ?></td>
									<td class="fix-wrap"><?php echo $users; ?></td>
									<td class="fix-wrap"><?php echo $privileges; ?></td>
									<td><div class="btn-group">
											<a href="<?php echo base_url("access_levels/update/{$grp->id}/{$siteGuard->csrf}"); ?>" class="btn btn-warning btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
											<a href="<?php echo base_url("access_levels/delete/{$grp->id}/{$siteGuard->csrf}"); ?>" class="btn btn-danger btn-sm"  onclick="return confirm('<?php echo siteGuard_msg('delete-alert'); ?>');" ><i class="fe fe-x"></i> Delete</a>
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
					 </div>
		</div>
	</div>
</div>