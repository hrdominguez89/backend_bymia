let amount = 0;
$('#btn-show-ammount').on('click', function () {
    if ($('#email').val() == '') {
        message('Información!', 'El campo email es obligatorio', MessageType.ERROR)
    } else {
        let cupon = $('#discount_coupon').val()
        if(cupon == '')
            cupon = '-'
        send(
            Method.GET,
            "/paypal/get-total-pay/"+$('#email').val()+'/'+cupon,
            null,
            function (response) {
                // message ok
                message();
                amount = parseFloat(response.data.total)
                $('#details').text('El monto a pagar es de ' + response.data.total + '.')
            },
            function () {
                //hide loader
            }
        );
    }
})


paypal.Button.render({
    env: 'sandbox', // Or 'production'
    // Set up the payment:
    // 1. Add a payment callback
    payment: function (data, actions) {
        // 2. Make a request to your server
        return actions.request.post('/api/public/paypal/pay', {
            email: $('#email').val(),
            discount_coupon: $('#discount_coupon').val()
        })
            .then(function (res) {
                // 3. Return res.id from the response
                return res.id;
            });
    },
    // Execute the payment:
    // 1. Add an onAuthorize callback
    onAuthorize: function (data, actions) {
        // 2. Make a request to your server
        return actions.request.post('/api/public/paypal/status', {
            paymentID: data.paymentID,
            payerID: data.payerID,
            email: $('#email').val(),
            discount_coupon: $('#discount_coupon').val()
        })
            .then(function (res) {
                // 3. Show the buyer a confirmation message.
            });
    }
}, '#paypal-button');


$('#btn-submit').on('click', function () {
    if ($('#email').val() == '') {
        message('Información!', 'El campo email es obligatorio', MessageType.ERROR)
    } else {
        let cupon = $('#discount_coupon').val()
        if (cupon == '')
            cupon = '-'
        send(
            Method.GET,
            "/public/paypal/pay/"+$('#email').val()+'/'+cupon,
            null,
            function (response) {
                // message ok
                message();
                console.info(response)
                $('#details').text('para proseguir el pago, consulte el siguiente link:  '+response.data.link+'.')
            },
            function () {
                //hide loader
            }
        );
    }
})
