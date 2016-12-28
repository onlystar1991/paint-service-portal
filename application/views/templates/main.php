<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="/favicon.ico">

		<title>Videre</title>

		<!-- Bootstrap core CSS -->
		<link href="<?= base_url() ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="<?= base_url() ?>assets/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
		<?= $style ?>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<script>
			var users = [];
			var base_url = "<?= base_url() ?>";
			var email = "<?= $this->session->userdata('email') ?>";
			
			<?php
				if (isset($users)) {
					foreach ($users as $user) {
						?>
						users.push("<?= $user['email'] ?>");
						<?php
					}
				}
			?>
		</script>
	</head>
	<body>

		<?php
			echo $body;
		?>
		<script type="text/javascript" src="<?= base_url() ?>assets/jquery/dist/jquery.min.js"></script>
		<script src="<?= base_url() ?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
		<?= $script ?>
	</body>
</html>
