<div class="container environment" id="environment">
    <div class="initial-display">
        <p>Environment Configuration</p>
    </div>

	<div class="row justify-content-center">
		<div class="col-md-6 col-md-offset-1">
			<div class="card card-default">
				<div class="card-body">
                    <form action="EnvConfig.php" method="POST" id="environment-form">
                        <div id="app-settings">
                            <div class="form-group" id="app_name">
                                <label for="application_name" class="required">Application Name</label>
                                <input type= "text" name= "app_name" id="application_name" class="form-control" value="Bagisto_" data-validation="required length" 
                                data-validation-length="max20">
                            </div>
                            
                            <div class="form-group" id="app_url">
                                <label for="application_url" class="required">Default URL</label>
                                <input type="text" name="app_url" id="application_url" class="form-control" value="https://<?php echo $_SERVER['HTTP_HOST']; ?>" 
                                    data-validation="required length" data-validation-length="max50">
                            </div>
                            
                            <div class="form-group" id="app_currency">
                                <label for="application_currency" class="required">Default Currency</label>
                                <select name="app_currency" id="application_currency" class="form-control" data-validation="required length" data-validation-length="max50">
                                    <option value="EUR">Euro</option>
                                    <option value="USD" selected>US Dollar</option>
                                </select>
                            </div>
                            
                            <div class="form-group" id="app_timezone">
                                <label for="application_timezone" class="required">Default Timezone</label>
                                <select name="app_timezone" id="application_timezone" class="js-example-basic-single">
                                    <?php 
                                    
                                    $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                    $current = date_default_timezone_get();
                                    
                                    foreach($tzlist as $key => $value) {
                                        if(!$value === $current) {
                                            echo "<option value='$value' selected>" . $value . "</option>";
                                        } else {
                                            echo "<option value='$value'>" . $value . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group" id="app_locale">
                                <label for="application_locale" class="required">Default Locale</label>
                                <select name="app_locale" id="application_locale" class="form-control" data-validation="required">
                                    <option value="nl">Dutch</option>
                                    <option value="en" selected>English</option>
                                    <option value="fr">French</option>
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-primary" id="environment-next">Continue</button>
                                <button type="button" class="btn btn-secondary" id="envronment-back">Go back</button>
                            </div>
                        </div>

                        <div id="database-settings">
                            <div class="databse-error" id="database_error"></div>
                            
                            <div class="form-group" id="database_connection">
                                <label for="db_connection" class="required">Database Connection</label>
                                <select name="database_connection" id="db_connection" class="form-control">
                                    <option value="mysql" selected>Mysql</option>
                                    <option value="sqlite">SQlite</option>
                                    <option value="pgsql">pgSQL</option>
                                    <option value="sqlsrv">SQLSRV</option>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6" id="host_name">
                                    <label for="db_hostname" class="required">Database Hostname</label>
                                    <input type="text" name="host_name" id="db_hostname" class="form-control" value="127.0.0.1" 
                                    data-validation="required length" data-validation-length="max50">
                                </div>
                                
                                <div class="form-group col-md-6" id="port_name">
                                    <label for="db_port" class="required">Database Port</label>
                                    <input type="text" name="port_name" id="db_port" class="form-control" value="3306" 
                                    data-validation="required alphanumeric number length" data-validation-length="max5">
                                </div>
                            </div>

                            <div class="form-group" id="database_name">
                                <label for="db_name" class="required">Database Name</label>
                                <input type="text" name="database_name" id="db_name" class="form-control" 
                                data-validation="length required" data-validation-length="max50">
                            </div>

                            <div class="form-group" id="user_name">
                                <label for="db_username" class="required">Database Username</label>
                                <input type="text" name="user_name" id="db_username" class="form-control" 
                                data-validation="length required" data-validation-length="max50">
                            </div>

                            <div class="form-group" id="user_password">
                                <label for="db_password" class="required">Database Password</label>
                                <input type="password" name="user_password" id="db_password" class="form-control" data-validation="required">
                            </div>

                            <div class="text-center">
                                <button class="btn btn-primary" id="environment-check">Save & Continue</button>
                                <button type="button" class="btn btn-secondary" id="environment-first">Go back</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            theme: 'bootstrap4'
        });

        $.validate({});
        $('#database-settings').hide();
        $('#environment-next').click(function() {
            $('#app-settings').hide();
            $('#database-settings').show(); 
        });

        $('#environment-first').click(function() {
            $('#app-settings').show();
            $('#database-settings').hide(); 
        });

        // process the form
        $('#environment-form').submit(function(event) {
            $('.control-group').removeClass('has-error'); // remove the error class
            $('.form-error').remove(); // remove the error text

            // get the form data
            var formData = {
                'app_name'            : $('input[name=app_name]').val(),
                'app_url'             : $('input[name=app_url]').val(),
                'app_currency'        : $('select[name=app_currency]').val(),
                'app_locale'          : $('select[name=app_locale]').val(),
                'app_timezone'        : $('select[name=app_timezone]').val(),
                'host_name'           : $('input[name=host_name]').val(),
                'port_name'           : $('input[name=port_name]').val(),
                'database_connection' : $("select[name=database_connection]" ).val(),
                'database_name'       : $('input[name=database_name]').val(),
                'user_name'           : $('input[name=user_name]').val(),
                'user_password'       : $('input[name=user_password]').val(),
            };

            var target = window.location.href.concat('/EnvConfig.php');

            // process the form
            $.ajax({
                type        : 'POST',
                url         : target,
                data        : formData,
                dataType    : 'json',
                encode      : true
            })
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
                    if (data.errors.app_timezone) {
                        $('#app_timezone').addClass('has-error');
                        $('#app_timezone').append('<div class="form-error">' + data.errors.app_timezone + '</div>');
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
            });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });
    });
</script>