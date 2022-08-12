function getChartColorsArray(r) {
  r = $(r).attr("data-colors");
  return (r = JSON.parse(r)).map(function (r) {
    r = r.replace(" ", "");
    if (-1 == r.indexOf("--")) return r;
    r = getComputedStyle(document.documentElement).getPropertyValue(r);
    return r || void 0;
  });
}

$(document).ready(function () {
  //getOrderSummary();
  //getProductBestSeller();
  //getBestCategory();
  //getSummaryByMonth();
  //getBetterCustomerList();
  //getLastOrderList();
  // getBestBrand();
});

//api functions
function getOrderSummary() {
  //send request
  send(Method.GET, "/secure/dashboard/order/sumary", null, function (response) {
    //transform response data
    data = response.data;

    var cval1 = 0;
    var cval2 = 0;
    var cvar = 0;
    var cdata1 = [];
    var cdata2 = [];
    for (let i = 0; i < data.custom.length; i++) {
      cdata1.push(parseFloat(data.custom[i].cant));
      cval1 += parseFloat(data.custom[i].cant);
    }
    for (let i = 0; i < data.last.length; i++) {
      cdata2.push(parseFloat(data.last[i].cant));
      cval2 += parseFloat(data.last[i].cant);
    }
    cvar = cval1 - cval2;
    miniChartRender("#mini-chart1", cdata1, cdata2);
    miniChartValRender("mini-chart1-val", cval1);
    miniChartVarRender("mini-chart1-var", cvar);

    cval1 = 0;
    cval2 = 0;
    cvar = 0;
    cdata1 = [];
    cdata2 = [];
    for (let i = 0; i < data.custom.length; i++) {
      cdata1.push(parseFloat(data.custom[i].amount));
      cval1 += parseFloat(data.custom[i].amount);
    }
    for (let i = 0; i < data.last.length; i++) {
      cdata2.push(parseFloat(data.last[i].amount));
      cval2 += parseFloat(data.last[i].amount);
    }
    cvar = cval1 - cval2;

    miniChartRender("#mini-chart2", cdata1, cdata2);
    miniChartValRender("mini-chart2-val", cval1, "$");
    miniChartVarRender("mini-chart2-var", cvar, "$");
  });
}

function getSummaryByMonth() {
  //send request
  send(
    Method.GET,
    "/secure/dashboard/summary/by-month",
    null,
    function (response) {
      //transform response data
      data = [];

      var data = response.data.map((obj) => {
        return obj;
      });

      var sales = [];
      var amount = [];
      for (let i = 0; i < 12; i++) {
        sales.push(parseFloat(data[i].sales));
        amount.push(parseFloat(data[i].amount));
      }

      columnChartDatalabelRender(
        "#column_chart_datalabel1",
        "Ventas",
        "",
        sales
      );
      columnChartDatalabelRender(
        "#column_chart_datalabel2",
        "Ingresos",
        "$",
        amount
      );
    }
  );
}

function columnChartDatalabelRender(title, name, symbol, data) {
  var columnDatalabelColors = getChartColorsArray(title),
    options = {
      chart: { height: 300, type: "bar", toolbar: { show: !1 } },
      plotOptions: {
        bar: { borderRadius: 10, dataLabels: { position: "top" } },
      },
      dataLabels: {
        enabled: !0,
        formatter: function (e) {
          return symbol + procesVal(e);
        },
        offsetY: -22,
        style: { fontSize: "12px", colors: ["#304758"] },
      },
      series: [
        {
          name: name,
          data: data,
        },
      ],
      colors: columnDatalabelColors,
      grid: { borderColor: "#f1f1f1" },
      xaxis: {
        categories: [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre",
        ],
        position: "top",
        labels: { offsetY: -58 },
        axisBorder: { show: !1 },
        axisTicks: { show: !1 },
        crosshairs: {
          fill: {
            type: "gradient",
            gradient: {
              colorFrom: "#D8E3F0",
              colorTo: "#BED1E6",
              stops: [0, 100],
              opacityFrom: 0.4,
              opacityTo: 0.5,
            },
          },
        },
        tooltip: { enabled: !0, offsetY: 10 },
      },
      yaxis: {
        axisBorder: { show: !1 },
        axisTicks: { show: !1 },
        labels: {
          show: !1,
          formatter: function (e) {
            return symbol + e;
          },
        },
      },
    };
  (chart = new ApexCharts(document.querySelector(title), options)).render();
}

