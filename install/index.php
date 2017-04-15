<?php
	session_start();
	$db_config_path		 = '../codeigniter/application/config/database.php';
	$base_url			 = (empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) === 'off') ? 'http' : 'https';
	$base_url			.= '://'. $_SERVER['HTTP_HOST'];
	$base_url			.= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

	if(isset($_POST['hash']))
	{
		require_once('includes/Core_class.php');
		require_once('includes/Database_class.php');
		$core			= new Core();
		$database		= new Database();
		$error			= false;
		if($core->validate_post($_POST) == true)
		{
			if($database->create_database($_POST) == false)
			{
				$error	= true;
				echo json_encode(array('status' => 204, 'messages' => array('Tidak dapat membuat database. Pastikan nama pengguna dan kata sandi yang Anda masukkan benar.')));
			}
			else if ($database->create_tables($_POST) == false)
			{
				$error	= true;
				echo json_encode(array('status' => 204, 'messages' => array('Tabel database tidak dapat dibuat. Silakan periksa konfigurasi yang Anda masukkan.')));
			}
			else if ($core->write_config($_POST) == false)
			{
				$error	= true;
				echo json_encode(array('status' => 204, 'messages' => array('Konfigurasi file database tidak dapat dibuat, silakan ubah chmod application/config/database.php file to 777')));
			}
			
			if(!$error)
			{
				$_SESSION['installSuccess']	= true;
				echo json_encode(array('status' => 200, 'redirect' => $base_url));
			}

		}
		else
		{
			$error		= true;
			echo json_encode(array('status' => 204, 'messages' => array('Ada beberapa kolom yang belum diisi dengan benar. Kolom host, username, password, dan database wajib diisi.')));
		}
	}
	else
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../themes/default/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="../themes/default/css/styles.css" />
		<link rel="stylesheet" type="text/css" href="../themes/default/css/font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="../themes/default/fonts/raleway/Raleway.css" />
		<script type="text/javascript" src="../themes/default/js/jquery.js"></script>
		<script type="text/javascript" src="../themes/default/js/bootstrap.min.js"></script>
		<link rel="shortcut icon" type="image/x-icon" href="../themes/default/images/favicon.ico" />
		<title>DWITRI Installer</title>
	</head>
	<body class="fixed">
		<div class="navbar" style="display:none"></div>
		<div class="jumbotron bg-primary">
			<div class="container first-child">
				<div class="col-sm-6 col-sm-offset-3 text-center">
					<h2>Selamat datang di halaman instalasi script DWITRI!</h2>
					<p>
						Pada halaman ini Anda dapat mengetahui fitur-fitur dasar dan fungsi modul serta helper di dalamnya. Juga mengisi konfigurasi untuk memulai instalasi script.
					</p>
				</div>
			</div>
		</div>
		<br />
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<ul class="nav nav-tabs nav-justified" role="tablist">
						<li role="presentation"<?php echo (!isset($_SESSION['installSuccess']) ? ' class="active"' : ''); ?>><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><h3>SAMBUTAN</h3></a></li>
						<li role="presentation"><a href="#modules" aria-controls="modules" role="tab" data-toggle="tab"><h3>MODUL</h3></a></li>
						<li role="presentation"><a href="#helper" aria-controls="helper" role="tab" data-toggle="tab"><h3>HELPER</h3></a></li>
						<li role="presentation"<?php echo (isset($_SESSION['installSuccess']) ? ' class="active"' : ''); ?>><a href="#install" aria-controls="install" role="tab" data-toggle="tab"><h3>INSTAL</h3></a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade<?php echo (!isset($_SESSION['installSuccess']) ? ' in active' : ''); ?>" id="home" style="background:#fff">
							<div class="col-sm-12">
								<center>
									<h2>Halo...</h2>
									<p>
										Perkenalkan, saya <b>Aby Dahana</b>. Pada halaman ini saya akan memperkenalkan fitur-fitur, modul dan helper yang ada pada script ini serta petunjuk untuk memulai instalasi. Silakan luangkan waktu sejenak untuk mendapatkan pemahaman tentang <i class="text-primary">System Requirements</i> untuk mencegah terjadinya kesalahan yang menyebabkan website tidak bekerja dengan baik.
									</p>
								</center>
								<br /><br />
								<?php
									$extensions	= get_loaded_extensions();
									$modules	= apache_get_modules();
								?>
								<div class="row">
									<div class="col-sm-10 col-sm-offset-1">
										<table class="table table-striped">
											<thead>
												<tr>
													<th>
														System Modules
													</th>
													<th>
														Status / Version
													</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														PHP Version
													</td>
													<td>
														<?php echo (version_compare(phpversion(), '5.4.0', '>') ? '<b class="text-success"><i class="fa fa-check"></i> ' . phpversion() . '</b>' : '<b class="text-danger"><i class="fa fa-times"></i> ' . phpversion() . '</b>. &nbsp; &nbsp; Versi minimal yang diminta adalah 5.4.0'); ?>
													</td>
												</tr>
												<tr>
													<td>
														MySQLi Driver
													</td>
													<td>
														<?php echo (in_array('mysqli', $extensions) ? '<b class="text-success"><i class="fa fa-check"></i> Loaded</b>' : '<b class="text-danger"><i class="fa fa-times"></i> Not Loaded</b>'); ?>
													</td>
												</tr>
												<tr>
													<td>
														GD Library
													</td>
													<td>
														<?php echo (in_array('gd', $extensions) ? '<b class="text-success"><i class="fa fa-check"></i> Loaded</b>' : '<b class="text-danger"><i class="fa fa-times"></i> Not Loaded</b>'); ?>
													</td>
												</tr>
												<tr>
													<td>
														JSON Extension
													</td>
													<td>
														<?php echo (in_array('json', $extensions) ? '<b class="text-success"><i class="fa fa-check"></i> Loaded</b>' : '<b class="text-danger"><i class="fa fa-times"></i> Not Loaded</b>'); ?>
													</td>
												</tr>
												<tr>
													<td>
														Rewrite Module
													</td>
													<td>
														<?php echo (in_array('mod_rewrite', $modules) ? '<b class="text-success"><i class="fa fa-check"></i> Enabled</b>' : '<b class="text-danger"><i class="fa fa-times"></i> Disabled</b>'); ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="modules" style="background:#fff">
							<div class="col-sm-12">
								<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="yyy">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
													<i class="fa fa-newspaper-o"></i> &nbsp; POSTS MODULE
												</a>
											</h4>
										</div>
										<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="yyy">
											<div class="panel-body">
												Modul ini memungkinkan pengguna untuk menulis dan membagikan repostase atau artikel yang ditulisnya kepada seluruh pengguna website. Pada modul ini telah diberikan beberapa sample data agar dapat dipelajari alur kerjanya, seperti pos yang menjadi headline, kategori, penyuntingan dan sebagainya.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menulis Artikel</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyunting Artikel</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menghapus Artikel</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengomentari Artikel</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyukai Artikel</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengirim Ulang Artikel</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Membuat Kategori</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyunting Kategori</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menghapus Kategori</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Thumbnail Otomatis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Slug Otomatis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Pencatatan Pembaca</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Validasi Form</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Post Excerpt</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Meta Dinamis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Pagination</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="jjj">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
													<i class="fa fa-image"></i> &nbsp; SNAPSHOTS MODULE
												</a>
											</h4>
										</div>
										<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="jjj">
											<div class="panel-body">
												Seperti pada website viral images, pada modul ini memungkinkan pengguna untuk mengunggah foto dan membagikannya ke seluruh pengguna.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengunggah Foto</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyunting Snapshot</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menghapus Foto</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengomentari Foto</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyukai Foto</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengirim Ulang Foto</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Thumbnail Otomatis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Slug Otomatis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Pencatatan Pembaca</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Validasi Form</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Extended Modal Preview</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Meta Dinamis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Pagination</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="mmm">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
													<i class="fa fa-paperclip"></i> &nbsp; OPEN LETTER MODULE
												</a>
											</h4>
										</div>
										<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="mmm">
											<div class="panel-body">
												Dengan modul ini pengguna dapat menulis surat terbuka yang dapat ditujukan kepada siapa saja dengan tujuan untuk memberikan kritik atau saran pada penerima surat terbuka tersebut.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menulis Surat</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyunting Surat</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menghapus Surat</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengomentari Surat</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyukai Surat</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengirim Ulang Surat</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Slug Otomatis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Pencatatan Pembaca</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Validasi Form</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Post Excerpt</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Meta Dinamis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Pagination</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="nnn">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
													<i class="fa fa-desktop"></i> &nbsp; TV MODULE
												</a>
											</h4>
										</div>
										<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="nnn">
											<div class="panel-body">
												Pengguna dapat mempublikasikan saluran televisi favorit atau saluran televisi yang telah dibuatnya. Pengguna kemudian dapat streaming untuk memutar saluran yang dibagikan tersebut.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menambah Saluran</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyunting Saluran</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menghapus Saluran</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengomentari Saluran</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyukai Saluran</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengirim Ulang Saluran</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Thumbnail Otomatis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Slug Otomatis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Pencatatan Pembaca</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Extended Modal Preview</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Validasi Form</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Meta Dinamis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Pagination</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="bbb">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
													<i class="fa fa-calendar"></i> &nbsp; TIMELINE MODULE
												</a>
											</h4>
										</div>
										<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="bbb">
											<div class="panel-body">
												Ini adalah modul yang terhubung dengan profil pengguna. Akan ditampilkan sebagai wall atau dinding dan juga lini masa yang menampilkan seluruh kegiatan pemilik profil.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Artikel</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Snapshot</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Surat Terbuka</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Saluran</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Yang Disukai Pengguna</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Yang Dikomentari Pengguna</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Yang Dikirim Ulang Pengguna</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Lini Masa Berdasarkan Bulan</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Form Pembaruan Status</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Auto Parsing Post</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Auto Parsing Snapshot</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Auto Parsing Openletter</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Auto Parsing TV Channel</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Paging</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="vvv">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
													<i class="fa fa-users"></i> &nbsp; USERS MODULE
												</a>
											</h4>
										</div>
										<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="vvv">
											<div class="panel-body">
												Modul untuk mengontrol pengguna website.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyunting Profil Pengguna</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menghapus Pengguna</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Form Validation</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Extended Modal Edit</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Paging</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="ccc">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
													<i class="fa fa-bell"></i> &nbsp; REALTIME NOTIFICATION MODULE
												</a>
											</h4>
										</div>
										<div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="ccc">
											<div class="panel-body">
												Modul yang menjalankan fungsi notifikasi secara real time.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Notifikasi Komentar</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Notifikasi Kesukaan</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Notifikasi Repost</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Notifikasi Pertemanan</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menampilkan Notifikasi Followers</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Real Time Push Notification</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Memodifikasi Document Title</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Memodifikasi Tampilan Counter</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="xxx">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
													<i class="fa fa-comments"></i> &nbsp; COMMENTS MODULE
												</a>
											</h4>
										</div>
										<div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="xxx">
											<div class="panel-body">
												Modul ini memungkinkan komentar pada kiriman antar pengguna.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Mengirimkan Komentar</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Membalas Komentar</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Implementasi AJAX</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Form Validation</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="zzz">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
													<i class="fa fa-files-o"></i> &nbsp; PAGES MODULE
												</a>
											</h4>
										</div>
										<div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="zzz">
											<div class="panel-body">
												Memungkinkan untuk membuat halaman baru mengenai website Anda.
												<br /><br />
												<div class="row">
													<div class="col-sm-6">
														<h2>FITUR MODUL</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menambah Halaman</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menyunting Halaman</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Menghapus Halaman</b>
															</li>
														</ul>
													</div>
													<div class="col-sm-6">
														<h2>FITUR SISTEM</h2>
														<ul class="list-group">
															<li class="list-group-item">
																<i class="fa fa-check"></i> Slug Otomatis</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Form Validation</b>
															</li>
															<li class="list-group-item">
																<i class="fa fa-check"></i> Paging</b>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="helper" style="background:#fff">
							<div class="col-sm-12">
								<div class="panel-group" id="accordions" role="tablist" aria-multiselectable="true">
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="sss">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordions" href="#acollapseOne" aria-expanded="true" aria-controls="acollapseOne">
													<i class="fa fa-newspaper-o"></i> &nbsp; CONTENT HELPER
												</a>
											</h4>
										</div>
										<div id="acollapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="sss">
											<div class="panel-body">
												Custom helper ini berisi callback function yang berisi informasi konten.
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="aaa">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordions" href="#acollapseTwo" aria-expanded="false" aria-controls="acollapseTwo">
													<i class="fa fa-language"></i> &nbsp; MULTI LANGUAGE HELPER
												</a>
											</h4>
										</div>
										<div id="acollapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="aaa">
											<div class="panel-body">
												Helper untuk memformat frasa menjadi dapat diterjemahkan. Disinilah frasa akan diproses oleh system. Function akan mengecek apakah frasa yang dimasukkan tersebut telah tersedia dalam baris database. Jika frasa sudah tersedia, maka akan diubah dengan frasa dari dalam database. Jika belum, maka system akan menambahkan frasa ini ke dalam baris baru pada database.
												<br />
												Berikut contoh untuk pemanggilannya:
												<br />
												<code>
													echo phrase('welcome');
												</code>
												<br />
												Frasa di atas akan menampilkan terjemahan dari kata "welcome".
												<br />
												<code>
													echo phrase('commented_on_user_update', array($row['userName'], $row['full_name']));
												</code>
												<br />
												<br />
												Pemanggilan frasa di atas adalah untuk mengirimkan beberapa variable ke dalam helper, yang mana variable tersebut mungkin berbeda posisi penerjemahannya. Contohnya pada bahasa Inggris dan Indonesia untuk kalimat ini:
												<br />
												<code>
													... mengomentari artikel Aby Dahana.
												</code>
												<br />
												<code>
													... commented on Aby Dahana article.
												</code>
												<br />
												<br />
												Pada perbandingan kata di atas jelas beda posisi variable untuk "user"nya. Jadi perlu mendefinisikan variable ke dalam pemanggilan function seperti yang dicontohkan di atas.
												<br />
												Dan untuk penerjemahannya, dapat menggunakan number variable untuk mengubah posisi variable yang dikirim, contoh:
												<br />
												<code>
													commented on &lt;a href=&quot;base_url/$1&quot; class=&quot;ajaxLoad&quot;&gt;$2&lt;/a&gt; article.
												</code>
												<br />
												<code>
													berkomentar dalam artikel &lt;a href=&quot;base_url/$1&quot; class=&quot;ajaxLoad&quot;&gt;$2&lt;/a&gt;.
												</code>
												<br />
												<br />
												Nah dari penerjemahan itu, variable nantinya akan diformat menjadi nama pengguna.
												<br />
												<code>$1</code> adalah hasil dari variable <code>$row['userName']</code>, dan <code>$2</code> adalah dari variable <code>$row['full_name']</code>.
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="666">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordions" href="#acollapseThree" aria-expanded="false" aria-controls="acollapseThree">
													<i class="fa fa-paperclip"></i> &nbsp; PARSING HELPER
												</a>
											</h4>
										</div>
										<div id="acollapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="666">
											<div class="panel-body">
												Helper ini berisi function untuk memformat text ke dalam smiley. Juga untuk memparsing kiriman berdasarkan ID yang diberikan pada saat memanggil function.
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="555">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordions" href="#acollapseFour" aria-expanded="false" aria-controls="acollapseFour">
													<i class="fa fa-desktop"></i> &nbsp; SPECIAL HELPER
												</a>
											</h4>
										</div>
										<div id="acollapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="555">
											<div class="panel-body">
												Berisi function-function yang dibuat khusus untuk memudahkan deployment website.
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="444">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordions" href="#acollapseFive" aria-expanded="false" aria-controls="acollapseFive">
													<i class="fa fa-calendar"></i> &nbsp; TIMELINE HELPER
												</a>
											</h4>
										</div>
										<div id="acollapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="444">
											<div class="panel-body">
												Untuk mengumpulkan data kiriman berdasarkan profil antar pengguna yang saling mengikuti atau saling berteman kemudian memparsingnya menjadi format berdasarkan jenis kiriman.
											</div>
										</div>
									</div>
									<div class="panel panel-info">
										<div class="panel-heading" role="tab" id="333">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordions" href="#acollapseSix" aria-expanded="false" aria-controls="acollapseSix">
													<i class="fa fa-users"></i> &nbsp; WIDGET HELPER
												</a>
											</h4>
										</div>
										<div id="acollapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="333">
											<div class="panel-body">
												Helper ini berisi kumpulan function yang dapat dipanggil untuk menampilkannya menjadi widget.
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div role="tabpanel" class="tab-pane fade<?php echo (isset($_SESSION['installSuccess']) ? ' in active' : ''); ?>" id="install" style="background:#fff">
							<div class="col-sm-12">
			
								<?php if(is_writable($db_config_path)){ ?>
								<?php if(!isset($_SESSION['installSuccess'])) { ?>
									<div class="row">
										<div class="col-sm-7 col-sm-offset-4">
											<blockquote>
												Silakan isi semua kolom di bawah ini sesuai dengan informasi login ke database.
											</blockquote>
										</div>
									</div>
									<form class="submitForm form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" data-save="Instal" data-saving="Mempersiapkan..." data-alert="Tidak dapat melakukan instalasi. Silakan coba lagi">
										<div class="form-group">
											<label class="control-label col-sm-4">Database Hostname</label>
											<div class="col-sm-7">
												<input class="form-control" name="hostname" type="text" value="localhost" placeholder="localhost" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Database Username</label>
											<div class="col-sm-7">
												<input class="form-control" name="username" type="text" placeholder="phpMyAdmin username" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Database Password</label>
											<div class="col-sm-7">
												<input class="form-control" name="password" type="text" placeholder="phpMyAdmin password" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Database Name</label>
											<div class="col-sm-7">
												<input class="form-control" name="database" type="text" placeholder="Database Name" />
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-7 col-sm-offset-4">
												<div class="statusHolder"></div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-4">
											</div>
											<div class="col-sm-7 text-right">
												<input type="hidden" name="hash" value="<?php echo sha1(time()); ?>" />
												<button type="submit" class="btn btn-lg btn-primary submitBtn"><i class="fa fa-check"></i> Instal</button>
											</div>
										</div>
									</form>
									
								<?php } else { ?>
									<div class="row">
										<div class="col-sm-10 col-sm-offset-1">
											<br />
											<div class="text-center">
												<h2 class="text-success">
													<i class="fa fa-check-circle"></i> &nbsp; INSTALASI BERHASIL!
												</h2>
											</div>
											<blockquote>
												Anda baru saja menyelesaikan proses instalasi. &nbsp; &nbsp; Website dengan beberapa sample data kini telah selesai dipersiapkan.
											</blockquote>
											<br />
											<p>
												Berikut ini beberapa informasi penting terkait informasi website Anda. Harap dicatat dan segera lakukan perubahan sesaat setelah Anda login.
											</p>
											<div class="row">
												<div class="col-sm-4">
													Alamat Website
												</div>
												<div class="col-sm-8">
													<?php echo str_replace('/install/', '', $base_url); ?>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4">
													Username Admin
												</div>
												<div class="col-sm-8">
													admin
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4">
													Sandi Admin
												</div>
												<div class="col-sm-8">
													admin
												</div>
											</div>
											<div class="row">
												<div class="col-sm-8 col-sm-offset-4">
													<div class="alert alert-warning">
														<strong>PENTING!</strong> Harap hapus folder instalasi (install) jika website sudah dapat diakses.
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-8 col-sm-offset-4">
													<a href="<?php echo str_replace('/install/', '', $base_url); ?>" class="btn btn-primary btn-lg"><i class="fa fa-arrow-right"></i> &nbsp; LIHAT WEBSITE</a>
												</div>
											</div>
											<br />
										</div>
									</div>
								<?php } ?>
								<?php } else { ?>
									<br />
									<p class="alert alert-danger error">
										Silakan mengubah permission pada file /codeigniter/application/config/database.php agar menjadi writable. 
										<br />
										<strong>Contoh</strong>:
										<br />
										<code>chmod 777 /codeigniter/application/config/database.php</code>
									</p>
								<?php } ?>
			
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<center>
			<br /><br />
			<a href="//www.dwitri.com"><img src="../themes/default/images/large_logo.png" width="100"/></a>
			<br /><br />
			&#169; <?php echo date('Y'); ?> - <a href="//www.facebook.com/abyprogrammer">Aby Dahana</a>
			<br />
			Hak Cipta Dilindungi!
		</center>
		<script type="text/javascript">
			var base_url	= '',
				theme_url	= '',
				siteName	= '',
				loggedIn	= false,
				fail_alert	= 'Tidak dapat memproses permintaan Anda',
				dc_alert	= 'Sepertinya koneksi internet Anda telah terputus',
				empty_alert	= 'Silakan masukkan beberapa kalimat untuk melanjutkan'
		</script>
		<script type="text/javascript" src="../themes/default/js/global.js"></script>
	</body>
</html>
	<?php } ?>