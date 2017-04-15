
	<div class="gap-sm"></div>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<h3 id="myModalLabel"><i class="fa fa-desktop"></i> &nbsp; &nbsp; <?php echo phrase('dashboard_access'); ?> <span class="pull-right hidden-xs"><i class="fa fa-lock text-danger"></i></span></h3>
				<hr />
			</div>
		</div>
		<?php if($this->session->flashdata('error')) { ?>
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo $this->session->flashdata('error'); ?></div>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-sm-6 nomargin-xs nopadding-xs col-sm-offset-3">
				<div class="image-placeholder-sm">
					<div class="col-sm-7 nomargin text-center">
						<div class="gap-sm"></div>
						<form action="<?php echo base_url('user/login'); ?>" method="post" class="submitForm" data-save="<?php echo phrase('sign_in'); ?>" data-saving="<?php echo phrase('signing_in'); ?>" data-alert="<?php echo phrase('unable_to_signing_in'); ?>">
							<div class="input-group col-sm-12">
								<span class="input-group-addon"><i class="fa fa-at"></i></span>
								<input type="text" class="form-control input-lg" name="username" value="<?php echo set_value('username'); ?>" placeholder="<?php echo phrase('username_or_email'); ?>" />
							</div>
							<div class="input-group col-sm-12">
								<span class="input-group-addon"><i class="fa fa-qrcode"></i></span>
								<input type="password" class="form-control input-lg" name="password" value="<?php echo set_value('password'); ?>" placeholder="<?php echo phrase('type_your_password'); ?>" />
							</div>
							<div class="statusHolder"></div>
							<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
							<button class="btn btn-primary btn-lg btn-block submitBtn" type="submit"><i class="fa fa-key"></i> <?php echo phrase('login'); ?></button>
						</form>
						<div class="gap-sm"></div>
					</div>
					<div class="col-sm-5 nomargin loginBorder">
						<div class="gap-sm"></div>
						<div class="gap-sm"></div>
						<a class="btn btn-primary btn-lg btn-block" href="<?php echo instantLoginURL(); ?>"><i class="fa fa-facebook"></i> &nbsp; <?php echo phrase('instant_login'); ?></a>
						<a class="btn btn-success btn-lg btn-block" href="<?php echo base_url('user/register'); ?>"><i class="fa fa-user-plus"></i> &nbsp; <?php echo phrase('register'); ?></a>
						<div class="gap-sm"></div>
						<div class="gap-sm"></div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="text-center">
				<div class="col-sm-6 col-sm-offset-3"><?php echo phrase('login_description'); ?></div>
			</div>
		</div>
	</div>
	<div class="gap-sm"></div>