
var menu = new TimelineMax({paused:true, reversed:true}) 
var map = new TimelineMax({paused:true, reversed:true})
	
menu
.to("#burger1", .1, {rotation: 45, transformOrigin: "left 50%", ease:Power2.easeInOut}, "cross")
.to("#burger2", .1, {autoAlpha: 0}, "cross")
.to("#burger3", .1, {rotation: -45, transformOrigin: "left 50%", ease:Power2.easeInOut}, "cross")
.to("#menu", 0.25, {x: "-300px", ease:Power2.easeInOut})
.to("#nav", 0.25, {x: "-300px", ease:Power2.easeInOut})
	
map 
.to("#map", 0.75, {x: "-100%", ease:Power2.easeInOut})
.to("#form", 0.75, {x: "-100%", ease:Power2.easeInOut})
	
	function menuIn() {
	menu.reversed() ? menu.play() : menu.reverse();	
	}
	function mapIn() {
	map.reversed() ? map.play() : map.reverse();	
	}

    
	$(".btn-group-toggle input").button('toggle');