<?php
    //File dùng để tạo kết nối với CSDL blog_riel
    $tenserver = "localhost";
    $tennguoidung = "root";
    $matkhau = "";
    $tendatabase = "blog_riel";
    
    $connect = new mysqli($tenserver, $tennguoidung, $matkhau, $tendatabase);
    //Nếu kết nối thất bại đến CSDL sẽ dừng toàn bộ trang web và báo lỗi.
    if($connect->connect_error){
        die("Connection failed" . $connect->connect_error);
    }
?>