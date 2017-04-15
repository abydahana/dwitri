
	<?php
		$totSegments = $this->uri->total_segments();
		$uriSegments = $this->uri->segment($totSegments);
		if(!is_numeric($uriSegments) || is_numeric($this->uri->segment(2)))
		{
			$offset = 0;
		} else if(is_numeric($uriSegments)){
			$offset = $this->uri->segment($totSegments);
		}
		
		$limit 	= 12;
		if($keywords)
		{
			$search	= userSearch($keywords, $limit, $offset);
			$count 	= userSearchCount($keywords);
		}
		else
		{
			$search	= listUsers(null, $limit, $offset);
			$count 	= userSearchCount($keywords);
		}
	?>
	
	<div class="jumbotron bg-primary">
		<div class="container first-child">
			<div class="row">
				<div class="col-md-8 col-sm-offset-2">
					<form class="form-horizontal submitForm" action="<?php echo base_url('users'); ?>" method="post" data-save="<?php echo phrase('search'); ?>" data-saving="<?php echo phrase('searching'); ?>" data-alert="<?php echo phrase('unable_to_submit_inquiry'); ?>">
						<div class="input-group">
							<input type="text" class="form-control input-lg" name="query" placeholder="<?php echo phrase('search_user'); ?>"<?php echo ($keywords != null ? ' value="' . $keywords . '"' : ''); ?> />
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
	<br />
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<?php if($keywords): ?>
				<br />
				<div class="alert alert-<?php echo ($count > 0 ? 'info' : 'danger'); ?>"><?php echo phrase('showing'); ?> <b><?php echo $offset . ' - ' . ($count > $limit && $limit+$offset < $count ? $limit+$offset : $count) . ' ' . phrase('from') . ' ' . ($count > 0 ? $count : 0); ?></b> <?php echo phrase('results_for_keywords'); ?> <b>"<?php echo $keywords; ?>"</b></div>
				<?php endif; ?>
				
				<?php
					$n	= 1;
					echo '<div class="row grid">';
					foreach($search as $row)
					{
						if($n++ == 7)
						{
							echo'
							<div class="col-sm-6 grid-item">
								' . widget_randomAds() . '
							</div>
							';
						}
						
						echo '
							<div class="col-sm-6 grid-item">
								' . getUserDetails($row['userID']) . '
							</div>
						';
					}
					echo '</div>';
				?>
				
				<div class="row">
					<div class="col-sm-12 text-center">
					
						<?php
						if($keywords)
						{
							echo generatePagination('userSearch', $keywords, null, $keywords, $limit, $offset);
						}
						?>
						
					</div>
				</div>
			</div>
		</div>
	</div>