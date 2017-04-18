<?php
	session_start();
	require_once('includes/Core_class.php');
	require_once('includes/Database_class.php');
	$core					= new Core();
	$database				= new Database();
	$installed				= false;
	$extensions				= get_loaded_extensions();
	$mod_rewrite			= $core->is_mod_rewrite_enabled();
	$db_config				= '../config.php';
	$base_url				= (empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) === 'off') ? 'http' : 'https';
	$base_url				.= '://'. $_SERVER['HTTP_HOST'];
	$base_url				.= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	
	if(is_writable($db_config))
	{
		$installed			= true;
	}
	
	if(isset($_POST['hash']))
	{
		require_once('includes/Core_class.php');
		require_once('includes/Database_class.php');
		$core				= new Core();
		$database			= new Database();
		$error				= false;
		if($core->validate_post($_POST) == true)
		{
			if($database->create_database($_POST) == false)
			{
				$error		= true;
				echo json_encode(array('status' => 204, 'messages' => array('Tidak dapat membuat database. Pastikan nama pengguna dan kata sandi yang Anda masukkan benar.')));
			}
			else if ($database->create_tables($_POST) == false)
			{
				$error		= true;
				echo json_encode(array('status' => 204, 'messages' => array('Tabel database tidak dapat dibuat. Silakan periksa konfigurasi yang Anda masukkan.')));
			}
			else if ($core->write_config($_POST) == false)
			{
				$error		= true;
				echo json_encode(array('status' => 204, 'messages' => array('Konfigurasi file database tidak dapat dibuat. Hubungi abydahana@gmail.com untuk meminta bantuan.')));
			}
			
			if(!$error)
			{
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
						Halaman pertama untuk melakukan instalasi.
					</p>
					<p>
						<a href="index.php" class="btn btn-danger btn-sm">
							<i class="fa fa-language"></i>
							English Version
						</a>
					</p>
				</div>
			</div>
		</div>
		<br />
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<ul class="nav nav-tabs nav-justified" role="tablist">
						<li role="presentation"<?php echo (!$installed ? ' class="active"' : ''); ?>><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><h3>SAMBUTAN</h3></a></li>
						<li role="presentation"<?php echo ($installed ? ' class="active"' : ''); ?>><a href="#install" aria-controls="install" role="tab" data-toggle="tab"><h3>INSTAL</h3></a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade<?php echo (!$installed ? ' in active' : ''); ?>" id="home" style="background:#fff">
							<div class="col-sm-12">
								<center>
									<h2>Halo...</h2>
									<p>
										Perkenalkan, saya <b>Aby Dahana</b>. Selamat datang di halaman instalasi DWITRI. Silakan luangkan waktu sejenak untuk mendapatkan pemahaman tentang <i class="text-primary">System Requirements</i> untuk mencegah terjadinya kesalahan yang menyebabkan website tidak bekerja dengan baik.
									</p>
								</center>
								<br /><br />
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
														<?php echo ($mod_rewrite ? '<b class="text-success"><i class="fa fa-check"></i> Enabled</b>' : '<b class="text-danger"><i class="fa fa-times"></i> Disabled</b>'); ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div role="tabpanel" class="tab-pane fade<?php echo ($installed ? ' in active' : ''); ?>" id="install" style="background:#fff">
							<div class="col-sm-12">
								<?php if($installed) { ?>
									<div class="row">
										<div class="col-sm-10 col-sm-offset-1">
											<br />
											<div class="text-center">
												<h2 class="text-success">
													<i class="fa fa-check-circle"></i> &nbsp; INSTALASI BERHASIL!
												</h2>
											</div>
											<blockquote>
												Anda baru saja menyelesaikan proses instalasi. Website dengan beberapa sample data kini telah selesai dipersiapkan.
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
								<?php } else { ?>
									<div class="row">
										<div class="col-sm-7 col-sm-offset-4">
											<blockquote>
												Silakan isi semua kolom di bawah ini sesuai dengan informasi login ke database.
											</blockquote>
										</div>
									</div>
									<form class="submitForm form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" data-save="Instal" data-saving="Mempersiapkan..." data-alert="Tidak dapat melakukan instalasi. Silakan coba lagi" data-icon="check">
										<div class="form-group">
											<label class="control-label col-sm-4">Database Hostname</label>
											<div class="col-sm-7">
												<input class="form-control" name="hostname" type="text" value="localhost" placeholder="localhost" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Database Username</label>
											<div class="col-sm-7">
												<input class="form-control" name="username" type="text" placeholder="Database username" />
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4">Database Password</label>
											<div class="col-sm-7">
												<input class="form-control" name="password" type="text" placeholder="Database password" />
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
			<a href="//www.facebook.com/abyprogrammer"><img src="../themes/default/images/large_logo.png" width="100"/></a>
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