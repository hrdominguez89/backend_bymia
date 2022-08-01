/**
 * Getters y Setters
 */

// get
const getTypeSpecificationSelectd = () => select__type_especificacion_id.val()
const getSpecificationSelectd = () => select__especifications_id.val()
const getSpecificationSelectdText = () => $("#select__especifications_id option:selected").text()
const getPropertySelectd = () => input__property_text_id.val()
const getValueSelectd = () => {
    if (cmp__value_selected.value == IMAGE_TYPE.value) {
        return cmp__value_selected.cmp[0].files[0]
    } else
        return cmp__value_selected.cmp.val()
}

const getOneSpecificationById = (id) => list__specifications.find(e => e.specification == id)

// set
const setSpecificationSelect = (value) => select__especifications_id.val(value)
const setTypeSpecificationSelect = (value) => select__type_especificacion_id.val(value)

/** {Booelan} - current specification is in use */
const isUseCurrentSpecification = () => Boolean(list__propertys.length)



/**
 * Close Card create specification
 */
function closeCardCreateSpecificarion() {
    showElement(btn__create_specification_id)
    hideElement(card__specification_form_id)
    resetAll()
}

/**
 * show selected type and hiden others, 
 * be the getTypeSpecificationSelectd()
 */
function switchType() {
    LIST_SPECIFICATIONS_TYPE.forEach(e => {
        if (e.value != getTypeSpecificationSelectd()) hideElement(e.cmp)
    })

    cmp__value_selected = LIST_SPECIFICATIONS_TYPE.find(e => e.value == getTypeSpecificationSelectd())
    showElement(cmp__value_selected.cmp)
}

/**
 * Enable or Disable Select components is propertys used
 * this is for edit mode
 */
function toggleSelectSpecAndType() {
    if (isUseCurrentSpecification()) disabledCmp([select__especifications_id, select__type_especificacion_id])
    else enabledCmp([select__especifications_id, select__type_especificacion_id])
}

/**
 * reDraw list of property each it is chanded
 * for the type of specification
 */
async function drawListProperty() {

    section__list_property.children().remove()
    section__list_property.removeClass()

    for (const element of list__propertys) {
        switch (getTypeSpecificationSelectd()) {
            case COLOR_TYPE.value:
                section__list_property.append(createColorComponent(element.property, element.value))
                break;
            case IMAGE_TYPE.value:
                section__list_property.addClass('d-flex flex-wrap')
                section__list_property.append(await createImgComponent(element.property, element.value))
                break;
            default:
                section__list_property.append(createValueComponent(element.property, element.value))
                break;
        }
    }
}

async function drawSpecifications() {

    section__list_specifications_id.children().remove()

    for (const element of list__specifications) {
        section__list_specifications_id.append(await createCardValueProperty(element))
    }

    updateSpeceficationSelectCmp()

}


const obtainImage = (file) => {
    return new Promise((resolve, reject) => {

        if (typeof file == 'string') resolve(file)

        var reader = new FileReader();

        reader.readAsDataURL(file);

        reader.onload = function (e) {
            resolve(e.target.result);
        }
    })
}


/**
 * save the specification in the list and redrow the componet
 * @returns {Boolean} if save status
 */
function saveSpecification() {
    if (!isUseCurrentSpecification()) {
        alert('debe crear alguna propiedad!')
        return false;
    }
    if (mode == ADD) {
        list__specifications.push({
            id: null,
            specification: getSpecificationSelectd(),
            specification_name: getSpecificationSelectdText(),
            type: getTypeSpecificationSelectd(),
            items: list__propertys
        })
    }

    if (mode == EDIT) {
        current_specification.specification = getSpecificationSelectd()
        current_specification.specification_name = getSpecificationSelectdText()
        current_specification.type = getTypeSpecificationSelectd()
    }
    drawSpecificationSelectCmp()
    // UI
    mode = ADD
    updateSpeceficationSelectCmp()
    drawSpecifications()
    return true
}


function resetAll() {

    mode = ADD
    list__propertys = []

    // reset forms
    card__specification_form_id.find('input').val('')

    enabledCmp(card__specification_form_id.find('select'))
    enabledCmp(card__specification_form_id.find('input'))
    drawListProperty()

}

function editSpecification(id_specification) {
    current_specification = list__specifications.find(e => e.specification == id_specification)

    // edit 
    mode = EDIT
    list__propertys = current_specification.items
    updateSpeceficationSelectCmp()
    setSpecificationSelect(current_specification.specification)
    setTypeSpecificationSelect(current_specification.type)
    toggleSelectSpecAndType()
    switchType()
    drawListProperty()

    showElement(card__specification_form_id)
    hideElement($(this))
}

function deleteSpecification(id_specification) {
    messageConfirmation(undefined, undefined, function () {
        _deleteEntireSpecification(id_specification);
    });
}


function validateFormProperty() {
    if (cmp__value_selected && cmp__value_selected.cmp.val() && input__property_text_id.val())
        if (!list__propertys.find(e => (e.property == getPropertySelectd())))
            return true
    return false
}

/**
 * Add new property in list
 */
function addNewPropertyToSpecification() {
    list__propertys.push({ property: getPropertySelectd(), value: getValueSelectd() })


    drawListProperty()
    toggleSelectSpecAndType()
    clearInputsProperty()
}


/**
 * Delete the property of list
 * @param {*} propObj 
 */
function deleteProperty(property, value) {
    messageConfirmation(undefined, undefined, function () {
        _delete(property, value);
    });
}

function _delete(property, value) {
    for (let i = 0; i < list__variatns_specification.length; i++) {
        let specification_filter = list__variatns_specification[i].specifications.find(e => e.property == property)
        if (specification_filter) {
            let slug = convertToSlug(property)
            $("#" + list__variatns_specification[i].id + '-' + slug).remove();
            list__variatns_specification[i].specifications.splice(
                list__variatns_specification[i].specifications.findIndex(e => e.property == property),
                1
            )
        }
    }
    list__propertys.splice(
        list__propertys.findIndex(e => e.property == property && e.value == value),
        1
    )

    drawListProperty()
    toggleSelectSpecAndType()
    // message()
}

function _deleteEntireSpecification(id) {

    for (const variant of list__variatns_specification) {
        let toDelete = variant.specifications.findIndex(e => e.specification == id)

        while (toDelete != -1) {
            const property = variant.specifications.find(e => e.specification == id).property
            
            //elimino el span
            let slug = convertToSlug(property)
            $("#" + variant.id + '-' + slug).remove();
            
            variant.specifications.splice(toDelete, 1)
            toDelete = variant.specifications.findIndex(e => e.specification == id)
        }
    }

    const index = list__specifications.findIndex(e => e.specification == id)
    list__specifications.splice(index, 1)

    drawListProperty()
    drawSpecifications()
    message()
}

function clearInputsProperty() {
    card__specification_form_id.find('input').val('')
}

function cargarEspecificaciones() {


    drawListProperty()
    drawSpecifications()
}