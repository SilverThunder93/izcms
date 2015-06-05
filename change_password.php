<?php $title = 'Change Password'; include('includes/header.php');?>
<?php include('includes/mysql_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/sidebar-a.php'); ?>
<div id="content">
    <?php 
        //Kiem tra nguoi dung da dang nhap hay chua
        is_logged_in(); 
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Bat dau xu ly form
            $errors = array();
            
            //Kiem tra current password co dung hay khong
            if(isset($_POST['cur_password']) && preg_match('/^\w{4,20}$/', trim($_POST['cur_password']))) {
                $cur_password = mysqli_real_escape_string($dbc, trim($_POST['cur_password']));
                
                //Truy van CSDL xem pass co ton tai hay khong
               $q = "SELECT first_name FROM users WHERE password = SHA1('$cur_password') AND user_id = {$_SESSION['uid']}";
                $r = mysqli_query($dbc, $q); confirm_query($r, $q);
                  
                //Neu co gia tri thi lam tiep
                if(mysqli_num_rows($r) == 1) {
                    
                    //Tim thay nguoi dung, cho phep thay doi mat khau
                    //Kiem tra mat khau co dung dinh dang hay khong
                    if(isset($_POST['password1']) && preg_match('/^\w{4,20}$/', trim($_POST['password1']))) {
                        //Neu dung ...
                        //Kiem tra password1 co trung voi password2 hay khong
                        if($_POST['password1'] == $_POST['password2']) {
                            //Neu hai password giong nhau
                            $np = mysqli_real_escape_string($dbc, trim($_POST['password1']));
                            $q = "UPDATE users SET password = SHA1('$np') WHERE user_id = {$_SESSION['uid']} LIMIT 1";
                            $r = mysqli_query($dbc, $q);
    		                  confirm_query($r, $q);
                              
                            //Kiem tra update co thanh cong hay chua
                            if(mysqli_affected_rows($dbc) == 1) {
                                //Update thanh cong
                                $message = "<p class='success'>Your password has been successfully updated.</p>";
                                } else {
                                    // Neu update khong thanh cong
                                    $errors[] = "<p class='error'>Your password could not be changed due to a system error.</p>";
                                }
                                
                            } else {
                                // Neu hai truong password khong giong nhau
                                $errors[] = "<p class='error'>Your password and confirm password do not match.</p>";
                            }
                        
                        } else {
                            // Neu sai ...
                            $errors[] = "<p class='error'>Your password is either too short or missing.</p>";
                        }
                        
                } else {
                    $errors[] = "<p class='error'>Your current password is incorrect. Please check your email to verify your password.</p>";
                }
            } else {
                // Mat khau qua ngan hoac thieu mat khau.
                $errors[] = "<p class='error'>Your password is either too short or missing.</p>";
            }
        } // END main IF
    ?>
    
    <form action="" method="post">
    <?php if(isset($message)) echo $message; if(isset($errors)) {report_error($errors);}?>
        <fieldset>
    		<legend>Change Password</legend>
            <div>
                <label for="Current Password">Current Password</label> 
                <input type="password" name="cur_password" value="" size="20" maxlength="40" tabindex='1' />
            </div>
    
    		<div>
                <label for="New Password">New Password</label> 
                <input type="password" name="password1" value="" size="20" maxlength="40" tabindex='2' />
            </div>
            
            <div>
                <label for="Confirm Password">Confirm Password</label> 
                <input type="password" name="password2" value="" size="20" maxlength="40" tabindex='3' />
            </div>
    	</fieldset>
     <div><input type="submit" name="submit" value="Update Password" tabindex='4' /></div>
    </form>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>