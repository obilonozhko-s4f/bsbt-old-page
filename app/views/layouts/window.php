<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"     
     xml:lang="en" >
  <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    <?if(isset($header) && $header): ?>
      <title><?=$header['title'];?></title>
    <?endif; ?>    
    
    <script type="text/javascript">
      var base_url = '<?=site_url();?>';

      var messages = {};
      messages['entity_delete_confirm'] = '<?=$lang->line('admin.confirm.entity_delete');?>';      
      messages['entities_delete_confirm'] = '<?=$lang->line('admin.confirm.enties_delete');?>';
      messages['image_not_find'] = '<?=$lang->line('admin.image_not_found');?>';
      messages['confirmation_dialog_title'] = '<?=$lang->line('admin.confirm.dialog_title');?>';
      messages['information_dialog_title'] = '<?=$lang->line('admin.confirm.information_dialog_title');?>';
      messages['delete_many_alert'] = '<?=$lang->line('admin.confirm.no_items_selected');?>';
      messages['yes_button'] = '<?=$lang->line('admin.confirm.yes_button');?>';
      messages['no_button'] = '<?=$lang->line('admin.confirm.no_button');?>';      
    </script>    
    
        
    <script type="text/javascript" src="<?=site_js("jquery/jquery.min.js,jquery/jquery-ui-1.8.custom.min.js,jquery/jquery.bgiframe.min.js,jquery/jquery.alerts.js,window.core.js,confirm.js");?>"></script>    
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/jquery-ui-all.css,admin/base/window.css,admin/base/jquery.alerts.css");?>"/>                  
    <link rel="shortcut icon" href="<?=site_img("favicon.ico");?>" type="image/x-icon" />
    <!--[if IE 6]>     
      <link href="<?=site_css("style_ie.css");?>" media="screen" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?=site_js("iepngfix_tilebg.js");?>"></script>           
    <![endif]-->

  </head>
  <body>  
    <div id="dialog">
      <div class="innerDialog">        
        <div id="main">        
          <div class="topBlock">
            <h4 class="fl"><?=$lang->line('admin.window.title');?></h4>
            <p class="path"><b>Path:</b>&nbsp;<span>/<?=isset($currentFolder)?$currentFolder:"";?></span></p>
            <div class="clear"></div>
            
            <?=html_flash_message();?>
            <!-- 
            <a class="synchronizeLink" href="<?=site_url($adminBaseRoute . "/synchronize_db_with_upload_dir");?>"><?=$lang->line('admin.window.synchronize_db_with_upload_dir');?></a>
             -->
          </div><!-- .topBlock -->
          
          <div class="centerBlock">
            <div id="detailsBlock" class="rightPart imageInfo">
              
                <div class="photoBlock">
                  <table class="vertical-middle" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                      <td>
                        <img id="image_preview_info" src="" alt="" title="" />
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="detailsBlock">
                  <p><b><?=$lang->line('admin.window.image_name');?>:</b></p>
                  <p><span id="image_name_info"></span></p>
                  <p><b><?=$lang->line('admin.window.image_size');?>:</b><span id="image_size_info"></span></p>
                  <p><b><?=$lang->line('admin.window.image_width');?>:</b><span id="image_width_info"></span></p>
                  <p><b><?=$lang->line('admin.window.image_height');?>:</b><span id="image_height_info"></span></p>
                  <p><b><?=$lang->line('admin.window.image_created_date');?>:</b><br/><span id="image_created_date_info"></span></p>
                  <p><b><?=$lang->line('admin.window.image_resize');?>:</b></p>
                  <form id="changeDimensions" method="post" action="<?=site_url($adminBaseRoute . "/resource/resize_resource");?>" autocomplete="off">
                    <input type="hidden" id="image_id" value="" name="image_id" />
                    <label><?=$lang->line('admin.window.image_resize_width');?>:</label><br/>
                    <input type="text" name="width" class="width" style="width: 95%"/>
                    <label><?=$lang->line('admin.window.image_resize_height');?>:</label><br/>
                    <input type="text" name="height" class="height" style="width: 95%;"/>
                    <button style="margin-top: 3px;" class="window-button ok-button"><b class="f"></b><b class="s"><?=$lang->line('admin.window.button_ok');?></b><b class="t"></b></button>
                  </form>
                  <p class="delete-image"><span><b>Delete:</b></span><a id="delete_url"  title="<?=$this->lang->line('admin.confirm.entity_delete')?>" class="confirm" href="<?=site_url($adminBaseRoute . "/resource/delete_resource");?>"><img src="<?=site_img("window/delete_window_icon.png");?>" alt="" title="" /></a></p>
                </div>              
              
            </div><!-- .rightPart --> 
            
            <div class="leftPart unvisible">
              <div class="contentBlock">
                <?=$content?>
                <div class="clear"></div>
              </div><!-- .contentBlock -->
            </div><!-- .leftPart -->
            
            <div class="clear"></div>
          </div><!-- .centerBlock -->
          
          <div class="rightPartBack"></div>
          <div class="leftPartBack unvisible"></div>
          
        </div><!-- #main -->
        
        
        <div class="bottomBlock">
          <div class="addFileBlock">
            <form method="post" action="<?=site_url($adminBaseRoute . "/resource/add_resource");?>" autocomplete="off" enctype="multipart/form-data">
              <div class="addFile">
                <label><?=$lang->line('admin.window.image_label');?>:</label>
                <input class="file" type="file" name="image" />
              </div>
              <button class="window-button"><b class="f"></b><b class="s"><?=$lang->line('admin.window.upload_label');?></b><b class="t"></b></button>
            </form>
          </div>
          <button style="margin-left: 8px;" id="createFolder" class="window-button"><b class="f"></b><b class="s"><?=$lang->line('admin.window.create_folder');?></b><b class="t"></b></button>
          
          <div class="buttonBlock">
            <button class="window-button" id="selectImage"><b class="f"></b><b class="s"><?=$lang->line('admin.window.button_ok');?></b><b class="t"></b></button>
          </div>
          <div class="clear"></div>
        </div><!-- .bottomBlock -->
      </div>
    </div>
    
   <div id="createFolderdialog"  title="Create Folder">
      <form action="<?=site_url($adminBaseRoute . '/resource/create_folder')?>" class="validate noScrollTo" method="post">
        <div class="input-row">
          <label class="requestLabel"><?=$lang->line('admin.window.folder_name');?>:</label><br/>
          <input type="text" name="name" value=""/>
        </div>  
        <div class="button-row" style="margin-top: 10px;">
          <button type="submit" class="window-button"><b class="f"></b><b class="s"><?=$lang->line('admin.window.button_create');?></b><b class="t"></b></button>
        </div>
      </form>
    </div>    
  </body>
  
</html>