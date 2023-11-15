<head>
    <!-- 他のmetaタグがあればここに追加 -->

    <!-- Stripe Elementsのviewport meta要件を満たす設定 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<div>
    @if (session('flash_alert'))
        <div class="alert alert-danger">{{ session('flash_alert') }}</div>
    @elseif(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
</div>
<div class="container">
    <div class="p-5">
        <div class="col-6 card">
            <div class="card-header">Stripe決済</div>
            <div class="card-body">
                <form id="card-form" action="http://localhost/payment/store3" method="POST">
                    <input type="hidden" name="_token" value="xhPMHpiujorpnEGM5hWPHyxK6hnAJxAWpxHWoG9k">

                    <!-- カード番号 -->
                    <div class="form-group">
                        <label for="card_number">カード番号</label>
                        <div id="card-number" class="form-control"></div>
                    </div>

                    <!-- 有効期限 -->
                    <div class="form-group">
                        <label for="card_expiry">有効期限</label>
                        <div id="card-expiry" class="form-control"></div>
                    </div>

                    <!-- セキュリティコード -->
                    <div class="form-group">
                        <label for="card-cvc">セキュリティコード</label>
                        <div id="card-cvc" class="form-control"></div>
                    </div>

                    <div id="card-errors" class="text-danger"></div>

                    <button class="mt-3 btn btn-primary" type="submit">支払い</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe_public_key = "{{ config('services.stripe.stripe_public_key') }}";
    const stripe = Stripe(stripe_public_key);
    const elements = stripe.elements();

    var style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
        }
    };

    var cardNumber = elements.create('cardNumber', {
        style: style
    });
    cardNumber.mount('#card-number');

    var cardExpiry = elements.create('cardExpiry', {
        style: style
    });
    cardExpiry.mount('#card-expiry');

    var cardCvc = elements.create('cardCvc', {
        style: style
    });
    cardCvc.mount('#card-cvc');

    var form = document.getElementById('card-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = ''; // Reset error message

        stripe.createToken(cardNumber).then(function(result) {
            if (result.error) {
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('card-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        form.submit();
    }
</script>
