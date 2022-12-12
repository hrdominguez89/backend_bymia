"use strict";
//<a title="Copiar" href="javascript:void(0);" class="data_to_copy" data-value="VALOR A COPIAR"><i class="far fa-copy"></i></a>
$(document).ready(() => {
  loadButtonCopy();
});

const loadButtonCopy = () => {
  if ($(".data_to_copy").length) {
    $(".data_to_copy").on("click", function (event) {
      navigator.clipboard.writeText(event.currentTarget.dataset.value);
    });
  }
};
