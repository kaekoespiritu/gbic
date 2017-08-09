$(function(){

    $('.dropdown').hover(function() {
        $(this).addClass('open');
    },
    function() {
        $(this).removeClass('open');
    });

   if($('#employees.dropdown').hasClass('active'))
   	{
   		$('#employees.dropdown').hover(function()
   		{
   			$(this).addClass('open');
   		},
   		function()
   		{
   			$(this).removeClass('open');
   		});
   	}
   	
   	if($('#payroll.dropdown').hasClass('active'))
   	{
   		$('#payroll.dropdown').hover(function()
   		{
   			$(this).addClass('open');
   		},
   		function()
   		{
   			$(this).removeClass('open');	
   		});
   	}
    
});