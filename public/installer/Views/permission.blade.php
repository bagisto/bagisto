<html>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500">
        <title>Bagisto Installer</title>
        <link rel="icon" sizes="16x16" href="Images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="CSS/style.css">
    </head>

    <body>

        <div class="container permission" id="permission">
            <div class="initial-display" style="padding-top: 100px;">
                <img class="logo" src="Images/logo.svg">
                <p>Permission</p>

                <div class="content">
                    <div class="title" style="text-align: center; margin-top: 10px">
                        Please wait while we are checking the permissions
                    </div>

                    <?php foreach($permissions['permissions'] as $permission): ?>
                        <div class="check" style="margin-left: 20%">
                            <?php if($permission['isSet'] ? $src = 'Images/green-check.svg' : $src = 'Images/red-check.svg' ): ?>
                                <img src="<?php echo $src ?>">
                            <?php endif; ?>
                            <span style="margin-left: 10px"><b><?php echo $permission['folder'] ?></b></span>
                            <span>(<?php echo $permission['permission'] ?> folder permission Required)</span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if(!isset($permissions['errors'])): ?>
                    <div>
                        <button class="prepare-btn" id="permission-check">Continue</button>
                    </div>
                    <div style="cursor: pointer; margin-top:10px">
                        <span id="permission-back">back</span>
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











