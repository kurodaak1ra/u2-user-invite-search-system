<?php
/**
 * Created by PhpStorm.
 * User: akira
 * Date: 2018-12-29
 * Time: 10:58
 */
function page($arr = null) {
    $select = '';
    $value = '';
    if (isset($_POST['way'])) {
        $select = $_POST['way'] == 'uid' ? 'selected="selected"' : '';
    }
    if (isset($_POST['user'])) {
        $value = $_POST['user'];
    }
    echo '
        <span class="menu_btn menu_show" onclick="menu_show();"></span>
        <div class="menu">
            <span class="menu_btn menu_hide" onclick="menu_hide();"></span>
            <p class="menu_title">'.t('MENU').'</p>
            <ul class="menu_list">
                <li>
                    <form action="" method="post" class="search">
                        <!-- <div class="ipt"> -->
                            <select name="way" class="way">
                                <option value="username">'.t('Username').'</option>
                                <option value="uid" '.$select.'>'.t('UID').'</option>
                            </select>
                            <input type="text" name="user" maxlength="25" placeholder="'.t('Please Enter Username').'" value="'.$value.'">
                            <script>
                                // Change Search Way 
                                var way = $(".way").val();
                                $(".way").on("change", function () {
                                    way = $(this).val();
                                    if (way == "username") {
                                        $("input[type=\'number\']").val("")
                                        .attr("placeholder", "'.t('Please Enter Username').'")
                                        .attr("type", "text");
                                    } else if (way == "uid") {
                                        $("input[type=\'text\']").val("")
                                        .attr("placeholder", "'.t('Please Enter UID').'")
                                        .attr("type", "number");
                                    }
                                })
                            </script>
                        <!-- </div> -->
                        <div class="btn">
                            <input type="submit" value="'.t('Invitee').'" name="invitee" title="Search invitee" class="invitee">
                            <input type="submit" value="'.t('Families').'" name="family" title="Total number of families" class="family">
                            <input type="submit" value="'.t('Same Clan').'" name="master" title="Total number of member of the same clan" class="master">
                            <input type="submit" value="'.t('Family Tree').'" name="family_tree" title="Family tree" class="family_tree">
                            <input type="submit" value="'.t('SC Tree').'" name="master_tree" title="Member of the same clan tree" class="master_tree">
                            <input type="button" value="'.t('About').'" title="About" onclick="win_show();">
                        </div>
                        <script>
                            // Prohibit Null Search
                            $(".invitee, .family, .master, .family_tree, .master_tree").on("click", function () {
                                if ($(".search>input").val() == "") {
                                    alert("'.t('Please Enter Username or UID').'");
                                    return false;
                                }
                            })
                        </script>
                    </form>
                </li>
                <li>
                    <select class="lan">
                        <option value="zh_CN" class="zh_CN">中国 - 简体中文</option>
                        <option value="zh_HK" class="zh_HK">香港 - 繁體中文</option>
                        <option value="zh_TW" class="zh_TW">台灣 - 繁體中文</option>
                        <option value="ja_JP" class="ja_JP">日本 - 日本語</option>
                        <option value="en_US" class="en_US">United States - English</option>
                    </select>
                </li>
                <li>
                    <button class="logout">'.t('Logout').'</button>
                </li>
            </ul>
        </div>';
    if (isset($_POST['invitee']) || isset($_POST['family']) || isset($_POST['master'])) {
        echo '
            <div class="list_title">
                <p class="master">'.$GLOBALS['list_title'].'</p>
                <p class="total">'.$GLOBALS['list_total'].'</p>
            </div>
            <ul class="list_info">
        ';
        $num = [
            t('First-generation invitations:'),
            t('Second-generation invitations:'),
            t('Third-generation invitations:'),
            t('Forth-generation invitations:'),
            t('Fifth-generation invitations:'),
            t('Sixth-generation invitations:'),
            t('Seventh-generation invitations:'),
            t('Eighth-generation invitations:'),
            t('Ninth-generation invitations:'),
            t('Tenth-generation invitations:')
        ];
        if (isset($_POST['invitee'])) {
            for ($i = 0;$i < count($GLOBALS['arr']['invitee']);$i++)
                echo '<li><a href="https://u2.dmhy.org/userdetails.php?id='.$GLOBALS['arr']['invitee'][$i]['uid'].'" title="https://u2.dmhy.org/userdetails.php?id='.$GLOBALS['arr']['invitee'][$i]['uid'].'" target="_blank"><span>'.$GLOBALS['arr']['invitee'][$i]['username'].'</span><span>'.$GLOBALS['arr']['invitee'][$i]['uid'].'</span></a></li>';
        } else if (isset($_POST['family'])) {
            for ($i = 0;$i < count($GLOBALS['arr']['generation']);$i++) {
                if ($GLOBALS['arr']['seniority'] == $i && $GLOBALS['arr']['seniority'] != 999) {
                    echo '<li><a href="javascript:void(0)" style="color: #e54d26;font-weight: bold;"><span>'.$num[$i].'</span><span>'.$GLOBALS['arr']['generation'][$i].' </span></a><ul style="display: none;">';
                    foreach ($arr[$i] as $key => $val) {
                        echo '<li><a href="https://u2.dmhy.org/userdetails.php?id='.$key.'" title="https://u2.dmhy.org/userdetails.php?id='.$key.'" target="_blank"><span>'.$val.'</span><span>'.$key.'</span></a></li>';
                    }
                    echo '</ul></li>';
                } else {
                    echo '<li><a href="javascript:void(0)"><span>'.$num[$i].'</span><span>'.$GLOBALS['arr']['generation'][$i].' </span></a><ul style="display: none;">';
                    foreach ($arr[$i] as $key => $val) {
                        echo '<li><a href="https://u2.dmhy.org/userdetails.php?id='.$key.'" title="https://u2.dmhy.org/userdetails.php?id='.$key.'" target="_blank"><span>'.$val.'</span><span>'.$key.'</span></a></li>';
                    }
                    echo '</ul></li>';
                }
            }
        } else if (isset($_POST['master'])) {
            for ($i = 0;$i < count($GLOBALS['arr']['generation']);$i++) {
                if ($GLOBALS['arr']['seniority'] == $i && $GLOBALS['arr']['seniority'] != 999) {
                    echo '<li><a href="javascript:void(0)" style="text-decoration: underline;font-weight: bold;"><span>'.$num[$i].'</span><span>'.$GLOBALS['arr']['generation'][$i].' </span></a><ul style="display: none;">';
                    foreach ($arr[$i] as $key => $val) {
                        echo '<li><a href="https://u2.dmhy.org/userdetails.php?id='.$key.'" title="https://u2.dmhy.org/userdetails.php?id='.$key.'" target="_blank"><span>'.$val.'</span><span>'.$key.'</span></a></li>';
                    }
                    echo '</ul></li>';
                } else {
                    echo '<li><a href="javascript:void(0)"><span>'.$num[$i].'</span><span>'.$GLOBALS['arr']['generation'][$i].' </span></a><ul style="display: none;">';
                    foreach ($arr[$i] as $key => $val) {
                        echo '<li><a href="https://u2.dmhy.org/userdetails.php?id='.$key.'" title="https://u2.dmhy.org/userdetails.php?id='.$key.'" target="_blank"><span>'.$val.'</span><span>'.$key.'</span></a></li>';
                    }
                    echo '</ul></li>';
                }
            }
        }
        echo '</ul>';
    }
    // Windows
    $request_time = mysqli_query($GLOBALS['link'], "SELECT data_time FROM other");
    while ($row = mysqli_fetch_assoc($request_time)) {
        echo '
            <!-- About -->
            <div class="windows" style="display:none;">
                <div class="about_win">
                    <p>'.t('This System is Provide for U2 Users Search Invitees').'</p>
                    <p>'.t('Developers (Names not listed in order) :').'</p>
                    <p><a href="https://u2.dmhy.org/userdetails.php?id=45735">graydove (45735)</a></p>
                    <p><a href="https://u2.dmhy.org/userdetails.php?id=46657">KurodaAkira (46657)</a></p>
                    <p><a href="https://u2.dmhy.org/userdetails.php?id=46808">DeveloperHZH (46808)</a></p>
                    <p>'.t('Main Topic: ').'<a href="https://u2.dmhy.org/forums.php?action=viewtopic&forumid=7&topicid=11127">'.t('Click Me to Jump').'</a></p>
                    <p>'.t('Readme: ').'<a href="https://u2.dmhy.org/forums.php?action=viewtopic&forumid=7&topicid=11145">'.t('Click Me to Jump').'</a></p>
                    <p>'.t('Data request time: ').'<span>'.$row["data_time"].'</span></p>
                    <p>'.t('Notice: If your privacy set strong, you can only use uid to search, if you are not invite anybody, you can\'t search about yours anything').'</p>
                    <ul onclick="win_hide();">
                        <li></li>
                        <li></li>
                    </ul>
                </div>
            </div>

            <script src="js/index.js"></script>
        ';
    }
}

