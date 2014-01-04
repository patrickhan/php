<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/banner.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
  <?php $image_row = 0; ?>
  <a onclick="addImage()" class="button"><span>Add Image</span></a><br><br>
  <form name="form4" enctype="multipart/form-data" method="post" action="index.php?route=extension/image_delete&sub=<?php echo $subfolder; ?>">
  <div id="images"></div>
  <div id="upload"></div>
  <div id="close"></form></div>
<table>
					<tbody id="image_row<?php echo $image_row; ?>">
		<?php echo $folders; ?>
		<br>
		 <form action="index.php?route=extension/image_delete" method="post">
		<input type="submit" name="Delete" value="Delete!" />
		<?php echo $code; ?>
		<input type="submit" name="Delete" value="Delete!" />
    </form>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript"><!--

var image_row = <?php echo $image_row; ?>;



function addImage() {


    uploadBoxes = '<input name="uploadFile' + image_row + '" type="file" id="uploadFile' + image_row + '" />';

$('#images').append(uploadBoxes);

image_row++;
	
	addEnd();
}

function addEnd()
{
	html = '<input name="uploadsNeeded" type="hidden" value="' + image_row + '" /><input type="submit" name="Upload" value="Upload" />';
	$('#upload').html(html);
}
</script>