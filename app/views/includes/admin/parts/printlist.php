<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xml:lang="en" >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
    <title><?=lang('admin.printlist.' . $entityName . '.title');?></title>
    
    <link rel="shortcut icon" href="<?=site_img("favicon.ico");?>" type="image/x-icon" />
    
    <link rel="stylesheet" type="text/css" href="<?=site_css("common/zero.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/base.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/list.css");?>"/>

    <!--[if IE 6]>
      <link href="<?=site_css("style_ie.css");?>" media="screen" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?=site_js("iepngfix_tilebg.js");?>"></script>
    <![endif]-->
    
    <script type="text/javascript">
      window.onload = function() {
        window.print();
      };
    </script>
  </head>
  <body>
    <div class="inner inner-table" style="width: 1100px; margin: 0 auto; overflow-x: auto;">
      <table id="list_<?=$entityName;?>" class="table" style="font-size: 10px;">
        <thead>
          <tr>
            <th class="first">№</th>
            <?foreach($params as $param):?>
              <? if(is_array($param)): ?>
                <? $tmp = ""; ?>
                <? foreach ($param as $pk => $pv): ?>
                <? $tmp .= $pk . '.' . $pv; ?>
                <? break; ?>
                <? endforeach; ?>
                <th class="<?=sort_by_get_class($tmp);?>"><?=lang("admin.entity_list." . $entityName . "." . $tmp)?></th>
              <? else: ?>
                <? $ordArr = explode(' ', $defOrderBy); ?>
                <? if ($ordArr[0] == $param && sort_by_has_prefix(current_url()) === FALSE): ?>
                <? $sClass = (strpos($defOrderBy, 'DESC') !== FALSE)?'desc':'asc'; ?>
                  <th class="<?=$sClass?>"><?=lang("admin.entity_list." . $entityName . "." . $param)?></th>
                <? else: ?>
                  <th class="<?=sort_by_get_class($param);?>"><?=lang("admin.entity_list." . $entityName . "." . $param)?></th>
                <? endif; ?>
              <? endif; ?>
            <?endforeach;?>
          </tr>
        </thead>
        <? $rowNumStart = isset($pager) ? $pager->getFirstIndice() - 1 : 0; ?>
        <?$rowNum = 1;?>
        <tbody  <?= $isListSortable ? 'class="sortable"' : '' ?>>
        <?foreach($entities as $el):?>
          <? $oel = $el; ?>
          <? $el = array_make_plain_with_dots($el); ?>
          <tr class="<?=($rowNum % 2 == 0)?"even":"odd"?>">
            <?$rowNumber = $rowNum + $rowNumStart; ?>
            <td>
              <?=$rowNumber?>
            </td>
            <?foreach($params as $param):?>
              <? $image = FALSE; ?>
              <? if(is_array($param)): ?>
                <? foreach ($param as $pk => $pv): ?>
                  <? if (isset($oel[$pk]['id'])): ?>
                    <? $viewVal = $oel[$pk][$pv]; ?>
                    <? if (!in_array($pk, $listViewIgnoreLinks)): ?>
                      <? if(isset($oel[$pk]['type']) && $oel[$pk]['type'] == 'image'): ?>
                        <? $image = TRUE; ?>
                        <? $viewVal = $oel[$pk]; ?>
                      <? else: ?>
                        <? $url = $pk; ?>
                        <? if(isset($listViewLinksRewrite[$pk])): ?>
                          <? $url = $listViewLinksRewrite[$pk]; ?>
                        <? endif; ?>
                        <? $link = site_url("$adminBaseRoute/" . $url . "/add_edit/" . $oel[$pk]['id']); ?>
                      <? endif; ?>
                    <? endif; ?>
                    <? break; ?>
                  <? else: ?>
                    <? $viewVal = array(); ?>
                    <? $link = array(); ?>
                    <? if ($el[$pk]): ?>
                      <? foreach($el[$pk] as $ent): ?>
                      <? $viewVal[] = $ent[$pv]; ?>
                      <? if (!in_array($pk, $listViewIgnoreLinks)): ?>
                        <? $url = $pk; ?>
                        <? if(isset($listViewLinksRewrite[$pk])): ?>
                          <? $url = $listViewLinksRewrite[$pk]; ?>
                        <? endif; ?>
                        <? $link[] = site_url("$adminBaseRoute/" . $url . "/add_edit/" . $ent['id']); ?>
                      <? endif; ?>
                      <? endforeach; ?>
                    <? endif; ?>
                  <? endif; ?>
                <? break; ?>
                <? endforeach; ?>
                <? if (is_array($viewVal) && !$image) : ?>
                  <td>
                  <? for($ii = 0; $ii < count($viewVal); $ii++): ?>
                    <? if(isset($link[$ii])): ?><a href="<?=$link[$ii];?>"><?endif;?><?=trim($viewVal[$ii]);?><? if(isset($link[$ii])): ?></a><?endif;?><? if ($ii != count($viewVal) - 1): ?>, <? endif; ?>
                  <? endfor; ?>
                  </td>
                <? else: ?>
                  <? if($image): ?>
                    <td><img src="<?=site_image_thumb_url('_admin', $viewVal); ?>"/></td>
                  <? else: ?>
                    <td><?if(isset($link)):?><a href="<?=$link;?>"><?endif;?><?=trim($viewVal);?><?if(isset($link)):?></a><?endif;?></td>
                  <? endif; ?>
                <? endif; ?>
              <? else: ?>
                <? if (isset($el[$param])): ?>
                  <? if (is_bool($el[$param])): ?>
                    <td><? if ($el[$param]): ?><?=lang('admin.yes')?><? else: ?><?=lang('admin.no')?><? endif; ?></td>
                  <? else: ?>
                    <? if(isset($listViewValuesRewrite[$param]) && isset($listViewValuesRewrite[$param][$el[$param]])): ?>
                      <? $el[$param] = $listViewValuesRewrite[$param][$el[$param]]; ?>
                    <? endif; ?>
                    <? $langValueKey = 'enum.' . strtolower($entityName) . '.' . $param . '.' . $el[$param]; // poduct.type.PUBLISHED ?>
                    <? if(lang_exists($langValueKey)): ?>
                      <? $el[$param] = lang($langValueKey); ?>
                    <? endif; ?>
                    <td><? if(isset($search["search_string"]) && in_array($param, explode(',', $search['search_in']))): ?><?=highlight_phrase(htmlspecialchars($el[$param]), $search["search_string"],'<span style="color:#F00">', '</span>')?><?else:?><?=character_limiter(htmlspecialchars($el[$param]), Base_Admin_Controller::ENTITY_LIST_STRING_LIMIT)?><? endif; ?></td>
                  <? endif; ?>
                <? else : ?>
                  <td></td>
                <? endif; ?>
                
              <? endif; ?>
            <?endforeach;?>
          </tr>
          <?$rowNum++; ?>
        <?endforeach;?>
        </tbody>
      </table>
    </div>
  </body>
</html>