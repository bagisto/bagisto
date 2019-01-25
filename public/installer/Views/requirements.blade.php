<html>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500">
        <title>Bagisto Installer</title>
        <link rel="icon" sizes="16x16" href="Images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="CSS/style.css">
    </head>

    <body>

        <div class="container requirement" id="requirement">
            <div class="initial-display" style="padding-top: 100px;">
                <img class="logo" src="Images/logo.svg">
                <p>Requirement</p>

                <div class="content">
                    <div class="title" style="text-align: center; margin-top: 10px">
                        Please wait while we are checking the requirements
                    </div>

                    <div class="check" style="margin-left: 25%">
                        <?php if($phpVersion['supported'] ? $src = 'Images/green-check.svg' : $src = 'Images/red-check.svg' ): ?>
                            <img src="<?php echo $src ?>">
                        <?php endif; ?>
                        <span style="margin-left: 10px"><b>PHP</b></span>
                        <span>(<?php echo $phpVersion['minimum'] ?> or Higher)</span>
                    </div>

                    <div class="check" style="margin-left: 25%;">
                        <?php if(($composerInstall == 0) ? $src = 'Images/green-check.svg' : $src = 'Images/red-check.svg' ): ?>
                            <img src="<?php echo $src ?>">
                        <?php endif; ?>
                        <span style="margin-left: 10px"><b>Composer</b></span>
                    </div>

                    <div style="margin-left: 30%;">
                        <?php if(!($composerInstall == 0)): ?>
                            <a href="https://getcomposer.org/" style="color: #0041FF; font-size: 16px">https://getcomposer.org/</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($phpVersion['supported'] && ($composerInstall == 0)): ?>

                    <div>
                        <button type="button" class="prepare-btn" id="requirement-check">Continue</button>
                    </div>
                    <div style="cursor: pointer; margin-top:10px">
                        <span id="requirement-back">back</span>
                    </div>

                <?php endif; ?>

            </div>

            <div class="footer">
                <img class="left-patern" src="Images/left-side.svg">
                <img class="right-patern" src="Images/right-side.svg">
            </div>
        </div>

    </body>
</html>