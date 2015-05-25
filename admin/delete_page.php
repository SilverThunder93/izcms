<?php 
    include('../includes/header.php');
    include('../includes/mysql_connect.php'); 
    include('../includes/functions.php');
    include('../includes/sidebar-admin.php'); 
?>		
     
<div id="content">
    <?php 
        if($pid = validate_id($_GET['pid'])) {
            $page_name = $_GET['pn'];
            //Neu ton tai pid va page_name thì se xoa category khoi co so du lieu
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['delete']) && ($_POST['delete'] == 'yes')) { //Neu muon delete categories
                    $q = "DELETE FROM pages WHERE page_id = {$pid} LIMIT 1";
                    $r = mysqli_query($dbc, $q);
                        confirm_query($r, $q);
                    if(mysqli_affected_rows($dbc) == 1) {//Xoa thanh cong -> bao nguoi dung biet
                        $message = "<p class='success'>The page was deleted successfully.</p>";
                    } else {
                        $message = "<p class='warning'>The oage was not deleted due to a system error.</p>";
                    }
                } else {//Neu khong muon delete
                    $message = "<p class='warning'>I thought so to!!! Shouldn't be deleted.</p>";
                }
            }
        } else {//Neu pid va page_name k ton tai hoac khong dung dinh dang
            redirect_to('admin/view_pages.php');
        }
    ?>
    <h2>Delete Page: <?php if (isset($page_name)) { echo htmlentities($page_name, ENT_COMPAT, 'UTF-8'); } ?></h2>
    <?php if (!empty($message)) { echo $message; }?>
    <form action="" method="post">
        <fieldset>
            <legend>Delete Page</legend>
            <label for="delete">Are you sure?</label>
            <div>
                <input type="radio" name="delete" value="no" checked="checked" /> No
                <input type="radio" name="delete" value="yes"/> Yes
            </div>
            <div>
                <input type="submit" name="submit" value="Delete" onclick="return confirm('Are you sure?');" />
            </div>
        </fieldset>
    </form>
</div><!--end content-->
<?php 
    include('../includes/sidebar-b.php'); 
    include('../includes/footer.php'); 
?>