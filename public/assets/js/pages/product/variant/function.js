/**
 * Geeters
 */

const getSelectVariantById = (id) => list__variatns_specification.find(e => e.id == id)

/******************  Functions ************************/
function addSpecificationToVariant(id) {

    let specification = $('#select-specifications-' + id).val()
    let specification_name = $('#select-specifications-' + id + ' option:selected').text()
    let property = $('#select-atributo-' + id + ' option:selected').text()

    const slect_variant = list__variatns_specification.find(e => e.id == id)

    const { type, items/* , property */ } =
        list__specifications.find(e => e.specification == specification)

    const { value } = items.find(e => e.property == property)
    const specificationObj = {
        specification,
        specification_name,
        type,
        property,
        value
    }


    if (slect_variant) {
        //la validacion solo es aqui porque el otro caso es un elemento completamente nuevo
        let repeat = slect_variant.specifications.find(e => (e.specification == specification && e.property == property))
        if (!repeat)
            slect_variant.specifications.push(specificationObj)
        else {
            message("Información", "La especificación ya se encuentra registrada.", MessageType.ERROR)
            return null;
        }
    } else
        list__variatns_specification.push({
            id: id,
            specifications: [specificationObj]
        })
    return specificationObj
}



/******************  Draw ************************/
function drawNewVariantSpecifications(id_varian, specification) {
    if (specification) {
        let slug = convertToSlug(specification.property)
        $("#product-specifications-" + id_varian).append(
            `<span style="font-size: 13px;" 
            class="badge rounded-pill bg-primary px-2 py-1 mb-1 mr-1" id="${id_varian}-${slug}">
                ${specification.specification_name}|${specification.property}
            <span style="font-size: 14px; cursor: pointer;" 
                class="mdi mdi-close-circle" 
                onclick="deleteSpecificationVariant('${id_varian}-${slug}','${id_varian}','${specification.property}')">
            </span>
        </span>`
        );
    }
}

function deleteSpecificationVariant(id_item, id_variation, specification_property) {
    $("#" + id_item).remove();
    let element_list = list__variatns_specification.find(e => e.id == id_variation)

    element_list.specifications.splice(
        element_list.specifications.findIndex(e => e.property == specification_property),
        1
    )
}

function drawSpecificationSelectCmp() {
    $('.slected-specifications').find('option').remove()
    $('.atribute-specifications').find('option').remove()
    $('.slected-specifications').append(`
        <option value="0" selected disabled>-Especificación-</option>
    `)
    list__specifications.forEach((value) => {
        let id_specification = value.specification
        let name_specification = value.specification_name
        $('.slected-specifications').append(`
            <option value="${id_specification}">${name_specification}</option> 
        `)
    })
}

function drawDateTimeFieldVariantProduct() {
    list__variatns.forEach((value) => {
        let id = value.id
        let offerPrice = value.offerPrice
        let fecha_inicio_oferta = value.offerStartDate
        let fecha_fin_oferta = value.offerEndDate
        if (offerPrice != '' || offerPrice > 0) {
            alert(1)
            if (fecha_inicio_oferta) {
                $('#offerStartDate-' + id).prop('disabled', false)
                $('#offerStartDate-' + id).val(moment(fecha_inicio_oferta).format('YYYY-MM-DDTHH:MM'))
            } else {
                $('#offerStartDate-' + id).prop('disabled', true)
                $('#offerStartDate-' + id).val()
            }
            if (fecha_fin_oferta) {
                $('#offerEndDate-' + id).prop('disabled', false)
                $('#offerEndDate-' + id).val(moment(fecha_fin_oferta).format('YYYY-MM-DDTHH:MM'))
            } else {
                $('#offerEndDate-' + id).prop('disabled', true)
                $('#offerEndDate-' + id).val()
            }
        } else {
            $('#offerStartDate-' + id).prop('disabled', true)
            $('#offerStartDate-' + id).val()
            $('#offerEndDate-' + id).prop('disabled', true)
            $('#offerEndDate-' + id).val()
        }
    })
}

function changeSpecification(id) {
    let select_changed = $('#select-specifications-' + id)
    let atributo_select = $('#select-atributo-' + id)
    atributo_select.find('option').remove()
    let value = select_changed.val()
    for (let i = 0; i < list__specifications.length; i++) {
        if (list__specifications[i]['specification'] == value) {
            let items = list__specifications[i]['items']
            for (let j = 0; j < items.length; j++) {
                let property = items[j]['property']
                atributo_select.append(`<option value="${property}">${property}</option>`)
            }
            break
        }
    }
}

function drawAllVariantSpecifications() {
    for (const item of list__variatns_specification) {
        for (const specification of item.specifications) {            
            drawNewVariantSpecifications(item.id, specification)
        }
    }
}

function removeImages(id) {
    $('#div-images-variants-' + id).find('div').remove()
}

/**
 * @param variatns_specification {}
 */
function loadVariantForSpecifications(variatns_specification) {

    for (const item of variatns_specification) {

        let productoSpecification = list__variatns_specification.find(e => e.id == item.product_id)

        if (!list__variatns_specification.length || !productoSpecification)
            list__variatns_specification.push({
                id: item.product_id,
                specifications:
                    [
                        {
                            specification: item.specification_id,
                            specification_name: getOneSpecificationById(item.specification_id).specification_name,
                            property: item.value,
                            value: item.custom_field_value
                        }
                    ]
            })
        else {
            if (productoSpecification)
                productoSpecification.specifications.push({
                    specification: item.specification_id,
                    specification_name: getOneSpecificationById(item.specification_id).specification_name,
                    property: item.value,
                    value: item.custom_field_value
                })
        }
    }

    // list__variatns_specification.push({
    //     id: id,
    //     specifications: [specificationObj]
    // })
}

// 'id' => $item -> getId(),
//     'product_id' => $item -> getProductId(),
//         'specification_id' => $item -> getSpecificationId(),
//             'value' => $item -> getValue(),
//                 'custom_field_type' => $item -> getCustomFieldsType(),
//                     'custom_field_value' => $item -> getCustomFieldsValue(),
//                         'create_variation'