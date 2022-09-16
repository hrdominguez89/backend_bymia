"use strict";

$(document).ready(() => {
  listenCheckboxDeleteIcon();
});

let cboxDeletIcon;
let inputFileIcon;

const listenCheckboxDeleteIcon = () => {
  cboxDeletIcon = $("#topic_delete_icon");
  if (cboxDeletIcon.length) {
    inputFileIcon = $("#topic_icon");
    cboxDeletIcon.on("click", () => {
      requireInputFile(cboxDeletIcon.is(":checked"));
    });
  }
};

const requireInputFile = (value) => {
  if (value) {
    inputFileIcon.removeAttr("required");
  } else {
    inputFileIcon.attr("required", "required");
  }
};
