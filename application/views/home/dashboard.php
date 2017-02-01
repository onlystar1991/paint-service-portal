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

</div>