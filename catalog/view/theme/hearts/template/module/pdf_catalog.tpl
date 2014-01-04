<div class="box">
  <div class="top"><img src="catalog/view/theme/default/image/brands.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle" style="text-align: center;">
    	<select style="width:160px;" onchange="fn_pdf_category(this.options[this.selectedIndex].value);">
    		<option value=""><?php echo $text_select; ?></option>
			<option value="0"><?php echo $text_all_categories; ?></option>
			<?php
				if(!empty($categories))
				{
					foreach($categories as $key => $category)
					{
			?>
			<option value="<?php echo $category['category_id']?>"><?php echo $category['name'];?></option>
			<?php
					}
				}
			?>
    	</select>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<script type="text/javascript">
	function fn_pdf_category(category_id)
	{
		if(category_id != "")
		{
			window.open('<?php echo $pdf_catalog_href;?>'+category_id, 'window_pdf', 'width=800, height=600, toolbar=1, resizable=1');
		}
	}
</script>