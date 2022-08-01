var table = null;
var action = null;
var pristine;

$(document).ready(function () {
  //table
  table = $("#datatable-tags").DataTable({
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
        data: "idApi",
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
});

//toggle form
function toggleForm() {
  $(".form-tag,.table-tag").toggle("slide");
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
  clearForm($(".form-tag"));
  pristine.reset();

  //set input x focus
  $("#name").focus();
}

function btnPut(btn) {
  //set action
  action = put;

  //set titles
  toogleLabelPut();

  //clear form
  clearForm($(".form-tag"));
  pristine.reset();

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
  //show table loader
  toogleTableLoader("table-tags-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    "/public/tags",
    null,
    function (response) {
      //transform response data
      data = [];

      var data = response.data.map((obj) => {
        return obj;
      });

      // load data
      table.rows.add(data).draw(false);
    },
    function () {
      //hide table loader
      toogleTableLoader("table-tags-loader");
    }
  );
}

function post() {
  //get form data
  var formData = $(".form-tag")
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
    "/tags",
    formData,
    function (response) {
      //reload grid
      getAll();

      //message ok
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
  var formData = $(".form-tag")
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
    "/tags/" + formData.id,
    formData,
    function (response) {
      //reload grid
      getAll();

      //message ok
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
    "/tags/" + id,
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
  var form = document.getElementById("form-tag");

  pristine = new Pristine(form);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristine.validate();
    if (valid) action();
  });
};
//end prevent submit
