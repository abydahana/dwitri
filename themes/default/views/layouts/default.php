<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?><!DOCTYPE html>
<html lang="en-US" class="no-js" prefix="og: http://ogp.me/ns#">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title><?php echo truncate($meta['title'], 160); ?> | <?php echo $this->settings['siteTitle']; ?></title>
		<meta name="description" content="<?php echo truncate($meta['descriptions'], 260); ?>" />
		<meta name="keywords" content="<?php echo truncate($meta['keywords'], 160); ?>" />
		<meta name="author" content="<?php echo (isset($meta['author']) ? $meta['author'] : $this->settings['siteTitle']); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		
		<meta property="og:title" content="<?php echo truncate($meta['title'], 160); ?> | <?php echo $this->settings['siteTitle']; ?>" />
		<meta property="og:type" content="article"/>
		<meta property="og:url" content="<?php echo current_url(); ?>"/>
		<meta property="og:site_name" content="dwitri.com" />
		<meta property="article:author" content="<?php echo (isset($meta['author']) ? $meta['author'] : 'https://www.facebook.com/DWITRIcom'); ?>" />
		<meta property="og:description" content="<?php echo truncate($meta['descriptions'], 260); ?>" />
		<meta property="og:image" content="<?php echo truncate($meta['image'], 160); ?>" />
		<meta property="fb:app_id" content="423905061028802"/>
		
		<link href="<?php echo base_url('themes/default/css/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('themes/default/css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('themes/default/css/animate.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('themes/default/css/emoji.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('themes/default/css/styles.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('themes/default/fonts/raleway/Raleway.css'); ?>" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<script src="<?php echo base_url('themes/default/js/jquery.js'); ?>"></script>
		<script src="<?php echo base_url('themes/default/js/bootstrap.min.js'); ?>"></script>
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('themes/default/images/favicon.ico'); ?>">
	</head>
	<body class="fixed">
		<nav class="navbar navbar-custom navbar-fixed-top navbar-inverse text-uppercase hidden-xs hidden-sm">
			<div class="container">
				<div class="collapse navbar-collapse" role="navigation">
					<a class="navbar-brand ajaxLoad" href="<?php echo base_url(); ?>"><img src="<?php echo base_url('themes/default/images/logo.png'); ?>" alt="logo" height="100%" /></a>
					<ul class="nav navbar-nav">
						<li<?php echo ($this->uri->segment(1) == 'posts' ? ' class="active"' : ''); ?>>
							<a href="<?php echo base_url('posts'); ?>" class="ajaxLoad"><i class="fa fa-newspaper-o"></i> <?php echo phrase('posts'); ?></a>
						</li>
						<li<?php echo ($this->uri->segment(1) == 'snapshots' ? ' class="active"' : ''); ?>>
							<a href="<?php echo base_url('snapshots'); ?>" class="ajaxLoad"><i class="fa fa-image"></i> <?php echo phrase('snapshot'); ?></a>
						</li>
						<li<?php echo ($this->uri->segment(1) == 'openletters' ? ' class="active"' : ''); ?>>
							<a href="<?php echo base_url('openletters'); ?>" class="ajaxLoad"><i class="fa fa-paperclip"></i> <?php echo phrase('open_letter'); ?></a>
						</li>
						<li<?php echo ($this->uri->segment(1) == 'tv' ? ' class="active"' : ''); ?>>
							<a href="<?php echo base_url('tv'); ?>" class="ajaxLoad"><i class="fa fa-desktop"></i> <?php echo phrase('tv_channels'); ?></a>
						</li>
						
						<?php if($this->session->userdata('loggedIn')) { ?>
						
						<li class="hidden-md<?php echo ($this->uri->segment(1) == $this->session->userdata('userName') ? ' active' : ''); ?>">
							<a href="<?php echo base_url($this->session->userdata('userName')); ?>" class="ajaxLoad"><i class="fa fa-clock-o"></i> <?php echo phrase('timeline'); ?></a>
						</li>
						
						<?php } ?>
						
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li<?php echo ($this->uri->segment(1) == 'search' ? ' class="active"' : ''); ?>>
							<a href="#search" data-toggle="modal" data-push="tooltip" data-placement="bottom" data-title="<?php echo phrase('search'); ?>"><i class="fa fa-search"></i></a>
						</li>
						<?php if($this->session->userdata('loggedIn')) { ?>
						<li class="dropdown">
							<a href="javascript:void(0)" class="dropdown-toggle load-notifications" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" aria-close="false"> &nbsp;  <span class="countAlert<?php echo (countAlert($this->session->userdata('userID')) > 0 ? ' badge bg-red' : ''); ?>" style="font-size:16px"><i class="fa fa-bell hidden-xs"></i> <?php echo (countAlert($this->session->userdata('userID')) > 0 ? countAlert($this->session->userdata('userID')) : ''); ?></span></a>
							<div class="dropdown-menu notifications">
								<div id="slimScroll" class="notifications-area"></div>
								<div class="notifications-footer">
									<a href="<?php echo base_url('user/notifications'); ?>" class="ajaxLoad"><b><?php echo phrase('view_all_notifications'); ?></b></a>
								</div>
							</div>
						</li>
						<li>
							<a href="#posts" data-toggle="modal" data-push="tooltip" data-placement="bottom" data-title="<?php echo phrase('send_something'); ?>"><span class="btn btn-warning"><i class="fa fa-plus"></i></span></a>
						</li>
						
						<?php /* Messages Placeholder
						<li class="dropdown">
							<a href="#" class="dropdown-toggle load-notifications" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" aria-close="false"> &nbsp;  <span class="countAlert<?php echo (countAlert($this->session->userdata('userID')) > 0 ? ' badge bg-red' : ''); ?>"><i class="fa fa-envelope hidden-xs"></i><span class="visible-xs"> <?php echo phrase('notifications'); ?></span><b id="countAlerts"><?php echo (countAlert($this->session->userdata('userID')) > 0 ? countAlert($this->session->userdata('userID')) : ''); ?></b></span></a>
							<div class="dropdown-menu notifications">
								<div class="notifications-area"></div>
								<div class="notifications-footer">
									<a href="#"><?php echo phrase('view_all_notifications'); ?></a>
								</div>
							</div>
						</li>
						*/ ?>
						
						<li class="dropdown<?php echo ($this->uri->segment(1) == 'user' ? ' active' : ''); ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($this->session->userdata('userID')), 1)); ?>" width="20" height="20" alt="..." class="rounded" /> <?php echo truncate($this->session->userdata('full_name'), 4); ?> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo base_url('user/dashboard'); ?>" class="ajaxLoad"><i class="fa fa-dashboard"></i> <?php echo phrase('dashboard'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url(getUsernameByID($this->session->userdata('userID'))); ?>" class="ajaxLoad"><i class="fa fa-desktop"></i> <?php echo phrase('timeline'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('user/posts'); ?>" class="ajaxLoad"><i class="fa fa-newspaper-o"></i> <?php echo phrase('articles'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('user/snapshots'); ?>" class="ajaxLoad"><i class="fa fa-image"></i> <?php echo phrase('snapshots'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('user/openletters'); ?>" class="ajaxLoad"><i class="fa fa-paperclip"></i> <?php echo phrase('open_letters'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('user/tv'); ?>" class="ajaxLoad"><i class="fa fa-desktop"></i> <?php echo phrase('tv_channels'); ?></a>
								</li>
								<li role="separator" class="divider"></li>
							
								<?php if($this->session->userdata('user_level') == 1) { ?>
								<li>
									<a href="<?php echo base_url('user/pages'); ?>" class="ajaxLoad"><i class="fa fa-file-o"></i> <?php echo phrase('manage_pages'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('user/categories'); ?>" class="ajaxLoad"><i class="fa fa-sitemap"></i> <?php echo phrase('manage_category'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('user/users'); ?>" class="ajaxLoad"><i class="fa fa-users"></i> <?php echo phrase('manage_user'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('user/translate'); ?>" class="ajaxLoad"><i class="fa fa-language"></i> <?php echo phrase('translate'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('user/settings'); ?>" class="ajaxLoad"><i class="fa fa-cog"></i> <?php echo phrase('global_settings'); ?></a>
								</li>
								<li role="separator" class="divider"></li>
								
								<?php } ?>
								
								<li>
									<a href="<?php echo base_url('user/edit_profile'); ?>" class="ajaxLoad"><i class="fa fa-cogs"></i> <?php echo phrase('edit_profile'); ?></a>
								</li>
								<li role="separator" class="divider"></li>
								<li>
									<a href="<?php echo base_url('user/logout'); ?>" class="ajaxLoad"><b><i class="fa fa-ban"></i> <?php echo phrase('logout'); ?></b></a>
								</li>
							</ul>
						</li>
						<?php } else { ?>
						<li style="border:none"<?php echo ($this->uri->segment(1) == 'user' ? ' class="active"' : ''); ?>>
							<a data-toggle="modal" href="#login"><span class="btn btn-success"><i class="fa fa-lock"></i> <?php echo phrase('login'); ?></b></span></a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div><!-- /.container -->
		</nav><!-- /.navbar -->
		<div class="content-wrapper" id="page-content">
		
		<?php echo $template['body']; ?>
		
		</div>
		<div class="clearfix"></div>
		<div class="footer">
			<div class="container">
				<div class="row hidden-xs hidden-sm">
					<div class="col-sm-3 text-center">
						<div class="page-header">
							<h4>&nbsp;</h4>
						</div>
						<a class="ajaxLoad" href="<?php echo base_url('pages/contact'); ?>"><img src="<?php echo base_url('themes/default/images/large_logo.png'); ?>" alt="logo" width="128" /></a>
					</div>
					<div class="col-sm-3">
						<div class="page-header" style="margin-bottom:0">
							<form class="form-horizontal submitForm" action="<?php echo base_url('search'); ?>" method="post" data-save="<?php echo phrase('search'); ?>" data-saving="<?php echo phrase('searching'); ?>" data-alert="<?php echo phrase('unable_to_submit_inquiry'); ?>">
								<div class="input-group">
									<input type="text" class="form-control" name="query" placeholder="<?php echo phrase('type_keywords'); ?>" />
									<span class="input-group-btn">
										<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
										<button type="submit" class="btn btn-success nomargin submitBtn"><i class="fa fa-search"></i> <?php echo phrase('search'); ?></button>
									</span>
								</div>
								<div class="form-group">
									<div class="col-sm-12 statusHolder">
									</div>
								</div>
							</form>
						</div>
						<b><?php echo phrase('latest_quiries'); ?>:</b>
						<br />
						
						<?php echo widget_hashTags(false, 20); ?>
						
						<br />
					</div>
					<div class="col-sm-3">
						<div class="page-header">
							<h4><i class="fa fa-clock-o"></i> &nbsp; <?php echo phrase('latest_news'); ?></h4>
						</div>
		
						<?php echo widget_sidebarNews(); ?>
							
					</div>
					<div class="col-sm-2">
						<div class="page-header">
							<h4><i class="fa fa-link"></i> &nbsp; <?php echo phrase('navigation'); ?></h4>
						</div>
						<?php echo generatePageNav(true); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center text-muted">
						<a data-toggle="modal" href="#language"><i class="fa fa-language"></i> <?php echo phrase('language'); ?></a>
						&nbsp; - &nbsp; 
						<a href="<?php echo base_url('users'); ?>" class="ajaxLoad"><?php echo phrase('search_user'); ?></a>
						&nbsp; - &nbsp; 
						<a href="<?php echo base_url('pages/contact'); ?>" class="ajaxLoad"><?php echo phrase('feedback'); ?></a>
						<br />
						<?php echo phrase('another_awesome_build_with'); ?> Codeigniter <?php echo CI_VERSION; ?> <?php echo phrase('and'); ?> Bootstrap 3
						<br />
						<?php echo phrase('hosted_by_sponsor_server'); ?>, <a href="//extranet.co.id" target="_blank" />GES</a>
					</div>
				</div>
			</div>
		</div>
		<div class="navbar navbar-custom header-mobile navbar-fixed-top visible-xs visible-sm">
			<div class="container">
				<div class="row">
					<div class="col-xs-2 nomargin nopadding">
						<a href="javascript:void(0)" style="color:#fff;line-height:60px" data-toggle="offcanvas" data-target=".menu-left" data-canvas="body" data-placement="left">
							<span class="btn" style="padding:0 0"><i class="fa fa-bars fa-2x"></i></span>
						</a>
					</div>
					<div class="col-xs-8 nomargin nopadding text-center pushTitle" style="color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:60px">
						<?php echo truncate($meta['title'], 160); ?>
					</div>
					<div class="col-xs-2 nomargin nopadding text-right">
						<?php if($this->session->userdata('loggedIn')){ ?>
							<a href="javascript:void(0)" style="color:#fff;line-height:60px" data-toggle="offcanvas" data-target=".menu-right" data-canvas="body" data-placement="right">
								<span class="btn" style="padding:0 0">
									<span class="countAlert<?php echo (countAlert($this->session->userdata('userID')) > 0 ? ' badge' : ''); ?>"><?php echo (countAlert($this->session->userdata('userID')) < 0 ? countAlert($this->session->userdata('userID')) : ''); ?><b class="countAlerts"><?php echo (countAlert($this->session->userdata('userID')) > 0 ? countAlert($this->session->userdata('userID')) : ''); ?></b></span> 
									<img src="<?php echo base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($this->session->userdata('userID')), 1)); ?>" width="28" height="28" alt="..." class="rounded bordered" />
								</span>
							</a>
						<?php }else{ ?>
							<a href="#login" data-toggle="modal" style="color:#fff;line-height:60px">
								<span class="btn" style="border:1px solid #fff"><i class="fa fa-lock"></i></span>
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="menu-left navmenu navmenu-default navmenu-fixed-left">
			<div style="line-height:60px;font-weight:bold;text-transform:uppercase;color:#fff;text-align:center;padding:0 15px"><?php echo phrase('main_menu'); ?></div>
			<ul class="navmenu-nav">
				<li>
					<form class="form-horizontal" action="<?php echo base_url('search'); ?>" method="post">
						<div class="input-group">
							<input type="text" class="form-control" name="query" placeholder="<?php echo phrase('type_keywords'); ?>" />
							<span class="input-group-btn">
								<button type="submit" class="btn btn-secondary nomargin"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
				</li>
				
				<li>
					<a href="<?php echo base_url(); ?>" class="ajaxLoad"><i class="fa fa-home"></i> <?php echo phrase('home'); ?></a>
				</li>
						
				<?php if($this->session->userdata('loggedIn')) { ?>
				
				<li>
					<a href="<?php echo base_url(getUsernameByID($this->session->userdata('userID'))); ?>" class="ajaxLoad"><i class="fa fa-clock-o"></i>  <?php echo phrase('timeline'); ?></a>
				</li>
				
				<?php } ?>
				
				<li>
					<a href="<?php echo base_url('posts'); ?>" class="ajaxLoad"><i class="fa fa-newspaper-o"></i> <?php echo phrase('posts'); ?></a>
				</li>
				<li>
					<a href="<?php echo base_url('snapshots'); ?>" class="ajaxLoad"><i class="fa fa-image"></i> <?php echo phrase('snapshot'); ?></a>
				</li>
				<li>
					<a href="<?php echo base_url('openletters'); ?>" class="ajaxLoad"><i class="fa fa-paperclip"></i> <?php echo phrase('openletters'); ?></a>
				</li>
				<li>
					<a href="<?php echo base_url('tv'); ?>" class="ajaxLoad"><i class="fa fa-desktop"></i> <?php echo phrase('tv_channels'); ?></a>
				</li>
				<li>
					<a data-toggle="modal" href="#language"><i class="fa fa-language"></i> <?php echo phrase('language'); ?></a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list"></i> <?php echo phrase('other'); ?> <span class="more"><i class="fa fa-chevron-down"></i></span></a>
					<ul class="dropdown-menu navmenu-nav">
						<?php echo generatePageNav(true); ?>
						<li>
							<a href="<?php echo base_url('pages/contact'); ?>" class="ajaxLoad"><i class="fa fa-phone"></i> <?php echo phrase('feedback'); ?></a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<?php if($this->session->userdata('loggedIn')) { ?>
		<div class="menu-right navmenu navmenu-default navmenu-fixed-right">
			<div style="line-height:60px;font-weight:bold;text-transform:uppercase;padding:0 15px">
				<a href="<?php echo base_url(getUsernameByID($this->session->userdata('userID'))); ?>" class="ajaxLoad" style="color:#fff"><?php echo truncate($this->session->userdata('full_name'), 22); ?></a>
				<a href="<?php echo base_url('user/edit_profile'); ?>" class="ajaxLoad pull-right" style="line-height:60px;color:#fff"><i class="fa fa-cog"></i></a>
			</div>
			<ul class="navmenu-nav">
				<li>
					<a href="<?php echo base_url('user/notifications'); ?>" class="ajaxLoad"><i class="fa fa-bell"></i> <?php echo phrase('notifications'); ?>
					<span class="countAlert more<?php echo (countAlert($this->session->userdata('userID')) > 0 ? ' badge' : ''); ?>"><?php echo (countAlert($this->session->userdata('userID')) < 0 ? countAlert($this->session->userdata('userID')) : ''); ?><b class="countAlerts"><?php echo (countAlert($this->session->userdata('userID')) > 0 ? countAlert($this->session->userdata('userID')) : ''); ?></b></span> 
				</li>
				<li>
					<a href="<?php echo base_url('user/posts'); ?>" class="ajaxLoad"><i class="fa fa-newspaper-o"></i> <?php echo phrase('articles'); ?><span class="badge more"><?php echo countPosts('posts', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))); ?></span></a>
				</li>
				<li>
					<a href="<?php echo base_url('user/snapshots'); ?>" class="ajaxLoad"><i class="fa fa-image"></i> <?php echo phrase('snapshots'); ?><span class="badge more"><?php echo countPosts('snapshots', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))); ?></span></a>
				</li>
				<li>
					<a href="<?php echo base_url('user/openletters'); ?>" class="ajaxLoad"><i class="fa fa-paperclip"></i> <?php echo phrase('open_letters'); ?><span class="badge more"><?php echo countPosts('openletters', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))); ?></span></a>
				</li>
				<li>
					<a href="<?php echo base_url('user/tv'); ?>" class="ajaxLoad"><i class="fa fa-desktop"></i> <?php echo phrase('tv_channels'); ?><span class="badge more"><?php echo countPosts('tv', ($this->session->userdata('user_level') == 1 ? null : $this->session->userdata('userID'))); ?></span></a>
				</li>
				<?php if($this->session->userdata('user_level') == 1) { ?>
				<li>
					<a href="<?php echo base_url('user/categories'); ?>" class="ajaxLoad"><i class="fa fa-sitemap"></i> <?php echo phrase('manage_category'); ?></a>
				</li>
				<li>
					<a href="<?php echo base_url('user/users'); ?>" class="ajaxLoad"><i class="fa fa-users"></i> <?php echo phrase('manage_user'); ?></a>
				</li>
				<li>
					<a href="<?php echo base_url('user/translate'); ?>" class="ajaxLoad"><i class="fa fa-language"></i> <?php echo phrase('translate'); ?></a>
				</li>
				<li>
					<a href="<?php echo base_url('user/settings'); ?>" class="ajaxLoad"><i class="fa fa-cog"></i> <?php echo phrase('global_settings'); ?></a>
				</li>
				<?php } ?>
				<li>
					<a href="<?php echo base_url('user/logout'); ?>" class="ajaxLoad"><b><i class="fa fa-ban"></i> <?php echo phrase('logout'); ?></b></a>
				</li>
			</ul>
		</div>
		<?php } ?>
		
		<?php if(!$this->session->userdata('loggedIn')) { ?>
				
		<div id="login" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table">
				<div class="modal-content modal_cell">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-lock"></i> <?php echo phrase('dashboard_access'); ?></h3>
					</div>
					<div class="modal-body" style="padding-top:0;padding-bottom:0">
						<div class="row">
							<div class="col-sm-7 nomargin">
								<form action="<?php echo base_url('user/login'); ?>" method="post" class="submitForm" data-save="<?php echo phrase('sign_in'); ?>" data-saving="<?php echo phrase('signing_in'); ?>" data-alert="<?php echo phrase('unable_to_signing_in'); ?>">
									<div class="input-group col-sm-12">
										<span class="input-group-addon"><i class="fa fa-at"></i></span>
										<input type="text" class="form-control input-lg" name="username" value="<?php echo set_value('email'); ?>" placeholder="<?php echo phrase('username_or_email'); ?>" />
									</div>
									<div class="input-group col-sm-12">
										<span class="input-group-addon"><i class="fa fa-qrcode"></i></span>
										<input type="password" class="form-control input-lg" name="password" value="<?php echo set_value('password'); ?>" placeholder="<?php echo phrase('type_your_password'); ?>" />
									</div>
									<div class="statusHolder"></div>
									<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
									<button class="btn btn-primary btn-lg btn-block submitBtn" type="submit"><i class="fa fa-key"></i> <?php echo phrase('login'); ?></button>
								</form>
							</div>
							<div class="col-sm-5 nomargin loginBorder text-center">
								<br /><br />
								<a class="btn btn-primary btn-lg btn-block" href="<?php echo instantLoginURL(); ?>"><i class="fa fa-facebook"></i> &nbsp; <?php echo phrase('instant_login'); ?></a>
								<a class="ajaxLoad btn btn-success btn-lg btn-block" href="<?php echo base_url('user/register'); ?>"><i class="fa fa-user-plus"></i> &nbsp; <?php echo phrase('register'); ?></a>
								<br /><br /><br />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="col-sm-12 text-center"><?php echo phrase('login_description'); ?></div>
					</div>
				</div>
			</div>
		</div>
		
		<?php } else { ?>
		
		<!-- (Normal Modal)-->
		<div class="modal fade" id="modal_delete">
			<div class="modal-dialog modal_table" style="max-width:400px">
				<div class="modal-content modal_cell">
					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-info-circle"></i> <?php echo phrase('notification');?></h3>
					</div>
					
					<div class="modal-body">
						<?php echo phrase('are_you_sure_want_to_continue'); ?>
					</div>
					
					<div class="modal-footer">
						<div class="row">
							<div class="col-xs-6 text-left">
								<a href="#" class="btn btn-danger" id="delete_link" data-id=""><i class="fa fa-check" id="delete-icon"></i> <?php echo phrase('continue');?></a>
							</div>
							<div class="col-xs-6">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo phrase('cancel');?></button>
							</div>
						</div>
					</div>
						
				</div>
			</div>
		</div>
				
		<div id="posts" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table" style="max-width:320px">
				<div class="modal-content modal_cell">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-plus"></i> &nbsp; <?php echo phrase('post_something'); ?></h3>
					</div>
					<div class="modal-body">
						<a href="<?php echo base_url('user/posts/add'); ?>" class="btn btn-primary btn-block btn-lg newPost"><i class="fa fa-newspaper-o"></i> &nbsp; <?php echo phrase('write_article'); ?></a>
						<a href="<?php echo base_url('user/snapshots/add'); ?>" class="btn btn-info btn-block btn-lg newPost"><i class="fa fa-image"></i> &nbsp; <?php echo phrase('send_snapshot'); ?></a>
						<a href="<?php echo base_url('user/openletters/add'); ?>" class="btn btn-warning btn-block btn-lg newPost"><i class="fa fa-paperclip"></i> &nbsp; <?php echo phrase('submit_open_letter'); ?></a>
						<a href="<?php echo base_url('user/tv/add'); ?>" class="btn btn-danger btn-block btn-lg newPost"><i class="fa fa-desktop"></i> &nbsp; <?php echo phrase('submit_tv_channel'); ?></a>
					</div>
					<div class="modal-footer text-center">
						<?php echo phrase('choose_what_is_in_your_mind_right_now'); ?>
					</div>
				</div>
			</div>
		</div>
		
		<?php } ?>
			
		<!-- (Ajax Modal Large)-->
		<div id="modal_ajax" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table" style="max-width:1200px">
				<div class="modal-content modal_cell">
					<div class="modal-body rounded nopadding h-600px">
					
					</div>
				</div>
			</div>
		</div>
			
		<!-- (Ajax Modal Small)-->
		<div id="modal_ajax_sm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table" style="max-width:380px">
				<div class="modal-content modal_cell">
					<div class="modal-body rounded repostForm" style="min-height:200px;max-width:380px">
					
					</div>
				</div>
			</div>
		</div>
			
		<!-- (Alert Modal)-->
		<div id="modal_alert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table" style="max-width:480px">
				<div class="modal-content modal_cell rounded">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-ban"></i> &nbsp; <?php echo phrase('oops'); ?></h3>
					</div>
					<div class="modal-body text-center text-danger" style="padding-top:30px;padding-bottom:30px;font-weight:bold;max-width:480px">
					
					</div>
					<div class="modal-footer text-center-xs">
						<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('close'); ?></button>
					</div>
				</div>
			</div>
		</div>
			
		<!-- (Success Modal)-->
		<div id="modal_success" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table" style="max-width:480px">
				<div class="modal-content modal_cell rounded">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-check-circle"></i> &nbsp; <?php echo phrase('action_success'); ?></h3>
					</div>
					<div class="modal-body text-center text-success" style="padding-top:30px;padding-bottom:30px;font-weight:bold;max-width:480px">
					
					</div>
					<div class="modal-footer text-center-xs">
						<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?php echo phrase('close'); ?></button>
					</div>
				</div>
			</div>
		</div>
			
		<!-- (Ajax Modal Dynamic)-->
		<div id="modal_ajax_dn" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table" style="width:auto">
				<div class="modal-content modal_cell">
					
				</div>
			</div>
		</div>
				
		<div id="language" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table" style="max-width:480px">
				<div class="modal-content modal_cell">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-language"></i> &nbsp; <?php echo phrase('default_language'); ?></h3>
					</div>
					<div class="modal-body">
						<div class="col-sm-12">
							<div class="row">
								
								<?php
									$fields = $this->db->list_fields('language');
									foreach($fields as $field)
									{
										if($field == 'phrase_id' || $field == 'phrase') continue;
										
										if($this->session->userdata('language') == $field)
										{
											echo '<div class="col-sm-4 col-xs-6 text-muted"><b><i class="fa fa-circle-o"></i> ' . ucwords($field) . '</b></div>';
										}
										else
										{
											echo '<div class="col-sm-4 col-xs-6 text-muted"><a href="' . base_url('user/language/' . $field) . '"><i class="fa fa-circle-o"></i> ' . ucwords($field) . '</a></div>';
										}
									}
								?>
								
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer text-center">
						<?php echo phrase('choose_available_translation_above_to_set_your_language'); ?>
					</div>
				</div>
			</div>
		</div>
				
		<div id="search" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table">
				<div class="modal-content modal_cell">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-search"></i> &nbsp; <?php echo phrase('search_article_or_hashtag'); ?></h3>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<form class="form-horizontal submitForm" action="<?php echo base_url('search'); ?>" method="post" data-save="<?php echo phrase('search'); ?>" data-saving="<?php echo phrase('searching'); ?>" data-alert="<?php echo phrase('unable_to_submit_inquiry'); ?>">
									<div class="input-group">
										<input type="text" class="form-control input-lg" name="query" placeholder="<?php echo phrase('type_keywords'); ?>" />
										<span class="input-group-btn">
											<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
											<button type="submit" class="btn btn-lg btn-success nomargin submitBtn"><i class="fa fa-search"></i> <?php echo phrase('search'); ?></button>
										</span>
									</div>
									<div class="form-group">
										<div class="col-sm-12 statusHolder">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="modal-footer text-center">
						<b><?php echo phrase('latest_quiries'); ?>:</b>
						<br />
						<?php echo widget_hashTags(false, 20); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="modal_confirm" class="modal fade">
			<div class="modal-dialog modal_table" style="max-width:400px">
				<div class="modal-content modal_cell">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-question-circle"></i> <?php echo phrase('leave_page_confirm');?></h3>
					</div>
					<div class="modal-body">
						<?php echo phrase('you_have_not_submitted_your_changes'); ?>. <?php echo phrase('do_you_want_to_leave_page_without_changes'); ?>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-xs-6 nomargin text-left">
								<a href="javascript:void(0)" class="btn btn-default" id="confirm_link"><i class="fa fa-check-circle"></i> <?php echo phrase('leave_page');?></a>
							</div>
							<div class="col-xs-6 nomargin">
								<button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo phrase('stay_here');?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="fb-root"></div>
	
		<?php if($this->session->flashdata('success')) { ?>
		<div id="successAlert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal_table" style="max-width:500px">
				<div class="modal-content modal_cell">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h3 id="myModalLabel"><i class="fa fa-check-circle"></i> <?php echo phrase('success');?></h3>
					</div>
					<div class="modal-body text-center">
						<h4 class="text-success">
							<?php echo $this->session->flashdata('success'); ?></b>
						</h4>
					</div>
					<div class="modal-footer text-center-xs">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo phrase('close');?></button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(window).load(function(){
				$('#successAlert').modal('show');
			});
		</script>
		<?php } ?>
		
		<script type="text/javascript">
			var base_url	= '<?php echo base_url(); ?>',
				theme_url	= '<?php echo base_url('themes/default/'); ?>',
				siteName	= '<?php echo $this->settings['siteTitle']; ?>',
				loggedIn	= <?php echo ($this->session->userdata('loggedIn') ? 'true' : 'false'); ?>,
				fail_alert	= '<?php echo phrase('unable_to_proccess_your_request'); ?>',
				dc_alert	= '<?php echo phrase('your_internet_was_disconnected'); ?>',
				empty_alert	= '<?php echo phrase('please_enter_some_text_to_submit'); ?>'
		</script>
		
		<script src="<?php echo base_url('themes/default/js/scrollbar.js'); ?>"></script>
		<script src="<?php echo base_url('themes/default/js/masonry.js'); ?>"></script>
		<script src="<?php echo base_url('themes/default/js/sticky.js'); ?>"></script>
		<script src="<?php echo base_url('themes/default/js/easing.js'); ?>"></script>
		<script src="<?php echo base_url('themes/default/js/global.js'); ?>"></script>
		<script type="text/javascript" src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script type="text/javascript">
			$(window).load(function()
			{
				if ($(window).width() > 720)
				{
					if($('.grid').length )
					{
						$('.grid').masonry({
							itemSelector: '.grid-item'
						});
					}
				}
				<?php /*
				$('.adsHolder').each(function(index)
				{
					var width = $(this).width();
					if (width > 160 && width < 200)
					{
						$(this).html('<ins class="adsbygoogle" style="display:inline-block;width:250px;height:250px" data-ad-client="ca-pub-9491964298052525" data-ad-slot="3863008691"></ins>');
					}
					if (width > 259 && width < 309)
					{
						$(this).html('<ins class="adsbygoogle" style="display:inline-block;width:250px;height:250px" data-ad-client="ca-pub-9491964298052525" data-ad-slot="4876703899"></ins>');
					}
					if (width > 309 && width < 464)
					{
						$(this).html('<ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-9491964298052525" data-ad-slot="7132166290"></ins>');
					}
					if (width > 464 && width < 720)
					{
						$(this).html('<ins class="adsbygoogle" style="display:inline-block;width:468px;height:60px" data-ad-client="ca-pub-9491964298052525" data-ad-slot="2562365896"></ins>');
					}
					(adsbygoogle = window.adsbygoogle || []).push({});
				});
				*/ ?>
			});
			$(document).ready(function()
			{
				$('.menu-left').on('shown.bs.offcanvas', function (){
					$(this).css('display','block');
					$('.menu-right').css('display', 'none');
				});
				$('.menu-right').on('shown.bs.offcanvas', function (){
					$(this).css('display','block');
					$('.menu-left').css('display', 'none');
				});
			});
			(function(i,s,o,g,r,a,m)
			{
				i['GoogleAnalyticsObject']=r;i[r]=i[r]||function()
				{
					(i[r].q=i[r].q||[]).push(arguments)
				},
				i[r].l=1*new Date();
				a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];
				a.async=1;
				a.src=g;
				m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-72523525-1', 'auto');
			ga('send', 'pageview');
			(function(d, s, id)
			{
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=423905061028802";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
	</body>
</html>