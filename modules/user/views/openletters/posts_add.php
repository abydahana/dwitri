
	<link href="<?php echo base_url('themes/' . $this->settings['theme'] . '/css/redactor.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('themes/' . $this->settings['theme'] . '/css/tags.css'); ?>" rel="stylesheet">
	<?php
	if(!$this->input->is_ajax_request()) echo '<br /><br />';
	?>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-save="<?php echo phrase('save'); ?>" data-saving="<?php echo phrase('saving'); ?>" data-alert="<?php echo phrase('unable_to_save_letter'); ?>">
				
					<?php if($this->input->is_ajax_request() && isset($modal)) { ?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('submit_open_letter'); ?></h3>
					</div>
					<?php } ?>
					
					<div class="modal-body">
						<div class="form-group">
							<div class="col-sm-12">
								<input type="text" name="title" class="form-control input-lg" value="<?php echo htmlspecialchars(set_value('title')); ?>" placeholder="<?php echo phrase('letter_headline'); ?>" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<input type="text" name="targetName" class="form-control" value="<?php echo htmlspecialchars(set_value('targetName')); ?>" placeholder="<?php echo phrase('aimed_to'); ?>" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<textarea name="targetDetails" class="form-control" placeholder="<?php echo phrase('target_details'); ?>"><?php echo htmlspecialchars(set_value('targetDetails')); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<textarea name="content" class="redactor form-control" placeholder="<?php echo phrase('write_complete_letter_here'); ?>"><?php echo set_value('content'); ?></textarea>
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
									<a href="<?php echo base_url('user/openletters'); ?>" class="btn btn-default btn-lg ajaxLoad"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></a>
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
	
	<script src="<?php echo base_url('themes/' . $this->settings['theme'] . '/js/redactor.js'); ?>"></script>
	<script src="<?php echo base_url('themes/' . $this->settings['theme'] . '/js/tags.js'); ?>"></script>
	
	<script type="text/javascript">
		if($(window).width() > 768)
		{
			$('.redactor').redactor({
				buttons:["formatting","|","bold","italic","deleted","|","unorderedlist","orderedlist","outdent","indent","|","alignment","|","horizontalrule"],
				plugins: ['fontcolor'],
				minHeight: 200
			});
		}
	</script>