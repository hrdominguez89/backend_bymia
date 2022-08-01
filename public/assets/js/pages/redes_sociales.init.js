var table = null;
var action = null;

$(document).ready(function () {
  //table
  table = $("#datatable-redes_sociales").DataTable({
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
        data: "btn",
        className: "text-center",
        width: "50px",
        bSortable: false,
        render: function (data, type, row, meta) {
          console.info(row.name)
          switch (row.name) {
            case "Facebook":
              return `<i class="fab fa-facebook fa-2x text-primary"></i>`;
            case "WhatsApp":
              return `<i class="fab fa-whatsapp fa-2x text-success"></i>`;
            case "Facebook Messenger":
              return `<i class="fab fa-facebook-messenger fa-2x text-primary"></i>`;
            case "Instagram":
              return `<i class="fab fa-instagram fa-2x text-primary"></i>`;
            case "Skype":
              return `<i class="fab fa-skype fa-2x text-info"></i>`;
            case "Telegram":
              return `<i class="fab fa-telegram fa-2x text-primary"></i>`;
            case "LinkedIn":
              return `<i class="fab fa-linkedin fa-2x text-primary"></i>`;
            case "Twitter":
              return `<i class="fab fa-twitter fa-2x text-primary"></i>`;
          }
        },
      },
      {
        data: "name",
      },
      {
        data: "url",
      },
      {
        data: "btn",
        className: "text-center",
        width: "70px",
        bSortable: false,
        render: function (data, type, row, meta) {
          return `
                <i class="icon-put fa fa-edit text-warning waves-effect me-2" onclick="btnPut(this)"></i>
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

  //init load
  getAll();
});

//toggle form
function toggleForm() {
  $(".form-redes-sociales,.table-redes-sociales").toggle("slide");
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
  clearForm($(".form-redes-sociales"));
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
  clearForm($(".form-redes-sociales"));
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
  toogleTableLoader("table-redes-sociales-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    "/public/redes-sociales",
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
      toogleTableLoader("table-redes-sociales-loader");
    }
  );
}

function post() {
  //get form data
  var formData = $(".form-redes-sociales")
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
    "/redes-sociales",
    formData,
    function (response) {
      // message ok
      message();
      getAll();
      //hide form
      toggleForm();
    },
    function () {
      //hide loader
      toggleLoader();

      //add data in table
      table.row.add(formData).draw(false);
    }
  );
}

function put() {
  //get form data
  var formData = $(".form-redes-sociales")
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
    "/redes-sociales/" + formData.id,
    formData,
    function (response) {
      // message ok
      message();
      getAll();
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
    Method.DELETE,
    "/redes-sociales/" + id,
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
  var form = document.getElementById("form-redes-sociales");

  pristine = new Pristine(form);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristine.validate();
    if (valid) action();
  });
};
//end prevent submit
