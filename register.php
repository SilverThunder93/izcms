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
		}//End main IF
    ?>
	<h2>Register</h2>
	<form id="register" action="register.php" method="post" >
		<fieldset>
			<legend>Register</legend>
			<div>
				<label for="First Name">First Name: <span class="required">*</span></label>
				<input name="first_name" type="text"  value="" size="20" maxlength="20" tabindex="1">
			</div>
			
			<div>
				<label for="Last Name">Last Name: <span class="required">*</span></label>
				<input name="last_name" type="text"  value="" size="20" maxlength="40" tabindex="1">
			</div>
			
			<div>
				<label for="email">Email: <span class="required">*</span></label>
				<input name="email" type="text"  value="" size="20" maxlength="80" tabindex="1">
			</div>
			
			<div>
				<label for="password">Password: <span class="required">*</span></label>
				<input name="password1" type="password"  value="" size="20" maxlength="20" tabindex="1">
			</div>
			
			<div>
				<label for="email">Confrim Password: <span class="required">*</span></label>
				<input name="password2" type="password"  value="" size="20" maxlength="20" tabindex="1">
			</div>
		</fieldset>
		<div><input type="submit" name="submit" value="Registerl"></div>
	</form>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>