<?php
$navigation_pages = unserialize(base64_decode($navigation->value));
?>
<div class="my-3 my-md-5">	
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-6">
				<form class="card pages-form" action="<?php echo base_url("privileges"); ?>" method="POST">
					<div class="card-header"><h3 class="card-title">Site Pages & Sections</h3><div class="card-options">
                      <a href="#me" class="btn btn-secondary btn-sm ml-2"  data-toggle="modal" data-target="#addPage"><i class="fa fa-plus"></i> &nbsp;&nbsp;Add Page</a>
					  <a href="#me" class="btn btn-secondary btn-sm ml-2" data-toggle="modal" data-target="#addSection"><i class="fa fa-plus"></i> &nbsp;&nbsp;Add Section</a>
                    </div></div>
					<div class="card-body overall-container">
						<div class="alert alert-icon alert-info" role="alert">
                          <i class="fe fe-alert-circle mr-2" aria-hidden="true"></i> Use this section to add your actual site & script pages, check <a href="<?php echo base_url("access_levels"); ?>">access levels</a> page to control which group of users can see each page.
                        </div>
				
				<?php 
				$icons = array('home' , 'users', 'layers', 'settings' , 'sliders' , 'database' ,  'cpu' , 'film' , 'map' , 'mail' , 'lock' , 'link' , 'message-circle' , 'alert-triangle' , 'clock' , 'power' , 'rss' , 'trash-2' , 'shopping-cart' , 'code' , 'file-text');
				?>
				
				<ol class="sort default vertical" id="pages_list">
					<?php foreach($navigation_pages as $page) {
					if($page['type'] == 'section') {
						$type = 'section';
					} else {
						$type = 'page';
					}
					?>
					
					<li class="parent" data-type="<?php echo $type; ?>" data-name="<?php echo url_title($page['name'],'-',TRUE); ?>" data-predefined="<?php echo $page['predefined']; ?>" data-icon="<?php echo $page['icon']; ?>">
						<div class="row"><div class="col-2 pl-5 pt-1">
							<select class="select2-icon <?php echo $page['name'] ?>-icon" style="width:51px"><?php foreach($icons as $icon) {
								echo "<option value='{$icon}' ";
									if($icon == $page['icon']) { echo ' selected'; }
								echo " ></option>";
								} ?></select>
						</div>
						<div class="col-2 pl-5 pt-1"><?php if($type == 'section') { echo 'Section: '; } else { echo 'Page:'; } ?></div>
						<div class="col-7"><input type="text" class="form-control" value="<?php echo $page['name']; ?>" <?php if($page['predefined'] == '1') { echo 'readonly'; } ?> required></div>
						<div class="col-1 pt-1"><a href="#me" class="btn btn-danger <?php if($page['predefined'] == '1') { echo 'disabled'; } else { echo "remove_{$type}"; } ?> btn-sm pull-right" data-toggle="tooltip" title="Delete <?php echo ucfirst($type); ?> <?php if($page['predefined'] == '1') { echo '(Unavailable)'; } ?>"><i class="fa fa-times"></i></a></div>
						</div>
						<?php if($type == 'section') { 
							if(isset($page['children'])) {echo '<ol>'; foreach($page['children'][0] as $v) { ?>
						<li class="" data-type="<?php echo $v['type']; ?>" data-name="<?php echo url_title($v['name'], '-', TRUE); ?>" data-predefined="<?php echo $v['predefined']; ?>">
							<div class="row"><div class="col-2 pl-5 pt-1">Page:</div>
							<div class="col-8"><input type="text" class="form-control" value="<?php echo $v['name']; ?>" <?php if($v['predefined'] == '1') { echo 'readonly'; } ?> required></div>
							<div class="col-2 pt-1"><a href="#me" class="btn btn-danger <?php if($v['predefined'] == '1') { echo 'disabled'; } else { echo 'remove_page'; } ?> btn-sm pull-right" data-toggle="tooltip" title="Delete Page <?php if($v['predefined'] == '1') { echo '(Unavailable)'; } ?>"><i class="fa fa-times"></i></a></div>
							</div>
						</li><?php } echo '</ol>'; } else { echo "<ol></ol>";} 
						} ?>
						
					</li>
						
						
						
					<?php } ?>
						
            </ol>
			
			
			<!-- Modal -->
			<div class="modal fade" id="addSection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add New Section</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true"></span>
					</button>
				  </div>
				  <div class="modal-body row">
					
					<div class="col-2"><label for="inputIcon" class="control-label">Icon:</label>
					<div class="">
						<select class="select2-icon new_section_icon" style="width:51px"><?php foreach($icons as $icon) {
								echo "<option value='{$icon}'></option>";
								} ?></select>
					</div>
					</div>
					<div class="col-10"><label for="inputName" class="control-label">Section Name:</label>
					<div class="">
						<input type="text" class="form-control new_section_name" placeholder="" >
					</div></div>
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary add_new_section">Add Section</button>
				  </div>
				</div>
			  </div>
			</div><!-- Modal -->
			<div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add New Page</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true"></span>
					</button>
				  </div>
				  <div class="modal-body">
					
					
					<div class="row">
					<div class="col-2"><label for="inputIcon" class="control-label">Icon:</label>
					<div class="">
						<select class="select2-icon new_page_icon" style="width:51px"><?php foreach($icons as $icon) {
								echo "<option value='{$icon}'></option>";
								} ?></select>
					</div>
					</div>
					<div class="col-10"><label for="inputName" class="control-label">Page Name:</label>
					<div class="">
						<input type="text" class="form-control new_page_name" placeholder="" >
					</div></div></div>
					
					
					
					<br>
					
					<div class="form-group">
                        <div class="form-label">Create actual PHP Controller/View pages?</div>
                        <div class="custom-controls-stacked">
                          <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" name="create_page" value="yes" >
                            <span class="custom-control-label create_page">Yes</span>
                          </label>
                          <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input create_page" name="create_page" value="no" checked>
                            <span class="custom-control-label">No</span>
                          </label>
                        </div>
                      </div>
					
					
					
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary add_new_page">Add Page</button>
				  </div>
				</div>
			  </div>
			</div>
			
			<p id="pages_list_locator"></p>
							
					</div>
					<div class="card-footer text-right">
					  <div class="d-flex">
						<a href="#me" class="btn btn-primary ml-auto update-pages">Update pages</a>
					  </div>
					</div>
					<?php 
						echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>";
						echo "<input type=\"hidden\" name=\"update_pages\" value=\"true\" readonly/>";
					?>
				</form>
				
				<script>"use strict";
						
						function generateSwal(title, type, message) {
							swal(title, message, type);
						}
						function scrollToId(aid){
							var aid = aid.split('#')[1];
							var aTag = $("[id='"+ aid +"']");
							$('html,body').animate({scrollTop: eval(aTag.offset().top - 50)},'slow');
						}
						function slugify(Text) {
							return Text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'_');
						}
						$(".add_new_section").on("click", function(){
							var section_name = slugify($(".new_section_name").val());
							var section_icon = $(".new_section_icon").val();
							
							if(section_name !== '') {
								
								var container = $("ol.sort");
								container.append("<li class='' data-name='"+section_name+"' data-type='section' data-predefined='0' data-icon='"+section_icon+"'><div class='row'><div class='col-2 pl-5 pt-1 '><p style='text-align:center; border:1px solid #aaa; border-radius:4px; height:35px; width:51px; line-height:35px'><i class='fe fe-"+section_icon+"'></i></p></div><div class='col-2'style='padding-left:20px;padding-top:7px'>Section:</div><div class='col-7'><input type='text' class='form-control' placeholder='Section Name..' value='"+section_name+"' required></div><div class='col-1' style='padding-top:7px'><a href='#me' class='remove_section btn btn-danger btn-sm pull-right' data-toggle='tooltip' title='Delete Section'><i class='fa fa-times'></i></a></div></div><ol></ol></li>");
								
								scrollToId("#pages_list_locator");
							}
							 $('#addSection').modal('toggle');
						});
						
						$(".add_new_page").on("click", function(){
							var page_name = slugify($(".new_page_name").val());
							var page_icon = $(".new_page_icon").val();
							var create_page = $("input[name=create_page]:checked").val();
							
							if(page_name !== '') {
								
								var container = $("ol.sort");
								container.append("<li class='' data-name='"+page_name+"' data-type='page' data-predefined='0' data-create_page='"+create_page+"' data-icon='"+page_icon+"'><div class='row'><div class='col-2 pl-5 pt-1 '><p style='text-align:center; border:1px solid #aaa; border-radius:4px; height:35px; width:51px; line-height:35px'><i class='fe fe-"+page_icon+"'></i></p></div><div class='col-2'style='padding-left:20px;padding-top:7px'>Page:</div><div class='col-7'><input type='text' class='form-control' placeholder='Page Name..' value='"+page_name+"' required></div><div class='col-1' style='padding-top:7px'><a href='#me' class='remove_page btn btn-danger btn-sm pull-right' data-toggle='tooltip' title='Delete Page'><i class='fa fa-times'></i></a></div></div></li>");
								
								scrollToId("#pages_list_locator");
							}
							 $('#addPage').modal('toggle');
						});
						
						function serializeIcons () {
							$("ol.sort li.parent").each(function(i, obj) {
								var name = $(this).find('.form-control').val();
								var icon = $("." + name + "-icon").val();
								$(this).data('name' , name);
								$(this).data('icon' , icon);
							});
						}
						
						$(".update-pages").on("click", function(){
							serializeIcons();
							var values_arr = $(".sort").sortable("serialize").get();
							$.post("<?php echo base_url('privileges'); ?>", {update_pages: true, data: values_arr , <?php echo csrf_token(); ?>:'<?php echo $siteGuard->csrf; ?>'}, function(data){
								generateSwal(data.title,data.type,data.message);
							});
						});
						
						$(document).ready(function(){
							function setIcon (icon) {
							  if (!icon.id) { return icon.text; }
								var $icon = $('<center><i class="fe fe-' + icon.element.value + '"></i></center>');
								return $icon;
							};
							
							$(".select2-icon").select2({
								minimumResultsForSearch: -1,
								templateResult: setIcon,
								templateSelection: setIcon
							});
						});
				  
				</script>
				
				
			</div>
			<div class="col-12 col-lg-6">
				
				<form class="card" action="<?php echo base_url("privileges"); ?>" method="POST">
					<div class="card-header"><h3 class="card-title">Privileges</h3><div class="card-options">
                      <a href="#me" class="add_privilege btn btn-secondary btn-sm"><i class="fa fa-plus"></i> &nbsp;&nbsp;Add privilege</a>
                    </div></div>
					<div class="card-body">
						<div class="alert alert-icon alert-info" role="alert">
                          <i class="fe fe-alert-circle mr-2" aria-hidden="true"></i> Use this section to add special privileges for your script, you can add any privilege you want, assign it to a group of users in <a href="<?php echo base_url("access_levels"); ?>">access levels</a> page and ask for it later using the privilege checker API.
                        </div>
						<?php 
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
						?>
				
				<ol class="default vertical">
					<?php foreach($predefined as $key => $value) { ?><li class="">
						<div class="row"><div class="col-6">
						<input type="text" class="form-control" placeholder="<?php echo $key; ?>" readonly>
						</div>
						<div class="col-5"><input type="text" class="form-control" placeholder="<?php echo $value; ?>" readonly></div>
						<div class="col-1 pt-1"><a href="#me" class="btn btn-danger disabled btn-sm pull-right" data-toggle="tooltip" title="Delete Privilege (unavailable)"><i class="fa fa-times"></i></a></div>
						</div>
					</li><?php } ?>
				</ol>
				<ol class="sort2 default vertical" id="privileges_list">
					<?php if($privileges->value != '') {
					$prvlgs = unserialize($privileges->value);
					if(is_array($prvlgs) && !empty($prvlgs) ) {
						foreach($prvlgs as $k => $v) {
							echo "<li class=''><div class='row'><div class='col-6' ><input type='text' class='form-control' name='display_name[]' placeholder='Display Name...' value='{$k}' required></div><div class='col-5'><input type='text' class='form-control' name='database_name[]' placeholder='database_name' value='{$v}' required></div><div class='col-1 pt-1'><a href='#me' class='remove_privilege btn btn-danger btn-sm pull-right' data-toggle='tooltip' title='Delete Page'><i class='fa fa-times'></i></a></div></div></li>";
						}
					}
				}
				?>
				</ol>
				
				
				
				
				
				
				<p id="privileges_list_locator"></p>
				
					</div><div class="card-footer text-right">
					  <div class="d-flex">
						<button type="submit" name="update_privileges" class="btn btn-primary ml-auto">Update privileges</button>
					  </div>
					</div>
					<?php 
						echo "<input type=\"hidden\" name=\"".csrf_token()."\" value=\"".$siteGuard->csrf."\" readonly/>";
					?>
				</form>
			</div>
		</div>
	</div>
</div>