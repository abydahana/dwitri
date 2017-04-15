
<?php if($type == 'post_repost') { ?>

	<?php
	foreach($modal as $row)
	{
		?>
		
		<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-id="<?php echo $row['postID']; ?>" data-save="<?php echo phrase('repost'); ?>" data-saving="<?php echo phrase('reposting'); ?>" data-alert="<?php echo phrase('unable_to_repost_this_post'); ?>">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			<h3><i class="fa fa-retweet"></i> &nbsp; <?php echo phrase('repost_this_article'); ?></h3>
			<div class="form-group">
				<div class="col-sm-12">
					<textarea class="form-control" placeholder="<?php echo phrase('say_something'); ?>" name="messages"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="img-thumbnail">
						<img src="<?php echo getFeaturedImage($row['postID']); ?>" class="img-responsive rounded" />
						<div class="row">
							<div class="col-xs-2 text-right">
								<img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['contributor']), 1)); ?>" class="rounded" width="40" height="40" alt="..." />
							</div>
							<div class="col-xs-10">
								<b><?php echo getFullnameByID($row['contributor']); ?></b> <small>@<?php echo time_since($row['timestamp']); ?></small>
								<p><?php echo truncate($row['postContent'], 160); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12 statusHolder"></div>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></button>
				</div>
				<div class="col-xs-6 text-right">
					<button class="btn btn-info submitBtn" type="submit"><i id="repost-icon" class="fa fa-retweet"></i> <?php echo phrase('repost'); ?></button>
				</div>
			</div>
		</form>
		
		<?php
	}
	?>
	
<?php } elseif($type == 'snapshot_repost') { ?>

	<?php
	foreach($modal as $row)
	{
		?>
		
		<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-id="<?php echo $row['snapshotID']; ?>" data-save="<?php echo phrase('repost'); ?>" data-saving="<?php echo phrase('reposting'); ?>" data-alert="<?php echo phrase('unable_to_repost_this_snapshot'); ?>">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			<h3><i class="fa fa-retweet"></i> &nbsp; <?php echo phrase('repost_this_snapshot'); ?></h3>
			<div class="form-group">
				<div class="col-sm-12">
					<textarea class="form-control" placeholder="<?php echo phrase('say_something'); ?>" name="messages"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="image-placeholder">
						<img src="<?php echo base_url('uploads/snapshots/thumbs/' . imageCheck('snapshots', $row['snapshotFile'], 1)); ?>" width="100%" class="img-responsive" />
						<div class="row">
							<div class="col-xs-2 text-right">
								<img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['contributor']), 1)); ?>" class="rounded" width="40" height="40" alt="..." />
							</div>
							<div class="col-xs-10">
								<b><?php echo getFullnameByID($row['contributor']); ?></b> <small>@<?php echo time_since($row['timestamp']); ?></small>
								<p><?php echo truncate($row['snapshotContent'], 160); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12 statusHolder"></div>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></button>
				</div>
				<div class="col-xs-6 text-right">
					<button class="btn btn-info submitBtn" type="submit"><i id="repost-icon" class="fa fa-retweet"></i> <?php echo phrase('repost'); ?></button>
				</div>
			</div>
		</form>
		
		<?php
	}
	?>
	
