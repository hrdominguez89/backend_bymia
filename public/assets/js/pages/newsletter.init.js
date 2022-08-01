let descripcion = $('#editor')
$(document).ready(function () {
    //init load
    send(Method.GET, "/public/newsletter", null, function (response) {
        descripcion.val(response.data)
    });
});

//action btn
$('#btn-submit').on('click',function () {
    send(
        Method.POST,
        "/newsletter",
        {name:descripcion.val()},
        function (response) {
            // message ok
            message();
            $('.loader-container').hide()

        },
        function () {
            //hide loader
            toggleLoader();
            $('.loader-container').hide()
        }
    );

})