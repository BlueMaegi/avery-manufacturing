$(function(){
	AutoloadTemplates(); 
	var id = GetUrlParam('id');
	AutoloadDataTemplates(id);
});

function AutoloadTemplates()
{
	var elements = $(".template.autoload");
	$(elements).each(function(idx, ele){
		$.get("Templates/"+$(ele).attr("rel")+".html", function(data) {
			$(ele).replaceWith(data);
		});
	});
}

function AutoloadDataTemplates(id)
{
	var elements = $(".data-template.autoload");
	$(elements).each(function(idx, container){
		LoadDataTemplate(container, id)
	});
}

function LoadDataTemplate(container, id)
{
	var dataType = $(container).attr("rel");
	var dataEndpoint = dataType.charAt(0).toUpperCase() + dataType.substr(1).toLowerCase();//todo:capitalize
	var postData = {"func":"get"};
	if(id) postData["id"] = id;
	var templateEndpoint = "Templates/"+dataType+(id?"":"s")+".html";
	
	Ajax(dataEndpoint, postData, function(data){
	$.get(templateEndpoint, function(template) {
		if(id)
		{
			var p = data[0];
			template = ParseObjectIntoTemplate(p, template);
			$(container).append(template);
		}
		else
		{
			$(data).each(function(idx, p){
				var inner = template;
				inner = ParseObjectIntoTemplate(p, inner);
				$(container).append(inner);
			});
		}
	});});
}

function ParseObjectIntoTemplate(data, template)
{
	for (var property in data) {
		if (data.hasOwnProperty(property)) {
			template = template.replace("["+property+"]", data[property]);
		}
	}
	
	return template;
}

function Ajax(object, dataSet, callback)
{
	//TODO: catch 404 and 403 and reroute...perhaps others too
	$.ajax({
	  url: "DataControllers/"+object+"DataController.php",
	  method: "POST",
	  data: dataSet
	}).done(function(data){
		callback(JSON.parse(data).data);
	});
}
	
function GetUrlParam(name){
    var url = window.location.href;    
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
    var results = regex.exec(url);
    
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}	