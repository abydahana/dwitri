
	<?php
	if(!$this->input->is_ajax_request()) echo '<br /><br />';
	?>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<form action="<?php echo current_url(); ?>" method="post"  enctype="multipart/form-data" class="form-horizontal submitForm" data-save="<?php echo phrase('save'); ?>" data-saving="<?php echo phrase('saving'); ?>" data-alert="<?php echo phrase('unable_to_save_channel'); ?>">
				
					<?php if($this->input->is_ajax_request() && isset($modal)) { ?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('submit_channel'); ?></h3>
					</div>
					<?php } ?>
					
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<div class="col-sm-12">
										<div data-provides="fileupload" class="fileupload fileupload-tv">
											<span class="btn btn-default btn-file btn-block">
												<input onchange="readChannel(this, 'tv_preview');" type="file" name="userfile" accept="image/*" />
												<div class="fileupload-tv" style="overflow:hidden">
													<img id="tv_preview" class="img-responsive" width="100%" src="<?php echo base_url('uploads/tv/thumbs/placeholder.jpg'); ?>" alt="" />
												</div>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<div class="col-sm-12">
										<input type="text" name="title" class="form-control input-lg" placeholder="<?php echo phrase('channel_title'); ?>" value="<?php echo strip_tags(set_value('title')); ?>" />
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<input type="text" name="url" class="form-control" placeholder="<?php echo phrase('channel_url'); ?>" value="<?php echo strip_tags(set_value('url')); ?>" />
										<span class="text-danger"><?php echo phrase('channel_url_notes'); ?></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<h3><?php echo phrase('about_this_channel'); ?></h3>
										<textarea name="content" class="form-control" placeholder="<?php echo phrase('write_channel_descriptions'); ?>"><?php echo strip_tags(set_value('content')); ?></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12 statusHolder">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="form-group">
							<div class="col-xs-6 nomargin text-left">
								<?php if($this->input->is_ajax_request() && isset($modal)) { ?>
									<a href="javascript:void(0)" class="btn btn-default btn-lg" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></a>
								<?php } else { ?>
									<a href="<?php echo base_url('user/tv'); ?>" class="btn btn-default btn-lg ajaxLoad"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></a>
								<?php } ?>
							</div>
							<div class="col-xs-6 nomargin">
								<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
								<button class="btn btn-success btn-lg submitBtn" type="submit"><i class="fa fa-save"></i> <?php echo phrase('save'); ?></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		function readChannel(input,id) {
			if (input.files && input.files[0])
			{
				var reader = new FileReader();
				reader.onload = function (e)
				{
					$('#'+id).attr('src', e.target.result);
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>