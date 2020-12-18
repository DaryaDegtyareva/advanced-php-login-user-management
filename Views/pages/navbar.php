<?php $request = \Config\Services::request(); $page = $request->uri->getSegment(1); ?>
<body class="">
    <div class="page">
      <div class="flex-fill">
		<?php if($siteGuard->get_impersonate()) { ?><div class="alert alert-danger">
			<i class='fa fa-user-secret'></i> <b>Impersonate Mode!</b> You're logged in as (<b><?php echo $current_user->name; ?></b> @<?php echo $current_user->username; ?>) - access level (<?php $group = $groupModel->get_specific_id($current_user->prvlg_group); echo $group->name; ?>) <a href='<?php echo base_url("index/deimpersonate/"); ?>' class='btn btn-outline-danger btn-sm pull-right'>Exit Impersonate Mode</a>
		</div><?php } ?>
		
        <div class="header">
          <div class="container">
            <div class="d-flex">
              <a class="header-brand" href="<?php echo base_url("index"); ?>">
				<?php if(isset($siteGuard->settings['logo']) && is_numeric($siteGuard->settings['logo'])) {
					$fileModel = new \App\Models\SiteGuardFile();
					$image_path = $fileModel->image_path($siteGuard->settings['logo']);
					$logo_url = base_url()."/".$image_path;
					$logo_url_path = FCPATH."/".$image_path;
					if (!file_exists($logo_url_path)) {
						$logo_url = base_url()."/SiteGuard/images/logo.png"; 
					}
				} else {
					$logo_url = base_url()."/SiteGuard/images/logo.png"; 
				} ?>
                <img src="<?php echo $logo_url; ?>" class="header-brand-img" alt="SiteGuard ® logo">
              </a>
              <div class="d-flex order-lg-2 ml-auto">
                
                <div class="dropdown">
                  <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                    <span class="avatar" style="background-image: url(<?php echo $userModel->get_avatar($current_user->id); ?>)"></span>
                    <span class="ml-2 d-none d-lg-block">
                      <span class="text-default"><?php echo $current_user->name; ?></span>
                      <small class="text-muted d-block mt-1"><?php $prvlg_group = $groupModel->get_specific_id($current_user->prvlg_group); echo $prvlg_group->name; ?></small>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <?php if($siteGuard->privilege("profile.read")) { ?><a class="dropdown-item" href="<?php echo base_url("profile"); ?>">
                      <i class="dropdown-icon fe fe-user"></i> Profile
                    </a><?php } ?>
                    <?php if($siteGuard->privilege("settings.read")) { ?><a class="dropdown-item" href="<?php echo base_url("settings"); ?>">
                      <i class="dropdown-icon fe fe-settings"></i> Settings
                    </a><?php } ?>
                    
                    <div class="dropdown-divider"></div>
                    <?php if($siteGuard->only_for('1')) { ?><a class="dropdown-item" href="<?php echo base_url("documentation"); ?>">
                      <i class="dropdown-icon fe fe-help-circle"></i> Need help?
                    </a><?php } ?>
                    <a class="dropdown-item" href="<?php echo base_url("logout"); ?>">
                      <i class="dropdown-icon fe fe-log-out"></i> Sign out
                    </a>
                  </div>
                </div>
              </div>
              <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
              </a>
            </div>
          </div>
        </div>
        <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-3 ml-auto">
                <form class="input-icon my-3 my-lg-0" action="<?php echo base_url("users"); ?>" method="GET">
                  <input type="search" name="name" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
                  <div class="input-icon-addon">
                    <i class="fe fe-search"></i>
                  </div>
                </form>
              </div>
              <div class="col-lg order-lg-first">
			  <?php 
				$miscModel = new \App\Models\MiscFunction();
				$navigation = $miscModel->get_function("navigation");
				$navigation_pages = unserialize(base64_decode($navigation->value));
			  ?>
			  
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                  <?php if(is_array($navigation_pages) && !empty($navigation_pages) ) {
				  foreach($navigation_pages as $nav) {
					$page_name = str_replace('_' , ' ' , $nav['name']);
					$page_name = str_replace('-' , ' ' , $page_name);
					$page_name = str_replace('section' , ' ' , $page_name);
					$page_name = ucwords($page_name);
					$visibility = 'visible';
					if($nav['type'] == 'section') {
						$dropdown = 'dropdown';
						$link = "#me";
						$page_array = array();
						$active = '';
						if(isset($nav['children'][0]) && is_array($nav['children'][0])) {
							foreach($nav['children'][0] as $child) {
								$page_array[] = $child['name'];
							}
						} if(in_array($page, $page_array)) {
							$active = 'active';
						}
						if(empty($page_array)) {
							$visibility = 'hidden';
						}
						$privilege = $nav['name'];
						$toggle = " data-toggle='dropdown' ";
					} else {
						$dropdown = '';
						$link = base_url($nav['name']);
						if($page == $nav['name']) { $active = 'active'; } else { $active = ''; }
						$privilege = $nav['name'].'.read';
						$toggle = '';
						$page_controller = ucwords($nav['name']);
						if (!file_exists(APPPATH.'/Controllers/'.$page_controller . ".php") && $nav['name'] != 'index' ) {
							$visibility = 'hidden';
						}
						
					}
					if(isset($nav['icon'])) {
						$icon = $nav['icon'];
					} else {
						$icon = "code";
					}
					if($siteGuard->privilege($privilege) && $visibility == 'visible' ) {
						echo "<li class='nav-item {$dropdown}'><a href='{$link}' class='nav-link {$active}' {$toggle} ><i class='fe fe-{$icon}'></i> {$page_name}</a>";
						if($nav['type'] == 'section' && isset($nav['children'][0]) && is_array($nav['children'][0]) && !empty($nav['children'][0])) {
							echo '<div class="dropdown-menu dropdown-menu-arrow">';
							foreach($nav['children'][0] as $child) {
								if($child['type'] == 'page') {
									$page_name = str_replace('_' , ' ' , $child['name']);
									$page_name = str_replace('-' , ' ' , $page_name);
									$page_name = ucwords($page_name);
									$link = base_url($child['name']);
									$visibility = "visible";
									$page_controller = ucwords($child['name']);
									if (!file_exists(APPPATH.'/Controllers/'.$page_controller . ".php")) {
										$visibility = 'hidden';
									}
									if($siteGuard->privilege($child['name'].'.read') && $visibility == 'visible' ) {
										echo "<a href='{$link}' class='dropdown-item '>{$page_name}</a>";
									}
								}
							}
							echo '</div>';
						}
						echo "</li>";
					}
                  }} ?>
                </ul>
				
				
              </div>
            </div>
          </div>
        </div>