let table = null;
let action = null;

$(document).ready(function () {
  // main table----
  table = $("#datatable-products").DataTable({
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
            <img class="image-products" src="/storage/default/${row.image}"
            alt="Header Avatar">
            `;
        },
      },
      {
        data: "name",
      },
      {
        data: "price",
      },
      {
        data: "stock",
      },
      {
        data: "sales",
      },
      {
        data: "amount",
      },
      {
        data: "btn",
        className: "text-center",
        width: "50px",
        bSortable: false,
        render: function (data, type, row, meta) {
          if (row.destacado == true || row.destacado == "true")
            return is_granted("ROLE_ADMIN")
              ? `<i style="cursor: pointer;" class="fas fa-star text-warning"></i>`
              : "";
          else
            return is_granted("ROLE_ADMIN")
              ? `<i style="cursor: pointer;" class="far fa-star"></i>`
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
});

$("#dateStart,#dateEnd").on("change", getAll);

//button functions
function clean() {
  $("#dateStart").val("");
  $("#dateEnd").val("");
}

//api functions
function getAll() {
  //validate date
  var dateStart = $("#dateStart").val();
  var dateEnd = $("#dateEnd").val();
  console.log(dateStart, dateEnd);
  if (dateStart == "" || dateEnd == "") return;

  //show table loader
  toogleTableLoader("table-products-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    `/public/products/best/seller/by/date/${dateStart}/${dateEnd}`,
    {
      dateStart: dateStart,
      dateEnd: dateEnd,
    },
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
      toogleTableLoader("table-products-loader");
    }
  );
}
