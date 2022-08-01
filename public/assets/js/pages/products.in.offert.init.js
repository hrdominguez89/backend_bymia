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
            <img class="image-products" src="${row.image}"
            alt="Header Avatar">
            `;
        },
      },
      {
        data: "name",
      },
      {
        data: "sku",
      },
      {
        data: "price",
      },
      {
        data: "date",
        render: function (data, type, row, meta) {
          if (row.date != "") {
            let arr_fecha = row.date.split("+");
            if (arr_fecha.length == 1) arr_fecha = row.date.split("-");
            let second_split = arr_fecha[0].split("T");
            let date = second_split[0];
            let time = second_split[1];
            return `<span>
                                    <i class="far fa-calendar-check"></i> ${date}
                                    <i class="far fa-clock"></i> ${time}
                                 </span>`;
          }
        },
      },
      {
        data: "sales",
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

//api functions
function getAll() {
  //validate date

  //show table loader
  toogleTableLoader("table-products-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    `/public/products/in/offert`,
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
      toogleTableLoader("table-products-loader");
    }
  );
}
