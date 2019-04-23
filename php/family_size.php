<?php
$way = $_POST['way'];
$user = $_POST['user'];

$search_start_flag = 0;
$search_uid_flag = 0;
$self_flag = 1;

$arr = [
    'self' => [],
    'master' => [],
    'generation' => [],
    'originator' => [],
    'seniority' => 999,
    'generation_details' => []
];

$json = [];
$id_arr = [];
$master_flag = 0;

// 查询方式
if (isset($_POST['family'])) {
    $type = 'originator';
} else if (isset($_POST['master'])) {
    $type = 'master';
} else {
    die('<p style="color:red;width: 100%;font-size:4em;font-weight:bold;text-align:center;position:absolute;top: 40%;">'.t('Parameter Error').'</p>');
}

// 限制条件
if ($way == '' || $user == '') die('<p style="color:red;width: 100%;font-size:4em;font-weight:bold;text-align:center;position:absolute;top: 40%;">'.t('Parameter Error').'</p>');
if ($way == 'uid') {
    if (strlen($user) > 5 || !is_numeric($user)) die('<p style="color:red;width: 100%;font-size:4em;font-weight:bold;text-align:center;position:absolute;top: 40%;">'.t('Parameter Error').'</p>');
}

// username => UID
if ($way == 'username') {
    $res = mysqli_query($link, "SELECT uid, username FROM users WHERE username = '$user' AND alive = 'yes'");
    if (mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            $user = $row['uid'];
            $arr['self']['uid'] = $row['uid'];
            $arr['self']['username'] = $row['username'];
        }
        $search_uid_flag = 1;
    } else {
        echo '<script>alert("'.t('No Such User, or Please Change the Query Method').'");</script>';
    }
} else {
    $search_uid_flag = 1;
}

if ($search_uid_flag) {
    // 查询祖先 UID
    originator($link, $user);
}

if ($search_start_flag) {
    // 查询开始，方式（祖先 or 本家）
    if ($type == 'originator') {
        loop($link, $arr['originator']['uid']);
    } else if ($type == 'master') {
        loop($link, $arr['master']['uid']);
    }
    
    // 循环 循环 查 Master 下一级
    $arr_count = 0;
    for ($i = 0;$i < 20;$i++) {
        $temp = loop_echo($link);
        if ($temp == $arr_count) {
            break;
        }
        $arr_count = $temp;
    }
    
    // 计算家族人口
    $count = 0;
    $seniority = 0;
    for ($i = count($json)-1;$i >= 0;$i--) {
        $temp_arr = [];
        foreach ($json[$i] as $key => $val) {
            for ($j = 0;$j < count($val);$j++) {
                if ($val[$j]['uid'] == $user) {
                    $arr['seniority'] = $seniority;
                }
                $temp_arr[$val[$j]['uid']] = $val[$j]['username'];
            }
            $count+=count($val);
        }
        array_push($arr['generation'], $count);
        array_push($arr['generation_details'], $temp_arr);
        $count = 0;
        $temp_arr = array();
        $seniority++;
    }

    // 设置变量
    if (isset($_POST['family'])) {
        $list_title = $arr['originator']['username'].' ['.$arr['originator']['uid'].']';
    } else if (isset($_POST['master'])) {
        $list_title = $arr['master']['username'].' ['.$arr['master']['uid'].']';
    }

    // 后宫总数
    $count = 0;
    for ($i = 0;$i < count($arr['generation']);$i++) {
        $count+=$arr['generation'][$i];
    }

    // 列表头
    if (isset($_POST['family'])) {
        $list_total = t('Total number of families: ').$count;
    } else if (isset($_POST['master'])) {
        $list_total = t('Total member of the same clan: ').$count;
    }
}

// 遍历查询 祖先、master
function originator($link, $id) {
    $res = mysqli_query($link, "SELECT uid, username, master FROM users WHERE uid = '$id' AND alive = 'yes'");
    if (mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            if ($GLOBALS['self_flag']) {
                $GLOBALS['arr']['self']['uid'] = $row['uid'];
                $GLOBALS['arr']['self']['username'] = $row['username'];
                $GLOBALS['self_flag'] = 0;
            }
            if ($row['master'] != '0') {
                if ($GLOBALS['master_flag'] == 0) {
                    $master = $row['master'];
                    $res2 = mysqli_query($link, "SELECT username FROM users WHERE uid = '$master' AND alive = 'yes'");
                    if (mysqli_num_rows($res) > 0) {
                        while($row2 = mysqli_fetch_assoc($res2)) {
                            $master_name = $row2['username'];
                            $GLOBALS['arr']['master'] = ['username'=>$master_name, 'uid'=>$row['master']];
                        }
                    } else {
                        $GLOBALS['arr']['master'] = ['username'=>'<span style="color: #fff;background: #e54d26;-webkit-border-radius: 50px;-moz-border-radius: 50px;border-radius: 50px;padding: 0 0.5em;">高隐私用户</span>', 'uid'=>$id];
                    }
                    $GLOBALS['master_flag'] = 1;
                }
                return originator($link, $row['master']);
            } else {
                $GLOBALS['arr']['originator'] = ['username'=>$row['username'], 'uid'=>$row['uid']];
                if (count($GLOBALS['arr']['master']) == 0) {
                    $GLOBALS['arr']['master'] = ['username'=>$row['username'], 'uid'=>$id];
                }
            }
        }
        $GLOBALS['search_start_flag'] = 1;
    } else {
        $master = mysqli_query($link, "SELECT master FROM users WHERE master = '$id' AND alive = 'yes'");
        if (mysqli_num_rows($master) > 0) {
            $GLOBALS['arr']['originator'] = ['uid'=>$id, 'username'=>'<span style="color: #fff;background: #e54d26;-webkit-border-radius: 50px;-moz-border-radius: 50px;border-radius: 50px;padding: 0 0.5em;">高隐私用户</span>'];
            $GLOBALS['arr']['master'] = ['uid'=>$id, 'username'=>'<span style="color: #fff;background: #e54d26;-webkit-border-radius: 50px;-moz-border-radius: 50px;border-radius: 50px;padding: 0 0.5em;">高隐私用户</span>'];
            $GLOBALS['search_start_flag'] = 1;
        } else {
            echo '<script>alert("'.t('No Such User, or Please Change the Query Method').'");</script>';
        }
    }
}

// 查 Master 直属后宫并 push uid 到数组
function loop($link, $id) {
    $res = mysqli_query($link, "SELECT uid, username, master FROM users WHERE master = '$id' AND alive = 'yes'");
    if (mysqli_num_rows($res) > 0) {
        $id_arr = [];
        while($row = mysqli_fetch_assoc($res)) {
            if ($row['master'] != 0) {
                $temp_arr = [
                    'uid' => $row['uid'],
                    'username' => $row['username'],
                ];
                array_push($id_arr, $temp_arr);
            }
        }
        $GLOBALS['id_arr'][$id] = $id_arr;
    }
}

// 循环查 Master 后宫的后宫的后宫。。。
function loop_echo($link) {
    if (count($GLOBALS['id_arr']) != 0) {
        array_unshift($GLOBALS['json'], $GLOBALS['id_arr']);
        $GLOBALS['id_arr'] = array();
    }
    foreach ($GLOBALS['json'][0] as $key => $val) {
        for ($i = 0;$i < count($val);$i++) {
            loop($link, $val[$i]['uid']);
        }
    }
    return count($GLOBALS['json']);
}