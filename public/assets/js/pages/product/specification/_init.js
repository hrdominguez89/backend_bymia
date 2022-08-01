// vars < specification_tab.twig >

const section__list_specifications_id = $("#section__list_specifications_id")
const btn__create_specification_id = $("#btn__create_specification_id")
const btn__save_specification_id = $("#btn__save_specification_id")
const btn__exit_specification_id = $("#btn__exit_specification_id")

// vars from < specification_form.twig >

const card__specification_form_id = $("#card__specification_form_id")
const select__especifications_id = $("#select__especifications_id")
const select__type_especificacion_id = $("#select__type_especificacion_id")
const section__list_property = $("#section__list_property")

// vars from < property_form.twig >

const input__property_text_id = $("#input__property_text_id")
const input__value_text_id = $("#input__value_text_id")
const input__value_color_id = $("#input__value_color_id")
const input__value_imagen_id = $("#input__value_imagen_id")
const btn__ok_property_id = $("#btn__ok_property_id")


// vars

const SELECT_TYPE = { value: 'select', text: 'Select', cmp: input__value_text_id }
const BUTTON_TYPE = { value: 'boton', text: 'Botón', cmp: input__value_text_id }
const COLOR_TYPE = { value: 'color', text: 'Color', cmp: input__value_color_id }
const IMAGE_TYPE = { value: 'imagen', text: 'Imagen', cmp: input__value_imagen_id }
const ADD = 'add'
const EDIT = 'edit'

const LIST_SPECIFICATIONS_TYPE = [SELECT_TYPE, BUTTON_TYPE, COLOR_TYPE, IMAGE_TYPE]

let cmp__value_selected = SELECT_TYPE

/**
 * [ 
 *  {   specification, 
 *      specification_name, 
 *      type, 
 *      items: 
 *      [    
 *          { property, value },
 *          { property, value },
 *          { property, value }
 *      ]
 *  } 
 * ]
 */
let list__specifications = []
let list__propertys = []
let current_specification = null
let mode = ADD

/**
 * 
 * init the specification module ....
 * 
 */

hideElement(
    [
        card__specification_form_id,
        input__value_color_id,
        input__value_imagen_id
    ]
)