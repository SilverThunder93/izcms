<?php include('../includes/header.php'); ?>
<?php include('../includes/mysql_connect.php'); ?>
<?php include('../includes/sidebar-admin.php'); ?>		
			
			<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST') { //Gia tri ton tai, xu ly form
					$errors = array();
					if(empty($_POST['page_name'])) {//Kiem tra page_name co trong hay khong
						$errors[] = 'position';
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
				}//End main IF submit condition
			?>
				
			<div id="content">
				<h2>Create a page</h2>
				<?php if(!empty($messages)) { echo $messages;} ?>

				<form id="login" action="" method="post" >
					<fieldset>
						<legend>Add a Page</legend>
						<div>
							<lable for="page">Page Name: <span class="required">*</span></lable>
							<input type="text" name="page_name" id="page_name" value="" size="20" maxlength="80" tabindex="1" />
						</div>

						<div>
							<lable for="category">All categories: <span class="required">*</span></lable>
							<select name="category" >
								<option value="">Select Category</option>		
							</select>
						</div>

						<div>
							<lable for="position">Position: <span class="required">*</span></lable>
							<select name="position" >
								<option value="">Select position</option>		
							</select>
						</div>

						<div>
							<lable for="page-conttent">Page Content: <span class="required">*</span></lable>
							<textarea name="content" cols="50" rows="20"></textarea>
						</div>
					</fieldset>

					<p><input type="submit" name="submit" value="Add Page" /></p>
				</form>
			</div><!--end #content-->
<?php include('../includes/sidebar-b.php'); ?>
<?php include('../includes/footer.php'); ?>			