<?php include('includes/header.php'); ?>
<?php include('includes/mysql_connect.php'); ?>
<?php include('includes/functions.php');?>
<?php include('includes/sidebar-a.php'); ?>		
<div id="content">
	<?php 
		if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
			$cid = $_GET['cid'];
				$q = " SELECT p.page_id, p.page_name, LEFT(p.content, 400) AS content, ";
	            $q .= " DATE_FORMAT(p.post_on, '%b %d %Y') AS date, ";
	            $q .= " CONCAT_WS(' ', u.first_name, u.last_name) AS name, u.user_id ";
	            $q .= " FROM pages AS p ";
	            $q .= " INNER JOIN users AS u ";
	            $q .= " USING(user_id) ";
	            $q .= " WHERE p.cat_id={$cid} ";
	            $q .= " ORDER BY date ASC LIMIT 0, 10 ";
	            $r = mysqli_query($dbc, $q);
	                confirm_query($r, $q);

	            if (mysqli_num_rows($r) > 0) {//Co post thi hien thi ra trinh duyet
	            	while ($pages = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	            		echo "
							<div class='post'>
								<h2><a href='single.php?pid={$pages['page_id']}'>{$pages['page_name']}</a></h2>
								<p>" . the_excerpt($pages['content']) . " ... <a href='single.php?pid={$pages['page_id']}'>Read more</a></p>
								<p class='meta'><strong>Posted by: </strong>{$pages['name']} | <strong>On: </strong> {$pages['date']}</p>
							</div>
	            		";
	            	}//End while
	            } else {
	            	echo "<p>There are currently no post in this categories.</p>";
	            }
		}
	?>
	<h2>Wellcome To izCMS</h2>
	<div>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>
</div><!--end #content-->
<?php include('includes/sidebar-b.php'); ?>
<?php include('includes/footer.php'); ?>			