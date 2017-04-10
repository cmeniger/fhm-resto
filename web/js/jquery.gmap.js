(function ($, window, document)
{
    function Plugin(settings)
    {
        this.settings = settings;
        this.page = 1;
        this.step = 0;
        this.count = 0;
        this.current = 0;
        this.map = null;
        this.markers = [];
        this.clusterer = null;
        this.play = true;
        this.init();
    }

    Plugin.prototype = {
        init:          function ()
                       {
                           var parent = this;
                           this.mapCanvas = document.getElementById(this.settings.anchors.map);
                           this.map = new google.maps.Map(this.mapCanvas, this.settings.map);
                           this.infowindow = new google.maps.InfoWindow();
                           this.mapCanvas.gMap = this.map;
                           if(this.settings.map.cluster)
                           {
                               this.clusterer = new MarkerClusterer(this.map, [], this.settings.cluster);
                           }
                           this.initCounter();
                           this.initAroundme();
                       },
        initCounter:   function ()
                       {
                           var parent = this;
                           $.ajax
                           ({
                               type:    'GET',
                               url:     parent.settings.url.counter,
                               success: function (data)
                                        {
                                            parent.page = data.page;
                                            parent.step = data.step;
                                            parent.count = data.count;
                                            parent.addCounter();
                                            parent.addMarkers();
                                        }
                           });
                       },
        initAroundme:  function ()
                       {
                           var parent = this;
                           if(parent.settings.map.aroundme)
                           {
                               var marker = new google.maps.Marker
                               ({
                                   position:  null,
                                   map:       parent.map,
                                   draggable: false,
                                   visible:   false,
                                   icon:      '/images/marker-aroundme.png'
                               });
                               var success = function (position)
                               {
                                   var location = new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
                                   marker.setPosition(location);
                                   marker.setVisible(true);
                                   parent.map.setCenter(location);
                                   parent.map.setZoom(17);
                               };
                               var error = function (error)
                               {
                                   console.warn('ERROR(' + error.code + '): ' + error.message);
                               };
                               $('#' + parent.settings.anchors.aroundme).click(function (e)
                               {
                                   if(navigator.geolocation)
                                   {
                                       navigator.geolocation.getCurrentPosition(success, error, parent.settings.aroundme);
                                   }
                               });
                           }
                           else
                           {
                               $('#' + parent.settings.anchors.aroundme).hide();
                           }
                       },
        addCounter:    function ()
                       {
                           var parent = this;
                           var percent = parent.count == 0 ? 100 : Math.floor(parent.current * 100 / parent.count);
                           var container = $('#' + parent.settings.anchors.counter);
                           var action = percent >= 100 ? 'refresh' : parent.play ? 'pause' : 'play'
                           if(parent.settings.map.counter)
                           {
                               container.html('');
                               if(parent.settings.counter.refresh)
                               {
                                   container.append("<div class='actions'><i class='fa fa-" + action + "' id='counter-play'></i></div>");
                               }
                               if(parent.settings.counter.progressbar)
                               {
                                   container.append("<div class='progressbar'><div class='bar'><div class='current' style='width:" + percent + "%;'></div></div><div class='text'>" + percent + "%</div></div>");
                               }
                               if(parent.settings.counter.count)
                               {
                                   container.append("<div class='count'>" + parent.current + " / " + parent.count + "</div>");
                               }
                               $('#counter-play').click(function (e)
                               {
                                   parent.play = !parent.play;
                                   if(percent >= 100)
                                   {
                                       parent.current = 0;
                                       parent.page = 1;
                                       parent.play = true;
                                       if(parent.settings.map.cluster)
                                       {
                                           parent.clusterer = new MarkerClusterer(parent.map, [], parent.settings.cluster);
                                       }
                                       parent.removeMarkers();
                                       parent.initCounter();
                                   }
                                   else
                                   {
                                       parent.addCounter();
                                       parent.addMarkers();
                                   }
                               });
                           }
                       },
        addMarkers:    function ()
                       {
                           var parent = this;
                           $.ajax
                           ({
                               type:    'GET',
                               url:     parent.settings.url.marker,
                               data:    {
                                   page: parent.page,
                                   step: parent.step
                               },
                               success: function (data)
                                        {
                                            parent.current += data.length;
                                            parent.page += 1;
                                            if(parent.current < parent.count && parent.play)
                                            {
                                                parent.addMarkers();
                                            }
                                            for(var i = 0; i < data.length; i++)
                                            {
                                                var marker = new google.maps.Marker
                                                ({
                                                    position:  new google.maps.LatLng(data[i].lat, data[i].lng),
                                                    map:       parent.map,
                                                    title:     data[i].name,
                                                    draggable: false,
                                                    visible:   true,
                                                    icon:      '/images/marker.png'
                                                });
                                                parent.markers.push(marker);
                                                parent.addPopup(marker, data[i].id);
                                            }
                                            if(parent.settings.map.cluster && parent.current >= parent.count)
                                            {
                                                parent.clusterer = new MarkerClusterer(parent.map, parent.markers, parent.settings.cluster);
                                            }
                                            parent.addCounter();
                                        }
                           });
                       },
        removeMarkers: function ()
                       {
                           var parent = this;
                           for(var i = 0; i < parent.markers.length; i++)
                           {
                               parent.markers[i].setMap(null);
                           }
                           parent.markers = [];
                       },
        addPopup:      function (marker, id)
                       {
                           var parent = this;
                           google.maps.event.addListener(marker, 'click', function ()
                           {
                               parent.infowindow.close();
                               parent.infowindow.setContent("<i class='fa fa-refresh fa-spin' style='font-size:30px;'></i>");
                               parent.infowindow.open(parent.map, marker);
                               $.ajax
                               ({
                                   type:    'GET',
                                   url:     parent.settings.url.popup,
                                   data:    {id: id},
                                   success: function (data)
                                            {
                                                parent.infowindow.setContent(data);
                                            }
                               });
                           })
                       }
    };
    $.fn.gmap = function (options)
    {
        var settings = $.extend
        ({
            anchors:  {
                map:      'map-canvas',
                counter:  'map-counter',
                aroundme: 'map-aroundme'
            },
            url:      {
                counter: '',
                marker:  '',
                popup:   ''
            },
            map:      {
                center:             {
                    lat: 46,
                    lng: 5
                },
                zoom:               5,
                scrollwheel:        true,
                panControl:         true,
                rotateControl:      true,
                scaleControl:       true,
                streetViewControl:  true,
                mapTypeControl:     true,
                overviewMapControl: true,
                zoomControl:        true,
                counter:            true,
                aroundme:           true,
                cluster:            false,
                styles:             []
            },
            counter:  {
                refresh:     true,
                progressbar: true,
                count:       true
            },
            aroundme: {
                enableHighAccuracy: true,
                timeout:            5000,
                maximumAge:         0
            },
            cluster:  {
                gridSize:    50,
                maxZoom:     15,
                zoomOnClick: false,
                styles:      [
                    {
                        url:        '/images/clusters_03.png',
                        textColor:  "white",
                        fontWeight: "light",
                        textSize:   14,
                        width:      50,
                        height:     50
                    },
                    {
                        url:        '/images/clusters_02.png',
                        textColor:  "white",
                        fontWeight: "light",
                        textSize:   15,
                        width:      50,
                        height:     50
                    },
                    {
                        url:        '/images/clusters_01.png',
                        textColor:  "white",
                        fontWeight: "light",
                        textSize:   16,
                        width:      50,
                        height:     50
                    }
                ]
            }
        }, options);
        new Plugin(settings);
    };
}
(jQuery, window, window.document));