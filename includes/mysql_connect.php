<?php 
	//connect with CSDL
	$dbc = mysqli_connect('localhost', 'root', '', 'izcms');
	
	//Nếu kết nối không thành công sẽ báo lỗi ra trình duyệt
	if(!$dbc) {
		trigger_error("Could not connect to DB: " . mysqli_connect_error());
	} else {
		//Đặt phương thức kết nối là utf8_decode
		
		mysqli_set_charset($dbc, 'utf-8');		
	}
	
	

?>