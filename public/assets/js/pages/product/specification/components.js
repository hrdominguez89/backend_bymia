const createValueComponent = (property, value) => {

    const component = `
        <div class="d-flex align-items-center">
        <div class="width-12rem">${property}</div>
        <div class="width-12rem">${value}</div>
            <span class="mouse-hand mdi mdi-delete mdi-24px" 
            onClick="deleteProperty('${property}','${value}')"></span>
        </div>
    `

    return component;
}


const createColorComponent = (property, value) => {

    const component = `
        <div class="d-flex align-items-center">
        <div class="width-12rem">${property}</div>
        <div class="width-12rem">
            <div class="color-cmp" style="background-color: ${value}"></div>
        </div>
            <span class="mouse-hand mdi mdi-delete  mdi-24px" 
                onClick="deleteProperty('${property}','${value}')">
            </span>
        </div>
    `

    return component;
}



const createImgComponent = async (property, value) => {
    const srcImg = await obtainImage(value);
    const component = `
        <div class="position-relative" style="width:85px; margin-right:.4rem;">
            <img width="85" class="img-thumbnail" src="${srcImg}">
            <span
                onClick="deleteProperty('${property}','${value}')" 
                style="color: red !important;"
                class="mouse-hand position-absolute mdi mdi-close-circle text-white p-top-0 p-right-0 mdi-18px"></span>
            <div>${property}</div>
        </div>
    `

    return component;
}

/**
 * redrow <select> specification
 */
const updateSpeceficationSelectCmp = () => {
    select__especifications_id.children().remove()

    const displays = listBackendSpecifications.filter(
        e => {
            if (mode == ADD)
                return !list__specifications.some(s => s.specification == e.id)
            else {
                if (current_specification.specification == e.id) return true
                else return !list__specifications.some(s => s.specification == e.id)
            }
        }
    )

    for (const item of displays) {
        select__especifications_id.append(
            `<option value="${item.id}">${item.name}</option>`
        )
    }
}


const createCardProperty = (specification, specification_name, type, htmlType, display = 'd-block') => {
    return `
    <div class="card">
        <div class="card-body p-3">

            <div class="d-flex align-items-center title-property-card">
                <div class="width-12rem">${specification_name}</div>
                <div class="width-12rem mr-auto">${type}</div>            
                <span class="mdi mdi-pencil mdi-18px mouse-hand" 
                    onClick="editSpecification('${specification}')" 
                    style="margin-left: auto;">
                </span>
                <span class="mdi mdi-delete mdi-18px mouse-hand" 
                    onClick="deleteSpecification('${specification}')"     
                    style="margin-left: 12px;">
                </span>
            </div>
            <div class="${display}"> 
                ${htmlType}
            </div>
        </div>
    </div>
    `
}

/**
 * create the <html> for the specification selected
 * @param {*} itemSpecification specification in the list
 * @returns <html> card for the specification
 */
const createCardValueProperty = async (itemSpecification) => {

    const {
        specification,
        specification_name,
        type,
        items
    } = itemSpecification
    let htmlType = ''

    for (const item of items) {

        switch (type) {
            case IMAGE_TYPE.value:
                const srcImg = await obtainImage(item.value);
                htmlType +=
                    `
                    <div class="position-relative" style="width:85px; margin-right:.4rem;">
                        <img width="85" class="img-thumbnail" src="${srcImg}">                    
                        <div>${item.property}</div>
                    </div>
                `
                break;
            case COLOR_TYPE.value:
                htmlType +=
                    `
                    <div class="d-flex align-items-center" style="padding: 4px;">
                        <div class="width-12rem">${item.property}</div>
                        <div class="width-12rem">
                            <div class="color-cmp" style="background-color: ${item.value};"></div>
                        </div>            
                    </div>
                `
                break;

            default:
                htmlType +=
                    `
                        <div class="d-flex align-items-center" style="padding: 4px;">
                            <div class="width-12rem">${item.property}</div>
                            <div class="width-12rem">${item.value}</div>            
                        </div>
                    `
                break;
        }

    }

    return type == IMAGE_TYPE.value
        ? createCardProperty(specification, specification_name, type, htmlType, 'd-flex flex-wrap')
        : createCardProperty(specification, specification_name, type, htmlType)
}