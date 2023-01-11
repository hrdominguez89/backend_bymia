$(document).ready(() => {
  loadDatatableFullButtons();
  listenToggleOnOff();
});

let tableFullButtons;

let rowData;
let rowNumber;
let columnNumber;

const loadDatatableFullButtons = () => {
  if ($("#table_full_buttons").length) {
    $.fn.dataTable.moment("DD/MM/YYYY");
    tableFullButtons = $("#table_full_buttons").DataTable({
      stateSave: true, //esto permite guardar en memoria la visualizacion de las columnas
      //dom: "lBftip", //l= cant. de registros por pagina | B=botones | f = campo de busqueda | t = tabla | i = informacion de cantidad de registros | p = pagination
      dom: "<'row'<'col-3'l><'col-6 text-center'B><'col-3'f>><'row'<'col-sm-12 mt-5'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      //   order: [[2, 'asc'],[3,'asc']],
      buttons: [
        "colvis",
        {
          extend: "copy",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          extend: "pdf",
          exportOptions: {
            columns: ":visible",
          },
          orientation: "landscape",
          pageSize: "A4",
          download: "open",
        },
        {
          extend: "excel",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          extend: "csv",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          extend: "print",
          exportOptions: {
            columns: ":visible",
          },
        },
      ],
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
