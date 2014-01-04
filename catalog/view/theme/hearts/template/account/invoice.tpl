<?echo $header;?>
<body>
<div style="page-break-after: always;">
  <h1>Invoice</h1>
  <div class="div1">
    <table width="100%">
      <tr>
        <td><?php echo $order[0]['store']; ?><br />
          <?php echo $order[0]['address']; ?><br />
          <?php echo $text_telephone; ?> <?php echo $order[0]['telephone']; ?><br />
          <?php if ($order[0]['fax']) { ?>
          <?php echo $text_fax; ?> <?php echo $order[0]['fax']; ?><br />
          <?php } ?>
          <?php echo $order[0]['email']; ?><br />
          <?php echo $order[0]['website']; ?></td>
        <td align="right" valign="top"><table>
            <tr>
              <td><b><?php echo $text_invoice_date; ?></b></td>
              <td><?php echo $order[0]['date_added']; ?></td>
            </tr>
            <tr>
              <td><b><?php echo $text_invoice_no; ?></b></td>
              <td><?php echo $order[0]['order_id']; ?></td>
            </tr>
          </table></td>
      </tr>
    </table>
  </div>
  <table class="address">
    <tr class="heading">
      <td width="50%"><b><?php echo $text_to; ?></b></td>
      <td width="50%"><b><?php echo $text_ship_to; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $order[0]['payment_address']; ?></td>
      <td><?php echo$order[0]['shipping_address']; ?></td>
    </tr>
  </table>
  <table class="product">
    <tr class="heading">
      <td><b><?php echo $column_product; ?></b></td>
      <td><b><?php echo $column_model; ?></b></td>
      <td align="right"><b><?php echo $column_quantity; ?></b></td>
      <td align="right"><b><?php echo $column_price; ?></b></td>
      <td align="right"><b><?php echo $column_total; ?></b></td>
    </tr>
    <?php foreach ($order[0]['product'] as $product) { ?>
    <tr>
      <td><?php echo $product['name']; ?>
        <?php foreach ($product['option'] as $option) { ?>
        <br />
        &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
        <?php } ?></td>
      <td><?php echo $product['model']; ?></td>
      <td align="right"><?php echo $product['quantity']; ?></td>
      <td align="right"><?php echo $product['price']; ?></td>
      <td align="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
    <?php foreach ($order[0]['total'] as $total) { ?>
    <tr>
      <td align="right" colspan="4"><b><?php echo $total['title']; ?></b></td>
      <td align="right"><?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>
</body>
<?/*
///////////////////////////////////////////////////////////////////////////////////////
  <div class="middle">
    <div class="light" style="padding: 10px; margin-bottom: 10px;">
      <table width="536">
        <tr>
          <td width="33.3%" valign="top"><b><?php echo $text_order; ?></b><br />
            #<?php echo $order_id; ?><br />
            <br />
            <b><?php echo $text_email; ?></b><br />
            <?php echo $email; ?><br />
            <br />
            <b><?php echo $text_telephone; ?></b><br />
            <?php echo $telephone; ?><br />
            <br />
            <?php if ($fax) { ?>
            <b><?php echo $text_fax; ?></b><br />
            <?php echo $fax; ?><br />
            <br />
            <?php } ?>
            <?php if ($shipping_method) { ?>
            <b><?php echo $text_shipping_method; ?></b><br />
            <?php echo $shipping_method; ?><br />
            <br />
            <?php } ?>
            <b><?php echo $text_payment_method; ?></b><br />
            <?php echo $payment_method; ?></td>
          <td width="33.3%" valign="top"><?php if ($shipping_address) { ?>
            <b><?php echo $text_shipping_address; ?></b><br />
            <?php echo $shipping_address; ?><br />
            <?php } ?></td>
          <td width="33.3%" valign="top"><b><?php echo $text_payment_address; ?></b><br />
            <?php echo $payment_address; ?><br /></td>
        </tr>
      </table>
    </div>
    <div class="light" style="padding: 10px; margin-bottom: 10px;">
      <table width="536">
        <tr>
          <th align="left"><?php echo $text_product; ?></th>
          <th align="left"><?php echo $text_model; ?></th>
          <th align="right"><?php echo $text_quantity; ?></th>
          <th align="right"><?php echo $text_price; ?></th>
          <th align="right"><?php echo $text_total; ?></th>
        </tr>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td align="left" valign="top"><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td align="left" valign="top"><?php echo $product['model']; ?></td>
          <td align="right" valign="top"><?php echo $product['quantity']; ?></td>
          <td align="right" valign="top"><?php echo $product['price']; ?></td>
          <td align="right" valign="top"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
      </table>
      <br />
      <div style="width: 100%; display: inline-block;">
        <table style="float: right; display: inline-block;">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td align="right"><?php echo $total['title']; ?></td>
            <td align="right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
    <?php if ($comment) { ?>
    <b style="margin-bottom: 2px; display: block;"><?php echo $text_comment; ?></b>
    <div class="light" style="padding: 10px; margin-bottom: 10px;"><?php echo $comment; ?></div>
    <?php } ?>
    <b style="margin-bottom: 2px; display: block;"><?php echo $text_order_history; ?></b>
    <div class="light" style="padding: 10px; margin-bottom: 10px;">
      <table width="536">
        <tr>
          <th align="left"><?php echo $column_date_added; ?></th>
          <th align="left"><?php echo $column_status; ?></th>
          <th align="left"><?php echo $column_comment; ?></th>
        </tr>
        <?php foreach ($historys as $history) { ?>
        <tr>
          <td valign="top"><?php echo $history['date_added']; ?></td>
          <td valign="top"><?php echo $history['status']; ?></td>
          <td valign="top"><?php echo $history['comment']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> */?>