<div class="group">
  <label class="label"><?=$label?></label>
  
  <? $left_field = ($name == 'map' ? '' : $name) . 'left_px'; ?>
  <? $top_field = ($name == 'map' ? '' : $name) . 'top_px'; ?>
  
  <input id="<?=$left_field?>" type="hidden" name="<?=$left_field?>" value="<?=$entity[$left_field]?>"/>
  <input id="<?=$top_field?>" type="hidden" name="<?=$top_field?>" value="<?=$entity[$top_field]?>"/>

  <div class="map-wrapper">
    <img id="<?=$id?>_target_img" class="target" src="<?=site_img('target.png')?>" style="display:none;" />
    <img id="<?=$id?>_map_img" class="map" src="<?=isset($params['map_image_url']) ? $params['map_image_url'] : site_img($params['map_image_name'])?>?>" />
  </div>
  <div class="clear"></div>
  
  <?if (!empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    /*** Functions ***/
    
    function setTargetImgPosition(left, top){
      $targetImg = $('#<?=$id?>_target_img');
      $targetImg.css('left', left - ($targetImg.width()/2) + 'px');
      $targetImg.css('top', top - ($targetImg.height()/2) + 'px');
      $targetImg.show();
    }


    function setPosition(left, top){
      // putting coordinates to hidden inputs
      $('#<?=$left_field?>').val(left);
      $('#<?=$top_field?>').val(top);
      setTargetImgPosition(left, top);
    }

    
    function setPositionByMapImage(e){
      $mapImg = $('#<?=$id?>_map_img');
      var offset = $mapImg.offset();
      var left = parseInt(e.pageX - offset.left);
      var top = parseInt(e.pageY - offset.top);
      setPosition(left, top);
    }


    function setPositionByTargetImage(e){
      $targetImg = $('#<?=$id?>_target_img');
      var targetPos = $targetImg.position();
      var left = parseInt(targetPos.left + $targetImg.width() / 2);
      var top = parseInt(targetPos.top + $targetImg.width() / 2);
      setPosition(left, top);
    }

    
    function isTargetUnderMap(e){
      $mapImg = $('#<?=$id?>_map_img');
      var mapOffset = $mapImg.offset();

      if(e.pageX > mapOffset.left + $mapImg.width()){
        return false;
      }
      if(e.pageX < mapOffset.left){
        return false;
      }
      if(e.pageY < mapOffset.top){
        return false;
      }
      if(e.pageY > mapOffset.top + $mapImg.height()){
        return false;
      }
      return true;
    }
    

    /*** Init ***/
    <?if($entity[$left_field] || $entity[$top_field]):?>
      var left = <?=$entity[$left_field]?>;
      var top = <?=$entity[$top_field]?>;
      setTargetImgPosition(left, top);
    <?endif;?>

    
    /*** Events ***/
    
    $('.target').draggable({
      containment: "parent",
      start: function(){
        $('#<?=$id?>_target_img').css({"cursor" : "move"});
        $('#<?=$id?>_target_img').parent().css({"cursor" : "move"});
      },
      stop: function(e){
        if(isTargetUnderMap(e)){
          setPositionByMapImage(e);
        } else {
          setPositionByTargetImage(e);
        }
        $('#<?=$id?>_target_img').css({"cursor" : "pointer"});
        $('#<?=$id?>_target_img').parent().css({"cursor" : "pointer"});
      }
    });
    
    
    $('.map').click(function(e){
      setPositionByMapImage(e);
    });
    

    $('.target').click(function(e){
      if(isTargetUnderMap(e)){
        setPositionByMapImage(e);
      }
    });
    
  });
</script>