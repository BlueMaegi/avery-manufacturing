$(function(){
	$(window).on("loadscripts", function(){
		Stripe.setPublishableKey('pk_test_rX9UFhyDZ7FlZvko24izhxf8');
		LoadCartIntoTemplate();
		shipmentObj = SetupShipping($("#shipping-table"));
		card = SetupCard($("#card-details"));
		$(".primary-button.complete").click(CompletePurchase);
	});
});

var cart = GetCookie();
var keys = SortCart();
var products = [];
products.tax = 0;
products.ship = 0;

var purchase = {};
var card = {};
var shipmentObj = {};
var rates = [];

function LoadCartIntoTemplate()
{
	if(cart == null || keys.length <= 0)
	{
		$("#shipping-table").before("<p class='empty-cart'>Your cart is empty, you cannot proceed with a purchase.<p>");
		return;
	}
	
	$.get(GetLocalUrl("Templates/checkout-item.html"), function(template) {
		$.each(keys, function(idx, id){
			var quantity = parseInt(cart[id]);
			Ajax("Product", {"func":"get", "id":id}, function(data){
				var inner = template;
				var price = (parseFloat((data[0]).price) * quantity).toFixed(2);
				inner = ParseObjectIntoTemplate(data[0], inner);
				inner = inner.replace("[quantity]", quantity);
				$("#order-table").prepend(inner);
				$("#order-table .subtotal").first().text("$ "+price);
				products[id] = price;
				RefreshPrices();
			});
		});
	});
}

function RefreshPrices()
{
	var subtotal = 0;
	for (var item in products) {
		if(products.hasOwnProperty(item) && item != "tax")
			subtotal += parseFloat(products[item]);
	};
	
	if(shipmentObj && shipmentObj.stateField.val() == "NY")
		products.tax = subtotal * 0.08;
	
	subtotal += products.tax;
		
	$("#total").text("$ "+subtotal.toFixed(2));
	$("#ship").text("$ "+products.ship.toFixed(2));
	$("#tax").text("$ "+products.tax.toFixed(2));
}

function SetupShipping(container)
{
	var shipment = {};
	shipment.nameField = $(".name", container);
	shipment.companyField = $(".company", container);
	shipment.emailField = $(".email", container);
	shipment.streetField = $(".street", container);
	shipment.cityField = $(".city", container);
	shipment.stateField = $(".state", container);
	shipment.zipField = $(".zip", container);
	shipment.phoneField = $(".phone", container);
	shipment.rateSelected = false;
	
	$("input", container).change(function(evt){
		var param = evt.target.value;
		
		if(param.length > 0 || evt.target.hasClass('company')) 
			CheckAndSendValues();
		//todo: basic sanitization
	});
	
	function CheckAndSendValues()
	{
		var allReady = true;
		$("input", container).each(function(idx, item){
			if(($(item).val().length <= 0 && !$(item).hasClass('company'))|| $(item).val().length > 200) allReady = false;
		});
		if(!allReady) return; //todo: alert the user
		if(shipment.rateSelected) ResetRates();
		
		var address = {
			"street1":shipment.streetField.val(),
			"city":shipment.cityField.val(),
			"state":shipment.stateField.val(),
			"zip":shipment.zipField.val(),
			"name":shipment.nameField.val(),
			"company":shipment.companyField.val(),
			"email":shipment.emailField.val(),
			"phone":shipment.phoneField.val()
		};
		
		RemoveError();
		Ajax("Purchase", {"func":"shipping", "address":address}, function(data){
			//TODO: show a loading spinner
			SetupRates(data);
		}, $("#column1 h2:nth-of-type(1)"));
	}
	
	function SetupRates(addressId)
	{
		var productId = keys[0];
		ResetRates();
		
		$.get(GetLocalUrl("Templates/ship-rate.html"), function(template) {
			Ajax("Purchase", {"func":"rates", "addressId":addressId, "productId":productId}, function(data){
				$(".no-shipping").hide();
				shipment.id = data[0].shipmentId;
				$(data).each(function(idx, rate){
					var inner = template;
					inner = $.parseHTML(ParseObjectIntoTemplate(rate, inner));
					rates.push(SetupRate(inner, rate));
					$("#rates-table").append(inner);
				});
			});
		});
	}
	
	function SetupRate(container, rateObj)
	{
		var rate = {};
		rate.id = rateObj.id;
		rate.cost = rateObj.cost;
		
		$("input", container).change(function(evt){
			shipment.rateSelected = false;
			shipment.rateId = null;
			if($(evt.target).is(':checked'))
			{
				shipment.rateId = rate.id;
				shipment.rateSelected = true;
				products.ship = parseFloat(rate.cost);
				RefreshPrices();
			}
		});
		
		return rate;
	}
	
	function ResetRates()
	{
		$("#rates-table").empty();
		$("no-shipping").show();
		rates.length = 0;
		shipment.rateId = null;
		shipment.rateSelected = false;
		RefreshPrices();
	}
	
	return shipment;
}


