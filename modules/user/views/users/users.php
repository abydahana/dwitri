
	<?php 
		$totSegments = $this->uri->total_segments();
		$uriSegments = $this->uri->segment($totSegments);
		if(!is_numeric($uriSegments)){
			$offset = 0;
		} else if(is_numeric($uriSegments)){
			$offset = $this->uri->segment($totSegments);
		}
		$limit = 12;
	?>
	
	<div class="bg-info">
		<div class="container first-child">
			<div class="row">
				<div class="col-sm-7 col-sm-offset-1 hidden-xs">
					<h2><i class="fa fa-users"></i> &nbsp; <?php echo phrase('users'); ?></h2>
				</div>
				<div class="col-sm-3">
					<form class="form-horizontal" action="<?php echo base_url('users'); ?>" method="post">
						<div class="input-group">
							<input type="text" class="form-control input-lg" name="query" placeholder="<?php echo phrase('search_user'); ?>" />
							<span class="input-group-btn">
								<button type="submit" class="btn btn-lg btn-success nomargin"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-md-offset-1 hidden-xs hidden-sm sticky">
			
				<?php echo ($this->input->is_ajax_request() ? $this->load->view('dashboard_navigation') : $template['partials']['navigation']); ?>
				
			</div>
			<div class="col-md-7 sticky">
				<table class="table table-hover">
					<tr>
						<th>
							<?php echo phrase('username'); ?>
						</th>
						<th class="hidden-xs">
							<?php echo phrase('full_name'); ?>
						</th>
						<th class="hidden-xs">
							<?php echo phrase('last_login'); ?>
						</th>
						<th class="hidden-xs">
							<?php echo phrase('level'); ?>
						</th>
						<th class="text-right col-xs-4">
							<?php echo phrase('action'); ?>
						</th>
					</tr>
					
					<?php
						$n = 1;
						$users = listUsers(null, $limit, $offset);
						if($users)
						{
							foreach($users as $c)
							{
								echo '
									<tr id="user' . $c['userID'] . '">
										<td>
											<a href="' . base_url($c['userName']) . '" target="_blank">' . truncate($c['userName'], 50) . '</a>
										</td>
										<td class="hidden-xs">
											<a href="' . base_url($c['userName']) . '" target="_blank">' . truncate($c['full_name'], 50) . '</a>
										</td>
										<td class="hidden-xs">
											' . $c['last_login'] . '
										</td>
										<td class="hidden-xs">
											' . ($c['level'] == 1 ? '<b class="text-primary">' . phrase('administrator') . '</b>' : ($c['level'] == 2 ? '<b class="text-success">' . phrase('moderator') . '</b>' : phrase('contributor'))) . '
										</td>
										<td class="text-right col-xs-4">
											<div class="btn-group">
												<a class="btn btn-default btn-sm newPost" href="' . base_url('user/users/edit/' . $c['userName']) . '" data-push="tooltip" data-placement="top" title="' . phrase('edit_user') . '"><i class="btn-icon-only fa fa-edit"> </i></a>
												<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/users/remove/' . $c['userID']) . '\', \'user' . $c['userID'] . '\')" data-push="tooltip" data-placement="top" title="' . phrase('remove') . '"><i class="btn-icon-only fa fa-times"> </i></a>
											</div>
										</td>
									</tr>
								';
							}
						}
					?>
					
				</table>
				
				<hr/>
				<div class="row">
					<div class="col-sm-12 text-center">
					
						<?php echo generatePagination('users', null, null, 'user', $limit, $offset); ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>