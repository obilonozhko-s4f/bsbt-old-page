<div class="logo pr">
  <a href="<?=site_url();?>"><img src="<?=site_img('logo.png');?>"/></a>
  <div class="flags">
    <a href="<?=$this->lang->switch_uri('ru') . get_get_params();?>" <?=($this->lang->lang() == 'ru') ? 'class="active"' : ''?> ><img src="<?=site_img('ru_flag.png');?>"/></a>
    <a href="<?=$this->lang->switch_uri('en') . get_get_params();?>" <?=($this->lang->lang() == 'en') ? 'class="active"' : ''?>><img src="<?=site_img('en_flag.png');?>"/></a>
    <a href="<?=$this->lang->switch_uri('de') . get_get_params();?>" <?=($this->lang->lang() == 'de') ? 'class="active"' : ''?>><img src="<?=site_img('de_flag.png');?>"/></a>
  </div>
</div>
<div class="contacts" style="margin: 25px 0 0 0;">
  <div style="display: none;">
    <img src="<?=site_img('skype.png');?>"/>
    <p>Skype Hotline</p>
  </div>
  <div>
    <img src="<?=site_img('phone.png');?>"/>
    <p>+4917624615269</p>
  </div>
  <div style="margin: 0;">
    <img src="<?=site_img('mail.png');?>"/>
    <a href="mailto:business@bs-travelling.com">business@bs-travelling.com</a>
  </div>
</div>
<div class="clear"></div>
<div class="nav">
  <ul>
    <li <?=url_equals('/') || url_equals('/ru/') || url_equals('/de/')?'class="selected"':'';?>><a href="<?=site_url();?>"><?=lang('nav.li_name.home');?></a></li>
    <li <?=url_contains('/apartments')?'class="selected"':'';?>><a href="<?=site_url('apartments');?>"><?=lang('nav.li_name.object_сatalog');?></a></li>
    <li <?=url_contains('news')?'class="selected"':'';?>><a href="<?=site_url('news' . get_get_params());?>"><?=lang('nav.li_name.news');?></a></li>
    
    <? foreach($pages as $page): ?>
      <li <?=url_contains($page['page_url'])?'class="selected"':'';?> >
        <a <? if(empty($page['__children'])):?>
             href="<?=site_url($page['page_url'] . get_get_params());?>"
           <? else:?>style="cursor: default;"<? endif;?>><?=$page['name'];?></a>
        <? if(!empty($page['__children'])): ?>
          <ul class="submenu">
            <? foreach($page['__children'] as $page1): ?>
             <li>
                <a href="<?=site_url($page1['page_url']);?>"><?=$page1['name'];?></a>
             </li>
            <? endforeach; ?>
          </ul>
        <? endif; ?>
      </li>
    <? endforeach; ?>
  </ul>
</div>
<div class="clear"></div>