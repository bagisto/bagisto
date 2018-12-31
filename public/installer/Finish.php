<html>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500">
        <title>Bagisto Installer</title>
        <link rel="icon" sizes="16x16" href="Images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="CSS/style.css">
    </head>

    <body>

        <div class="container finish" id="finish">
            <div class="initial-display" style="padding-top: 100px;">
                <img class="logo" src="Images/logo.svg">
                <p>Finish Installment</p>
                <div class="content">
                    <div class="content-container" style="padding: 20px">
                        <span>
                            Bagisto is successfully installed on your system. Click the below button to launch Admin Panel.
                        </span>
                    </div>
                </div>

                <button  class="prepare-btn" onclick="finish()">Finish</button>

            </div>
            <div class="footer">
                <img class="left-patern" src="Images/left-side.svg">
                <img class="right-patern" src="Images/right-side.svg">
            </div>
        </div>

    </body>

</html>

<script>

    function finish() {
        lastIndex = window.location.href.lastIndexOf("/");
        secondlast = window.location.href.slice(0,lastIndex).lastIndexOf("/");
        next = window.location.href.slice(0,secondlast);
        next = next.concat('/admin/login');
        window.location.href = next;
    }
</script>