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
		<link href="<?= base_url() ?>assets/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
		<link href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet">
		
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/jqueryui/dataTables.jqueryui.css">
		
		<link href="<?= base_url() ?>assets/css/token_field/tokenfield-typeahead.css" type="text/css" rel="stylesheet">
		<link href="<?= base_url() ?>assets/css/token_field/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">
		
		<link href="<?= base_url() ?>assets/docs-assets/css/pygments-manni.css" type="text/css" rel="stylesheet">
		<link href="<?= base_url() ?>assets/docs-assets/css/docs.css" type="text/css" rel="stylesheet">
		
		<?= $style ?>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<?php
			echo $body;
		?>
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

		<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src="<?= base_url() ?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="<?= base_url() ?>assets/js/bootstrap-tokenfield.js"></script>
		<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/jqueryui/dataTables.jqueryui.js">
		</script>

		<script type="text/javascript" src="<?= base_url() ?>assets/docs-assets/js/scrollspy.js" charset="UTF-8"></script>
		<script type="text/javascript" src="<?= base_url() ?>assets/docs-assets/js/affix.js" charset="UTF-8"></script>
		<script type="text/javascript" src="<?= base_url() ?>assets/docs-assets/js/typeahead.bundle.min.js" charset="UTF-8"></script>
		<script type="text/javascript" src="<?= base_url() ?>assets/docs-assets/js/docs.min.js" charset="UTF-8"></script>

		<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.16/clipboard.min.js"></script>
		<?= $script ?>
	</body>
</html>
