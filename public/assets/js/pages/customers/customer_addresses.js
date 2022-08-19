"use strict";

$(document).ready(() => {
  getSelectsAndLabels();
  listenSelectCountry();
  listenSelectState();
  listenSelectCities();
});

let selectCountry;
let selectState;
let selectCity;

let labelCountry;
let labelState;
let labelCity;

let countryId;
let stateId;
let cityId;

let states;
let cities;

const getSelectsAndLabels = () => {
  selectCountry = $("#customer_addresses_country");
  selectState = $("#customer_addresses_state");
  selectCity = $("#customer_addresses_city");
  labelCountry = $("#label-country");
  labelState = $("#label-state");
  labelCity = $("#label-city");
};

const listenSelectCities = () => {
  cityId = parseInt(labelCity.data("city-id"));
  selectCity.on("change", () => {
    cityId = parseInt(selectCity.val());
    labelCity.data("city-id", cityId);
  });
};

const listenSelectState = () => {
  stateId = parseInt(labelState.data("state-id"));
  if (stateId) {
    getCities();
  }
  selectState.on("change", () => {
    stateId = parseInt(selectState.val());
    labelState.data("state-id", stateId);
    if (stateId) {
      //SI NO ES NaN
      cleanSelects();
      getCities();
    }
  });
};

const disableSelect = (select) => {
  select.attr("disabled", "disabled");
};

const listenSelectCountry = () => {
  countryId = parseInt(labelCountry.data("country-id"));
  if (countryId) {
    getStates();
  }
  selectCountry.on("change", () => {
    countryId = parseInt(selectCountry.val());
    labelCountry.data("country-id", countryId);
    if (countryId) {
      //SI NO ES NaN
      cleanSelects(true);
      getStates();
    }
  });
};

const cleanSelects = (cleanState = false) => {
  if (cleanState) {
    const optionStates = $("<option></option>").text(
      "Seleccione un estado/provincia"
    );
    selectState.html(optionStates);
    selectCity.attr("disabled", "disabled");
  }
  const optionCity = $("<option></option>").text("Seleccione una ciudad");
  selectCity.html(optionCity);
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

const getStates = () => {
  $.ajax({
    url: `/secure/customeraddresses/getStates/${countryId}`,
    method: "GET",
    success: async (res) => {
      if (res.status) {
        states = await res.data;
        createOptions(selectState, states, stateId ? stateId : false);
      } else {
        disableSelect(selectState);
      }
    },
  });
};

const getCities = () => {
  $.ajax({
    url: `/secure/customeraddresses/getCities/${stateId}`,
    method: "GET",
    success: async (res) => {
      if (res.status) {
        cities = await res.data;
        createOptions(selectCity, cities, cityId ? cityId : false);
      } else {
        disableSelect(selectCity);
      }
    },
  });
};
