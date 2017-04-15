
		<?php
			if(!$this->input->is_ajax_request()) echo '<br /><br />';
		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-save="<?php echo phrase('save'); ?>" data-saving="<?php echo phrase('saving'); ?>" data-alert="<?php echo phrase('unable_to_save_category'); ?>">
					
						<?php if($this->input->is_ajax_request() && isset($modal)) { ?>
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
							<h3><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('add_category'); ?></h3>
						</div>
						<?php } ?>
						
						<div class="modal-body">
							<div class="form-group">
								<div class="col-sm-12">
									<input type="text" name="categoryTitle" class="form-control input-lg" value="<?php echo htmlspecialchars(set_value('categoryTitle')); ?>" placeholder="<?php echo phrase('category_title'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<textarea name="categoryDescription" class="form-control" placeholder="<?php echo phrase('category_descriptions'); ?>"><?php echo htmlspecialchars(set_value('categoryDescription')); ?></textarea>
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
										<a href="<?php echo base_url('user/categories'); ?>" class="btn btn-default btn-lg ajaxLoad"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></a>
									<?php } ?>
								</div>
								<div class="col-xs-6 nomargin text-right">
									<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
									<button class="btn btn-success btn-lg submitBtn" type="submit"><i class="fa fa-save"></i> <?php echo phrase('save'); ?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>