<div class="container finish" id="finish">
    <div class="initial-display">
        <p>Installation completed</p>
        <div class="content">
            <div class="content-container" style="padding: 20px">
                <span>
                    Bagisto is successfully installed on your system.
                </span>
            </div>
        </div>

        <button  class="prepare-btn" onclick="finish()">Launch the admin panel</button>
    </div>
</div>

<script>
    function finish() {
        lastIndex = window.location.href.lastIndexOf("/");
        secondlast = window.location.href.slice(0, lastIndex).lastIndexOf("/");
        next = window.location.href.slice(0, secondlast);
        next = next.concat('/admin/login');
        window.location.href = next;
    }
</script>