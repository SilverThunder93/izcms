<?php
	//Kiem tra ket qua tra ve co dung hay khong    
    function confirm_query($result, $query) {
        global $dbc;
        if(!$result) {
            die("Query {$query} \n<br/> MySQL Error: " .mysqli_error($dbc));
        } 
    }
?>