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
        <? if(isset($info['vat_nr']) && !empty($info['vat_nr'])):?>
        	VAT Nr.: <?=$info['vat_nr'];?>
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
      <p style="font-weight: bold; font-size: 13px; margin: 0;">INVOICE</p>
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
        <span style="font-size: 13px; font-weight: bold;">Apartment address:</span> <?=$order['apartment']['street'];?>, <?=$order['apartment']['post_index'];?>, <?=$order['apartment']['city']['title'];?>
      </p>
    </td>
  </tr>
</table>

<table cellpadding="0" width="100%" cellspacing="0" border="0" style="margin: 5px 0 0 0;">
  <tr>
    <td style="border-bottom: 1px solid #000;" align="left">
      <p>Issue</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <p>Number(s)</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <p>Price per night</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <p>Night(s)</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <p>EUR</p>
    </td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #000;" align="left">
      <p>Apartment</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <p>1</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <? if(isset($info['added_person']) && !empty($info['added_person'])):?>
        <p><?=$order['apartment']['price_out'] + $order['apartment']['add_person_out'];?></p>
      <? else:?>
        <p><?=$order['apartment']['price_out'];?></p>
      <? endif;?>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <p><?=$info['nights'];?></p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <p><?=$info['apn_cost'];?></p>
    </td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #000;" align="left">
      <p>Shuttle Service</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"><? if(isset($info['transfer']) && $info['transfer'] == TRUE):?><p>+</p><? else:?><p>-</p><? endif;?></td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #000;" align="left">
      <p>Other Service</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #000;" align="left">
      <p>Breakfast</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <? if(isset($info['breakfast']) && $info['breakfast'] == TRUE):?>
        <? if(isset($info['added_person'])):?>
          <p><?=$order['persons']+1;?></p>
        <? else:?>
          <p><?=$order['persons'];?></p>
        <? endif;?>
      <? endif;?>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <? if(isset($info['breakfast']) && $info['breakfast'] == TRUE):?>
        <p><?=$info['br_cost'];?></p>
      <? endif;?>
    </td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #000;" align="left">
      <p>VAT 7%(Included)</p>
    </td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center"></td>
    <td style="border-bottom: 1px solid #000;" align="center">
      <p><?=number_format($info['vat'], 2, '.', '');?></p>
    </td>
  </tr>
</table>

<table style="margin-top: 20px; border: 1px solid #000;" width="100%" height="25" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" height="25" width="100%">
      <p style="padding-left: 15px; font-weight: bold; font-size: 14px;">Invoice amount</p>
    </td>
    <td align="right" height="25" width="100px">
      <p style="padding-right: 15px; font-weight: bold; width: 100px; font-size: 14px;"><?=number_format($info['total_cost'], 2, '.', '');?> &#8364;</p>
    </td>
  </tr>
</table>

<p style="margin-bottom: 0px; font-size: 11px;">Payment should be made within 7 working days from the moment of reception of this letter.</p>
<p style="margin-top: 0px; font-size: 11px;">Please make sure to use your invoice number with payment.<br />
														<span style="color: red;">If you want to proceed with your personal credit card account please contact us and we will send the form, to fill in.</span><br />
														We will charge the payment after your confirmation.<br />
														After the payment we will send you confirmation letter (Voucher) with full address of the apartment and additional information.<br />
                            Cancelation policy - is "NON REFUNDABLE Reservation". For more details see our webpage on <a href="http://bs-travelling.com">www.bs-travelling.com</a><br />
                            Concerning bank transactions from abroad please make sure that the full invoice amount will be credited completely on our account. If we do not receive the full invoice amount we will not be able to confirm the booking.<br />
                            In case of any damage made to the property of the landlord, the client must pay the penalty to the company or landlord.<br />
                            Our contact phone: +4917624615269, Oleksandr.
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