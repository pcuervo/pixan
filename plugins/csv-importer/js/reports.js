
(function ($) {
	"use strict";
	$(function () {
		$(".btnImprimir").click(function(e) {
			e.preventDefault();
			printData();
		});
	});
	
	function printData()
	{
	   var divToPrint=document.getElementById("tablaExistencias");
	   var newWin= window.open("");
	   newWin.document.write(divToPrint.outerHTML);
	   newWin.print();
	   newWin.close();
	}
}(jQuery));