function SetupCard(container)
{
	var card = {};
	card.nameField = $(".name", container);
	card.numberField = $(".number", container);
	card.cscField = $(".csc", container);
	card.expMonthField = $(".exp-m", container);
	card.expYearField = $(".exp-y", container);
	
	$("input", container).change(function(evt){
		var param = evt.target.value;
		if(param.length > 0) CheckAndSendValues();
	});
	$("select", container).change(function(evt){CheckAndSendValues();});
	
	function CheckAndSendValues()
	{
		card.id = null;
		var allReady = true;
		$("input", container).each(function(idx, item){
			if($(item).val().length <= 0|| $(item).val().length > 200) allReady = false;
		});
		$("select", container).each(function(idx, item){
			if(!$(item).val() || $(item).val().length <= 0) allReady = false;
		});
		
		if(!allReady) return; //todo: alert the user
		
		RemoveError();
		Stripe.card.createToken({
		  number: card.numberField.val(),
		  cvc: card.cscField.val(),
		  exp_month: card.expMonthField.val(),
		  exp_year: card.expYearField.val(),
		  name: card.nameField.val()
		}, function(status, cardResult){
			//TODO: show a loading spinner
			if(cardResult.error)
			{
				ShowError("Error: "+cardResult.error.message, $("#column1 h2:nth-of-type(3)"));
			}
			else
				card.id = cardResult["id"];
		});
	}
	
	return card;
}

function CompletePurchase()
{
	RemoveError();
	if(cart == null || keys.length <= 0) 
	{
		ShowError("Error: Your cart is empty. You cannot complete a purchase with nothing in your cart.");
		return; 
	}
	if(rates.length <= 0)
	{
		ShowError("Please enter your shipping address before you place your order.",$("#column1 h2:nth-of-type(1)"));
		return;
	}
	if(!shipmentObj.rateSelected)
	{
		ShowError("Please select a shipping method before you place your order.", $("#column1 h2:nth-of-type(2)"));
		return;
	}
	if(!card.id) 
	{
		ShowError("Please enter a valid payment card to complete your order.", $("#column1 h2:nth-of-type(3)"));
		return;
	}
	
	var selectedRate = $("input[value='"+shipmentObj.rateId+"']").siblings();
	
	var purchase = {
		epLabelId:shipmentObj.rateId,
		cardId: card.id,
		lastFour: card.numberField.val().substr(card.numberField.val().length - 4),
		email: shipmentObj.emailField.val(),
		orderItems:[]
	};
	
	$.each(keys, function(idx, id){
		var qty = parseInt(cart[id]);
		purchase.orderItems.push({productId:id, quantity:qty, taxAmount:"0", discount:"0"});
	});
	
	Ajax("Purchase", {"func":"complete", "purchase":purchase}, function(data){
		console.log(data);
		//TODO: clear cart cookie
		//TODO: navigate to receipt page
	});
}

//Tax Calculation Data: http://www.nolo.com/legal-encyclopedia/50-state-guide-internet-sales-tax-laws.html
//Naturally we need to collect taxes for NY sales at 8.0%
//Most states do not require you to charge sales tax if
//you doesn't have a physical presence in that state

//These states require taxes to be collected if you make more than $10,000 in sales to that state:
//Arkansas, California, Georgia, Illinois, Maine, North Carolina, and Vermont
//These are weird ones:
//Connecticut - $2,000
//Rhode Island - $5,000
//Minnesota - $10,000 or 100 sales







