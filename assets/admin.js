(function ($) {
  $(document).ready(function () {
    $coos = $('.acf-field[data-name^="coordinate"]');
    if (!$coos || $coos.length < 1) {
      return;
    }

    $.getScript(
      "https://maps.google.com/maps/api/js?key=AIzaSyDX8qDLInd3ZnhEmE6g0D9hsRy2_vJ_ZcQ&libraries=places",
      function (data, textStatus, jqxhr) {
        console.log("LTM Loaded");
        init();
      }
    );

    function init() {
      geocoder = new google.maps.Geocoder();

      $coos.each(function () {
        var $coo_container = $(this);
        var $coo_field = $(this).find("input");

        var $btn = $(
          '<a style="float: left; margin-right: 4px;" class="button acf-button">geocode</a>'
        );
        $coo_container.find(".acf-input").prepend($btn);
        $btn.click(function () {
          var address = $('.acf-field[data-name^="indirizzo"] input').val();
          console.log("geocoding address: " + address);

          if (address && address.length > 0) {
            geocoder.geocode({ address: address }, function (result) {
              if (result && result.length) {
                var lat = result[0].geometry.location.lat();
                var lng = result[0].geometry.location.lng();
                $coo_field.val(lat + ", " + lng);
              } else {
                $coo_field.val("error");
              }
            });
          }
        });
      });
    }
  });
})(jQuery);
