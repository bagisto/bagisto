<style>
    .window {
        max-height: 488px;
        background: #222;
        color: #fff;
        overflow: hidden;
        position: relative;
        margin: 0 auto;
        width: 100%;

        &:before {
            content: ' ';
            display: block;
            height: 48px;
            background: #C6C6C6;
        }

        &:after {
            content: '. . .';
            position: absolute;
            left: 12px;
            right: 0;
            top: -3px;
            font-family: "Times New Roman", Times, serif;
            font-size: 96px;
            color: #fff;
            line-height: 0;
            letter-spacing: -12px;
        }
    }

    .terminal {
        margin: 20px;
        font-family: monospace;
        font-size: 16px;
        color: #22da26;
        max-height: 488px;

        .command {
            width: 0%;
            white-space: nowrap;
            overflow: hidden;
            animation: write-command 5s both;

            &:before {
                content: '$ ';
                color: #22da26;
            }
        }
    }
</style>

<div class="container migration" id="migration">
    <div class="initial-display">
        <p>Database Configuration</p>
    </div>

    <div class="row justify-content-center">
		<div class="col-md-6 col-md-offset-1">
			<div class="card card-default">
				<div class="card-body" id="migration-result">
                    <div class="cp-spinner cp-round" id="loader"></div>

                    <div id="install-log">
                        <label for="install-details">Installation log</label>
                        <textarea rows="15" id="install-details" class="form-control"></textarea>
                    </div>
                    
                    <div class="instructions" style="padding-top: 40px;" id="instructions">
                        <div style="text-align: center;">
                            <h4> Click the button below to run following :</h4>
                        </div>
                        
                        <div class="message">
                            <span>Create the database tables </span>
                        </div>
                        
                        <div class="message">
                            <span> Populate the database tables </span>
                        </div>
                        
                        <div class="message">
                            <span> Publishing Vendor </span>
                        </div>
                    </div>
                   
                    
                    <div class="row">
                        <div class="col-md-12 text-center">
                        <p class="composer" id="comp">Checking Composer Dependency</p>
                        <p class="composer" id="composer-migrate">Migrating Database</p>
                        <p class="composer" id="composer-seed">Seeding Data</p>
                        </div>
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <form method="POST" id="migration-form">
                        <div style="text-align: center;">
                            <button class="btn btn-primary" id="migrate-seed">Start installation</button>
                            <button class="btn btn-primary" id="continue">Continue</button>
                        </div>
                        
                        <div style="cursor: pointer; margin-top:10px">
                            <span id="migration-back">back</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#continue').hide();
        $('#loader').hide();
        $('#install-log').hide();

        // process the form
        $('#migration-form').submit(function(event) {
            // showing loader & hiding migrate button
            $('#loader').show();
            $('#comp').show();
            $('#instructions').hide();
            $('#migrate-seed').hide();
            $('#migration-back').hide();
            $('#migrate').hide();
            $('#seed').hide();
            $('#publish').hide();
            $('#storage').hide();
            $('#composer').hide();

            var composerTarget = window.location.href.concat('/Composer.php');

            // process form
            $.ajax({
                type        : 'POST',
                url         : composerTarget,
                dataType    : 'json',
                encode      : true
            })

            .done(function(data) {
                if (data) {
                    $('#comp').hide();
                    $('#seed').show();
                    $('#publish').show();
                    $('#storage').show();
                    $('#composer').show();

                    if (data['install'] == 0) {
                        $('#composer-migrate').show();

                        var migrationTarget = window.location.href.concat('/MigrationRun.php');

                        // post the request again
                        $.ajax({
                            type        : 'POST',
                            url         : migrationTarget,
                            dataType    : 'json',
                            encode      : true
                        })
                            // using the done promise callback
                        .done(function(data) {
                            if(data) {
                                $('#composer-migrate').hide();

                                if (data['results'] == 0) {
                                    $('#composer-seed').show();

                                    var seederTarget = window.location.href.concat('/Seeder.php');

                                    $.ajax({
                                        type        : 'POST',
                                        url         :  seederTarget,
                                        dataType    : 'json',
                                        encode      : true
                                    })
                                     // using the done promise callback
                                    .done(function(data) {
                                        $('#composer-seed').hide();
                                        $('#install-log').show();

                                        if (data['seeder']) {
                                            $('#install-details').append(data['seeder']);
                                        }
                                        if (data['publish']) {
                                            $('#install-details').append(data['publish']);
                                        }
                                        if (data['key']) {
                                            $('#install-details').append(data['key']);
                                        }

                                        if ((data['key_results'] == 0) && (data['seeder_results'] == 0) && (data['publish_results'] == 0)) {
                                            $('#continue').show();
                                            $('#migrate-seed').hide();
                                            $('#loader').hide();
                                        };
                                    });

                                } else {
                                    $('#migrate').show();
                                    $('#loader').hide();
                                    $('#migrate-seed').hide();
                                    $('#migration-back').hide();
                                    if (data['migrate']) {
                                        $('#install-details').append(data['migrate']);
                                    }
                                }
                            }
                        });

                    } else {
                        $('#loader').hide();
                        $('#composer-migrate').hide();
                        $('#migrate-seed').hide();
                        $('#migration-back').hide();
                        $('#install-details').append(data['composer']);
                        $('#install-log').show();
                    }
                }
            });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });

    });
</script>
