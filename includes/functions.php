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

    function pagination($aid, $display = 4){
        global $dbc; global $start;
        if(isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $page = $_GET['p'];
        } else {
            // Nếu biến p không có, sẽ truy vấn CSDL để tìm xem có bao nhiêu page để hiển thị
            $q = "SELECT COUNT(page_id) FROM pages";
            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
            list($record) = mysqli_fetch_array($r, MYSQLI_NUM);
            
            // Tìm số trang bằng cách chia số dữ liệu cho số display
            if($record > $display) {
                $page = ceil($record/$display);
            } else {
                $page = 1;
            }
        }
        
        $output = "<ul class='pagination'>";
        if($page > 1) {
            $current_page = ($start/$display) + 1;
            
            // Nếu không phải ở trang đầu (hoặc 1) thì sẽ hiển thị Trang trước.
            if($current_page != 1) {
                $output .= "<li><a href='author.php?aid={$aid}&s=".($start - $display)."&p={$page}'>Previous</a></li>";
            }
            
            // Hiển thị những phần số còn lại của trang
            for($i = 1; $i <= $page; $i++) {
                if($i != $current_page) {
                    $output .= "<li><a href='author.php?aid={$aid}&s=".($display * ($i - 1))."&p={$page}'>{$i}</a></li>";
                } else {
                    $output .= "<li class='current'>{$i}</li>";
                }
            }// END FOR LOOP
            
            // Nếu không phải trang cuối, thì hiển thị trang kế.
            if($current_page != $page) {
                $output .= "<li><a href='author.php?aid={$aid}&s=".($start + $display)."&p={$page}'>Next</a></li>";
            }
        } // END pagination section
            $output .= "</ul>";
            
            return $output;
    } // END pagination  
	
	function clean_email($value) {
        $suspects = array('to:', 'bcc:','cc:','content-type:','mime-version:', 'multipart-mixed:','content-transfer-encoding:');
        foreach ($suspects as $s) {
            if(strpos($value, $s) !== FALSE) {
                return '';
            }
            // Tra ve gia tri cho dau xuong hang
            $value = str_replace(array('\n', '\r', '%0a', '%0d'), '', $value);
            return trim($value);
        }
    }   
	
