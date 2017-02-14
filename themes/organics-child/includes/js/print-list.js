
$("#btnImprimir").click(function(e) {
	e.preventDefault();
  $('.screen-reader-text, .order_actions, .order_actions, .edit, .trash, .button.tips, input ').hide();
  $('#the-list').css( "background-color", "#000" );
  $('#the-list a, small, .meta').css({
    color: "#000",
    textDecoration: "none",
    marginRight: "10px",
  });
  $('*').css({
    fontWeight: "500",
    fontFamily: "Arial",
    lineheight: "30px",
    fontSize: "14px",
    marginBottom: "5px"
  });
  $('.show_order_items').css({
    backgroundColor: "#fff",
    color: "#1E4B24",
    marginLeft: "-15px",
    marginTop: "-15px",
    height: "20px"
  });
  $('input[type=checkbox]').css( "border-color", "#fff" );
  $('.email').css({
   marginBottom: "-20px",
   backgroundColor: "#fff",
   zIndex: "99",
   position: "relative"
  });
  $('.row-title').css({
    marginLeft: "20px",
    color: "#1E4B24"
  });
  $('.amount, .meta, abbr, .note-on.tips').css( "display", "block" );
  $('.amount').css( "color", "#1E4B24" );
  $('.order_status').css( "background-color", "#fff" );
  $(':checkbox').hide();

  setTimeout(function() {
    imprimirDiv();
    setTimeout(function() {
      $('.screen-reader-text, .order_actions, .order_actions, .edit, .trash, .button.tips, input').show();
      $('#the-list a, small, .meta').css({
        color: "",
        textDecoration: "",
        marginRight: "",
      });
      $('*').css({
        fontWeight: "",
        fontFamily: "",
        lineheight: "",
        fontSize: "",
        marginBottom: ""
      });
      $('.show_order_items').css({
        backgroundColor: "#",
        color: "",
        marginLeft: "",
        marginTop: "",
        height: ""
      });
      $('input[type=checkbox]').css( "border-color", "" );
      $('.email').css({
       marginBottom: "",
       backgroundColor: "",
       zIndex: "",
       position: ""
      });
      $('.row-title').css({
        marginLeft: "",
        color: ""
      });
      $('.amount, .meta, abbr, .note-on.tips').css( "display", "" );
      $('.amount').css( "color", "" );
      $('.order_status').css( "background-color", "" );
      $(':checkbox').show();
    }, 1000);
  }, 1000);
});

function imprimirDiv()
{
  //var ficha = document.getElementsByClassName("wp-list-table widefat fixed striped posts");
  //var ficha = $('.wp-list-table');
  var ficha = document.getElementById("the-list");
  //var ficha = document.getElementsByClassName("wp-list-table");
  var izquierda = (screen.width-800)/2;
  var ventimp = window.open(' ', 'popimpr','width=1000,height=800,left='+izquierda+'scrollbars=NO');
  ventimp.document.write( ficha.innerHTML);
  //ventimp.document.write(  );
  ventimp.document.close();
  ventimp.print();
  ventimp.close();

}