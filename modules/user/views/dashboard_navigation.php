				<div class="row">
					<div class="col-sm-12">
						<div class="list-group">
						
							<a href="<?php echo base_url('user/dashboard'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == '' || $this->uri->segment(2) == 'dashboard') echo ' active'; ?>"><i class="fa fa-dashboard"></i> &nbsp; <?php echo phrase('dashboard'); ?></a>
							
							<a href="<?php echo base_url('user/posts'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'posts') echo ' active'; ?>"><i class="fa fa-newspaper-o"></i> &nbsp; <?php echo phrase('posts'); ?> &nbsp; <?php echo (countPosts('posts', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))) > 0 ? '<span class="badge">' . countPosts('posts', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))) . '</span>' : '') ?></a>
							
							<a href="<?php echo base_url('user/snapshots'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'snapshots') echo ' active'; ?>"><i class="fa fa-image"></i> &nbsp; <?php echo phrase('snapshots'); ?> &nbsp; <?php echo (countPosts('snapshots', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))) > 0 ? '<span class="badge">' . countPosts('snapshots', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))) . '</span>' : '') ?></a>
							
							<a href="<?php echo base_url('user/openletters'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'openletters') echo ' active'; ?>"><i class="fa fa-paperclip"></i> &nbsp; <?php echo phrase('open_letters'); ?> &nbsp; <?php echo (countPosts('openletters', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))) > 0 ? '<span class="badge">' . countPosts('openletters', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))) . '</span>' : '') ?></a>
							
							<a href="<?php echo base_url('user/tv'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'tv') echo ' active'; ?>"><i class="fa fa-desktop"></i> &nbsp; <?php echo phrase('tv_channels'); ?> &nbsp; <?php echo (countPosts('tv', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))) > 0 ? '<span class="badge">' . countPosts('tv', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))) . '</span>' : '') ?></a>
							
							<?php if($this->session->userdata('user_level') == 1) : ?>
						</div>
						<h4><?php echo phrase('administration'); ?></h4>
						<div class="list-group">
							<a href="<?php echo base_url('user/pages'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'pages') echo ' active'; ?>"><i class="fa fa-files-o"></i> &nbsp; <?php echo phrase('manage_pages'); ?></a>
							
							<a href="<?php echo base_url('user/categories'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'categories') echo ' active'; ?>"><i class="fa fa-sitemap"></i> &nbsp; <?php echo phrase('post_categories'); ?> &nbsp; <?php echo (countPosts('categories', null) > 0 ? '<span class="badge">' . countPosts('categories', null) . '</span>' : '') ?></a>
							
							<a href="<?php echo base_url('user/users'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'users') echo ' active'; ?>"><i class="fa fa-users"></i> &nbsp; <?php echo phrase('users'); ?> &nbsp; <?php echo (countPosts('users', null) > 0 ? '<span class="badge">' . countPosts('users', null) . '</span>' : '') ?></a>
							
							<a href="<?php echo base_url('user/settings'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'settings') echo ' active'; ?>"><i class="fa fa-cogs"></i> &nbsp; <?php echo phrase('global_settings'); ?></a>
							
							<a href="<?php echo base_url('user/translate'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'translate') echo ' active'; ?>"><i class="fa fa-language"></i> &nbsp; <?php echo phrase('translate'); ?></a>
							
							<?php endif; ?>
							
							<a href="<?php echo base_url('user/edit_profile'); ?>" class="ajaxLoad list-group-item<?php if($this->uri->segment(2) == 'edit_profile') echo ' active'; ?>"><i class="fa fa-cogs"></i> &nbsp; <?php echo phrase('edit_profile'); ?></a>
							
							<a href="<?php echo base_url('user/logout'); ?>" class="ajaxLoad list-group-item"><i class="fa fa-sign-out"></i> &nbsp; <?php echo phrase('logout'); ?></a>
							
						</div>
					</div>
				</div>