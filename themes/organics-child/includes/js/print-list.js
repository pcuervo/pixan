
$("#btnImprimir").click(function(e) {
	e.preventDefault();
  $('.screen-reader-text, .order_actions, .order_actions').hide();
  $(':checkbox').hide();
  setTimeout(function() {
    imprimirDiv();
    setTimeout(function() {
      $('.screen-reader-text, .order_actions, .order_actions').show();
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
  ventimp.print( );
  ventimp.close();
    
}