<?php $title = 'Register'; include('includes/header.php');?>
<?php include('includes/mysql_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/sidebar-a.php'); ?>
<div id="content">
    <?php 
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//Bat dau xu ly form
			$errors = array();
			//Mac dinh nhap lieu la FALSE
			$fn = $ln = $e = $p = FALSE;
			
			if(preg_match('/^[\w\'.-]{2,20}$/i', trim($_POST['first_name']))) {
				$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
			} else {
				$errors[] = 'first name';
			}
			
			if(preg_match('/^[\w\'.-]{2,20}$/i', trim($_POST['last_name']))) {
				$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
			} else {
				$errors[] = 'last name';
			}
			
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$e = mysqli_real_escape_string($dbc, $_POST['email']);
			} else {
				$errors[] = 'email';
			}
			
			if(preg_match('/^[\w\'.-]{4,20}$/', trim($_POST['password1']))) {
				if($_POST['password1'] == $_POST['password2']) {
					//Neu mat khau phu hop mat khau xac nhan thi luu vao csdl
					$p = mysqli_real_escape_string($dbc, trim($_POST['password1']));
				} else {
					//Neu mat khau khong phu hop voi nhau
					$errors[] = "password not match";
				}
			} else {
				$errors[] = 'password';
			}
			
			if($fn && $ln && $e && $p) {
				//Moi thu day du se thuc hien form
				$q = "SELECT user_id FROM users WHERE email = '{$e}'";
				$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);
				
				if(mysqli_num_rows($r) == 0) {
					//Email chua dang ky
					
					//Tao chuoi Activate key
					$a = md5(uniqid(rand(), true));
					
					//Chen gia tri vao csdl
					$q = "INSERT INTO users (first_name, last_name, email, password, active, registration_date) VALUES ('{$fn}', '{$ln}', '{$e}', SHA1('$p'), '{$a}', NOW())";
					$r = mysqli_query($dbc, $q);
					confirm_query($r, $q);
					
					if(mysqli_affected_rows($dbc) == 1) {
						$body = "Cảm ơn bạn đã đăng ký. Xin vui lòng xác nhận đăng ký. \n\n ";
						$body .= BASE_URL . "admin/activate.php?x=" . urlencode($e) . "&y={'$a'}";
						if(mail($_POST['email'], 'Activate account izCMS', $body, 'FROM: localhost')) {
							$message =  "<p class='success'>Đăng ký thành công. hãy check email của bạn.</p>";
						} else {
							$message = "<p class='warning'>Không thể gửi email cho bạn. Xin lỗi nhé baby</p>";
						}
					} else {
						$message = "<p class='warning'>Yêu cầu của bạn không thể thực hiện. Vui lòng thử lại.</p>";
					}
				} else {
					//Email da dang ky
					$message = "<p class='warning'>Email của bạn đã đăng ký. Vui lòng đăng nhập.</p>";
				}
			} else {
				//Neu 1 trong ca truong thieu gia tri se bao ra ngoai
				$message = "<p class='warning'>Hãy nhập tất cả các trường.</p>";
			}
		}//End main IF
    ?>
	<h2>Register</h2>
	<?php if(!empty($message)) echo $message; ?>
	<form id="register" action="register.php" method="post" >
		<fieldset>
			<legend>Register</legend>
			<div>
				<label for="First Name">First Name: <span class="required">*</span>
				<?php if(isset($errors) && in_array('first name', $errors)) echo "<span class='warning'>Please enter your first name!!!</span>"?>
				</label>
				<input name="first_name" type="text"  value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name']; ?>" size="20" maxlength="20" tabindex="1">
			</div>
			
			<div>
				<label for="Last Name">Last Name: <span class="required">*</span>
				<?php if(isset($errors) && in_array('last name', $errors)) echo "<span class='warning'>Please enter your last name!!!</span>"?>
				</label>
				<input name="last_name" type="text"  value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name']; ?>" size="20" maxlength="40" tabindex="1">
			</div>
			
			<div>
				<label for="email">Email: <span class="required">*</span>
				<?php if(isset($errors) && in_array('email', $errors)) echo "<span class='warning'>Please enter your validate email!!!</span>"?>
				</label>
				<input name="email" type="text"  value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8'); ?>" size="20" maxlength="80" tabindex="1">
			</div>
			
			<div>
				<label for="password">Password: <span class="required">*</span>
				<?php if(isset($errors) && in_array('password', $errors)) echo "<span class='warning'>Please enter your password!!!</span>"?>
				</label>
				<input name="password1" type="password"  value="" size="20" maxlength="20" tabindex="1">
			</div>
			
			<div>
				<label for="email">Confrim Password: <span class="required">*</span>
				<?php if(isset($errors) && in_array('password not match', $errors)) echo "<span class='warning'>Please enter your correct confirm password!!!</span>"?>
				</label>
				<input name="password2" type="password"  value="" size="20" maxlength="20" tabindex="1">
			</div>
		</fieldset>
		<div><input type="submit" name="submit" value="Register"></div> 
	</form>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>