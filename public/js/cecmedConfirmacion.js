function createDelete(rutaEliminar, rutaRedirect, token) {
  
  Swal.fire({
    title: "CONFIRMACIÓN",
    text:
      "Esta acción no se podrá deshacer. Seguro que desea eliminar este elemento?",
    icon: "error",
    showCancelButton: true,

    showLoaderOnConfirm: true,

    cancelButtonText: "No, cancelar!",
    confirmButtonText: "Si, eliminar!",
    
    preConfirm: () => {
      $.ajax({
        url: rutaEliminar,
        data: {_token: token},
        method: "POST"
      }).always(function() {
        swal.close();
          //window.location.href = rutaRedirect;
        });
    }
  });
}

function createDeleteAll(dataTable, route) {
  var ch = [];
  var i = 0;

  var set = $("#dataTable").find('tbody > tr > td input[type="checkbox"]');

  $(set).each(function() {
    if ($(this).is(":checked")) {
      ch[i] = $(this).attr("value");
      i = i + 1;
    }
  });

  if (i != 0) {
    Swal.fire({
      title: "CONFIRMACIÓN",
      text:
        "Esta acción no se podrá deshacer. Seguro que desea eliminar los elementos seleccionados?",
      type: "error",
      showCancelButton: true,
      confirmButtonClass: "btn btn-danger",
      cancelButtonClass: "btn btn-light",
      cancelButtonText: "No, cancelar!",
      confirmButtonText: "Si, eliminar!",
      showLoaderOnConfirm: true,

      preConfirm: function() {
        $.ajax({
          url: Routing.generate(route, { data: ch }),
          method: "POST"
        })
          .done(function(e) {
            toastr.success(e.texto, "Notificación");
            $(".group-checkable").prop("checked", "");
            dataTable.ajax.reload();
          })
          .fail(function(e, status, errorThrown) {
            if (errorThrown == "Forbidden") {
              var r =
                "No tienes los permisos necesarios para realizar esta operación";
              toastr.error(r, "Notificación");
            } else {
              var r = eval("(" + e.responseText + ")");
              toastr.error(r.texto, "Notificación");
            }
          })
          .always(function() {
            swal.close();
          });
      }
    });
  } else {
    toastr["warning"]("No existen elementos seleccionados", "Notificación");
  }
}

function aprobar(ruta) {

  Swal.fire({
    title: "CONFIRMACIÓN",
    text: "Seguro que desea aprobar este elemento?",
    icon: "info",

    showCancelButton: true,

    cancelButtonText: "No, cancelar!",
    confirmButtonText: "Si, aprobar!",

    showLoaderOnConfirm: true,

    preConfirm: function() {
      $.ajax({
          url: ruta,
          method: "POST"
      }).always(function() {
          swal.close();
          location.reload();
      });
    },

  });

}


function rechazar(ruta) {

  Swal.fire({
    title: "CONFIRMACIÓN",
    text: "Seguro que desea rechazar este elemento?",
    icon: "info",

    showCancelButton: true,

    cancelButtonText: "No, cancelar!",
    confirmButtonText: "Si, rechazar!",


  }).then((result) => {

    if(result.isConfirmed){

        const { value: text } = Swal.fire({
        input: 'textarea',
        title: 'Message',
        inputPlaceholder: 'Type your message here...',
        showCancelButton: true,
        showLoaderOnConfirm: true,

        preConfirm: (text) => {

          $.ajax({
              url: ruta,
              data: {observaciones: text},
              method: "POST"
          }).always(function() {
              swal.close();
              location.reload();
          });
        }

      })


    }


  });

}

