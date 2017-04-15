
	<?php foreach($post as $page): ?>
	
	<script src="<?php echo base_url('themes/' . $this->settings['theme'] . '/js/mediaplayer/mediaelement-and-player.min.js'); ?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('themes/' . $this->settings['theme'] . '/js/mediaplayer/mediaelementplayer.min.css'); ?>" />
	
	<?php if(!isset($modal)){ ?>
		<div class="jumbotron bg-dark text-center first-child">
			<div class="container">
				<div class="row">
					<div class="col-md-10 h-600px col-sm-offset-1 nomargin-xs nopadding-xs ocdc2475">
						<video id="player">
							<source type="application/x-mpegURL" src="<?php echo $page['tvURL']; ?>" />
						</video>
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
											<br />
											<small class="text-muted">
											<i class="fa fa-newspaper-o"></i> <?php echo (countPosts('posts', $page['contributor']) + countPosts('snapshots', $page['contributor'])); ?>
											/ <i class="fa fa-users"></i> <?php echo getUserFollowers('followers', $page['contributor']); ?>
											/ <i class="fa fa-clock-o"></i> <?php echo time_since($page['timestamp']); ?>
											/ <i class="fa fa-eye"></i> <?php echo $page['visits_count']; ?></small>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
									
											<?php
												echo special_parse($page['tvContent']);
											?>
											
										</div>
									</div>
									<div class="row" id="appendComment">
										<div class="col-sm-12">
											<div class="btn-group btn-group-justified">
												<a href="javascript:void(0)" class="btn btn-default"><i class="fa fa-comments"></i> <span class="comments-count-tv<?php echo $page['tvID']; ?>"><?php echo countComments('tv', $page['tvID']); ?></span> <span class="hidden-xs"><?php echo phrase('comments'); ?></span></a>
												<a class="like like-tv<?php echo $page['tvID']; ?> btn btn-default<?php echo (is_userLike('tv', $page['tvID']) ? ' active' : ''); ?>" href="<?php echo base_url('user/like/tv/' . $page['tvID']); ?>" data-id="tv<?php echo $page['tvID']; ?>"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count"><?php echo countLikes('tv', $page['tvID']); ?></span> <span class="hidden-xs"><?php echo phrase('likes'); ?></span></a>
												<a href="<?php echo base_url('user/repost/tv/' . $page['tvID']); ?>" class="btn btn-default repost" data-id="<?php echo $page['tvID']; ?>"><i class="fa fa-retweet"></i> <span id="reposts-count<?php echo $page['tvID']; ?>"><?php echo countReposts('tv', $page['tvID']); ?></span> <span class="hidden-xs"><?php echo phrase('reposts'); ?></span></a>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
												
											<?php
												echo getComments('tv', $page['tvID']);
											?>
												
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 hidden-xs hidden-sm sticky">
							<h4><i class="fa fa-ellipsis-v"></i> <?php echo phrase('another_channels'); ?></h4>
							<?php echo widget_activeTV(4, 0, $page['tvID']); ?>
							
							<hr />
							<div class="row hidden-xs hidden-sm">
								<div class="col-sm-6 sticky">
									<h4><i class="fa fa-tags"></i> &nbsp; <?php echo phrase('trending'); ?></h4>
									
									<?php echo widget_hashTags(true, 10); ?>
									
								</div>
								<div class="col-sm-6 sticky">
									<h4><i class="fa fa-certificate"></i> &nbsp; <?php echo phrase('top_contributors'); ?></h4>
								
									<?php echo widget_topContributors(); ?>
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
						<div class="middle ocdc2475">
							<span class="img text-center">
								<video id="player">
									<source type="application/x-mpegURL" src="<?php echo $page['tvURL']; ?>" />
								</video>
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="h-600px relative" id="slimScroll">
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
							<div class="col-sm-12">
								<div class="btn-group btn-group-justified">
									<a href="javascript:void(0)" class="btn btn-default"><i class="fa fa-comments"></i> <span class="comments-count-tv<?php echo $page['tvID']; ?>"><?php echo countComments('tv', $page['tvID']); ?></span> <span class="hidden-xs"><?php echo phrase('comments'); ?></span></a>
									<a class="like like-tv<?php echo $page['tvID']; ?> btn btn-default<?php echo (is_userLike('tv', $page['tvID']) ? ' active' : ''); ?>" href="<?php echo base_url('user/like/tv/' . $page['tvID']); ?>" data-id="tv<?php echo $page['tvID']; ?>"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count"><?php echo countLikes('tv', $page['tvID']); ?></span> <span class="hidden-xs"><?php echo phrase('likes'); ?></span></a>
									<a href="<?php echo base_url('user/repost/tv/' . $page['tvID']); ?>" class="btn btn-default repost" data-id="<?php echo $page['tvID']; ?>"><i class="fa fa-retweet"></i> <span id="reposts-count<?php echo $page['tvID']; ?>"><?php echo countReposts('tv', $page['tvID']); ?></span> <span class="hidden-xs"><?php echo phrase('reposts'); ?></span></a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
						
								<?php
									echo '<p>' . special_parse($page['tvContent']) . '</p>';
								?>
								
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
									
								<?php
									echo getComments('tv', $page['tvID']);
								?>
								
								<br />
								
							</div>
						</div>
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
							<?php echo phrase('sharing_is_caring_channel_desc'); ?>
						</p>
						<div class="pw-server-widget rounded" data-id="wid-puio7d2l"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(window).load(function(){
			$('#postShare').modal('show');
		});
	</script>
	<?php } ?>
	
	<script type="text/javascript">
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
		$('#player').mediaelementplayer({
			videoWidth: $('.ocdc2475').width(),
			videoHeight: $('.ocdc2475').height(),
			enableAutosize: true,
			startVolume: 0.8,
			/* features: ['playpause','progress','current','duration','tracks','volume','fullscreen'], */
			features: ['playpause','progress','current','duration','volume'],
			alwaysShowControls: false,
			enableKeyboard: true,
			pauseOtherPlayers: true,
			enablePluginSmoothing: true,
			success: function(player, node){
				$('.mejs-overlay-button').trigger('click');
			}
		});
	</script>
	
	<?php endforeach; ?>