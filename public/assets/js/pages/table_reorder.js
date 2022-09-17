$(document).ready(() => {
  loadDatatableReOrder();
  listenToggleOnOff();
});

let tableReOrder;

let rowData;
let rowNumber;
let columnNumber;

let orderData;

const loadDatatableReOrder = () => {
  if ($("#table_reorder").length) {
    $.fn.dataTable.moment("DD/MM/YYYY");
    tableReOrder = $("#table_reorder").DataTable({
      paging: false,
      rowReorder: true,
      columnDefs: [
        { orderable: true, className: "reorder", targets: 0 },
        { orderable: false, targets: 1, visible: false },
        { orderable: false, targets: "_all" },
      ],
      //   scrollY: 400,
      //dom: "lBftip", //l= cant. de registros por pagina | B=botones | f = campo de busqueda | t = tabla | i = informacion de cantidad de registros | p = pagination
      dom: "<'row'<'col-sm-12 mt-5'tr>><'row'<'col-sm-12 col-md-5'i>>",
      language: {
        url: "/assets/libs/datatables.net-language/es-ES.json",
      },
    });

    tableReOrder.on("row-reorder", function (e, diff, edit) {
      orderData = {
        ids: [],
        orders: [],
      };
      for (var i = 0, ien = diff.length; i < ien; i++) {
        var rowData = tableReOrder.row(diff[i].node).data();
        orderData.ids.push(rowData[1]);
        orderData.orders.push(diff[i].newData);
      }
      saveNewOrder();
    });
  }
};

const saveNewOrder = async () => {
  const slug = $("#table_reorder").data("slug");
  await $.ajax({
    url: slug,
    method: "POST",
    data: { orderData: orderData },
    // success: (res) => {
    // },
  });
};

const listenToggleOnOff = async () => {
  if ($(".toggle-on-off").length) {
    tableReOrder.on("click", ".toggle-on-off", async function () {
      const id = this.dataset.id;
      const slug = this.dataset.slug;
      const status = this.dataset.status;

      columnNumber = tableReOrder.column($(this).parents("td")).index();
      rowData = tableReOrder.row($(this).parents("tr")).data();
      rowNumber = tableReOrder.row($(this).parents("tr")).index();

      await $.ajax({
        url: slug,
        method: "POST",
        data: { id: id, visible: status },
        success: (res) => {
          if (res.visible) {
            rowData[
              columnNumber
            ] = `<a style="font-size:16px" data-status="on" data-slug="${slug}" data-id="${id}" class="text-success m-2 toggle-on-off" href="javascript:void(0);">
            <i class="fas fa-toggle-on"></i>
          </a>`;
          } else {
            rowData[
              columnNumber
            ] = `<a style="font-size:16px" data-status="off" data-slug="${slug}" data-id="${id}" class="text-secondary m-2 toggle-on-off" href="javascript:void(0);">
            <i class="fas fa-toggle-off"></i>
          </a>`;
          }
          tableReOrder.row(rowNumber).data(rowData).draw(false);
        },
      });
    });
  }
};
