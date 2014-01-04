<?php echo $header; ?>

<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>

<?php } ?>

<div class="box">

  <div class="left"></div>

  <div class="right"></div>

  <div class="heading">

    <h1 style="background-image: url('view/image/category.png');"><?php echo $heading_title; ?></h1>

    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>

  </div>

  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <div class="tabs">

        <?php foreach ($languages as $language) { ?>

        <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>

        <?php } ?>

      </div>

      <?php foreach ($languages as $language) { ?>

      <div id="language<?php echo $language['language_id']; ?>">

        <table class="form">

          <tr>

            <td><span class="required">*</span> <?php echo $entry_name; ?></td>

            <td><input name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" />

              <?php if (isset($error_name[$language['language_id']])) { ?>

              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>

              <?php } ?></td>

          </tr>

          <tr>

            <td><?php echo $entry_meta_keywords; ?></td>

            <td><textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="40" rows="5"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea></td>

          </tr>

          <tr>

            <td><?php echo $entry_meta_description; ?></td>

            <td><textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>

          </tr>

          <tr>

            <td><?php echo $entry_description; ?></td>

            <td><textarea name="category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea></td>

          </tr>

        </table>

      </div>

      <?php } ?>

      <table class="form">

        <tr>

          <td><span class="required">*</span> <?php echo $entry_status; ?></td>

            <td><select name="status">

              <?php if ($status) { ?>

              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

              <option value="0"><?php echo $text_disabled; ?></option>

              <?php } else { ?>

              <option value="1"><?php echo $text_enabled; ?></option>

              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

              <?php } ?>

          </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_category; ?></td>

          <td><select name="parent_id">

              <option value="0"><?php echo $text_none; ?></option>

              <?php foreach ($categories as $category) { ?>

              <?php if ($category['category_id'] == $parent_id) { ?>

              <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_keyword; ?></td>

          <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>

        </tr>
<tr>

          <td><?php echo $entry_sort_order; ?></td>

          <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>

        </tr>
       <table id="images" class="list">

					<thead>

						<tr>

							<td class="left"><?php echo $entry_image; ?></td>

							<td><a class="tooltip">
<img src='../image/data/miniqmark.png'/>
<span>If an image you are using does not show up after being uploaded, open the image in a picture editor such as PhotoShop or Paint, convert it to an RGB Format and save it as a JPG or PNG</span></a></td>

						</tr>

					</thead>

					<tbody>

						<tr>

							<td><?php echo $entry_image; ?></td>

							<td><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />

								<img src="<?php echo $preview; ?>" alt="" id="preview" style="border: 1px solid #EEEEEE;" />&nbsp;<img src="view/image/image.png" alt="" style="cursor: pointer;" align="top" onclick="image_upload('image', 'preview');" /></td>

						</tr>	

					</tbody>

					<?php $image_row = 0; ?>

					<?php if (isset($product_images)) { foreach ($product_images as $product_image) { ?>

					<tbody id="image_row<?php echo $image_row; ?>">

						<tr>

							<td class="left"><input type="hidden" name="product_image[<?php echo $image_row; ?>]" value="<?php echo $product_image['file']; ?>" id="image<?php echo $image_row; ?>"	/>

								<img src="<?php echo $product_image['preview']; ?>" alt="" id="preview<?php echo $image_row; ?>" />&nbsp;<img src="view/image/image.png" alt="" style="cursor: pointer;" align="top" onclick="image_upload('image<?php echo $image_row; ?>', 'preview<?php echo $image_row; ?>');" /></td>

							<td class="left"><a onclick="$('#image_row<?php echo $image_row; ?>').remove();" class="button"><span>Remove</span></a></td>

						</tr>

					</tbody>

					<?php $image_row++; ?>

					<?php } }?>

				</table>

				<br />

				<a onclick="addImg();" class="button"><span>Add Image</span></a><br>
        

      </table>

    </form>

  </div>

</div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

<script type="text/javascript"><!--

<?php foreach ($languages as $language) { ?>

CKEDITOR.replace('description<?php echo $language['language_id']; ?>');

<?php } ?>

//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>

<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>

<script type="text/javascript"><!--

function image_upload(field, preview) {

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	

	$('#dialog').dialog({

		title: '<?php echo $text_image_manager; ?>',

		close: function (event, ui) {

			if ($('#' + field).attr('value')) {

				$.ajax({

					url: 'index.php?route=common/filemanager/image',

					type: 'POST',

					data: 'image=' + encodeURIComponent($('#' + field).val()),

					dataType: 'text',

					success: function(data) {

						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" style="border: 1px solid #EEEEEE;" />');

					}

				});

			}

		},	

		bgiframe: false,

		width: 700,

		height: 400,

		resizable: false,

		modal: false

	});

};

//--></script>
<script type="text/javascript"><!--

var image_row = <?php echo $image_row; ?>;



function addImg() {

	html = '<tbody id="image_row' + image_row + '">';

	html += '<tr>';

	html += '<td class="left"><input type="hidden" name="product_image[' + image_row + ']" value="" id="image' + image_row + '" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + image_row + '" style="margin: 4px 0px; border: 1px solid #EEEEEE;" />&nbsp;<img src="view/image/image.png" alt="" style="cursor: pointer;" align="top" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');" /></td>';

	html += '<td class="left"><a onclick="$(\'#image_row' + image_row + '\').remove();" class="button"><span>Remove</span></a></td>';

	html += '</tr>';

	html += '</tbody>';

	

	$('#images').append(html);

	

	image_row++;

}

//--></script>
<script type="text/javascript"><!--

$.tabs('.tabs a'); 

//--></script>

<?php echo $footer; ?>