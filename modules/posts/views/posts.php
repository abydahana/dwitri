
	<div class="bg-info">
		<div class="container first-child">
			<div class="row">
				<div class="col-md-9 hidden-xs">
					<h2><i class="fa fa-newspaper-o"></i> &nbsp; <?php echo phrase('posts'); ?></h2>
				</div>
				<div class="col-md-3">
					<?php if(!$this->session->userdata('loggedIn')) { ?>
						<a href="#login" class="btn btn-lg btn-block btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('write_article'); ?></a>
					<?php } else { ?>
						<a href="<?php echo base_url('user/posts/add'); ?>" class="btn btn-lg btn-block btn-primary newPost"><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('write_article'); ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-md-2 hidden-xs hidden-sm">
					<h4><i class="fa fa-certificate"></i> &nbsp;<?php echo phrase('contributors'); ?></h4>
				
					<?php echo widget_topContributors(8); ?>
							
				</div>
				<div class="col-md-7 nomargin-xs nopadding-xs">
			
					<?php
						if(getHeadlineNews('all', 5) !== null)
						{
							echo getHeadlineNews('all', 5);
						}
					?>
				
				</div>
				<div class="col-md-3">
					<h4><i class="fa fa-comments-o"></i> &nbsp;<?php echo phrase('most_commented'); ?></h4>
				
					<?php echo widget_mostCommentNews(5, 0); ?>
				
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-2 hidden-xs hidden-sm sticky">
				<h3><i class="fa fa-sitemap"></i> &nbsp;<?php echo phrase('categories'); ?></h3>
				<?php echo widget_sidebarCategory(); ?>
			</div>
			<div class="col-md-7 nomargin sticky">
				
				<div class="grid row">
				
				<?php 
					$catch 	=  getCategories();
					$n		= 1;
					foreach($catch as $cat)
					{
						$hex		= '#' . random_hex();
						if($n == 6 || $n == 12 || $n == 18)
						{
							echo '
								<div class="col-sm-6 grid-item">
									<div class="image-placeholder">
										' . widget_randomAds() . '
									</div>
								</div>
							';
						}
						
						echo '
							<div class="col-sm-6 grid-item">
								<a href="' . base_url('posts/' . $cat['categorySlug']) . '" class="ajaxLoad"><h3><i class="fa fa-bookmark-o"></i> ' . $cat['categoryTitle'] . '</h3></a>
						';
						
						$fetch 		= getCategoryNews($cat['categoryID'], 4, 0);
						$i 			= 1;
						foreach($fetch as $c)
						{
							$post_tag	= '';
							if($c['tags'] != '')
							{
								$tags = explode(',', $c['tags']);
								foreach($tags as $tag)
								{
									$post_tag = '<a href="' . base_url('search/' . $tag) . '" class="ajaxLoad"><span class="badge"><i class="fa fa-tag"></i> ' . truncate($tag, 12) . '</span></a> ';
								}
							}
							else
							{
								foreach(json_decode($c['categoryID']) as $key => $val)
								{
									$post_tag = '<a href="' . base_url('posts/' . getCategorySlugByID($val)) . '" class="ajaxLoad"><span class="badge"><i class="fa fa-tag"></i> ' . truncate(getCategoryByID($val), 12) . '</span></a> ';
								}
							}
							if($i == 1)
							{
								echo '
									<div class="first image-placeholder relative">
										<div class="col-sm-12 nomargin nogap_ltr rounded-top">
											<div class="row article_cover" style="background:' . $hex . ' url(' . getFeaturedImage($c['postID'], 1) . ') center center no-repeat;background-size:cover;-webkit-background-size:cover">
												<div class="col-sm-12 nomargin absolute text-shadow" style="width:100%">
													<div class="col-xs-2">
														<a href="' . base_url(getUsernameByID($c['contributor'])) . '" class="ajaxLoad hoverCard">
															<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($c['contributor']), 1)) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
														</a>
													</div>
													<div class="col-xs-10 relative">
														<h2 class="pull-right">#' . $c['visits_count'] . '</h2>
														<a href="' . base_url(getUsernameByID($c['contributor'])) . '" class="ajaxLoad hoverCard">
															<b>' . getFullnameByID($c['contributor']) . '</b> 
														</a>
														<br />
														<small>@' . getUsernameByID($c['contributor']) . ' - ' . time_since($c['timestamp']) . '</small>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">
												<h4 class="nomargin"><b>'.truncate($c['postTitle'], 30) . '</b></h4>
											</a>
											<p class="meta">
												'.truncate($c['postExcerpt'], 60).'
												<br />
												<b><i class="fa fa-clock-o"></i>'.time_since($c['timestamp']) . ' <span class="pull-right">' . $post_tag . '</span></b>
											</p>
										</div>
									</div>
								';
							}
							else
							{
								echo '
									<div class="image-placeholder">
										<div class="col-xs-3">
											<a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">
												<img width="100%" class="img-responsive img-rounded" src="' . getFeaturedImage($c['postID'], 1) . '" alt="..."/>
											</a>
										</div>
										<div class="col-xs-9">
											<a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">
												'.truncate($c['postTitle'], 80).'
											</a>
											<p class="meta">
												<b><i class="fa fa-clock-o"></i> '.time_since($c['timestamp']).' <span class="pull-right">' . $post_tag . '</span></b>
											</p>
										</div>
										<div class="clearfix"></div>
									</div>
								';
							}
							
							$i++;
						}
						echo '<a href="' . base_url('posts/' . $cat['categorySlug']) . '" class="ajaxLoad btn btn-default btn-block"><i class="fa fa-arrow-circle-right"></i> &nbsp; ' . phrase('see_all') . ' <span class="badge">' . countCategoryNews($cat['categoryID']) . '</span></a></div><br />';
						
						$n++;
					}
				?>
				
				</div>
			</div>
			<div class="col-md-3 hidden-xs hidden-sm sticky">
				
				<h3><i class="fa fa-clock-o"></i> &nbsp;<?php echo phrase('latest_articles'); ?></h3>
				<?php echo widget_sidebarNews(); ?>
				
				<br />
				
				<h4><i class="fa fa-eye"></i> &nbsp;<?php echo phrase('most_views'); ?></h4>
				<?php echo widget_mostViewNews(); ?>
				
				<?php // echo widget_randomAds(); ?>
				
				<br />
				
				<h4><i class="fa fa-tags"></i> &nbsp;<?php echo phrase('trending_hashtags'); ?></h4>
					
				<?php echo widget_hashTags(true, 10); ?>
			</div>
		</div>
	</div>
