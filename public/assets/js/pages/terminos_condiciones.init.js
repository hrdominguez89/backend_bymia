$(document).ready(function () {
    //init load
    send(Method.GET, "/public/terms-conditions", null, function (response) {
        myEditor.setData(response.data)
    });
});

//action btn
$('#btn-submit').on('click',function () {
    let description =myEditor.getData()
    send(
        Method.POST,
        "/terms-conditions",
        {descripcion:description},
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