let config_apis_pago = [];
$(document).ready(function () {
    $(document).on('click', '.borrar', function (event) {
        event.preventDefault();
        for (const element_array of config_apis_pago) {
            if (element_array.id == $(this).attr('identificador')) {
                element_array.data = element_array.data.filter(e => e.clave !== $(this).attr('clave'))
            }
        }
        $(this).closest('tr').remove()
    })
    getAll()


    $('#btn-save').on('click',function (){
        let params = JSON.stringify(config_apis_pago)
        send(
            Method.POST,
            "/metodo-pago",
            {data: params},
            function (response) {
                message();
                location.reload()
            },
            function () {

            }
        );
    })
})

//api functions
function post() {
    //get form data
    var formData = $(".form-metodos-pago")
        .serializeArray()
        .reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

    //show loader


    //send request
    send(
        Method.POST,
        "/metodo-pago",
        formData,
        function (response) {
            // message ok
            //  message();
            message();
            location.reload()
        },
        function () {}
    );
}

function getAll() {
    //send request
    send(
        Method.GET,
        "/metodo-pago",
        null,
        function (response) {
            //transform response data
            var array_data = response.data
            for (const argument of array_data) {
                let id = argument.id
                let data = argument.data
                config_apis_pago.push({
                    id, data
                })
                let cls_border = 'border-primary'
                let icons_status = 'btn-outline-danger fa fa-trash'
                let display_column = 'style="display: table-cell"'
                let display_div = 'style="display: flex"'
                if (argument.active == false) {
                    cls_border = 'border-danger'
                    icons_status = 'btn-outline-primary fa fa-bell'
                    display_column = 'style="display: none"'
                    display_div = 'style="display: none"'
                }
                $('#continer').append(`<div class="card border ${cls_border}" id="card-${argument.id}">
                            <div class="card-header bg-transparent border-primary">
                                <h5 class="my-0 text-primary" data-key="t-info-customer"><button type="button" class="btn ${icons_status} mr-2" id="btn-${argument.id}" onclick="changeStatus('${argument.id}',true)"></button> ${argument.name}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row" ${display_div}>
                                    <div class="col-lg-5 col-md-4 col-sm-12">
                                        <input type="text" name="clave" class="form-control w-100 m-1" id="clave-${argument.id}" placeholder="Clave">
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-sm-12">
                                        <input type="text" name="valor" class="form-control w-100 m-1" id="valor-${argument.id}" placeholder="Valor">
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12">
                                        <button type="button" class="btn btn-secondary w-100 m-1" name="adicionar" id="btn-submit-${argument.id}" onclick="addParams('${argument.id}')"> Adicionar</button>
                                    </div>
                                </div>
                                <table class="table table-sm p-2 mt-2">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col-1">Clave</th>
                                                 <th scope="col-1">Valor</th>
                                                 <th ${display_column} width="50px" class="text-center"><i class="fa fa-eraser text-danger text-right"></i></th>
                                            </tr>
                                            </thead>
                                            <tbody id="body-${argument.id}">`)
                for (const datum of argument.data) {
                    let clave = datum.clave
                    let valor = datum.valor
                    $('#body-' + argument.id).append(`<tr>
                                        <td>${datum.clave}</td>    
                                        <td>${datum.valor}</td>    
                                        <td ${display_column} ><button class="btn btn-outline-danger fa fa-eraser borrar" type="button" clave="${datum.clave}" identificador = "${argument.id}"></button></td>    
                                    </tr>`)
                }
                $('#continer').append(`</tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>`)

            }
        },
        function () {
            //hide table loader
            toogleTableLoader("table-metodos-pago-loader");
        }
    );
}

function changeStatus(id, active) {
    //send request
    send(
        Method.PUT,
        "/metodo-pago/desactive/" + id,
        null,
        function (response) {
            // mensaje ok
            message(
                "Actualizado!",
                active == true
                    ? "El método de pago ha sido desactivado.!"
                    : "El método de pago ha sido activado.!"
            );
           location.reload()
        },
        function () {}
    );
}

function addParams(id) {
    let clave = $('#clave-' + id).val()
    let valor = $('#valor-' + id).val()
    if (clave == '' || valor == '')
        message('Error!', 'Existen campos en blanco.', MessageType.ERROR)
    else {
        for (let i = 0; i < config_apis_pago.length; i++) {
            if (config_apis_pago[i].id == id) {
                let clave_repetida = false;
                let data_api = config_apis_pago[i].data;
                for (const element of data_api) {
                    if (element.clave == clave)
                        clave_repetida = true
                }
                if (clave_repetida == false) {
                    $('#body-' + id).append(`<tr>
                                    <td>${clave}</td>    
                                    <td>${valor}</td>    
                                    <td><button class="btn btn-outline-danger fa fa-eraser borrar" type="button" clave="${clave}" identificador = "${id}"></button></td>    
                                </tr>`)
                    config_apis_pago[i].data.push({
                        clave, valor
                    })
                    $('#clave-' + id).val('')
                    $('#valor-' + id).val('')
                } else
                    message('Error!', 'La clave ' + clave + ' ya se encuentra registrada.', MessageType.ERROR)
            }
        }
    }
}