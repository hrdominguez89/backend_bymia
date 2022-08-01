var table = null;
var table_especificacion = null;
var action = null;
var pristineTipoEspecificacion;
var pristineEspecificacion;

$(document).ready(function () {
  //table
  table = $("#datatable-tipo_especificacion").DataTable({
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
        data: "name",
      },
      {
        data: "btn",
        className: "text-center",
        width: "50px",
        bSortable: false,
        render: function (data, type, row, meta) {
          return `
            <i class="icon-put fa fa-edit text-warning waves-effect me-2" onclick="btnPut(this)"></i>
            <i class="icon-remove fa fa-trash-alt text-danger waves-effect" onclick="btnRemove(this,'${row.id}')"></i>
          `;
        },
      },
      {
        data: "btn",
        className: "text-center",
        width: "25px",
        bSortable: false,
        render: function (data, type, row, meta) {
          return `<span style="border-radius: 5px" class="btn btn-success p-2" onclick="toggleGlobal(true,'${row.id}','${row.name}')">
                        <i class="pl-2 mt-2 fa fa-cogs text-white"></i>
                        Gestionar
                  </span>
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

  //table_especificacion
  table_especificacion = $("#datatable-especificacion").DataTable({
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
        data: "id_api",
      },
      {
        data: "name",
      },
      {
        data: "btn",
        className: "text-center",
        width: "50px",
        bSortable: false,
        render: function (data, type, row, meta) {
          return `
            <i class="icon-put fa fa-edit text-warning waves-effect me-2" onclick="btnPutEspecificacion('${row.id}','${row.name}','${row.filter}','${row.id_api}')"></i>
            <i class="icon-remove fa fa-trash-alt text-danger waves-effect" onclick="btnRemoveSubcategoria(this,'${row.id}')"></i>
          `;
        },
      },
    ],
  });
  //index column table_subcategoria
  table_especificacion
    .on("order.dt search.dt", function () {
      table_especificacion
        .column(0, { search: "applied", order: "applied" })
        .nodes()
        .each(function (cell, i) {
          cell.innerHTML = i + 1;
        });
    })
    .draw();

  //init load
  getAll();
});

//toggle form
function toggleForm() {
  $("#form_tipo_especificacion_data,.table-tipo_especificacion").toggle(
    "slide"
  );
}

//action btn
function btnPost() {
  //set action
  action = post;

  //set titles
  toogleLabelPost();

  //show form
  toggleForm();

  //clear form
  clearForm($("#form_tipo_especificacion_data"));
  pristineTipoEspecificacion.reset();

  //set input x focus
  $("#name").focus();
}

function btnPut(btn) {
  //set action
  action = put;

  //set titles
  toogleLabelPut();

  //clear form
  clearForm($("#form_tipo_especificacion_data"));
  pristineTipoEspecificacion.reset();

  //load form data
  var obj = table.row($(btn).closest("tr")).data();
  $.each(obj, function (key, value) {
    $(`[name='${key}']`).val(value);
  });

  //show form
  toggleForm();

  //set input x focus
  $("#name").focus();
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
  //send request
  send(Method.GET, "/public/tipo-especificacion", null, function (response) {
    //transform response data
    data = [];

    var data = response.data.map((obj) => {
      return obj;
    });

    // clear table
    table.rows().remove().draw(false);

    // load data
    table.rows.add(data).draw(false);
  });
}

function post() {
  //get form data
  var formData = $("#form_tipo_especificacion_data")
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
    "/tipo-especificacion",
    formData,
    function (response) {
      // message ok
      message();

      //hide form
      toggleForm();
    },
    function () {
      //hide loader
      toggleLoader();

      getAll();
    }
  );
}

function put() {
  //get form data
  var formData = $("#form_tipo_especificacion_data")
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
    "/tipo-especificacion/" + formData.id,
    formData,
    function (response) {
      // message ok
      message();

      //hide form
      toggleForm();
    },
    function () {
      //hide loader
      toggleLoader();

      //reload grid
      getAll();
    }
  );
}

function remove(tr, id) {
  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/tipo-especificacion-delete/" + id,
    null,
    function (response) {
      //eliminamos fila
      table.row(tr).remove().draw(false);

      // mensaje ok
      message("Eliminado!", "Your selection has been deleted.!");
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

//end api functions

//prevent form submit
window.onload = function () {
  var formTipoEspecificacion = document.getElementById(
    "form_tipo_especificacion_data"
  );

  pristineTipoEspecificacion = new Pristine(formTipoEspecificacion);

  formTipoEspecificacion.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristineTipoEspecificacion.validate();
    if (valid) action();
  });
};
//end prevent submit

//------------------------------- SUBCATEGORIAS ---------------------------//
//toggle formGlobal(cabiar la vista de Categoria a Subcategoria)
function toggleGlobal(flag, id = null, name) {
  if (flag) {
    $("#title_especificacion").text("Tipo de Especificación: " + name);
    $("#id_tipo_especificacion").val(id);
    $("#div_tipo_especificacion,#div_especificacion").toggle("slide");
    getAllEspecificaciones(id);
  } else {
    $("#id_tipo_especificacion").val("");
    $("#div_especificacion,#div_tipo_especificacion").toggle("slide");
    getAll();
  }
}

//end toogle form

//api functions
function getAllEspecificaciones(id_tipo_especificacion) {
  //send request
  send(
    Method.GET,
    "/public/tipo-especificacion/" + id_tipo_especificacion + "/especificacion",
    null,
    function (response) {
      //transform response data
      var data = response.data.map((obj) => {
        return obj;
      });
      // clear table
      table_especificacion.rows().remove().draw(false);
      // load data
      table_especificacion.rows.add(data).draw(false);
    }
  );
}

$("#btn-submit_especificacion").on("click", function () {
  var formEspecificacion = document.getElementById("form_especificacion_data");
  pristineEspecificacion = new Pristine(formEspecificacion);
  var validEspecificacion = pristineEspecificacion.validate();
  if (validEspecificacion) {
    let id_tipo_especificacion = $("#id_tipo_especificacion").val();
    let name = $("#name_especificacion").val();
    let idApi = $("#idApi_especificacion").val();
    let id_especificacion = $("#id_especificacion").val();
    let filter =true
    send(
      Method.POST,
      "/tipo-especificacion/" + id_tipo_especificacion + "/especificacion",
      { name: name, id_especificacion: id_especificacion, filter: filter,idApi:idApi },
      function (response) {
        $("#id_especificacion").val("");
        $("#idApi_especificacion").val("");
        // message ok
        message();
        getAllEspecificaciones(id_tipo_especificacion);
        $("#name_especificacion").val("");
        $("#btn-submit_especificacion").text("Adicionar");
        $("#btn-submit_especificacion").removeClass("btn-warning");
        $("#btn-submit_especificacion").addClass("btn-primary");
      },
      function () {
        //hide loader
        $("#id_especificacion").val("");
        message();
      }
    );
  }
});

function btnRemoveSubcategoria(btn, id) {
  //confirmation dialog
  messageConfirmation(undefined, undefined, function () {
    removeSubcategoriaAction($(btn).closest("tr"), id);
  });
}

function removeSubcategoriaAction(tr, id) {
  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/especificacion-delete/" + id,
    null,
    function (response) {
      //eliminamos fila
      table_especificacion.row(tr).remove().draw(false);

      // mensaje ok
      message("Eliminado!", "Your selection has been deleted.!");
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

function btnPutEspecificacion(id, name, filter,idApi) {
  // pristineEspecificacion.reset();

  $("#id_especificacion").val(id);
  $("#name_especificacion").val(name);
  $("#idApi_especificacion").val(idApi);
  $("#filter_especificacion").prop("checked", false);
  if (filter == true || filter == "true")
    $("#filter_especificacion").prop("checked", true);
  $("#btn-submit_especificacion").text("Modificar");
  $("#btn-submit_especificacion").removeClass("btn-primary");
  $("#btn-submit_especificacion").addClass("btn-warning");
}

$("#btn-cancel_especificacion").on("click", function () {
  $("#name_especificacion").val("");
  $("#filter_especificacion").prop("checked", false);
  $("#btn-submit_especificacion").text("Adicionar");
  $("#btn-submit_especificacion").removeClass("btn-warning");
  $("#btn-submit_especificacion").addClass("btn-primary");
});
