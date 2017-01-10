<br>
<div class="container">
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
	<ul class="nav nav-tabs clearfix">
		<li class="<?= $active_tab == 1 ? 'active' : '' ?>" >
			<a href="<?= base_url()?>user/setting">Profile</a>
		</li>
		<li class="<?= $active_tab == 2 ? 'active' : '' ?>" >
			<a href="<?= base_url()?>user/account">Account</a>
		</li>
		<li class="<?= $active_tab == 3 ? 'active' : '' ?>" >
			<a href="<?= base_url()?>user/security">Security</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane <?= $active_tab == 1 ? 'active' : '' ?> in" id="first" style="padding-top: 40px; padding-left: 20px;">
			<div class="row">
				<div class="col-md-5 col-xs-8 user-name">
					<div class="pull-left">
						<span>Username</span><br><br>
						<div style="display: flex">

							<input type="text" id="input-user-first-name" class="form-control user-name-field" value="<?= $user['first_name']?>" disabled placeholder="First Name" >&nbsp;
							<input type="text" id="input-user-last-name" class="form-control user-name-field" value="<?= $user['last_name']?>" disabled placeholder="Last Name">&nbsp;
							<button id="save_user_name" class="btn btn-primary" style="display: none">Ok</button>
						</div>
					</div>
					<div class="pull-right">
						<a id="change_user_name" href="#">Change</a>
					</div>
				</div>
				
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-5 col-xs-8 account-title">
					Account Photo
					<br><br>
					<div class="pull-left">
						<img class="account_avatar" src="<?= $user['photo'] ? $user['photo'] : base_url().'assets/img/user.png'?>">
						<?php echo form_open_multipart('user/photo_upload', 'id="photo-upload-form"');?>
							<input type="file" id="photo_file_upload" name="photo" accept="image/*" class="hide" />
						</form>
					</div>
					<div class="pull-right">
						<a id="delete_photo" href="<?= base_url()?>user/delete_photo">Delete Photo</a><br>
						<a id="change_photo" href="#">Change Photo</a>
					</div>
				</div>
				<div class="col-md-5 col-xs-4 account-title">
					Personal Email
					<br><br>
					<div style="display: flex">
						<input type="text" id="input-new-email" class="form-control" value="<?= $user['email']?>" disabled>
						<button id="change_new_email" class="btn btn-primary" style="display: none">Ok</button>
					</div>
					<br>
					<a id="change_email" href="#">Change Email</a>
				</div>
			</div>
			<hr>
			<div class="row">
				<?php
					if (!$user['customer_id']) {
						?>
						<div class="alert alert-danger alert-dismissible">
							You are using free trial subscription. Free subscription will be expired in 
							<?php
								$six_weeks = 6 * 7 * 24 * 3600;
								$passed = strtotime('now') - strtotime($user['register']);
								$remain_days = (int)(($six_weeks - $passed) / 3600 / 24);
								echo $remain_days.'days';
							?>
						</div>
						<?php
					}
				?>
				<div class="col-md-12">
					Subscription
					<?php
						$monthly = strpos($user_type, 'monthly');
						$is_monthly = false;

						if ($monthly !== false) {
							$is_monthly = true;
						} else {
							$is_monthly = false;
						}

						$is_basic = strpos($user['plan_id'], 'basic');
						$is_medium = strpos($user['plan_id'], 'medium');
						$is_super = strpos($user['plan_id'], 'super');

						$yearly_pay_amount = 0;
						$monthly_pay_amount = 0;

						if ($is_basic !== false) {
							$yearly_pay_amount = 49.99;
							$monthly_pay_amount = 4.95;
						} else if ($is_medium !== false) {
							$yearly_pay_amount = 99.99;
							$monthly_pay_amount = 9.95;
						} else if ($is_super !== false) {
							$yearly_pay_amount = 199.99;
							$monthly_pay_amount = 19.95;
						} else {
							$yearly_pay_amount = "Free";
							$monthly_pay_amount = "Free";
						}
					?>
					<table class="subscription-table" border="1">
						<tbody>
							<tr>
								<td rowspan="2"><?= $size ?>GB</td>

								<?php
									if ($yearly_pay_amount == "Free") {
										?>
										<td>
											<label>
												<input type="radio" name="change_member_ship" disabled="">
												&nbsp;&nbsp;Free trial
											</label>
										</td>
										<?php
									} else {
										?>
										<td>
											<label>
												<input type="radio" name="change_member_ship" <?= $monthly ? 'checked' : '' ?> disabled="">
												&nbsp;&nbsp;$<?= $monthly_pay_amount ?>/ monthly
											</label>
										</td>
										<?php
									}
								?>

							</tr>
							<tr>
								<?php
									if ($yearly_pay_amount == "Free") {
										?>
										<td>
											<label>
												<input type="radio" name="change_member_ship" disabled="">
												&nbsp;&nbsp;Free trial
											</label>
										</td>
										<?php
									} else {
										?>
										<td>
											<label>
												<input type="radio" name="change_member_ship" <?= $monthly ? '' : 'checked' ?> disabled="">
												&nbsp;&nbsp;$<?= $yearly_pay_amount ?>/ yearly
											</label>
										</td>
										<?php
									}
								?>
							</tr>
						</tbody>
					</table>
					<div class="pull-right">
						<a href="<?= base_url()?>user/upgrade_membership">Change membership</a>
					</div>
				</div>
				
			</div>
		</div>
		<div class="tab-pane <?= $active_tab == 2 ? 'active' : '' ?>" id="second">
			<br><br>
			<div class="col-md-12">
				Space (Videre Pro)
			</div>
			<br><br>
			<div class="col-md-12">
				<div class="progress">
					<?php
						$used_percent = round($all_size / ($size * 1000) * 100, 2);
					?>
					<div class="progress-bar" role="progressbar" aria-valuenow="<?= $used_percent ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $used_percent ?>%">
						<?= $used_percent ?>%
					</div>
				</div>
			</div>
			<br><br>
			<div class="col-md-12">
				<?php
					if (($all_size / 1000) < 1) {
						echo round($all_size, 2) . 'MB';
					} else {
						echo round($all_size / 1000, 2) . 'GB';
					}
				?> out of <?= $size ?>GB(<?= $used_percent ?>%) used
			</div>
			<hr>
			<ul>
				<li><a href="#">View billing history</a></li>
				<li><a id="delete_account" href="<?= base_url()?>user/delete_account">Delete my Videre account</a></li>
			</ul>
		</div>
		<div class="tab-pane <?= $active_tab == 3 ? 'active' : '' ?>" id="third">
			<div class="col-md-12">
				<br>
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									Change Password
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
								<div class="col-md-6 col-xs-12 col-md-offset-3">
									<form action="<?= base_url()?>user/change_pass" method="post">
										<input type="password" name="password" class="form-control" placeholder="Your current password" autocomplete="off">
										<br>
										<input type="password" name="_password" class="form-control" placeholder="New Password" autocomplete="off">
										<br>
										<input type="password" name="_confirm" class="form-control" placeholder="Confirm New Password" autocomplete="off">
										<br>
										<input type="submit" name="submit" value="Change Password" class="btn btn-primary">
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="<?= base_url()?>user/forget_password" aria-expanded="false" aria-controls="collapseTwo">
									Forget Password
								</a>
							</h4>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div id="preview-photo-modal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4>Do you want to use this photo as your avatar?</h4>
				</div>
				<div class="modal-body">
					<img id="new-photo-img" style="width: 100px; height: 100px;">
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-primary upload-this-file">Yes, I will use.</a>
					<a href="#" class="btn btn-default cancel-upload">No, I will try with other one.</a>
				</div>
			</div>
		</div>
	</div>

</div>