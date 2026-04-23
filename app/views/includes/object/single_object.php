<? if(!empty($_GET)): ?>
  <a href="<?=site_url('apartments' . get_get_params());?>" class="back"><?=lang('object.back2search');?></a>
<? endif; ?>
<div class="clear"></div>
<div class="single_object">
  <h2><?=$object['objecttype']['name']?> <?=lang('object.title.incity');?> <?=$object['city']['title']?></h2>
  <img class="main" src="<?=site_image_thumb_url('_big', $object['image']);?>" alt="<?=$object['image']['file_name']?>" />
  <div class="gallery">
    <ul id="slider1" style="margin: 0; padding: 0;">
      <? if(!empty($object['images'])): ?>
        <? foreach($object['images'] as $img): ?>
          <li style="width: 70px; height: 73px; margin: 0; padding: 0;"><a href="<?=site_img($img['image']['web_path'] . $img['image']['file_name']);?>"><img src="<?=site_image_thumb_url('_small', $img['image']);?>" style="width: 70px; height: 70px;" alt="<?=$img['image']['file_name']?>" /></a></li>
        <? endforeach; ?>
      <? endif; ?>
    </ul>
  <script type="text/javascript">
    $(document).ready(function() {
      $(function() {
        $('#slider1 li a').lightBox({
      	  imageLoading: '<?=site_img('lightbox-ico-loading.gif');?>',
      	  imageBtnClose: '<?=site_img('lightbox-btn-close.gif');?>',
      	  imageBtnPrev: '<?=site_img('lightbox-btn-prev.gif');?>',
      	  imageBtnNext: '<?=site_img('lightbox-btn-next.gif');?>',
          fixedNavigation:true
          });
      });
    });
  </script>
  
  </div>
  <div class="right_part">
    <div class="top">
      <? if(!empty($_GET['date_from']) && !empty($_GET['date_to'])): ?>
        <p class="period"><?=lang('object.right_part.period');?></p>
      <? else: ?>
        <p class="noperiod"><?=lang('object.right_part.noperiod');?></p>
      <? endif; ?>
    </div>
    <div class="clear"></div>
    <ul>
      <li><p class="left"><?=lang('object.right_part.code');?></p><p><?=$object['id']?></p><div class="clear"></div></li>
      <li><p class="left"><?=lang('object.right_part.index');?></p><p><?=$object['post_index']?></p><div class="clear"></div></li>
      <li><p class="left"><?=lang('object.right_part.city');?></p><p><?=$object['city']['title']?></p><div class="clear"></div></li>
      <li><p class="left"><?=lang('object.right_part.street');?></p><p><?=$object['street']?></p><div class="clear"></div></li>
      <li><p class="left"><?=lang('object.right_part.nights_min');?></p><p><?=$object['nights_min']?></p><div class="clear"></div></li>
      <li><p class="left"><?=lang('object.right_part.persons_max');?></p><p><?=$object['persons_max']?><?if(!empty($object['add_person_in']) && !empty($object['add_person_out'])):?> + 1<? endif;?></p><div class="clear"></div></li>
      <li style="border: none;"><p class="left"><?=lang('object.right_part.space');?></p><p><?=$object['space']?> м&#178;</p><div class="clear"></div></li>
    </ul>
    <div class="order">
      <p><?=lang('object.right_part.price');?> <span><?=$object['price_out']?>&#8364;</span> / <?=lang('object.right_part.pernight');?></p>
      <? if(!empty($_GET['date_from']) && !empty($_GET['date_to']) && !empty($_GET['persons'])): ?>
        <a href="<?=site_url('reservation' . '?id=' . $object['id'] . '&date_from=' . $_GET['date_from'] . '&date_to=' . $_GET['date_to'] . '&persons=' . $_GET['persons']);?>" class="button green"><?=lang('object.right_part.book');?></a>
      <? else:?>
        <a href="<?=site_url('reservation' . '?id=' . $object['id']);?>" class="button green"><?=lang('object.right_part.book');?></a>
      <? endif;?>
    </div>
  </div>
  <div class="clear"></div>
  
  <div class="features" id="<?=$object['id'];?>">
     <img src="<?=site_img('preloader.gif');?>" class="preload" alt="loading..." title="loading..." />
  </div>
  <p class="description"><?=$object['description']?></p>
  <? $lat = str_replace(',', '.', $object['latitude']); ?>
  <? $lng = str_replace(',', '.', $object['longitude']); ?>

  <div class="clear"></div>
  <div class="google-box" style="width: 690px; height: 430px; position: relative; overflow: hidden; border: 1px solid #d8d8d8; background: #f3f4f6;">
    <div class="map" id="map_canvas" style="width: 690px; height: 430px; display: none;"></div>
    <div id="bsbt-map-placeholder" style="width: 690px; height: 430px; display: table; text-align: center; background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);">
      <div style="display: table-cell; vertical-align: middle; padding: 30px;">
        <div style="max-width: 420px; margin: 0 auto;">
          <h3 style="margin: 0 0 12px 0; font: bold 24px Arial, Helvetica, sans-serif; color: #212f54;">Google Maps</h3>
          <p style="margin: 0 0 18px 0; font: 14px/1.6 Arial, Helvetica, sans-serif; color: #4b5563;">
            To display the map, Google content must be loaded. Please allow Google Maps in the cookie settings.
          </p>
          <button type="button" id="bsbt-enable-map" style="display: inline-block; background: #212f54; color: #fff; border: none; border-radius: 10px; padding: 12px 18px; font: bold 14px Arial, Helvetica, sans-serif; cursor: pointer;">
            Allow Google Maps
          </button>
        </div>
      </div>
    </div>
  </div>

