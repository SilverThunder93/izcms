<?php include('../includes/header.php'); ?>
<?php include('../includes/mysql_connect.php'); ?>
<?php include('../includes/functions.php');?>
<?php include('../includes/sidebar-admin.php'); ?>	

<?php
	//Xac nhan bien cid ton tai loai du lieu cho phep
	if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
		$cid = $_GET['cid'];
	
		if($_SERVER['REQUEST_METHOD'] == 'POST') { //Gia tri ton tai, xu ly form
			$errors = array();
			if(empty($_POST['category'])) {//Kiem tra ten cua category co trong hay khong
				$errors[] = "category";
			} else {
				$cat_name = mysqli_real_escape_string($dbc, strip_tags($_POST['category']));
			}

			if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' =>1))) { //Kiem tra position
				$position = $_POST['position'];
			} else {
				$errors[] = "position";
			}

			if(empty($errors)) { //Neu khong co loi xay ra thi chen vao CSDL
				$q = "UPDATE categories SET cat_name = '{$cat_name}', position = $position WHERE cat_id = {$cid} LIMIT 1";
				$r = mysqli_query($dbc, $q);
					confirm_query($r, $q);

				if(mysqli_affected_rows($dbc) == 1) {
					$messages =  "<p class='success'>The category was edited successfully.</p>";
				} else {
					$messages = "<p class='warning'>Could not edited the categories.</p>";
				}
			} else {
				$messages = "<p class='warning'>Please fill all the required fields<p>";
			}
		}//End main IF submit condition
	} else {
		redirect_to('admin/view_categories.php');
	}
?>
	
<div id="content">
	<?php //Chon page trong co so du lieu de hien thi ra trinh duyet
        $q = "SELECT cat_name, position FROM categories WHERE cat_id = {$cid}";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);
        if(mysqli_num_rows($r) == 1) {
            // Neu category ton tai trong database, dua vao CID, xuat du lieu ra ngoai trinh duyet
            list($cat_name, $position) = mysqli_fetch_array($r, MYSQLI_NUM);
        } else {
            // Neu CID khong hop le, se khong the hien thi category
            $messages = "<p class='warning'>The category does not exist.</p>";
        }
    ?>
	<h2>Edit category: <?php if (isset($cat_name)) { echo $cat_name; }?></h2>
	<?php
		if(!empty($messages)){
			echo $messages;
		};
	?>
	<form id="edit_cat" action="" method="post">
		<fieldset>
			<legend>Edit category</legend>
			<div>
				<label for="category">Category Name: <span class="required">*</span>
					<?php
						if(isset($errors) && in_array('category', $errors)) {
							echo "<p class='warning'>Please fill in the category name.</p>";
						}
					?>
				</label>
				<input type="text" name="category" id="category" value="<?php if (isset($cat_name)) { echo $cat_name;	}?>" size="20" maxlength="150" tabindex="1" />
			</div>
			
			<div>
				<label for="position">Position: <span class="required">*</span>
					<?php
						if(isset($errors) and in_array('position', $errors)) {
							echo "<p class='warning'>Please fill in the position.</p>";
						}
					?>
				</label>
				<select name="position" tabindex="2">
					<?php 
						$q = "SELECT count(cat_id) AS count FROM categories";
						$r = mysqli_query($dbc, $q);
        					confirm_query($r, $q);

						if(mysqli_num_rows($r) == 1) {
							list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
							for($i=1; $i<=$num+1; $i++) {//Tao vong lap cong them 1 cho position de chon dc position moi lon hon cho cat moi
								echo "<option value='{$i}'";
									if(isset($position) && ($position == $i)) { echo "selected='selected'"; }
								echo ">" . $i . "</option>";
							}
						}
					?>
				</select>
			</div>						
		</fieldset>					
		<p><input type="submit" name="submit" value="Save change" /></p>
	</form>
</div><!--end #content-->
<?php include('../includes/sidebar-b.php'); ?>
<?php include('../includes/footer.php'); ?>			