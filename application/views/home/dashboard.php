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
    ?>

    <div class="row">
        <div class="pull-right">
            <a href="<?= base_url() ?>user/setting">
                <img class="account_avatar" src="<?= $user['photo'] ? $user['photo'] : base_url().'assets/img/user.png'?>" style="width: 50px; height: 50px;">
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <table id="videos_table" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Modified</th>
                    <th>Shared</th>
                </tr>
            </thead>
            <?php foreach ($videos as $video) : ?>
                <tr>
                    <td>
                        <a href="<?= base_url() ?>video_detail/<?= $video['timestamp'] ?>">
                            <?= json_decode($video['info'])->file_name ?>
                        </a>
                    </td>
                    <td>
                        <?php
                        $datetime = new DateTime();
                        $datetime->setTimestamp(round($video['timestamp']/1000));
                        $format = $datetime->format('m/d/y H:i:s');
                        echo $format;
                        ?>
                    </td>
                    <td>
                        
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="row video-upload-form">
        <?php echo form_open_multipart('home/do_upload');?>
            <input type="file" id="video_file_upload" name="video" accept="video/*" class="hide" />
        </form>
        <br>
        <button class="add-new-video btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Upload New Video</button>
    </div>


    <div id="preview-video-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Are you sure to upload this video?</h4>
                </div>
                <div class="modal-body">
                    <video id="preview-video" preload controls loop autoplay></video>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary upload-this-file">Upload this file</a>
                    <a href="#" class="btn btn-default cancel-upload">Cancel upload</a>
                </div>
            </div>
        </div>
    </div>



</div>