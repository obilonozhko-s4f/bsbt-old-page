<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xml:lang="en">
<head>
  <meta http-equiv="content-type"	content="application/xhtml+xml; charset=utf-8" />
  <?
    if (!isset($header) || !isset($header['title'])) {
      $header['title'] = '';
    }
  ?>
  <title><?=$header['title']?></title>
  <? if(isset($header['description'])): ?>
    <meta name="description" content="<?=$header['description']?>" />
  <? endif; ?>
         
  <link rel="shortcut icon" href="<?=site_img('favicon.ico');?>" type="image/x-icon" />
  <link rel="stylesheet" type="text/css" href="<?=site_css("common/zero.css,common/messages.css,style.css,jquery-ui-1.8.18.custom.css,jquery.stepper.css");?>" />
  <script type="text/javascript" src="<?=site_js("jquery/jquery-1.7.1.min.js");?>"></script>
  <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.core.min.js");?>"></script>
  <script type="text/javascript" src="<?=site_js("jquery/localization/jquery.ui.datepicker-ru.js");?>"></script>
  <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.ui.datepicker.min.js");?>"></script>
  <script type="text/javascript" src="<?=site_js("jquery/ui/jquery.stepper.min.js");?>"></script>
  <script type="text/javascript" src="<?=site_js("jquery/jquery.validate.js");?>"></script>
  <script type="text/javascript" src="<?=site_js("core.js");?>"></script>
  <? if($this->lang->lang() == 'ru'): ?>
    <script type="text/javascript" src="<?=site_js("jquery/localization/jquery.ui.datepicker-ru.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/messages_ru.js");?>"></script>
  <? elseif ($this->lang->lang() == 'de'): ?>
    <script type="text/javascript" src="<?=site_js("jquery/localization/jquery.ui.datepicker-de.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("jquery/messages_de.js");?>"></script>
  <? elseif ($this->lang->lang() == 'en'): ?>
    <script type="text/javascript" src="<?=site_js("jquery/localization/jquery.ui.datepicker-en-GB.js");?>"></script>
  <? endif;?>
  <script type="text/javascript">
    var base_url = "<?=surround_with_slashes(site_url('/'));?>";
  </script>
</head>
<body>
	<div id="wrapper">
    <div id="header">
      <? $this->load->view("includes/parts/header"); ?>
    </div><!-- END #header -->
    <div id="middle">
      <div id="content" class="reserv_wrap">
        <?=$content;?>
      </div><!-- #content -->
      <div id="right_part">
        <? $this->load->view("includes/parts/right_part"); ?>
      </div><!-- #right_part -->
	  <div class="clear"></div>
    </div><!-- #middle -->
    <div id="footer">
      <? $this->load->view("includes/parts/footer"); ?>
    </div><!-- #footer -->
	</div><!-- #wrapper -->
<? $this->load->view("includes/parts/cookie_banner"); ?>
</body>
</html>