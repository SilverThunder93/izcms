<?php $title = 'Log in'; include('includes/header.php');?>
<?php include('includes/mysql_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/sidebar-a.php'); ?>
<div id="content">
    <?php 
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//bat dau xu ly form
			$errors = array();
			
			//Validate email
			if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$e = mysqli_real_escape_string($dbc, $_POST['email']);
			} else {
				$errors[] = 'email';
			}
			
		//Validate password
		if(isset($_POST['password']) && preg_match('/^[\w\'.-]{4,20}$/',$_POST['password'])) {
			$p = mysqli_real_escape_string($dbc, $_POST['password']);
		} else {
			$errors[] = 'password';
		}
		
		if(empty($errors)) {
			//Bat dau truy van csdl
			$q = "SELECT user_id, first_name, user_level FROM users WHERE (email = '{$e}' AND password = SHA1('$p')) AND active IS NULL LIMIT 1";
			$r = mysqli_query($dbc, $q);
			confirm_query($r, $q);
			
			if(mysqli_num_rows($r) == 1) {
				//Thong tin nguoi dung nhap dung
				
				list($uid, $first_name, $user_level) = mysqli_fetch_array($r, MYSQLI_NUM);
                $_SESSION['uid'] = $uid;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['user_level'] = $user_level;
                                
                redirect_to();
			} else {
				$massage = "<p class='error'>Email or password was wrong.</p>";
			}
		} else {
			$message = "<p class='error'>Please fill in all the required fields.</p>";
		}
			
			
		}//End MAIN IF
    ?>
	
	<h2>Login</h2>
    <?php if(!empty($message)) echo $message; ?>
    <form id="login" action="" method="post">
        <fieldset>
        	<legend>Login</legend>
            	<div>
                    <label for="email">Email:
                        <?php if(isset($errors) && in_array('email',$errors)) echo "<span class='warning'>Please enter your email.</span>";?>
                    </label>
                    <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email']);} ?>" size="20" maxlength="80" tabindex="1" />
                </div>
                <div>
                    <label for="pass">Password:
                        <?php if(isset($errors) && in_array('password',$errors)) echo "<span class='warning'>Please enter your password.</span>";?>
                    </label>
             <input type="password" name="password" id="pass" value="" size="20" maxlength="20" tabindex="2" />
                </div>
        </fieldset>
        <div><input type="submit" name="submit" value="Login" /></div>
    </form>
    <p><a href="retrieve_password.php">Forgot password?</a></p>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>