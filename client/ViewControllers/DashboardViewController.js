$(function(){
	$(window).on("loadscripts", function(){
		$('.refresh').click(LoadSummaryTemplate);
		var today = new Date();
		var yearFirst = new Date("01/01/"+today.getFullYear());

		$('#start-datepicker').val($.datepicker.formatDate( "yy-mm-dd", yearFirst));
		$('#end-datepicker').val($.datepicker.formatDate( "yy-mm-dd", today));
		$('#start-datepicker').datepicker({defaultDate: yearFirst});
		$('#end-datepicker').datepicker({defaultDate: today});
		LoadSummaryTemplate();
	});
});

function LoadSummaryTemplate()
{	
	var authId = localStorage.getItem("id");
	var tok = sessionStorage.getItem("tolkien");
	var groupBy = $('select.group').val();
	
	var startDate = $( "#start-datepicker" ).datepicker( "getDate" );
	var endDate = $( "#end-datepicker" ).datepicker( "getDate" );
	$('#start-datepicker, #end-datepicker').datepicker("hide");
	var startDateStr = $.datepicker.formatDate( "yy-mm-dd", startDate);
	var endDateStr = $.datepicker.formatDate( "yy-mm-dd", endDate);

	$.get(GetLocalUrl("Templates/dashboard-item.html"), function(template) {
		Ajax("Order", {"func":"get", "groupBy":groupBy, "startDate":startDateStr, "endDate":endDateStr, "authId":authId, "auth":tok}, function(data){
			$("#summary-table tr").not(':first').remove();
			$(data).each(function(idx, o){
				var tax = parseFloat(o.tax) + parseFloat(o.shiptax);
				var gross = parseFloat(o.sales) + parseFloat(o.ship) + tax;
				var inner = template;
				inner = $.parseHTML(ParseObjectIntoTemplate(o, inner));
				$('td.tax span', inner).text(tax.toFixed(2));
				$('td.gross span', inner).text(gross.toFixed(2));
				$("#summary-table").append(inner);
			});
			
			CalculateTotals();
		});
	});
}

function CalculateTotals()
{
	var totalSales = 0.0;
	var totalShip = 0.0;
	var totalTax = 0.0;
	var totalRefund = 0.0;
	var totalGross = 0.0;
	
	$('#summary-table tr').each(function(idx, row){
		totalSales += parseFloat($(row).find("td.sales span").text()) || 0.0;
		totalShip += parseFloat($(row).find("td.ship span").text()) || 0.0;
		totalTax += parseFloat($(row).find("td.tax span").text()) || 0.0;
		totalRefund += parseFloat($(row).find("td.refund span").text()) || 0.0;
		totalGross += parseFloat($(row).find("td.gross span").text()) || 0.0;
	});
	
	totalSales = totalSales.toFixed(2);
	totalShip = totalShip.toFixed(2);
	totalTax = totalTax.toFixed(2);
	totalRefund = totalRefund.toFixed(2);
	totalGross = totalGross.toFixed(2);

	$('#summary-table').append("<tr class='grand-total'><td>Totals</td><td>$"+totalSales+"</td><td>$"+totalTax+"</td><td>$"+totalShip+"</td><td>$"+totalGross+"</td><td>$"+totalRefund+"</td></tr>");
}