function getProductBestSeller() {
  //send request
  send(
    Method.GET,
    "/secure/dashboard/products/best-seller",
    null,
    function (response) {
      //transform response data
      data = [];

      var data = response.data.map((obj) => {
        return obj;
      });

      var cdata1 = [];
      var cdata2 = [];
      $legend = "";
      var max = data.length < 6 ? data.length : 5;
      for (let i = 0; i < max; i++) {
        cdata1.push(parseFloat(data[i].sales));
        cdata2.push(data[i].name);
        $legend += ` <div>
          <p class="mb-2"><i class="mdi mdi-circle align-middle font-size-10 me-2 color${i}"></i>
          ${data[i].name}: ${parseFloat(data[i].sales).toFixed(2)} </p>
            </div>`;
      }
      $("#product-best-seller-legend").html($legend);

      var piechartColors = getChartColorsArray("#product-best-seller"),
        options = {
          series: cdata1,
          chart: { width: 227, height: 227, type: "pie" },
          labels: cdata2,
          colors: piechartColors,
          stroke: { width: 0 },
          legend: { show: !1 },
          responsive: [{ breakpoint: 480, options: { chart: { width: 200 } } }],
        };
      (chart = new ApexCharts(
        document.querySelector("#product-best-seller"),
        options
      )).render();
    }
  );
}

function getBestCategory() {
  //send request
  send(Method.GET, "/secure/dashboard/products/best-category", null, function (response) {
    //transform response data
    data = [];

    var data = response.data.map((obj) => {
      return obj;
    });

    var cdata1 = [];
    var cdata2 = [];
    $legend = "";

    var max = data.length < 6 ? data.length : 5;
    for (let i = 0; i < max; i++) {
      cdata1.push(parseFloat(data[i].sales));
      cdata2.push(data[i].name);
      $legend += ` <div>
          <p class="mb-2"><i class="mdi mdi-circle align-middle font-size-10 me-2 color${i}"></i>
          ${data[i].name}: ${parseFloat(data[i].sales).toFixed(2)}</p>
          </div>`;
    }
    $("#better-categorys-legend").html($legend);

    var piechartColors = getChartColorsArray("#better-categorys"),
      options = {
        series: cdata1,
        chart: { width: 227, height: 227, type: "pie" },
        labels: cdata2,
        colors: piechartColors,
        stroke: { width: 0 },
        legend: { show: !1 },
        responsive: [{ breakpoint: 480, options: { chart: { width: 200 } } }],
      };
    (chart = new ApexCharts(
      document.querySelector("#better-categorys"),
      options
    )).render();
  });
}

function getBetterCustomerList() {
  //send request
  send(
    Method.GET,
    "/secure/dashboard/better-customer",
    null,
    function (response) {
      //transform response data
      data = [];

      var data = response.data.map((obj) => {
        return obj;
      });

      $li = "";
      var max = data.length < 11 ? data.length  : 10;
      for (let i = 0; i < max; i++) {
        $li += ` 
              <li class="activity-list activity-border">
                  <div class="activity-icon avatar-md">
                      <img class="rounded-circle header-profile-user" src="${
                        data[i].image
                      }"/>
                  </div>
                  <div class="timeline-list-item">
                      <div class="d-flex">
                          <div class="flex-grow-1 overflow-hidden me-4">
                              <h5 class="font-size-14 mb-1">${data[i].name}</h5>
                              <p class="text-truncate text-muted font-size-13  mb-1">${
                                data[i].email
                              }</p>
                              <p class="text-truncate text-muted font-size-13">${
                                data[i].address
                              }</p>
                          </div>
                          <div class="flex-shrink-0 text-end me-3">
                              <h6 class="mb-1">&nbsp;</h6>
                              <h6 class="mb-1">${data[i].cant}</h6>
                              <div class="font-size-13">$${parseFloat(
                                data[i].amount
                              ).toFixed(2)}</div>
                          </div>
                      </div>
                  </div> 
              </li>`;
      }
      $("#better-cstomers-list").html($li);
    }
  );
}

