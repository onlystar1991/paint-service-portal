<div class="container">
	<div class="row">
		<div class="login-form col-md-6 col-md-offset-3">
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
			?>
			<div class="form-login">
				<?php echo form_open("user/login");?>
					<h4>Please Login</h4>
					<div class="input-group" style="margin-bottom: 25px;">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<?php echo form_input($email);?>
					</div>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<?php echo form_input($password);?>
					</div>
					</br>
					</br>
					<div class="wrapper">
						<input type="submit" class="btn btn-default btn-md" value="Login">
						<br>
					</div>
					<br>
					<div class="bottom">
						<a href="#">Forget password?</a> | <a href="<?= base_url()?>user/register">Create New Account</a>
					</div>
					
					<br>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>