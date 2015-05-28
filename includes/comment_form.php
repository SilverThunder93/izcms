<?php 
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errors = array();

		//Validate Name
		if (!empty($_POST['name'])) {
			$name = mysqli_real_escape_string($dbc, strip_tags($_POST['name']));
		} else {
			$errors[] = "name";
		}

		//validate Email
		if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$e = mysqli_real_escape_string($dbc, strip_tags($_POST['email']));
		} else {
			$errors[] = "email";
		}

		//Validate comment
		if (!empty($_POST['comment'])) {
			$comment = mysqli_real_escape_string($dbc, strip_tags($_POST['comment']));
		} else {
			$errors[] = "comment";
		}

		//Validate captcha
		if (isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['q']['answer']) {
			$errors[] = "wrong";
		}

		if (empty($errors)) {//Neu khong co loi thi them comment vao csdl
			$q = "INSERT INTO comments (page_id, author, email, comment, comment_date) VALUES ({$pid}, '{$name}', '{$e}', '{$comment}', NOW())";
			$r = mysqli_query($dbc, $q);
        		confirm_query($r, $q);

        	if (mysqli_affected_rows($dbc) == 1) {
        		//Success
        		$messages = "<p class='success'>Thank you for your comment.</p>";
        	} else {
        		$messages = "<p class='warning'>Your comment could not to be post by something error.</p>";
        	}
		} else {
			$messages = "<p class='error'>Please try agian.</p>";
		}
	}//End Main IF
?>

<?php 
	//Display comment from database
	$q = "SELECT author, comment, DATE_FORMAT(comment_date, ' %d %d, %Y') AS date FROM comments WHERE page_id = {$pid}";
	 $r = mysqli_query($dbc, $q);
	 confirm_query($r, $q);

	 if (mysqli_num_rows($r) > 0) {//Neu co comment se hien thi ra
	 	echo "<ol id='disscuss'>";
	 	while (list($author, $comment, $date) = mysqli_fetch_array($r, MYSQLI_NUM)) {
	 		echo "<li>
				<p class='author'>{$author}</p>
				<p class='comment-sec'>{$comment}</p>
				<p class='date'>{$date}</p>";
			echo "</li>";
	 	} //End WHILE 	
	 	echo "</ol>";
	 } else {//End IF
	 	echo "<h2>Be the first leave comment</h2>";
	 }
?>

<?php 
	if (!empty($messages)) {echo $messages;}
?>
<form id="comment_form" action="" method="post" >
	<fieldset>
		<legend>Leave a comment</legend><div>
			<label for="name">Name: <span class="required">*</span>
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
			<label for="comment">Your comment: <span class="required">*</span>
				<?php 
					if (isset($errors) && in_array('comment', $errors)) {
						echo "<span class='warning'>Please enter your comment.</span>";
					}
				?>
			</label>
			<div id="comment"><textarea name="comment" rows="10" cols="50" tabindex="3"><?php if (isset($_POST['comment'])) { echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8'); } ?></textarea></div>
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
	<div><input type="submit" name="submit" value="Post comment"></div>
</form>