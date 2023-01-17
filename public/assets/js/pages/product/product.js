"use strict";

$(document).ready(() => {
  listenSelectCategories();
});

let selectCity;
let labelCity;
let cityId;

const listenSelectCities = () => {
    cityId = parseInt(labelCity.data("city-id"));
    selectCity.on("change", () => {
      cityId = parseInt(selectCity.val());
      labelCity.data("city-id", cityId);
    });
  };