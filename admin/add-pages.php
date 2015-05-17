<?php include('../includes/header.php'); ?>
<?php include('../includes/mysql_connect.php'); ?>
<?php include('../includes/sidebar-admin.php'); ?>		
			
			<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST') { //Gia tri ton tai, xu ly form
					$errors = array();
					if(empty($_POST['page_name'])) {//Kiem tra page_name co trong hay khong
						$errors[] = 'page_name';
					} else {
						$page_name = mysqli_real_escape_string($dbc, strip_tags($_POST['page_name']));
					}

					if(isset($_POST['category']) && filter_var($_POST['category'], FILTER_VALIDATE_INT, array('min_range' =>1))) { //Luu category vao neu ng dung co chon
						$cat_id = $_POST['category'];
					} else {
						$errors[] = "category";
					}

					if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' =>1))) { //Luu position vao neu ng dung co chon
						$position = $_POST['position'];
					} else {
						$errors[] = "position";
					}

					if(empty($_POST['content'])) {
						$errors[] = 'content';
					} else {
						$content = mysqli_real_escape_string($dbc, $_POST['content']);
					}

					if (empty($errors)) { // Neu khong co loi thi chay cac lenh phia sau
						$q = "INSERT INTO pages (user_id, cat_id, page_name, content, position, post_on) VALUES (1, {$cat_id}, '{$page_name}', '{$content}', $position, NOW())";
						$r = mysqli_query($dbc, $q) or die("Query ($q) \n<br/> MySQL Error: " . mysqli_error($dbc));
						if (mysqli_affected_rows($dbc) == 1) {
							$messages = "<p class='success'>The page was added successfully.</p>";
						} else {
							$messages = "<p class='warning'>The page was not added successfully.</p>";
						}
					} else {
						$messages = "<p class='warning'>Please fill in all required fields</p>";
					}
				}//End main IF submit condition
			?>
				
			<div id="content">
				<h2>Create a page</h2>
				<?php if(!empty($messages)) { echo $messages;} ?>

				<form id="login" action="" method="post" >
					<fieldset>
						<legend>Add a Page</legend>
						<div>
							<lable for="page">Page Name: <span class="required">*</span>
							<?php
								if(isset($errors) && in_array('page_name', $errors)) {
									echo "<p class='warning'>Please fill in the page name.</p>";
								}
							?>
							</lable>
							<input type="text" name="page_name" id="page_name" value="" size="20" maxlength="80" tabindex="1" />
						</div>

						<div>
	                        <label for="category">All categories: <span class="required">*</span>
	                            <?php 
	                                if(isset($errors) && in_array('category', $errors)) {
	                                    echo "<p class='warning'>Please pick a category</p>";
	                                }
	                            ?>
	                        </label>
	                        
	                        <select name="category">
	                            <option>Select Category</option>
	                            <?php
	                                $q = "SELECT cat_id, cat_name FROM categories ORDER BY position ASC";
	                                $r = mysqli_query($dbc, $q);
	                                if(mysqli_num_rows($r) > 0) {
	                                    while($cats = mysqli_fetch_array($r, MYSQLI_NUM)) {
	                                        echo "<option value='{$cats[0]}'";
	                                            if(isset($_POST['category']) && ($_POST['category'] == $cats[0])) echo "selected='selected'";
	                                        echo ">".$cats[1]."</option>";
	                                    }
	                                }
	                            ?>
	                        </select>
	                    </div>

						<div>
							<lable for="position">Position: <span class="required">*</span>
							<?php
								if(isset($errors) && in_array('position', $errors)) {
									echo "<p class='warning'>Please pick position.</p>";
								}
							?>
							</lable>
							<select name="position" >
								<?php 
									$q = "SELECT count(page_id) AS count FROM pages";
									$r = mysqli_query($dbc, $q) or die("Query ($q) \n<br/> MySQL Error: " . mysqli_error($dbc));

									if(mysqli_num_rows($r) == 1) {
										list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
										for($i=1; $i<=$num+1; $i++) {//Tao vong lap cong them 1 cho position de chon dc position moi lon hon cho cat moi
											echo "<option value='{$i}'";
												if(isset($_POST['position']) && $_POST['position'] == $i) echo "selected='selected'";
											echo ">" . $i . "</option>";
										}
									}
								?>
							</select>
						</div>

						<div>
	                        <label for="page-content">Page Content: <span class="required">*</span>
	                            <?php 
	                                if(isset($errors) && in_array('content', $errors)) {
	                                    echo "<p class='warning'>Please fill in the content</p>";
	                                }
	                            ?>
	                        </label>
	                        <textarea name="content" cols="50" rows="20"><?php if(isset($_POST['content'])) echo htmlentities($_POST['content'], ENT_COMPAT, 'UTF-8'); ?></textarea>)
	                    </div>
					</fieldset>

					<p><input type="submit" name="submit" value="Add Page" /></p>
				</form>
			</div><!--end #content-->
<?php include('../includes/sidebar-b.php'); ?>
<?php include('../includes/footer.php'); ?>			