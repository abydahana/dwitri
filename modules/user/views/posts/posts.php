
	<?php 
		$totSegments = $this->uri->total_segments();
		$uriSegments = $this->uri->segment($totSegments);
		if(!is_numeric($uriSegments)){
			$offset = 0;
		} else if(is_numeric($uriSegments)){
			$offset = $this->uri->segment($totSegments);
		}
		$limit = 12;
	?>
	
	<div class="bg-info">
		<div class="container first-child">
			<div class="row">
				<div class="col-sm-7 col-sm-offset-1 hidden-xs">
					<h2><i class="fa fa-newspaper-o"></i> &nbsp; <?php echo phrase('posts'); ?></h2>
				</div>
				<div class="col-sm-3">
					<div class="col-12-xs">
						<a href="<?php echo base_url('user/posts/add'); ?>" class="btn btn-lg btn-block btn-primary newPost"><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('write_article'); ?></a>
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
				<table class="table table-hover">
					<tr>
						<th>
							<?php echo phrase('title'); ?>
						</th>
						<th class="hidden-xs">
							<?php echo phrase('date_created'); ?>
						</th>
						<th class="hidden-xs">
							<?php echo phrase('comments'); ?>
						</th>
						<th class="text-right col-xs-4">
							<?php echo phrase('action'); ?>
						</th>
					</tr>
					
					<?php
						$posts = getPosts('posts', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID')), null, $limit, $offset);
						if($posts)
						{
							foreach($posts as $c)
							{
								echo '
									<tr id="post' . $c['postID'] . '">
										<td>
											<a href="' . base_url('posts/' . $c['postSlug']) . '" target="_blank">' . truncate($c['postTitle'], 50) . '</a>
										</td>
										<td class="hidden-xs">
											' . date('d M Y', $c['timestamp']) . '
										</td>
										<td class="hidden-xs">
											' . countComments('posts', $c['postID']) . '
										</td>
										<td class="text-right col-xs-4">
											<div class="btn-group">
												<a class="btn btn-default btn-sm newPost" href="' . base_url('user/posts/edit/' . $c['postSlug']) . '" data-push="tooltip" data-placement="top" title="' . phrase('edit_post') . '"><i class="btn-icon-only fa fa-edit"> </i></a>
												<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/posts/remove/' . $c['postID']) . '\', \'post' . $c['postID'] . '\')" data-push="tooltip" data-placement="top" title="' . phrase('remove') . '"><i class="btn-icon-only fa fa-times"> </i></a>
											</div>
										</td>
									</tr>
								';
							}
						}
					?>
					
				</table>
				
				<hr/>
				<div class="row">
					<div class="col-sm-12 text-center">
					
						<?php echo generatePagination('posts', null, ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID')), 'user', $limit, $offset); ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>