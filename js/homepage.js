$(document).ready(function() {
	$('#fullpage').fullpage({
    anchors: ['home', /*'about',*/ 'quote', 'weather', 'wiki', 'snake', 'contact'], /* add anchor to be asigned to each created section */
    //menu: '#navList',
    //navigation: true,
    //navigationPosition: left,
    //paddingTop: '52px', //prevents fullPage.js sections covering #navbar
		fixedElements: '#navbar', //keeps #navbar at top of screen on all sections
		slidesNavigation: true,
		verticalCentered: true,
    recordHistory: false,
		/* functions to add active to menu items on scroll */
		onLeave: function(index, nextIndex, direction){
			var leavingSection = $(this);
			if(index == 1 && direction =='down'){
				$(".nav-active").removeClass("nav-active");
				$("#portfolio-nav").addClass("nav-active");
			}
			if(index == 5 && direction =='down'){
				$(".nav-active").removeClass("nav-active");
				$("#contact-nav").addClass("nav-active");
			}
			if(index == 2 && direction == 'up'){
				$(".nav-active").removeClass("nav-active");
				//$("#home-nav").addClass("nav-active"); //do not want name as active
			}
			else if(index == 6 && direction == 'up'){
				$(".nav-active").removeClass("nav-active");
				$("#portfolio-nav").addClass("nav-active");
			}
		}
  });

	/* onclick functions to act as top navigation */
	$(document).on('click', '#home-nav', function(){
  	$.fn.fullpage.moveTo('home', 0);
		$(".nav-active").removeClass("nav-active");
		//$("#home-nav").addClass("nav-active"); //do not want name as active
	});
	$(document).on('click', '#contact-nav', function(){
		$.fn.fullpage.moveTo('contact', 0);
		$(".nav-active").removeClass("nav-active");
		$("#contact-nav").addClass("nav-active");
	});
	$(document).on('click', '#portfolio-nav', function(){
		$.fn.fullpage.moveTo('quote', 0);
		$(".nav-active").removeClass("nav-active");
		$("#portfolio-nav").addClass("nav-active");
	});
	/* changes active menu item on scroll */
	//FIND BETTER WAY TO DO THIS

	//set contact page map
	var contactLat = 43.6532;
	var contactLon = -79.3832;
	function contactMap(lat, lon) {
    var contactMapOptions = {
      zoom: 8,
			disableDefaultUI: true,
      center: new google.maps.LatLng(lat, lon),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      gestureHandling: 'greedy',
      scrollwheel: false,
			draggable: false
    }
    var contactMap = new google.maps.Map(document.getElementById("contact-map"), contactMapOptions);
    var contactMarker = new google.maps.Marker({
      position: new google.maps.LatLng(lat, lon),
      map: contactMap,
      title: 'Job Seeking Location'
    });
    contactMarker.setMap(contactMap);
		google.maps.event.addDomListener(window, "resize", function() {
			var contactMapCenter = contactMap.getCenter();
			google.maps.event.trigger(contactMap, "resize");
			contactMap.setCenter(contactMapCenter);
		});
  }
	contactMap(contactLat, contactLon);
});
