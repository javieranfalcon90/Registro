$(document).ready(function() {

    $('body .wrapper').css('visibility', 'visible');
    $('body .wrapper').css('opacity', 1);

    $(".page-preloader").fadeOut("slow");

    $('.datepick').daterangepicker({
        autoUpdateInput: false,
        autoApply: true,
        singleDatePicker: true,
        showDropdowns: true,
        maxDate: new Date(),
        //minYear: 1901,
        //maxYear: parseInt(moment().format('YYYY'),10)

        locale: {
        format: "DD-MM-YYYY",
        cancelLabel: 'Limpiar',
        applyLabel: "Aplicar",
        daysOfWeek: [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sá"
        ],
        monthNames: [
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
            "Diciembre"
        ],
        },
    });

    $('.datepick').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY'));
    });


    $('.daterange').daterangepicker({
        ranges: {
          'Hoy': [moment(), moment()],
          'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
          'Este Mes': [moment().startOf('month'), moment().endOf('month')],
          'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
          'Año Anterior': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
        },


        autoUpdateInput: false,
        alwaysShowCalendars: true,
        linkedCalendars: false,
        showDropdowns: true,

        locale: {
          format: "MM-DD-YYYY",
          separator: " / ",
          cancelLabel: 'Limpiar',
          applyLabel: "Aplicar",
          linkedCalendars: false,
          daysOfWeek: [
              "Do",
              "Lu",
              "Ma",
              "Mi",
              "Ju",
              "Vi",
              "Sá"
          ],
          monthNames: [
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
              "Diciembre"
          ],
        },
    });

    $('.daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' / ' + picker.endDate.format('DD-MM-YYYY'));
    });

    $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });



  select2 = $(".select2").select2({
    language: "es",
    theme: "bootstrap-5",
    placeholder: ""
  });



  $(".group-checkable").prop("checked", "");
  $(".group-checkable").change(function() {
    var set = $("#dataTable").find('tbody > tr > td input[type="checkbox"]');
    var checked = $(this).prop("checked");

    $(set).each(function() {
      $(this).prop("checked", checked);
    });
  });
});

function select_menu(menu_id) {
  var element = "#" + menu_id;

  if (!$(element).hasClass("active")) {

    $(element).removeClass("active");
    $(element).removeClass("expand");
    $(element).addClass("active expand");
    $(element).find('.collapse').addClass('show');

  }
}
