<html>

    <style>
        .window {
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

    </style>

    <body>

        <div class="container migration" id="migration">
            <div class="initial-display">
                <p>Migration & Seed</p>

                <div class="cp-spinner cp-round" id="loader">
                </div>

                <div class="content" id="migration-result">
                    <div class="window" id="migrate">
                    </div>
                    <div class="window" id="key">
                    </div>
                    <div class="window" id="seed">
                    </div>
                    <div class="window" id="publish">
                    </div>
                    <div class="window" id="composer">
                    </div>

                    <div class="instructions" style="padding-top: 40px;" id="instructions">
                        <div style="text-align: center;">
                            <span> Click the below button to run following : </span>
                        </div>
                        <div class="message">
                            <span>Database Migartion </span>
                        </div>
                        <div class="message">
                            <span> Database Seeder </span>
                        </div>
                        <div class="message">
                            <span> Publishing Vendor </span>
                        </div>
                        <div class="message">
                            <span> Generating Application Key </span>
                        </div>
                    </div>

                    <span class="composer" id="comp" style="left: calc(50% - 135px);">Checking Composer Dependency</span>
                    <span class="composer"  id="composer-migrate" style="left: calc(50% - 85px);">Migrating Database</span>
                    <span class="composer"  id="composer-seed" style="left: calc(50% - 55px);">Seeding Data</span>
                </div>

                <form method="POST" id="migration-form">
                    <div>
                        <button class="prepare-btn" id="migrate-seed">Migrate & Seed</button>
                        <button class="prepare-btn" id="continue">Continue</button>
                    </div>
                    <div style="cursor: pointer; margin-top:10px">
                        <span id="migration-back">back</span>
                    </div>
                </form>

            </div>
        </div>

    </body>

</html>

<script>
    $(document).ready(function() {
        $('#continue').hide();
        $('#loader').hide();

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

                                        if (data['seeder']) {
                                            $('#seed').append('<div class="terminal">' + data['seeder'] + '</div>');
                                        }
                                        if (data['publish']) {
                                            $('#publish').append('<div class="terminal">' + data['publish'] + '</div>');
                                        }
                                        if (data['key']) {
                                            $('#key').append('<div class="terminal">' + data['key'] + '</div>');
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
                                        $('#migrate').append('<div class="terminal">' + data['migrate'] + '</div>');
                                    }
                                }
                            }
                        });

                    } else {
                        $('#loader').hide();
                        $('#composer-migrate').hide();
                        $('#migrate-seed').hide();
                        $('#migration-back').hide();
                        $('#composer').append('<div class="terminal">' + data['composer'] +'</div>');
                    }
                }
            });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });

    });
</script>