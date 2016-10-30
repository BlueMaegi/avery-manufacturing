$(function(){
	$(window).on("loadscripts", function(){
		FormatDates();
		orderId = GetUrlParam('id');
		if(orderId)
		{
			LoadItemsTemplate();
			LoadShipmentsTemplate();
			if(!$(".data-template[rel='order']").hasClass('autoload'))
			{
				LoadDataTemplate($(".data-template[rel='order']"), orderId)
			}
			
			$(".secondary-button.ship").click(function(){
				var authId = localStorage.getItem("id");
				var tok = sessionStorage.getItem("tolkien");
				Ajax("Purchase", {"func":"backorder", "orderId":orderId, "authId":authId, "auth":tok}, function(){
					window.location.reload();
				});
			});
			
			$(".secondary-button.refund").click(function()
			{
				var total = parseFloat($("span.total").text());
				var str = $("input.refund").val();
				if(str.indexOf("$") >= 0) str = str.substr(str.indexOf("$") +1);
				var amount = parseFloat(str);
				
				if(amount <= 0 || amount > total)
					return false;
				
				var authId = localStorage.getItem("id");
				var tok = sessionStorage.getItem("tolkien");	
				Ajax("Purchase", {"func":"refund", "orderId":orderId, "amount":amount, "authId":authId, "auth":tok}, function(){
					window.location.reload();
				});
			});
		}
	});
});

var orderId;
var backOrder = false;

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

function FormatStatus()
{
	$('.status').each(function(idx, s){
		var status = parseInt($(s).text());
		var statusText = "";
		switch(status){
			case 0: statusText = "New";
				break;
			case 1: statusText = "In-Progress";
				break;
			case 2: statusText = "Shipped";
				break;
			case 3: statusText = "Error";
				break;	
			case 5: statusText = "Back-Order";
				break;
		}
		
		$(s).text(statusText);
	});
}

function LoadItemsTemplate()
{	
	var authId = localStorage.getItem("id");
	var tok = sessionStorage.getItem("tolkien");

	$.get(GetLocalUrl("Templates/order-item.html"), function(template) {
		Ajax("OrderItem", {"func":"get", "orderId":orderId, "authId":authId, "auth":tok}, function(data){
			$(data).each(function(idx, o){
				var inner = template;
				var price = (parseFloat(o.price) * parseFloat(o.quantity)).toFixed(2);
				inner = ParseObjectIntoTemplate(o, inner);
				$("#order-item-table").prepend(inner);
				$("#order-item-table .subtotal span").first().text(price);
				RefreshPrices();
			});
		});
	});
}

function LoadShipmentsTemplate()
{
	var authId = localStorage.getItem("id");
	var tok = sessionStorage.getItem("tolkien");
	
	$.get(GetLocalUrl("Templates/order-shipment.html"), function(template) {
		Ajax("Shipment", {"func":"get", "orderId":orderId, "authId":authId, "auth":tok}, function(data){
			FormatDates();
			$(data).each(function(idx, o){
				var inner = template;
				inner = ParseObjectIntoTemplate(o, inner);
				$("#shipment-table").prepend(inner);
				RefreshPrices();
				FormatStatus();
				if(o.status == 5)
				{
					$("div.secondary-button.ship").show();
					backOrder = true;
				}
			});
		});
	});
}

function RefreshPrices()
{
	var taxTotal = 0;
	var total = 0;
	var refundTotal = parseFloat($("p.refund").text());
	
	$("td.tax").each(function(idx, p)
	{
		taxTotal += parseFloat($(p).attr("rel"));
	});
	
	$("span.cost").each(function(idx, s)
	{
		total += parseFloat($(s).text());
	});
	
	total += taxTotal;
	$("span.total-tax").text(taxTotal.toFixed(2));
	$("span.total").text(total.toFixed(2));
	$("span.total-refund").text(refundTotal.toFixed(2));
}