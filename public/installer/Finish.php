<html>
    <body>
        <div class="container finish" id="finish">
            <div class="initial-display">
                <p>Installation completed</p>
                <div class="content">
                    <div class="content-container" style="padding: 20px">
                        <span>
                            Bagisto is successfully installed on your system.<br>
                            Click the below button to launch the admin panel.
                        </span>
                    </div>
                </div>

                <button  class="prepare-btn" onclick="finish()">Continue</button>
            </div>
        </div>
    </body>
</html>

<script>
    function finish() {
        next = window.location.href.split("/installer")[0];
        next = next.concat('/admin/login');
        window.location.href = next;
    }
</script>
