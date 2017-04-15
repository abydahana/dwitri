
	<?php foreach($post as $page): ?>
	<?php if(!isset($modal)){ ?>
	
		<div class="jumbotron bg-dark text-center first-child">
			<div class="container">
				<div class="row">
					<div class="col-md-10 col-sm-offset-1 nomargin-xs nopadding-xs">
						<img src="<?php echo base_url('uploads/snapshots/' . imageCheck('snapshots', $page['snapshotFile'], 1)); ?>" alt="<?php echo $page['snapshotFile']; ?>" class="img-responsive rounded-sm" />
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="row">
						<div class="col-sm-6 nomargin-xs nopadding-xs sticky">
							<div class="image-placeholder-sm">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-xs-2">
											<img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($page['contributor']), 1)); ?>" width="40" height="40" alt="" class="rounded" />
										</div>
										<div class="col-xs-10">
											<a href="<?php echo base_url(getUsernameByID($page['contributor'])); ?>" class="ajaxLoad hoverCard"><b><?php echo getFullNameByID($page['contributor']); ?></b> <small class="text-muted">@<?php echo getUsernameByID($page['contributor']); ?></small></a>
											<p class="text-muted">
												<i class="fa fa-newspaper-o"></i> <?php echo (countPosts('posts', $page['contributor']) + countPosts('snapshots', $page['contributor'])); ?>
												/ <i class="fa fa-users"></i> <?php echo getUserFollowers('followers', $page['contributor']); ?>
												/ <i class="fa fa-clock-o"></i> <?php echo time_since($page['timestamp']); ?>
												/ <i class="fa fa-eye"></i> <?php echo $page['visits_count']; ?>
											</p>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
									
											<?php
												echo '<p>' . special_parse($page['snapshotContent']) . '</p>';
												echo ($page['snapshotCredits'] != '' ? '<p class="text-warning"><small>' . phrase('credits_to') . ': <i>' . strip_tags($page['snapshotCredits']) . '</i></small></p>' : '');
											?>
											
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div id="appendComment">
									<div class="col-sm-12">
										<div class="btn-group btn-group-justified roundless">
											<a href="javascript:void(0)" class="btn btn-default"><i class="fa fa-comments"></i> <span class="comments-count-snapshots<?php echo $page['snapshotID']; ?>"><?php echo countComments('snapshots', $page['snapshotID']); ?></span> <span class="hidden-xs"><?php echo phrase('comments'); ?></span></a>
											<a class="like like-snapshots<?php echo $page['snapshotID']; ?> btn btn-default<?php echo (is_userLike('snapshots', $page['snapshotID']) ? ' active' : ''); ?>" href="<?php echo base_url('user/like/snapshots/' . $page['snapshotID']); ?>" data-id="snapshots<?php echo $page['snapshotID']; ?>"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count"><?php echo countLikes('snapshots', $page['snapshotID']); ?></span> <span class="hidden-xs"><?php echo phrase('likes'); ?></span></a>
											<a href="<?php echo base_url('user/repost/snapshots/' . $page['snapshotID']); ?>" class="repost btn btn-default" data-id="<?php echo $page['snapshotID']; ?>"><i class="fa fa-retweet"></i> <span class="reposts-count<?php echo $page['snapshotID']; ?>"><?php echo countReposts('snapshots', $page['snapshotID']); ?></span> <span class="hidden-xs"><?php echo phrase('reposts'); ?></span></a>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-sm-12">
									<?php
										echo getComments('snapshots', $page['snapshotID']);
									?>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col-sm-6 hidden-xs hidden-sm">
							<h4><i class="fa fa-ellipsis-v"></i> <?php echo phrase('another_snapshots'); ?></h4>
							<?php echo widget_activeSnapshots(4, 0, $page['snapshotID']); ?>
							<hr />
							<div class="row">
								<div class="col-sm-6 sticky">
									<h4><i class="fa fa-tags"></i> &nbsp; <?php echo phrase('trending'); ?></h4>
									
									<?php echo widget_hashTags(true, 10); ?>
									
								</div>
								<div class="col-sm-6 sticky">
									<div class="row">
										<h4><i class="fa fa-certificate"></i> &nbsp; <?php echo phrase('top_contributors'); ?></h4>
									
										<?php echo widget_topContributors(); ?>
										
										<hr />
									</div>
									<div class="row">
										<h4><i class="fa fa-clock-o"></i> &nbsp; <?php echo phrase('latest_articles'); ?></h4>
										
										<?php echo widget_sidebarNews(); ?>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="container">
			<div class="row">
				<div class="col-sm-8 nomargin nopadding">
					<div class="row bg-dark preloader">
						<div id="slimScroll">
							<div class="middle">
								<span class="img text-center">
									<img src="<?php echo base_url('uploads/snapshots/' . imageCheck('snapshots', $page['snapshotFile'])); ?>" alt=" " style="max-width:100%" />
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="h-600px relative" id="slimScroll_b">
						<button type="button" class="btn close" style="position:absolute;right:0" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<div class="row">
							<div class="col-xs-2 nomargin">
								<img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($page['contributor']), 1)); ?>" width="40" height="40" alt="" class="rounded" data-dismiss="modal" aria-hidden="true" />
							</div>
							<div class="col-xs-10 nomargin">
								<a href="<?php echo base_url(getUsernameByID($page['contributor'])); ?>" class="ajaxLoad hoverCard" data-dismiss="modal" aria-hidden="true"><b><?php echo getFullNameByID($page['contributor']); ?></b> <small class="text-muted">@<?php echo getUsernameByID($page['contributor']); ?></small></a>
								<br />
								<small class="text-muted">
									<i class="fa fa-newspaper-o"></i> <?php echo (countPosts('posts', $page['contributor']) + countPosts('snapshots', $page['contributor'])); ?>
									/ <i class="fa fa-users"></i> <?php echo getUserFollowers('followers', $page['contributor']); ?>
									/ <i class="fa fa-clock-o"></i> <?php echo time_since($page['timestamp']); ?>
									/ <i class="fa fa-eye"></i> <?php echo $page['visits_count']; ?></small>
							</div>
						</div>
						<div class="row" id="appendComment">
							<div class="col-xs-12">
								<div class="btn-group btn-group-justified roundless">
									<a href="javascript:void(0)" class="btn btn-default"><i class="fa fa-comments"></i> <span class="comments-count-snapshots<?php echo $page['snapshotID']; ?>"><?php echo countComments('snapshots', $page['snapshotID']); ?></span> <span class="hidden-xs"><?php echo phrase('comments'); ?></span></a>
									<a class="like like-snapshots<?php echo $page['snapshotID']; ?> btn btn-default<?php echo (is_userLike('snapshots', $page['snapshotID']) ? ' active' : ''); ?>" href="<?php echo base_url('user/like/snapshots/' . $page['snapshotID']); ?>" data-id="snapshots<?php echo $page['snapshotID']; ?>"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count"><?php echo countLikes('snapshots', $page['snapshotID']); ?></span> <span class="hidden-xs"><?php echo phrase('likes'); ?></span></a>
									<a href="<?php echo base_url('user/repost/snapshots/' . $page['snapshotID']); ?>" class="repost btn btn-default" data-id="<?php echo $page['snapshotID']; ?>"><i class="fa fa-retweet"></i> <span class="reposts-count<?php echo $page['snapshotID']; ?>"><?php echo countReposts('snapshots', $page['snapshotID']); ?></span> <span class="hidden-xs"><?php echo phrase('reposts'); ?></span></a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
						
								<?php
									echo '<p>' . special_parse($page['snapshotContent']) . '</p>';
									echo ($page['snapshotCredits'] != '' ? '<p class="text-warning"><small>' . phrase('credits_to') . ': <i>' . strip_tags($page['snapshotCredits']) . '</i></small></p>' : '');
								?>
						
							</div>
						</div>
							
						<?php
							echo getComments('snapshots', $page['snapshotID']);
						?>
						
						<br />
						
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<?php if($this->session->flashdata('success')) { ?>
		
	<div id="postShare" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal_table" style="max-width:500px">
			<div class="modal-dialog modal_cell">
				<div class="modal-content">
					<div class="modal-body rounded text-center">
						<a href="javascript:void(0)" class="btn btn-icon-only pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></a>
						<h4>
							<b><?php echo phrase('sharing_is_caring'); ?></b>
						</h4>
						<p>
							<?php echo phrase('sharing_is_caring_snapshot_desc'); ?>
						</p>
						<div class="pw-server-widget rounded" data-id="wid-puio7d2l"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<script type="text/javascript">
		<?php if($this->session->flashdata('success')) { ?>
		
		$(window).load(function(){
			$('#postShare').modal('show');
		});
		<?php } ?>
		
		(function ()
		{
			var s = document.createElement('script');
			s.type = 'text/javascript';
			s.async = true;
			s.src = ('https:' == document.location.protocol ? 'https://s' : 'http://i')
			  + '.po.st/static/v4/post-widget.js#publisherKey=1v3g03tkrnc8ghs1c154';
			var x = document.getElementsByTagName('script')[0];
			x.parentNode.insertBefore(s, x);
		})();
	</script>
	
	<?php endforeach; ?>