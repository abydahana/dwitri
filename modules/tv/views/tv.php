
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
				<div class="col-md-9 hidden-xs">
					<h2><i class="fa fa-desktop"></i> &nbsp; <?php echo phrase('tv_channels'); ?></h2>
				</div>
				<div class="col-md-3">
					<?php if(!$this->session->userdata('loggedIn')) { ?>
						<a href="#login" class="btn btn-lg btn-primary btn-block" data-toggle="modal"><i class="fa fa-desktop"></i> &nbsp; <?php echo phrase('publish_channel'); ?></a>
					<?php } else { ?>
						<a href="<?php echo base_url('user/tv/add'); ?>" class="btn btn-lg btn-primary btn-block newPost"><i class="fa fa-desktop"></i> &nbsp; <?php echo phrase('publish_channel'); ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="row grid">
					
					<?php
						$n = 1;
						$tv = getPosts('tv', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID')), null, $limit, $offset);
						if($tv)
						{
							foreach($tv as $c)
							{
								echo '
									<div class="col-md-4 col-sm-6 col-xs-12 grid-item" id="tv' . $c['tvID'] . '">
										<div class="image-placeholder">
											<a href="' . base_url('tv/' . $c['tvSlug']) . '" class="ajax bg-dark"><img width="100%" class="img-responsive" src="' . base_url('uploads/tv/thumbs/' . imageCheck('tv', $c['tvFile'], 1)) . '" alt="' . truncate($c['tvTitle'], 20) . '" /></a>
											<div class="col-sm-12 nomargin" style="border-top:1px solid #ddd">
												<h3 style="margin-top:10px">' . truncate($c['tvTitle'], 20) . ' <a href="' . base_url('tv/' . $c['tvSlug']) . '" class="ajaxLoad btn btn-primary btn-sm pull-right"><i class="fa fa-eye"></i> ' . phrase('watch') . '</a></h3>
												<p>
													' . truncate($c['tvContent'], 60) . '
												</p>
											</div>
										</div>
									</div>
								';
							}
						}
					?>
					
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
					
						<?php echo generatePagination('tv', null, ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID')), null, $limit, $offset); ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
