<div class="col-md-12">

    <section class="mb-5">
        <h2>Create a new deck</h2>
        <pre class="text-white">{{url('/api/decks/1')}}</pre>
        <p>Create 1 or more decks of cards. Max number is 10.</p>
        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 52
}</pre>
    </section>

    <hr>

    <section class="mb-5">
        <h2>Create a new brand deck</h2>
        <pre class="text-white">{{url('/api/decks/new')}}</pre>
        <p>Open a brand new deck of cards. <br> A-spades, 2-spades, 3-spades... followed by diamonds, clubs, then hearts. </p>
        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 52
}</pre>
    </section>

    <hr>

    <section class="mb-5">
        <h2>Draw cards</h2>
        <pre class="text-white">{{url('/api/decks/{deck_id}/draw/2')}}</pre>
        <p>Draw cards from that deck</p>
        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 50
    "cards": [
        0
            "image": "{{ url('/images/cards/6H.png')}}",
            "value": "6",
            "suit": "HEARTS",
            "code": "6H"
        },
        {
            "image": "{{ url('/images/cards/7H.png')}}",
            "value": "8",
            "suit": "CLUBS",
            "code": "8C"
        }
    ]
}
}</pre>
    </section>


    <hr>

    <section class="mb-5">
        <h2>Create a new deck and draw cards</h2>
        <pre class="text-white">{{url('/api/decks/new/draw/2')}}</pre>
        <p>Create a shuffled deck and draw cards from that deck in the same request.</p>
        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 50
    "cards": [
        {
            "image": "{{ url('/images/cards/6H.png')}}",
            "value": "6",
            "suit": "HEARTS",
            "code": "6H"
        },
        {
            "image": "{{ url('/images/cards/7H.png')}}",
            "value": "8",
            "suit": "CLUBS",
            "code": "8C"
        }
    ]
}</pre>
    </section>

    <hr>

    <section class="mb-5">
        <h2>Shuffle the deck</h2>
        <pre class="text-white">{{url('/api/decks/{deck_id}/shuffle')}}</pre>
        <p>Shuffle the deck and get all cards back to the deck.</p>
        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 52
}</pre>
    </section>

    <hr>

    <section class="mb-5">
        <h2>Create a partial deck</h2>
        <pre class="text-white">{{url('/api/decks/partial/AS,AD,AC,AH')}}</pre>
        <p class="mb-0">Create a deck only with the cards you want.</p>
        <small>
            The value, one of A (for an ace), 2, 3, 4, 5, 6, 7, 8, 9, 0 (for a ten), J (jack), Q (queen), or K (king);
            <br> The suit, one of S (Spades), D (Diamonds), C (Clubs), or H (Hearts).
        </small>
        <h2 class="mt-3">Response</h2>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 4
}</pre>
    </section>


</div>

<div class="col-md-12">

    <section class="mb-5">
        <h2>Create a pile</h2>
        <pre class="text-white">{{url('/api/decks/{deck_id}/piles/aces/add/AS,AD,AH,AC')}}</pre>
        <p>Select specific cards to create a pile.</p>
        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 48
    "piles":{
        "aces": [
                  "AS",
                  "AD",
                  "AH",
                  "AC"
                ]
        }
}</pre>
    </section>

    <hr>

    <section class="mb-5">
        <h2>List all the piles from a deck</h2>
        <pre class="text-white">{{url('/api/decks/{deck_id}/piles/list')}}</pre>

        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 44
    "piles": [
               "aces",
               "kings"
            ]
}</pre>
    </section>

    <hr>

    <section class="mb-5">
        <h2>Shown the cards from a pile</h2>
        <pre class="text-white">{{url('/api/decks/{deck_id}/piles/list')}}</pre>
        <p>Display the available cards from a specific pile.</p>
        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 44
    "pile": "aces",
    "cards":[
                {
                    "image": "{{ url('/images/cards/AS.png')}}",
                    "value": "ACE",
                    "suits": "SPADES",
                    "code": "AS"
                },
                {
                    "image": "{{ url('/images/cards/AD.png')}}",
                    "value": "ACE",
                    "suits": "DIAMONDS",
                    "code":"AD"
                },
                {
                    "image": "{{ url('/images/cards/AH.png')}}",
                    "value": "ACE",
                    "suits": "HEARTS",
                    "code": "AH"
                },
                {
                    "image": "{{ url('/images/cards/AC.png')}}",
                    "value": "ACE",
                    "suits": "CLUBS",
                    "code":"AC"
                }
            ]
}</pre>
    </section>

    <hr>

    <section class="mb-5">
        <h2>Draw cards from a pile</h2>
        <pre class="text-white">{{url('/api/decks/{deck_id}/piles/{pile_name}/draw/2')}}</pre>
        <h3>Response</h3>
        <pre class="text-white">{
    "status": "success"
    "deck_id": "3p40paa87x90"
    "shuffled": true
    "remaining": 2,
    "cards": [
                {
                    "image": "{{ url('/images/cards/AS.png')}}",
                    "value": "ACE",
                    "suits": "SPADES",
                    "code": "AS"
                },
                {
                    "image": "{{ url('/images/cards/AH.png')}}",
                    "value": "ACE",
                    "suits": "HEARTS",
                    "code": "AH"
                }
            ]
}</pre>
    </section>

</div>
