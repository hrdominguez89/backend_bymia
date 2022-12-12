"use strict";

$(document).ready(() => {
  listenSelectCustomerTypeRole();
});

let selectCustomerTypeRole;

const listenSelectCustomerTypeRole = () => {
  selectCustomerTypeRole = $("#customer_customer_type_role");
  if (selectCustomerTypeRole.length) {
    showDivs(parseInt(selectCustomerTypeRole.val()));
    selectCustomerTypeRole.on("change", () => {
      showDivs(parseInt(selectCustomerTypeRole.val()));
    });
  }
};

const showDivs = (value) => {
  const divCustomerForm = $("#divCustomerForm");
  const divsPerson = $(".col-data-person");
  const inputLastName = $("#customer_lastname");
  const nameInput = $('#name_input');

  switch (value) {
    case 1: //Persona
      divCustomerForm.show();
      divsPerson.show();
      inputLastName.attr('required','required')
      nameInput.html('Nombre');
      break;
    case 2: //Empresa
      divCustomerForm.show();
      divsPerson.hide();
      inputLastName.removeAttr('required')
      nameInput.html('Razón social');
      break;
    default: //Ninguno
      divCustomerForm.hide();
      inputLastName.removeAttr('required')
      break;
  }
};
