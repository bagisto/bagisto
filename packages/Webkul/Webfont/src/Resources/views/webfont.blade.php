@if(core()->getConfigData('general.design.webfont.status') && core()->getConfigData('general.design.webfont.enable_backend') || (core()->getConfigData('general.design.webfont.status') && core()->getConfigData('general.design.webfont.enable_frontend')))
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>

    @php
        $activatedFont = app('Webkul\Webfont\Repositories\WebfontRepository');

        $primaryColor = core()->getConfigData('general.design.webfont.primary_color') ?? '#02bb89';

        $secondaryColor = core()->getConfigData('general.design.webfont.secondary_color') ?? '#436be0';

        $font = $activatedFont->findOneWhere([
            'activated' => 1
        ]);

        if (isset($font)) {
            $activeFont = $font->font;

            $font = explode(',', $activeFont)[0];

            $family = explode(',', $activeFont)[1];
        } else {
            $font = 'Montserrat';

            $family = 'sans-serif';
        }
    @endphp

    <script>
    WebFont.load({
        google: {
        families: ['{{ $font }}']
        }
    });
    </script>

    <style>
        * {
            font-family: "{{ $font }}", {{ $family }};
        }

        *::-webkit-input-placeholder {
            font-family: $font-montserrat;
        }

        *::-webkit-input-placeholder {
            font-family: $font-montserrat;
        }

        input {
            font-family: $font-montserrat;
        }

        body {
            font-family: "{{ $font }}", {{ $family }};
        }

        .btn.btn-primary {
            background: {{ $primaryColor }};
            color: #fff;
        }

        .btn.btn-black {
            background: {{ $secondaryColor }};
            color: #fff;
        }

        .btn.btn-white {
            background: #c7c7c7;
            color: #fff;
        }

        .btn:disabled, .btn[disabled="disabled"], .btn[disabled="disabled"]:hover, .btn[disabled="disabled"]:active {
            cursor: not-allowed;
            background: {{ $secondaryColor }};
            -webkit-box-shadow: none;
                    box-shadow: none;
            opacity: 1;
        }

        .tabs ul li.active a {
            border-bottom: 3px solid {{ $secondaryColor }};
        }

        .dropdown-list .dropdown-container ul li a:hover {
            color: {{ $secondaryColor }};
        }

        a:hover {
            color: {{ $secondaryColor }};
        }

        a:link, a:hover, a:visited, a:focus, a:active {
            color: {{ $secondaryColor }};
        }

        .control-group .control:focus {
            border-color: {{ $primaryColor }};
        }

        .account-content .menu-block .menubar li.active a {
            color: {{ $primaryColor }};
        }

        .dashboard .dashboard-stats .dashboard-card .title {
            color: {{ $primaryColor }}
        }

        .dashboard .dashboard-stats .dashboard-card .data {
            color: {{ $secondaryColor }}
        }

        .dashboard .card .card-info ul li .description .name {
            color: {{ $primaryColor }}
        }
    }
    </style>
@endif