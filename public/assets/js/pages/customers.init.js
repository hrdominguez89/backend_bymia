var table = null;
var action = null;
var image = null;
var pristine;

// Prevent Dropzone from auto discovering this element:
Dropzone.options.myAwesomeDropzone = false;
// This is useful when you want to create the
// Dropzone programmatically later

// Disable auto discover for all elements:
Dropzone.autoDiscover = false;

$(document).ready(function () {
  //table
  table = $("#datatable-customers").DataTable({
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
            <img class="image-customers" src="${row.image}"
            alt="Header Avatar">
            `;
        },
      },
      {
        data: "email",
        width: "100px",
      },
      {
        data: "name",
        render: function (data, type, row, meta) {
          return row.name+' '+row.last_name
        },
      },
      {
        data: "btn",
        className: "text-center",
        width: "50px",
        bSortable: false,
        render: function (data, type, row, meta) {
          return  is_granted("ROLE_ADMIN")
            ? `<i class="icon-put fa fa-edit text-warning waves-effect me-2" onclick="btnPut(this)"></i>`
            : "";
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
  $(".form-customer,.table-customer").toggle("slide");
}
//end toogle form

function btnLoad() {
  load();
}
//
// function btnTest() {
//   //send request
//   let params = {
//     action: 'Delete',
//     customer: {
//       id: '123',
//       name: 'Camilo',
//       password: '123Password',
//       type: 'Personal',
//       identification: '89102815009',
//       last_name: 'Hernandez Valdes',
//       email: 'kahveahd@gmail.com',
//       country: 'EEUU',
//       province: 'Barcelona',
//       municipality: 'Municipio Barcelones',
//       direction: 'Calle A',
//       postal_code: '2020',
//       home_phone: '48769307',
//       cell_phone: '55816826',
//       retire_office: 'Office 54'
//     }
//   };
//   send(
//       Method.POST,
//       "/customers/manager",
//       params,
//       function (response) {
//         //reload grid
//         getAll();
//         message();
//       },
//       function () {
//       }
//   );
// }

function btnPut(btn) {
  //set action
  action = put;

  //set titles
  toogleLabelPut();

  //clear form
  clearForm($(".form-customer"));
  pristine.reset();
  $("#dZUploadError").addClass("d-none");
  image = null;

  //load form data
  var obj = table.row($(btn).closest("tr")).data();
  $.each(obj, function (key, value) {
    if (key == "image") {
      $("#imageView").attr("src", value);
    } else
      $(`[name='${key}']`).val(value);
  });
  /*clear card information*/
  $('#t-phone').html()
  $('#t-name').text()
  $('#t-username').text()
  $('#t-type').text()
  $('#t-email').text()
  $('#t-identification').text()
  $('#t-retire-office').text()
  $('#t-country').text()
  $('#t-province').text()
  $('#t-municipality').text()
  $('#t-direction').text()
  $('#t-postal-code').text()

  /*set new card information*/
  $('#t-name').text(obj.name+' '+obj.last_name)
  $('#t-username').text(obj.username)
  $('#t-type').text(obj.type)
  $('#t-email').text(obj.email)
  $('#t-identification').text(obj.identification)
  if(obj.home_phone !== null && obj.cell_phone !== null)
  $('#t-phone').html(`<i class="fa fa-home"></i>` + obj.home_phone+' - '+`<i class="fa fa-phone"></i>`+ obj.cell_phone)
  if(obj.home_phone !== null && obj.cell_phone === null)
    $('#t-phone').html(`<i class="fa fa-home"></i>` + obj.home_phone)
  if(obj.home_phone === null && obj.cell_phone !== null)
    $('#t-phone').html(`<i class="fa fa-phone"></i>`+ obj.cell_phone)
  $('#t-retire-office').text(obj.retire_office)
  $('#t-country').text(obj.country)
  $('#t-province').text(obj.province)
  $('#t-municipality').text(obj.municipality)
  $('#t-direction').text(obj.direction)
  $('#t-postal-code').text(obj.postal_code)

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
  //show table loader
  toogleTableLoader("table-customers-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    "/public/customers",
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
      if(data.length>0){
        $('#btn-load').prop('hidden',true)
      }
      else{
        $('#btn-load').prop('hidden',false)
      }
    },
    function () {
      //hide table loader
      toogleTableLoader("table-customers-loader");
    }
  );
}

function load() {
  //show loader
  toggleLoader();

  //send request
  send(
    Method.GET,
    "/customers/load",
      null,
      function (response) {
        //reload grid
        getAll();

        // mensaje ok
        message();
      },
      function () {
        //hide loader
        toggleLoader();
      }
  );
}



function put() {
  //get form data
  var formData = $(".form-customer")
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
    "/customers/" + formData.id,
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
    "/customers/" + id,
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
  var form = document.getElementById("form-customer");

  pristine = new Pristine(form);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristine.validate();
    if (valid) action();
  });
};
//end prevent submit
