<?php
define('Copyright', '作者QQ:1834219632');
define('ROOT_PATH', $_SERVER["DOCUMENT_ROOT"] . '/');
include_once ROOT_PATH . 'function/global.php';
include_once ROOT_PATH . 'function/opNumberList.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $type = $_POST['type'];
    $db = new DB();
    $select_str = '';
    $table_name = '';
    $ball_selecter = '';
    $count = 0;

    if (!isset($_POST['count'])) {
        $count = 0;
    } else {
        $count = $_POST['count'];
    }

    switch ($type) {
        case 1;
        case 5;
            $ball_selecter = ' `g_ball_1`, `g_ball_2`, `g_ball_3`, `g_ball_4`, `g_ball_5`, `g_ball_6`, `g_ball_7`, `g_ball_8` ';
            break;
        case 2;
            $ball_selecter = ' `g_ball_1`, `g_ball_2`, `g_ball_3`, `g_ball_4`, `g_ball_5`';
            break;
        case 6;
            $ball_selecter = ' `g_ball_1`, `g_ball_2`, `g_ball_3`, `g_ball_4`, `g_ball_5`, `g_ball_6`, `g_ball_7`
            , `g_ball_8`, `g_ball_9`,`g_ball_10` ';
            break;
        case 9;
            $ball_selecter = ' `g_ball_1`, `g_ball_2`, `g_ball_3`';
            break;
    }
    if ($type == 1) {
        $table_name = 'g_history';
    } else {
        $table_name = 'g_history'. $type;
    }

    if ($type !=1 && $type != 5 ) {
        $select_str = '`g_date`,';
    }

    $start_date = $start_date . ' 02:00';
    $end_date = dayMorning($end_date, (60 * 60 * 24)) . ' 02:00';
    $date = " `g_date` > '{$start_date}' AND `g_date` < '{$end_date}' ";
    $sql_str =
        "select
        `g_id`,
        {$select_str}
        `g_qishu`,
        {$ball_selecter}
        from {$table_name} where {$date} order by g_id desc LIMIT {$count},".($count+30);
    $results = $db->query($sql_str,1);

    if ($type == 1) {
        $ret = klc_transfer($results,$type);
    } elseif ($type == 5) {
        $ret = klc_transfer($results,$type);
    } else {
        $ret = ssc_transfer($results);
    }
    $ret['ret_array'] = $ret;
    $ret['type'] = $type;


    echo json_encode($ret);
    exit;

} else {
    if (isset($_GET['id'])) {
        $typeid = $_GET['id'];
    } else {
        if ((isset($_SESSION['cq']) && $_SESSION['cq'] == true))
            $typeid = 2;
        else if ((isset($_SESSION['gx']) && $_SESSION['gx'] == true))
            $typeid = 3;
        else if ((isset($_SESSION['nc']) && $_SESSION['nc'] == true))
            $typeid = 5;
        else if ((isset($_SESSION['pk']) && $_SESSION['pk'] == true))
            $typeid = 6;
        else if ((isset($_SESSION['lhc']) && $_SESSION['lhc'] == true))
            $typeid = 7;
        else if ((isset($_SESSION['xj']) && $_SESSION['xj'] == true))
            $typeid = 8;
        else if ((isset($_SESSION['jsk3']) && $_SESSION['jsk3'] == true))
            $typeid = 9;
        else
            $typeid = 1;
    }
    if (!isset($_GET['date'])) {
        $date = date('Y-m-d');
    } else {
        $date = $_GET['date'];
    }

    $numberList = numberList($typeid, $date);
//$page = $numberList['page'];
    $lang = new utf8_lang();
}

//广东快乐十分与幸运农场
function klc_transfer($db_result,$type)
{
    $ret_array = array();
    for ($i=0;$i<count($db_result);$i++) {
        $ret_array[] = klc_transfer_each($db_result[$i], $type);
    }
    return $ret_array;
}

function ssc_transfer($db_result)
{
    $ret_array = array();
    for ($i=0;$i<count($db_result);$i++) {
        $ret_array[] = ssc_transfer_each($db_result[$i]);
    }
    return $ret_array;
}
function ssc_transfer_each($number_array)
{
    $g_id = $number_array['g_id'];
    $qishu = $number_array['g_qishu'];
    $date = $number_array['g_date'];
    $balls_array = array();
    $number_array = array_slice($number_array,3);
    foreach ($number_array as $value) {
        $balls_array[] = $value;
    }

    return array('qishu'=>$qishu,'date'=>$date, 'balls'=>join(',', $balls_array));
}

