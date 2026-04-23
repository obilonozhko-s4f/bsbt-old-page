<? $baseUrl = config_item("base_url"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xml:lang="en">
<head>
  <meta http-equiv="content-type"	content="application/xhtml+xml; charset=utf-8" />
  <title>Page not found</title>  
  <link rel="stylesheet" type="text/css" href="<?=site_css("common/zero.css,style.css");?>" />
</head>

<body>
  <div id="wrapper">
    <div id="header" style="height: 100px;">
      <div class="logo pr">
	    <a href="<?=site_url();?>"><img src="<?=site_img('logo.png');?>"/></a>
	  </div>
	  <div class="contacts">
	    <div>
		  <img src="<?=site_img('skype.png');?>"/>
		  <p>Skype Hotline</p>
		</div>
		<div>
		  <img src="<?=site_img('phone.png');?>"/>
		  <p>+4917624625266</p>
		</div>
		<div>
		  <img src="<?=site_img('mail.png');?>"/>
		  <a href="mailto:business@bs-travelling.com">business@bs-travelling.com</a>
		</div>
	  </div>
	  <div class="clear"></div>
    </div><!-- END #header -->
    <div id="middle" style="border-radius: 10px; min-height: 580px;">
      <p style="text-align: center;">404 error. Page not found.</p>
	<div class="clear"></div>
    </div><!-- #middle -->
    <div id="footer">
      <div class="contacts">
	    <div>
		  <img src="<?=site_img('foot_skype.png');?>"/>
		  <p>Skype Hotline</p>
		</div>
		<div>
		  <img src="<?=site_img('foot_phone.png');?>"/>
		  <p>+4917624625266</p>
		</div>
		<div>
		  <img src="<?=site_img('foot_email.png');?>"/>
		  <a href="mailto:business@bs-travelling.com">business@bs-travelling.com</a>
	    </div>
      </div>
	  <p class="copyright">Copyright © 2011 - 2012 BS Business Travelling.</p>
    </div><!-- #footer -->
  </div><!-- #wrapper -->
</body>
</html>