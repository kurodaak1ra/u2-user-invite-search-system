<?php
    if (count($jump_arr) == 0) die();

    if ($type == 'originator') {
        $user = $jump_arr['originator'];
    } else if ($type == 'master') {
        $user = $jump_arr['master'];
    }

    global $json;
    $json = [];
    global $id_arr;
    $id_arr = [];
    global $originator;
    $originator = [];

    // 程序开始
    originator($link, $user);
    loop($link, $originator['value']);
    $arr_count = 0;
    for ($i = 0;$i < 20;$i++) {
        $temp = loop_echo($link);
        if ($temp == $arr_count) {
            break;
        }
        $arr_count = $temp;
    }

    // 查询祖辈 UID username
    function originator($link, $id) {
        $res = mysqli_query($link, "SELECT uid, username FROM users WHERE uid = '$id' AND alive = 'yes'");
        if (mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                $GLOBALS['originator'] = ['name'=>$row['username'], 'value'=>$row['uid']];
            }
        } else {
            $master = mysqli_query($link, "SELECT master FROM users WHERE master = '$id' AND alive = 'yes'");
            if (mysqli_num_rows($master) > 0) {
                $GLOBALS['originator'] = ['name'=>'高隐私用户', 'value'=>$id];
            }
        }
    }
    
    // 循环代数
    function loop($link, $id) {
        $res = mysqli_query($link, "SELECT uid, username, master FROM users WHERE master = '$id' AND alive = 'yes'");
        if (mysqli_num_rows($res) > 0) {
            $id_arr = [];
            while($row = mysqli_fetch_assoc($res)) {
                if ($row['master'] != '0') {
                    $temp_arr = [
                        'value' => $row['uid'],
                        'name' => $row['username'],
                        'children' => []
                    ];
                    array_push($id_arr, $temp_arr);
                }
            }
            $GLOBALS['id_arr'][$id] = $id_arr;
        }
    }

    // 遍历生成代数 数组
    function loop_echo($link) {
        if (count($GLOBALS['id_arr']) != 0) {
            array_unshift($GLOBALS['json'], $GLOBALS['id_arr']);
            $GLOBALS['id_arr'] = array();
        }
        foreach ($GLOBALS['json'][0] as $key => $val) {
            for ($i = 0;$i < count($val);$i++) {
                loop($link, $val[$i]['value'], $val[$i]['name']);
            }
        }
        return count($GLOBALS['json']);
    }

    // 生成 json
    for ($i = 0;$i < count($json)-1;$i++) {
        foreach ($json[$i+1] as $key => $val) {
            for ($j = 0;$j < count($val);$j++) {
                if (isset($json[$i][$val[$j]['value']])) {
                    $val[$j]['children'] = $json[$i][$val[$j]['value']];
                }
            }
            $json[$i+1][$key] = $val;
        }
    }

    // 添加祖先 UID username
    $originator['children'] = $json[count($json)-1][$originator['value']];

    // 输出 js 变量
    echo '
        <script>
            var data = '.json_encode($originator).'
        </script>
    ';
?>