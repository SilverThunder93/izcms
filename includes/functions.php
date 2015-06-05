<?php
    // Xac dinh hang so cho dia chi tuyet doi
    define('BASE_URL', 'http://localhost/izcms/');
    define('LIVE', FALSE); // FALSE la dang trong qua trinh phat trien | TRUE la dang trong production
    // Kiem tra xem ket qua tra ve co dung hay khong?
    function confirm_query($result, $query) {
        global $dbc;
        if(!$result && !LIVE) {
            die("Query {$query} \n<br/> MySQL Error: " .mysqli_error($dbc));
        } 
    }

    // Tao function de bao loi rieng
    function custom_error_handler($e_number, $e_message, $e_files, $e_line, $e_vars) {
        // Tao ra mot cau bao loi rieng
        $message = "<p class='warning'>Có lỗi xảy ra ở file {$e_files} tại dòng {$e_line}: {$e_message} \n";
        //$message .= print_r($e_vars, 1);

        if(!LIVE) {
            // Dang trong qua trinh phat trien, in loi chi tiet va cu the
            echo "<pre>". $message ."</pre><br/>\n";
        } else {
            // Dang o trong qua trinh production, va live tren host
            echo "<p class='warning'>Oops! something went wrong, we are so sorry for the inconvenice.</p>";
        }
    }// End custom_error_handler

    // use our custom handler
    set_error_handler('custom_error_handler');

    // Kiem tra xem nguoi dung da dang nhap hay chua?
    function is_logged_in() {
        if(!isset($_SESSION['uid'])) {
            redirect_to('login.php');
        }
    } // END is_logged_in
        
    // Tai dinh huong nguoi dung ve trang mac dinh la index
    function redirect_to($page = 'index.php') {
        $url = BASE_URL . $page;
        header("Location: $url");
        exit();
    }
    
    // Ham nay de thong bao loi
    function report_error($mgs) {
        if(isset($mgs)) {
            foreach ($mgs as $m) {
                echo $m;
            }
        }
    } // END report_error
       
    // Cat chu~ de hien thi thanh doan van ngan.
    function the_excerpt($text, $string = 400) {
        $sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8');
        if(strlen($sanitized) > $string) {
            $cutString = substr($sanitized,0,$string);
            $words = substr($sanitized, 0, strrpos($cutString, ' '));
            return $words;
        } else {
            return $sanitized;
        }
       
    } // End the_excerpt
    
    // Tao paragraph tu CSDL
    function the_content($text) {
        $sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8');
        return str_replace(array("\r\n", "\n"),array("<p>", "</p>"),$sanitized);
    }
    
    // Ham tao ra de kiem tra xem co phai la admin hay khong
    function is_admin() {

        return isset($_SESSION['user_level']) && ($_SESSION['user_level'] == 2);
    }
    
    // Kiem tra xem nguoi dung co the vao trang admin hay khong?
    function admin_access() {
        if(!is_admin()) {
            redirect_to();
        }
    }
    
    // Kiem tra xem $id co hop le, la dang so hay khong?
    function validate_id($id) {
        if(isset($id) && filter_var($id, FILTER_VALIDATE_INT, array('min_range' =>1))) {
            $val_id = $id;
            return $val_id;
        } else {
            return NULL;
        }
    } // End validate_id
    
    // Truy van CSDL de lay post va thong tin nguoi dung.
    function get_page_by_id($id) {
        global $dbc;
        $q = " SELECT p.page_name, p.page_id, p.content,"; 
        $q .= " DATE_FORMAT(p.post_on, '%b %d, %y') AS date, ";
        $q .= " CONCAT_WS(' ', u.first_name, u.last_name) AS name, u.user_id ";
        $q .= " FROM pages AS p ";
        $q .= " INNER JOIN users AS u ";
        $q .= " USING (user_id) ";
        $q .= " WHERE p.page_id={$id}";
        $q .= " ORDER BY date ASC LIMIT 1";
        $result = mysqli_query($dbc,$q);
        confirm_query($result, $q);
        return $result;
    } // End get_page_by_id
    
    // Ham dung chong spam trong form
    function captcha() {
        $qna = array(
                1 => array('question' => 'Mot cong mot', 'answer' => 2),
                2 => array('question' => 'ba tru hai', 'answer' => 1),
                3 => array('question' => 'ba nhan nam', 'answer' => 15),
                4 => array('question' => 'sau chia hai', 'answer' => 3),
                5 => array('question' => 'nang bach tuyet va .... chu lun', 'answer' => 7),
                6 => array('question' => 'Alibaba va ... ten cuop', 'answer' => 40),
                7 => array('question' => 'an mot qua khe, tra .... cuc vang', 'answer' => 1),
                8 => array('question' => 'may tui .... gang, mang di ma dung', 'answer' => 3)
                );
        $rand_key = array_rand($qna); // Lay ngau nhien mot trong cac array 1, 2, 4
        $_SESSION['q'] = $qna[$rand_key];
        return $question = $qna[$rand_key]['question'];
    } // END function captcha
    
    // Phan trang
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
    
    // Ham de chong spam email
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
     