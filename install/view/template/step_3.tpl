<?php echo $header; ?>
<h1 style="background: url('view/image/configuration.png') no-repeat;">Step 3 - Configuration</h1>
<div style="width: 100%; display: inline-block;">
	<div style="float: left; width: 569px;">
		<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
		<?php } ?>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<p>1. Please enter your server details.</p>
			<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
				<table>
					<tr>
						<td width="185"><span class="required">*</span>Domain:</td>
						<td><input type="text" name="domain" value="<?php echo $domain; ?>" />
							<br />
							<?php if ($error_domain) { ?>
							<span class="required"><?php echo $error_domain; ?></span>
							<?php } ?></td>
					</tr>
				</table>
			</div>
			<p>2. Please enter your database connection details.</p>
			<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
				<table>
					<tr>
						<td width="185"><span class="required">*</span>Database Host:</td>
						<td><input type="text" name="db_host" value="<?php echo $db_host; ?>" />
							<br />
								<?php if ($error_db_host) { ?>
								<span class="required"><?php echo $error_db_host; ?></span>
								<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span>User:</td>
						<td><input type="text" name="db_user" value="<?php echo $db_user; ?>" />
							<br />
							<?php if ($error_db_user) { ?>
							<span class="required"><?php echo $error_db_user; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="text" name="db_password" value="<?php echo $db_password; ?>" /></td>
					</tr>
					<tr>
						<td><span class="required">*</span>Database Name:</td>
						<td><input type="text" name="db_name" value="<?php echo $db_name; ?>" />
							<br />
							<?php if ($error_db_name) { ?>
							<span class="required"><?php echo $error_db_name; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td>Database Prefix:</td>
						<td><input type="text" name="db_prefix" value="<?php echo $db_prefix; ?>" /></td>
					</tr>
				</table>
			</div>
			<!--<p>3. Please enter your Zend details.</p>
			<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
				<table>
					<tr valign="top">
						<td width="185"><span class="required">*</span>Product License filename:</td>
						<td><?php echo $base; ?><input type="text" name="license" value="<?php echo $license; ?>" />
							<?php if ($error_license) { ?>
							<span class="required"><?php echo $error_license; ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr valign="top">
						<td width="185"><span class="required">*</span>Zend Optimizer path:</td>
						<td>
							<table>
								<tr>
									<td>
										<?php if ($host == '1') { ?>
										<input type="radio" name="host" value="1" checked="checked" /> I am using JSI Canadian hosting
										<?php } else { ?>
										<input type="radio" name="host" value="1" /> I am using JSI Canadian hosting
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td>
										<?php if ($host == '2') { ?>
										<input type="radio" name="host" value="2" checked="checked" /> I am using JSI American hosting
										<?php } else { ?>
										<input type="radio" name="host" value="2" /> I am using JSI American hosting
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td>
										
										<?php if ($host == '3') { ?>
										<input type="radio" name="host" value="3" checked="checked" /> I am using a different hosting company
										<?php } else { ?>
										<input type="radio" name="host" value="3" /> I am using a different hosting company
										<?php } ?>
										<br />
										<br />
										<p>If you are using a different hosting company, please enter the neccessary information for setting up Zend Optimizer in a php.ini file. You can get this information from your hosting company.</p>
										<textarea cols="50" rows="3" name="host_other"><?php echo $host_other; ?></textarea>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>-->
			<p>4. Please enter a username and password for the administration.</p>
			<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
				<table>
					<tr>
						<td width="185"><span class="required">*</span>Username:</td>
						<td><input type="text" name="username" value="<?php echo $username; ?>" />
							<?php if ($error_username) { ?>
							<span class="required"><?php echo $error_username; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span>Password:</td>
						<td><input type="text" name="password" value="<?php echo $password; ?>" />
							<?php if ($error_password) { ?>
							<span class="required"><?php echo $error_password; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span>E-Mail:</td>
						<td><input type="text" name="email" value="<?php echo $email; ?>" />
							<?php if ($error_email) { ?>
							<span class="required"><?php echo $error_email; ?></span>
							<?php } ?></td>
					</tr>
				</table>
			</div>
			<div style="text-align: right;"><a onclick="document.getElementById('form').submit()" class="button"><span class="button_left button_continue"></span><span class="button_middle">Continue</span><span class="button_right"></span></a></div>
		</form>
	</div>
	<div style="float: right; width: 205px; height: 400px; padding: 10px; color: #003366; border: 1px solid #CCE0FF; background: #CCF5FF;">
		<ul>
			<li>License</li>
			<li>Pre-Installation</li>
			<li><b>Configuration</b></li>
			<li>Finished</li>
		</ul>
	</div>
</div>
<?php echo $footer; ?>z