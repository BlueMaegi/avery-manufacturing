$(function(){
	$(window).on("loadscripts", function(){
		FormatDates();
	});
});

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