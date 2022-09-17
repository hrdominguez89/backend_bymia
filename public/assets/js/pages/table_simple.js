$(document).ready(() => {
  loadDatatableFullButtons();
  listenToggleOnOff();
});

let tableFullButtons;

let rowData;
let rowNumber;
let columnNumber;

const loadDatatableFullButtons = () => {
  if ($("#table_simple").length) {
    $.fn.dataTable.moment("DD/MM/YYYY");
    tableFullButtons = $("#table_simple").DataTable({
      stateSave: true, //esto permite guardar en memoria la visualizacion de las columnas
      colReorder: true,
      language: {
        url: "/assets/libs/datatables.net-language/es-ES.json",
      },
    });
  }
};

const listenToggleOnOff = async () => {
  if ($(".toggle-on-off").length) {
    tableFullButtons.on("click", ".toggle-on-off", async function () {
      const id = this.dataset.id;
      const slug = this.dataset.slug;
      const status = this.dataset.status;

      columnNumber = tableFullButtons.column($(this).parents("td")).index();
      rowData = tableFullButtons.row($(this).parents("tr")).data();
      rowNumber = tableFullButtons.row($(this).parents("tr")).index();

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
          tableFullButtons.row(rowNumber).data(rowData).draw(false);
        },
      });
    });
  }
};
