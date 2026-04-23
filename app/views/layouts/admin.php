<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xml:lang="en" >
  <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    
    <?if (isset($header) && $header) :?>
      <title><?=$header['title'];?></title>
    <?endif; ?>
    
    <link rel="shortcut icon" href="<?=site_img("favicon.ico");?>" type="image/x-icon" />
    
    <link rel="stylesheet" type="text/css" href="<?=site_css("common/zero.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/base.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/messages.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/buttons.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/forms.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/list.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("jquery.chosen.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/add_edit.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/multipleselect.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/fileuploader.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/map.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/geo.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/jquery.alerts.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/jquery-ui-all.css");?>"/>
    <!--[if IE 6]>
      <link href="<?=site_css("style_ie.css");?>" media="screen" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?=site_js("iepngfix_tilebg.js");?>"></script>
    <![endif]-->
        
    <script type="text/javascript">
      var base_url = '<?=site_url();?>';
      var admin_url = '<?=$adminBaseRoute;?>';

     	<?if(isset($entityName)): ?>
    		var entityName = "<?=$entityName?>";
    	<?endif;?>
          
      var messages = {};
      messages['entity_delete_confirm'] = '<?=lang('admin.confirm.entity_delete');?>';
      messages['entities_delete_confirm'] = '<?=lang('admin.confirm.enties_delete');?>';
      messages['image_not_find'] = '<?=lang('admin.image_not_found');?>';
      messages['confirmation_dialog_title'] = '<?=lang('admin.confirm.dialog_title');?>';
      messages['information_dialog_title'] = '<?=lang('admin.confirm.information_dialog_title');?>';
      messages['delete_many_alert'] = '<?=lang('admin.confirm.no_items_selected');?>';
      messages['yes_button'] = '<?=lang('admin.confirm.yes_button');?>';
      messages['no_button'] = '<?=lang('admin.confirm.no_button');?>';

    </script>
    
    
    <script type="text/javascript" src="<?=site_js("jquery/jquery-1.7.1.min.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/jquery.metadata.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/jquery.validate.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/jquery.cookie.js");?>"></script>
    
    <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.core.min.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.datepicker.min.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.widget.min.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.tabs.min.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.button.min.js");?>"></script>

    <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.mouse.min.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.draggable.min.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.sortable.min.js");?>"></script>
                     
                      
                      
    
    
    <script type="text/javascript" src="<?=site_js("jquery/jquery.alerts.js,
                                                    jquery/jquery.autocomplete.js,
                                                    jquery/jquery.dragsort.js,
                                                    jquery/jquery.translit.js,
                                                    jquery/jquery.bgiframe.min.js,
                                                    jquery/jquery.tools.js,
                                                    jquery/jquery.selectboxes.js,
                                                    jquery/jquery.counter-1.0.js,
                                                    jquery/jquery.query.js,
                                                    jquery/requireScript.js,
                                                    jquery/jquery.chosen.min.js");?>"></script>
                                                    
                                                    
    <script type="text/javascript" src="<?=site_js("admin.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("multiple.select.js,
                                                    confirm.js,
                                                    checkboxes.js,
                                                    overlay.image.js,
                                                    flowplayer.min.js,
                                                    password.generator.js");?>"></script>
    	
  <? /* ?>
    <script type="text/javascript" src="<?=site_js("jquery/jquery-1.7.1.min.js,
                                                    jquery/jquery.metadata.js,
                                                    jquery/jquery.validate.js,
                                                    jquery/jquery.cookie.js,
                                                    jquery/ui/jquery.ui.core.min.js,
                                                    jquery/ui/jquery.ui.position.min.js,
                                                    jquery/ui/jquery.ui.widget.min.js,
                                                    jquery/ui/jquery.ui.button.min.js,
                                                    jquery/ui/jquery.ui.datepicker.min.js,
                                                    jquery/ui/jquery.ui.tabs.min.js,
                                                    jquery/ui/jquery.ui.mouse.min.js,
                                                    jquery/ui/jquery.ui.draggable.min.js,
                                                    jquery/ui/jquery.ui.sortable.min.js,
                                                    jquery/jquery.alerts.js,
                                                    jquery/jquery.autocomplete.js,
                                                    jquery/jquery.dragsort.js,
                                                    jquery/jquery.translit.js,
                                                    jquery/jquery.bgiframe.min.js,
                                                    jquery/jquery.tools.js,
                                                    jquery/jquery.selectboxes.js,
                                                    jquery/jquery.counter-1.0.js,
                                                    jquery/jquery.query.js,
                                                    jquery/requireScript.js,
                                                    jquery/jquery.chosen.min.js,
                                                    admin.js,
                                                    multiple.select.js,
                                                    confirm.js,
                                                    checkboxes.js,
                                                    overlay.image.js,
                                                    flowplayer.min.js,
                                                    password.generator.js");?>"></script>
                                                    
	<? */ ?>
    


    <script type="text/javascript" src="<?=site_js("packages/tiny_mce/jquery.tinymce.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("editor.changer.js")?>"></script>
    
        
    <script type="text/javascript">
      $.scriptBaseUrl = '<?=site_js('')?>';
    </script>
        
  </head>
  
  <body>
    <div id="wrapper-admin">
      
      <div id="container">
        <div id="header">
          <?if (!url_contains("login") && !url_contains("forgot_password")) :?>
            <div id="user-navigation">
              <ul class="wat-cf">
                <li><a href="<?=site_url("$adminBaseRoute/change_info");?>"><?=lang('admin.change_info');?></a></li>
                <li><a class="logout" href="<?=site_url("$adminBaseRoute/logout");?>"><?=lang('admin.logout');?></a></li>
              </ul>
            </div>
          <?endif;?>
          <div id="main-navigation">
            <h2 class="admin-title"><?=lang('admin.title');?>&nbsp;&nbsp;<a target="_blank" href="<?=site_url('/');?>"><?=lang('admin.goto_website');?></a></h2>
          </div>
        </div>
        
        <div id="main">
          <?if (isset($hasSidebar) && $hasSidebar) :?>
            <div id="sidebar">
              <?=$this->load->view('includes/admin/parts/admin_menu')?>
            </div><!-- #sidebar -->
          <?endif;?>
          
          <div class="admin-part" <?= (!isset($hasSidebar) || !$hasSidebar) ? "style=\"margin-left: 0px;\"" : ""?>>
            <div id="content" class="content"><?=$content?></div>
          </div><!-- adminPart -->
          <div class="clear paddingBottom120"></div>
        </div><!-- #main -->
        <div class="clear push-box"></div>
      </div><!-- #container -->
      
      <div id="footer">
         <div class="block">
          <p><?=lang('admin.footer_copyright');?>&nbsp;<a href="mailto:support@itirra.com?subject=Support (<?=current_url();?>)&body=" style="color: #fff;" title="<?=lang('admin.footer_support');?>"><?=lang('admin.footer_support');?></a></p>
        </div>
      </div>
       
    </div><!-- #wrapper -->
    <div class="apple_overlay" id="overlay">
      <div class="overlayContent">
        <div class="contentWrap"></div>
      </div>
    </div>
  </body>
</html>