<table cellpadding="0" width="100%" cellspacing="0" border="0" style="margin: 0px;">
  <tr>
    <td align="right">
      <p style="font-style: italic; margin: 0;">
        Inh.O.Bilonozhko<br />
        Marienwerderstr.11<br />
        30823 Garbsen<br />
        www.bs-travelling.com<br />
        business@bs-travelling.com
      </p>
    </td>
  </tr>
</table>

<table cellpadding="0" width="100%" cellspacing="0" border="0" style="margin: 20px 0 0 0;">
  <tr>
    <td align="left">
      <p style="font-size: 10px; margin: 0 0 20px 0;">Biloonozhko.O. Marienwerderstr.11,30823 Garbsen</p>
      <p style="font-weight: bold;"><?=$order['apartment']['landlord_name'];?><br />
         <?=$order['apartment']['street'];?>, <?=$order['apartment']['house'];?><br />
         <?=$order['apartment']['post_index'];?>, <?=$order['apartment']['city']['title'];?></p>
    </td>
    <td align="right" style="vertical-align: top;">
      <p style="margin: 0;">Hannover, <?=convert_date($order['created'], 'm-d-Y');?></p>
    </td>
  </tr>
</table>
<p style="margin: 0 0 20px 0;">VERBINDLICHE BUCHUNG(Keine Buchungsanfrage)</p>
<p style="margin: 0 0 5px 0; font-weight: bold;">Objekt ID: <?=$info['apartment_id'];?></p>
<p style="margin: 0 0 5px 0;">Gast:</p>
<? if(isset($info['persons_arr_info']) && !empty($info['persons_arr_info'])):?>
  <? if(is_array($info['persons_arr_info'])):?>
  	<ol style="margin: 0;">
		  <? foreach ($info['persons_arr_info'] as $p):?>
		    <li><?=$p?></li>
		  <? endforeach;?>
	  </ol>
  <? else:?>
  	<p style="margin: 0;"><?=$info['persons_arr_info']?></p>
  <? endif;?>
<? endif;?>
<p style="margin: 0;">Address: <?=$info['street'];?>, <?=$info['home'];?>, <?=$info['zip'];?>, <?=$info['city'];?>, <?=$info['country'];?></p>
<p style="margin: 0 0 5px 0;">Phone: <?=$info['phone'];?></p>
<p style="margin: 0;">Vermieter: <?=$order['apartment']['landlord_name'];?></p>
<p style="margin: 0;">Vermittelt durch: Bilonozhko Oleksandr, BS Business Travelling mit Sitz in D-30823<br />
                      Garbsen,Marienwerderstr.11
</p>
<p style="margin: 0;">Laufzeit:<br />
                      Die Buchungsbest&auml;tigung gilt f&uuml;r die Dauer der Buchung:
</p>
<p style="margin: 0; font-weight: bold;">Anreise: <?=$order['date_from'];?> Abreise: <?=$order['date_to'];?> (<?=$info['nights'];?> N&auml;chte)</p>
<p style="margin: 0;">Zahlungsabwicklung:<br />
                      Bei einer erfolgreichen Belegung zahlt BS Business Travelling die vom Gast erhaltene Mietsumme innerhalb von 30 Tage nach Abreise auf das angegebene Konto des Vermieters aus, ODER in Barzahlung. Bei der kurzfristigen Beherbergung in Privatunterk&uuml;nften handelt es sich um steuerpflichtige Eink&uuml;nfte. Der Vermieter verpflichtet sich, die steuerlichen Gegebenheiten eigenverantwortlich zu regeln.
</p>
<p style="margin: 0;">Wohnung: <? if(isset($info['breakfast']) && $info['breakfast'] == TRUE):?><?=$info['total_cost_in']-$info['br_cost_in'];?><? else:?><?=$info['total_cost_in'];?><? endif;?> Euro</p>
<p style="margin: 0;">Fr&uuml;hst&uuml;ck: <? if(isset($info['breakfast']) && $info['breakfast'] == TRUE):?><?=$info['br_cost_in'];?><? else:?>0<? endif;?> Euro</p>
<p style="font-weight: bold; margin: 0;">Die Gesamtmietsumme betr&auml;gt: <?=$info['total_cost_in'];?> Euro.</p>
<p style="font-weight: bold; margin: 0;">Bitte senden Sie ein Kopie innerhalb von 7 Tage unterschrieben zur&uuml;ck(Per Fax,e-mail,Post).</p>

<table width="100%" height="15" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td height="15" width="100%"></td>
  </tr>
</table>

<table style="margin-top: 20px; border: 0;" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" width="100%">
      <p style="margin: 0; width: 251px; height: 105px; background-image:url('<?=site_img('email_sign.png');?>');">Mit freundlichen Gr&uuml;&szlig;en<br />
         O.Bilonozhko<br />
         BS Business Travelling</p>
    </td>
    <td align="right" width="100%" style="vertical-align: top;">
      <p style="width: 200px; padding-right: 15px;">Hannover, <?=convert_date($order['created'], 'm-d-Y');?></p>
    </td>
  </tr>
</table>