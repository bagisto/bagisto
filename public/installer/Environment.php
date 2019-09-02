<div class="container environment" id="environment">
    <form action="EnvConfig.php" method="POST" id="environment-form">
        <div class="initial-display">
            <p>Environment Configuration</p>
            <div class="content">
                <div class="databse-error" style="text-align: center; padding-top: 10px" id="database_error"></div>
                <div class="form-container" style="padding: 10%; padding-top: 15px">
                    <div class="control-group" id="app_name">
                        <label for="app_name" class="required">Store Name</label>
                        <input type = "text" name = "app_name" class = "control" value = "Bagisto_" data-validation="required length" data-validation-length="max50">
                    </div>

                    <div class="control-group" id="app_url">
                        <label for="app_url" class="required">Store URL</label>
                        <input type="text" name="app_url" class="control" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>" data-validation="required length" data-validation-length="max50">
                    </div>

                    <div class="control-group">
                        <label for="database_connection" class="required">Database Connection</label>
                        <select name="database_connection" id="database_connection" class="control" data-validation="required length" data-validation-length="max50">
                            <option value="" selected="">Please select a option</option>
                            <option value="mysql">MySQL</option>
                            <option value="pgsql">PostgreSQL</option>
                            <option value="sqlite">SQLite</option>
                            <option value="sqlsrv">SQL Server</option>
                        </select>
                    </div>

                    <div class="control-group" id="port_name">
                        <label for="port_name" class="required">Database Port</label>
                        <input type="text" name="port_name" class="control" value="" data-validation="required alphanumeric number length" data-validation-length="max4">
                    </div>

                    <div class="control-group" id="host_name">
                        <label for="host_name" class="required">Database Host</label>
                        <input type="text" name="host_name"  class="control" value="" data-validation="required length" data-validation-length="max50">
                    </div>

                    <div class="control-group" id="database_name">
                        <label for="database_name" class="required">Database Name</label>
                        <input type="text" name="database_name" class="control" data-validation="length required" data-validation-length="max50">
                        <div id="SQLiteHelp">After creating a new SQLite database you can point this newly created database by using the database's absolute path</div>
                    </div>

                    <div class="control-group" id="user_name">
                        <label for="user_name" class="required">Database User Name</label>
                        <input type="text" name="user_name" class="control" data-validation="length required" data-validation-length="max50">
                    </div>

                    <div class="control-group" id="user_password">
                        <label for="user_password" class="required">Database User Password</label>
                        <input type="password" name="user_password" class="control">
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="prepare-btn" id="environment-check">Save & Continue</button>
                <button class="back-btn" id="envronment-back">Go back</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $.validate({});

        $('#port_name').hide();
        $('#host_name').hide();
        $('#database_name').hide();
        $('#user_name').hide();
        $('#user_password').hide();
        $('#SQLiteHelp').hide();

        $('#database_connection').on('change', function() {
            $('#port_name').show(); 
            $('#host_name').show();
            $('#database_name').show();
            $('#user_name').show();
            $('#user_password').show();
            $('#SQLiteHelp').hide();

            if($(this).val() == 'mysql') {
                $('#port_name input').val('3306');
                $('#host_name input').val('127.0.0.1');
                $('#database_name input').val('');
            } else if ($(this).val() == 'sqlsrv') {                
                $('#port_name input').val('1433');
                $('#database_name input').val('');
            } else if ($(this).val() == 'pgsql') {
                $('#port_name input').val('5432');
                $('#host_name input').val('127.0.0.1');
                $('#database_name input').val('');
            } else if ($(this).val() == 'sqlite') {
                $('#port_name').hide();
                $('#host_name').hide();
                $('#port_name input').val('3306');
                $('#host_name input').val('127.0.0.1');
                $('#database_name input').val('database/database.sqlite');
                $('#SQLiteHelp').show();
                $('#user_name').hide();
                $('#user_password').hide();
            } else {
                //  block of code to be executed if the condition1 is false and condition2 is false
                $('#port_name').hide();
                $('#host_name').hide();
                $('#database_name').hide();
                $('#user_name').hide();
                $('#user_password').hide();
            }
        });
        // process the form

        $('#environment-form').submit(function(event) {
            $('.control-group').removeClass('has-error'); // remove the error class
            $('.form-error').remove(); // remove the error text

            // get the form data
            var formData = {
                'app_name'            : $('input[name=app_name]').val(),
                'app_url'             : $('input[name=app_url]').val(),
                'host_name'           : $('input[name=host_name]').val(),
                'port_name'           : $('input[name=port_name]').val(),
                'database_name'       : $('input[name=database_name]').val(),
                'user_name'           : $('input[name=user_name]').val(),
                'user_password'       : $('input[name=user_password]').val(),
                'database_connection' : $("#database_connection" ).val(),
            };

            var target = window.location.href.concat('/EnvConfig.php');

            // process the form
            $.ajax({type: 'POST', url: target, data: formData, dataType: 'json', encode: true})
            // using the done promise callback
            .done(function(data) {
                if (!data.success) {
                    // handle errors
                    if (data.errors.app_name) {
                        $('#app_name').addClass('has-error');
                        $('#app_name').append('<div class="form-error">' + data.errors.app_name + '</div>');
                    }
                    if (data.errors.app_url) {
                        $('#app_url').addClass('has-error');
                        $('#app_url').append('<div class="form-error">' + data.errors.app_url + '</div>');
                    }
                    if (data.errors.host_name) {
                        $('#host_name').addClass('has-error');
                        $('#host_name').append('<div class="form-error">' + data.errors.host_name + '</div>');
                    }
                    if (data.errors.port_name) {
                        $('#port_name').addClass('has-error');
                        $('#port_name').append('<div class="form-error">' + data.errors.port_name + '</div>');
                    }
                    if (data.errors.user_name) {
                        $('#user_name').addClass('has-error');
                        $('#user_name').append('<div class="form-error">' + data.errors.user_name + '</div>');
                    }
                    if (data.errors.database_name) {
                        $('#database_name').addClass('has-error');
                        $('#database_name').append('<div class="form-error">' + data.errors.database_name + '</div>');
                    }
                    if (data.errors.user_password) {
                        $('#user_password').addClass('has-error');
                        $('#user_password').append('<div class="form-error">' + data.errors.user_password + '</div>');
                    }
                    if (data.errors.app_url_space) {
                        $('#app_url').addClass('has-error');
                        $('#app_url').append('<div class="form-error">' + data.errors.app_url_space + '</div>');
                    }
                    if (data.errors.app_name_space) {
                        $('#app_name').addClass('has-error');
                        $('#app_name').append('<div class="form-error">' + data.errors.app_name_space + '</div>');
                    }
                    if (data.errors.host_name_space) {
                        $('#host_name').addClass('has-error');
                        $('#host_name').append('<div class="form-error">' + data.errors.host_name_space + '</div>');
                    }
                    if (data.errors.port_name_space) {
                        $('#port_name').addClass('has-error');
                        $('#port_name').append('<div class="form-error">' + data.errors.port_name_space + '</div>');
                    }
                    if (data.errors.user_name_space) {
                        $('#user_name').addClass('has-error');
                        $('#user_name').append('<div class="form-error">' + data.errors.user_name_space + '</div>');
                    }
                    if (data.errors.database_name_space) {
                        $('#database_name').addClass('has-error');
                        $('#database_name').append('<div class="form-error">' + data.errors.database_name_space + '</div>');
                    }
                    if (data.errors.user_password_space) {
                        $('#user_password').addClass('has-error');
                        $('#user_password').append('<div class="form-error">' + data.errors.user_password_space + '</div>');
                    }
                    if (data.errors.database_error) {
                        $('#database_error').append('<div class="form-error">' + data.errors.database_error + '</div>');
                    }
                } else {
                    $('#environment').hide();
                    $('#migration').show();
                }
            })

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });
});
</script>