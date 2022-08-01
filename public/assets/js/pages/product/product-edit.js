function asociarMainImagen(id) {
    if ($('#mainImage-' + id).val() != '') {
        let data = new FormData($('#form-main-image-' + id)[0])
        $.ajax({
            url: "/secure/_product/saveMainImage/" + id,
            type: 'POST',
            data,
            contentType: false,
            processData: false,
            success: (res) => {
                let image = res['src']
                removeMainImage(id)
                $('#main-image-' + id).append(`
                        <div class="text-center mt-2" style="border-radius: 5px; background-color: gainsboro">
								<span style="font-size: 14px; cursor: pointer;" class="mdi mdi-close-circle text-danger"
									  onclick="deleteMainImagen('${id}')">Eliminar</span>
								<img style=" height: 60px;width: 60px;border: 2px solid darkgray;" alt="Imagen"
									 src='${image}'>
							</div>`)
            }
        });
    } else {
        message("Información", "Debe seleccionar la imagen", MessageType.ERROR)
    }
}



function deleteMainImagen(id_product) {
    $.ajax({
        url: "/secure/_product/deleteMainImage/" + id_product,
        type: 'POST',
        contentType: false,
        processData: false,
        success: (res) => {
            let src = res['src']
            removeMainImage(id_product)
            if (src != '') {
                $('#main-image-' + id_product).append(`
                        <div class="text-center mt-2" style="border-radius: 5px; background-color: gainsboro">
								<span style="font-size: 14px; cursor: pointer;" class="mdi mdi-close-circle text-danger"
									  onclick="deleteMainImagen('${id_product}')">Eliminar</span>
								<img style=" height: 60px;width: 60px;border: 2px solid darkgray;" alt="Imagen"
									 src='${src}'>
                        </div>`)
            }
        }
    });
}

function submitProducto() {

    let formData = generateFormDataSpecifications(list__specifications)

    formData.append('id_product', $("#id_producto").val())
    formData.append('array_tag', JSON.stringify(array_tag))
    formData.append('array_element', JSON.stringify(array_element))
    formData.append('brand_select', $("#brand_select").val())
    formData.append('large_description', ckeditors.large_description.getData())
    formData.append('short_description', ckeditors.short_description.getData())
    formData.append('price', $("#price").val())
    formData.append('ofert_price', $("#ofert_price").val())
    formData.append('start_ofert_day', $("#start_ofert_day").val())
    formData.append('end_ofert_day', $("#end_ofert_day").val())

    const lists = generateVariatiosToSubmit()
    formData.append('list_variations', lists)

    loadingCmponent.show()
    $.ajax({
        url: "/secure/_product/save-product-edit",
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: (res) => {
            message()
            let url = '/secure/_product/index'
            window.open(url,'_self')
        },
        error: function (error) {
            loadingCmponent.close()
        },
    });
}

function generateFormDataSpecifications(list_specification) {

    let listToStringfy = []

    fomrData = new FormData()

    for (const specification of list_specification) {
        if (specification.type == 'imagen') {

            let items = []
            for (const item of specification.items) {

                const namte_property = specification.specification + '-' + item.property

                if (!(typeof item.value == 'string'))
                    fomrData.append(namte_property, item.value, namte_property + '.png')
                items.push({
                    property: item.property,
                    value: typeof item.value == 'string' ? item.value : namte_property
                })
            }

            listToStringfy.push(
                {
                    specification: specification.specification,
                    type: specification.type,
                    items
                }
            )
        }
        else
            listToStringfy.push(
                {
                    specification: specification.specification,
                    type: specification.type,
                    items: [...specification.items]
                }
            )

    }
    fomrData.append('specifications', JSON.stringify(listToStringfy))
    return fomrData
}

const generateVariatiosToSubmit = () => {
    // shortDescription-{{ product.id }}
    // largeDescription-{{ product.id }}"

    // offerPrice-{{ product.id }}
    // id="offerStartDate-{{ product.id }}"
    // offerEndDate-{{ product.id }}
    // dimensions-{{ product.id }}
    // weight-{{ product.id }}

    let listVariations = []

    for (const product of listBackendVariations.filter(e => e.son)) {

        const specification = getSelectVariantById(product.id)

        listVariations.push({
            id_producto: product.id,
            short_description: ckeditors[`shortDescription-${product.id}`].getData(),
            large_description: ckeditors[`largeDescription-${product.id}`].getData(),
            offer_price: $(`#offerPrice-${product.id}`).val(),
            offer_start_date: $(`#offerStartDate-${product.id}`).val(),
            offer_end_date: $(`#offerEndDate-${product.id}`).val(),
            dimensions: $(`#dimensions-${product.id}`).val(),
            weight: $(`#weight-${product.id}`).val(),
            specification: specification ? specification.specifications : []
        })
    }

    return JSON.stringify(listVariations)
}
