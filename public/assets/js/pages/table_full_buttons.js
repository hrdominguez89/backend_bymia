$(document).ready(() => {
  loadDatatableOperators();
});

let tableOperators;

const loadDatatableOperators = () => {
  if ($("#table_full_buttons").length) {
    tableOperators = $("#table_full_buttons").DataTable({
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
      colReorder: true,
      language: {
        url: "/assets/libs/datatables.net-language/es-ES.json",
      },
    });
  }
};
