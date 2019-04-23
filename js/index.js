$(function () {
    // 限制输入框输入长度
    $('input[type="text"]').on('input', function () {
        if ($(this).val().length > 25) {
            $(this).val($(this).val().slice(0, 25));
        }
    })
    $('input[type="number"]').on('input', function () {
        if ($(this).val().length > 5) {
            $(this).val($(this).val().slice(0, 5));
        }
    })

    // 列表自动 top
    try {
        $('.list_info').css('top', $('.list_title')[0].offsetHeight+15+'px');
    } catch (e) { }

    // PC 端菜单 padding
    if (screen.width > 1024) {
        $('.menu').css('padding', '0 80px');
    }

    // PC 端不显示页面滚动条
    if (screen.width > 1024) {
        $('.container, .list_info').niceScroll({
            cursorborder: "",
            cursorwidth: "0px",
            cursorcolor: "transparent",
            background: 'transparent',
        });
    }

    // 数据查询
    $('.invitee, .family, .master').on('click', function () {
        $('form').attr('action', '');
        $('form').attr('target', '');
    })

    // 检测终端设置菜单 left 偏移量
    if(/Andriod|iPhone|iPad/i.test(navigator.userAgent)) {
        $('.menu').css('left', '-60%');
    } else {
        $('.menu').css('left', '-400px');
    }

    // 菜单弹出按钮移动端动画
    $('.menu_show').on('touchstart', function () {
        $(this).css('color', '#e54d26').css('background', '#fff').css('border', '1px solid #e54d26');
    })
    $('.menu_show').on('touchend', function () {
        $(this).css('color', '#fff').css('background', '#e54d26').css('border', 'none');
    })
    $('.menu_hide').on('touchstart', function () {
        $(this).css('color', '#e54d26');
    })
    $('.menu_hide').on('touchend', function () {
        $(this).css('color', '#fff');
    })

    // 登出
    $('.logout').on('click', function () {
        document.cookie = "u=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        document.cookie = "l=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        document.cookie = "t=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        document.cookie = "c=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        location.replace("./index.php");
    })

    // 登出移动端动画
    $('.logout').on('touchstart', function () {
        $(this).css('color', '#fff').css('background', 'red');
    })
    $('.logout').on('touchend', function () {
        $(this).css('color', 'red').css('background', '#fff');
    })

    // 移动端家族列表点击动画
    var list_info_flag = true;
    var list_info_color_temp = null;
    $('.list_info>li').on('click', function () {
        if (list_info_flag) {
            $('.list_info>li ul').hide();
            $(this).children('ul').show();
        } else {
            $(this).children('ul').hide();
        }
        $('.list_info').scrollTop(0);
        list_info_flag = ! list_info_flag;
    })
    $('.list_info>li').on('touchstart', function () {
        list_info_color_temp = $(this).children('a').css('color');
        $(this).children('a').css('color', '#e54d26');
    })
    $('.list_info>li').on('touchend', function () {
        $(this).children('a').css('color', list_info_color_temp);
    })

    // 切换语言
    $('.lan').on('change', function () {
        var val = $(this).val();
        if (val != '') {
            var exp = new Date();
            exp.setTime(exp.getTime() + 0.25*60*60*1000);
            document.cookie = "l=" + val + ";expires=" + exp.toGMTString();
            location.replace("./index.php");
        }
    })

    // 首次登陆按 Cookie 设置语言选中项
    var cookie_obj = {};
    var cookie_temp = document.cookie.split('; ');
    for (var i = 0;i < cookie_temp.length;i++) {
        var temp = cookie_temp[i].split('=');
        cookie_obj[temp[0]] = temp[1];
    }
    if (cookie_obj.l == 'zh_CN') {
        $('.lan .zh_CN').attr('selected', 'selected');
    } else if (cookie_obj.l == 'zh_HK') {
        $('.lan .zh_HK').attr('selected', 'selected');
    } else if (cookie_obj.l == 'zh_TW') {
        $('.lan .zh_TW').attr('selected', 'selected');
    } else if (cookie_obj.l == 'ja_JP') {
        $('.lan .ja_JP').attr('selected', 'selected');
    } else if (cookie_obj.l == 'en_US') {
        $('.lan .en_US').attr('selected', 'selected');
    }

    // bilibili conosle
    var rewrite_console = "JTVCJTVCJTIyJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwLy8lMjIsJTIyJTIyJTVELCU1QiUyMiUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCU1QyU1QyU1QyU1QyUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMC8vJTIyLCUyMiUyMiU1RCwlNUIlMjIlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlNUMlNUMlNUMlNUMlMjAlMjAlMjAlMjAlMjAlMjAlMjAvLyUyMiwlMjIlMjIlNUQsJTVCJTIyJTIwJTIwJTIwJTIwIyNEREREREREREREREREREREREREREREIyMlMjIsJTIyJTIyJTVELCU1QiUyMiUyMCUyMCUyMCUyMCMjJTIwREREREREREREREREREREREREREQlMjAjIyUyMiwlMjIlMjAlMjAlMjBfX19fX19fXyUyMCUyMCUyMF9fXyUyMCUyMCUyMF9fXyUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMF9fXyUyMCUyMCUyMF9fX19fX19fJTIwJTIwJTIwX19fJTIwJTIwJTIwX19fJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwX19fJTIyJTVELCU1QiUyMiUyMCUyMCUyMCUyMCMjJTIwaGglMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjBoaCUyMCMjJTIyLCUyMiUyMCUyMCUyMCU3QyU1QyU1QyUyMCUyMCUyMF9fJTIwJTIwJTVDJTVDJTIwJTdDJTVDJTVDJTIwJTIwJTVDJTVDJTIwJTdDJTVDJTVDJTIwJTIwJTVDJTVDJTIwJTIwJTIwJTIwJTIwJTIwJTdDJTVDJTVDJTIwJTIwJTVDJTVDJTIwJTdDJTVDJTVDJTIwJTIwJTIwX18lMjAlMjAlNUMlNUMlMjAlN0MlNUMlNUMlMjAlMjAlNUMlNUMlMjAlN0MlNUMlNUMlMjAlMjAlNUMlNUMlMjAlMjAlMjAlMjAlMjAlMjAlN0MlNUMlNUMlMjAlMjAlNUMlNUMlMjIlNUQsJTVCJTIyJTIwJTIwJTIwJTIwIyMlMjBoaCUyMCUyMCUyMCUyMC8vJTIwJTIwJTIwJTIwJTVDJTVDJTVDJTVDJTIwJTIwJTIwJTIwaGglMjAjIyUyMiwlMjIlMjAlMjAlMjAlNUMlNUMlMjAlNUMlNUMlMjAlMjAlNUMlNUMlN0MlNUMlNUMlMjAvXyU1QyU1QyUyMCU1QyU1QyUyMCUyMCU1QyU1QyU1QyU1QyUyMCU1QyU1QyUyMCUyMCU1QyU1QyUyMCUyMCUyMCUyMCUyMCU1QyU1QyUyMCU1QyU1QyUyMCUyMCU1QyU1QyU1QyU1QyUyMCU1QyU1QyUyMCUyMCU1QyU1QyU3QyU1QyU1QyUyMC9fJTVDJTVDJTIwJTVDJTVDJTIwJTIwJTVDJTVDJTVDJTVDJTIwJTVDJTVDJTIwJTIwJTVDJTVDJTIwJTIwJTIwJTIwJTIwJTVDJTVDJTIwJTVDJTVDJTIwJTIwJTVDJTVDJTIyJTVELCU1QiUyMiUyMCUyMCUyMCUyMCMjJTIwaGglMjAlMjAlMjAvLyUyMCUyMCUyMCUyMCUyMCUyMCU1QyU1QyU1QyU1QyUyMCUyMCUyMGhoJTIwIyMlMjIsJTIyJTIwJTIwJTIwJTIwJTVDJTVDJTIwJTVDJTVDJTIwJTIwJTIwX18lMjAlMjAlNUMlNUMlNUMlNUMlMjAlNUMlNUMlMjAlMjAlNUMlNUMlNUMlNUMlMjAlNUMlNUMlMjAlMjAlNUMlNUMlMjAlMjAlMjAlMjAlMjAlNUMlNUMlMjAlNUMlNUMlMjAlMjAlNUMlNUMlNUMlNUMlMjAlNUMlNUMlMjAlMjAlMjBfXyUyMCUyMCU1QyU1QyU1QyU1QyUyMCU1QyU1QyUyMCUyMCU1QyU1QyU1QyU1QyUyMCU1QyU1QyUyMCUyMCU1QyU1QyUyMCUyMCUyMCUyMCUyMCU1QyU1QyUyMCU1QyU1QyUyMCUyMCU1QyU1QyUyMiU1RCwlNUIlMjIlMjAlMjAlMjAlMjAjIyUyMGhoJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwaGglMjAjIyUyMiwlMjIlMjAlMjAlMjAlMjAlMjAlNUMlNUMlMjAlNUMlNUMlMjAlMjAlNUMlNUMlN0MlNUMlNUMlMjAlMjAlNUMlNUMlNUMlNUMlMjAlNUMlNUMlMjAlMjAlNUMlNUMlNUMlNUMlMjAlNUMlNUMlMjAlMjAlNUMlNUNfX19fJTIwJTVDJTVDJTIwJTVDJTVDJTIwJTIwJTVDJTVDJTVDJTVDJTIwJTVDJTVDJTIwJTIwJTVDJTVDJTdDJTVDJTVDJTIwJTIwJTVDJTVDJTVDJTVDJTIwJTVDJTVDJTIwJTIwJTVDJTVDJTVDJTVDJTIwJTVDJTVDJTIwJTIwJTVDJTVDX19fXyUyMCU1QyU1QyUyMCU1QyU1QyUyMCUyMCU1QyU1QyUyMiU1RCwlNUIlMjIlMjAlMjAlMjAlMjAjIyUyMGhoJTIwJTIwJTIwJTIwJTIwJTIwd3d3dyUyMCUyMCUyMCUyMCUyMCUyMGhoJTIwIyMlMjIsJTIyJTIwJTIwJTIwJTIwJTIwJTIwJTVDJTVDJTIwJTVDJTVDX19fX19fXyU1QyU1QyU1QyU1QyUyMCU1QyU1Q19fJTVDJTVDJTVDJTVDJTIwJTVDJTVDX19fX19fXyU1QyU1QyU1QyU1QyUyMCU1QyU1Q19fJTVDJTVDJTVDJTVDJTIwJTVDJTVDX19fX19fXyU1QyU1QyU1QyU1QyUyMCU1QyU1Q19fJTVDJTVDJTVDJTVDJTIwJTVDJTVDX19fX19fXyU1QyU1QyU1QyU1QyUyMCU1QyU1Q19fJTVDJTVDJTIyJTVELCU1QiUyMiUyMCUyMCUyMCUyMCMjJTIwaGglMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjBoaCUyMCMjJTIyLCUyMiUyMCUyMCUyMCUyMCUyMCUyMCUyMCU1QyU1QyU3Q19fX19fX18lN0MlMjAlNUMlNUMlN0NfXyU3QyUyMCU1QyU1QyU3Q19fX19fX18lN0MlMjAlNUMlNUMlN0NfXyU3QyUyMCU1QyU1QyU3Q19fX19fX18lN0MlMjAlNUMlNUMlN0NfXyU3QyUyMCU1QyU1QyU3Q19fX19fX18lN0MlMjAlNUMlNUMlN0NfXyU3QyUyMiU1RCwlNUIlMjIlMjAlMjAlMjAlMjAjIyUyME1NTU1NTU1NTU1NTU1NTU1NTU1NJTIwIyMlMjIsJTIyJTIyJTVELCU1QiUyMiUyMCUyMCUyMCUyMCMjTU1NTU1NTU1NTU1NTU1NTU1NTU1NTSMjJTIyLCUyMiUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCVFNyU5QyU4QiVFNyU5RCU4MCVFNiU4QyVCQSVFNSVBNSVCRCVFNyU5QyU4QiVFNyU5QSU4NCVFRiVCQyU4QyVFNyU4NCVCNiVFNSU5MCU4RSVFNSVCMCVCMSVFNCVCQiU4RSUyMGJpbGliaWxpJTIwJUU2JTg5JTkyJUU1JTg3JUJBJUU2JTlEJUE1JUU3JTlBJTg0JUU0JUJCJUEzJUU3JUEwJTgxJUVGJUJDJTgxJTIyJTVELCU1QiUyMiUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCU1QyU1Qy8lMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlNUMlNUMvJTIyLCUyMiUyMiU1RCU1RA==";
    if (!(window.ActiveXObject || "ActiveXObject"in window)) {
        var console_arr = ["%c"];
        var obj = JSON.parse(decodeURI(atob(rewrite_console)))
        obj.forEach(function(data) {
            var tv = data[0];
            var text = data[1];
            console_arr.push(tv + text);
        })
        var str = [console_arr.join("\n")].concat(["color:#e54d26"]);
        console.log.apply(console, str);
    }
})

// 菜单打开关闭
function menu_show() {
    $('.menu').css('left', '0');
    $('.menu_show').css('opacity', '0');
}
function menu_hide() {
    if(/Andriod|iPhone|iPad/i.test(navigator.userAgent)) {
        var device = '-60%';
    } else {
        var device = '-400px';
    }
    $('.menu').css('left', device);
    $('.menu_show').css('opacity', '1');
}

// 弹窗打开关闭
function win_show() {
    $('.mask, .windows, .about_win').show();
    menu_hide();
}
function win_hide() {
    $('.mask, .windows, .about_win').hide();
}