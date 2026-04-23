<?php
// RU: Подготовка данных для вывода
// EN: Preparing data for output
$guestName = trim((isset($info['name']) ? $info['name'] : '') . ' ' . (isset($info['surname']) ? $info['surname'] : ''));
if ($guestName === '') $guestName = 'Guest';

$apartmentTitle = 'Apartment';
if (isset($order['apartment']['city']['title']) && !empty($order['apartment']['city']['title'])) {
  $apartmentTitle .= ' in ' . $order['apartment']['city']['title'];
}

if (isset($order['apartment']['objecttype']['name']) && !empty($order['apartment']['objecttype']['name'])) {
  $apartmentTitle = $order['apartment']['objecttype']['name'] . ' in ' . $order['apartment']['city']['title'];
}

$propertyUrl = '';
if (isset($order['apartment']['id']) && !empty($order['apartment']['id'])) {
  $propertyUrl = site_url('object/' . $order['apartment']['id']);
}
?>

<p style="margin:0 0 18px 0; font-size:24px; line-height:32px; font-weight:bold; color:#111827;">
    We received your request <?=$order['code'];?> - availability confirmation within 24 hours
</p>

<p style="margin:0 0 14px 0; font-size:15px; line-height:24px; color:#374151;">
  Dear <?=$guestName;?>,
</p>

<p style="margin:0 0 14px 0; font-size:15px; line-height:24px; color:#374151;">
  Thank you for your booking request with <strong>BS Business Travelling</strong>.
</p>

<p style="margin:20px 0 8px 0; font-size:16px; line-height:24px; font-weight:bold; color:#111827;">
  Important Information
</p>

<p style="margin:0 0 14px 0; font-size:15px; line-height:24px; color:#374151;">
  Your request has been successfully received and is currently being reviewed by the apartment owner.
  This process usually takes up to 24 hours.
</p>

<p style="margin:0 0 14px 0; font-size:15px; line-height:24px; color:#374151;">
  Once the owner confirms availability, we will send you an email and payment request
  (bank transfer or credit card payment). After the payment we will send you a voucher.
</p>

<p style="margin:0 0 22px 0; font-size:15px; line-height:24px; color:#374151;">
  If the apartment owner is unable to confirm your request, our team will contact you
  to offer suitable alternative accommodation options.
</p>

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse; margin:0 0 24px 0; border:1px solid #e5e7eb;">
  <tr>
    <td colspan="2" style="padding:14px 16px; font-size:18px; line-height:24px; font-weight:bold; color:#111827; background:#f9fafb; border-bottom:1px solid #e5e7eb;">
      Summary of Request
    </td>
  </tr>

  <tr>
    <td style="padding:12px 16px; width:210px; font-size:14px; line-height:22px; color:#111827; font-weight:bold; border-bottom:1px solid #e5e7eb;">
      Request No.
    </td>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#374151; border-bottom:1px solid #e5e7eb;">
      <?=$order['code'];?>
    </td>
  </tr>

  <tr>
    <td style="padding:12px 16px; width:210px; font-size:14px; line-height:22px; color:#111827; font-weight:bold; border-bottom:1px solid #e5e7eb;">
      Reservation ID
    </td>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#374151; border-bottom:1px solid #e5e7eb;">
      <?=$order['id'];?>
    </td>
  </tr>

  <tr>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#111827; font-weight:bold; border-bottom:1px solid #e5e7eb;">
      Apartment
    </td>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#374151; border-bottom:1px solid #e5e7eb;">
      <?=$apartmentTitle;?>
    </td>
  </tr>

  <tr>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#111827; font-weight:bold; border-bottom:1px solid #e5e7eb;">
      Stay
    </td>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#374151; border-bottom:1px solid #e5e7eb;">
      <?=convert_date($order['date_from'], 'j. F Y');?> - <?=convert_date($order['date_to'], 'j. F Y');?>
    </td>
  </tr>

  <tr>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#111827; font-weight:bold; border-bottom:1px solid #e5e7eb;">
      Apartment ID
    </td>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#374151; border-bottom:1px solid #e5e7eb;">
      <?=$order['apartment']['id'];?>
      <? if(!empty($propertyUrl)): ?>
        (<a href="<?=$propertyUrl;?>" target="_blank" style="color:#2563eb; text-decoration:none;">Click to view property</a>)
      <? endif; ?>
    </td>
  </tr>

  <tr>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#111827; font-weight:bold;">
      Total Amount
    </td>
    <td style="padding:12px 16px; font-size:14px; line-height:22px; color:#111827; font-weight:bold;">
            <?=number_format($info['total_cost'], 2, ',', '.');?> EUR
    </td>
  </tr>
</table>

<p style="margin:0; font-size:15px; line-height:24px; color:#374151;">
  Best regards,<br />
  <strong>BS Business Travelling</strong>
</p>