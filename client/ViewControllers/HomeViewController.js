$(function(){
	$(window).on("loadscripts", function(){
		SetupGallery($('.gallery'), 3);
		
		$('.primary-button.order-now').click(AddToCart);
		$('.play-button').click(StartVideo);
		$('video').get(0).onended = EndVideo;
		$(window).resize(setDimensions);
	});
});

function SetupGallery(container, id)
{
	var g = {};
	g.nextButton = $('.btn-fwd', container);
	g.backButton = $('.btn-back', container);
	g.mainImage = $('.showcase', container);
	g.totalImages = 4;
	g.imageBase = '../Images/Products/'+id+'/main_';
	g.thumbBase = '../Images/Products/'+id+'/thumb_';
	g.idx = 0;
	
	setImage(g.idx);
	setDimensions();
	
	for(var i = 0; i < g.totalImages; i++)
	{
		$('.thumbnails', container).append('<div rel="'+i+'" style="background-image:url(\''+g.thumbBase+i+'.jpg\');"></div>');
	}
	
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
	
	return g;
}

function setDimensions()
{
	var galleryRatio = 1.80; //600px/333px
	$('.showcase').height($('.showcase').width() * galleryRatio);
	
	var videoRatio = 0.565;
	$('.video').height($('.video').width() * videoRatio);
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

function StartVideo()
{
	$('video').get(0).controls = true;
	$('video').get(0).play();
	$('.play-button').hide();
}

function EndVideo()
{
	$('video').get(0).controls = false;
	$('video').get(0).currentTime = 0;
	$('.play-button').show();
}