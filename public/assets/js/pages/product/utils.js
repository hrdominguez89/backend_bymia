function hideElement(element, time = -1) {
    if (Array.isArray(element))
        element.forEach(e => e.fadeOut(time))
    else
        element.fadeOut(time)
}

function showElement(element, time = -1) {
    if (Array.isArray(element))
        element.forEach(e => e.fadeIn(time))
    else
        element.fadeIn(time)
}

function enabledCmp(element) {
    if (Array.isArray(element))
        element.forEach(e => e.prop("disabled", false))
    else
        element.prop("disabled", false)
}

function disabledCmp(element) {
    if (Array.isArray(element))
        element.forEach(e => e.prop("disabled", true))
    else
        element.prop("disabled", true)
}

