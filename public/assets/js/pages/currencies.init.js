var table = null;
var action = null;
var pristine;

$(document).ready(function () {
  //table
  table = $("#datatable-currencies").DataTable({
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
        data: "idApi",
      },
      {
        data: "abbreviation",
        className: "text-center",
        width: "20px",
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
  $(".form-currency,.table-currency").toggle("slide");
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
  clearForm($(".form-currency"));
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
  clearForm($(".form-currency"));
  pristine.reset();

  //load form data
  var obj = table.row($(btn).closest("tr")).data();
  $.each(obj, function (key, value) {
    if (key == "main") {
      $("#main").prop("checked", value != "");
    } else $(`[name='${key}']`).val(value);
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
  toogleTableLoader("table-currencies-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    "/public/currencies",
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
      toogleTableLoader("table-currencies-loader");
    }
  );
}

function post() {
  //get form data
  var formData = $(".form-currency")
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
    "/currencies",
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
  var formData = $(".form-currency")
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
    "/currencies/" + formData.id,
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
    "/currencies/" + id,
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
  var form = document.getElementById("form-currency");

  pristine = new Pristine(form);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristine.validate();
    if (valid) action();
  });
};
//end prevent submit
