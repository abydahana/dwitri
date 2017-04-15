
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
					<h2><i class="fa fa-paperclip"></i> &nbsp; <?php echo phrase('open_letters'); ?></h2>
				</div>
				<div class="col-md-3">
					<a href="<?php echo base_url('user/openletters/add'); ?>" class="btn btn-lg btn-block btn-primary newPost"><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('write_letter'); ?></a>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-2 hidden-xs hidden-sm sticky">
				<h4><i class="fa fa-certificate"></i> &nbsp; <?php echo phrase('top_contributors'); ?></h4>
			
				<?php echo widget_topContributors(12); ?>
				
			</div>
			<div class="col-md-5 nomargin-xs sticky">
				<?php
					$posts 	= getPosts('openletters', null, null, $limit, $offset);
					$n		= 1;
					foreach($posts as $c)
					{
						if($n == 7)
						{
							echo '
								<div class="letter-placeholder">
								
									' . widget_randomAds() . '
									
								</div>
							';
						}
						
						echo '
							<div class="letter-placeholder">
								<div class="blog_article">
									<div class="row">
										<div class="col-sm-12 nomargin">
											<b>
												<div class="row">
													<div class="col-sm-3 nopadding">
														' . phrase('subject') . '
													</div>
													<div class="col-sm-9 nopadding">
														' . $c['title'] . '
													</div>
												</div>
												<div class="row">
													<div class="col-sm-3">
														' . phrase('aimed_to') . '
													</div>
													<div class="col-sm-9">
														' . $c['targetName'] . '
													</div>
												</div>
											</b>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-7">
											<div class="row">
												<div class="col-sm-12">
													' . truncate($c['content'], 160) . '
												</div>
											</div>
										</div>
										<div class="col-sm-5">
											<div class="row">
												<a href="' . base_url(getUsernameByID($c['contributor'])) . '" class="ajaxLoad hoverCard">
													<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($c['contributor']), 1)) . '" class="rounded col-xs-4" alt="..." />
												</a>
												<div class="col-xs-8">
													<a href="' . base_url(getUsernameByID($c['contributor'])) . '" class="ajaxLoad hoverCard"><b>' . getFullNameByID($c['contributor']) . '</b>
													<br />
													<small class="text-muted">@' . getUsernameByID($c['contributor']) . '</small></a>
													<br />
													<small class="text-muted"><i class="fa fa-newspaper-o"></i> ' . (countPosts('posts', $c['contributor']) + countPosts('snapshots', $c['contributor'])) . ' / <i class="fa fa-users"></i> ' . getUserFollowers('followers', $c['contributor']) . '</small>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<a href="' . base_url('openletters/' . $c['slug']) . '" class="ajaxLoad btn btn-default btn-block"><i class="fa fa-envelope"></i> ' . phrase('read_letter') . '</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						';
						
						$n++;
					}
				?>
				
				<div class="row">
					<div class="col-sm-12 text-center">
					
						<?php echo generatePagination('openletters', null, null, null, $limit, $offset); ?>
						
					</div>
				</div>
					
			</div>
			<div class="col-md-2 hidden-xs hidden-sm sticky">
				<h4><i class="fa fa-tags"></i> &nbsp; <?php echo phrase('trending'); ?></h4>
						
				<?php echo widget_hashTags(true, 10); ?>
			</div>
			<div class="col-md-3 hidden-xs hidden-sm sticky">
				<h4><i class="fa fa-clock-o"></i> &nbsp; <?php echo phrase('latest_articles'); ?></h4>
				
				<?php echo widget_sidebarNews(); ?>
				
				<?php // echo widget_randomAds('small'); ?>
				
			</div>
		</div>
	</div>