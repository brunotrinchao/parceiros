$(document).ready(function(){
        // Select
        $('select').select2();
        var start = moment().subtract(29, 'days');
        var end = moment();
       //Date picker
       $('.datetimepicker').daterangepicker({
          "singleDatePicker": true,
          "autoApply": true,
          "showDropdowns": true,
          "locale": {
              "format": "DD/MM/YYYY",
              "separator": " - ",
              "applyLabel": "Apply",
              "cancelLabel": "Cancel",
              "fromLabel": "From",
              "toLabel": "To",
              "customRangeLabel": "Custom",
              "weekLabel": "W",
              "daysOfWeek": [
                  "Do",
                  "Se",
                  "Te",
                  "Qu",
                  "Qu",
                  "Se",
                  "Sa"
              ],
              "monthNames": [
                  "Janeiro",
                  "Fevereiro",
                  "Março",
                  "Abril",
                  "Maio",
                  "Junho",
                  "Julho",
                  "Agosto",
                  "Setembro",
                  "Outubro",
                  "Novembro",
                  "Dezembro"
              ],
              "firstDay": 1
          },
          "alwaysShowCalendars": true,
          "startDate": start,
          "endDate": end,
          "maxDate": end,
          "opens": "left"
      }, function(start, end, label) {
        if (start.format('DD/MM/YYYY') !== end.format('DD/MM/YYYY')) {
                $('.datetimepicker input').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
            } else {
                $('.datetimepicker input').val(start.format('DD/MM/YYYY'));
            }
      });
  
      // date range
       $('.daterange').daterangepicker({
        "showDropdowns": true,
          ranges: {
             'Hoje': [moment(), moment()],
             'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
             'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
             'Este mês': [moment().startOf('month'), moment().endOf('month')],
             'Mês anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          "locale": {
              "format": "DD/MM/YYYY",
              "separator": " até ",
              "applyLabel": "Aplicar",
              "cancelLabel": "Cancelar",
              "fromLabel": "De",
              "toLabel": "Para",
              "customRangeLabel": "Selecionar período",
              "daysOfWeek": [
                  "Do",
                  "Se",
                  "Te",
                  "Qu",
                  "Qu",
                  "Se",
                  "Sa"
              ],
              "monthNames": [
                  "Janeiro",
                  "Fevereiro",
                  "Março",
                  "Abril",
                  "Maio",
                  "Junho",
                  "Julho",
                  "Agosto",
                  "Setembro",
                  "Outubro",
                  "Novembro",
                  "Dezembro"
              ],
              "firstDay": 1
          },
          "startDate": start,
          "endDate": end,
          "opens": "center",
          "endDate": end,
          "maxDate": end,
          "autoUpdateInput": false,
          "alwaysShowCalendars": false,
      }, function(start, end, label) {
        if (start.format('DD/MM/YYYY') !== end.format('DD/MM/YYYY')) {
            $('#data_cadastro').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        } else {
            $('#data_cadastro').val(start.format('DD/MM/YYYY'));
        }
      });
  
      // Mascaras
      $('.cpf').mask('999.999.999-99', { 'placeholder': '__.___.___-__' });
      $('.telefone').mask("(99) 9999-9999?9", { 'placeholder': '(  ) _____-____)' })
          .focusout(function (event) {  
              var target, phone, element;  
              target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
              phone = target.value.replace(/\D/g, '');
              element = $(target);  
              element.unmask();  
              if(phone.length > 10) {  
                  element.mask("(99) 99999-999?9");  
              } else {  
                  element.mask("(99) 9999-9999?9");  
              }  
          });
      $('.valor').maskMoney({
        prefix: 'R$ ',
        decimal:",", 
        thousands:"."
      });
  
      // Datatables
      $('.datatables').DataTable({
        responsive: true,
        language: {
          "lengthMenu": "Exibir _MENU_ registros",
          "zeroRecords": "Nada encontrado",
          "info": "Mostrar _PAGE_ de _PAGES_",
          "infoEmpty": "Nenhum registro disponível",
          "infoFiltered": "(Filtrado de _MAX_ no total)",
          "loadingRecords": "Carregando...",
          "processing":     "Processando...",
          "search":         "Pesquisar:",
          "paginate": {
              "first":      "Primeiro",
              "last":       "Úlrimo",
              "next":       "Próximo",
              "previous":   "Anterior"
          },
          "aria": {
              "sortAscending":  ": ativar para classificar coluna ascendente",
              "sortDescending": ": ativar para classificar coluna descendente"
          }
        }
      });
});