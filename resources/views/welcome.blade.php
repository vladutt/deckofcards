<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
        <title>Deck of Cards - API</title>
    </head>
    <body style="background: black">

        <div class="content">
            <div class="row text-white p-5">
                <div class="col-md-12 text-center">
                    <h2>Deck of Cards - API</h2>
                </div>
                <div class="col-md-6 text-center offset-3">

                    <p>
                        Link: <a href="{{url('/api/decks/1')}}">New deck</a>
                    </p>

                    <p>
                        Link: <a href="{{url('/api/decks/new')}}">Brand new deck</a>
                    </p>

                    <p>
                        Link: <a href="{{url('/api/decks/new/draw/2')}}">New deck + draw cards</a>
                    </p>

                        <pre class="text-white">{{url('/api/decks/{decks}')}}</pre>

                        <pre class="text-white">{{url('/api/decks/{deck}/draw/{cards}')}}</pre>

                        <pre class="text-white">{{url('/api/decks/{deck}/shuffle')}}</pre>

                        <pre class="text-white">{{url('/api/decks/partial/{cards}')}}</pre>

                </div>
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}" type="text/js"></script>
    </body>
</html>
