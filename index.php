<?php
    header("Content-type:text/html;charset=utf-8");
    require_once 'php/gettext.php';
    require_once 'php/html.php';
    require_once 'php/main.php';
    $link = mysqli_connect($MAIN['host'], $MAIN['name'], $MAIN['pswd'], 'u2');
    mysqli_query($link, "SET NAMES utf8");
    $list_title = '';
    $list_total = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
    <link rel="stylesheet" href="css/common.min.css">
    <link rel="stylesheet" href="css/index.css">
    <style>
        .mask {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
        }
    </style>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <title><?= isset($_POST['family_tree']) || isset($_POST['master_tree']) ? t('U2 Invite Tree') : t('U2 Invite Search System') ?></title>
</head>
<body>
    <!-- 0.8 mask -->
    <div class="mask" style="display: none;"></div>

    <?php
        if (isset($_POST['invitee'])) {
            require_once 'php/invitee.php';
        } else if (isset($_POST['family']) || isset($_POST['master'])) {
            require_once 'php/family_size.php';
            print_r($arr['generation_details'][0]);
            die();
        }
    ?>

    <?php
        $repair = mysqli_query($link, "SELECT repair FROM other");
        $repair_flag = null;
        while ($row = mysqli_fetch_assoc($repair)) { $repair_flag = $row['repair']; }
        if ($repair_flag == 'yes') {
            echo '<!-- System Repair --><div class="repair"><p>'.t('System Maintenance...').'</p></div><script>$(".mask").show();</script>';
        } else if (isset($_POST['submit'])) {
            $uid = $_POST['uid'];
            $username = $_POST['username'];
            // Verify UID Validation
            if (!is_numeric($uid) || strlen($uid) > 5) {
                check();
                echo '<script>alert("'.t('Invalid UID').'")</script>';
                return;
            }
            // Verify POST
            $res = mysqli_query($link, "SELECT * FROM users WHERE uid = '$uid' AND alive = 'yes'");
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    if ($row['username'] == $username) {
                        $uid_md5 = md5($uid);
                        $username_md5 = md5($username);
                        $timestamp = time();
                        setcookie("u",md5(md5($uid_md5.'380a4ec965f23650'.$timestamp.$username_md5)), time()+3600*0.25);
                        setcookie("c",$row['id'], time()+3600*0.25);
                        setcookie("t",$timestamp, time()+3600*0.25);
                        if (isset($_POST['family']) || isset($_POST['master'])) { page($arr['generation_details']); } else { page(); }
                        tree($link);
                    } else {
                        check();
                        echo '<script>alert("'.t('Username can\'t mach UID, please re-enter').'");</script>';
                    }
                }
            } else {
                check();
                echo '<script>alert("'.t('Username can\'t mach UID, please re-enter').'");</script>';
            }
        } else if (isset($_COOKIE['u']) && isset($_COOKIE['t']) && isset($_COOKIE['c'])) {
            // Verify Cookie
            $u = $_COOKIE['u'];
            $id = $_COOKIE['c'];
            $timestamp = $_COOKIE['t'];
            $res = mysqli_query($link, "SELECT * FROM users WHERE id = '$id' AND alive = 'yes'");
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $uid_md5 = md5($row['uid']);
                    $username_md5 = md5($row['username']);
                    if (md5(md5($uid_md5.'380a4ec965f23650'.$timestamp.$username_md5)) == $u) {
                        if (isset($_POST['family']) || isset($_POST['master'])) { page($arr['generation_details']); } else { page(); }
                        tree($link);
                    } else {
                        check();
                        echo '<script>alert("'.t('Auto login verification failed, please login again').'");</script>';
                    }
                }
            } else {
                check();
                echo '<script>alert("'.t('Auto login verification failed, please login again').'");</script>';
            }
        } else {
            check();
        }
    ?>
</body>
</html>
