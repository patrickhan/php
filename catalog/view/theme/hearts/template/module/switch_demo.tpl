<div class="box">
  <div class="top"><img src="catalog/view/theme/default/image/category.png" alt="" />Designs</div>
  <div id="switch_demo" class="middle">
  
  <form method="post" action="/index.php" id="demo_form">
		<strong>Select Design:</strong>
		<br />
		<select name="template_switch" id="demo_switch">
			<option value=""></option> 
			<? foreach($all_templates as $all_templates_key){ ?>
						<option value="<?=$all_templates_key?>"><?=strtoupper($all_templates_key)?></option>
			<? } ?>		
		</select>
	</form>
	
	<script>
		$('#demo_switch').change(function() {
		   $('#demo_form').submit();
		});					
	</script>
  
  </div>
  <div class="bottom">&nbsp;</div>
</div>
