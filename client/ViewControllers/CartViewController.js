$(function(){
	$(window).on("loadscripts", function(){
		LoadCartIntoTemplate();
	});
});

var cart = GetCookie();
var keys = SortCart();
var products = [];

function LoadCartIntoTemplate()
{
	if(cart == null || keys.length <= 0)
	{
		$("#cart-table").replaceWith("<p class='empty-cart'>Your cart is empty, there is nothing to show.<p>");
		return;
	}

	$.get(GetLocalUrl("Templates/cart-item.html"), function(template) {
		$.each(keys, function(idx, id){
			var quantity = parseInt(cart[id]);
			Ajax("Product", {"func":"get", "id":id}, function(data){
				var inner = template;
				inner = ParseObjectIntoTemplate(data[0], inner);
				inner = inner.replace("[quantity]", quantity);
				products[id] = SetupProductRow($.parseHTML(inner));
				$("#cart-table").append(products[id].element);
				RefreshSubtotal();
			});
		});
	});
}

function DisplayTable()
{
	$("#cart-table tr").remove();
	$.each(keys, function(idx, id){
		if(products.hasOwnProperty(id))
			$("#cart-table").append(products[id].element);
	});
}

function SetupProductRow(row)
{
	var product = {};
	product.element = row;
	product.plusButton = $(row).find(".plus");
	product.minusButton = $(row).find(".minus");
	product.quantityField = $(row).find(".quantity");
	product.removeButton = $(row).find(".remove-from-cart");
	product.priceField = $(row).find(".subtotal");
	product.id = parseInt($(row).attr("rel"));
	product.quantity = parseInt(product.quantityField.val());
	product.unitPrice = parseFloat($(row).find(".subtotal").attr("rel"));
	product.maxQty = 50;
	
	product.quantityField.change(function(evt)
	{
		product.quantity = parseInt(evt.target.value);
		product.RefreshPrice();
	});
	
	product.plusButton.click(function()
	{
		product.quantity++;
		product.RefreshPrice();
	});

	product.minusButton.click(function()
	{
		product.quantity--;
		product.RefreshPrice();
	});
	
	product.removeButton.click(function()
	{
		product.quantity = 0;
		$(product.element).remove();
		delete cart[product.id];
		SetCookie(cart);
		delete products[product.id];
		RefreshSubtotal();
	});
	
	product.RefreshPrice = function()
	{
		if(isNaN(product.quantity)) product.quantity = 1;
		product.quantity = Math.min(product.quantity, product.maxQty );
		product.quantity = Math.max(product.quantity, 1);
		
		if(product.quantity >= product.maxQty) 
			$(product.plusButton).attr("disabled", "disabled");
		if(product.quantity <= 1)
			$(product.minusButton).attr("disabled", "disabled");
		$(product.quantityField).val(product.quantity);
		
		$(product.priceField).text("$"+(product.unitPrice * product.quantity).toFixed(2));
		cart[product.id] = product.quantity;
		SetCookie(cart);
		RefreshSubtotal();
	}
	
	product.RefreshPrice();
	return product;
}

function RefreshSubtotal()
{
	var subtotal = 0;
	for(var p in products){
		var row = products[p];
		subtotal += row.unitPrice * row.quantity;
	};
	$("#amount").text(subtotal.toFixed(2));
}
