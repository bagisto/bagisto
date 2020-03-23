<div class="container finish" id="finish">
    <div class="initial-display">
        <p>Installation completed</p>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card card-default">
                <div class="card-body">
                    <div class="alert alert-success">
                        <b>Bagisto</b> is successfully installed on your system.<br>
                    </div>
                </div>                
            </div>

            <div class="clearfix">&nbsp;</div>
            <div class="" role="toolbar" aria-label="buttons">
                <button class="btn btn-primary" onclick="finish()">Launch the admin interface</button>
                <a href="https://bagisto.com/en/extensions/" class="btn btn-secondary" target="_blank">Bagisto Extensions</a>
                <a href="https://forums.bagisto.com/" class="btn btn-secondary" target="_blank">Bagisto Forums</a>
            </div> 
        </div>
    </div>
</div>

<script>
    function finish() {
        next = window.location.href.split("/installer")[0];
        next = next.concat('/admin/login');
        window.location.href = next;
    }
    
    $(document).ready(function() {
        $('#finish').hide();
        $('#requirement').show();
    });
</script>