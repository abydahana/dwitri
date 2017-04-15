
	<?php
	if(!$this->input->is_ajax_request()) echo '<br /><br />';
	foreach ($user as $page)
	{
	?>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<form action="<?php echo current_url(); ?>" method="post" class="form-horizontal submitForm" data-save="<?php echo phrase('update'); ?>" data-saving="<?php echo phrase('updating'); ?>" data-alert="<?php echo phrase('unable_to_update_user_information'); ?>">
				
					<?php if($this->input->is_ajax_request() && isset($modal)) { ?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3><i class="fa fa-edit"></i> &nbsp; <?php echo phrase('update_user_profile'); ?></h3>
					</div>
					<?php } ?>
					
					<div class="modal-body">
						<div class="form-group">
							<div class="col-sm-7 col-sm-offset-4">
								<h4><i class="fa fa-user"></i> <?php echo phrase('basic_information'); ?></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('full_name'); ?></label>
							<div class="col-sm-7">
								<input type="text" class="form-control" name="full_name" value="<?php echo $page['full_name']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('gender'); ?></label>
							<div class="col-sm-7">
								<select name="gender" class="form-control">
									<option value=""><?php echo phrase('select_gender'); ?></option>
									<option value="l"<?php if($page['gender'] == 'l') echo ' selected'; ?>><?php echo phrase('man'); ?></option>
									<option value="p"<?php if($page['gender'] == 'p') echo ' selected'; ?>><?php echo phrase('woman'); ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('age'); ?></label>
							<div class="col-sm-7">
								<input type="text" class="form-control" name="age" value="<?php echo $page['age']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('language'); ?></label>
							<div class="col-sm-7">
								<select name="language" class="form-control">
				
									<?php
										$fields = $this->db->list_fields('language');
										foreach($fields as $field)
										{
											if($field == 'phrase_id' || $field == 'phrase') continue;
									?>
									
										<option value="<?php echo $field;?>"<?php if($this->session->userdata('language') == $field) echo ' selected'; ?>><?php echo ucwords($field);?></option>
				
									<?php } ?>
									
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('biography'); ?></label>
							<div class="col-sm-7">
								<textarea class="form-control" name="bio"><?php echo $page['bio']; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-7 col-sm-offset-4">
								<br />
								<h4><i class="fa fa-phone"></i> <?php echo phrase('contact_info'); ?></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('phone_number'); ?></label>
							<div class="col-sm-7">
								<input type="text" class="form-control" name="mobile" value="<?php echo $page['mobile']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('address'); ?></label>
							<div class="col-sm-7">
								<textarea class="form-control" name="address"><?php echo $page['address']; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-7 col-sm-offset-4">
								<br />
								<h4><i class="fa fa-info-circle"></i> <?php echo phrase('account_info'); ?></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('email'); ?></label>
							<div class="col-sm-7">
								<input type="text" class="form-control" name="email" value="<?php echo $page['email']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('choose_username'); ?></label>
							<div class="col-sm-7">
								<input type="text" class="form-control" name="username" value="<?php echo $page['userName']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('password'); ?></label>
							<div class="col-sm-7">
								<input type="password" class="form-control" name="password" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo phrase('retype_password'); ?></label>
							<div class="col-sm-7">
								<input type="password" class="form-control" name="con_password" />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="form-group">
							<div class="col-xs-6 nomargin text-left">
								<?php if($this->input->is_ajax_request() && isset($modal)) { ?>
									<a href="javascript:void(0)" class="btn btn-default btn-lg" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></a>
								<?php } else { ?>
									<a href="<?php echo base_url('user/users'); ?>" class="btn btn-default btn-lg ajaxLoad"><i class="fa fa-times"></i> <?php echo phrase('cancel'); ?></a>
								<?php } ?>
							</div>
							<div class="col-xs-6 nomargin">
								<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
								<button class="btn btn-success btn-lg btn-lg submitBtn" type="submit"><i class="fa fa-save"></i> <?php echo phrase('update'); ?></button>
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