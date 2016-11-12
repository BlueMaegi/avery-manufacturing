$(function(){
	$(window).on("loadscripts", function(){
	
		inventoryId = GetUrlParam('id');
		if(!inventoryId)
			LoadItemsTemplate();
	});
});

function LoadItemsTemplate()
{	
	var authId = localStorage.getItem("id");
	var tok = sessionStorage.getItem("tolkien");

	$.get(GetLocalUrl("Templates/inventory-item.html"), function(template) {
		Ajax("Inventory", {"func":"get", "locationId":"1", "authId":authId, "auth":tok}, function(data){
			$(data).each(function(idx, o){
				var inner = template;
				inner = ParseObjectIntoTemplate(o, inner);
				$("#inventory-table").append(inner);
				SetupInventoryRow($("#inventory-table tr").last());
			});
		});
	});
}

function SetupInventoryRow(row)
{
	var product = {};
	product.element = row;
	product.plusButton = $(row).find(".plus");
	product.minusButton = $(row).find(".minus");
	product.quantityField = $(row).find(".quantity");
	product.priceField = $(row).find(".price");
	product.enabledField = $(row).find(".enable");
	product.saveButton = $(row).find(".save");
	product.id = parseInt($(row).attr("rel"));
	product.quantity = parseInt(product.quantityField.val());
	product.productId = parseInt($(row).find(".productId").attr("rel"));
	product.locationId = parseInt($(row).find(".locationId").attr("rel"));
	product.price = parseFloat(product.priceField.val());
	product.description = $(row).find(".description").attr("rel");
	product.epParcelId = $(row).find(".epParcelId").attr("rel");
	product.name = $(row).find(".name").attr("rel");
	product.originalPrice =  parseFloat(product.priceField.val());
	product.originalQuantity = parseInt(product.quantityField.val());
	product.maxQty = 999;
	product.maxPrice = 999.99;
	product.enabled = true;
	product.hasChanged = false;
	
	product.quantityField.change(function(evt)
	{
		product.quantity = parseInt(evt.target.value);
		product.RefreshQty();
		product.MarkChanged(true);
	});
	
	product.plusButton.click(function()
	{
		product.quantity++;
		product.RefreshQty();
		product.MarkChanged(true);
	});

	product.minusButton.click(function()
	{
		product.quantity--;
		product.RefreshQty();
		product.MarkChanged(true);
	});
	
	product.RefreshQty = function()
	{
		if(isNaN(product.quantity)) product.quantity = 0;
		product.quantity = Math.min(product.quantity, product.maxQty );
		product.quantity = Math.max(product.quantity, 0);
		
		if(product.quantity >= product.maxQty) 
			$(product.plusButton).attr("disabled", "disabled");
		if(product.quantity <= 0)
			$(product.minusButton).attr("disabled", "disabled");
		$(product.quantityField).val(product.quantity);
	}
	
	product.enabledField.change(function(evt){
		product.CheckEnabled();
		product.MarkChanged(true);
	});
	
	product.CheckEnabled = function()
	{
		if($(product.enabledField).is(":checked"))
			product.enabled = true;
		else
			product.enabled = false;
	};
	
	product.priceField.change(function(evt)
	{
		product.price = parseFloat(evt.target.value);
		product.RefreshPrice();
		product.MarkChanged(true);
	});
	
	product.RefreshPrice = function()
	{
		if(isNaN(product.price)) product.price = (0).toFixed(2);
		product.price = Math.min(product.price, product.maxPrice).toFixed(2);
		product.price = Math.max(product.price, 0).toFixed(2);

		$(product.priceField).val(product.price);
	}
	
	product.MarkChanged = function(changed)
	{
		product.hasChanged = changed;
		if(changed)
		{
			$(product.element).css("background-color", "rgb(255, 255, 210)");
		}
		else
		{
			$(product.element).css("background-color", "white");
		}
	}
	
	product.saveButton.click(function(){
		if(!product.hasChanged)
			return;
		
		var inventory = {
			"id":product.id,
			"productId":product.productId,
			"locationId":product.locationId,
			"quantity":product.quantity
		};
		
		var prod = {
			"id":product.productId,
			"price":product.price,
			"name":product.name,
			"description":product.description,
			"epParcelId":product.epParcelId,
			"enabled": product.enabled? "1" : "0"
		}
		
		var history = {
			"inventoryId":product.id
		};
		
		var authId = localStorage.getItem("id");
		var tok = sessionStorage.getItem("tolkien");
		
		Ajax("Product", {"func":"update", "authId":authId, "auth":tok, "product":prod}, function(){});
		Ajax("Inventory", {"func":"update", "authId":authId, "auth":tok, "inventory":inventory}, function(){
			product.MarkChanged(false);
		});
		
		if(product.originalPrice != product.price)
		{
			history.eventType = 4;
			history.quantity = product.quantity;
			Ajax("InventoryHistory", {"func":"create", "authId":authId, "auth":tok, "inventoryHistory":history}, function(){
				product.originalPrice = product.price;
			});
		}
		
		if(product.originalQuantity != product.quantity)
		{
			history.eventType = 1;
			history.quantity = product.quantity - product.originalQuantity;
			Ajax("InventoryHistory", {"func":"create", "authId":authId, "auth":tok, "inventoryHistory":history}, function(){
				product.originalQuantity = product.quantity;
			});
		}
		
	});
	
	
	
	product.CheckEnabled();
}