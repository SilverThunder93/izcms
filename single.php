<?php 
		include('includes/mysql_connect.php');
	include('includes/functions.php');
?>

<?php 
	if($pid = validate_id($_GET['pid'])) {
		//Neu pid hop le thi truy van CSDL
		$set = get_page_by_id($pid);
		$posts = array();//Tao array trong de luu phan noi dung

        if (mysqli_num_rows($set) > 0) {//Co post thi hien thi ra trinh duyet
        	$pages = mysqli_fetch_array($set, MYSQLI_ASSOC); 
    		$title = $pages['page_name'];  
    		$posts[] = array(
    						'page_name' => $pages['page_name'],
    						'content' => $pages['content'], 
    						'author' => $pages['name'], 
    						'post-on' => $pages['date'],
    						'aid' => $pages['user_id']
    						);         	
        } else {
        	echo "<p>There are currently no post in this categories.</p>";
        }
	} else {//NEu pid k hop le thi chuyen nguoi dung ve trang index.php
		redirect_to();
	}
?>


<?php 	
	include('includes/header.php');
	include('includes/sidebar-a.php'); 
?>	
<div id="content">
	<?php 
		foreach ($posts as $post) {
			echo "
			<div class='post'>
				<h2>{$post['page_name']}</h2>
				<p>" . the_content($post['content']) . "</p>
				<p class='meta'><strong>Posted by: </strong><a href = 'author.php?aid={$post['aid']}'>{$post['author']}</a> | <strong>On: </strong> {$post['post-on']}</p>
			</div>
			";
		}//End foreach
	?>

	<?php include('includes/comment_form.php'); ?>
</div><!--end #content-->
<?php 
	include('includes/sidebar-b.php'); 
	include('includes/footer.php'); 
?>			