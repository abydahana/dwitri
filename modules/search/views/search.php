
	<?php
		$totSegments = $this->uri->total_segments();
		$uriSegments = $this->uri->segment($totSegments);
		if(!is_numeric($uriSegments)){
			$offset = 0;
		} else if(is_numeric($uriSegments)){
			$offset = $this->uri->segment($totSegments);
		}
		$limit 	= 12;
		if($keywords)
		{
			$count 	= getSearchCount($keywords);
			$search	= getSearch($keywords, $limit, $offset);
		}
		else
		{
			$count	= null;
		}
	?>
	
	<div class="jumbotron bg-primary">
		<div class="container first-child">
			<div class="row">
				<div class="col-md-6 col-sm-offset-3">
					<form class="form-horizontal submitForm" action="<?php echo base_url('search'); ?>" method="post" data-save="<?php echo phrase('search'); ?>" data-saving="<?php echo phrase('searching'); ?>" data-alert="<?php echo phrase('unable_to_submit_inquiry'); ?>">
						<div class="input-group">
							<input type="text" class="form-control input-lg" name="query" placeholder="<?php echo phrase('type_keywords_and_hit_search'); ?>"<?php echo ($keywords != null ? ' value="' . $keywords . '"' : ''); ?> />
							<span class="input-group-btn">
								<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
								<button type="submit" class="btn btn-lg btn-success nomargin submitBtn"><i class="fa fa-search"></i> <?php echo phrase('search'); ?></button>
							</span>
						</div>
						<div class="form-group">
							<div class="col-sm-12 statusHolder">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<?php if($keywords): ?>
				<div class="alert alert-<?php echo ($count > 0 ? 'info' : 'danger'); ?>"><?php echo phrase('showing'); ?> <b><?php echo $offset . ' - ' . ($count > $limit && $limit+$offset < $count ? $limit+$offset : $count) . ' dari ' . $count; ?></b> <?php echo phrase('results_for_keywords'); ?> <b>"<?php echo $keywords; ?>"</b></div>
				<?php endif; ?>
				
				<?php
					if($keywords)
					{
						$n	= 1;
						foreach($search as $row)
						{
							if($n == 7)
							{
								echo'
									<br />
									<div class="image-placeholder">
										<div class="col-sm-12">
										
											' . widget_randomAds() . '
											
										</div>
									</div>
								';
							}
							if(isset($row['postID']))
							{
								$post_tag = '';
								if($row['tags'] != '')
								{
									$tags = explode(',', $row['tags']);
									foreach($tags as $tag)
									{
										$post_tag = '<a href="' . base_url('search/' . $tag) . '" class="ajaxLoad"><span class="badge"><i class="fa fa-tag"></i> ' . truncate($tag, 12) . '</span></a> ';
									}
								}
								else
								{
									foreach(json_decode($row['categoryID']) as $key => $val)
									{
										$post_tag = '<a href="' . base_url('category/' . getCategorySlugByID($val)) . '" class="ajaxLoad"><span class="badge"><i class="fa fa-tag"></i> ' . truncate(getCategoryByID($val), 12) . '</span></a> ';
									}
								}
								echo '
									<br />
									<div class="image-placeholder">
										<div class="col-sm-12 nomargin">
											<div class="row">
												<div class="col-xs-2 col-md-1">
													<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['contributor']), 1)) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
												</div>
												<div class="col-xs-10 col-md-11">
													<a href="' . base_url(getUsernameByID($row['contributor'])) . '" class="ajaxLoad hoverCard">
														<b>' . getFullnameByID($row['contributor']) . '</b>
													</a>
													<br />
													<small class="text-muted">' . time_since($row['timestamp']) . '</small>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-9 col-sm-8 nomargin">
													<a href="' . base_url('posts/' . $row['postSlug']) . '" class="ajaxLoad"><h4>'.truncate($row['postTitle'], 80).'</h4></a>
													<p class="hidden-xs">'.truncate($row['postExcerpt'], 80).'</p>
													<p class="meta hidden-xs">
														<b><i class="fa fa-comments"></i> '.countComments('posts', $row['postID']).' &nbsp; <i class="fa fa-thumbs-up"></i> '.countLikes('posts', $row['postID']).' &nbsp; <i class="fa fa-eye"></i> '.$row['visits_count'].' <span class="badge pull-right">' . $post_tag . '</span></b>
													</p>
												</div>
												<div class="col-xs-3 col-sm-4">
													<a href="' . base_url('posts/' . $row['postSlug']) . '" class="ajaxLoad"><img class="img-responsive img-rounded" src="' . getFeaturedImage($row['postID'], 1) . '" alt="'.truncate($row['postTitle'], 80).'"/></a>
												</div>
											</div>
										</div>
									</div>
								';
							}
							elseif(isset($row['snapshotID']))
							{
								echo '
									<br />
									<div class="image-placeholder">
										<div class="col-sm-12 nomargin">
											<div class="row">
												<div class="col-xs-2 col-md-1">
													<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['contributor']), 1)) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
												</div>
												<div class="col-xs-10 col-md-11">
													<a href="' . base_url(getUsernameByID($row['contributor'])) . '" class="ajaxLoad hoverCard">
														<b>' . getFullnameByID($row['contributor']) . '</b>
													</a>
													<br />
													<small class="text-muted">' . time_since($row['timestamp']) . '</small>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="row">
														<a href="' . base_url('snapshots/' . $row['snapshotSlug']).'" class="ajax relative" style="display:block">
															' . (strtolower(substr($row['snapshotFile'], -3)) == 'gif' ? '<span class="gif_play"></span>' : '') . '
															<img width="100%" class="img-responsive" src="' . base_url('uploads/snapshots/thumbs/' . imageCheck('snapshots', $row['snapshotFile'], 1)).'" alt="'.truncate($row['snapshotContent'], 80).'"/>
														</a>
													</div>
													<br />
													<p>
														' . special_parse(truncate($row['snapshotContent'], 160)) . '
													</p>
													<div class="btn-group btn-group-justified">
														<a href="' . base_url('snapshots/' . $row['snapshotSlug']).'" class="btn btn-default ajax"><i class="fa fa-comments"></i> <span id="comments-count-snapshots' . $row['snapshotID'] . '">' . countComments('snapshots', $row['snapshotID']) . '</span><span class="hidden-xs"> ' . phrase('comments') . '</span></a>
														<a class="like like-snapshots' . $row['snapshotID'] . ' btn btn-default' . (is_userLike('snapshots', $row['snapshotID']) ? ' active' : '') . '" href="' . base_url('user/like/snapshots/' . $row['snapshotID']) . '" data-id="snapshots' . $row['snapshotID'] . '"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count">' . countLikes('snapshots', $row['snapshotID']) . '</span><span class="hidden-xs"> ' . phrase('likes') . '</span></a>
														<a href="' . base_url('user/repost/snapshots/' . $row['snapshotID']) . '" class="btn btn-default repost" data-id="' . $row['snapshotID'] . '"><i class="fa fa-retweet"></i> <span id="reposts-count' . $row['snapshotID'] . '">' . countReposts('snapshots', $row['snapshotID']) . '</span><span class="hidden-xs"> ' . phrase('reposts') . '</span></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								';
							}
							
							$n++;
						}
					}
				?>
				
				<div class="row">
					<div class="col-sm-12 text-center">
					
						<?php
							if($keywords)
							{
								echo generatePagination('search', $keywords, null, null, $limit, $offset);
							}
						?>
						
					</div>
				</div>
			</div>
		</div>
	</div>