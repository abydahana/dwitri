
	<?php foreach($openletters as $page): ?>
	
	<br />
	<div class="container first-child">
		<div class="row">
			<div class="col-md-10 col-md-offset-1 text-center">
				<a href="<?php echo base_url(getUsernameByID($page['contributor'])); ?>" class="ajaxLoad hoverCard"><img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($page['contributor']), 1)); ?>" alt="" class="img-circle" style="width:100px;height:100px" /></a>
				<br />
				<a href="<?php echo base_url(getUsernameByID($page['contributor'])); ?>" class="ajaxLoad hoverCard"><b><?php echo getFullNameByID($page['contributor']); ?></b> - <small>@<?php echo getUsernameByID($page['contributor']); ?></small></a>
				<br />
				<small><i class="fa fa-newspaper-o"></i> <?php echo (countPosts('posts', $page['contributor']) + countPosts('snapshots', $page['contributor'])); ?> / <i class="fa fa-users"></i> <?php echo getUserFollowers('followers', $page['contributor']); ?></small>
				<h3 class="nomargin"><?php echo $page['title']; ?></h3>
				<p class="meta">
					<i class="fa fa-info-circle"></i> &nbsp; <?php echo time_since($page['timestamp']); ?>, <?php echo $page['visits_count'] . ' ' . phrase('readers'); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="image-placeholder-sm">
					<div class="col-sm-12">
						<?php if($page['contributor'] == $this->session->userdata('userID') || $this->session->userdata('user_level') == 1) { ?>
						<a href="<?php echo base_url('user/openletters/edit/' . $page['slug']); ?>" class="btn btn-success btn-sm pull-right nomargin nopadding newPost"><b><i class="fa fa-edit"></i> <?php echo phrase('edit'); ?></b></a>
						<?php } ?>
						
						<div class="blog_article">
							<div class="row">
								<div class="col-sm-9">
									<div class="row nomargin">
										<div class="col-xs-5 nopadding">
											<b><?php echo phrase('subject'); ?></b>
										</div>
										<div class="col-xs-7 nopadding">
											<b><?php echo $page['title']; ?></b>
										</div>
									</div>
									<div class="row nomargin">
										<div class="col-xs-5 nopadding">
											<b><?php echo phrase('aimed_to'); ?></b>
										</div>
										<div class="col-xs-7 nopadding">
											<b><?php echo $page['targetName']; ?></b>
											<p>
												<?php echo $page['targetDetails']; ?>
											</p>
										</div>
									</div>
								</div>
								<div class="col-sm-3 text-right">
									<br />
									<?php echo date('d, F Y', $page['timestamp']); ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-justify">
									
									<?php echo special_parse($page['content']); ?>
									
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 col-md-offset-8">
									<b>
									<?php echo phrase('sincerely'); ?>,
									<br />
									<?php echo getFullnameByID($page['contributor']); ?>
									</b>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div id="appendComment">
						<div class="col-xs-12">
							<div class="btn-group btn-group-justified">
								<a href="javascript:void(0)" class="btn btn-default"><i class="fa fa-comments"></i> <span class="comments-count-openletters<?php echo $page['letterID']; ?>"><?php echo countComments('openletters', $page['letterID']); ?></span> <span class="hidden-xs"><?php echo phrase('comments'); ?></span></a>
								<a class="like like-openletters<?php echo $page['letterID']; ?> btn btn-default<?php echo (is_userLike('openletters', $page['letterID']) ? ' active' : ''); ?>" href="<?php echo base_url('user/like/openletters/' . $page['letterID']); ?>" data-id="openletters<?php echo $page['letterID']; ?>"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count"><?php echo countLikes('openletters', $page['letterID']); ?></span> <span class="hidden-xs"><?php echo phrase('likes'); ?></span></a>
								<a href="<?php echo base_url('user/repost/openletters/' . $page['letterID']); ?>" class="btn btn-default repost" data-id="<?php echo $page['letterID']; ?>"><i class="fa fa-retweet"></i> <span id="reposts-count<?php echo $page['letterID']; ?>"><?php echo countReposts('openletters', $page['letterID']); ?></span> <span class="hidden-xs"><?php echo phrase('reposts'); ?></span></a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-12">
							
						<?php
							echo getComments('openletters', $page['letterID']);
						?>
							
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h4><i class="fa fa-ellipsis-v"></i> &nbsp; <?php echo phrase('another_open_letters'); ?></h4>
				
				<?php echo widget_activeOpenletters(6, 0, $page['letterID']); ?>
				
			</div>
		</div>
	</div>
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
	</script>

	<?php endforeach; ?>