function klc_transfer_each($number_array,$type)
{
    $balls_array = array();
    $result_array = array();
    $db = new DB();
    $table_name = $type==1? 'g_history':'g_history5';

    $g_id = $number_array['g_id'];
    $qishu = $number_array['g_qishu'];
    $number_array = array_slice($number_array,2);
    foreach ($number_array as $value) {
        $balls_array[] = $value;
    }

    for ($i=1; $i<=20;$i++) {
        $tmp = -1;
        if (!in_array($i,$balls_array)) {
            $sql_str =
                "select
                `g_id` from $table_name  where
                (`g_ball_1`=$i or
                `g_ball_2`=$i or
                `g_ball_3`=$i or
                `g_ball_4`=$i or
                `g_ball_5`=$i or
                `g_ball_6`=$i or
                `g_ball_7`=$i or
                 `g_ball_8`=$i) and g_id<$g_id  order by g_id desc LIMIT 1";
            $sql_ret = $db->query($sql_str, 1);
            $tmp = $g_id -$sql_ret[0]['g_id'];
        }
        $result_array[] = $tmp;
    }


    return array('qishu'=>$qishu, 'balls'=>join(',', $balls_array), 'result_array'=>$result_array);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?= $oncontextmenu ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/artDialog.js?skin=twitter"></script>
    <script type="text/javascript" src="./js/sc.js"></script>
    <script type="text/javascript" src="./js/fen_pei_biao.js"></script>
    <script type="text/javascript" src="/Manage/temp/js/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        <!--
        function selects($this) {
            location.href = "result.php?id=" + $this.value;
        }
        //-->
        $(document).ready(function () {
            var win_height = window.innerHeight;
            $("#layout").css('height', win_height + 'px');
        });
    </script>
    <link rel="stylesheet" href="/wjl_tmp/steal_front.css"/>
</head>
<body class="<?php echo $_COOKIE['g_skin']; ?>">
<div class="mains_corll wjl_container" id="layout">
<div id="rightLoader" dom="right" style="">
<?php
switch ($typeid) {
    case 1:
        echo
        ' <div id="result_klc" class="page struct_table_center result klc" tmp="result_klc"> ';
        break;
    case 2:
        echo
        ' <div id="result_ssc" class="page struct_table_center result klc" tmp="result_ssc"> ';
        break;
    case 6:
        echo
        ' <div id="result_pk10" class="page struct_table_center result pk10" tmp="result_pk10"> ';
        break;
    case 5:
        echo
        ' <div id="result_nc" class="page struct_table_center result nc" tmp="result_nc"> ';
        break;
    case 9:
        echo
        ' <div id="result_ks" class="page struct_table_center result ks" tmp="result_ks"> ';
        break;
}?>
<div style="height:4px;visibility:hidden;font-size:0"></div>
<script type="text/javascript">
    function Result_Load(typeid) {
        window.location = "Result.php?id=" + typeid;
    }
</script>
<div class="title"><span class="sub_title_color">开奖日期：</span>
    <input type="text" value="<?php echo $date ?>" id="dateName" onfocus="WdatePicker({
        el:'dateName',
        onpicked:function(){window.location = 'result.php?id=<?php echo $typeid ?>&date='+this.value}
        });">&nbsp;&nbsp;
    <select id="rusult_md_cs" onchange="Result_Load(this.value)">
        <option value="1" <?php if ($typeid == 1) echo 'selected="selected"' ?> >广东快乐十分</option>
        <option value="2" <?php if ($typeid == 2) echo 'selected="selected"' ?>>重庆时时彩</option>
        <option value="6" <?php if ($typeid == 6) echo 'selected="selected"' ?>>北京赛车(PK10)</option>
        <option value="5" <?php if ($typeid == 5) echo 'selected="selected"' ?>>幸运农场</option>
        <option value="9" <?php if ($typeid == 9) echo 'selected="selected"' ?>>江苏骰宝</option>
    </select>&nbsp;&nbsp;<a href="javascript:void(0);" class="btn_m elem_btn" id="ball_btn">球号分配表</a></div>
