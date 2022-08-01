var table = null;
var table_subcategoria = null;
var action = null;
var image = null;
var pristineCategoria;
var pristineSubcategoria;

// Prevent Dropzone from auto discovering this element:
Dropzone.options.myAwesomeDropzone = false;
// This is useful when you want to create the
// Dropzone programmatically later

// Disable auto discover for all elements:
Dropzone.autoDiscover = false;

$(document).ready(function () {
  //table
  table = $("#datatable-categorias").DataTable({
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
        data: "image",
        className: "text-center",
        width: "30px",
        render: function (data, type, row, meta) {
          return `
            <img class="image-categoria" src="${row.image}"
            alt="Header Avatar">
            `;
        },
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
                        <i class="icon-put far fa-edit text-warning waves-effect me-2" onclick="btnPut(this)"></i>
                        <i class="icon-remove far fa-trash-alt text-danger waves-effect" onclick="btnRemove(this,'${row.id}')"></i>
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
                  </span>`;
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

  //table_subcategoria
  table_subcategoria = $("#datatable-subcategorias").DataTable({
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
            <i class="icon-put far fa-edit text-warning waves-effect me-2" onclick="btnPutSubcategoria('${row.id}','${row.name}','${row.id_api}')"></i>
            <i class="icon-remove far fa-trash-alt text-danger waves-effect" onclick="btnRemoveSubcategoria(this,'${row.id}')"></i>
          `;
        },
      },
    ],
  });
  //index column table_subcategoria
  table_subcategoria
    .on("order.dt search.dt", function () {
      table_subcategoria
        .column(0, { search: "applied", order: "applied" })
        .nodes()
        .each(function (cell, i) {
          cell.innerHTML = i + 1;
        });
    })
    .draw();

  //init load
  getAll();

  //dropzone upload image
  $("#dZUpload").dropzone({
    url: "hn_SimpeFileUploader.ashx",
    maxFiles: 1,
    uploadMultiple: false,
    autoProcessQueue: false,
    acceptedFiles: "image/*",
    previewTemplate: `
        <div class="dz-preview dz-image-preview">
            <div class="dz-image">
                <img id="image" height="120px" data-dz-thumbnail="" alt="Logo" src=""/>                                      
            </div>              
            <a class="dz-remove" href="javascript:undefined;" data-dz-remove="">
                <i class="mt-2 far fa-trash-alt text-danger waves-effect" onclick="uploadImageShowHide()"></i>
            </a>
        </div>
        `,
    init: function () {
      this.on("addedfile", function (file) {
        toBase64(file);
      });
    },
    maxfilesexceeded: function (files) {
      this.removeAllFiles();
      this.addFile(files);
    },
  });
});

//convert to base64
function toBase64(file) {
  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = function (event) {
    image = event.target.result; //- is dataURL data
    $("#dZUpload").parent().removeClass("has-danger");
    $("#dZUploadError").addClass("d-none");
  };
}

//upload Image Show
function uploadImageShowHide(hide = "dZUploadView", show = "dZUpload") {
  if (show == "dZUpload") image = null;
  $("#" + hide).addClass("d-none");
  $("#" + show).removeClass("d-none");
}

//toggle form
function toggleForm() {
  $("#form_categoria_data,.table-categoria").toggle("slide");
}

//action btn
function btnPost() {
  //set action
  action = post;

  //set titles
  toogleLabelPost("#btn-submit", ".modal-title-categoria");

  //show upload image
  uploadImageShowHide();

  //show form
  toggleForm();

  //clear form
  clearForm($("#form_categoria_data"));
  pristineCategoria.reset();
  $("#dZUploadError").addClass("d-none");

  //set input x focus
  $("#name").focus();
}

