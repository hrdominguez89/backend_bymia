var table = null;
var action = null;

$(document).ready(function () {
  //table
  table = $("#datatable-cupon_descuento").DataTable({
    columns: [
      {
        data: null,
        className: "text-center",
        width: "20px",
        searchable: false,
        orderable: false,
        bSortable: false,
      },
      {
        visible: false,
        data: "id",
      },
      {
        data: "nro",
      },
      // {
      //     data: "porciento",
      // },
      {
        data: "btn",
        className: "text-right",
        width: "150px",
        bSortable: false,
        render: function (data, type, row, meta) {
          let valor = parseFloat(row.valor).toFixed(2);
          if (row.porciento == "true" || row.porciento == true)
            return `<div class="text-primary text-right" style="border-radius: 5px">${valor}%</div> `;
          else
            return `<div class="text-danger text-right" style="border-radius: 5px">${valor}</div> `;
        },
      },
      {
        data: "cantidad_usos",
      },
      {
        data: "btn",
        className: "text-center",
        width: "50px",
        bSortable: false,
        render: function (data, type, row, meta) {
          return `
            <i class="icon-remove fa fa-trash-alt text-danger waves-effect" onclick="btnRemove(this,'${row.id}')"></i>
          `;
        },
      },
    ],
  });

  //index column table
  table
    .on("order.dt search.dt", function () {
      table
        .column(0, { search: "applied", order: "applied" })
        .nodes()
        .each(function (cell, i) {
          cell.innerHTML = i + 1;
        });
    })
    .draw();

  $("#lote").on("change", function () {
    if ($("#lote").is(":checked")) {
      $("#cantidad_lote").prop("disabled", false);
      $("#cantidad_lote").attr("required", "required");
      $("#cantidad_lote").attr(
        "data-pristine-required-message",
        "Campo requerido!."
      );

      $("#nro").val("");
      $("#nro").prop("disabled", true);
      $("#nro").removeAttr("required");
      $("#nro").removeAttr("data-pristine-required-message");
    } else {
      $("#cantidad_lote").val(1);
      $("#cantidad_lote").prop("disabled", true);
      $("#cantidad_lote").removeAttr("required");
      $("#cantidad_lote").removeAttr("data-pristine-required-message");

      $("#nro").prop("disabled", false);
      $("#nro").attr("required", "required");
      $("#nro").attr("data-pristine-required-message", "Campo requerido!.");
    }

    //reload validation
    pristine.destroy();
    var form = document.getElementById("form-cupon-descuento");
    pristine = new Pristine(form);
  });

  //init load
  getAll();
});

//toggle form
function toggleForm() {
  $(".form-cupon-descuento,.table-cupon-descuento").toggle("slide");
}

//end toogle form

//action btn
function btnPost() {
  //set action
  action = post;

  //set titles
  toogleLabelPost();

  //show form
  toggleForm();

  //clear form
  clearForm($(".form-cupon-descuento"));
  pristine.reset();
}

function btnRemove(btn, id) {
  //confirmation dialog
  messageConfirmation(undefined, undefined, function () {
    remove($(btn).closest("tr"), id);
  });
}

//end action btn

//api functions
function getAll() {
  //show table loader
  toogleTableLoader("table-cupon-descuento-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    "/public/cupon-descuento",
    null,
    function (response) {
      //transform response data
      data = [];

      var data = response.data.map((obj) => {
        return obj;
      });

      // clear table
      table.rows().remove().draw(false);

      // load data
      table.rows.add(data).draw(false);
    },
    function () {
      //hide table loader
      toogleTableLoader("table-cupon-descuento-loader");
    }
  );
}

function post() {
  //get form data
  var formData = $("#form-cupon-descuento")
    .serializeArray()
    .reduce(function (obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});
  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/cupon-descuento",
    formData,
    function (response) {
      message();
      toggleForm();
    },
    function () {
      toggleLoader();
      getAll();
    }
  );
}

function remove(tr, id) {
  toggleLoader();
  send(
    Method.DELETE,
    "/cupon-descuento/" + id,
    null,
    function (response) {
      getAll();
      message("Eliminado!", "Your selection has been deleted.!");
    },
    function () {
      toggleLoader();
    }
  );
}

//end api functions

//prevent form submit
window.onload = function () {
  var form = document.getElementById("form-cupon-descuento");
  pristine = new Pristine(form);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristine.validate();
    if (valid) action();
  });
};
//end prevent submit
