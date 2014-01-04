<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success""><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/backup.png');"><?php echo $heading_title; ?></h1>
  
  </div>
  <div class="content">
    <form action="<?php echo $generate_sitemap; ?>" method="post" enctype="multipart/form-data" id="generate_sitemap">
      <table class="form">
        <tr>
          <td>This functionality is not available in this version of our cart - for a small extra cost you can purchase our SEO tools to help you reach the top of the search engines. Contact kdc@jsicorp.com for more information</td>
        </tr>
		<?php if ($output != '') { ?>
        <tr>
          <td><?php echo $output; ?></td>
        </tr>
		<?php } ?>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>