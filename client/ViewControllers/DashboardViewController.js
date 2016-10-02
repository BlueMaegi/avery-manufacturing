$(function(){
	$(window).on("loadscripts", function(){
		LoadSummaryTemplate();
	});
});

function LoadSummaryTemplate()
{	
	var authId = localStorage.getItem("id");
	var tok = sessionStorage.getItem("tolkien");

	$.get(GetLocalUrl("Templates/dashboard-item.html"), function(template) {
		Ajax("Order", {"func":"get", "groupBy":"month", "startDate":"2016-07-01", "endDate":"2016-11-01", "authId":authId, "auth":tok}, function(data){
			$(data).each(function(idx, o){
				var tax = parseFloat(o.tax) + parseFloat(o.shiptax);
				var gross = parseFloat(o.sales) + parseFloat(o.ship) + tax;
				var inner = template;
				inner = $.parseHTML(ParseObjectIntoTemplate(o, inner));
				$('td.tax span', inner).text(tax.toFixed(2));
				$('td.gross span', inner).text(gross.toFixed(2));
				$("#summary-table").append(inner);
			});
		});
	});
}