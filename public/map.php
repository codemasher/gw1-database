<?php
/**
 *
 * @filesource   map.php
 * @created      16.06.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GW1DBwww;

require_once '../vendor/autoload.php';

$lang = 'de';

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>GW Map test</title>
	<link rel="stylesheet" href="./gw-fonts.css">
	<link rel="stylesheet" href="./gwmap.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.4.0/leaflet.css">
</head>
<body>
<div class="gw1map" data-continent="tyria"></div>
<div class="gw1map" data-continent="cantha"></div>
<div class="gw1map" data-continent="elona"></div>
<div class="gw1map" data-continent="presearing"></div>
<div class="gw1map" data-continent="battleisles"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.4.0/leaflet-src.js"></script>
<script src="GWMap.js"></script>
<script>
	const GWMapOptions = {
		targetSelector: '.gw1map',
		apiURL: 'http://localhost/gw1-database/public/geojson.php',
		gwdbURL: 'http://localhost/gw1-database/public/gwdb',
		tilePath: '/img/maps',
		lang:'<?php echo $lang; ?>',
		mapAttribution: 'Imagery: <a href="http://www.guildwars.com/" target="_blank">Guild Wars</a>, &copy; <a href="http://www.arena.net/" target="_blank">ArenaNet</a>',
	};

	((mapOptions) => {

		// check if leaflet is loaded (paranoid)
		if(L.version){

			// override L.TileLayer.getTileUrl() and add a custom tile getter
			L.TileLayer.prototype.getTileUrl = function(tilePoint){

				if(typeof this.options.tileGetter === 'function'){
					return this.options.tileGetter(tilePoint, this._getZoomForUrl());
				}

				return this.options.errorTileUrl;
			};


			let maps = [];

			document.querySelectorAll(mapOptions.targetSelector).forEach(function(e, i){
				maps[i] = new GWMap(e, mapOptions).init();
			});

			console.log(maps);

		}
		else{
			console.log('Leaflet not loaded');
		}

	})(GWMapOptions);
</script>
</body>
</html>
