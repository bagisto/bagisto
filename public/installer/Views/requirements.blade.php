<?php
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $greenCheck = $actual_link .'/'. 'Images/green-check.svg';
    $redCheck = $actual_link .'/'. 'Images/red-check.svg';
?>

<div class="container requirement" id="requirement">
    <div class="initial-display">
        <p>Server Requirements</p>
    </div>

    <div class="row justify-content-center">
		<div class="col-md-6 col-md-offset-1">
			<div class="card card-default">
				<div class="card-body">
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

                    <?php if(!isset($requirements['errors']) && ($phpVersion['supported'] && $composerInstall['composer_install'] == 0)): ?>
                        <div class="text-center"><button type="button" class="btn btn-primary" id="requirement-check">Continue</button></div>
                    <?php elseif(!($phpVersion['supported'] && $composerInstall['composer_install'] == 0)): ?>
                        <div class="text-center"><button type="button" class="btn btn-primary" id="requirements-refresh">Refresh</button></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