function getLastOrderList() {
  //send request
  send(Method.GET, "/secure/dashboard/last-orders", null, function (response) {
    //transform response data
    data = [];

    var data = response.data.map((obj) => {
      return obj;
    });

    $li = "";
    var max = data.length < 11 ? data.length : 10;
    for (let i = 0; i < max; i++) {
      $li += ` 
              <li class="activity-list activity-border">
                  <div class="activity-icon avatar-md">
                      <img class="rounded-circle header-profile-user" src="${
                        data[i].image
                      }"/>
                  </div>
                  <div class="timeline-list-item">
                      <div class="d-flex">
                          <div class="flex-grow-1 overflow-hidden me-4">
                              <h5 class="font-size-14 mb-1">Orden: ${
                                data[i].number
                              }</h5>
                              <p class="text-truncate text-muted font-size-13  mb-1">${
                                data[i].name
                              }</p>
                              <p class="text-truncate text-muted font-size-13">${
                                data[i].state
                              }</p>
                          </div>
                          <div class="flex-shrink-0 text-end me-3">
                              <h6 class="mb-1">&nbsp;</h6>                              
                              <h6 class="mb-1">&nbsp;</h6>
                              <div class="font-size-13">$${parseFloat(
                                data[i].total
                              ).toFixed(2)}</div>
                          </div>
                      </div>
                  </div> 
              </li>`;
    }
    $("#last-orders").html($li);
  });
}

//api functions
// function getBestBrand() {
//   //send request
//   send(Method.GET, "/dashboard/best/brand", null, function (response) {
//     //transform response data
//     data = [];

//     var data = response.data.map((obj) => {
//       return obj;
//     });

//     var $inner = "";
//     var $indicator = "";
//     var max = data.length < 6 ? data.length : 5;
//     for (let i = 0; i < max; i++) {
//       $inner += `<div class="carousel-item ${i == 0 ? "active" : ""}">
//                     <div class="text-center p-4">
//                         <img class="widget-box-1-icon img_brand" src="${
//                           data[i].image
//                         }"/>
//                         <div class="m-auto">
//                             <img class="img_brand" src="${data[i].image}"/>
//                         </div>
//                         <h4 class="mt-3 lh-base fw-normal text-white"><b>${
//                           data[i].name
//                         }</b></h4>
//                         <h5 class="card-title me-2 mb-3 lh-1 d-block text-truncate text-white">Ventas: ${parseFloat(
//                           data[i].sales
//                         ).toFixed(2)}
//                         </h5>
//                         <h5 class="card-title me-2 mb-3 lh-1 d-block text-truncate text-white">Ingresos: $${parseFloat(
//                           data[i].amount
//                         ).toFixed(2)}</h5>
//                     </div>
//                 </div>`;
//       $indicator += `<button type="button" data-bs-target="#bestBrand" data-bs-slide-to="${i}" class="${
//         i == 0 ? "active" : ""
//       }"
//                       aria-current="true" aria-label="Slide ${i}"></button>`;
//     }

//     $("#bestBrandInner").html($inner);
//     $("#bestBrandIndicator").html($indicator);
//   });
// }

function miniChartRender(chart, data1, data2) {
  options = {
    series: [
      {
        data: data1,
      },
      { data: data2 },
    ],
    chart: { type: "line", height: 50, sparkline: { enabled: !0 } },
    colors: ["#6B6FC8", "#FD625E"],
    stroke: { curve: "smooth", width: 2 },
    tooltip: {
      fixed: { enabled: !1 },
      x: { show: !1 },
      y: {
        title: {
          formatter: function (r) {
            return "";
          },
        },
      },
      marker: { show: !1 },
    },
  };
  (chart = new ApexCharts(document.querySelector(chart), options)).render();
}
function miniChartValRender(element, val, money = "") {
  var html = `${money}<span class="counter-value">${procesVal(
    val,
    money == "$"
  )}</span>`;
  $("#" + element).html(html);
}
function miniChartVarRender(element, val, money = "") {
  var type = val >= 0 ? "success" : "danger";
  var symbol = val >= 0 ? "+" + money : "" + money;
  var html = `<span class="badge bg-soft-${type} text-${type}">${symbol}${procesVal(
    val,
    money == "$"
  )}</span>`;
  $("#" + element).html(html);
}
function procesVal(val, money = false) {
  if (money) {
    if (Math.abs(val) > 1000000) return (val / 1000000).toFixed(2) + "M";
    if (Math.abs(val) > 1000) return (val / 1000).toFixed(2) + "K";
    return val.toFixed(2);
  } else {
    if (Math.abs(val) > 1000000) return (val / 1000000).toFixed(0) + "M";
    if (Math.abs(val) > 1000) return (val / 1000).toFixed(0) + "K";
    return val.toFixed(0);
  }
}
