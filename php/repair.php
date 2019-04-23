<?php header("Content-type:text/html;charset=utf-8"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="keywords" content="A Personal Website for Entertaiment">
    <meta name="description" content="A Personal Website for Entertaiment">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">    
    <link rel="stylesheet" href="../css/common.min.css">
    <title>U2 邀请查询系统维护开关</title>
    <style>
        body {
            display: flex;
            align-items: center;
        }
        div {
            width: 100%;
            position: absolute;
            top: 5%;
        }
        p {
            text-align: center;
            font-size: 1.5em;
        }
        form {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        input {
            display: block;
            width: 50%;
            max-width: 300px;
            border-radius: 50%;
            font-size: 2.5em;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
    <script>
        window.onload = function () {
            var input = document.getElementsByClassName('ipt')[0];
            input.style.height = input.offsetWidth + 'px';
        }
    </script>
</head>
<body>
    <div>
        <p>一键维护按钮</p>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <?php
            require_once 'main.php';
            $link = mysqli_connect($MAIN['host'], $MAIN['name'], $MAIN['pswd'], "u2");
            $res = mysqli_query($link, "SELECT repair FROM other");
            while($row = mysqli_fetch_assoc($res)) {
                $status = $row['repair'];
                if ((string)$status == 'yes') {
                    echo '<input type="submit" name="submit" value="维护中" class="ipt" style="background: yellow;">';
                } else {
                    echo '<input type="submit" name="submit" value="运行中" class="ipt" style="background: green;">';
                }
            }
        ?>
        <?php
            if (isset($_POST['submit'])) {
                if ((string)$status == 'no') {
                    $temp = 'yes';
                    javascript('维护中', 'yellow');
                } else {
                    $temp = 'no';
                    javascript('运行中', 'green');
                }
                $res = mysqli_query($link, "UPDATE other SET repair='$temp' WHERE repair='$status'");
            }

            function javascript($str, $color) {
                echo '
                    <script>
                        window.onload = function () {
                            var input = document.getElementsByClassName("ipt")[0];
                            input.style.background = "'.$color.'";
                            input.setAttribute("value", "'.$str.'");
                            input.style.height = input.offsetWidth + "px";
                        }
                    </script>
                ';
            }
        ?>
    </form>
</body>
</html>