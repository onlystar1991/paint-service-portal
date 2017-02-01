<?php
	$arr = preg_split("/[\s,',{,}]+/", $default_categories);
	$arr = array_slice($arr, 1, count($arr) - 2);
?>

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
	?>
	
	<div class="video_panel row">
		<div class="col-md-3 col-xs-4 no-padding">
			<div class="well no-padding">
        		<div>
        			<button id="play_all_button" class="btn btn-default" style="width: 100%; font-size: 20px; font-weight: 500">Play All</button>
        			<ul class="nav nav-list nav-menu-list-style category-clips">
						<?php foreach ($arr as $default_category) : ?>
							<li class="default_category">
								<label data-category-name="<?= $default_category ?>" class="tree-toggle nav-header glyphicon-icon-rpad">
									<?= $default_category ?>
									<span class="caret"></span>
								</label>
								<ul class="nav nav-list tree bullets">
									<?php foreach ($clips as $clip) : ?>
										<?php
											if ($clip['category'] == $default_category) {
												$clip_info = json_decode($clip['info']);
												?>
												<li>
													<a href="#" class="clip-data" data-start="<?= $clip_info->start ?>" data-end="<?= $clip_info->end ?>">Clip (<?php echo $clip_info->start . ' - ' . $clip_info->end ?>)</a>
												</li>
												<?php
											}
										?>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php endforeach; ?>

						<?php foreach ($custom_categories as $custom_category) : ?>
							<li class="custom_category">
								<label data-category-name="<?= $custom_category['name']?>" data-category-timestamp="<?= $custom_category['timestamp']?>" class="tree-toggle nav-header glyphicon-icon-rpad"><?= $custom_category['name']?></label>
							</li>
							<ul class="sidebar-nav">
								<span class="category_title"></span>
								<?php foreach ($clips as $clip) : ?>
									<?php
										if ($clip['category'] == $custom_category['timestamp']) {
											$clip_info = json_decode($clip['info']);
											?>
											<li>
												<a href="#" class="clip-data" data-start="<?= $clip_info->start ?>" data-end="<?= $clip_info->end ?>">Clip (<?php echo $clip_info->start . ' - ' . $clip_info->end ?>)</a>
											</li>
											<?php
										}
									?>
								<?php endforeach; ?>
							</ul>
						<?php endforeach; ?>
					</ul>
        		</div>
    		</div>
		</div>
		<div class="col-md-9 col-xs-8" style="border-left: 1px solid #e0e0e0; ">
			<div class="row video-title">
				<span><?= json_decode($video['info'])->file_name ?></span>
				<button id="show-share-video-modal" class="pull-right btn btn-primary">Share this video</button>
			</div>
			<div class="col-md-6 col-md-offset-3" style="padding-top: 10px;">
				<video id="play_video" style="width: 100%;" src='<?= json_decode($video['info'])->url ?>' controls preload></video>
			</div>
		</div>	
	</div>

	<div id="share-with-other-modal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4>Share with others.</h4>
				</div>
				<div class="modal-body">
					Link sharing...<br>
					<div class="panel panel-default">
						<div class="panel-heading">
							Anyone with the link <strong>can view</strong>
						</div>
						<div class="panel-body">
							<div class="input-group" style="margin-bottom: 25px;">
								<input id="copy-input" type="text" class="form-control" value="<?= json_decode($video['info'])->url ?>" readonly>
								<span class="input-group-btn">
									<button class="btn copybtn" data-clipboard-target="#copy-input">Copy</button>
								</span>
							</div>
						</div>
					</div>
					<hr>
					People<br>
					<div class="panel panel-default">
						<div class="panel-body">
							<input id="share-users" type="text" class="form-control" value="">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button id="start-share" data-timestamp="<?= $video['timestamp']?>" class="btn btn-primary" style="border-radius: 1px;">Send Link</button>
				</div>
			</div>
		</div>
	</div>

</div>