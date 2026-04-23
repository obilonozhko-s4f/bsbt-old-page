<div class="group">
  <label class="label"><?=$label?></label>
  
  <? $lat_field = ($name == 'geo' ? '' : $name) . 'latitude'; ?>
  <? $lng_field = ($name == 'geo' ? '' : $name) . 'longitude'; ?>
  <? $address_field = ($name == 'geo' ? '' : $name) . 'address'; ?>
  
  <div id="change-location-form">
  
    <div id="location_map" class="geo-box">
      <div id="google_map" class="google" style="height: <?=isset($params['map_height']) ? $params['map_height'] : 600?>px; width: <?=isset($params['map_width']) ? $params['map_width'] : 800?>px;"></div>
      
      <div class="geo-info">
        <div class="input-row left-row">
          <label>Address:</label>
          <textarea id="address-str" name="<?=$address_field?>" class="" style="width: 400px; height: 100px;"><?=$entity[$address_field]?></textarea>
        </div>
        <div class="r-row">
          <div class="input-row">
            <label>Latitude:</label>&nbsp;<input class="text" id="<?=$lat_field?>" type="text" name="<?=$lat_field?>" readonly="readonly" value="<?=$entity[$lat_field]?>"/>
          </div>
          <div class="input-row">
            <label>Longitude:</label>&nbsp;<input class="text" id="<?=$lng_field?>" type="text" name="<?=$lng_field?>" readonly="readonly" value="<?=$entity[$lng_field]?>"/>
          </div>
        </div>
        <div class="clear"></div>
        <div class="input-row">
          <label>&nbsp;</label>
          <input class="but" id="find-location" type="submit" value="Find address"/>
        </div>
      </div>
      
      <div class="clear"></div>
    </div>
    
    
  
  </div>
</div>

<script type="text/javascript">
  (function() {
      
    var latitude = '<?=$entity[$lat_field]?>';
    var longitude = '<?=$entity[$lng_field]?>';
        
    function findLocationHandler(result) {
      $('#<?=$lat_field?>').val(result['lat']);
      $('#<?=$lng_field?>').val(result['lng']);
      $('textarea[name=<?=$address_field?>]').val(result['address']);
      latitude = result['lat'];
      longitude = result['lng'];
    }

    var options = {
      'latitude': latitude,
      'longitude': longitude,
      'mapArea': '#location_map',
      'findLocationCallback': findLocationHandler,
      'mapChangeNavi': false
    }

    $.mmap.init(options, function(map) {

      if (latitude && longitude) {
        setTimeout(function() { $.mmap.composeMarker(latitude, longitude, {
          draggable: true              
        }); }, 100);
      } 
      if ($.mmap.getCurrentMapType() == 'google') {
        $(map).gclick( function(event) {
          $.mmap.composeMarker(event.latLng.lat(), event.latLng.lng(), {
            draggable: true
          }, options.findLocationCallback);
        });
      }
    });
  
  })();
</script>