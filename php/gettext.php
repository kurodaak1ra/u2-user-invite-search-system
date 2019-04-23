<?php  
    // $ip = ip();
    $ip = '61.144.117.212';

    $country = ipApiEn($ip)->status == 'success' ? ipApiEn($ip)->country : '';

    if ($country == 'China')
        $lan = 'zh_CN';
    elseif ($country == 'Hong Kong')
        $lan = 'zh_HK';
    elseif ($country == 'Taiwan')
        $lan = 'zh_TW';
    elseif ($country == 'Japan')
        $lan = 'ja_JP';
    else
        $lan = 'en_US';

    if (isset($_POST['lan'])) {
        if ($_POST['lan'] != 'auto') {
            $lan = $_POST['lan'];
            setcookie("l", $_POST['lan'], time() + 3600 * 0.25);
        } else {
            setcookie("l", $lan, time() + 3600 * 0.25);
        }
    } else {
        if (isset($_COOKIE['l'])) $lan = $_COOKIE['l'];
    }

    if ($lan == 'zh_CN') {
        putenv('LANG=zh_CN');
        setlocale(LC_ALL, 'zh_CN');
    } elseif ($lan == 'zh_HK') {
        putenv('LANG=zh_HK');
        setlocale(LC_ALL, 'zh_HK');
    } elseif ($lan == 'zh_TW') {
        putenv('LANG=zh_TW');
        setlocale(LC_ALL, 'zh_TW');
    } elseif ($lan == 'ja_JP') {
        putenv('LANG=ja_JP');
        setlocale(LC_ALL, 'ja_JP');
    } elseif ($lan == 'en_US') {  
        putenv('LANG=en_US');   
        setlocale(LC_ALL, 'en_US');
    }

    $domain = 'gettext';                              // 域名，可以任意取个有意义的名字，不过要跟相应的.mo文件的文件名相同（不包括扩展名）。
    bindtextdomain($domain, "multilanguage/");        // 设置某个域的mo文件路径
    bind_textdomain_codeset($domain, 'UTF-8');        // 设置mo文件的编码为UTF-8
    textdomain($domain);                              // 设置gettext()函数从哪个域去找mo文件

    function t($context) { return gettext($context); }

    function ip() {
        if (isset($_SERVER)) {    
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv( "HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

    function ipApiEn($ip) {
        $url = 'http://ip-api.com/json/' . $ip;
        $data = json_decode(file_get_contents($url));
        return $data;
    }
?>