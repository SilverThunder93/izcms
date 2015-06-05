<?php $title = 'Log Out'; include('includes/header.php');?>
<?php include('includes/mysql_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/sidebar-a.php'); ?>
<div id="content">
    <?php 
        if(!isset($_SESSION['first_name'])) {
            //Neu chua dang nhap va khong co thong tin trong he thong thi chuyen nguoi dung ve index.php
            redirect_to();
        } else {
            //Neu da dang nhap va co thong thin thi thuc hien lenh
            $_SESSION = array(); //Xoa het array cua SESSION
            session_destroy(); //Huy SESSION
            setcookie(session_name(), '', time()-36000); //Xoa COOKIES cua trinh duyet           
        }
        
        echo "<h2>You are log out!</h2>";
    ?>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>