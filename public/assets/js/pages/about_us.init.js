$(document).ready(function () {
    //init load
    send(Method.GET, "/public/about-us", null, function (response) {
        myEditor.setData(response.data)
    });
});

//action btn
$('#btn-submit').on('click',function () {
    let description =myEditor.getData()
    send(
        Method.POST,
        "/about-us",
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