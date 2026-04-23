<?=$this->load->view('includes/admin/parts/simple_add_edit_entity', array('fields' => $fields,
                                                                          'entity' => $entity,
                                                                          'backUrl' => $backUrl))?>
<script type="text/javascript">
    $(document).ready(function() {
      <? if(!empty($entity['persons'])):?>
        cp = <?=$entity['persons']?>;
      <? endif;?>
      ap = $('#reservation_apartment_chzn .chzn-single span').html();
      if (ap == null){
        ap = $('#reservation_apartment').attr('value');
      }
      if($.isNumeric(ap)) {
        $.get(base_url + "ajax/persons", {id: ap, enp: cp},
                function(data){
                  $('input#reservation_persons').parent().html(data);
                });
      }
      $('ul.chzn-results li').click(function(){
        ap = $(this).html();
        if($.isNumeric(ap)) {
          $.get(base_url + "ajax/persons", {id: ap},
                  function(data){
                    $('input#reservation_persons').parent().html(data);
                  });
          $.get(base_url + "ajax/persons", {id: ap},
                function(data){
                  $('select#reservation_persons').parent().html(data);
                });
         }
      });
      return false;
    });
</script>