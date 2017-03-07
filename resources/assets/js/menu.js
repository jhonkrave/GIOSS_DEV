 alert("adfsd");
$(document).ready(function(){
 
  $('.chaffle').chaffle({
      speed: 20,
      time: 140
    });
  $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

  
});




