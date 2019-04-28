<html>

    <?php
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $greenCheck = $actual_link .'/'. 'Images/green-check.svg';
        $redCheck = $actual_link .'/'. 'Images/red-check.svg';
    ?>

    <body>

        <div class="container requirement" id="requirement">
            <div class="initial-display">
                <p>Requirement</p>

                <div class="content">
                    <div class="title" style="text-align: center; margin-top: 10px">
                        Please wait while we are checking the requirements
                    </div>

                    <div class="check" style="margin-left: 25%">
                        <?php if($phpVersion['supported'] ? $src = $greenCheck : $src = $redCheck): ?>
                            <img src="<?php echo $src ?>">
                        <?php endif; ?>
                        <span style="margin-left: 10px"><b>PHP</b></span>
                        <span>(<?php echo $phpVersion['minimum'] ?> or Higher)</span>
                    </div>

                    <?php foreach($requirements['requirements'] as $type => $require): ?>

                        <?php foreach($requirements['requirements'][$type] as $extention => $enabled) : ?>
                            <div class="check" style="margin-left: 25%">
                                <?php if($enabled ? $src = $greenCheck : $src = $redCheck ): ?>
                                    <img src="<?php echo $src ?>">
                                <?php endif; ?>
                                <span style="margin-left: 10px"><b><?php echo $extention ?></b></span>
                                <span>(<?php echo $extention ?> Required)</span>
                            </div>
                        <?php endforeach; ?>

                    <?php endforeach; ?>

                    <php class="check" style="margin-left: 25%">
                        <?php if(($composerInstall['composer_install'] == 0) ? $src = $greenCheck : $src = $redCheck ): ?>
                            <img src="<?php echo $src ?>">
                            <span style="margin-left: 10px"><b>Composer</b></span>
                        <?php endif; ?>
                    </php>

                    <div style="margin-left: 30%;">
                        <?php if(!($composerInstall['composer_install'] == 0)): ?>
                            <span style="margin-left: 10px; color: red;"><?php echo $composerInstall['composer'] ?></span>
                        <?php endif; ?>
                    </div>

                </div>

                <?php if(!isset($requirements['errors']) && ($phpVersion['supported'] && $composerInstall['composer_install'] == 0)): ?>
                    <div>
                        <button type="button" class="prepare-btn" id="requirement-check">Continue</button>
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </body>

</html>
