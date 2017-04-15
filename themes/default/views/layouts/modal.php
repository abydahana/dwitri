<?php if(isset($code) && $code == 403) { ?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		<h3><i class="fa fa-warning"></i> &nbsp; <?php echo phrase('access_forbidden'); ?></h3>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 animated shake text-center">
				<img src="<?php echo base_url('themes/default/images/large_logo.png'); ?>" />
				<h1><?php echo $meta['title']; ?></h1>
				<p><?php echo $meta['descriptions']; ?></p>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<div class="row">
			<div class="col-xs-6">
				<a href="javascript:void(0)" class="btn btn-default btn-lg pull-left" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('close'); ?></a>
			</div>
			<div class="col-xs-6">
				<a href="#login" data-dismiss="modal" aria-hidden="true" data-toggle="modal" class="btn btn-primary btn-lg"><i class="fa fa-sign-in"></i> <?php echo phrase('login'); ?></a>
			</div>
		</div>
	</div>

<?php } elseif(isset($code) && $code == 404) { ?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		<h3><i class="fa fa-warning"></i> &nbsp; <?php echo phrase('request_not_found'); ?></h3>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 animated shake text-center">
				<img src="<?php echo base_url('themes/default/images/large_logo.png'); ?>" />
				<h1><?php echo $meta['title']; ?></h1>
				<p><?php echo $meta['descriptions']; ?></p>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<div class="row">
			<div class="col-xs-6">
				<a href="javascript:void(0)" class="btn btn-default btn-lg pull-left" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('close'); ?></a>
			</div>
			<div class="col-xs-6">
				<a href="#login" data-dismiss="modal" aria-hidden="true" data-toggle="modal" class="btn btn-primary btn-lg"><i class="fa fa-sign-in"></i> <?php echo phrase('login'); ?></a>
			</div>
		</div>
	</div>
	
<?php } ?>