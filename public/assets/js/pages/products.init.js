let table_category = $('#datatable-category')

function removeMainImage(id) {
  $('#main-image-' + id).find('div').remove()
}

function drawDateTimeFieldParentProduct(offerPrice, fecha_inicio_oferta, fecha_fin_oferta) {
  if (offerPrice != '' || offerPrice > 0) {
    if (fecha_inicio_oferta) {
      $('#start_ofert_day').prop('disabled', false)
      $('#start_ofert_day').val(moment(fecha_inicio_oferta).format('YYYY-MM-DDTHH:MM'))
    } else {
      $('#start_ofert_day').prop('disabled', true)
      $('#start_ofert_day').val()
    }
    if (fecha_fin_oferta) {
      $('#end_ofert_day').prop('disabled', false)
      $('#end_ofert_day').val(moment(fecha_fin_oferta).format('YYYY-MM-DDTHH:MM'))
    } else {
      $('#end_ofert_day').prop('disabled', true)
      $('#end_ofert_day').val()
    }
  } else {
    $('#start_ofert_day').prop('disabled', true)
    $('#start_ofert_day').val()
    $('#end_ofert_day').prop('disabled', true)
    $('#end_ofert_day').val()
  }
}

function loadCategory() {
  let id_last_subcategory_dd = "";
  getSubcategorysSelected();
  table_category.find("ul").remove();
  send(
    Method.GET,
    "/secure/product/get-all-categorys",
    null,
    function (response) {
      //transform response data
      var data = response.data.map((obj) => {
        return obj;
      });
      if (data.length == 0)
        table_category.prepend(
          `
            <div 
                class="text-center text-white" 
                style="background-color: darkred; border-radius: 5px!important;">
                No Data
            </div> 
          `
        );
      else {

        let menu =
          `
            <ul id="myUL">
                <li>
                    <input type="checkbox" 
                        name="sin_categoria" 
                        id="sin_categoria" 
                        onchange="onCheck('-1','null')"> 
                        Sin Categoría
                </li>
                <li>
                    <span class="box">Categorías</span>
            <ul class="nested active">
          `;
        for (let element of data) {
          let str = `
          <li>
              <span class="box"  id="${element.id}">${element.name}</span>
              <ul class="nested active" id="${element.id}">`;

          if (element.children.length > 0) {
            let children = element.children;
            children.forEach(function (item) {
              str =
                str +
                `
                <li>
                    <input type="checkbox" 
                        class="form-check-input" checked 
                        name="${item.id}" id="categoris-li-${item.id}" 
                        onchange="onCheck('${item.id}','${element.id}')"> 
                        ${item.name}
                </li>
                `;
            });
            menu = menu + str + ` </ul></li>`;
          }
        }
        menu = menu + ` </ul></li></ul>`;
        table_category.prepend(menu);
        let toggler = $(".box");
        var i;

        for (i = 0; i < toggler.length; i++) {
          toggler[i].addEventListener("click", function () {
            console.log(this.parentElement.querySelector(".nested"))
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("check-box");
          });
        }
        cargarSubcategorias();
      }
    }
  );
}

//----FUNCTIONS LAUNCH WHITD EVENT CHANGE OF FORMS FIELDS----
$("#ofert_price").on("keyup", function () {
  if ($("#ofert_price").val() != "") {
    $("#start_ofert_day").prop("disabled", false);
    $("#end_ofert_day").prop("disabled", false);
  } else {
    $("#start_ofert_day").prop("disabled", true);
    $("#end_ofert_day").prop("disabled", true);
  }
});

//funcion tree
function selectAll(id) {
  if (id != "") $("ul[id=" + id + "] li input").prop("checked", true);
}

function deselectAll(id = "") {
  if (id != "") $("ul[id=" + id + "] li input").prop("checked", false);
  else $("li input ").prop("checked", false);
}

