$(function(){
	$(window).on("loadscripts", function(){
		SetupGallery($('.gallery'), 3);
		
		$('.primary-button.order-now').click(AddToCart);
	});
});

function SetupGallery(container, id)
{
	var g = {};
	g.nextButton = $('.btn-fwd', container);
	g.backButton = $('.btn-back', container);
	g.mainImage = $('.showcase', container);
	g.totalImages = 5;
	g.imageBase = '../Images/Products/'+id+'/main_';
	g.thumbBase = '../Images/Products/'+id+'/thumb_';
	g.idx = 0;
	
	setImage(g.idx);
	setDimensions();
	
	for(var i = 0; i < g.totalImages; i++)
	{
		$('.thumbnails', container).append('<div rel="'+i+'" style="background-image:url(\''+g.thumbBase+i+'.jpg\');"></div>');
	}
	
	$(window).resize(setDimensions);
	
	$('.thumbnails div').click(function(evt){
		setImage($(evt.target).attr('rel'));
	});
	$('.thumbnails div').hover(function(thumb){
		$(thumb.target).css("opacity", "1.0");
	},
	function(thumb){
		$(thumb.target).css("opacity", "0.6");
	});
	
	g.nextButton.click(function(){
		if((g.idx + 1) >= g.totalImages)
			setImage(0);
		else
			setImage(g.idx + 1);
	});
	
	g.backButton.click(function(){
		if((g.idx - 1) < 0)
			setImage(g.totalImages - 1);
		else
			setImage(g.idx - 1);
	});
	
	g.mainImage.hover(function(){
		g.nextButton.show();
		g.backButton.show();
	},
	function(){
		g.nextButton.hide();
		g.backButton.hide();
	});
	
	function setImage(idx)
	{
		$(g.mainImage).css('background-image', "url('"+g.imageBase+idx+".jpg')");
		$('.thumbnails div').css("opacity", "0.6");
		$('.thumbnails div[rel="'+idx+'"]').css("opacity", "1.0");
		g.idx = idx;
	}
	
	function setDimensions()
	{
		var ratio = 1.80; //600px/333px
		$('.showcase', container).height($('.showcase').width() * ratio);
	}
	
	return g;
}

function AddToCart(evt)
{
	var id = parseInt($(evt.target).attr("rel"));
	var quantity = 1;
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