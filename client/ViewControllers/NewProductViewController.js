$(function(){
	$(window).on("loadscripts", function(){
		product = SetupForm($("div.center-main.form"));
		$(".primary-button.save").click(Save);
	});
});

var product = {};
var maxIntVal = 999;

function SetupForm(container)
{
	var product = {};
	product.element = container;
	
	$("input", container).change(function(evt){
		var field = $(evt.target);
		var result = field.val();
		
		if(field.hasClass("int"))
		{
			result = SnapToInt(result);
		}
		else if(field.hasClass("float"))
		{
			result = SnapToFloat(result);
		}
		else
		{
			result = result.substr(0, 500);
		}
		
		product[field.attr("rel")] = result;
		field.val(result);
	});
	
	return product;
}

function Save()
{
	RemoveError();
	//console.log(product);
	
	var ready = true;
	$("input", product.element).each(function(idx, i){
		var prop = $(i).attr("rel");
		if(!product.hasOwnProperty(prop))
		{
			ShowError("All fields are required. Please enter a value for the "+prop+" before saving.");
			ready = false;
			return false;
		}
		
		if(prop != "quantity" && ($(i).hasClass("int") || $(i).hasClass("float")))
		{
			if(product[prop] == 0)
			{
				ShowError("Invalid Value: "+prop+" cannot be zero");
				ready = false;
				return false;
			}
		}
		
		if(product[prop].length == 0 && (prop == "name" || prop =="description"))
		{
			ShowError("All fields are required. Please enter a value for the "+prop+" before saving.");
			ready = false;
			return false;
		}
	});
	
	if(!ready) return;
	
	var ajaxProd = {
		name: product.name,
		price: product.price,
		description: product.description,
		enabled: true
	};
	
	var ajaxParcel = {
		height: product.height,
		width: product.width,
		length: product.length,
		weight: product.weight
	};
	
	var ajaxInventory = {
		locationId: 1, //NOTE: hard-coded location here
		quantity: product.quantity
	};
	
	var authId = localStorage.getItem("id");
	var tok = sessionStorage.getItem("tolkien");
	
	Ajax("Product", {"func":"create", "authId":authId, "auth":tok, "product":ajaxProd, "parcel":ajaxParcel}, function(data){
		ajaxInventory.productId = data[0].id;
		Ajax("Inventory", {"func":"create", "authId":authId, "auth":tok, "inventory":ajaxInventory}, function(){
			window.location.href = GetLocalUrl("/admin/inventory.html");
		});
	});
}

function SnapToInt(val)
{
	val = parseInt(val);
	if(isNaN(val)) val = 0;
	val = Math.min(val, maxIntVal );
	val = Math.max(val, 0);
	
	return val;
}

function SnapToFloat(val)
{
	val = parseFloat(val);
	if(isNaN(val)) val = 0;
	val = Math.min(val, maxIntVal );
	val = Math.max(val, 0);
	
	return val.toFixed(2);
}






