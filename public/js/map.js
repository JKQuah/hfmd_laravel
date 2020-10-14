//Creating a map in dashboard.blade
var map = new ol.Map({
    target: 'map',
    layers: [
        new ol.layer.Tile({
            source: new ol.source.OSM()
        })
    ],
    view: new ol.View({
        // MALAYSIA LONG, LAT (109.45547499999998, 4.1093195)
        center: ol.proj.fromLonLat([109.45547499999998, 4.1093195]),
        zoom: 5
    })
});