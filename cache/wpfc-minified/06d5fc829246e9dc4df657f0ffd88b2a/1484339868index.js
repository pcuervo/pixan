// source --> http://localhost/pixan/wp-content/plugins/product-list/inc/js/product-list.js?ver=4.5.4 
$ = jQuery.noConflict();
ids = new Array();
cant = new Array();

/*
function addToCart(p_id) {
    $.get('/wp/?post_type=product&add-to-cart=' + p_id, function(data) {
       console.log(data);
    });
}
*/

$(document).ready(function(){
    
    $( ".add_to_list_dialog" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      
      buttons: {
        "Continuar" : {
          id : 'btnContinuar',
          text: 'Continuar',
          click : function() {
            //RESET ARRAYS
            ids = [];
            cant = [];
            $("#btnContinuar").hide();
            $("#dialogLoader").show();
            $("#dialogDefaultText").hide();
            $('.cart_item').each(function() {
              ids.push($(this).find('.remove').data('product_id'));
              cant.push($(this).find('.qty').val());
              //ids.push($(this).children('a.remove').data('product_id'));
            });
            //IF NOT CART PAGE AND IS SINGLE PRODUCT PAGE
            if(ids.length == 0) {
              ids.push($("input[name=add-to-cart]").val());
              cant.push(1);
            }
            
            $.post($("#rutaAjax").val(), {action: 'add_products_to_a_list', list_id: $("#add_product_list").val(), ids: ids, cant: cant }, 
              function(data) {
                
                if(data == "OK") {
                  var exitoText = '<div class="" style="background-color:#C8E6C9;">Articulos agregados con exito a tu lista.</div>';
                  $("#btnContinuar").hide();
                  $("#dialogMsj").show();
                  
                  $("#dialogMsj").html(exitoText);
                  $("#btnCerrar").html('Listo');
                } 
                else {
                  var errorText = '<div class="" style="background-color:#F8BBD0;">Ocurrio un error, por favor intentalo nuevamente.</div>';
                  $("#dialogMsj").html(errorText);
                }
            })
            .always(function(data){ 
               console.log('Always -> ['+data+']');
               $("#dialogLoader").hide();
            })
            .fail(function(xhr, status, error) {
                console.log('FAIL');
                console.log(xhr);
                console.log(status);
                console.log(error);
            });
          }
        },
        "Cancelar": {
          click: function() {
            $( this ).dialog( "close" );
            $("#dialogMsj").hide();
            $("#dialogDefaultText").show();
            $("#btnContinuar").show();
            $("#btnCerrar").html('Cerrar');
           
          }, 
          id: 'btnCerrar',
          text: 'Cancelar'
        }
      }
    });
    $( ".addToList" ).on( "click", function(e) {
      e.preventDefault();
      $("input[name=add-to-cart]").val($(this).data("product-id"));
      $(".add_to_list_dialog").dialog( "open" );
    });

    /*
    $( ".loadCart" ).on( "click", function(e) {
      e.preventDefault();
      $('.productOnList').each(function() {
        addToCart($(this).data('p_id'));
      });
    });
    */
});