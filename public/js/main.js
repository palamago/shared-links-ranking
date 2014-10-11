$(document).ready(function(){

	$('.tooltip-simple').tooltip();

/*	var SPARKLINESDATA = [];

	if(IDs && FILTER){
		$.getJSON('/api/sparklines/'+IDs.join(',')+'/'+FILTER)
		.then(function(data){
			SPARKLINESDATA = data;
			renderSparklines();
		});
		
	}

	function renderSparklines(){
		$.each(SPARKLINESDATA,function(i,e){
			if(e[0]!=0){
				e.unshift(0);
			}
			$("#sparkline-"+i).sparkline(e, 
			{
				type: 'line',
			    width: $("#sparkline-"+i).parent().width(),
			    height: '50'
		    });
		});
	}

	$(window).bind('resize', function(e)
	{
	    window.resizeEvt;
	    $(window).resize(function()
	    {
	        clearTimeout(window.resizeEvt);
	        window.resizeEvt = setTimeout(function()
	        {
	            renderSparklines();
	        }, 250);
	    });
	});
*/
});