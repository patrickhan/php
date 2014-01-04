<?php echo $header; ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/banner.png');"><?php echo $heading_title; ?></h1>
	</div>
	<div class="content">
		<table class="list" style="width:300px;">
			<thead>
				<tr>
					<td class="left"><?php echo $column_date; ?></td>
					<td class="right"><?php echo $column_views; ?></td>
					<td class="right"><?php echo $column_clicks; ?></td>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($stats as $stat) { ?>
			<tr>
				<td class="left"><?php echo $stat['date']; ?></td>
				<td class="right"><?php echo $stat['views']; ?></td>
				<td class="right"><?php echo $stat['clicks']; ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
</div>
<?php echo $footer; ?>