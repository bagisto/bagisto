<div class="container email" id="email">
    <div class="initial-display">
        <p>Email Configuration</p>
    </div>

    <div class="row justify-content-center">
		<div class="col-md-6 col-md-offset-1">
			<div class="card card-default">
				<div class="card-body">
                    <form action="EmailConfig.php" method= "POST" id="email-form">
                        <input type="hidden" name="mail_driver" value="smtp">
                        
                        <div class="form-row">
                            <div class="form-group col-md-6" id="mail_host">
                                <label for="mail_hostname" class="required">Outgoing mail server</label>
                                <input type="text" name="mail_host" id="mail_hostname" class="form-control" placeholder="smtp.mailtrap.io" data-validation="required">
                            </div>
                            
                            <div class="form-group col-md-6" id="mail_port">
                                <label for="mail_portnumber" class="required">Server port</label>
                                <input type="text" name="mail_port" id="mail_portnumber" class="form-control" placeholder="2525" data-validation="required">
                            </div>
                        </div>
                        
                        <div class="form-group" id="mail_encryption">
                            <label for="mail_encrypt">Encryption</label>
                            <select name="mail_encryption" id="mail_encrypt" class="form-control">
                                <option value="ssl">SSL</option>
                                <option value="tls">TLS</option>
                                <option value="">None</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="mail_from">
                            <label for="email_from" class="required">Store email address</label>
                            <input type="text" name="mail_from" id="email_from" class="form-control" placeholder="store@example.com" data-validation="required">
                        </div>
                        
                        <div class="form-group" id="mail_username">
                            <label for="email_username" class="required">Username</label>
                            <input type="text" name="mail_username" id="email_username" class="form-control" placeholder="store@example.com" data-validation="required">
                        </div>
                        
                        <div class="form-group" id="mail_password">
                            <label for="email_password" class="required">Password</label>
                            <input type="password" name="mail_password" id="email_password" class="form-control" data-validation="required">
                        </div>
                        
                        <div class="text-center">
                            <button class="btn btn-primary" id="mail-check">Save configuration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $.validate({});
</script>

<script>
    $(document).ready(function() {
        $('#email').hide();
        // process the form
        $('#email-form').submit(function(event) {
            $('.form-group').removeClass('has-error'); // remove the error class
            $('.form-error').remove(); // remove the error text
            // get the form data
            var mailformData = {
                'mail_driver'        : $('input[name=mail_driver]').val(),
                'mail_host'          : $('input[name=mail_host]').val(),
                'mail_port'          : $('input[name=mail_port]').val(),
                'mail_from'          : $('input[name=mail_from]').val(),
                'mail_username'      : $('input[name=mail_username]').val(),
                'mail_password'      : $('input[name=mail_password]').val(),
                'mail_encryption'    : $('select[name=mail_encryption]').val(),
            };

            var EmailTarget = window.location.href.concat('/EmailConfig.php');

            $.ajax({type : 'POST', url :  EmailTarget, data : mailformData, dataType : 'json', encode : true})
            .done(function(data) {
                if (!data.success) {
                    // handle errors
                    if (data.errors.mail_driver) {
                        $('#mail_driver').addClass('has-error');
                        $('#mail_driver').append('<div class="form-error">' + data.errors.mail_driver + '</div>');
                    }

                    if (data.errors.mail_host) {
                        $('#mail_host').addClass('has-error');
                        $('#mail_host').append('<div class="form-error">' + data.errors.mail_host + '</div>');
                    }

                    if (data.errors.mail_port) {
                        $('#mail_port').addClass('has-error');
                        $('#mail_port').append('<div class="form-error">' + data.errors.mail_port + '</div>');
                    }

                    // if (data.errors.mail_encryption) {
                    //     $('#mail_encryption').addClass('has-error');
                    //     $('#mail_encryption').append('<div class="form-error">' + data.errors.mail_encryption + '</div>');
                    // }

                    if (data.errors.mail_from) {
                        $('#mail_from').addClass('has-error');
                        $('#mail_from').append('<div class="form-error">' + data.errors.mail_from + '</div>');
                    }

                    if (data.errors.mail_username) {
                        $('#mail_username').addClass('has-error');
                        $('#mail_username').append('<div class="form-error">' + data.errors.mail_username + '</div>');
                    }

                    if (data.errors.mail_password) {
                        $('#mail_password').addClass('has-error');
                        $('#mail_password').append('<div class="form-error">' + data.errors.mail_password + '</div>');
                    }
                } else {
                    $('#admin').hide();
                    $('#email').hide();
                    $('#finish').show();
                }
            });
            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });
    });
</script>