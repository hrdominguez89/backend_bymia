var table = null;
var action = null;
var imageLg = null;
var imageSm = null;

// Prevent Dropzone from auto discovering this element:
Dropzone.options.myAwesomeDropzone = false;
// This is useful when you want to create the
// Dropzone programmatically later

// Disable auto discover for all elements:
Dropzone.autoDiscover = false;

$(document).ready(function () {
  //table
  table = $("#datatable-cover-images").DataTable({
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
        data: "imageLg",
        className: "text-center",
        width: "30px",
        render: function (data, type, row, meta) {
          return `
            <img class="image-cover-images" src="${row.imageLg}"
            alt="Header Avatar">
            `;
        },
      },
      {
        data: "imageSm",
        className: "text-center",
        width: "30px",
        render: function (data, type, row, meta) {
          return `
            <img class="image-cover-images" src="${row.imageSm}"
            alt="Header Avatar">
            `;
        },
      },
      {
        data: "main",
        className: "text-center",
        width: "20px",
        render: function (data, type, row, meta) {
          return row.main
            ? '<i class="fas fa-check-circle text-success"></i>'
            : "";
        },
      },
      {
        data: "title",
      },
      {
        data: "description",
        render:function (data, type, row, meta) {
          if(row.description == '')
            return '';
          let cadena = row.description.substr(0,30)
          // return `<button type="button" class="btn btn-secondary" data-container="body"
          //           data-toggle="popover" data-placement="bottom" data-content="Vivamus
          //         sagittis lacus vel augue laoreet rutrum faucibus.">
          //           Popover on bottom
          //         </button>`;
          return `<button type="button" class="btn text-dark"
                        data-container="body" data-toggle="popover"
                        data-trigger="focus"
                        title="Descripción"
                        data-placement="bottom" data-content="${row.description}">
                    <i>${cadena}... </i>
                </button>`;
        }
      },
      {
        data: "btn",
        className: "text-center",
        width: "50px",
        bSortable: false,
        render: function (data, type, row, meta) {
          var put = is_granted("ROLE_ADMIN")
            ? `<i class="icon-put fa fa-edit text-warning waves-effect me-2" onclick="btnPut(this)"></i>`
            : "";

          var remove = is_granted("ROLE_ADMIN")
            ? `<i class="icon-remove fa fa-trash-alt text-danger waves-effect" onclick="btnRemove(this,'${row.id}')"></i>`
            : "";

          return put + remove;
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

  //init load
  getAll();

  //dropzone upload image Lg
  $("#dZUploadLg").dropzone({
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
            <i class="mt-2 far fa-trash-alt text-danger waves-effect" onclick="uploadImageShowHideLg()"></i>
          </a>
      </div>
    `,
    init: function () {
      this.on("addedfile", function (file) {
        toBase64Lg(file);
      });
      this.on("thumbnail", function (file) {
        if (file.height > 480 && file.width > 1110) {
          message(
            "Información.!",
            "Las dimenciones máximas permitidas en la imagen son de 1110x480.",
            MessageType.INFO
          );
          this.removeFile(file);
        }
      });
    },
    maxfilesexceeded: function (files) {
      this.removeAllFiles();
      this.addFile(files);
    },
  });

  //dropzone upload image Lg
  $("#dZUploadSm").dropzone({
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
            <i class="mt-2 far fa-trash-alt text-danger waves-effect" onclick="uploadImageShowHideSm()"></i>
          </a>
      </div>
    `,
    init: function () {
      this.on("addedfile", function (file) {
        toBase64Sm(file, imageSm);
      });
      this.on("thumbnail", function (file) {
        if (file.height > 395 && file.width > 510) {
          message(
            "Información.!",
            "Las dimenciones máximas permitidas en la imagen son de 510x395.",
            MessageType.INFO
          );
          this.removeFile(file);
        }
      });
    },
    maxfilesexceeded: function (files) {
      this.removeAllFiles();
      this.addFile(files);
    },
  });
});

//convert to base64 Lg
function toBase64Lg(file) {
  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = function (event) {
    imageLg = event.target.result; //- is dataURL data
    $("#dZUploadLg").parent().removeClass("has-danger");
    $("#dZUploadLgError").addClass("d-none");
  };
}
//convert to base64 Sm
function toBase64Sm(file) {
  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = function (event) {
    imageSm = event.target.result; //- is dataURL data
    $("#dZUploadSm").parent().removeClass("has-danger");
    $("#dZUploadSmError").addClass("d-none");
  };
}

//upload Image Show Lg
function uploadImageShowHideLg(hide = "dZUploadViewLg", show = "dZUploadLg") {
  if (show == "dZUploadLg") imageLg = null;
  $("#" + hide).addClass("d-none");
  $("#" + show).removeClass("d-none");
}
//upload Image Show Sm
function uploadImageShowHideSm(hide = "dZUploadViewSm", show = "dZUploadSm") {
  if (show == "dZUploadSm") imageSm = null;
  $("#" + hide).addClass("d-none");
  $("#" + show).removeClass("d-none");
}

//toggle form
function toggleForm() {
  $(".form-cover-image,.table-cover-image").toggle("slide");
}
//end toogle form

//action btn
function btnPost() {
  //set action
  action = post;

  //set titles
  toogleLabelPost();

  //show upload image
  uploadImageShowHideLg();
  uploadImageShowHideSm();

  //show form
  toggleForm();

  //clear form
  clearForm($(".form-cover-image"));
  pristine.reset();
  $("#dZUploadLgError").addClass("d-none");
  $("#dZUploadSmError").addClass("d-none");
  imageLg = null;
  imageSm = null;

  //set input x focus
  $("#name").focus();
}

function btnPut(btn) {
  //set action
  action = put;

  //set titles
  toogleLabelPut();

  //clear form
  clearForm($(".form-cover-image"));
  pristine.reset();
  $("#dZUploadLgError").addClass("d-none");
  $("#dZUploadSmError").addClass("d-none");
  imageLg = null;
  imageSm = null;

  //load form data
  var obj = table.row($(btn).closest("tr")).data();
  $.each(obj, function (key, value) {
    if (key == "imageLg") {
      $("#imageLg").attr("src", value);
    } else if (key == "imageSm") {
      $("#imageSm").attr("src", value);
    } else if (key == "main") {
      $("#main").prop("checked", value);
    } else $(`[name='${key}']`).val(value);
  });
  // pristine.reset();
  // $("#dZUploadLgError").addClass("d-none");
  // $("#dZUploadSmError").addClass("d-none");

  //hide upload image
  uploadImageShowHideLg("dZUploadLg", "dZUploadViewLg");
  uploadImageShowHideSm("dZUploadSm", "dZUploadViewSm");

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
  //show table loader
  toogleTableLoader("table-cover-images-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    "/public/cover_images",
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
      toogleTableLoader("table-cover-images-loader");
    }
  );
}

function post() {
  //get form data
  var formData = $(".form-cover-image")
    .serializeArray()
    .reduce(function (obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});

  formData.base64ImageLg = imageLg;
  formData.base64ImageSm = imageSm;

  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/cover_images",
    formData,
    function (response) {
      //reload grid
      getAll();

      // message ok
      message();

      //hide form
      toggleForm();
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

function put() {
  //get form data
  var formData = $(".form-cover-image")
    .serializeArray()
    .reduce(function (obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});

  formData.base64ImageLg = imageLg;
  formData.base64ImageSm = imageSm;

  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/cover_images/" + formData.id,
    formData,
    function (response) {
      //reload grid
      getAll();

      // message ok
      message();

      //hide form
      toggleForm();
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

function remove(tr, id) {
  //show loader
  toggleLoader();

  //send request
  send(
    Method.DELETE,
    "/cover_images/" + id,
    null,
    function (response) {
      //eliminamos fila
      table.row(tr).remove().draw(false);

      // mensaje ok
      message("Eliminado!", "Your file has been deleted.!");
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
  var form = document.getElementById("form-cover-image");

  pristine = new Pristine(form);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristine.validate();
    if ($(".modal-title").text() == "Adicionar") {
      if (imageLg == null) {
        $("#dZUploadLg").parent().addClass("has-danger");
        $("#dZUploadLgError").removeClass("d-none");
      } else {
        $("#dZUploadLg").parent().removeClass("has-danger");
        $("#dZUploadLgError").addClass("d-none");
      }
      if (imageSm == null) {
        $("#dZUploadSm").parent().addClass("has-danger");
        $("#dZUploadSmError").removeClass("d-none");
      } else {
        $("#dZUploadSm").parent().removeClass("has-danger");
        $("#dZUploadSmError").addClass("d-none");
      }
      if (valid && imageLg != null && imageSm != null) action();
    } else {
      if (valid) action();
    }
  });
};
//end prevent submit
