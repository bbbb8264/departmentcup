$(document).ready(function(){
	$(".team").click(function(){
		if($(this).children(".teaminformation").css("display") == "none"){
			$(this).children(".teaminformation").slideDown();
		}else{
			$(this).children(".teaminformation").slideUp();
		}
	});
});