function check() {
    echo '
        <!-- 身份认证 -->
        <div class="windows">
            <form action"" method="post" class="check">
                <p>'.t('U2 Users Authentication').'</p>
                <p>'.t('Please Full Enter Username and UID').'</p>
                <input type="number" name="uid" placeholder="'.t('Please Enter UID').'" maxlength="5">
                <input type="text" name="username" placeholder="'.t('Please Enter Username').'" maxlength="25">
                <select name="lan" style="text-indent: 0;">
                    <option value="auto">'.t('Choose Language (Default: Current Page Setup)').'</option>
                    <option value="zh_CN">中国 - 简体中文</option>
                    <option value="zh_HK">香港 - 繁體中文</option>
                    <option value="zh_TW">台灣 - 繁體中文</option>
                    <option value="ja_JP">日本 - 日本語</option>
                    <option value="en_US">United States - English</option>
                </select>
                <input type="submit" name="submit" value="'.t('Login').'" style="cursor: pointer">
            </form>
        </div>
        <script>
            setTimeout(function () {
                $(".mask").show();
            }, 100)
            $(".check input:nth-of-type(1)").on("focus", function () {
                $(".check p:nth-child(2)").css("opacity", "0");
            })
            $(".check input:nth-of-type(2)").on("focus", function () {
                $(".check p:nth-child(2)").css("opacity", "0");
            })
            $(".check input:nth-of-type(3)").on("click", function () {
                if ($(".check input:nth-of-type(1)").val() == "" || $(".check input:nth-of-type(2)").val() == "") {
                    $(".check p:nth-child(2)").css("opacity", "1");
                    return false;
                }
            })
            $("input[type=\'number\']").on("input", function () {
                if ($(this).val().length > 5) {
                    $(this).val($(this).val().slice(0, 5));
                }
            })
        </script>
    ';
}

function tree($link) {
    if (isset($_POST['family_tree']) || isset($_POST['master_tree'])) {
        $jump_arr = [];
        require_once 'php/jump_tree.php';
        require_once 'php/json.php';
        echo '<script>var user = "' . $jump_arr['username'] . '"</script>';
        echo '<script src="js/echarts.min.js"></script>';
        echo '<script src="js/tree.js?"></script>';
    }
}