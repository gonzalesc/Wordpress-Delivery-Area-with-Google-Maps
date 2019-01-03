<?php 
header("Content-type: text/javascript");
$pid = sanitize_key($_GET['id']);

$settings = get_post_meta($pid, 'areamaps_metakey',true);

$lcolor = isset($settings['lcolor']) ? $settings['lcolor'] : $this->options_default['lcolor'];
$lat = isset($settings['lat']) ? $settings['lat'] : $this->options_default['lat'];
$lng = isset($settings['lng']) ? $settings['lng'] : $this->options_default['lng'];
$zoom = isset($settings['zoom']) ? $settings['zoom'] : $this->options_default['zoom'];


/*	Reset coords	*/
foreach($this->options_default['coords'] as $array_default_coords) {
	$symbol_lat = $array_default_coords[0]>0?'+':'';
	$symbol_lng = $array_default_coords[1]>0?'+':'';

	$array_reset_latlng[] = 'new google.maps.LatLng(cLat'.$symbol_lat.$array_default_coords[0].',cLng'.$symbol_lng.$array_default_coords[1].')';
}


if( is_array($settings['coords']) && count($settings['coords'])>0 ) {

	foreach($settings['coords'] as $coords) {		
		if($coords[0] != '' && $coords[1] != '')
			$array_latlng[] = 'new google.maps.LatLng('.$coords[0].','.$coords[1].')';
	}
} else
	$array_latlng = $array_reset_latlng;
?>


jQuery(function(){
	initialize();
	geocoder = new google.maps.Geocoder();
});

var geocoder;
var map;
var coords;
var polygon;
var autocomplete;

function initialize() {

	autocomplete = new google.maps.places.Autocomplete(
						(document.getElementById('areamaps_address')),
						{types: ['geocode']}
					);

	var mapOptions = {
						center: new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>),
						zoom: <?php echo $zoom; ?>,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};

	map = new google.maps.Map(document.getElementById("areamaps_id"), mapOptions);
		
	var center = map.getCenter();
	var cLat = center.lat();
	var cLng = center.lng();
	coords = [<?php echo implode(",\n",$array_latlng); ?>];

	jQuery("input#areamaps_coords").val( coords.toString() );
	jQuery("input#areamaps_lat").val( center.lat() );
	jQuery("input#areamaps_lng").val( center.lng() );
	jQuery("input#areamaps_zoom").val( map.getZoom() );
		
	draw_boundary();
		
	google.maps.event.addListener(map, 'click', function(e) {
		addCoord(e.latLng);

		jQuery("input#areamaps_coords").val( coords.toString() );
    });

    google.maps.event.addListener(map, 'center_changed', function(e) {
    	center = map.getCenter();
		jQuery("input#areamaps_lat").val( center.lat() );
		jQuery("input#areamaps_lng").val( center.lng() );
    });

    google.maps.event.addListener(map, 'zoom_changed', function(e) {
		jQuery("input#areamaps_zoom").val( map.getZoom() );
    });  
}
	
	
function reset_coords(cLat, cLng){
	polygon.setMap(null);
	coords = [<?php echo implode(",\n",$array_reset_latlng); ?>];
}
	
function draw_boundary(){
	var center = map.getCenter();
	var cLat = center.lat();
	var cLng = center.lng();

	polygon = new google.maps.Polygon({
        paths: coords,
        strokeColor: '<?php echo $lcolor; ?>',
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: '<?php echo $lcolor ?>',
        fillOpacity: 0.35
    });
    polygon.setMap(map);
	addMarkers();
}
	

var currentMarker;
var markers = [];
	
function removeMarkers(){
	for(x=0;x<markers.length;x++){
		markers[x].setMap(null);
	}
}

function addMarkers(){
	for(var i = 0; i < coords.length; i++){
		addMarker(coords[i]);
	}
}
	
