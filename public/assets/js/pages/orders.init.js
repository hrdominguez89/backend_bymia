let table = null;
let action = null;

$(document).ready(function () {
  // main table----
  table = $("#datatable-orders").DataTable({
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
        data: "order_number",
        render: function (data, type, row, meta) {
          return `<a class="btn btn btn-outline-secondary" onclick="showDetails('${row.order_number}')">${row.order_number}</a>`;
        }
      },
      {
        data: "order_statut",
        render: function (data, type, row, meta) {
          let status_colum = row.order_statut
          if (status_colum == 'Completado')
            return `<a class="btn btn btn-success" style="width: -webkit-fill-available;" onclick="btnChangeStatus('${row.id}','${row.order_statut}')">${row.order_statut}</a>`;
          if (status_colum == 'Cancelado')
            return `<a class="btn btn btn-danger" style="width: -webkit-fill-available;" onclick="btnChangeStatus('${row.id}','${row.order_statut}')">${row.order_statut}</a>`;
          if (status_colum == 'Reembolsado')
            return `<a class="btn btn btn-info" style="width: -webkit-fill-available;" onclick="btnChangeStatus('${row.id}','${row.order_statut}')">${row.order_statut}</a>`;
          if (status_colum == 'Fallido')
            return `<a class="btn btn btn-warning" style="width: -webkit-fill-available;" onclick="btnChangeStatus('${row.id}','${row.order_statut}')">${row.order_statut}</a>`;
          return `<a class="btn btn btn-secondary" style="width: -webkit-fill-available;" onclick="btnChangeStatus('${row.id}','${row.order_statut}')">Procesando</a>`;
        }
      },
      {
        data: "email",
      },
      {
        data: "fecha_pedido",
        render: function (data, type, row, meta) {
          if(row.fecha_pedido != ''){
              arr_fecha = row.fecha_pedido.split(' ')
            let date = arr_fecha[0]
            let time = arr_fecha[1]
            return  `<span>
                        <i class="far fa-calendar-check"></i> ${date}
                        <i class="far fa-clock"></i> ${time}
                     </span>`;
          }
        }
      },
      {
        data: "total",
        render: function (data, type, row, meta) {
          let total = row.total
          return parseFloat(total).toFixed(2)
        },
      }
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
const ordes_status = ['Procesando','Completado','Cancelado','Reembolsado','Fallido'];
function toggleForm() {
  $(".form-orders,.table-orders").toggle("slide");
}

function btnChangeStatus(id,status) {
  $('#id_orden').val(id)
  $('#updated_statut').find('option').remove();
  for (const idElement of ordes_status) {
    if(idElement.toLowerCase() == status.toLowerCase() ){
      $('#updated_statut').append(`<option value="${idElement}" id="${idElement}" selected>${idElement.toUpperCase()}</option>`);
    }
    else{
      $('#updated_statut').append(`<option value="${idElement}" id="${idElement}">${idElement.toUpperCase()}</option>`);
    }
  }
  toggleForm()
}


function changeStatus() {
    let id = $('#id_orden').val()
    let status = $('#updated_statut option:selected').val();
  toggleLoader();

  //send request
  send(
      Method.POST,
      "/order/update",
      {id:id,status:status},
      function (response) {
        // message ok
        message();
      },
      function () {
        //hide loader
        toggleForm();
      }
  );

}

function showDetails(number_order){
  window.open('/api/public/orders/get-one/'+number_order)
}

$("#dateStart,#dateEnd").on("change", getAll);

//button functions
function clean() {
  $("#dateStart").val("");
  $("#dateEnd").val("");
  table.rows().remove().draw(false);
}

//api functions
function getAll() {
  //validate date
  var dateStart = $("#dateStart").val() == ''?'null':$("#dateStart").val();
  var dateEnd = $("#dateEnd").val() == ''?'null':$("#dateEnd").val();
  var customer_id = 'none';
  // if (dateStart == "" || dateEnd == "") return;

  //show table loader
  toogleTableLoader("table-products-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    `/public/orders/get-summary/${dateStart}/${dateEnd}`,
    {
      dateStart: dateStart,
      dateEnd: dateEnd,
      customer_id: customer_id,
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
