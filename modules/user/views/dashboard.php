
	<div class="jumbotron">
		<div class="container first-child">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1 text-shadow text-center hidden-xs">
					<h2><i class="fa fa-info-circle"></i> &nbsp; <?php echo phrase('welcome_to_user_control_panel'); ?></h2>
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
			
				<div id="abycms-chart" style="width:100%;height:430px"></div>
				
			</div>
		</div>
	</div>
	
	<link href="<?php echo base_url('themes/' . $this->settings['theme'] . '/css/chart.css'); ?>" rel="stylesheet">
	<script src="<?php echo base_url('themes/' . $this->settings['theme'] . '/js/chart.js'); ?>"></script>
	<script type="text/javascript">
		$(function () {
			$('#abycms-chart').highcharts({
				chart: {
					type: 'areaspline',
					backgroundColor:null
				},
				title: {
					text: '<?php echo phrase('website_and_traffic_analytics'); ?>'
				},
				xAxis: {
					categories: [
						<?php
						foreach ($visitors as $name => $row)
						{
							$month = date('D', strtotime(date('Y-m-' . $name)));
							echo '"' . phrase(strtolower($month)) . ' ' . $name . '",';
						}
						?>
					]
				},
				yAxis: {
					allowDecimals: false,
					minorTickInterval: 10,
					title: {
						text: '<?php echo phrase('hit_units'); ?>'
					}
				},
				tooltip: {
					shared: true,
					valueSuffix: ' <?php echo phrase('hits'); ?>'
				},
				credits: {
					enabled: false
				},
				plotOptions: {
					areaspline: {
						fillOpacity: .03
					}
				},
				series: [
					{
						name: '<?php echo $this->onlinetracker->users() . ' ' . phrase('online'); ?>'
					},
					{
						name: '<?php echo phrase('visitors'); ?>',
						data: [
							<?php
							foreach ($visitors as $row)
							{
								if (!empty($row)) {
									foreach($row as $r){
										echo round($r->amount) . ',';
									}
								}
								else
								{
									echo '0,';
								}
							}
							?>
							
						]
					},
					{
						name: '<?php echo phrase('posts'); ?>',
						data: [
							<?php
							foreach ($posts as $row)
							{
								if (!empty($row)) {
									foreach($row as $r){
										echo round($r->amount) . ',';
									}
								}
								else
								{
									echo '0,';
								}
							}
							?>
							
						]
					},
					{
						name: '<?php echo phrase('snapshots'); ?>',
						data: [
							<?php
							foreach ($snapshots as $row)
							{
								if (!empty($row)) {
									foreach($row as $r){
										echo round($r->amount) . ',';
									}
								}
								else
								{
									echo '0,';
								}
							}
							?>
							
						]
					},
					{
						name: '<?php echo phrase('open_letters'); ?>',
						data: [
							<?php
							foreach ($openletters as $row)
							{
								if (!empty($row)) {
									foreach($row as $r){
										echo round($r->amount) . ',';
									}
								}
								else
								{
									echo '0,';
								}
							}
							?>
							
						]
					}
				]
			});
		});
	</script>