<table class="dataArea t1">
    <thead>
    <?php
    $noresult_html = '';
    switch ($typeid) {
        case 1:
            echo
            '<tr>
                <th>期数</th>
                <th>开奖时间</th>
                <th colspan="8">开奖号码</th>
                <th colspan="4">总和</th>
                <th colspan="4">1~4龙虎</th>
            </tr>';
            $noresult_html = '<td colspan="18">暂无数据!</td>';
            break;
        case 2:
            echo
            '<tr class=""><th>期数</th><th>开奖时间</th><th colspan="5">开奖号码</th><th colspan="3">总和</th><th>龙虎</th><th>前三</th><th>中三</th><th>后三</th></tr>';
            $noresult_html = '<td colspan="14">暂无数据！</td>';
            break;
        case 6:
            echo
            '<tr class=""><th>期数</th><th>开奖时间</th><th colspan="10">开奖号码</th><th colspan="3">冠亚和</th><th>冠军</th><th>亚军</th><th>第三名</th><th>第四名</th><th>第五名</th></tr>';
            $noresult_html = '<td colspan="20">暂无数据！</td>';
            break;
        case 5:
            echo
            '<tr class=""><th>期数</th><th>开奖时间</th><th colspan="8">开奖号码</th><th colspan="4">总和</th><th colspan="4">1~4龙虎</th></tr>';
            $noresult_html = '<td colspan="18">暂无数据！</td>';
            break;
        case 9:
            echo
            '<tr class=""><th>期数</th><th>开奖时间</th><th colspan="3">开出骰子</th><th colspan="2">总和</th></tr>';
            $noresult_html = '<td colspan="15">暂无数据！</td>';
            break;
    }?>
    </thead>
    <tbody id="result_tb">
    <?php
    if (!$numberList) {
        echo $noresult_html;
    } else if ($typeid == 1) {
        for ($i = 0; $i < count($numberList); $i++) {
            $ball_array = explode(',',$numberList[$i][3]);
            ?>
            <tr>
                <td><?php echo $numberList[$i][1] ?></td>
                <td><?php echo $numberList[$i][2] ?></td>
                <!--    <td colspan="8" id="2013111984"><span class="number num7"></span><span
                            class="number num4"></span><span class="number num8"></span><span
                            class="number num1"></span><span class="number num3"></span><span
                            class="number num20"></span><span class="number num11"></span><span
                            class="number num12"></span></td>-->
                <td colspan="8" id="<?php echo $numberList[$i][1] ?>"><?php echo $numberList[$i][3] ?></td>
    <!--                <td class="bold"><?php /*echo array_sum($ball_array) */?></td>-->
                <td class="bold"><?php echo $numberList[$i][4]?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][5]) ?></td>
                <td class="bold"><span class="reder "><?php echo $lang->hk_cn($numberList[$i][6]) ?></span></td>
                <td class="bold"><span class="reder "><?php echo $lang->hk_cn($numberList[$i][7]) ?> </span></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][8]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][9]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][10]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][11]) ?></td>
            </tr>
        <?php
        } //for end
    } else if ($typeid == 2) {
        for ($i = 0; $i < count($numberList); $i++) {
            ?>
            <tr class="">
                <td><?php echo $numberList[$i][1] ?></td>
                <td><?php echo $numberList[$i][2] ?></td>
                <td colspan="5" id="<?php echo $numberList[$i][1] ?>">
                    <?php echo $numberList[$i][3] ?>
                </td>
                <td class="bold"><?php echo $numberList[$i][4] ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][5]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][6]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][7]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][8]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][9]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][10]) ?></td>
            </tr>
        <?php
        }
    } else if ($typeid == 6) {
        for ($i = 0; $i < count($numberList); $i++) {
            ?>
            <tr class="">
                <!--                类似395481这样的期数需要特殊处理啊,在用期数取数据库的时候-->
                <td><?php echo $numberList[$i][1] ?></td>
                <td><?php echo $numberList[$i][2] ?></td>
                <td colspan="10" id="<?php echo $numberList[$i][1] ?>">
                    <?php echo $numberList[$i][3] ?>
                </td>
                <td class="bold"><?php echo $numberList[$i][4] ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][5]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][6]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][7]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][8]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][9]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][10]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][11]) ?></td>
            </tr>
        <?php
        }
    } else if ($typeid == 5) {
        for ($i = 0; $i < count($numberList); $i++) {
            ?>
            <tr class="">
                <td><?php echo $numberList[$i][1] ?></td>
                <td><?php echo $numberList[$i][2] ?></td>
                <td colspan="8" id="<?php echo $numberList[$i][1] ?>">
                    <?php echo $numberList[$i][3] ?>
                </td>
                <td class="bold"><?php echo $numberList[$i][4] ?></td>
                <td class="bold"><span class="reder "><?php echo $lang->hk_cn($numberList[$i][5]) ?></span></td>
                <td class="bold"><span class="reder "><?php echo $lang->hk_cn($numberList[$i][6]) ?></span></td>
                <td class="bold"><span class="reder "><?php echo $lang->hk_cn($numberList[$i][7]) ?> </span></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][8]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][9]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][10]) ?></td>
                <td class="bold"><?php echo $lang->hk_cn($numberList[$i][11]) ?></td>
            </tr>
        <?php
        }
    } else if ($typeid == 9) {
        for ($i = 0; $i < count($numberList); $i++) {
            ?>
            <tr class="">
                <td><?php echo $numberList[$i][1] ?></td>
                <td><?php echo $numberList[$i][2] ?></td>
                <td colspan="3" id="<?php echo $numberList[$i][1] ?>">
                    <?php echo $numberList[$i][3] ?>
                </td>
                <td class="bold"><?php echo $numberList[$i][4] ?></td>
                <td class="bold"><span class="reder "><?php echo $numberList[$i][5] ?></span></td>
            </tr>
        <?php
        }
    }
    ?>
    </tbody>
