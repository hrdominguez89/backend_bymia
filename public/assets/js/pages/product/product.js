"use strict";

let selectCategory;
let productId;
let categoryId;
let subcategoryId;

let subcategories;

let sku = '';
let categoryNomenclature = '';
let brandNomenclature = '';
let model = '';
let color = '';
let vp1 = '';
let vp2 = '';
let vp3 = '';


$(document).ready(() => {
  initInputs();
  initSku();
  listenSelectCategories();
  listenBrand();
  listenModel();
  listenColor();
  listenVp1();
  listenVp2();
  listenVp3();
});

const initInputs = () => {
  subcategoryId = $('#label-subcategory').data('subcategory-id')
  categoryId = $('#label-category').data('category-id')
  getSubcategories();
  productId = $('#label-sku').data('product-id') ? parseInt($('#label-sku').data("product-id")) : false;


  if (vp1.length == 4) {
    $('#product_vp2').prop('disabled', false);
  }
  if (vp2.length == 4) {
    $('#product_vp3').prop('disabled', false);
  }
}

const initSku = async () => {
  categoryNomenclature = $("#product_category option:selected").text().split(" - ")[1] ? $("#product_category option:selected").text().split(" - ")[1] : '';
  brandNomenclature = $("#product_brand option:selected").text().split(" - ")[1] ? '-' + $("#product_brand option:selected").text().split(" - ")[1] : '';
  model = $('#product_model').val() ? '-' + $('#product_model').val().substring(0, 6) : '';
  color = $('#product_color').val() ? '-' + $('#product_color').val() : '';
  vp1 = $('#product_vp1').val() ? '-' + $('#product_vp1').val() : '';
  vp2 = $('#product_vp2').val() ? '-' + $('#product_vp2').val() : '';
  vp3 = $('#product_vp3').val() ? '-' + $('#product_vp3').val() : '';
  updateSku();
}

const updateSku = () => {
  sku = (categoryNomenclature + brandNomenclature + model + color + vp1 + vp2 + vp3).toUpperCase();
  if (categoryNomenclature && brandNomenclature && model.length == 7 && color.length == 3 && vp1.length == 4) {
    consultFreeSku();
  } else {
    changeBorderColor('warning');
  }
  $('#product_sku').val(sku);
}

const changeBorderColor = (status, message = false) => {
  let color;
  switch (status) {
    case 'warning':
      color = '#ffc107';
      $('#message_sku').html('Complete todos los campos para verificar la disponibilidad del SKU.');
      break;
    case 'success':
      color = '#198754';
      $('#message_sku').html('El SKU se encuentra disponible.');
      break;
    case 'danger':
      color = '#dc3545';
      $('#message_sku').html(message);
      break
  }
  $('#product_sku').css('border-color', color)
}

const listenSelectCategories = () => {
  selectCategory = $('#product_category');
  selectCategory.on("change", () => {
    categoryId = parseInt(selectCategory.val());
    categoryNomenclature = $("#product_category option:selected").text().split(" - ")[1];
    if (!categoryNomenclature) {
      categoryNomenclature = '';
      cleanSelects(true);
    } else {
      getSubcategories();
    }
    updateSku();
  });
};

const listenBrand = () => {
  $('#product_brand').on("change", () => {
    brandNomenclature = $("#product_brand option:selected").text().split(" - ")[1] ? '-' + $("#product_brand option:selected").text().split(" - ")[1] : '';
    updateSku();
  });

}
const listenModel = () => {
  $("#product_model").keyup(function () {
    model = '-' + $(this).val().substring(0, 6);
    if (model == '-') {
      model = '';
    }
    updateSku();
  });
}
const listenColor = () => {
  $("#product_color").keyup(function () {
    color = '-' + $(this).val();
    if (color == '-') {
      color = '';
    }
    updateSku();
  });
}
const listenVp1 = () => {
  $("#product_vp1").keyup(function () {
    vp1 = '-' + $(this).val();
    if (vp1 == '-' || vp1.length <= 3) {
      vp1 = '';

      vp2 = '';
      $("#product_vp2").prop("disabled", true);
      $("#product_vp2").val('');

      vp3 = '';
      $("#product_vp3").prop("disabled", true);
      $("#product_vp3").val('');

    } else {
      $("#product_vp2").prop("disabled", false);
    }

    updateSku();
  });
}
const listenVp2 = () => {
  $("#product_vp2").keyup(function () {
    vp2 = '-' + $(this).val();
    if (vp2 == '-' || vp2.length <= 3) {
      vp2 = '';

      vp3 = '';
      $("#product_vp3").prop("disabled", true);
      $("#product_vp3").val('');

    } else {
      $("#product_vp3").prop("disabled", false);
    }

    updateSku();
  });
}
const listenVp3 = () => {
  $("#product_vp3").keyup(function () {
    vp3 = '-' + $(this).val();
    if (vp3 == '-') {
      vp3 = '';
    }
    updateSku();
  });
}

const getSubcategories = () => {
  $.ajax({
    url: `/secure/subcategory/getSubcategories/${categoryId}`,
    method: "GET",
    success: async (res) => {
      if (res.status) {
        subcategories = await res.data;
        cleanSelects();
        $("#product_subcategory").prop("disabled", false);
        for (let i = 0; i < subcategories.length; i++) {
          const element = subcategories[i];
          const option = $("<option></option>").text(element.name);
          option.attr("value", element.id);
          if (subcategoryId && subcategoryId == element.id) {
            option.attr("selected", "selected");
          }
          $("#product_subcategory").append(option);
        }

      } else {
        cleanSelects(true);
      }
    },
  });
};

const consultFreeSku = () => {
  let query_string = '';
  if (productId) {
    query_string = '?product_id=' + productId
  }
  $.ajax({
    url: `/secure/product/consultFreeSku/${sku}${query_string}`,
    method: "GET",
    success: async (res) => {
      if (res.status) {
        changeBorderColor('success');
        $("#product_subcategory").prop("disabled", false);
      } else {
        changeBorderColor('danger', res.message);
      }
    },
  });
}


const cleanSelects = (disable = false) => {
  const defaultOptionSelect = $("<option></option>").text(
    "Seleccione una subcategoría"
  );
  $("#product_subcategory").html(defaultOptionSelect);
  if (disable) {
    $("#product_subcategory").prop("disabled", true);
  }
};