</div>

<script type="text/javascript">
  $(document).ready(function() {
	// Features  
    $('.features').each(function() {
      var that = $(this);
      $.get(base_url + "ajax/features", {aId: $(this).attr('id')},
        function(data){
          that.html(data);
        });
    });
  });
</script>

<script type="text/javascript">
(function() {
  var mapLoaded = false;

  function getCookie(name) {
    var match = document.cookie.match(new RegExp('(^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[2]) : null;
  }

  function setCookie(name, value, days) {
    var expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires.toUTCString() + '; path=/; SameSite=Lax';
  }

  function readConsent() {
    var raw = getCookie('bsbt_cookie_consent');
    if (!raw) return null;
    try {
      return JSON.parse(raw);
    } catch(e) {
      if (raw === 'accepted') {
        return {essential:true, analytics:true, maps:true};
      }
      if (raw === 'declined') {
        return {essential:true, analytics:false, maps:false};
      }
      return null;
    }
  }

  function initMap() {
    var latitude = '<?=str_replace(',', '.', $object['latitude']);?>';
    var longitude = '<?=str_replace(',', '.', $object['longitude']);?>';

    var myLatlng = new google.maps.LatLng(latitude, longitude);
    var myOptions = {
      zoom: 12,
      scrollwheel: false,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title:"<?=$object['objecttype']['name']?> <?=lang('object.title.incity');?> <?=$object['city']['title']?>",
      zIndex: 5
    });
    var contentString = '<div style="width: 120px; height: 40px;"><p style="color: #F8931F;"><?=$object['objecttype']['name']?> <?=lang('object.title.incity');?> <?=$object['city']['title']?></p></div>';
    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
    });

    var image = new google.maps.MarkerImage('<?=site_img('map_marker.png');?>',
                                    	    new google.maps.Size(36, 52),
                                    	    new google.maps.Point(0,0),
                                    	    new google.maps.Point(18, 52));
    latitude = "52.325898";
    longitude = "9.804268";
    myLatlng = new google.maps.LatLng(latitude, longitude);
    marker2 = new google.maps.Marker({
      position: myLatlng,
      map: map,
      icon: image,
      title:"Hannover Exhibition Center",
      zIndex: 999
    });
    var contentString2 = '<div style="width: 120px; height: 40px;"><p style="color: #F8931F;">Hannover Exhibition Center</p></div>';
    var infowindow2 = new google.maps.InfoWindow({
        content: contentString2
    });
    google.maps.event.addListener(marker2, 'click', function() {
    	infowindow2.open(map,marker2);
    });
  }

  function loadMap() {
    if (mapLoaded) return;
    mapLoaded = true;

    document.getElementById('bsbt-map-placeholder').style.display = 'none';
    document.getElementById('map_canvas').style.display = 'block';

    if (window.google && window.google.maps) {
      initMap();
      return;
    }

    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-U&sensor=false';
    script.onload = function() {
      initMap();
    };
    document.getElementsByTagName('head')[0].appendChild(script);
  }

  window.bsbtApplyMapConsent = function(status) {
    if (status === 'granted') {
      loadMap();
    }
  };

  document.getElementById('bsbt-enable-map').onclick = function() {
    var consent = readConsent() || {essential:true, analytics:false, maps:false};
    consent.maps = true;
    setCookie('bsbt_cookie_consent', JSON.stringify(consent), 365);
    loadMap();
  };

  var consent = readConsent();
  if (consent && consent.maps) {
    loadMap();
  }
})();
</script>
