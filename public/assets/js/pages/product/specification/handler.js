$(document).ready(function () {


    /**
     * Switch card to create spicifications
     */
    btn__create_specification_id.on('click', function (event) {
        showElement(card__specification_form_id)
        hideElement($(this))
    })

    /** Exit card specification */
    btn__exit_specification_id.on('click', closeCardCreateSpecificarion)


    btn__save_specification_id.on('click', function () {

        // guardar new specification
        if (!saveSpecification()) return
        // exit
        // closeCardCreateSpecificarion()
        resetAll()
    })

    /**
     * switch type specification
     */
    select__type_especificacion_id.on('change', switchType)


    btn__ok_property_id.on('click', function () {
        // validate form

        if (!validateFormProperty()) {
            message('Información','Existen errores en el formulario, o la especificación ya se encuentra registrada.',MessageType.ERROR)
            return
        }

        // add new property
        addNewPropertyToSpecification()

    })
});