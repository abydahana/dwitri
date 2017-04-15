
	<div class="jumbotron text-center">
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<img src="<?php echo base_url('themes/' . $this->settings['theme'] . '/images/large_logo.png'); ?>" alt="logo" class="img-responsive" style="max-width:200px" />
					<br />
					<h2><?php echo $this->settings['siteTitle']; ?></h2> 
					<p class="meta">
					
						<?php echo $this->settings['siteDescription']; ?>
						
					</p>   
				</div>
			</div>
		</div>
	</div>
	
	<div id="map" class="first-child preloader"></div>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-1">
				<h3><?php echo phrase('find_us'); ?></h3>
			
				<?php
					if(!empty($this->settings['siteAddress'])) {
						echo '<div class="row"><div class="col-xs-4" style="padding-bottom:0;">' . phrase('address') . '</div><div class="col-xs-8" style="padding-bottom:0;">' . nl2br(htmlspecialchars($this->settings['siteAddress'])) . '</div></div>';
					}
					
					if(!empty($this->settings['sitePhone'])) {
						echo '<div class="row"><div class="col-xs-4" style="padding-bottom:0;">' . phrase('phone') . '</div><div class="col-xs-8" style="padding-bottom:0;">' . htmlspecialchars($this->settings['sitePhone']) . '</div></div>';
					}
					
					if(!empty($this->settings['siteFax'])) {
						echo '<div class="row"><div class="col-xs-4" style="padding-bottom:0;">' . phrase('fax') . '</div><div class="col-xs-8" style="padding-bottom:0;">' . htmlspecialchars($this->settings['siteFax']) . '</div></div>';
					}
					
					if(!empty($this->settings['siteEmail'])) {
						echo '<div class="row"><div class="col-xs-4" style="padding-bottom:0;">' . phrase('email') . '</div><div class="col-xs-8" style="padding-bottom:0;">' . htmlspecialchars($this->settings['siteEmail']) . '</div></div>';
					}
					
					if(!empty($this->settings['siteYM'])) {
						if(is_json($this->settings['siteYM']))
						{
							echo '<div class="row"><div class="col-xs-4" style="padding-bottom:0;">' . phrase('messenger') . '</div><div class="col-xs-8" style="padding-bottom:0;">';
							$items = json_decode($this->settings['siteYM'])[0]->YM;
							$n = 0;
							foreach($items as $user_data)
							{
								$n++;
								echo '<a href="ymsgr:SendIM?' . htmlspecialchars($user_data) . '"><img src="http://opi.yahoo.com/online?u=' . $user_data . '&amp;m=g&amp;t=1&amp;l=us" border="0" alt="' . htmlspecialchars($user_data) . '"></a> ';
							}
							echo '</div></div>';
						}
					}
					
					if(!empty($this->settings['siteBBM'])) {
						if(is_json($this->settings['siteBBM']))
						{
							echo '<div class="row"><div class="col-xs-4" style="padding-bottom:0;">' . phrase('bbm_pin') . '</div><div class="col-xs-8" style="padding-bottom:0;">';
							$items = json_decode($this->settings['siteBBM'])[0]->BBM;
							$n = 0;
							foreach($items as $user_data)
							{
								$n++;
								echo '<b>' . htmlspecialchars($user_data) . '</b>, ';
							}
							echo '</div></div>';
						}
					}
				?>
				
				<div class="row"><div class="col-xs-4"><?php echo phrase('website'); ?></div><div class="col-xs-8"><?php echo base_url(); ?></div></div>
			</div>
			<div class="col-md-5 col-md-offset-1 nomargin-xs nopadding-xs">
				<div class="image-placeholder-sm">
					<div class="col-sm-12">
						<h3><?php echo phrase('send_feedback'); ?></h3>
						<form class="form-horizontal submitForm" method="post" action="<?php echo current_url(); ?>" data-save="<?php echo phrase('send'); ?>" data-saving="<?php echo phrase('sending'); ?>" data-alert="<?php echo phrase('unable_to_send_your_messages'); ?>">
							<div class="form-group">
								<div class="col-sm-6">
									<label for="name" class="control-label"><?php echo phrase('full_name'); ?></label>
									<input type="text" class="form-control" name="full_name" placeholder="<?php echo phrase('enter_full_name'); ?>" />
								</div>
								<div class="col-sm-6">
									<label for="email" class="control-label"><?php echo phrase('email'); ?></label>
									<input type="text" class="form-control" name="email" placeholder="<?php echo phrase('enter_email'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label for="name" class="control-label"><?php echo phrase('phone_number'); ?></label>
									<input type="text" class="form-control" name="phone" placeholder="<?php echo phrase('enter_phone_number'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label for="name" class="control-label"><?php echo phrase('subjects'); ?></label>
									<input type="text" class="form-control" name="subject" placeholder="<?php echo phrase('enter_subject_of_messages'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label for="message" class="control-label"><?php echo phrase('messages'); ?></label>
									<textarea name="messages" id="message" class="form-control" rows="5" placeholder="<?php echo phrase('write_messages'); ?>"></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12 statusHolder">
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-7">
									<label class="control-label">
										<input type="checkbox" value="1" name="copy_email" id="copyMessages" />
										<?php echo phrase('also_send_to_me'); ?>
									</label>
								</div>
								<div class="col-xs-5 text-right">
									<input type="hidden" name="hash" value="<?php echo sha1('c0NtacT'); ?>" />
									<button type="submit" class="btn btn-primary btn-lg submitBtn"><i class="fa fa-paper-plane"></i> <?php echo phrase('send'); ?></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="//maps.google.com/maps/api/js"></script>
	<script type="text/javascript">
		google.maps.event.addDomListener(window, 'load', init);
		function init() {
			var mapOptions = {
				center: new google.maps.LatLng(-6.280594,107.021794),
				styles: [{
					featureType:"all",
					elementType:"all",
					stylers:[{
						invert_lightness:false
					},
					{
						saturation:10
					},
					{
						lightness:10
					},
					{
						gamma:0
					},
					{
						hue:"#0066AA"
					}]
				}],
				zoom: 16,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false,
			};
			var mapElement = document.getElementById('map');
			var map = new google.maps.Map(mapElement, mapOptions);
			new google.maps.Marker({
				position: new google.maps.LatLng(-6.280594,107.021794),
				map: map
			});
		}
	</script>
	