var quantity = 1;
var max = 50;
$(function(){
	$(window).on("loadscripts", function(){
		$(".minus").click(DecreaseQuantity);
		$(".plus").click(IncreaseQuantity);
		$("input.quantity").change(OnTextChange);
		$(".add-to-cart").click(AddToCart);
	});
});

function IncreaseQuantity()
{
	quantity++;
	ValidateQuantity();
}

function DecreaseQuantity()
{
	quantity--;
	ValidateQuantity();
}

function OnTextChange(evt)
{
	quantity = parseInt(evt.target.value);
	ValidateQuantity();
}

function ValidateQuantity()
{
	if(isNaN(quantity)) quantity = 1;
	quantity = Math.min(quantity, max);
	quantity = Math.max(quantity, 1);
	if(quantity >= max) 
		$(".plus").attr("disabled", "disabled");
	if(quantity <= 1)
		$(".minus").attr("disabled", "disabled");
	$("input.quantity").val(quantity);
}

function AddToCart(evt)
{
	var id = parseInt($(evt.target).attr("rel"));
	var cart = GetCookie();
	if(!isNaN(id))
	{
		if(cart.hasOwnProperty(id))
			cart[id] += quantity;
		else
			cart[id] = quantity;	
		SetCookie(cart);
	}
	window.location = GetLocalUrl("cart.html");
}
