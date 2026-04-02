/*
Template Name: StarCode & Dashboard Template
File: Leaflet init js (Clean Version بدون Mapbox)
*/

// =====================
// Base Tile (OpenStreetMap)
// =====================
const tileLayer = (map) => {
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
};

// =====================
// Basic Map
// =====================
var mymap = L.map('leaflet-map').setView([51.505, -0.09], 13);
tileLayer(mymap);

// =====================
// Map With Marker
// =====================
var markermap = L.map('leaflet-map-marker').setView([51.505, -0.09], 13);
tileLayer(markermap);

var mapPinIcon = L.icon({
    iconUrl: 'assets/images/map-pin.png',
});

L.marker([51.5, -0.09], { icon: mapPinIcon }).addTo(markermap);

L.circle([51.508, -0.11], {
    color: '#0ab39c',
    fillColor: '#0ab39c',
    fillOpacity: 0.5,
    radius: 500
}).addTo(markermap);

L.polygon([
    [51.509, -0.08],
    [51.503, -0.06],
    [51.51, -0.047]
], {
    color: '#405189',
    fillColor: '#405189',
}).addTo(markermap);

// =====================
// Map With Popups
// =====================
var popupmap = L.map('leaflet-map-popup').setView([51.505, -0.09], 13);
tileLayer(popupmap);

L.marker([51.5, -0.09], { icon: mapPinIcon })
    .addTo(popupmap)
    .bindPopup("<b>Hello world!</b><br />I am a popup.")
    .openPopup();

L.circle([51.508, -0.11], {
    color: '#f06548',
    fillColor: '#f06548',
    fillOpacity: 0.5,
    radius: 500
}).addTo(popupmap).bindPopup("I am a circle.");

L.polygon([
    [51.509, -0.08],
    [51.503, -0.06],
    [51.51, -0.047]
], {
    color: '#405189',
    fillColor: '#405189',
}).addTo(popupmap).bindPopup("I am a polygon.");

// =====================
// Custom Icons Map
// =====================
var customiconsmap = L.map('leaflet-map-custom-icons').setView([51.5, -0.09], 13);
tileLayer(customiconsmap);

var LeafIcon = L.Icon.extend({
    options: {
        iconSize: [45, 45],
        iconAnchor: [22, 94],
        popupAnchor: [-3, -76]
    }
});

var greenIcon = new LeafIcon({
    iconUrl: 'assets/images/logo-sm.png'
});

L.marker([51.5, -0.09], {
    icon: greenIcon
}).addTo(customiconsmap);

// =====================
// Interactive Map
// =====================
var interactivemap = L.map('leaflet-map-interactive-map').setView([37.8, -96], 4);
tileLayer(interactivemap);

function getColor(d) {
    return d > 1000 ? '#405189' :
        d > 500 ? '#516194' :
        d > 200 ? '#63719E' :
        d > 100 ? '#7480A9' :
        d > 50 ? '#8590B4' :
        d > 20 ? '#97A0BF' :
        d > 10 ? '#A8B0C9' :
        '#A8B0C9';
}

function style(feature) {
    return {
        weight: 2,
        opacity: 1,
        color: 'white',
        dashArray: '3',
        fillOpacity: 0.7,
        fillColor: getColor(feature.properties.density)
    };
}

// تأكد إن statesData موجودة
if (typeof statesData !== "undefined") {
    L.geoJson(statesData, { style: style }).addTo(interactivemap);
}

// =====================
// Layer Group Control
// =====================
var cities = L.layerGroup();

L.marker([39.61, -105.02], { icon: mapPinIcon }).bindPopup('Littleton').addTo(cities);
L.marker([39.74, -104.99], { icon: mapPinIcon }).bindPopup('Denver').addTo(cities);
L.marker([39.73, -104.8], { icon: mapPinIcon }).bindPopup('Aurora').addTo(cities);
L.marker([39.77, -105.23], { icon: mapPinIcon }).bindPopup('Golden').addTo(cities);

var grayscale = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
var streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

var layergroupcontrolmap = L.map('leaflet-map-group-control', {
    center: [39.73, -104.99],
    zoom: 10,
    layers: [streets, cities]
});

var baseLayers = {
    "Default": grayscale,
    "Streets": streets
};

var overlays = {
    "Cities": cities
};

L.control.layers(baseLayers, overlays).addTo(layergroupcontrolmap);