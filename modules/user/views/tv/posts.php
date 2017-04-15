
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
					<h2><i class="fa fa-desktop"></i> &nbsp; <?php echo phrase('tv_channels'); ?></h2>
				</div>
				<div class="col-sm-3">
					<div class="col-12-xs">
						<a href="<?php echo base_url('user/tv/add'); ?>" class="btn btn-lg btn-block btn-primary newPost"><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('new_channel'); ?></a>
					</div>
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
				<div class="row grid">
					
					<?php
						$n = 1;
						$tv = getPosts('tv', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID')), null, $limit, $offset);
						if($tv)
						{
							foreach($tv as $c)
							{
								echo '
									<div class="col-sm-4 grid-item" id="tv' . $c['tvID'] . '">
										<div class="image-placeholder">
											<a href="' . base_url('tv/' . $c['tvSlug']) . '" class="ajax"><img width="100%" class="img-responsive" src="' . base_url('uploads/tv/thumbs/' . imageCheck('tv', $c['tvFile'], 1)) . '" alt="' . truncate($c['tvTitle'], 50) . '" /></a>
											<div class="col-sm-12" style="border-top:1px solid #ddd;padding-top:10px">
												<div class="btn-group btn-group-justified">
													<a class="btn btn-default btn-sm newPost" href="' . base_url('user/tv/edit/' . $c['tvSlug']) . '"><i class="btn-icon-only fa fa-edit"></i> ' . phrase('edit') . '</a>
													<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/tv/remove/' . $c['tvSlug']) . '\', \'tv' . $c['tvID'] . '\')"><i class="btn-icon-only fa fa-times"></i> ' . phrase('remove') . '</a>
												</div>
											</div>
										</div>
									</div>
								';
							}
						}
					?>
					
				</div>
				
				<hr/>
				<div class="row">
					<div class="col-sm-12 text-center">
					
						<?php echo generatePagination('tv', null, ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID')), 'user', $limit, $offset); ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
