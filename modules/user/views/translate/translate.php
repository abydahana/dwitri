
	<div class="bg-info">
		<div class="container first-child">
			<div class="row">
				<div class="col-sm-7 col-sm-offset-1 hidden-xs">
					<h2><i class="fa fa-language"></i> &nbsp; <?php echo phrase('translation'); ?></h2>
				</div>
				<div class="col-sm-3">
					<div class="col-12-xs">
						<a href="javascript:void(0)" class="btn btn-lg btn-block btn-primary disabled"><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('add_translation'); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-md-offset-1 hidden-xs hidden-sm sticky">
			
				<?php echo ($this->input->is_ajax_request() ? $this->load->view('dashboard_navigation') : $template['partials']['navigation']); ?>
				
			</div>
			<div class="col-md-7 sticky">
			
				<?php if($edit_phrase) { ?>
					<div class="row">
					
						<form action="<?php echo base_url('user/translate/' . $edit_phrase); ?>" method="post" class="col-sm-12 form-horizontal submitForm" enctype="multipart/form-data" data-save="<?php echo phrase('update'); ?>" data-saving="<?php echo phrase('updating'); ?>" data-alert="<?php echo phrase('unable_to_update_translation'); ?>">
							<div class="form-group">
								<?php
								$count = 1;
								foreach($phrase as $row)
								{
									$count++;
									$phrase_id				= $row['phrase_id'];
									$phrase					= $row['phrase'];
									$phrase_language		= $row[$edit_phrase];
									?>
									
									<div class="col-sm-4" style="margin:0">
										<div class="tile-stats tile-gray">
											<p>
												<input type="text" name="phrase<?php echo $row['phrase_id'];?>" placeholder="<?php echo $row['phrase'];?>" value="<?php echo $phrase_language;?>" data-push="tooltip" data-placement="top" title="<?php echo $row['phrase'];?>" class="form-control"/>
											</p>
										</div>
										
									</div>
										
								<?php  } ?>
							</div>
							<div class="form-group">
								<div class="col-sm-12 nomargin">
									<div class="statusHolder"></div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-6">
									<a href="<?php echo base_url('user/translate'); ?>" class="btn btn-default btn-lg"><i class="fa fa-chevron-left"></i> <?php echo phrase('back'); ?></a>
								</div>
								<div class="col-xs-6 text-right">
									<input type="hidden" name="phrase_start" value="<?php echo ($this->uri->segment(4) && is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0); ?>" />
									<input type="hidden" name="hash" value="<?php echo sha1($count); ?>" />
									<button type="submit" class="btn btn-info btn-lg submitBtn"><i class="fa fa-save"></i> <?php echo phrase('update');?></button>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
								
									<?php echo generatePagination('translations', null, null, $this->uri->segment(3), 60, $this->uri->segment(4)); ?>
									
								</div>
							</div>
						</form>
					</div>
					
				<?php } else { ?>
					
					<table class="table table-hover">
						<tr>
							<th><?php echo phrase('translation');?></th>
							<th class="text-right col-xs-4"><?php echo phrase('options');?></th>
						</tr>
						
						<?php
							$fields = $this->db->list_fields('language');
							$n = 1;
							foreach($fields as $field)
							{
								if($field == 'phrase_id' || $field == 'phrase') continue;
						?>
						
						<tr id="translate<?php echo $field; ?>">
							<td><?php echo ucwords($field);?></td>
							<td class="text-right col-xs-4">
								<div class="btn-group">
									<a href="<?php echo base_url('user/translate/' . $field);?>" class="btn btn-default btn-sm ajaxLoad" data-push="tooltip" data-placement="top" title="<?php echo phrase('edit_phrase'); ?>"><i class="btn-icon-only fa fa-edit"> </i></a><?php if($this->session->userdata('user_level') == 1) { ?><a href="javascript:void(0)" class="btn btn-default btn-sm" onclick="return confirm_modal('<?php echo base_url('user/translate/delete_language/' . $field);?>', 'translate<?php echo $field; ?>');" data-push="tooltip" data-placement="top" title="<?php echo phrase('delete_translation'); ?>"><i class="btn-icon-only fa fa-trash"> </i></a><?php } ?>
								</div>
							</td>
						</tr>
						
						<?php } ?>
						
					</table>
				
				<?php } ?>
				
			</div>
		</div>
	</div>