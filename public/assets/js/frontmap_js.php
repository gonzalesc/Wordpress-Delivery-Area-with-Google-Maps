<?php 
header("Content-type: text/javascript");

if( !is_numeric($_GET['id']) )
	exit;

$settings = get_post_meta($_GET['id'], 'areamaps_metakey',true);
    
if( $settings ) {
    foreach($settings['coords'] as $array_coords) {
    	$js_array_coords[] = 'new google.maps.LatLng('.$array_coords[0].','.$array_coords[1].')';
    }
?>
	var map_<?php echo $_GET['id']; ?>;

	jQuery(function(){
		initialize_<?php echo $_GET['id']; ?>();
	});

	function initialize_<?php echo $_GET['id']; ?>() {

		var mapOptions = {
			center: new google.maps.LatLng(<?php echo $settings['lat']; ?>, <?php echo $settings['lng']; ?>),
			zoom: <?php echo $settings['zoom']; ?>,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			zoomControl: true,
    		zoomControlOptions: { style: google.maps.ZoomControlStyle.DEFAULT }
		};

		map_<?php echo $_GET['id']; ?> = new google.maps.Map(document.getElementById('areamaps-<?php echo $_GET['id']; ?>'), mapOptions);

	
		polygon = new google.maps.Polygon({
        	paths: [<?php echo implode(",\n",$js_array_coords); ?>],
        	strokeColor: '<?php echo $settings['lcolor']; ?>',
        	strokeOpacity: 0.8,
        	strokeWeight: 3,
        	fillColor: '<?php echo $settings['lcolor']; ?>',
        	fillOpacity: 0.35
    	});
    	
    	polygon.setMap(map_<?php echo $_GET['id']; ?>);

    	map_<?php echo $_GET['id']; ?>.fitBounds(polygon.getBounds());
	}


	google.maps.Polygon.prototype.getBounds = function() {
    	var bounds = new google.maps.LatLngBounds();
    	var paths = this.getPaths();
    	var path;        
    	
    	for (var i = 0; i < paths.getLength(); i++) {
			path = paths.getAt(i);
			
			for (var ii = 0; ii < path.getLength(); ii++) {
				bounds.extend(path.getAt(ii));
			}
		}
	
		return bounds;
	}
<?php } ?>