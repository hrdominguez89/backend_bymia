$(document).ready(function () {
  //init load
  send(Method.GET, "/public/contact-us", null, function (response) {
    $.each(response.data[0], function (key, value) {
      $(`[name='${key}']`).val(value);
    });
  });
});

function post() {
  //get form data
  var formData = $(".form-contact-us")
    .serializeArray()
    .reduce(function (obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});

  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/contact-us",
    formData,
    function (response) {
      // message ok
      message();
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

//prevent form submit
window.onload = function () {
  var form = document.getElementById("form-contact-us");

  pristine = new Pristine(form);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristine.validate();
    if (valid) post();
  });
};
//end prevent submit
