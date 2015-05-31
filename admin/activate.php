<?php $title = 'Activate Account'; include('../includes/header.php');?>
<?php include('../includes/mysql_connect.php');?>
<?php include('../includes/functions.php');?>
<?php include('../includes/sidebar-a.php'); ?>
<div id="content">
    <?php 
		if(isset($_GET['x']) && filter_var($_GET['x'], FILTER_VALIDATE_EMAIL) && strlen($_GET['y']) == 32)  {
			//Neu day du thong tin va hop le thi xu ly form
			$e = mysqli_real_escape_string($dbc, $_GET['x']);
			$a = mysqli_real_escape_string($dbc, $_GET['y']);
			
			$q = "UPDATE users SET active = NULL WHERE email = '{$e}' AND active = '{$a}' LIMIT 1";
			$r = mysqli_query($dbc, $q);
			confirm_query($r, $q);
			
			if(mysqli_affected_rows($dbc) == 1) {
				echo "<p class='success'>Your account has been active successfully. You can <a href='". BASE_URL ."login.php'>login</a> now.</p>";
			} else {
				echo "<p class='success'>Your account hasn't been active.</p>";
			}
		} else {
			//Neu thong tin khong hop le thi tro ve trang index
			redirect_to();
		}
    ?>
</div><!--end content-->
<?php include('../includes/sidebar-b.php');?>
<?php include('../includes/footer.php'); ?>