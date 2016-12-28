<div class="container" style="margin-top: 20px;">
	<?php
		if (isset($message)) {
			?>
			<div class="alert alert-success alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?= $message ?>
			</div>
			<?php
		}

		if (isset($error)) {
			?>
			<div class="alert alert-danger alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?= $error ?>
			</div>
			<?php
		}


		if ($user_type == 1) {
			$size = 40;
		} elseif ($user_type == 2) {
			$size = 100;
		} else {
			$size = 250;
		}
	?>

	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				Set Your New Password
			</h4>
		</div>
		<div id="collapseThree" class="panel-body">
			<div class="panel-body">
				<div class="col-md-6 col-xs-12 col-md-offset-3">
					<form action="<?= base_url()?>user/set_new_password" method="post">
						<input type="hidden" name="email" value="<?= $email ?>">
						<input type="hidden" name="token" value="<?= $token ?>">
						<input type="password" name="password" class="form-control">
						<br>
						<input type="password" name="confirm_password" class="form-control">
						<br>
						<button class="btn btn-primary">Reset Password</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>