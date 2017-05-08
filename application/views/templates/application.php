<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<meta name="format-detection" content="telephone=no" />
		<meta name="SKYPE_TOOLBAR" content ="SKYPE_TOOLBAR_PARSER_COMPATIBLE"/>
		<link rel="icon" type="image/png" href="<?= base_url() ?>assets/images/favicon.png" />
		<title>Home | Zinbids</title>
		<link href="<?= base_url() ?>assets/stylesheet/style.css" rel="stylesheet" type="text/css" />
		<?= $style ?>
		<script type="text/javascript" src="<?= base_url() ?>assets/js/html5.js"></script>
		<script type="text/javascript">
			var base_url = '<?= base_url() ?>';
		</script>
	</head>

	<body>
		<?php
			echo $body;
		?>
		<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?= base_url() ?>assets/js/slick.min.js"></script>
		<script type="text/javascript" src="<?= base_url() ?>assets/js/custom.js"></script>
		<?php
			echo $script;
		?>
	</body>
</html>