function addMarker(myLatLng){
	var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
		draggable: true
	});
		
	markers.push(marker);
		
	google.maps.event.addListener(marker, 'dragstart', function() {
		currentMarker = marker.getPosition();
	});
		
	google.maps.event.addListener(marker, 'dragend', function() {
        for(x=0;x<coords.length;x++){
			if(coords[x] == currentMarker){
				coords[x] = marker.getPosition();
			}
		}
		polygon.setPaths(coords);

		jQuery("input#areamaps_coords").val( coords.toString() );
	});
		
	google.maps.event.addListener(marker, 'click', function() {
		currentMarker = marker.getPosition();
		for(x=0;x<coords.length;x++){
			if(coords[x] == currentMarker){
				coords.splice(x, 1);
			}
		}
		marker.setMap(null);	//removes marker
		polygon.setPaths(coords);

		jQuery("input#areamaps_coords").val( coords.toString() );
	});
}
	
function addCoord(point){
	var d1, d2, d, dx, dy;
	var insertAt1, insertAt2;
	for(var i = 0; i < coords.length; i++){
		dx = coords[i].lng() - point.lng();
		dy = coords[i].lat() - point.lat();
		d = (dx*dx) + (dy*dy);

		d = Math.sqrt(d);
			
		if(i > 0){
			if(d < d1){
				d2 = d1;
				d1 = d;
				insertAt2 = insertAt1;
				insertAt1 = i;
			} else if (d < d2){
				d2 = d;
				insertAt2 = i;
			}
		} else {
			d1 = d;
		}
	}
	
	if(insertAt2 < insertAt1)
		insertAt1 = insertAt2;
			
	coords.splice(insertAt1+1,0,point);
	polygon.setPaths(coords);
	removeMarkers();
	addMarkers();
}
	
	
var hits = [];
var lastBetween;
var sHtml = "";
	
function isInside(position){
		
	sHtml = "";
	var points = coords;
	var Lx = position.lng();
	var Ly = position.lat();
		
	for(var i = 0; i < points.length; i++){
		//document.write("x="+getX(points[i])+", y="+getY(points[i])+"<br>");
		var p1 = i;
		var p2 = i+1;
		if(p2 == points.length)
			p2 = 0;
			
		bIntersected( points[p1].lng(), points[p1].lat(), points[p2].lng(), points[p2].lat(), Lx, Ly, Lx+1, Ly+0.001 );
	}
	
	var iLeft = 0;
	var iRight = 0;
	for(i = 0; i < hits.length; i++){
		if(hits[i] <= Lx)
			iLeft++;
		else
			iRight++;
	}
	
	for(i = 0; i <= hits.length+5; i++){
		hits.pop();
	}
	
	sHtml += ("iLeft = "+iLeft+", iRight = "+iRight+"<br>");
	sHtml += ("mod iLeft = "+iLeft%2+", mod iRight = "+iRight%2+"<br>");
	
	if(iLeft%2 == 1 && iRight%2 == 1)
		return true;
	else
		return false;
}
	
function bIntersected(x1a,y1a,x2a,y2a,x1b,y1b,x2b,y2b){
	///////////////////  LINE 1  //////////////////////////
	var ma = (y1a - y2a)/(x1a -x2a);
	//y = mx + b (formula for a line)
	//solve for b
	var ba = y1a - (ma*x1a);
	///////////////////  LINE 2  //////////////////////////
	var mb = (y1b - y2b)/(x1b -x2b);
	//y = mx + b
	//solve for b
	var bb = y1b - (mb*x1b);
	///////////////////  Solve for intersection of X  //////////////////////////
	//use the first point to resolve X
	var xi = (bb - ba)/(ma - mb);
	//solve for yi based on one of the line functions and xi
	var yi = (ma*xi) + ba;
	/////////////////// Is the intersection between the end points? ///////////////
	var iBetween = (x1a - xi)*(xi - x2a);
	if(iBetween  >= 0 && lastBetween != 0){
		hits.push(xi);
	}
	
	lastBetween = iBetween;
}
	
function codeAddress() {
    var address = document.getElementById('areamaps_address').value;
    geocoder.geocode( { 'address': address}, function(results, status) {

		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);

			removeMarkers();
			reset_coords(results[0].geometry.location.lat(), results[0].geometry.location.lng());
			draw_boundary();
		} else {
			console.log('Geocode was not successful for the following reason: ' + status);
		}
    });
}