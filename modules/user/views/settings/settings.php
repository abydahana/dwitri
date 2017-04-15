
	<div class="bg-info">
		<div class="container first-child">
			<div class="row">
				<div class="col-sm-7 col-sm-offset-1 hidden-xs">
					<h2><i class="fa fa-cogs"></i> &nbsp; <?php echo phrase('global_settings'); ?></h2>
				</div>
				<div class="col-sm-3">
					<form class="form-horizontal" action="<?php echo base_url('users'); ?>" method="post">
						<div class="input-group">
							<input type="text" class="form-control input-lg" name="query" placeholder="<?php echo phrase('search_user'); ?>" />
							<span class="input-group-btn">
								<button type="submit" class="btn btn-lg btn-success nomargin"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
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
				<form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal submitForm" data-save="<?php echo phrase('update'); ?>" data-saving="<?php echo phrase('updating'); ?>" data-alert="<?php echo phrase('unable_to_update_settings'); ?>">
					<?php foreach ($settings as $s) { ?>
					<div class="row">
						<div class="col-md-6 nomargin">
							<div class="form-group">
								<div class="col-sm-12">
									<h3 class="box-title"><i class="fa fa-building"></i> &nbsp; <?php echo phrase('site_information'); ?></h3>
								</div>
							</div>
							<hr />
							<div class="form-group">							
								<label class="control-label col-sm-12 text-left" for="siteTitle"><?php echo phrase('website_name'); ?></label>
								<div class="col-sm-12">
									<input type="text" name="siteTitle" id="siteTitle" class="form-control" value="<?php echo $s['siteTitle']; ?>" placeholder="<?php echo phrase('company_or_business_name'); ?>" required />
								</div>
							</div>
									
							<div class="form-group">							
								<label class="control-label col-sm-12 text-left" for="siteDescription"><?php echo phrase('website_description'); ?></label>
								<div class="col-sm-12">
									<textarea name="siteDescription" id="siteDescription" class="form-control" placeholder="<?php echo phrase('company_slogan_and_showcase'); ?>"><?php echo $s['siteDescription']; ?></textarea>
								</div>
							</div>
									
							<div class="form-group">							
								<label class="control-label col-sm-12 text-left" for="siteFooter"><?php echo phrase('website_footer'); ?></label>
								<div class="col-sm-12">
									<input type="text" name="siteFooter" id="siteFooter" class="form-control" value="<?php echo $s['siteFooter']; ?>" placeholder="<?php echo phrase('footer_notes'); ?>" />
								</div>
							</div>
							
							<hr />
						
							<div class="form-group">
								<div class="col-sm-6">
									<div class="row">
										<label class="control-label col-sm-12 text-left" for="siteTheme"><?php echo phrase('theme'); ?></label>
										<div class="col-sm-12">
											<select name="siteTheme" class="form-control">
											<?php
												foreach ($themesdir as $t){
													if (!is_dir($t)){
														if (($t != "index.html") && ($t != "admin")){
															$data[$t] = str_replace("\\", "", $t);
															if($data[$t] != 'admin'){
																echo '
																	<option value="' . $data[$t] . '"' . ($data[$t] == $s['siteTheme'] ? ' selected="selected"' : '') . '>' . ucwords($data[$t]) . '</option>
																';
															}
														}
													}
												}
											?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<label class="control-label col-sm-12 text-left" for="siteLang"><?php echo phrase('default_language'); ?></label>
										<div class="col-sm-12">
											<select name="siteLang" class="form-control">
							
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
								</div>
							</div>
						</div>
						 
						<div class="col-md-6 nomargin">
							<div class="form-group">
								<div class="col-sm-12">
									<h3 class="box-title"><i class="fa fa-phone"></i> &nbsp; <?php echo phrase('contact_information'); ?></h3>
								</div>
							</div>
							<hr />
							<div class="form-group">							
								<label class="control-label col-sm-12 text-left" for="siteAddress"><?php echo phrase('settings_address'); ?></label>
								<div class="col-sm-12">
									<textarea name="siteAddress" id="siteAddress" class="form-control" placeholder="<?php echo phrase('type_company_address'); ?>"><?php echo $s['siteAddress']; ?></textarea>
								</div>
							</div>
									
							<div class="form-group">							
								<label class="control-label col-sm-12 text-left" for="sitePhone"><?php echo phrase('settings_phone'); ?></label>
								<div class="col-sm-12">
									<input type="text" name="sitePhone" id="sitePhone" class="form-control" value="<?php echo $s['sitePhone']; ?>" placeholder="<?php echo phrase('type_company_phone'); ?>" />
								</div>
							</div>
									
							<div class="form-group">
								<label class="control-label col-sm-12 text-left" for="siteFax"><?php echo phrase('settings_fax'); ?></label>
								<div class="col-sm-12">
									<input type="text" name="siteFax" id="siteFax" class="form-control" value="<?php echo $s['siteFax']; ?>" placeholder="<?php echo phrase('type_company_fax'); ?>" />
								</div>
							</div>
									
							<div class="form-group">						
								<label class="control-label col-sm-12 text-left" for="siteEmail"><?php echo phrase('settings_email'); ?></label>
								<div class="col-sm-12">
									<input type="text" name="siteEmail" id="siteEmail" class="form-control" value="<?php echo $s['siteEmail']; ?>" placeholder="<?php echo phrase('type_company_email'); ?>" />
								</div>
							</div>
							
							<hr />
									
							<div class="form-group">
								<div class="col-sm-12">
									<h3><?php echo phrase('yahoo_messenger'); ?> <a class="btn btn-sm btn-default pull-right" onclick="ymFunction()"><?php echo phrase('add'); ?></a></h3>
									</hr />
									<span id="ymForm">
										<?php
											if(is_json($s['siteYM']))
											{
												$items = json_decode($s['siteYM'])[0]->YM;
												$n = 0;
												foreach($items as $user_data)
												{
													$n++;
													echo '<div style="position:relative" id="idYM_' . $n . '"><input name="siteYM[]" placeholder="' . phrase('ym_username_or_email') . ' ' . $n . '" class="form-control" type="text" value="' . $user_data . '"><i class="fa fa-times" style="position:absolute;top:10px;right:10px;cursor:pointer"></i></div>';
												}
											}
										?>
									</span>
								</div>
							</div>
							
							<hr />
									
							<div class="form-group">
								<div class="col-sm-12">
									<h3><?php echo phrase('bbm_pin'); ?> <a class="btn btn-sm btn-default pull-right" onclick="bbmFunction()"><?php echo phrase('add'); ?></a></h3>
									</hr />
									<span id="bbmForm">
										<?php
											if(is_json($s['siteBBM']))
											{
												$items = json_decode($s['siteBBM'])[0]->BBM;
												$n = 0;
												foreach($items as $user_data)
												{
													$n++;
													echo '<div style="position:relative" id="idBBM_' . $n . '"><input name="siteBBM[]" placeholder="' . phrase('bbm_pin') . ' ' . $n . '" class="form-control" type="text" value="' . $user_data . '"><i class="fa fa-times" style="position:absolute;top:10px;right:10px;cursor:pointer"></i></div>';
												}
											}
										?>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 statusHolder">
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 text-right">
							<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
							<button class="btn btn-primary btn-lg submitBtn" type="submit"><i class="fa fa-save"></i> <?php echo phrase('update'); ?></button>
						</div>
					</div>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
	
	<?php 
		if(is_json($s['siteYM']))
		{
			echo 'var a = ' . count(json_decode($s['siteYM'])[0]->YM) . ';';
		} else {
			echo 'var a = 0;';
		}
		if(is_json($s['siteBBM']))
		{
			echo 'var b = ' . count(json_decode($s['siteBBM'])[0]->BBM) . ';';
		} else {
			echo 'var b = 0;';
		}
	?>
	
	function incrementa(){
		a += 1;
	}
	function incrementb(){
		b += 1;
	}
	function removeElement(parentDiv, childDiv){
		if (childDiv == parentDiv){
			alert("<?php echo phrase('parent_cannot_removed'); ?>");
		}
		else if (document.getElementById(childDiv)){
			var child = document.getElementById(childDiv);
			var parent = document.getElementById(parentDiv);
			parent.removeChild(child);
		}
		else{
			alert("<?php echo phrase('child_removed_or_not_exist.'); ?>");
			return false;
		}
	}
	function ymFunction(){
		incrementa();
		var r = document.createElement('div');
		r.setAttribute("style", "position:relative");
		var y = document.createElement("input");
		y.setAttribute("type", "text");
		y.setAttribute("class", "form-control");
		y.setAttribute("placeholder", "<?php echo phrase('ym_username_or_email'); ?> " + a);
		var g = document.createElement("i");
		g.setAttribute("class", "fa fa-times");
		g.setAttribute("style", "position:absolute;top:10px;right:10px;cursor:pointer");
		y.setAttribute("name", "siteYM[]");
		r.appendChild(y);
		g.setAttribute("onclick", "removeElement('ymForm','idYM_" + a + "')");
		r.appendChild(g);
		r.setAttribute("id", "idYM_" + a);
		document.getElementById("ymForm").appendChild(r);
	}
	function bbmFunction(){
		incrementb();
		var r = document.createElement('div');
		r.setAttribute("style", "position:relative");
		var y = document.createElement("input");
		y.setAttribute("type", "text");
		y.setAttribute("class", "form-control");
		y.setAttribute("placeholder", "<?php echo phrase('bbm_pin'); ?> " + b);
		var g = document.createElement("i");
		g.setAttribute("class", "fa fa-times");
		g.setAttribute("style", "position:absolute;top:10px;right:10px;cursor:pointer");
		y.setAttribute("name", "siteBBM[]");
		r.appendChild(y);
		g.setAttribute("onclick", "removeElement('bbmForm','idBBM_" + b + "')");
		r.appendChild(g);
		r.setAttribute("id", "idBBM_" + b);
		document.getElementById("bbmForm").appendChild(r);
	}
	function resetElements(){
		document.getElementById('ymForm, bbmForm').innerHTML = '';
	}
	
	$(function () {
		
		if(document.getElementById('file_upload'))
			{
				function prepareUpload(event)
				{
					files = event.target.files;
					uploadFiles(event);
				}
		
				function uploadFiles(event)
				{
					event.stopPropagation();
					event.preventDefault();
		
					$('#loading_pic').show();
		
					var data = new FormData();
					$.each(files, function(key, value){ data.append(key, value); });
					
					$.ajax({
						url: '<?php echo base_url(); ?>backend/settings/submit/?files',
						type: 'POST',
						data: data,
						cache: false,
						dataType: 'json',
						processData: false,
						contentType: false,
						success: function(data, textStatus, jqXHR){
							if(data!='0')
							{
								$('#logo_preloaded').show();
								document.getElementById('logo_preloaded').src = '<?php echo base_url(); ?>uploads/' + data;
								document.getElementById('siteLogo').value = data;
								$('#loading_pic').hide();
							}
							else
								alert('<?php echo phrase('settings_image_error'); ?>');
								$('#loading_pic').hide();
						}
					});
				}
				
				var files;
				$('input[type=file]').on('change', prepareUpload);
			}
		});	
	</script>