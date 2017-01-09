<div class="container">
	<div class="row">
		<div class="login-form col-md-6 col-md-offset-3">
			<?php
				if (isset($message)) {
					?>
					<div class="alert alert-danger alert-dismissible">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<?= $message ?>
					</div>
					<?php
				}
			?>
			<div class="form-login">
				<?php echo form_open("user/register");?>
					<h4>Please Sign Up</h4>
					<div class="input-group" style="margin-bottom: 25px;">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<?php echo form_input($first_name);?>
					</div>
					<div class="input-group" style="margin-bottom: 25px;">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<?php echo form_input($last_name);?>
					</div>
					<div class="input-group" style="margin-bottom: 25px;">
						<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
						<?php echo form_input($email);?>
					</div>
					<div class="input-group" style="margin-bottom: 25px;">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<?php echo form_input($password);?>
					</div>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<?php echo form_input($confirm_password);?>
					</div>
					<br>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="agree"> Yes, I understand and agree to the 
						</label>
						<a href="<?= base_url() ?>home/terms">Terms of Service </a>, including the <a href="<?= base_url() ?>home/agreement">User Agreement </a>and<a href="<?= base_url() ?>home/policy"> Privacy Policy.</a> for Videre
					</div>
					</br>
					</br>
					<div class="wrapper">
						<input type="submit" class="btn btn-default btn-md" value="Register">
						<br>
					</div>
					<br>
					<div class="bottom">
						<a href="<?= base_url()?>user/login">Already have a account? Signin here</a>
					</div>
					
					<br>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>