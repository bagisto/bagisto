<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>

@php
    $activatedFont = app('Webkul\Webfont\Repositories\WebfontRepository');

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
</style>