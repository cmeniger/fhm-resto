(function ($, window, document)
{
    $.fn.addresspicker = function (options)
    {
        var settings = $.extend
        ({
            anchors: {
                map:     'map',
                search:  'map-search',
                address: 'map-address',
                zipcode: 'map-zipcode',
                city:    'map-city',
                country: 'map-country',
                lat:     'map-lat',
                lng:     'map-lng'
            },
            map:     {
                center:                 {
                    lat: 46,
                    lng: 5
                },
                zoom:                   16,
                scrollwheel:            true,
                panControl:             true,
                rotateControl:          true,
                scaleControl:           true,
                streetViewControl:      true,
                mapTypeControl:         true,
                overviewMapControl:     true,
                zoomControl:            true,
                draggable:              true,
                clickableIcons:         true,
                disableDoubleClickZoom: true,
                styles:                 []
            },
            marker:  {
                position: {
                    lat: 46,
                    lng: 5
                },
                visible:  false,
                drag:     true,
                icon:     '/images/marker.png'
            },
            view:    {}
        }, options);
        // Initialisations
        var address = {
            street_number: '',
            postal_code:   '',
            locality:      '',
            country:       ''
        };
        // View mode
        if(typeof settings.view.map != 'undefined')
        {
            settings.anchors.map = settings.view.map;
            settings.map.center.lat = (typeof settings.view.center != 'undefined' && typeof settings.view.center.lat != 'undefined') ? settings.view.center.lat : settings.view.lat;
            settings.map.center.lng = (typeof settings.view.center != 'undefined' && typeof settings.view.center.lng != 'undefined') ? settings.view.center.lng : settings.view.lng;
            settings.map.scrollwheel = (typeof settings.view.scrollwheel != 'undefined') ? settings.view.scrollwheel : settings.map.scrollwheel;
            settings.map.panControl = (typeof settings.view.panControl != 'undefined') ? settings.view.panControl : settings.map.panControl;
            settings.map.rotateControl = (typeof settings.view.rotateControl != 'undefined') ? settings.view.rotateControl : settings.map.rotateControl;
            settings.map.scaleControl = (typeof settings.view.scaleControl != 'undefined') ? settings.view.scaleControl : settings.map.scaleControl;
            settings.map.streetViewControl = (typeof settings.view.streetViewControl != 'undefined') ? settings.view.streetViewControl : settings.map.streetViewControl;
            settings.map.mapTypeControl = (typeof settings.view.mapTypeControl != 'undefined') ? settings.view.mapTypeControl : settings.map.mapTypeControl;
            settings.map.overviewMapControl = (typeof settings.view.overviewMapControl != 'undefined') ? settings.view.overviewMapControl : settings.map.overviewMapControl;
            settings.map.zoomControl = (typeof settings.view.zoomControl != 'undefined') ? settings.view.zoomControl : settings.map.zoomControl;
            settings.map.draggable = (typeof settings.view.draggable != 'undefined') ? settings.view.draggable : settings.map.draggable;
            settings.map.clickableIcons = (typeof settings.view.clickableIcons != 'undefined') ? settings.view.clickableIcons : settings.map.clickableIcons;
            settings.map.disableDoubleClickZoom = (typeof settings.view.disableDoubleClickZoom != 'undefined') ? settings.view.disableDoubleClickZoom : settings.map.disableDoubleClickZoom;
            settings.map.zoom = (typeof settings.view.zoom != 'undefined') ? settings.view.zoom : settings.map.zoom;
            settings.map.styles = (typeof settings.view.styles != 'undefined') ? settings.view.styles : settings.map.styles;
            settings.marker.position.lat = settings.view.lat;
            settings.marker.position.lng = settings.view.lng;
            settings.marker.position.lng = settings.view.lng;
            settings.marker.visible = true;
            settings.marker.drag = false;
            settings.marker.icon = (typeof settings.view.icon != 'undefined') ? settings.view.icon : settings.marker.icon;
        }
        var mapCanvas = document.getElementById(settings.anchors.map);
        var mapSearch = document.getElementById(settings.anchors.search);
        var map = new google.maps.Map(mapCanvas, settings.map);
        var search = mapSearch ? new google.maps.places.Autocomplete(mapSearch) : '';
        var marker = new google.maps.Marker
        ({
            position:  new google.maps.LatLng(settings.marker.position.lat, settings.marker.position.lng),
            map:       map,
            draggable: settings.marker.drag,
            visible:   settings.marker.visible,
            icon:      settings.marker.icon
        });
        // Set position
        if($('#' + settings.anchors.lat).val() && $('#' + settings.anchors.lng).val())
        {
            var location = new google.maps.LatLng($('#' + settings.anchors.lat).val(), $('#' + settings.anchors.lng).val());
            marker.setPosition(location);
            marker.setVisible(true);
            map.setCenter(location);
            map.setZoom(17);
            $('#' + settings.anchors.search).val
            (
                (typeof $('#' + settings.anchors.address).val() != 'undefined' ? $('#' + settings.anchors.address).val() + ', ' : '') +
                (typeof $('#' + settings.anchors.zipcode).val() != 'undefined' ? $('#' + settings.anchors.zipcode).val() + ', ' : '') +
                (typeof $('#' + settings.anchors.city).val() != 'undefined' ? $('#' + settings.anchors.city).val() + ', ' : '') +
                (typeof $('#' + settings.anchors.country).val() != 'undefined' ? $('#' + settings.anchors.country).val() : '')
            );
        }
        // Autocomplete
        if(search)
        {
            google.maps.event.addListener(search, 'place_changed', function ()
            {
                var place = search.getPlace();
                if(!place.geometry)
                {
                    return;
                }
                // Center map
                if(place.geometry.viewport)
                {
                    map.fitBounds(place.geometry.viewport);
                }
                else
                {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                // Add marker
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                // Address components
                for(var i = 0; i < place.address_components.length; i++)
                {
                    address[place.address_components[i].types[0]] = place.address_components[i].long_name;
                }
                // Reset
                settings.anchors.address ? $('#' + settings.anchors.address).val('').trigger('change') : '';
                settings.anchors.zipcode ? $('#' + settings.anchors.zipcode).val('').trigger('change') : '';
                settings.anchors.city ? $('#' + settings.anchors.city).val('').trigger('change') : '';
                settings.anchors.country ? $('#' + settings.anchors.country).val('').trigger('change') : '';
                settings.anchors.lat ? $('#' + settings.anchors.lat).val('').trigger('change') : '';
                settings.anchors.lng ? $('#' + settings.anchors.lng).val('').trigger('change') : '';
                // Update input value
                settings.anchors.address ? $('#' + settings.anchors.address).val(address.street_number + ' ' + address.route).trigger('change') : '';
                settings.anchors.zipcode ? $('#' + settings.anchors.zipcode).val(address.postal_code).trigger('change') : '';
                settings.anchors.city ? $('#' + settings.anchors.city).val(address.locality).trigger('change') : '';
                settings.anchors.country ? $('#' + settings.anchors.country).val(address.country).trigger('change') : '';
                settings.anchors.lat ? $('#' + settings.anchors.lat).val(place.geometry.location.lat()).trigger('change') : '';
                settings.anchors.lng ? $('#' + settings.anchors.lng).val(place.geometry.location.lng()).trigger('change') : '';
            });
        }
        // Drag marker
        if(settings.marker.drag)
        {
            google.maps.event.addListener(marker, 'dragend', function (event)
            {
                // Reset
                settings.anchors.lat ? $('#' + settings.anchors.lat).val('').trigger('change') : '';
                settings.anchors.lng ? $('#' + settings.anchors.lng).val('').trigger('change') : '';
                // Update input value
                settings.anchors.lat ? $('#' + settings.anchors.lat).val(event.latLng.lat()).trigger('change') : '';
                settings.anchors.lng ? $('#' + settings.anchors.lng).val(event.latLng.lng()).trigger('change') : '';
            });
        }
    };
}(jQuery, window, window.document));