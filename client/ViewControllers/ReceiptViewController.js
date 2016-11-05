$(function(){
	$(window).on("loadscripts", function(){
		orderCode = GetUrlParam('code');
		if(orderCode)
		{
			LoadTemplate();
		}	
	});
});

function LoadTemplate()
{	
	$.get(GetLocalUrl("Templates/receipt.html"), function(template) {
		Ajax("Order", {"func":"get", "code":orderCode}, function(data){
			var inner = template;
			inner = ParseObjectIntoTemplate(data[0], inner);
			$("div.data-template").prepend(inner);
			RefreshPrices();
			FormatDates();
			FormatPhone();
			LoadShipmentsTemplate();
		});
	});
}

function LoadShipmentsTemplate()
{	
	$.get(GetLocalUrl("Templates/receipt-shipment.html"), function(template) {
		Ajax("Shipment", {"func":"get", "code":orderCode}, function(data){
			$(data).each(function(idx, o){
				var inner = template;
				inner = ParseObjectIntoTemplate(o, inner);
				$("#shipment-table").prepend(inner);
				$("#shipment-table tr").first().find(".ship-num").text(idx+1);
			});
			if(data.length > 1)
				$(".ship-num").parent().show();
		});
	});
}

function RefreshPrices()
{
	var total = 0;
	
	$("span.subtotal").each(function(idx, p)
	{
		total += parseFloat($(p).text());
	});
	
	$("span.total").text(total.toFixed(2));
}

function FormatDates()
{
	$('.date').each(function(idx, d){
		var date = new Date($(d).text());
		var day = date.getDate();
		var month = date.getMonth() + 1;
		var year = date.getFullYear();
		var hours = date.getHours();
		var ampm = hours >= 12 ? 'PM' : 'AM';
  		var minutes = date.getMinutes();
  		
  		hours = hours % 12;
  		if(hours == 0) hours = 12;
  		if(hours < 10) hours = "0"+hours;
  		if(minutes < 10) minutes = "0"+minutes;
  		if(day < 10) day = "0"+day;
  		if(month < 10) month = "0"+month;
  		
		var formatted = month+ "/" +day+ "/" +year+ " " +hours+ ":" +minutes+ " "+ampm;
		$(d).text(formatted);
	});
}

function FormatPhone()
{
	$('.phone').each(function(idx, d){
		var num = $(d).text();
		var areaCode = num.substr(0,3);
		var first = num.substr(3,3);
		var second = num.substr(6,4);
  		
		var formatted = "(" +areaCode+ ") " +first+ "-" +second;
		$(d).text(formatted);
	});
}