
	<?php foreach($updates as $page): ?>
	<br /><br />
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="image-placeholder">
					<div class="col-sm-12 text-center nomargin text-shadow" style="background:url(<?php echo base_url('uploads/users/covers/' . imageCheck('covers', getUserCover($page['userID']), 1)); ?>) center center no-repeat;background-size:cover;-webkit-backgroun-size:cover">
						<br />
						<a href="<?php echo base_url(getUsernameByID($page['userID'])); ?>" class="ajaxLoad hoverCard"><img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($page['userID']), 1)); ?>" alt="" class="img-circle" style="width:100px;height:100px" /></a>
						<br />
						<a href="<?php echo base_url(getUsernameByID($page['userID'])); ?>" class="ajaxLoad hoverCard"><b><?php echo getFullNameByID($page['userID']); ?></b> - <small>@<?php echo getUsernameByID($page['userID']); ?></small></a>
						<br />
						<small><i class="fa fa-newspaper-o"></i> <?php echo (countPosts('posts', $page['userID']) + countPosts('snapshots', $page['userID'])); ?> / <i class="fa fa-users"></i> <?php echo getUserFollowers('followers', $page['userID']); ?></small>
						<p class="meta">
							<i class="fa fa-info-circle"></i> &nbsp; <?php echo time_since($page['timestamp']); ?>, <?php echo $page['visits_count'] . ' ' . phrase('readers'); ?>
						</p>
					</div>
					<div class="col-sm-12">
						<div class="blog_article">
							<div class="row">
								<div class="col-md-12 text-justify">
									<blockquote>
										<?php echo special_parse($page['updateContent']); ?>
									</blockquote>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div id="appendComment">
						<div class="col-xs-12">
							<div class="btn-group btn-group-justified">
								<a href="javascript:void(0)" class="btn btn-default"><i class="fa fa-comments"></i> <span class="comments-count-updates<?php echo $page['updateID']; ?>"><?php echo countComments('updates', $page['updateID']); ?></span> <span class="hidden-xs"><?php echo phrase('comments'); ?></span></a>
								<a class="like like-updates<?php echo $page['updateID']; ?> btn btn-default<?php echo (is_userLike('updates', $page['updateID']) ? ' active' : ''); ?>" href="<?php echo base_url('user/like/updates/' . $page['updateID']); ?>" data-id="updates<?php echo $page['updateID']; ?>"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count"><?php echo countLikes('updates', $page['updateID']); ?></span> <span class="hidden-xs"><?php echo phrase('likes'); ?></span></a>
								<a href="<?php echo base_url('user/repost/updates/' . $page['updateID']); ?>" class="btn btn-default repost" data-id="<?php echo $page['updateID']; ?>"><i class="fa fa-retweet"></i> <span id="reposts-count<?php echo $page['updateID']; ?>"><?php echo countReposts('updates', $page['updateID']); ?></span> <span class="hidden-xs"><?php echo phrase('reposts'); ?></span></a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-12">
							
						<?php
							echo getComments('updates', $page['updateID']);
						?>
							
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<br /><br />

	<?php endforeach; ?>