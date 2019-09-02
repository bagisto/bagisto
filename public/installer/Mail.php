<div class="container mailsettings" id="mailsettings">
    <div class="initial-display">
        <p>Email configuration</p>
        <form action="MailConfig.php" method= "POST" id="mail-form">
            <div class="content">
                <div class="form-container" style="padding: 10%; padding-top: 25px">
                    <div class="control-group">
                        <label for="mail_hostname" class="required">SMTP Host</label>
                        <input type="text" name="mail_hostname" id="mail_hostname" class="control" value="127.0.0.1" data-validation="required length" data-validation-length="max50">
                    </div>

                    <div class="control-group">
                        <label for="mail_port" class="required">SMTP Port</label>
                        <input type="number" name="mail_port" id="mail_port" class="control" value="25" data-validation="required alphanumeric number length" data-validation-length="max4">
                    </div>

                    <div class="control-group">
                        <label for="mail_username" class="required">Email username</label>
                        <input type="text" name="mail_username" id="mail_username" class="control" value="">
                    </div>

                    <div class="control-group">
                        <label for="mail_password" class="required">Email password</label>
                        <input type="password" name="mail_password" id="mail_password" class="control" value="">
                    </div>

                    <hr>

                    <div class="control-group">
                        <label for="mail_from" class="required">Store email</label>
                        <input type="text" name="mail_from" id="mail_from" class="control" value="shop@bagsaas.com" data-validation="length required" data-validation-length="max50">
                    </div>

                    <div class="control-group">
                        <label for="mail_to" class="required">Administrator email</label>
                        <input type="text" name="mail_to" id="mail_to" class="control" value="admin@bagsaas.com" data-validation="length required" data-validation-length="max50">
                    </div>
                </div>              
            </div>

            <div>
                <button type="submit" class="prepare-btn" id="environment-mail-check">Save & Continue</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.mailsettings').hide();
        $.validate({});

        // process the form
        $('#mail-form').submit(function(event) {
            console.log('Triggering the email environment settings.');
            $('.control-group').removeClass('has-error'); // remove the error class
            $('.form-error').remove(); // remove the error text
            // get the form data
            var formData = {
                'mail_hostname'  : $('input[name=mail_hostname]').val(),
                'mail_port'      : $('input[name=mail_port]').val(),
                'mail_username'  : $('input[name=mail_username]').val(),
                'mail_password'  : $('input[name=mail_password]').val(),
            };

            var mailTarget = window.location.href.concat('/MailConfig.php');
            console.log('Saving email environment settings.');
            // process the form
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         :  mailTarget, // the url where we want to POST
                data        : formData, // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                encode      : true
            })
                // using the done promise callback
            .done(function(data) {
                $('#mail-form').hide();
                $('#migrate').show();
            });
            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });
    });
</script>