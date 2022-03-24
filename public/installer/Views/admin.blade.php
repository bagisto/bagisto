<div class="container admin" id="admin">
    <div class="initial-display">
        <p>Create a Administrator</p>
    </div>

    <div class="row justify-content-center">
		<div class="col-md-6 col-md-offset-1">
			<div class="card card-default">
                <div class="card-body">
                    <form action="AdminConfig.php"  method= "POST" id="admin-form">
                        <div class="form-group" id="admin_name">
                            <label for="admin_user_name" class="required">Name</label>
                            <input type="text" name="admin_name" id="admin_user_name" class="form-control" data-validation="required length" data-validation-length="max50">
                        </div>
                        
                        <div class="form-group" id="admin_email">
                            <label for="admin_user_email" class="required">Email</label>
                            <input type="text" name="admin_email" id="admin_user_email" class="form-control" data-validation="required email length" data-validation-length="max50">
                        </div>
                        
                        <div class="form-group" id="admin_password">
                            <label for="admin_user_password" class="required">Password</label>
                            <input type="password" name="admin_password" id="admin_user_password" class="form-control" data-validation="length required" data-validation-length="min6">
                        </div>
                        
                        <div class="form-group" id="admin_re_password">
                            <label for="admin_user_repassword" class="required">Password confirmation</label>
                            <input type="password" name="admin_re_password" id="admin_user_repassword" class="form-control" data-validation="length required" data-validation-length="min6">
                        </div>

                        <div class="text-center" style="padding-bottom: 10px;">
                            <button type="submit" class="btn btn-primary" id="admin-check">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.validate({});
        // process the form
        $('#admin-form').submit(function(event) {
            $('.form-group').removeClass('has-error'); // remove the error class
            $('.form-error').remove(); // remove the error text
            // get the form data
            var formData = {
                'admin_email'        : $('input[name=admin_email]').val(),
                'admin_name'         : $('input[name=admin_name]').val(),
                'admin_password'     : $('input[name=admin_password]').val(),
                'admin_re_password'  : $('input[name=admin_re_password]').val(),
            };

            var adminTarget = window.location.href.concat('/AdminConfig.php');
            // process the form
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         :  adminTarget, // the url where we want to POST
                data        : formData, // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                            encode          : true
            })
                // using the done promise callback
            .done(function(data) {
                if (!data.success) {
                    // handle errors
                    if (data.errors.admin_email) {
                        $('#admin_email').addClass('has-error');
                        $('#admin_email').append('<div class="form-error">' + data.errors.admin_email + '</div>');
                    }
                    if (data.errors.admin_name) {
                        $('#admin_name').addClass('has-error');
                        $('#admin_name').append('<div class="form-error">' + data.errors.admin_name + '</div>');
                    }
                    if (data.errors.admin_password) {
                        $('#admin_password').addClass('has-error');
                        $('#admin_password').append('<div class="form-error">' + data.errors.admin_password + '</div>');
                    }
                    if (data.errors.admin_re_password) {
                        $('#admin_re_password').addClass('has-error');
                        $('#admin_re_password').append('<div class="form-error">' + data.errors.admin_re_password + '</div>');
                    }
                    if (data.errors.password_match) {
                        $('#admin_re_password').addClass('has-error');
                        $('#admin_password').addClass('has-error');
                        $('#admin_re_password').append('<div class="form-error">' + data.errors.password_match + '</div>');
                        $('#admin_password').append('<div class="form-error">' + data.errors.password_match + '</div>');
                    }
                } else {
                    // error handling for database
                    // connection error
                    if (data['connection']) {
                        alert(data['connection']);
                    }
                    // insert error
                    if (data['insert_fail']) {
                        alert(data['insert_fail']);
                    }
                    // Database support error
                    if (data['support_error']) {
                        result = confirm(data['support_error']);
                        if (result) {
                            $('#admin').hide();
                            $('#email').show();
                        }
                    }
                    if (!data['connection'] && !data['insert_fail'] && !data['support_error']) {
                        $('#admin').hide();
                        $('#email').show();
                    }
                }
            });
            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });
    });
</script>