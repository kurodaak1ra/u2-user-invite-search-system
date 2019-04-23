<?php
$user = $_POST['user'];
$way = $_POST['way'];
$search_flag = 0;
global $jump_arr;
$jump_arr = [];

// 限制条件
if (isset($_POST['family_tree'])) {
    $type = 'originator';
} else if (isset($_POST['master_tree'])) {
    $type = 'master';
} else {
    die('<p style="color:red;width: 100%;font-size:4em;font-weight:bold;text-align:center;position:absolute;top: 40%;">'.t('Parameter Error').'</p>');
}

// 初始查询条件
if ($way == '' || $user == '') die('<p style="color:red;width: 100%;font-size:4em;font-weight:bold;text-align:center;position:absolute;top: 40%;">'.t('Parameter Error').'</p>');
if ($way == 'uid') {
    if (strlen($user) > 5 || !is_numeric($user)) die('<p style="color:red;width: 100%;font-size:4em;font-weight:bold;text-align:center;position:absolute;top: 40%;">'.t('Parameter Error').'</p>');
}

if ($way == 'username') {
    $res = mysqli_query($link, "SELECT uid, username FROM users WHERE username = '$user' AND alive = 'yes'");
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $user = $row['uid'];
            $jump_arr['username'] = $row['username'];
        }
        $search_flag = 1;
    } else {
        echo '<script>alert("' . t('No Such User, or Please Change the Query Method') . '");</script>';
    }
} else if ($way == 'uid') {
    $res = mysqli_query($link, "SELECT username FROM users WHERE uid = '$user' AND alive = 'yes'");
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $jump_arr['username'] = $row['username'];
        }
        $search_flag = 1;
    } else {
        $master = mysqli_query($link, "SELECT master FROM users WHERE master = '$user' AND alive = 'yes'");
        if (mysqli_num_rows($master) > 0) {
            $jump_arr['username'] = '高隐私用户';
            $search_flag = 1;
        } else {
            echo '<script>alert("'.t('No Such User, or Please Change the Query Method').'");</script>';
        }
    }
}


if ($search_flag) {
    if ($type == 'originator') {
        family($link, $user);
    } else if ($type == 'master') {
        master($link, $user);
    }
}

// 查询祖先 UID
function family($link, $id) {
    $res = mysqli_query($link, "SELECT master FROM users WHERE uid = '$id' AND alive = 'yes'");
    if (mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            if ($row['master'] != '0') {
                return family($link, $row['master']);
            } else {
                $GLOBALS['jump_arr']['originator'] = $id;
            }
        }
    } else {
        $master = mysqli_query($link, "SELECT master FROM users WHERE master = '$id' AND alive = 'yes'");
        if (mysqli_num_rows($master) > 0) {
            $GLOBALS['jump_arr']['originator'] = $id;
        }
    }
}

// 查询 Master UID
function master($link, $id) {
    $res = mysqli_query($link, "SELECT master FROM users WHERE uid = '$id' AND alive = 'yes'");
    if (mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            if ($row['master'] != '0') {
                $GLOBALS['jump_arr']['master'] = $row['master'];
            } else {
                $GLOBALS['jump_arr']['master'] = $id;
            }
        }
    } else {
        $master = mysqli_query($link, "SELECT master FROM users WHERE master = '$id' AND alive = 'yes'");
        if (mysqli_num_rows($master) > 0) {
            $GLOBALS['jump_arr']['master'] = $id;
        }
    }
}