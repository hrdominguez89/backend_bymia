//show or hide loader
function toggleLoader() {
  $(".loader-container").toggle();
}

//show table loader
function toogleTableLoader(tableLoaderClass) {
  $("." + tableLoaderClass).toggle();
}

function toogleLabelPost(btn = "#btn-submit", title = ".modal-title") {
  $(btn).text("Adicionar");
  $(btn).addClass("btn-primary");
  $(btn).removeClass("btn-warning");
  $(title).text("Adicionar");
}

function toogleLabelPut(btn = "#btn-submit", title = ".modal-title") {
  $(btn).text("Modificar");
  $(btn).removeClass("btn-primary");
  $(btn).addClass("btn-warning");
  $(title).text("Modificar");
}

//clear form field
function clearForm(form) {
  // iterate over all of the inputs for the form
  // element that was passed in
  $(":input", form).each(function () {
    var type = this.type;
    var tag = this.tagName.toLowerCase(); // normalize case
    // it's ok to reset the value attr of text inputs,
    // password inputs, and textareas
    if (
      type == "text" ||
      type == "password" ||
      tag == "textarea" ||
      type == "number" ||
      type == "email"
    )
      this.value = "";
    // checkboxes and radios need to have their checked state cleared
    // but should *not* have their 'value' changed
    else if (type == "checkbox" || type == "radio") this.checked = false;
    // select elements need to have their 'selectedIndex' property set to -1
    // (this works for both single and multiple select elements)
    else if (tag == "select") this.selectedIndex = -1;
  });
}

//show message
const MessageType = {
  SUCCESS: "success",
  INFO: "info",
  WARNING: "warning",
  ERROR: "danger",
};

function message(
  title = "Información.!",
  html = "Operación satisfactoria.!",
  type = MessageType.SUCCESS
) {
  const template = `<div style="position: absolute;z-index: 999999;top: 80px;right: 20px;" 
             class="mr-auto w-25 alert alert-${type} alert-dismissible alert-label-icon label-arrow fade show toasts-alert" role="alert">
                <i class="mdi mdi-check-all label-icon"></i><strong>${title}: </strong>${html}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

  $("body").append(template);
  setTimeout(() => {
    $(".toasts-alert").addClass("transition-right");
    setTimeout(() => $(".toasts-alert").remove(), 1000);
  }, 4000);
}

//show confirmation massage
function messageConfirmation(
  title = "Advertencia!!!",
  text = "Está seguro que desea eliminar el elemento seleccionado? No podrá deshacer esta operación!",
  fnCallBack
) {
  Swal.fire({
    title: title,
    text: text,
    icon: "warning",
    showCancelButton: !0,
    confirmButtonColor: "#2ab57d",
    cancelButtonColor: "#fd625e",
  }).then(function (e) {
    e.value && fnCallBack();
  });
}

//send a rquest
const Method = {
  GET: 0,
  POST: 1,
  PUT: 2,
  DELETE: 3,
};

function send(method, url, params, fnCallBackOnSuccess, fnCallBackAlways) {
  var rquest;

  switch (method) {
    case Method.POST:
      rquest = axios.post(url, params);
      break;
    case Method.PUT:
      rquest = axios.put(url, params);
      break;
    case Method.DELETE:
      rquest = axios.delete(url, params);
      break;
    default:
      rquest = axios.get(url, params);
      break;
  }

  rquest
    .then(function (response) {
      //success
      if (fnCallBackOnSuccess) fnCallBackOnSuccess(response);
    })
    .catch(function (error) {
      // handle error
      // Error 😨
      console.log(error);
      message(
        "Error!",
        "Ha ocurrido un error en la solicitud",
        MessageType.ERROR
      );
      // if (error.response) {
      //   /*
      //    * The request was made and the server responded with a
      //    * status code that falls out of the range of 2xx
      //    */
      //   console.log(error.response);

      //   if (error.response.data.errors) {
      //     $msg = "";
      //     $.each(error.response.data.errors.children, function (key, value) {
      //       if (undefined != value.errors) $msg += value.errors + "<br>";
      //     });

      //     // message error
      //     message(error.response.data.message, $msg, MessageType.ERROR);
      //   } else {
      //     // message error
      //     message("Error!", error.response.data.message, MessageType.ERROR);
      //   }
      // } else if (error.request) {
      //   /*
      //    * The request was made but no response was received, `error.request`
      //    * is an instance of XMLHttpRequest in the browser and an instance
      //    * of http.ClientRequest in Node.js
      //    */
      //   console.log(error.request);

      //   // message error
      //   message("Error!", error, MessageType.ERROR);
      // } else {
      //   // Something happened in setting up the request and triggered an Error
      //   console.log("Error", error.message);
      //   // message error
      //   message("Error!", error.message, MessageType.ERROR);
      // }
    })
    .then(function () {
      // always executed
      if (fnCallBackAlways) fnCallBackAlways();
    });
}

//security  is_granted
function is_granted(role) {
  return true;
}

$(document).ready(function () {
  openModal();
  //set language on init to all site
  o = "sp"; //localStorage.getItem("language");
  $.getJSON("/assets/lang/" + o + ".json", function (t) {
    $("html").attr("lang", o),
      $.each(t, function (t, e) {
        "head" === t && $(document).attr("title", e.title),
          $("[data-key='" + t + "']").text(e);
      });
  });
});

const openModal = () => {
  if($('#messageModal').length){
    $('#messageModal').modal('show');
  }
};

const loadingCmponent = {
  show() {
    $("#loading--component-id").fadeIn();
  },
  close() {
    $("#loading--component-id").fadeOut();
  },
};

loadingCmponent.close();
