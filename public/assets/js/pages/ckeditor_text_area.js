"use strict";

$(document).ready(() => {
  loadTextAreaEditor();
});

const loadTextAreaEditor = () => {
  if ($("#DivTextEditor").length) {
    let idTexArea = $("#DivTextEditor").data("idTextArea");
    ClassicEditor.create(document.querySelector(`#${idTexArea}`)).catch(
      (error) => {
        console.error(error);
      }
    );
  }
};