<?php } elseif($type == 'update_repost') { ?>

	<?php
	foreach($modal as $row)
	{
		?>
		
		<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-id="<?php echo $row['updateID']; ?>" data-save="<?php echo phrase('repost'); ?>" data-saving="<?php echo phrase('reposting'); ?>" data-alert="<?php echo phrase('unable_to_repost_this_update'); ?>">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			<h3><i class="fa fa-retweet"></i> &nbsp; <?php echo phrase('repost_this_update'); ?></h3>
			<div class="form-group">
				<div class="col-sm-12">
					<textarea class="form-control" placeholder="<?php echo phrase('say_something'); ?>" name="messages"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="image-placeholder">
						<div class="row">
							<div class="col-xs-2 text-right">
								<img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['userID']), 1)); ?>" class="rounded" width="40" height="40" alt="..." />
							</div>
							<div class="col-xs-10">
								<b><?php echo getFullnameByID($row['userID']); ?></b> <small>@<?php echo time_since($row['timestamp']); ?></small>
								<p><?php echo truncate($row['updateContent'], 160); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12 statusHolder"></div>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></button>
				</div>
				<div class="col-xs-6 text-right">
					<button class="btn btn-info submitBtn" type="submit"><i id="repost-icon" class="fa fa-retweet"></i> <?php echo phrase('repost'); ?></button>
				</div>
			</div>
		</form>
		
		<?php
	}
	?>
	
<?php } elseif($type == 'openletter_repost') { ?>

	<?php
	foreach($modal as $row)
	{
		?>
		
		<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-id="<?php echo $row['letterID']; ?>" data-save="<?php echo phrase('repost'); ?>" data-saving="<?php echo phrase('reposting'); ?>" data-alert="<?php echo phrase('unable_to_repost_this_open_letter'); ?>">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			<h3><i class="fa fa-retweet"></i> &nbsp; <?php echo phrase('repost_this_letter'); ?></h3>
			<div class="form-group">
				<div class="col-sm-12">
					<textarea class="form-control" placeholder="<?php echo phrase('say_something'); ?>" name="messages"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="img-thumbnail">
						<div class="row">
							<div class="col-xs-2 text-right">
								<img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['contributor']), 1)); ?>" class="rounded" width="40" height="40" alt="..." />
							</div>
							<div class="col-xs-10">
								<b><?php echo getFullnameByID($row['contributor']); ?></b> <small>@<?php echo time_since($row['timestamp']); ?></small>
								<br />
								<b class="text-primary"><?php echo truncate($row['title'], 60); ?></b>
								<p><?php echo truncate($row['content'], 90); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12 statusHolder"></div>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></button>
				</div>
				<div class="col-xs-6 text-right">
					<button class="btn btn-info submitBtn" type="submit"><i id="repost-icon" class="fa fa-retweet"></i> <?php echo phrase('repost'); ?></button>
				</div>
			</div>
		</form>
		
		<?php
	}
	?>
	
<?php } elseif($type == 'channel_repost') { ?>

	<?php
	foreach($modal as $row)
	{
		?>
		
		<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-id="<?php echo $row['tvID']; ?>" data-save="<?php echo phrase('repost'); ?>" data-saving="<?php echo phrase('reposting'); ?>" data-alert="<?php echo phrase('unable_to_repost_this_channel'); ?>">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			<h3><i class="fa fa-retweet"></i> &nbsp; <?php echo phrase('repost_this_channel'); ?></h3>
			<div class="form-group">
				<div class="col-sm-12">
					<textarea class="form-control" placeholder="<?php echo phrase('say_something'); ?>" name="messages"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="image-placeholder">
						<img src="<?php echo base_url('uploads/tv/thumbs/' . imageCheck('tv', $row['tvFile'], 1)); ?>" width="100%" class="img-responsive" />
						<div class="row">
							<div class="col-xs-2 text-right">
								<img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['contributor']), 1)); ?>" class="rounded" width="40" height="40" alt="..." />
							</div>
							<div class="col-xs-10">
								<b><?php echo getFullnameByID($row['contributor']); ?></b> <small>@<?php echo time_since($row['timestamp']); ?></small>
								<p><?php echo truncate($row['tvTitle'], 160); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12 statusHolder"></div>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></button>
				</div>
				<div class="col-xs-6 text-right">
					<button class="btn btn-info submitBtn" type="submit"><i id="repost-icon" class="fa fa-retweet"></i> <?php echo phrase('repost'); ?></button>
				</div>
			</div>
		</form>
		
		<?php
	}
	?>
	
<?php } ?>