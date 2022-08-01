function asociarImagen(id) {
    if($('#productImage-'+id).val() != ''){
        let data = new FormData($('#form-image-' + id)[0])
        $.ajax({
            url: "/secure/_product/saveImage/" + id,
            type: 'POST',
            data,
            contentType: false,
            processData: false,
            success: (res) => {
                let images = res['images']
                removeImages(id)
                images.forEach((valor) => {
                    let id_image = valor.id
                    let images_db = valor.images
                    let id_product_db = valor.id_product
                    $('#div-images-variants-' + id).append(`
                        <div class="col-lg-2 col-md-3 col-sm-4 m-2 text-center"
                             style="border-radius: 5px; background-color: gainsboro">
                            <span style="font-size: 14px; cursor: pointer;"
                                  class="mdi mdi-close-circle text-danger"
                                  onclick="deleteImagenVariations('${id_image}','${id_product_db}')">Eliminar</span>
                            <img style=" height: 60px;width: 60px;border: 2px solid darkgray;"
                                 alt="Imagen"
                                 src='${images_db}'/>
                        </div>`)
                })
            }
        });
    }
    else{
        message("Información","Debe seleccionar la imagen",MessageType.ERROR)
    }
}

function deleteImagenVariations(id,id_product) {
    $.ajax({
        url: "/secure/_product/deleteImage/" + id,
        type: 'POST',
        contentType: false,
        processData: false,
        success: (res) => {
            let images = res['images']
            removeImages(id_product)
            images.forEach((valor) => {
                let id_image = valor.id
                let images_db = valor.images
                $('#div-images-variants-' + id_product).append(`
                        <div class="col-lg-2 col-md-3 col-sm-4 m-2 text-center"
                             style="border-radius: 5px; background-color: gainsboro">
                            <span style="font-size: 14px; cursor: pointer;"
                                  class="mdi mdi-close-circle text-danger"
                                  onclick="deleteImagenVariations('${id_image}','${id_product}')">Eliminar</span>
                            <img style=" height: 60px;width: 60px;border: 2px solid darkgray;"
                                 alt="Imagen"
                                 src='${images_db}'/>
                        </div>`)
            })
        }
    });
}

function returnEdition(id, childrens) {
    let url = '/secure/_product/index'
    if(childrens)
     url = '/secure/_product/deleteAllNewImage/' + id

    window.open(url,'_self')
}