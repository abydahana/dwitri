
	<?php foreach($page as $row): ?>
	
		<div class="jumbotron text-center">
			<div class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1 text-shadow">
						<h2><?php echo $row['pageTitle']; ?></h2>
						<p>
							<?php echo $row['pageExcerpt']; ?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="row">
						<div class="col-md-8 sticky">
						
							<?php echo special_parse($row['pageContent']); ?>
							
							<br />
							<p class="text-muted">
								<i class="fa fa-clock-o"></i> <?php echo phrase('updated_on') . ' ' . date('d M Y, H:i', $row['timestamp']); ?>
							</p>
						</div>
						<div class="col-md-4 hidden-xs hidden-sm sticky">
							<div class="list-group">
							
								<?php foreach($navigations as $nav) { ?>
								
									<a href="<?php echo base_url('pages/' . $nav['pageSlug']); ?>" class="ajaxLoad list-group-item<?php if($row['pageSlug'] == $nav['pageSlug']) echo ' active'; ?>"><i class="fa fa-file-o"></i> &nbsp; <?php echo truncate($nav['pageTitle'], 22); ?></a>
									
								<?php } ?>
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<?php endforeach; ?>