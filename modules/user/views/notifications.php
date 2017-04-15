
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
					<h3 class="hidden-xs"><i class="fa fa-bell"></i> &nbsp; <?php echo phrase('notifications'); ?></h3>
				</div>
				<div class="col-sm-3 text-right">
					<a href="javascript:void(0)" onclick="confirm_modal('<?php echo base_url('user/remove_alerts'); ?>', 'all')" class="btn btn-danger btn-lg btn-block"><i class="fa fa-times"></i> &nbsp; <?php echo phrase('clear_notifications'); ?></a>
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
				<table class="table table-hover" id="listHolder">
					<tr>
						<th>
							<?php echo phrase('descriptions'); ?>
						</th>
						<th class="text-right col-xs-2">
							<?php echo phrase('action'); ?>
						</th>
					</tr>
					
					<?php
						$alerts	= getNotifications($limit, $offset);
						if($alerts)
						{
							foreach($alerts as $row)
							{
								if($row['type'] == 'comment')
								{
									$icon		= 'comments text-info';
									$actions 	= phrase('commented_on');
								}
								elseif($row['type'] == 'like')
								{
									$icon		= 'thumbs-up text-success';
									$actions 	= phrase('liking_on');
								}
								elseif($row['type'] == 'following')
								{
									$icon		= 'refresh text-warning';
									$actions 	= phrase('is_following_you');
								}
								elseif($row['type'] == 'friendship')
								{
									$icon		= 'user-plus text-warning';
									$actions 	= phrase('requesting_friendship_to_you');
								}
								elseif($row['type'] == 'confirmed')
								{
									$icon		= 'check-circle text-info';
									$actions 	= phrase('accepted_your_friend_request');
								}
								else
								{
									$icon		= 'info';
									$actions 	= null;
								}
									
								echo '
									<tr id="notify' . $row['notifyID'] . '">
										<td>
											<a href="' . $row['targetURL'] . '" class="ajaxLoad">
												<div class="row" style="margin-right:0;margin-left:0;' . ($row['status'] == 0 ? 'color:#000' : 'color:#aaa') . '">
													<div class="col-xs-3 col-sm-2">
														<div class="row-xs">
															<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['fromID']), 1)) . '" class="img-rounded img-responsive" style="margin-top:6px" alt="..." />
														</div>
													</div>
													<div class="col-xs-9 col-sm-10">
														<small>
															<b>' . getFullnameByID($row['fromID']) . '</b>
															' . $actions . ' ' . ($row['type'] == 'comment' || $row['type'] == 'like' ? '"' . truncate(getPostTitleByID($row['itemID']), 50 - strlen(getFullnameByID($row['fromID']))) . '"': '') . '<br />
															<i class="fa fa-' . $icon . '"></i> <span class="tex-muted" style="font-size:11px">' . time_since($row['timestamp']) . '</span>
														</small>
													</div>
												</div>
											</a>
										</td>
										<td class="text-right col-xs-2">
											<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove_alert/' . $row['notifyID']) . '\', \'notify' . $row['notifyID'] . '\')" data-push="tooltip" data-placement="top" title="' . phrase('remove') . '"><i class="btn-icon-only fa fa-times"> </i></a>
										</td>
									</tr>
								';
							}
						}
						else
						{
							echo '
								<tr>
									<td colspan="3">
										<div class="alert alert-danger">
											' . phrase('you_do_not_have_any_notification') . '
										</div>
									</td>
								</tr>
							';
						}
					?>
					
				</table>
				
				<hr/>
				<div class="row">
					<div class="col-sm-12 text-center">
					
						<?php echo generatePagination('notifications', null, $this->session->userdata('userID'), 'notifications', $limit, $offset); ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>