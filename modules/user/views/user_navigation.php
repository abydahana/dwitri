	<?php if($page['userID'] == $this->session->userdata('userID')) { ?>

	<form id="coverUpload" action="<?php echo base_url('user/uploads/cover'); ?>" method="post" enctype="multipart/form-data">
		<div data-provides="fileupload" id="cover_preview" class="btn btn-file fileupload fileupload-cover jumbotron noborder roundless" style="background:url(<?php echo base_url('uploads/users/covers/' . imageCheck('covers', getUserCover($page['userID']))); ?>) center center no-repeat;background-size:cover;-webkit-background-size:cover;background-position:fixed;padding-top:240px;padding-bottom:0;width:100%">
			<input onchange="readCover(this, 'cover_preview');" type="file" name="userfile" accept="image/*" />
			<div class="fileupload-cover"></div>
		</div>
	</form>
	
	<?php } else { ?>

	<div class="jumbotron" style="background:url(<?php echo base_url('uploads/users/covers/' . imageCheck('covers', getUserCover($page['userID']))); ?>) center center no-repeat;background-size:cover;-webkit-background-size:cover;background-position:fixed;padding-top:240px;padding-bottom:0;margin-bottom:0;width:100%"></div>
	
	<?php } ?>
	<div class="container" style="position:relative">
		<div class="row" style="position:absolute;bottom:0;width:100%">
			<div>
				<div class="col-sm-2 nomargin relative">
					<?php if($page['userID'] == $this->session->userdata('userID')) { ?>
					<form id="photoUpload" action="<?php echo base_url('user/uploads/photo'); ?>" method="post" enctype="multipart/form-data">
						<div data-provides="fileupload" class="btn btn-file fileupload fileupload-new userImage" style="padding:0">
							<div class="fileupload-new" style="overflow:hidden">
								<div class="col-xs-6 col-xs-offset-3 col-sm-12 col-sm-offset-0 text-center" style="padding:0">
									<div class="rounded" style="width:100%;border:6px solid #eee;overflow:hidden">
										<img id="userfile_preview" src="<?php echo base_url('uploads/users/' . imageCheck('users', getUserPhoto($page['userID']))); ?>" class="img-rounded img-bordered" style="width:100%" alt="" />
										<input onchange="readPhoto(this, 'userfile_preview');" type="file" name="userfile" accept="image/*" />
									</div>
								</div>
							</div>
						</div>
					</form>
					
					<?php } else { ?>
					
					<div class="userImage">
						<div class="col-xs-6 col-xs-offset-3 col-sm-12 col-sm-offset-0 text-center" style="padding:0">
							<div class="rounded" style="width:100%;border:6px solid #eee;overflow:hidden">
								<?php echo '<img src="' . base_url('uploads/users/' . imageCheck('users', getUserPhoto($page['userID']))) . '" class="image-rounded img-bordered" style="width:100%" alt="" />'; ?>
							</div>
						</div>
					</div>
					
					<?php } ?>
				</div>
				<div class="col-sm-10 nomargin">
					<?php 
						echo '
							<div class="row">
								<div class="col-sm-6 text-center-xs">
									<h3>
										<a class="text-shadow" href="' . base_url($page['userName']) . '"><b>' . $page['full_name'] . '</b></a>
										<br />
										<span class="badge">@' . $page['userName'] . ' - ' . $page['visits_count'] . ' ' . phrase('stalker') . '</span>
									</h3>
								</div>
								<div class="col-sm-6 text-right-sm text-center-xs">
									<span class="btn-group">
										<a href="#profileView" class="btn btn-default" data-toggle="modal"><i class="fa fa-info-circle"></i> ' . phrase('info') . '</a>
										' . ($page['userID'] != $this->session->userdata('userID') ? '
										' . (is_userConnect('following', $page['userID']) ? '
											<a class="btn btn-warning" id="follow" href="' . base_url('user/follow/' . $page['userName']) . '"><i class="fa fa-refresh" id="follow-icon"></i> <span class="follow-text">' . phrase('unfollow') . '</span></a>
										' : '
											<a class="btn btn-info" id="follow" href="' . base_url('user/follow/' . $page['userName']) . '"><i class="fa fa-refresh" id="follow-icon"></i> <span class="follow-text">' . phrase('follow') . '</span></a>
										') . '
										' . (is_userConnect('friendship', $page['userID']) ? '
											<a class="btn btn-danger" id="friendship" href="' . base_url('user/friendship/' . $page['userName']) . '""><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase('unfriend') . '</span></a>
										' : (is_userConnect('pending', $page['userID']) ? '
											<a class="btn btn-warning" id="friendship" href="' . base_url('user/friendship/' . $page['userName']) . '""><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase(($page['userID'] == $this->session->userdata('userID') ? 'cancel' : 'accept')) . '</span></a>
										' : '
											<a class="btn btn-success" id="friendship" href="' . base_url('user/friendship/' . $page['userName']) . '"><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase('add_friend') . '</span></a>
										')) . '
										' : '
											<a href="#profileEdit" class="btn btn-primary editProfile" data-toggle="modal"><i class="fa fa-cogs"></i> ' . phrase('update_info') . '</a>
										') . '
									</span>
								</div>
							</div>
						';
					?>
				</div>
			</div>
		</div>
	</div>
	<div style="border-bottom:1px solid #ddd">
		<div class="container">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-2" style="position:relative">
					
					<div class="btn-group btn-group-justified">
						<a class="ajaxLoad btn btn-default btn-lg<?php echo (!$this->uri->segment(2) || $this->uri->segment(2) == 'timeline' ? ' active' : ''); ?>" href="<?php echo base_url($page['userName']); ?>" style="border-right:none"><i class="fa fa-newspaper-o"></i><span class="hidden-xs"> &nbsp; <?php echo phrase('wall'); ?></span></a>
						
						<a class="ajaxLoad btn btn-default btn-lg<?php echo ($this->uri->segment(2) == 'friends' ? ' active' : ''); ?>" href="<?php echo base_url($page['userName'] . '/friends'); ?>" style="border-right:none"><i class="fa fa-users"></i><span class="hidden-xs"> &nbsp; <?php echo phrase('friends'); ?></span> <?php echo '<small class="badge" id="friends-count">' . getUserFriends('active', $page['userID']) . '</small>'; ?></a>
						
						<a class="ajaxLoad btn btn-default btn-lg<?php echo ($this->uri->segment(2) == 'followers' ? ' active' : ''); ?>" href="<?php echo base_url($page['userName'] . '/followers'); ?>" style="border-right:none"><i class="fa fa-retweet"></i><span class="hidden-xs"> &nbsp; <?php echo phrase('followers'); ?></span> <?php echo '<small class="badge" id="followers-count">' . getUserFollowers('followers', $page['userID']) . '</small>'; ?></a>
						
						<a class="ajaxLoad btn btn-default btn-lg<?php echo ($this->uri->segment(2) == 'following' ? ' active' : ''); ?>" href="<?php echo base_url($page['userName'] . '/following'); ?>"><i class="fa fa-random"></i><span class="hidden-xs"> &nbsp; <?php echo phrase('following'); ?></span> <?php echo '<small class="badge" id="following-count">' . getUserFollowers('following', $page['userID']) . '</small>'; ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="profileView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal_table">
			<div class="modal-content modal_cell">
				<div class="modal-content">
					<div class="container">
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
									<h3 id="myModalLabel"><i class="fa fa-user"></i> &nbsp; <?php echo $page['full_name']; ?></h3>
								</div>
								<div class="modal-body">
									<div class="form-horizontal">
										<div class="form-group">
											<div class="col-xs-10 col-xs-offset-1">
												<h4><i class="fa fa-info-circle"></i> <?php echo phrase('account_details'); ?></h4>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-1 col-xs-offset-1 text-left"><i class="fa fa-quote-left"></i></label>
											<div class="col-xs-9">
												<?php echo $page['bio']; ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-1 col-xs-offset-1 text-left"><i class="fa fa-map-marker"></i></label>
											<div class="col-xs-9">
												<?php echo $page['address']; ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-1 col-xs-offset-1 text-left"><i class="fa fa-mobile"></i></label>
											<div class="col-xs-9">
												<?php echo $page['mobile']; ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-1 col-xs-offset-1 text-left"><i class="fa fa-venus-mars"></i></label>
											<div class="col-xs-9">
												<?php echo ($page['gender'] == 'l' ? phrase('male') : phrase('female')); ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-1 col-xs-offset-1 text-left"><i class="fa fa-child"></i></label>
											<div class="col-xs-9">
												<?php echo $page['age'] . ' ' . phrase('years'); ?>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<div class="form-group">
										<div class="col-xs-4 text-left">
											<button type="button" data-dismiss="modal" class="btn btn-default"><i class="fa fa-times"></i> <?php echo phrase('close'); ?></button>
										</div>
										<div class="col-xs-8">
											<?php
												echo '
													<span class="btn-group">
														' . ($page['userID'] != $this->session->userdata('userID') ? '
														' . (is_userConnect('following', $page['userID']) ? '
															<a class="btn btn-warning" id="follow" href="' . base_url('user/follow/' . $page['userName']) . '"><i class="fa fa-refresh" id="follow-icon"></i> <span class="follow-text">' . phrase('unfollow') . '</span></a>
														' : '
															<a class="btn btn-info" id="follow" href="' . base_url('user/follow/' . $page['userName']) . '"><i class="fa fa-refresh" id="follow-icon"></i> <span class="follow-text">' . phrase('follow') . '</span></a>
														') . '
														' . (is_userConnect('friendship', $page['userID']) ? '
															<a class="btn btn-danger" id="friendship" href="' . base_url('user/friendship/' . $page['userName']) . '""><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase('unfriend') . '</span></a>
														' : (is_userConnect('request', $page['userID']) ? '
															<a class="btn btn-danger" id="friendship" href="' . base_url('user/friendship/' . $page['userName']) . '""><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase('waiting_approval') . '</span></a>
														' : (is_userConnect('pending', $page['userID']) ? '
															<a class="btn btn-info" id="friendship" href="' . base_url('user/friendship/' . $page['userName']) . '""><i class="fa fa-check-circle" id="friend-icon"></i> <span class="friend-text">' . phrase('confirm_request') . '</span></a>
														' : '
															<a class="btn btn-success" id="friendship" href="' . base_url('user/friendship/' . $page['userName']) . '"><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase('add_friend') . '</span></a>
														'))) . '
														' : '
															<a href="#profileEdit" class="btn btn-primary editProfile" data-toggle="modal"><i class="fa fa-cogs"></i> ' . phrase('update_info') . '</a>
														') . '
													</span>
												';
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if($page['userID'] == $this->session->userdata('userID')) { ?>
		
	<div id="profileEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal_table" style="max-width:600px">
			<div class="modal-content modal_cell">
				<div class="modal-content">
					<div class="container">
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
									<h3 id="myModalLabel"><i class="fa fa-edit"></i> &nbsp; <?php echo phrase('complete_your_account'); ?></h3>
								</div>
								<form action="<?php echo base_url('user/edit_profile'); ?>" method="post" class="form-horizontal submitForm" data-save="<?php echo phrase('update'); ?>" data-saving="<?php echo phrase('updating'); ?>" data-alert="<?php echo phrase('unable_to_update_your_profile'); ?>">
									<div class="modal-body">
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('full_name'); ?></label>
											<div class="col-sm-6">
												<input type="text" class="form-control" name="full_name" value="<?php echo $page['full_name']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('gender'); ?></label>
											<div class="col-sm-6">
												<select name="gender" class="form-control">
													<option value=""><?php echo phrase('select_gender'); ?></option>
													<option value="l"<?php if($page['gender'] == 'l') echo ' selected'; ?>><?php echo phrase('man'); ?></option>
													<option value="p"<?php if($page['gender'] == 'p') echo ' selected'; ?>><?php echo phrase('woman'); ?></option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('age'); ?></label>
											<div class="col-sm-6">
												<input type="text" class="form-control" name="age" value="<?php echo $page['age']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('language'); ?></label>
											<div class="col-sm-6">
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
											<label class="control-label col-sm-4 text-right"><?php echo phrase('biography'); ?></label>
											<div class="col-sm-6">
												<textarea class="form-control" name="bio"><?php echo $page['bio']; ?></textarea>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-sm-6 col-sm-offset-4">
												<h4><?php echo phrase('contact_info'); ?></h4>
												<hr/>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('phone_number'); ?></label>
											<div class="col-sm-6">
												<input type="text" class="form-control" name="mobile" value="<?php echo $page['mobile']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('address'); ?></label>
											<div class="col-sm-6">
												<textarea class="form-control" name="address"><?php echo $page['address']; ?></textarea>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-sm-6 col-sm-offset-4">
												<h4><?php echo phrase('account_info'); ?></h4>
												<hr/>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('email'); ?></label>
											<div class="col-sm-6">
												<input type="text" class="form-control" name="email" value="<?php echo $page['email']; ?>" />
											</div>
										</div>
										<?php if($this->session->userdata('newRegister')) { ?>
										
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('choose_username'); ?></label>
											<div class="col-sm-6">
												<input type="text" class="form-control" name="username" value="<?php echo $page['userName']; ?>" />
											</div>
										</div>
										
										<?php } ?>
										
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('password'); ?></label>
											<div class="col-sm-6">
												<input type="password" class="form-control" name="password" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4 text-right"><?php echo phrase('retype_password'); ?></label>
											<div class="col-sm-6">
												<input type="password" class="form-control" name="con_password" />
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-12">
												<div class="statusHolder"></div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<div class="col-xs-4 text-left">
											<button type="button" data-dismiss="modal" class="btn btn-default"><i class="fa fa-times"></i> <?php echo phrase('close'); ?></button>
										</div>
										<div class="col-xs-8">
											<input type="hidden" name="hash" value="<?php echo sha1($this->session->userdata('userName')); ?>" />
											<button class="btn btn-success submitBtn" type="submit"><i class="fa fa-save"></i> <?php echo phrase('save'); ?></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		<?php if($this->session->userdata('newRegister')) { ?>
		$(window).load(function(){
			$('#profileEdit').modal('show');
		});
		<?php } else { ?>
		$(document).on('click', '.editProfile', function(){
			$('#profileEdit').modal('show');
		});
		<?php } ?>
		
	</script>

	<?php } ?>