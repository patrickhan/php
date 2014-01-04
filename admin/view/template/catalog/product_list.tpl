<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?><h3>Show: <input type="radio" name="set" onClick='window.location="index.php?route=catalog/product/setOn"' <?if ($alls) {?>checked='yes'<?}?> value='On'/>All <input type="radio" name="set" onClick='window.location="index.php?route=catalog/product/setOff"' <?if (!$alls && !$dis) {?>checked='yes'<?}?> value='Off'/>Enabled Only <input type="radio" name="set" onClick='window.location="index.php?route=catalog/product/setDis"' <?if ($dis) {?>checked='yes'<?}?> value='Dis'/>Disabled Only</h3>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/product.png');"><?php echo $heading_title; ?></h1>	
    <div class="buttons"><a class="tooltip"><?echo $qmark;?><span>Sets a URL Alias for every product that doesn't have one, making your site more search-engine friendly</span></a><a onclick="location='<?php echo $sAlias; ?>'" class="button"><span>Set Alias</span></a> <a class="tooltip"><?echo $qmark;?><span>All products without metatags take the Meta Description and Meta Keywords from the category they're in</span></a><a onclick="location='<?php echo $sMeta; ?>'" class="button"><span>Set Metatags</span></a><a onclick="location='<?php echo $viewCat; ?>'" class="button"><span>View Categories</span></a><a onclick="location='<?php echo $viewAll; ?>'" class="button"><span>View All</span></a>   <a onclick="location='<?php echo $insert; ?>'" class="button"><span><?php echo $button_add_product; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <?if ($view == 'categories') {
	  if ($cat2) {
	  
	  }
	  else
	  {
	  ?><div class='biglink'><?
	  ?>Select Produt Category To View<br><?
	  foreach ($cats as $cat1)
	  {?>
	 
	  <a href='index.php?route=catalog/product&view=categories&category_id=<?echo $cat1['num'];?>'><?echo $cat1['cat'];?></a><br>
	  <?}
	  
	  ?></div><?
	  }
	  $count = 0;
	  
	  
	  /*<?
	  foreach ($cat as $products) {
	   ?>
	  <a onclick='toggle(<?echo $count;?>)'><?echo $cats[$count]['cat'];?></a><br>
	  <div style='display:none' name='categ<?echo $count;?>' id='categ<?echo $count;?>'>
	 */ ?>
	 
	  
	  <? if ($catprods) {?>
	  <table class="list">
        <thead>
          <tr>
            <td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="center"><?php echo $column_image; ?></td>
            <td class="left">
              <?php echo $column_name; ?>
             </td>
           <td class="left">
              <?php echo $column_model; ?>
             </td>
            <td class="left">
              <?php echo $column_quantity; ?>
             </td>
			<td class="left">
              <?php echo $column_category; ?>
             </td>
           <td class="left">
              <?php echo $column_status; ?>
             </td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
		
		<?/*?>
		
          <tr class="filter">
            <td></td>
            <td></td>
            <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
            <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
            <td align="right"><input type="text" size="3" name="filter_quantity" value="<?php echo $filter_quantity; ?>" style="text-align: right;" /></td>
            <td><select name="filter_category">
              <option value="*"></option>
			  <?php if ($categories) { ?>
			  <?php foreach ($categories as $category) { ?>
			  <?php if ($filter_category == $category['category_id']) { ?>
              <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
			  <?php } else { ?>
              <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
			  <?php } ?>
			  <?php } ?>
			  <?php } ?>
            </select></td>
            <td><select name="filter_status">
                <option value="*"></option>
                <?php if ($filter_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
                <?php if (!is_null($filter_status) && !$filter_status) { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
		  
		  <?*/?>
		  
          <?php if ($products) { ?>
          <?php foreach ($products as $key => $product) {?>
          <tr class="row" id="row<?php echo $key; ?>" >
            <td style="align: center;"><?php if ($product['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
              <?php } ?></td>
            <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
            <td class="left"><?php echo $product['name']; ?></td>
            <td class="left"><?php echo $product['model']; ?></td>
            <td class="right"><?php if ($product['quantity'] <= 0) { ?>
              <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
              <?php } elseif ($product['quantity'] <= 5) { ?>
              <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
              <?php } else { ?>
              <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
              <?php } ?></td>
			<td class="left">
			<?php if ($product['categories']) { ?>
			  <ul class="secret">
              <?php foreach ($product['categories'] as $product_category) { ?>
			    <li><?php echo $product_category; ?></li>
			  <?php } ?>
			  </ul>
			<?php } ?></td>
			</td>
            <td class="left"><?php echo $product['status']; ?></td>
            <td class="right"><?php foreach ($product['action'] as $action) { ?>
              [ <a <?php echo $action['js']; ?> href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
	  </div>
	  <? $count++; } } else { ?><table class="list">
        <thead>
          <tr>
            <td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="center"><?php echo $column_image; ?></td>
            <td class="left"><?php if ($sort == 'pd.name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'p.model') { ?>
              <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
              <?php } ?></td>
            <td class="right"><?php if ($sort == 'p.quantity') { ?>
              <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
              <?php } ?></td>
			<td class="left"><?php if ($sort == 'p.category') { ?>
              <a href="<?php echo $sort_category; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_category; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_category; ?>"><?php echo $column_category; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'p.status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td></td>
            <td></td>
            <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
            <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
            <td align="right"><input type="text" size="3" name="filter_quantity" value="<?php echo $filter_quantity; ?>" style="text-align: right;" /></td>
            <td><select name="filter_category">
              <option value="*"></option>
			  <?php if ($categories) { ?>
			  <?php foreach ($categories as $category) { ?>
			  <?php if ($filter_category == $category['category_id']) { ?>
              <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
			  <?php } else { ?>
              <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
			  <?php } ?>
			  <?php } ?>
			  <?php } ?>
            </select></td>
            <td><select name="filter_status">
                <option value="*"></option>
                <?php if ($filter_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
                <?php if (!is_null($filter_status) && !$filter_status) { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($products) { ?>
          <?php foreach ($products as $key => $product) { ?>
          <tr class="row" id="row<?php echo $key; ?>" >
            <td style="align: center;"><?php if ($product['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" onclick="highlight(this, 'row<?php echo $key; ?>')" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" onclick="highlight(this, 'row<?php echo $key; ?>')" />
              <?php } ?></td>
            <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
            <td class="left"><?php echo $product['name']; ?></td>
            <td class="left"><?php echo $product['model']; ?></td>
            <td class="right"><?php if ($product['quantity'] <= 0) { ?>
              <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
              <?php } elseif ($product['quantity'] <= 5) { ?>
              <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
              <?php } else { ?>
              <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
              <?php } ?></td>
			<td class="left">
			<?php if ($product['categories']) { ?>
			  <ul class="secret">
              <?php foreach ($product['categories'] as $product_category) { ?>
			    <li><?php echo $product_category; ?></li>
			  <?php } ?>
			  </ul>
			<?php } ?></td>
			</td>
            <td class="left"><?php echo $product['status']; ?></td>
            <td class="right"><?php foreach ($product['action'] as $action) { ?>
              [ <a <?php echo $action['js']; ?> href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
	  <?}?>
    </form>
	 <?if ($view != 'categories') {?>
    <div class="pagination"><?php echo $pagination; ?></div>
	<?}?>
  </div>
</div>
<script type="text/javascript"><!--
function toggle(id) {

var divname = "categ";
var divname1 = divname+id;
var test = document.getElementById(divname1);



if (document.getElementById(divname1).style.display = "block") {
 if (document.getElementById) { // DOM3 = IE5, NS6 
document.getElementById('"'+divname1+'"').style.display = 'none'; 

} 
else {
if (document.layers) { // Netscape 4 
document.divname1.display = 'none'; 

}
else {// IE 4 
document.all.divname1.style.display = 'none'; 

}
}
}
else
{
 if (document.getElementById) { // DOM3 = IE5, NS6 
document.getElementById(divname1).style.display = 'block'; 

} 
else {
if (document.layers) { // Netscape 4 
document.divname1.display = 'block'; 

}
else {// IE 4 
document.all.divname1.style.display = 'block'; 

}
}
}
}
function filter() {
	url = 'index.php?route=catalog/product';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
	
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	
live	}
	var filter_category = $('select[name=\'filter_category\']').attr('value');
	
	if (filter_category != '*') {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}

	location = url;
}

function confirmSubmit() {
	if (confirm("Are you sure you wish to delete this product?")) {
		return true;
	} else {
		return false;
	}
}

//--></script>
<?php echo $footer; ?>