"use strict";

let selectTag;
let cboxTagExpires;

$(document).ready(() => {
  initSelect2();
  listenTagSelect();
  listenCboxTagExpires();
  listenInputDate();
});

const listenTagSelect = () => {
  selectTag = $('#product_tag_tag');
  if (selectTag.val()) {
    $('#product_tag_tag_expires').attr('disabled', false);
  }else{
    $('#message_expiration_time').attr('class', 'text-muted');
    $('#message_expiration_time').html('Sin etiqueta.');
  }
  selectTag.on("change", async () => {
    let tag = selectTag.val() ? true : false;
    if (tag) {
      $('#product_tag_tag_expires').attr('disabled', false);
      if (!cboxTagExpires.is(":checked")) {
        $('#message_expiration_time').attr('class', 'text-success');
        $('#message_expiration_time').html('La etiqueta no tiene vencimiento.');
      }
    } else {
      $('#product_tag_tag_expires').attr('disabled', true);
      $('#product_tag_tag_expires').prop('checked', false);
      $('#product_tag_tag_expiration_date').attr('disabled', true);
      $('#product_tag_tag_expiration_date').val('');
      $('#product_tag_tag_expiration_date').attr('required', false);
      $('#message_expiration_time').attr('class', 'text-muted');
      $('#message_expiration_time').html('Sin etiqueta.');
    }
  });
}

const listenInputDate = () => {
  let inputDate = new Date($("#product_tag_tag_expiration_date").val());
  let today = new Date();
  if ($("#product_tag_tag_expiration_date").val()) {
    if (inputDate <= today) {
      $('#message_expiration_time').attr('class', 'text-danger');
      $('#message_expiration_time').html('La etiqueta expiró.');
    } else {
      $('#message_expiration_time').attr('class', 'text-success');
      $('#message_expiration_time').html('La etiqueta se encuentra en fecha.');
    }
  }
  $("#product_tag_tag_expiration_date").change(function () {
    inputDate = new Date($(this).val());
    if (inputDate <= today) {
      $('#message_expiration_time').attr('class', 'text-danger');
      $('#message_expiration_time').html('La etiqueta expiró.');
    } else {
      $('#message_expiration_time').attr('class', 'text-success');
      $('#message_expiration_time').html('La etiqueta se encuentra en fecha.');
    }
  });
}

const listenCboxTagExpires = () => {
  cboxTagExpires = $('#product_tag_tag_expires');
  if(selectTag.val()){
    if (cboxTagExpires.is(":checked")) {
      $('#product_tag_tag_expiration_date').attr('disabled', false);
      $('#product_tag_tag_expiration_date').attr('required', true);
      if (!$('#product_tag_tag_expiration_date').val()) {
        $('#message_expiration_time').attr('class', 'text-warning');
        $('#message_expiration_time').html('Elija la fecha de vencimiento.');
      }
    } else {
      $('#message_expiration_time').attr('class', 'text-success');
      $('#message_expiration_time').html('La etiqueta no tiene vencimiento.');
    }
  }
  cboxTagExpires.change(async () => {
    if (cboxTagExpires.is(":checked")) {
      $('#product_tag_tag_expiration_date').attr('disabled', false);
      $('#product_tag_tag_expiration_date').attr('required', true);
      if (!$('#product_tag_tag_expiration_date').val()) {
        $('#message_expiration_time').attr('class', 'text-warning');
        $('#message_expiration_time').html('Elija la fecha de vencimiento.');
      }
    } else {
      $('#product_tag_tag_expiration_date').attr('disabled', true);
      $('#product_tag_tag_expiration_date').attr('required', false);
      $('#product_tag_tag_expiration_date').val('');
      $('#message_expiration_time').attr('class', 'text-success');
      $('#message_expiration_time').html('La etiqueta no tiene vencimiento.');
    }
  });
}

const initSelect2 = () => {
  $('select').select2({
    theme: 'bootstrap4',
  });
}