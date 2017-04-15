
	<link href="<?php echo base_url('themes/' . $this->settings['theme'] . '/css/redactor.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('themes/' . $this->settings['theme'] . '/css/tags.css'); ?>" rel="stylesheet">
	<?php
	if(!$this->input->is_ajax_request()) echo '<br /><br />';
	foreach ($page as $p)
	{
	?>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-save="<?php echo phrase('update'); ?>" data-saving="<?php echo phrase('updating'); ?>" data-alert="<?php echo phrase('unable_to_update_page'); ?>">
				
					<?php if($this->input->is_ajax_request() && isset($modal)) { ?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3><i class="fa fa-edit"></i> &nbsp; <?php echo phrase('update_page'); ?></h3>
					</div>
					<?php } ?>
					
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-8">
								<h3><?php echo phrase('title_and_content'); ?></h3>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<div class="col-sm-12">
										<select name="language" class="form-control">
											<option value="">--<?php echo phrase('select_language'); ?>--</option>
									
											<?php
												$fields = $this->db->list_fields('language');
												foreach($fields as $field)
												{
													if($field == 'phrase_id' || $field == 'phrase') continue;
											?>
											
												<option value="<?php echo $field;?>"<?php if($p['language'] == $field) echo ' selected'; ?>><?php echo ucwords($field);?></option>
						
											<?php } ?>
														
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<input type="text" name="pageTitle" class="form-control input-lg" value="<?php echo htmlspecialchars(set_value('pageTitle', $p['pageTitle'])); ?>" placeholder="<?php echo phrase('page_title'); ?>" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<textarea name="content" class="redactor form-control" placeholder="<?php echo phrase('write_page_content_here'); ?>"><?php echo set_value('content', $p['pageContent']); ?></textarea>
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
									<a href="<?php echo base_url('user/pages'); ?>" class="btn btn-default btn-lg ajaxLoad"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></a>
								<?php } ?>
							</div>
							<div class="col-xs-6 nomargin">
								<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
								<button class="btn btn-success btn-lg submitBtn" type="submit"><i class="fa fa-save"></i> <?php echo phrase('update'); ?></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
		
	<?php
	}
	?>
	
	<script src="<?php echo base_url('themes/' . $this->settings['theme'] . '/js/redactor.js'); ?>"></script>
	<script src="<?php echo base_url('themes/' . $this->settings['theme'] . '/js/tags.js'); ?>"></script>
	
	<script type="text/javascript">
		if($(window).width() > 768)
		{
			$('.redactor').redactor({
				minHeight: 200,
				imageUpload: '<?php echo base_url('user/upload/images/pages');?>',
				imageGetJson: '<?php echo base_url('user/upload/choose/pages');?>',
				imageUploadErrorCallback: function(response)
				{
					alert(response.error);
				}
			});
		}
	</script>