   <div class="container environment" id="email">
        <div class="initial-display">
            <p>Email Configuration</p>
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
                <button type="submit" class="prepare-btn" id="environment-check">Save & Continue</button>
                <button type="button" class="back-btn" id="envronment-back">Go back</button>
            </div>
        </div>
    </div>