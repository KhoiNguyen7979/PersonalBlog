<?php
    $tenserver = "localhost";
    $tennguoidung = "root";
    $matkhau = "";
    $tendatabase = "blog_riel";
    
    $connect = new mysqli($tenserver, $tennguoidung, $matkhau, $tendatabase);
    
    if($connect->connect_error){
        die("Kết nối failed" . $connect->connect_error);
    }
?>