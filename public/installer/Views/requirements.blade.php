<html>

    <?php
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $greenCheck = $actual_link .'/'. 'Images/green-check.svg';
        $redCheck = $actual_link .'/'. 'Images/red-check.svg';
    ?>

    <body>

        <div class="container requirement" id="requirement">
            <div class="initial-display">
                <p>Server Requirements</p>
                <div class="content">
                    <ul class="requirements_list">
                        <li>
                            <?php if($phpVersion['supported'] ? $src = $greenCheck : $src = $redCheck): ?>
                                <img src="<?php echo $src ?>">
                            <?php endif; ?>
                            <span><b>PHP</b></span>
                            <small>(<?php echo $phpVersion['minimum'] ?> or higher)</small>
                            <br>
                            <?php if(!($phpVersion['supported'] == 1)): ?>
                                <small style="color: red;">
                                    Bagisto has detected that your PHP version (<?php echo $phpVersion['current']; ?>) is not supported.<br>
                                    Contact your provider if you are not the server administrator.
                                </small>
                            <?php endif; ?>
                        </li>

                        <?php foreach($requirements['requirements'] as $type => $require): ?>
                            <?php foreach($requirements['requirements'][$type] as $extention => $enabled) : ?>
                                <li>
                                    <?php if($enabled ? $src = $greenCheck : $src = $redCheck ): ?>
                                        <img src="<?php echo $src ?>">
                                    <?php endif; ?>
                                    <span><b><?php echo $extention ?></b></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                        <li>
                            <?php if(($composerInstall['composer_install'] == 0) ? $src = $greenCheck : $src = $redCheck ): ?>
                                <img src="<?php echo $src ?>">
                                <span><b>composer</b></span>
                            <?php endif; ?>
                            <br>                            
                            <?php if(!($composerInstall['composer_install'] == 0)): ?>
                                <small style="color: red;">
                                    <?php echo $composerInstall['composer'] ?>
                                </small>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>

                <?php if(!isset($requirements['errors']) && ($phpVersion['supported'] && $composerInstall['composer_install'] == 0)): ?>
                    <div><button type="button" class="prepare-btn" id="requirement-check">Continue</button></div>
                <?php elseif(!($phpVersion['supported'] && $composerInstall['composer_install'] == 0)): ?>
                    <div><button type="button" class="prepare-btn" id="requirements-refresh">Refresh</button></div>
                <?php endif; ?>

            </div>
        </div>
    </body>
</html>
