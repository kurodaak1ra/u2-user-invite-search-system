<?php
$user = $_POST['user'];
$way = $_POST['way'];
$search_flag = 0;
$arr = [
    'master' => [],
    'invitee' => []
];

// 限制条件
if ($way == '' || $user == '') {
    die('<p style="color:red;width: 100%;font-size:4em;font-weight:bold;text-align:center;position:absolute;top: 40%;">'.t('Parameter Error').'</p>');
}
if ($way == 'uid') {
    if (strlen($user) > 5 || !is_numeric($user)) {
        die('<p style="color:red;width: 100%;font-size:4em;font-weight:bold;text-align:center;position:absolute;top: 40%;">'.t('Parameter Error').'</p>');
    }
}

// 查询方式
if ($way == 'uid') {
    $uid = mysqli_query($link, "SELECT uid, username FROM users WHERE uid = '$user' AND alive = 'yes'");
} elseif ($way == 'username') {
    $uid = mysqli_query($link, "SELECT uid, username FROM users WHERE username = '$user' AND alive = 'yes'");
}

// 当前查询条件的 UID username
if (mysqli_num_rows($uid) > 0) {
    while($row = mysqli_fetch_assoc($uid)) {
        $arr['master'] = ['uid' => $row['uid'],'username' => $row['username']];
    }
    $search_flag = 1;
} else {
    if ($way == 'uid') {
        $master = mysqli_query($link, "SELECT master FROM users WHERE master = '$user' AND alive = 'yes'");
        if (mysqli_num_rows($master) > 0) {
            $arr['master'] = ['uid' => $user,'username' => '<span style="color: #fff;background: #e54d26;-webkit-border-radius: 50px;-moz-border-radius: 50px;border-radius: 50px;padding: 0 0.5em;">高隐私用户</span>'];
            $search_flag = 1;
        } else {
            echo '<script>alert("'.t('No Such User, or Please Change the Query Method').'");</script>';
        }
    } else {
        echo '<script>alert("'.t('No Such User, or Please Change the Query Method').'");</script>';
    }
}

if ($search_flag) {
    // 被邀请者
    if ($way == 'username') $user = $arr['master']['uid'];
    $invitee = mysqli_query($link, "SELECT uid, username FROM users WHERE master = '$user' AND alive = 'yes'");
    if (mysqli_num_rows($invitee) > 0) {
        while($row = mysqli_fetch_assoc($invitee)) {
            array_push($arr['invitee'], ['uid' => $row['uid'],'username' => $row['username']]);
        }
    }

    // 设置表头
    $list_title = $arr['master']['username'].' ['.$arr['master']['uid'].']';
    $list_total = t('Direct invite: ').count($arr['invitee']);
}