<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
        <title>Deck of Cards - API</title>
    </head>
    <body style="background: black">

        <div class="container">
            <div class="row text-white p-5">
                <div class="col-md-12 text-center">
                    <h4>Deck of Cards - API</h4>
                </div>
                <div class="col-md-6 text-center offset-3">

                    Magic tricks!!!

                </div>

                @include('decks-request')

            </div>
        </div>
        <script src="{{ asset('js/app.js') }}" type="text/js"></script>
    </body>
</html>
