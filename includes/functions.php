<?php
    // Xac dinh hang so cho dia chi tuyet doi
    define('BASE_URL', 'http://localhost/izcms/');

	//Kiem tra ket qua tra ve co dung hay khong    
    function confirm_query($result, $query) {
        global $dbc;
        if(!$result) {
            die("Query {$query} \n<br/> MySQL Error: " .mysqli_error($dbc));
        } 
    }

    //Tai dinh huong neu khong co dia chi
    function redirect_to($page = 'index.php') {
    	$url = BASE_URL . $page;
    	header("Location: $url");
		exit();
    }
?>