</table>
<!--底部弹出菜单开始-->
<div class="pop-border" id="popup_form" style="display: none">
    <div dom="head" class="pop-hd">
        <h4 dom="title">球号分配表</h4>
        <a href="javascript:void(0)" dom="headico" class="headico"></a>
        <a href="javascript:void(0)" dom="close" class="close"></a>
        <a href="javascript:void(0)" dom="toggleSize" class="maxsize" style="display: none;"></a></div>
    <div class="pop-bd">
        <div dom="container" class="pop-container" style="width: 1002px;">
            <div id="ball_loader" class="pop" ptitle="球号分配表" normal-width="1002" style="display: block;">
                <div id="ball_klc">
                    <div class="requestData ball" id="requestData">
                        <div class="title">
                            开始日期
                            <input type="text" id="start_date_klc"
                                   style="background-color: white; border: 1px solid rgb(187, 188, 192);
                                   background-position: initial initial;
                                    background-repeat: initial initial;"
                                   onfocus="WdatePicker({
                                       el:'start_date_klc'
                                       });"
                                >&nbsp;&nbsp;至&nbsp;
                            结束日期
                            <input type="text" id="end_date_klc"
                                   style="background-color: white; border: 1px solid rgb(187, 188, 192); background-position: initial initial; background-repeat: initial initial;"
                                   onfocus="WdatePicker({
                                       el:'end_date_klc'
                                       });"
                                >&nbsp;&nbsp;
                            <a href="javascript:void(0)" class="btn_m elem_btn" id="s_ball_klc">查询</a>
                        </div>
                        <div id="pk_toggle" style="display: none"><span class="pk-ball ball-on" id="min">1~5</span><span class="pk-ball" id="max">6~10</span></div>
                        <ul class="ball-title">

                        </ul>
                        <div class="ball-list" id="ball_list_klc">
                            <ul>
                                <li class="ball-1">13122173</li>
                                <li class="ball-2">06,04,<i class="red">20</i>,02,17,11,05,16</li>
                                <li>2</li>
                                <li><span class="number num2"></span></li>
                                <li>1</li>
                                <li><span class="number num4"></span></li>
                                <li><span class="number num5"></span></li>
                                <li><span class="number num6"></span></li>
                                <li>9</li>
                                <li>1</li>
                                <li>4</li>
                                <li>1</li>
                                <li><span class="number num11"></span></li>
                                <li>8</li>
                                <li>3</li>
                                <li>1</li>
                                <li>2</li>
                                <li><span class="number num16"></span></li>
                                <li><span class="number num17"></span></li>
                                <li>2</li>
                                <li>2</li>
                                <li><span class="number num20"></span></li>
                            </ul>
                        </div>
                        <div class="get-more"><a id="get_more_klc" href="javascript:void(0)">查看更多</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pop-ft"></div>
</div>
<script type="text/javascript">
</script>
<!--底部弹出菜单结束-->
</div>
</div>
</div>
</body>
</html>