var shown = true;
var sign_toggle = function() {
   if(shown) {
	   $(".blink").css("color","#0fc5b5");
	   shown = false;
   } else {
	   $(".blink").css("color","#ec0e03");
	   shown = true;
   }
}

setInterval(sign_toggle, 500);

$(function() {
	//$("#sign").signature();
	$("#clear").click(function() {
		$("#sign").signature("clear");
	});
	$("#sign").signature({syncField: "#signatureJSON"});
});