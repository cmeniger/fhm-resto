!function(e,o,a){e.fn.addresspicker=function(o){var n=e.extend({anchors:{map:"map",search:"map-search",address:"map-address",zipcode:"map-zipcode",city:"map-city",country:"map-country",lat:"map-lat",lng:"map-lng"},map:{center:{lat:46,lng:5},zoom:16,scrollwheel:!0,panControl:!0,rotateControl:!0,scaleControl:!0,streetViewControl:!0,mapTypeControl:!0,overviewMapControl:!0,zoomControl:!0,draggable:!0,clickableIcons:!0,disableDoubleClickZoom:!0,styles:[]},marker:{position:{lat:46,lng:5},visible:!1,drag:!0,icon:"/images/marker.png"},view:{}},o),r={street_number:"",postal_code:"",locality:"",country:""};"undefined"!=typeof n.view.map&&(n.anchors.map=n.view.map,n.map.center.lat="undefined"!=typeof n.view.center&&"undefined"!=typeof n.view.center.lat?n.view.center.lat:n.view.lat,n.map.center.lng="undefined"!=typeof n.view.center&&"undefined"!=typeof n.view.center.lng?n.view.center.lng:n.view.lng,n.map.scrollwheel="undefined"!=typeof n.view.scrollwheel?n.view.scrollwheel:n.map.scrollwheel,n.map.panControl="undefined"!=typeof n.view.panControl?n.view.panControl:n.map.panControl,n.map.rotateControl="undefined"!=typeof n.view.rotateControl?n.view.rotateControl:n.map.rotateControl,n.map.scaleControl="undefined"!=typeof n.view.scaleControl?n.view.scaleControl:n.map.scaleControl,n.map.streetViewControl="undefined"!=typeof n.view.streetViewControl?n.view.streetViewControl:n.map.streetViewControl,n.map.mapTypeControl="undefined"!=typeof n.view.mapTypeControl?n.view.mapTypeControl:n.map.mapTypeControl,n.map.overviewMapControl="undefined"!=typeof n.view.overviewMapControl?n.view.overviewMapControl:n.map.overviewMapControl,n.map.zoomControl="undefined"!=typeof n.view.zoomControl?n.view.zoomControl:n.map.zoomControl,n.map.draggable="undefined"!=typeof n.view.draggable?n.view.draggable:n.map.draggable,n.map.clickableIcons="undefined"!=typeof n.view.clickableIcons?n.view.clickableIcons:n.map.clickableIcons,n.map.disableDoubleClickZoom="undefined"!=typeof n.view.disableDoubleClickZoom?n.view.disableDoubleClickZoom:n.map.disableDoubleClickZoom,n.map.zoom="undefined"!=typeof n.view.zoom?n.view.zoom:n.map.zoom,n.map.styles="undefined"!=typeof n.view.styles?n.view.styles:n.map.styles,n.marker.position.lat=n.view.lat,n.marker.position.lng=n.view.lng,n.marker.position.lng=n.view.lng,n.marker.visible=!0,n.marker.drag=!1,n.marker.icon="undefined"!=typeof n.view.icon?n.view.icon:n.marker.icon);var t=a.getElementById(n.anchors.map),l=a.getElementById(n.anchors.search),i=new google.maps.Map(t,n.map),c=l?new google.maps.places.Autocomplete(l):"",s=new google.maps.Marker({position:new google.maps.LatLng(n.marker.position.lat,n.marker.position.lng),map:i,draggable:n.marker.drag,visible:n.marker.visible,icon:n.marker.icon});if(e("#"+n.anchors.lat).val()&&e("#"+n.anchors.lng).val()){var g=new google.maps.LatLng(e("#"+n.anchors.lat).val(),e("#"+n.anchors.lng).val());s.setPosition(g),s.setVisible(!0),i.setCenter(g),i.setZoom(17),e("#"+n.anchors.search).val(("undefined"!=typeof e("#"+n.anchors.address).val()?e("#"+n.anchors.address).val()+", ":"")+("undefined"!=typeof e("#"+n.anchors.zipcode).val()?e("#"+n.anchors.zipcode).val()+", ":"")+("undefined"!=typeof e("#"+n.anchors.city).val()?e("#"+n.anchors.city).val()+", ":"")+("undefined"!=typeof e("#"+n.anchors.country).val()?e("#"+n.anchors.country).val():""))}c&&google.maps.event.addListener(c,"place_changed",function(){var o=c.getPlace();if(o.geometry){o.geometry.viewport?i.fitBounds(o.geometry.viewport):(i.setCenter(o.geometry.location),i.setZoom(17)),s.setPosition(o.geometry.location),s.setVisible(!0);for(var a=0;a<o.address_components.length;a++)r[o.address_components[a].types[0]]=o.address_components[a].long_name;n.anchors.address?e("#"+n.anchors.address).val("").trigger("change"):"",n.anchors.zipcode?e("#"+n.anchors.zipcode).val("").trigger("change"):"",n.anchors.city?e("#"+n.anchors.city).val("").trigger("change"):"",n.anchors.country?e("#"+n.anchors.country).val("").trigger("change"):"",n.anchors.lat?e("#"+n.anchors.lat).val("").trigger("change"):"",n.anchors.lng?e("#"+n.anchors.lng).val("").trigger("change"):"",n.anchors.address?e("#"+n.anchors.address).val(r.street_number+" "+r.route).trigger("change"):"",n.anchors.zipcode?e("#"+n.anchors.zipcode).val(r.postal_code).trigger("change"):"",n.anchors.city?e("#"+n.anchors.city).val(r.locality).trigger("change"):"",n.anchors.country?e("#"+n.anchors.country).val(r.country).trigger("change"):"",n.anchors.lat?e("#"+n.anchors.lat).val(o.geometry.location.lat()).trigger("change"):"",n.anchors.lng?e("#"+n.anchors.lng).val(o.geometry.location.lng()).trigger("change"):""}}),n.marker.drag&&google.maps.event.addListener(s,"dragend",function(o){n.anchors.lat?e("#"+n.anchors.lat).val("").trigger("change"):"",n.anchors.lng?e("#"+n.anchors.lng).val("").trigger("change"):"",n.anchors.lat?e("#"+n.anchors.lat).val(o.latLng.lat()).trigger("change"):"",n.anchors.lng?e("#"+n.anchors.lng).val(o.latLng.lng()).trigger("change"):""})}}(jQuery,window,window.document);