<?php 
     $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
     $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
     $beforeurl = $_SERVER['HTTP_REFERER'];
     $parse_url_arr = parse_url ($beforeurl);
     parse_str ( $parse_url_arr['query'], $query_arr );
     $thisid = $query_arr['id'];

    if(!isValidURL($beforeurl)) {
        header('Location: error_page.php');
        exit();
    }

    function isValidURL($beforeurl) {
        $pattern = '/^(https?|ftp):\/\/[^\s\/$.?#].[^\s]*$/i';

        if (preg_match($pattern, $beforeurl)) {
            return true; 
        } else {
            return false;
        }
    }
?>