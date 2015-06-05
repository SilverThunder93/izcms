<?php $title = 'Forgot password'; include('includes/header.php');?>
<?php include('includes/mysql_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/sidebar-a.php'); ?>
<div id="content">
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $uid = FALSE;
            $errors = array();
            //Bat dau xu ly form
            if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $e = mysqli_real_escape_string($dbc, $_POST['email']);
                
                //Kiem tra email co ton tai hay khong
                $q = "SELECT user_id FROM users WHERE email = '{$e}'";
                $r = mysqli_query($dbc, $q); 
                confirm_query($r, $q);    
                            
                if(mysqli_num_rows($r) == 1) {
                    //Email co trong csdl
                    list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);
                }
            } else {
                //Neu quen khong dien email
                $errors[] = "<p class='error'>Please enter your email.</p>";
            }
        
            if($uid) {
                //NEu co user ID se lam tiep
                $temp_pass = substr(md5(uniqid(rand(), true)), 3, 6);
                
                //Update csdl voi password tam thoi
                $q = "UPDATE users SET password = SHA1('$temp_pass') WHERE user_id = {$uid} LIMIT 1";
                $r = mysqli_query($dbc, $q); 
                confirm_query($r, $q); 
                
                if(mysqli_affected_rows($dbc) == 1) {
                    //Neu update thanh cong
                    $body = "Your password has bean change to {$temp_pass}.";
                    mail($e, "Your password has been change", $body, "FROM: Silver Thunder");
                    $errors[] =  "<p class='success'>Your password has been change. Please log in again with your password in your email.</p>";
                } else {
                    $errors[] = "<p class='warning'>Your password has not been change. Please try again.</p>";
                }
            } else {
                $errors[] = "<p class='error'>The email could not find. Please enter your email again.<p>";
            }
        }//END main IF
    ?>
    
    <form id="login" action="" method="post">
    <?php 
        if(isset($errors)) {
            foreach ($errors as $e) {
                echo $e;
            }//End foreach
        }//end if
    ?>
        <fieldset>
        	<legend>Retrieve Password</legend>
        	<div>
                <label for="email">Email: </label> 
                
                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email']);} ?>" size="40" maxlength="80" tabindex="1" />
            </div>
        </fieldset>
        <div><input type="submit" name="submit" value="Retrieve Password" /></div>
    </form>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>