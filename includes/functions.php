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

    //Cat chu hien thi thanh doan van ngan
    function the_excerpt($text) {
        $sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8');
        if(strlen($sanitized) > 400) {
            $cutString = substr($sanitized, 0, 400);
            $words = substr($sanitized, 0, strrpos($cutString, ' '));
            return $words;
        } else {
            return $sanitized;
        }
    }

    //Tao paragraph tu CSDL
    function the_content($text) {
        $sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8');
        return str_replace(array("\r\n", "\n"), array("<p>", "</p>"), $sanitized);
    }

    //Kiem tra $id co phai dang so hay khong
    function validate_id($id) {
        if (isset($id) && filter_var($id, FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $val_id = $id;
            return $val_id;
        } else {
            return NULL;
        }
    }//End validate_id

    //Truy van CSDL de lay post va thong ti nguoi dung
    function get_page_by_id($id) {
        global $dbc;
        $q = " SELECT p.page_id, p.page_name, p.content, ";
        $q .= " DATE_FORMAT(p.post_on, '%b %d %Y') AS date, ";
        $q .= " CONCAT_WS(' ', u.first_name, u.last_name) AS name, u.user_id ";
        $q .= " FROM pages AS p ";
        $q .= " INNER JOIN users AS u ";
        $q .= " USING(user_id) ";
        $q .= " WHERE p.page_id={$id} ";
        $q .= " ORDER BY date ASC LIMIT 1 ";
        $result = mysqli_query($dbc, $q);
            confirm_query($result, $q);
        return $result;
    }

    function captcha() {
        $qna = array (
                1 => array('question' => 'Mot cong mot', 'answer' => 2),
                2 => array('question' => 'ba tru hai', 'answer' => 1),
                3 => array('question' => 'ba nhan nam', 'answer' => 15),
                4 => array('question' => 'sau chia hai', 'answer' => 3),
                5 => array('question' => 'nang bach tuyet va .... chu lun', 'answer' => 7),
                6 => array('question' => 'Alibaba va ... ten cuop', 'answer' => 40),
                7 => array('question' => 'an mot qua khe, tra .... cuc vang', 'answer' => 1),
                8 => array('question' => 'may tui .... gang, mang di ma dung', 'answer' => 3)
                );
        $rand_key =array_rand($qna); //Lay ngau nhien 1 key trong array
        $_SESSION['q'] = $qna[$rand_key];
        return $question = $qna[$rand_key]['question'];
    }//End function captcha

 

   
