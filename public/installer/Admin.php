<html>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500">
        <title>Bagisto Installer</title>
        <link rel="icon" sizes="16x16" href="Images/favicon.ico">

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
    </head>

    <body>

        <div class="container admin" id="admin">
            <div class="initial-display" style="padding-top: 100px;">
                <img class="logo" src="Images/logo.svg">
                <p>Admin Details</p>

                <form action="AdminConfig.php"  method= "POST" id="admin-form">
                    <div class="content">
                        <div class="form-container" style="padding: 10%; padding-top: 35px">
                            <div class="control-group" id="admin_name">
                                <label for="admin_name" class="required">Name</label>
                                <input type="text" name="admin_name" class="control"
                                data-validation="required length" data-validation-length="max50">
                            </div>

                            <div class="control-group" id="admin_email">
                                <label for="admin_email" class="required">Email</label>
                                <input type="text" name="admin_email" class="control"
                                data-validation="required email length" data-validation-length="max50">
                            </div>

                            <div class="control-group" id="admin_password">
                                <label for="admin_password" class="required">Password</label>
                                <input type="password" name="admin_password" class="control"
                                data-validation="length required" data-validation-length="max50">
                            </div>

                            <div class="control-group" id="admin_re_password">
                                <label for="admin_re_password" class="required">Re-Password</label>
                                <input type="password" name="admin_re_password" class="control"
                                data-validation="length required" data-validation-length="max50">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button  class="prepare-btn" id="admin-check">Continue</button>
                    </div>
                </form>

            </div>
            <div class="footer">
                <img class="left-patern" src="Images/left-side.svg">
                <img class="right-patern" src="Images/right-side.svg">
            </div>
        </div>

    </body>

</html>

<script>
    $.validate({});
</script>


<script>
    $(document).ready(function() {

        // process the form
        $('#admin-form').submit(function(event) {

            $('.control-group').removeClass('has-error'); // remove the error class
            $('.form-error').remove(); // remove the error text

            // get the form data
            var formData = {
                'admin_email'        : $('input[name=admin_email]').val(),
                'admin_name'         : $('input[name=admin_name]').val(),
                'admin_password'     : $('input[name=admin_password]').val(),
                'admin_re_password'  : $('input[name=admin_re_password]').val(),
            };

            // process the form
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : 'AdminConfig.php', // the url where we want to POST
                data        : formData, // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                            encode          : true
            })
                // using the done promise callback
            .done(function(data) {

                if (!data.success) {
                    // handle errors
                    if (data.errors.admin_email) {
                        $('#admin_email').addClass('has-error'); // add the error class to show red input
                        $('#admin_email').append('<div class="form-error">' + data.errors.admin_email + '</div>'); // add the actual error message under our input
                    }
                    if (data.errors.admin_name) {
                        $('#admin_name').addClass('has-error'); // add the error class to show red input
                        $('#admin_name').append('<div class="form-error">' + data.errors.admin_name + '</div>'); // add the actual error message under our input
                    }
                    if (data.errors.admin_password) {
                        $('#admin_password').addClass('has-error'); // add the error class to show red input
                        $('#admin_password').append('<div class="form-error">' + data.errors.admin_password + '</div>'); // add the actual error message under our input
                    }
                    if (data.errors.admin_re_password) {
                        $('#admin_re_password').addClass('has-error'); // add the error class to show red input
                        $('#admin_re_password').append('<div class="form-error">' + data.errors.admin_re_password + '</div>'); // add the actual error message under our input
                    }
                    if (data.errors.password_match) {
                        $('#admin_re_password').addClass('has-error'); // add the error class to show red input
                        $('#admin_password').addClass('has-error'); // add the error class to show red input
                        $('#admin_re_password').append('<div class="form-error">' + data.errors.password_match + '</div>'); // add the actual error message under our input
                        $('#admin_password').append('<div class="form-error">' + data.errors.password_match + '</div>'); // add the actual error message under our input
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
                            $('#finish').show();
                        }
                    }

                    if (!data['connection'] && !data['insert_fail'] && !data['support_error']) {
                        $('#admin').hide();
                        $('#finish').show();
                    }
                }
            });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });

    });
</script>



