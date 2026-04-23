<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xml:lang="en" >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
    <title><?=lang('admin.add_edit.' . $entityName . ".form_title");?></title>
    <script type="text/javascript">        
      window.onload = function() {
        window.print();
      };
    </script>  
  </head>
  <body>
    <? if(isset($e)): ?>
      <h3><?=lang('admin.add_edit.' . $entityName . ".form_title");?></h3>
      <? foreach ($fields as $key => $options): ?>
        <? if(isset($e[$key]) && !empty($e[$key])): ?>
          <div style="margin-bottom: 15px;">
            <? $langKey = "admin.add_edit.$entityName.$key" ?>  
            <span style="text-decoration: underline; font-size: 18px;"><?=lang($langKey);?></span>
            <div>
              <?=$e[$key];?>
            </div>
          </div>
        <? endif; ?>
      <? endforeach; ?>
      <p><?=admin_site_url($entityName . '/add_edit/' . $e['id']);?> - <?=date('d.m.Y G:i');?></p>
    <? endif; ?>
  </body>
</html>