function onCheck(id_li, id_ul) {
  if (id_li != "-1") {
    if ($("#categoris-li-" + id_li).is(":checked")) {
      $("#sin_categoria").prop("checked", false);
      array_element.push(id_li);
    } else {
      var i = array_element.indexOf(id_li)
      array_element.splice(i, 1);
      if (array_element.length == 0) $("#sin_categoria").prop("checked", true);
    }
  } else {
    // deselectAll();
    array_element = [];
    $("#sin_categoria").prop("checked", true);
  }
}

function cargarSubcategorias() {
  deselectAll();
  array_parents = [];
  if (array_element.length == 0) $(".nested").removeClass("active");
  else $(".nested").addClass("active");
  for (let elemt of array_element) {
    let check = $("#categoris-li-" + elemt);
    let id_padre = check.parents(".nested").attr("id");
    if (!array_parents.includes(id_padre)) {
      array_parents[array_parents.length] = id_padre;
      // $('#'+id_padre).pr("active")
    }
    check.prop("checked", true);
  }
}

function getSubcategorysSelected() {
  $("li input:checkbox:checked").each(function () {
    array_element.push($(this).attr("id"));
  });
}

loadCategory()
//end of function tree

$("#tipo_producto").on("change", function () {
  let select_option = $("#tipo_producto").val();
  if (select_option == 1) {
    $("#v-products-variaciones-tab").css("display", "none");
    $("#uso_variaciones").css("display", "none");
    $("#v-products-general-tab").css("display", "block");
    $("#v-products-general").addClass("active show");
    $("#v-products-inventario").removeClass("active show");
    $("#v-products-atributos").removeClass("active show");
    $("#v-products-variaciones").removeClass("active show");
    $("#v-products-general-tab").addClass("active");
    $("#v-products-inventario-tab").removeClass("active");
    $("#v-products-atributos-tab").removeClass("active");
    $("#v-products-variaciones-tab").removeClass("active");
  } else {
    $("#v-products-variaciones-tab").css("display", "block");
    $("#uso_variaciones").css("display", "block");
    $("#v-products-general-tab").css("display", "none");
    $("#v-products-general").removeClass("active show");
    $("#v-products-inventario").addClass("active show");
    $("#v-products-atributos").removeClass("active show");
    $("#v-products-variaciones").removeClass("active show");
    $("#v-products-general-tab").removeClass("active");
    $("#v-products-inventario-tab").addClass("active");
    $("#v-products-atributos-tab").removeClass("active");
    $("#v-products-variaciones-tab").removeClass("active");
  }
});

function formatValue(value_) {
  let array_values = value_.split(",");
  let array_return = [];
  array_values.forEach(function (element) {
    if (element != "")
      if (array_return.indexOf(element) == -1) {
        array_return[array_return.length] = element;
      }
  });
  return array_return;
}

function deleteElement(id_item, tag = false) {
  $("#tag-" + id_item).remove();
  if (tag) {
    for (let i = 0; i < array_tag.length; i++) {
      if (array_tag[i] == id_item) array_tag.splice(i, 1);
    }
  }
}

function addTag() {
  let id_tag = $("#tags").val();
  let name_tag = $("#tags option:selected").text();
  if (!array_tag.includes(id_tag)) {
    array_tag.push(id_tag);
    $("#container_tag").append(`
        <span 
            style="font-size: 13px;" 
            class="badge rounded-pill bg-primary px-2 py-1 mb-1 mr-1" 
            id="tag-${id_tag}">  
                ${name_tag}
                <span 
                    style="font-size: 14px; cursor: pointer;" 
                    class="mdi mdi-close-circle" 
                    onclick="deleteElement('${id_tag}',true)">
                </span>
            </span>`);
  }
}

function cartesian() {
  var r = [],
    arg = arguments,
    max = arg.length - 1;

  function helper(arr, i) {
    for (var j = 0, l = arg[i].length; j < l; j++) {
      var a = arr.slice(0);
      a.push(arg[i][j]);
      if (i == max) r.push(a);
      else helper(a, i + 1);
    }
  }
  helper([], 0);
  return r;
}

function convertToSlug(text) {
  return text
    .toLowerCase()
    .replace(/ /g, '-')
    .replace(/[^\w-]+/g, '')
    ;
}