function btnPut(btn) {
  //set action
  action = put;

  //set titles
  toogleLabelPut("#btn-submit", ".modal-title-categoria");

  //load form data
  var obj = table.row($(btn).closest("tr")).data();
  $.each(obj, function (key, value) {
    if (key == "image") {
      $("#image").attr("src", value);
    } else $(`[name='${key}']`).val(value);
  });
  pristineCategoria.reset();
  $("#dZUploadError").addClass("d-none");

  //hide upload image
  uploadImageShowHide("dZUpload", "dZUploadView");

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
  send(Method.GET, "/public/categoria", null, function (response) {
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
  var formData = $("#form_categoria_data")
    .serializeArray()
    .reduce(function (obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});

  formData.base64Image = image;
  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/categoria",
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
  var formData = $("#form_categoria_data")
    .serializeArray()
    .reduce(function (obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});

  formData.base64Image = image;

  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/categoria/" + formData.id,
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
    "/categoria-delete/" + id,
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
  var formCategoria = document.getElementById("form_categoria_data");
  pristineCategoria = new Pristine(formCategoria);
  formCategoria.addEventListener("submit", function (e) {
    e.preventDefault();
    var validCategoria = pristineCategoria.validate();
    if ($(".modal-title-categoria").text() == "Adicionar") {
      if (image == null) {
        $("#dZUpload").parent().addClass("has-danger");
        $("#dZUploadError").removeClass("d-none");
      } else {
        $("#dZUpload").parent().removeClass("has-danger");
        $("#dZUploadError").addClass("d-none");
      }
      if (validCategoria && image != null) action();
    } else {
      if (validCategoria) action();
    }
  });
};
//end prevent submit

//------------------------------- SUBCATEGORIAS ---------------------------//
//toggle formGlobal(cabiar la vista de Categoria a Subcategoria)
function toggleGlobal(flag, id = null, name) {
  if (flag) {
    $("#title_subcategoria").text("Categoría: " + name);
    $("#id_categoria").val(id);
    $("#div_categoria,#div_subcategoria").toggle("slide");
    $("#idApi_subcategoria").val("");
    $("#name_subcategoria").val("");
    getAllSubcategoria(id);
  } else {
    $("#id_categoria").val("");
    $("#div_subcategoria,#div_categoria").toggle("slide");
    getAll();
  }
}

//end toogle form

//api functions
function getAllSubcategoria(id_categoria) {
  //send request
  send(
    Method.GET,
    "/public/categoria/" + id_categoria + "/subcategorias",
    null,
    function (response) {
      //transform response data
      var data = response.data.map((obj) => {
        return obj;
      });
      // clear table
      table_subcategoria.rows().remove().draw(false);
      // load data
      table_subcategoria.rows.add(data).draw(false);
    }
  );
}

$("#btn-submit_subcategoria").on("click", function () {
  var formSubcategoria = document.getElementById("form_subcategoria_data");
  pristineSubcategoria = new Pristine(formSubcategoria);
  var validSubcategoria = pristineSubcategoria.validate();
  if (validSubcategoria) {
    let id_categoria = $("#id_categoria").val();
    let name = $("#name_subcategoria").val();
    let id_subcategoria = $("#id_subcategoria").val();
    let idApi_subcategoria = $("#idApi_subcategoria").val();
    send(
      Method.POST,
      "/categoria/" + id_categoria + "/subcategorias",
      {
        name: name,
        id_subcategoria: id_subcategoria,
        id_api: idApi_subcategoria,
      },
      function (response) {
        $("#id_subcategoria").val("");
        // message ok
        message();
        getAllSubcategoria(id_categoria);
        //set titles
        toogleLabelPost("#btn-submit_subcategoria", ".modal-title");
        pristineSubcategoria.reset();
        $("#name_subcategoria").val("");
        $("#idApi_subcategoria").val("");
      },
      function () {
        //hide loader
        $("#id_subcategoria").val("");
        $("#idApi_subcategoria").val("");
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
    "/subcategoria-delete/" + id,
    null,
    function (response) {
      //eliminamos fila
      table_subcategoria.row(tr).remove().draw(false);

      // mensaje ok
      message("Eliminado!", "Your selection has been deleted.!");
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

function btnPutSubcategoria(id, name, idApi) {
  //set titles
  toogleLabelPut("#btn-submit_subcategoria", ".modal-title");
  if(pristineSubcategoria)
    pristineSubcategoria.reset();

  $("#id_subcategoria").val(id);
  $("#name_subcategoria").val(name);
  $("#idApi_subcategoria").val(idApi);
}

$("#btn-cancel_subcategoria").on("click", function () {
  $("#name_subcategoria").val("");
  $("#idApi_subcategoria").val("");

  $("#btn-submit_subcategoria").text("Adicionar");
  $("#btn-submit_subcategoria").removeClass("btn-warning");
  $("#btn-submit_subcategoria").addClass("btn-primary");
});
