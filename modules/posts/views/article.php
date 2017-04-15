
	<?php foreach($post as $page): ?>
	
	<br />
	<div class="container first-child">
		<div class="row">
			<div class="col-md-12 text-center">
				<a href="<?php echo base_url(getUsernameByID($page['contributor'])); ?>" class="ajaxLoad hoverCard"><img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($page['contributor']), 1)); ?>" alt="" class="img-circle" style="width:100px;height:100px" /></a>
				<br />
				<a href="<?php echo base_url(getUsernameByID($page['contributor'])); ?>" class="ajaxLoad hoverCard"><b><?php echo getFullNameByID($page['contributor']); ?></b> - <small>@<?php echo getUsernameByID($page['contributor']); ?></small></a>
				<br />
				<small><i class="fa fa-newspaper-o"></i> <?php echo (countPosts('posts', $page['contributor']) + countPosts('snapshots', $page['contributor'])); ?> / <i class="fa fa-users"></i> <?php echo getUserFollowers('followers', $page['contributor']); ?></small>
				<h3 class="nomargin"><?php echo $meta['title']; ?></h3>
				<p class="meta">
					<i class="fa fa-info-circle"></i> &nbsp; <?php echo time_since($page['timestamp']); ?>, <?php echo $page['visits_count'] . ' ' . phrase('readers'); ?>
				</p>
			</div>
		</div>
	</div>
	<hr />
	<div class="container">
		<div class="row">
			<div class="col-md-1 sticky hidden-xs hidden-sm">
				<div class="pw-server-widget rounded" data-id="wid-puio7d2l"></div>
			</div>
			<div class="col-md-6 nopadding-xs sticky">
				<div class="text-center-xs">
					<div class="btn-group btn-group-sm-justified">
						<a href="javascript:void(0)" class="btn btn-primary btn-sm" style="margin-top:0"><b><i class="fa fa-share"></i> <?php echo phrase('share'); ?></b></a>
						<?php if($page['contributor'] == $this->session->userdata('userID') || $this->session->userdata('user_level') == 1) { ?>
							<a href="<?php echo base_url('user/posts/edit/' . $page['postSlug']); ?>" class="btn btn-success btn-sm newPost" style="margin-top:0"><b><i class="fa fa-edit"></i> <?php echo phrase('edit'); ?></b></a>
						<?php } ?>
						<a href="javascript:void(0)" class="btn btn-danger btn-sm" style="margin-top:0"><b><i class="fa fa-ban"></i> <?php echo phrase('report'); ?></b></a>
					</div>
				</div>
				<div class="blog_article image-placeholder-sm">
					<div class="col-sm-12">
						<div class="text-justify">
						
							<?php echo special_parse($page['postContent']); ?>
							
							<?php // echo widget_randomAds(); ?>
						
						</div>
						<p>
						<?php
							if($page['tags'] != '')
							{
								$tags = explode(',', $page['tags']);
								foreach($tags as $tag)
								{
									echo '<a href="' . base_url('search/' . $tag) . '" class="ajaxLoad"><span class="badge"><i class="fa fa-tag"></i> ' . $tag . '</span></a> ';
								}
							}
							else
							{
								foreach(json_decode($page['categoryID']) as $key => $val)
								{
									echo '<a href="' . base_url('category/' . getCategorySlugByID($val)) . '" class="ajaxLoad"><span class="badge"><i class="fa fa-tag"></i> ' . getCategoryByID($val) . '</span></a> ';
								}
							}
						?>
						</p>
					</div>
					<div class="clearfix"></div>
					<?php if(getUserBio($page['contributor'])): ?>
					<hr />
					<div class="col-sm-12">
						<div class="row">
							<div class="col-xs-2">
								<a href="<?php echo base_url(getUsernameByID($page['contributor'])); ?>" class="ajaxLoad hoverCard"><img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($page['contributor']), 1)); ?>" alt="" class="rounded img-responsive" /></a>
							</div>
							<div class="col-xs-10">
								<b><i><?php echo getUserBio($page['contributor']); ?></i></b>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<hr />
					<?php endif; ?>
					
					<div id="appendComment">
						<div class="col-xs-12">
							<div class="btn-group btn-group-justified">
								<a href="javascript:void(0)" class="btn btn-default"><i class="fa fa-comments"></i> <span class="comments-count-posts<?php echo $page['postID']; ?>"><?php echo countComments('posts', $page['postID']); ?></span> <span class="hidden-xs"><?php echo phrase('comments'); ?></span></a>
								<a class="like like-posts<?php echo $page['postID']; ?> btn btn-default<?php echo (is_userLike('posts', $page['postID']) ? ' active' : ''); ?>" href="<?php echo base_url('user/like/posts/' . $page['postID']); ?>" data-id="posts<?php echo $page['postID']; ?>"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count"><?php echo countLikes('posts', $page['postID']); ?></span> <span class="hidden-xs"><?php echo phrase('likes'); ?></span></a>
								<a href="<?php echo base_url('user/repost/posts/' . $page['postID']); ?>" class="btn btn-default repost" data-id="<?php echo $page['postID']; ?>"><i class="fa fa-retweet"></i> <span id="reposts-count<?php echo $page['postID']; ?>"><?php echo countReposts('posts', $page['postID']); ?></span> <span class="hidden-xs"><?php echo phrase('reposts'); ?></span></a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-12">
					
						<?php
							echo getComments('posts', $page['postID']);
						?>
							
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="col-md-2 hidden-xs hidden-sm sticky">
				<div class="blog_article">
					<div class="row">
						<h4><i class="fa fa-sitemap"></i> &nbsp; <?php echo phrase('category'); ?></h4>
						
						<?php echo widget_sidebarCategory(); ?>
						
						<h4><i class="fa fa-tags"></i> &nbsp; <?php echo phrase('trending'); ?></h4>
								
						<?php echo widget_hashTags(true, 10); ?>
						
					</div>
				</div>
			</div>
			<div class="col-md-3 hidden-xs hidden-sm sticky">
				<div class="blog_article">
					<div class="row">
						<div class="col-sm-12 nomargin">
							<h4><i class="fa fa-ellipsis-v"></i> &nbsp; <?php echo phrase('another_articles'); ?></h4>
							
							<?php echo widget_activePosts(6, 0, $page['postID']); ?>
							
						</div>
					</div>
					<br />
					<div class="row hidden-xs hidden-sm">
						<div class="col-sm-12">
							<h4><i class="fa fa-certificate"></i> &nbsp; <?php echo phrase('top_contributors'); ?></h4>
						
							<?php echo widget_topContributors(); ?>
							
							<?php // echo widget_randomAds(); ?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php if($this->session->flashdata('success')) { ?>
		
	<div id="postShare" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal_table" style="max-width:500px">
			<div class="modal-dialog modal_cell">
				<div class="modal-content">
					<div class="modal-body rounded text-center">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h4>
							<b><?php echo phrase('sharing_is_caring'); ?></b>
						</h4>
						<p>
							<?php echo phrase('sharing_is_caring_desc'); ?>
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