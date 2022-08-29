"use strict";

$(document).ready(() => {
  getSelectsAndLabels();
  listenSelectRegion();
});

let selectRegion;
let selectSubregion;

let labelRegion;
let labelSubregion;

let regionId;
let subregionId;

let subregiones;

const getSelectsAndLabels = () => {
  selectRegion = $("#countries_region_type");
  selectSubregion = $("#countries_subregion");
  labelRegion = $("#label-region");
  labelSubregion = $("#label-subregion");
};


const disableSelect = (select) => {
  select.attr("disabled", "disabled");
};

const listenSelectRegion = () => {
  regionId = parseInt(labelRegion.data("region-id"));
  subregionId = labelSubregion.data('subregion-id') ? parseInt(labelSubregion.data("subregion-id")):null;
  if (regionId) {
    getSubregiones();
  }
  selectRegion.on("change", () => {
    regionId = parseInt(selectRegion.val());
    labelRegion.data("region-id", regionId);
    if (regionId) {
      //SI NO ES NaN
      cleanSelects(true);
      getSubregiones();
    }
  });
};

const cleanSelects = (cleanState = false) => {
  if (cleanState) {
    const optionsubregiones = $("<option></option>").text(
      "Seleccione una subregion"
    );
    selectSubregion.html(optionsubregiones);
  }
};

const createOptions = (select, data, idSelected = false) => {
  select.removeAttr("disabled");
  for (let i = 0; i < data.length; i++) {
    const element = data[i];
    const option = $("<option></option>").text(element.name);
    option.attr("value", element.id);
    if (idSelected && idSelected == element.id) {
      option.attr("selected", "selected");
    }
    select.append(option);
  }
};

const getSubregiones = () => {
  $.ajax({
    url: `/secure/world/getSubregiones/${regionId}`,
    method: "GET",
    success: async (res) => {
      if (res.status) {
        subregiones = await res.data;
        createOptions(
          selectSubregion,
          subregiones,
          subregionId ? subregionId : false
        );
      } else {
        disableSelect(selectSubregion);
      }
    },
  });
};
