<p style="margin: 5px 0 15px 0; text-decoration: underline; font-size: 10px; color: #000;">BS Business Travelling,Inh.O.Bilonozhko Marienwerderstr.11,30823 Garbsen</p>

<table cellpadding="0" width="100%" cellspacing="0" border="0" style="margin: 0px;">
  <tr>
    <td align="left">
      <p style="font-weight: bold; font-size: 13px; line-height: 16px; margin: 0 0 5px 0;">
        <?=$info['name'];?> <?=$info['surname'];?><br />

        <? if(isset($info['org']) && !empty($info['org'])):?>
          <?=$info['org'];?><br />
        <? endif;?>

        <?=$info['street'];?>, <?=$info['home'];?>, <?=$info['office'];?><br />
        <?=$info['zip'];?>, <?=$info['city'];?><br />
        <?=$info['country'];?><br />
        <? if(isset($info['vat']) && !empty($info['vat'])):?>
        	VAT Nr.: <?=$info['vat'];?>
       	<? endif;?>
      </p>
    </td>
    <td style="vertical-align: top;" align="right">
      <p style="font-weight: bold; font-size: 13px; line-height: 16px; margin: 0;">Date: <?=convert_date($order['created_at'], 'm-d-Y');?></p>
    </td>
  </tr>
</table>

<table cellpadding="0" width="100%" cellspacing="0" border="0" style="margin: 0px;">
  <tr>
    <td align="left">
      <p style="font-weight: bold; font-size: 13px; margin: 0;">Confirmation / Voucher</p>
      <p>We appreciate your attention to our company.<br />
         With this we confirm your reservation of the apartment for <?=isset($info['added_person'])?$order['persons']+1:$order['persons'];?> person(s).
      </p>
    </td>
    <td style="vertical-align: top;" align="right">
      <p style="font-size: 13px; margin-bottom: 0px;">Invoice Nr.: <?=$order['code'];?></p>
      <p style="font-size: 10px; margin-top: 0px;">Please quote with payment!</p>
    </td>
  </tr>
</table>

<table cellpadding="0" width="100%" cellspacing="0" border="0" style="margin: 0px;">
  <tr>
    <td align="left">
      <p style="margin: 0;">
        <span style="font-size: 13px; font-weight: bold;">Arrival:</span> <?=$order['date_from'];?> - <span style="font-size: 13px; font-weight: bold;">Departure:</span> <?=$order['date_to'];?>, <?=$info['nights'];?> night(s), <? if(isset($info['breakfast']) && $info['breakfast'] == TRUE):?>Breakfast: included<? else:?>Breakfast is not included<? endif;?><br />
        <span style="font-size: 13px; font-weight: bold;">Apartment address:</span> <?=$order['apartment']['street'];?>, <?=$order['apartment']['house'];?>, <?=$order['apartment']['post_index'];?>, <?=$order['apartment']['city']['title'];?><br />
        <span style="font-size: 13px; font-weight: bold;">Landlord:</span> <?=$order['apartment']['flat_num'];?><br />
        <span style="font-size: 13px; font-weight: bold;">Phone:</span>: <?=$order['apartment']['c_pr_phone'];?>
      </p>
    </td>
  </tr>
</table>

<p style="font-size: 11px;">
	The keys will be passed to you at the time of check-in, direct in apartment (please, inform us about your arrival time).<br />
	Do not forget that this is a private apartment.<br />
	Cleaning in apartment will be made every third day, but please, try to keep apartment in order too.<br /><br />

	When check-out you can put the keys on the table in the apartment and close the door. Or you can arrange<br />
	with our manager or landlord about your check-out time to pass the keys.<br /><br />

	You can find map of public transport of Hannover in attach. Main train station in Hannover is Kr&ouml;pke or<br />
	Hauptbahnhof. Exhibition Center tram stops is Messe Nord (Tram Nr. 8,18), Messe Ost(Tram Nr. 6,16),<br />
	Laatzen/Eichstra&szlig;e(Bahnhof) (Tram Nr.1 and 2)<br /><br />

	Cancelation policy - is "NON REFUNDABLE Reservation". For more details see our webpage on<br />
	<a href="http://www.bs-travelling.com">www.bs-travelling.com</a><br /><br />

	In case of any damage made to the property of the landlord, the client must pay the penalty to the company<br />
	or landlord.<br />
	Our service number: +4917624615269 (Phone, Whats App)<br />
</p>

<table style="margin-top: 5px; margin-bottom: 10px; border: 0;" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" width="100%">
      <p style="margin: 0; width: 251px; height: 105px; background-image:url('<?=site_img('email_sign.png');?>');">Best wishes,<br />
         Oleksandr Bilonozhko<br />
         BS Business Travelling</p>
    </td>
    <td align="right" width="100%" style="vertical-align: top;">
      <p style="width: 200px; padding-right: 15px;"><?=convert_date($order['created'], 'm-d-Y');?> Hannover</p>
    </td>
  </tr>
</table>