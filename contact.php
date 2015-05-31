<?php $title = 'Contact Us'; include('includes/header.php');?>
<?php include('includes/mysql_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/sidebar-a.php'); ?>
<div id="content">
	<?php
		//Xu ly form
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//Tao bien de bao loi neu co
			$errors = array();
			
			//chong spam mail
			$clean = array_map('clean_email', $_POST);
			
			//Kiem tra truong nhap ten
			if(empty($clean['name'])) {
				$errors[] = "name";
			} 
			
			//Kiem tra email co hop le hay khong
			if(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/', $clean['email'])) {
				$errors[] = "email";
			}
			
			//Kiem tra noi dung tin nhan
			if(empty($clean['message'])) {
				$errors[] = "message";
			}
			
			//Validate captcha
			if (isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['q']['answer']) {
				$errors[] = "wrong";
			}
			
			//Kiem tra co loi o form khong, neu khong thi gui email
			if(empty($errors)){
				$body = "Name: {$clean['name']} \n\n Comment:\n" . strip_tags($clean['message']);
				$body = wordwrap($body, 70);
				if(mail('nhmtuan93@gmail.com', 'Contact form submistion', $body, 'FROM: localhost@localhost')) {
					echo "<p class='success'>Thanks for your contact</p>";
					$_POST = array();
				} else {
					echo "<p class='warning'>Sorry, email could not sent.</p>";
				}
			} else {
				//Neu co loi trong bien $errors thi de nguoi dung nhap lai
				echo "<p class='warning'>Please fill out all the required fields.</p>";
			}
		}//End Main IF
	?>
	<form id="Name" action="" method="post" >
		<fieldset>
			<legend>Contact</legend>
			<div>
				<label for="name">Your name: <span class="required">*</span>
					<?php 
						if (isset($errors) && in_array('name', $errors)) {
							echo "<span class='warning'>Please enter your name.</span>";
						}
					?>
				</label>
				<input id="name" name="name" type="text"  value="<?php if (isset($_POST['name'])) { echo htmlentities($_POST['name'], ENT_COMPAT, 'UTF-8') ;} ?>" size="20" maxlength="80" tabindex="1">
			</div>

			<div>
				<label for="email">Email: <span class="required">*</span>
					<?php 
						if (isset($errors) && in_array('email', $errors)) {
							echo "<span class='warning'>Please enter your email.</span>";
						}
					?>
				</label>
				<input id="email" name="email" type="text"  value="<?php if (isset($_POST['email'])) { echo htmlentities($_POST['email']); } ?>" size="20" maxlength="80" tabindex="2">
			</div>

			<div>
				<label for="message">Your Message: <span class="required">*</span>
					<?php 
						if (isset($errors) && in_array('message', $errors)) {
							echo "<span class='warning'>Please enter your message.</span>";
						}
					?>
				</label>
				<div id="message"><textarea name="message" rows="10" cols="50" tabindex="3"><?php if (isset($_POST['message'])) { echo htmlentities($_POST['message'], ENT_COMPAT, 'UTF-8'); } ?></textarea></div>
			</div>
			
			<div>
			<label for="captcha">Enter number only, please! <?php echo captcha(); ?><span class="required">*</span>
				<?php 
					if (isset($errors) && in_array('wrong', $errors)) {
						echo "<span class='warning'>Please enter correct answer.</span>";
					}
				?>
			</label>
			<input type="text" name="captcha" id="captcha" value="" size="20" maxlength="10" tabindex="4">
		</div>
		</fieldset>
		<div><input type="submit" name="submit" value="Send email"></div>
	</form>

</div>
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>