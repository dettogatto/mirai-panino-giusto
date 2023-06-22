(function ($) {
  $(document).ready(function () {
    var mobileBreakpoint = 600;
    /* BEGIN FILTERS CODE */

    function applyFilters() {
      var $form = $(".stores-map-form");
      var textSearch = $form.find("#stores-map-search").val();
      var types = [];
      $form.find('input[name="store-type"]:checked').each(function () {
        types.push($(this).val());
      });
      console.log("applyFilters");
      var $storesContainer = $("#stores-map-list");
      $storesContainer.find(".ristorante").each(function () {
        hidden = false;
        console.log($(this));
        console.log($(this).data("type"));
        if (types.length > 0) {
          var myTypes = $(this).data("type").split(",");
          var found = false;
          for (var i = 0; i < myTypes.length; i++) {
            console.log("looking: " + myTypes[i]);
            if (types.includes(myTypes[i])) {
              console.log("found: " + myTypes[i]);
              found = true;
            }
          }
          if (!found) {
            hidden = true;
          }
        }
        if (!hidden && textSearch.length > 0) {
          if (
            $(this)
              .data("name")
              .toLowerCase()
              .indexOf(textSearch.toLowerCase()) < 0 &&
            $(this)
              .data("city")
              .toLowerCase()
              .indexOf(textSearch.toLowerCase()) < 0
          ) {
            hidden = true;
          }
        }
        if (hidden) {
          $(this).addClass("hidden");
        } else {
          $(this).removeClass("hidden");
        }
      });
      clearMarkers();
      loadMarkers();
    }

    $(".stores-map-form input").change(function () {
      applyFilters();
    });
    var keyupDebunker = null;

    $("#stores-map-search").keyup(function () {
      if (keyupDebunker) {
        clearTimeout(keyupDebunker);
      }
      keyupDebunker = setTimeout(function () {
        applyFilters();
      }, 200);
    });

    $(".stores-map-form").on("focusin click", function () {
      $("#stores-map-list").show();
    });

    /* BEGIN GMAPS CODE */

    var map;
    var markers = [];
    var infoWindows = [];
    var $map = $("#stores-map-canvas");
    var color = "#9E2811"; // google map background colour
    var saturation = 100;
    var styles = [
      {
        featureType: "landscape",
        stylers: [
          { hue: "#000" },
          { saturation: -100 },
          { lightness: 40 },
          { gamma: 1 },
        ],
      },
      {
        featureType: "road.highway",
        stylers: [
          { hue: color },
          { saturation: saturation },
          { lightness: 20 },
          { gamma: 1 },
        ],
      },
      {
        featureType: "road.arterial",
        stylers: [
          { hue: color },
          { saturation: saturation },
          { lightness: -10 },
          { gamma: 1 },
        ],
      },
      {
        featureType: "road.local",
        stylers: [
          { hue: color },
          { saturation: saturation },
          { lightness: 20 },
          { gamma: 1 },
        ],
      },
      {
        featureType: "water",
        stylers: [
          { hue: "#000" },
          { saturation: -100 },
          { lightness: 15 },
          { gamma: 1 },
        ],
      },
      {
        featureType: "poi",
        stylers: [
          { hue: "#000" },
          { saturation: -100 },
          { lightness: 25 },
          { gamma: 1 },
        ],
      },
    ];
    if (!$map || !$map.length) {
      return;
    }
    console.log("Mappa trovata");
    $.getScript(
      "https://maps.google.com/maps/api/js?key=AIzaSyDX8qDLInd3ZnhEmE6g0D9hsRy2_vJ_ZcQ&libraries=places",
      function (data, textStatus, jqxhr) {
        console.log("LTM Loaded");
        initMap();
      }
    );

    function initMap() {
      var myLatLng = { lat: 45.464206, lng: 9.189802 };
      map = new google.maps.Map($map[0], {
        center: myLatLng,
        zoom: 12,
        center: myLatLng,
        styles: styles,
        disableDefaultUI: true, // a way to quickly hide all controls
        scaleControl: true,
        zoomControl: true,
        fullscreenControl: true,
      });
      loadMarkers();
    }

    function loadMarkers() {
      var singleStoreMode = $(".stores-map-container").hasClass("single-store");
      var $storesContainer = $("#stores-map-list");
      $storesContainer.find(".ristorante:not(.hidden)").each(function () {
        let ristorante = {};
        ristorante.title = $(this).data("name");
        ristorante.coordinates = $(this).data("coordinates");
        ristorante.type = $(this).data("type").split(",");
        addMarker($(this), singleStoreMode);
      });
    }

    function addMarker($ristorante, singleStoreMode = false) {
      var coo = str2coo($ristorante.data("coordinates"));
      var rTitle = $ristorante.data("name");
      var rLink = $ristorante.data("link");
      var rType = $ristorante.data("type").split(",");
      if (!coo) {
        return;
      }
      let icon = rType.includes("to-go")
        ? window.markerIconToGo
        : window.markerIcon;
      var infoWindow;
      if (singleStoreMode) {
        infoWindow = new google.maps.InfoWindow({
          content: `
            <div class="map-info-window" style="text-align: center;">
              <h3 class="map-info-window-title">${rTitle}</h3>
              <br>
              <a href="${rLink}" target="blank" class="map-info-window-link">Apri nelle mappe</a>
            </div>
          `,
        });
      } else {
        infoWindow = new google.maps.InfoWindow({
          content: `
          <div class="map-info-window" style="text-align: center;">
            <h3 class="map-info-window-title">${rTitle}</h3>
            <br>
            <a href="${rLink}" class="map-info-window-link">Vai al ristorante</a>
          </div>
        `,
        });
      }
      var marker = new google.maps.Marker({
        position: coo,
        map,
        title: rTitle,
        icon,
      });
      google.maps.event.addListener(marker, "click", function () {
        infoWindows.forEach(function (iw) {
          iw.close();
        });
        infoWindow.open(map, marker);
      });
      $ristorante.click(function () {
        infoWindows.forEach(function (iw) {
          iw.close();
        });
        infoWindow.open(map, marker);
        map.setZoom(14);
        map.setCenter(coo);
        if (window.innerWidth > mobileBreakpoint) {
          map.panBy(-200, 0);
        } else {
          $("#stores-map-list").hide();
        }
      });
      if (singleStoreMode) {
        map.setCenter(coo);
        map.setZoom(15);
      }
      markers.push(marker);
      infoWindows.push(infoWindow);
    }

    function clearMarkers() {
      for (var marker of markers) {
        marker.setMap(null);
      }
      markers.length = 0;
    }

    function str2coo(string) {
      if (!string || string.length < 1) {
        return null;
      }
      var val = string.split(",");
      if (val.length != 2) {
        return null;
      }
      var lat = parseFloat(val[0].trim());
      var lng = parseFloat(val[1].trim());
      var ret = { lat: lat, lng: lng };
      return ret;
    }

    // user position button
    $("#stores-map-locate-me").click(function () {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function (position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            };
            map.setCenter(pos);
            map.setZoom(14);
            if (window.innerWidth > mobileBreakpoint) {
              map.panBy(-200, 0);
            }
          },
          function (error) {
            console.log("Error geolocating");
            console.log(error);
          }
        );
      } else {
        console.log("No geolocation :(");
      }
    });

    // end document.ready
  });
})(jQuery);
