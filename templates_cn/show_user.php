<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 13-12-26
 * Time: 上午12:08
 */

$lang = new utf8_lang();

//获取最新投注的10条记录
$db=new DB();
$type = $gtypes;

//处理回显文字
//此处同left.php当中的文字处理
for ($i=0;$i<count($ListArr);$i++) {
    $subtype = $ListArr[$i]['g_mingxi_1'];
    $used_money += $ListArr[$i]['g_jiner'];
    if ($subtype== '选二连直') {
        /*        $ball_array = explode('|',$ListArr[$i]['g_mingxi_2']);
                $ball_array[0] = explode('、',$ball_array[0]);
                $ball_array[0] = join(',',$ball_array[0]);
                $ball_array[1] = explode('、',$ball_array[1]);
                $ball_array[1] = join(',',$ball_array[1]);

                $ball_array[0] = '前位 ' .$ball_array[0];
                $ball_array[1] = '后位 ' . $ball_array[1];
                $ListArr[$i]['g_mingxi_2'] = '选二连直 '.$lang->hk_cn($ball_array[0] ." ". $ball_array[1]);*/
    } else if ($subtype == '三军' || $subtype == '长牌' ||$subtype == '围骰') {
        //江苏sb特殊处理
        if ($ListArr[$i]['g_mingxi_2'] != '大'
            && $ListArr[$i]['g_mingxi_2'] != '小'
            && $ListArr[$i]['g_mingxi_2'] != '全骰' ) {

            $tmp = explode('+',$ListArr[$i]['g_mingxi_2']);
            $ListArr[$i]['g_mingxi_2'] = join('',$tmp);
            $ListArr[$i]['g_mingxi_2'] = $subtype . ' ('.$ListArr[$i]['g_mingxi_2'].')';
        }
    } else if ($subtype == '點數') {
        $ListArr[$i]['g_mingxi_2'] = '点' . ' ' . $ListArr[$i]['g_mingxi_2'];
    } else {
        $tmp = explode('、',$ListArr[$i]['g_mingxi_2']);
        $tmp = join(',',$tmp);
        if ($subtype == '總和、家禽野兽') {
            $subtype = '总和';
            $tmp = substr($tmp,6);
        } else if ($subtype == '冠、亞軍和') {
            $subtype = '冠亚和';
        } else if ($subtype == '冠亞和') {
            $subtype = '冠亚';
            $tmp = substr($tmp,9);
        } else if ($subtype == '總和、龍虎和') {
            /*            $preg = '總和.';*/
            $subtype = '';
            if ($tmp == '總和大'
                ||$tmp == '總和小'
                ||$tmp == '總和雙'
                ||$tmp == '總和單'
            ) {
                $subtype = '总和';
                $tmp = substr($tmp,6);
            }
        } else if ($subtype == '總和、龍虎') {
            $subtype = '总和';
            $tmp = substr($tmp,6);
        }
        $ListArr[$i]['g_mingxi_2'] = $subtype . ' '. $tmp;

    }
}

//$sql = "SELECT * FROM g_zhudan where g_nid='$name' and g_win=null and g_type='$type' ORDER BY g_id DESC LIMIT 10";
$sql = "SELECT g_mingxi_1,g_mingxi_2,g_date,g_odds,g_jiner FROM g_zhudan where g_nid='$name' and g_type='$type' and g_win is null ORDER BY g_id DESC LIMIT 10";
$result1 = $db->query($sql, 1);
$used_money = 0;
for ($i=0;$i<count($result1);$i++) {
    $used_money += $result1[$i]['g_jiner'];
}
//获取游戏的开放情况
$configModel = configModel("g_kg_game_lock,g_cq_game_lock,g_gx_game_lock,g_pk_game_lock,g_nc_game_lock,g_lhc_game_lock,g_xj_game_lock,g_jsk3_game_lock");
?>
<table border="0" cellpadding="0" cellspacing="0" class="t_list">
    <tr>
        <td class="t_list_caption redbg" colspan="2">账户信息</td>
    </tr>
    <tr>
        <td class="t_td_caption_1" width="71">账号：</td>
        <td class="t_td_text" width="137"><?php echo $user[0]['g_name'] ?>(<label
                id="pls"><?php echo strtoupper($user[0]['g_panlu']) ?></label>盘)
        </td>
    </tr>
    <tr>
        <td class="t_td_caption_1">信用额度：</td>
        <td class="t_td_text"><?php echo is_Number($user[0]['g_money']) ?></td>
    </tr>
    <tr>
        <td class="t_td_caption_1">信用余额：</td>
        <td id="jine" class="t_td_text" style="font-weight:bold;"><?php echo is_Number($user[0]['g_money_yes']) ?></td>
    </tr>
    <tr>
        <td class="t_td_caption_1">已下金额：</td>
        <td class="t_td_text"><?php echo $used_money?></td>
    </tr>


    <!--新旧版跳转临时按钮-->
    <tr>
        <td class="t_list_caption left_version" colspan="2"><a href="/index.php?version=hk" target="_parent">新版</a></td>
    </tr>
    <!--临时按钮end-->
    <tr class="hide-successinfo t1" style="display: table-row;">
        <td style="text-align:center;text-indent:0;" colspan="2"><a class="btn_m elem_btn" href="../left.php"
                                                                    id="sideLeftBack">返回</a></td>
    </tr>
    <tr id="left_times_title" class="t1" style="display: table-row;">
        <th colspan="2"><h3 class="red-title center"><?php echo $number_1 ?>&nbsp;期</h3></th>
    </tr>
</table>
<div id="successinfo" class="success-info" style="display: block;">
    <ul>
        <li class="failure" style="display: none;">
            <table>
                <tbody>
                <tr>
                    <td id="f-list">
                        <dl>
                            <dt><span class="bluer">第一球 8</span> @ <span class="red">9.8</span></dt>
                            <dd class="red"></dd>
                        </dl>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="center mrg20"><a href="javascript:void(0)" class="btn_m elem_btn l-c-b del-failure-info"
                                       id="sideLeftCancel">取消</a></p></li>
        <li class="failure-odd-change" style="display: none;">
            <dl>
                <dt><span class="bluer">第一球 8</span> @ <span class="red">9.8</span></dt>
                <dd class="red"></dd>
            </dl>
            <p class="center mrg20"><a href="javascript:void(0)" class="btn_m elem_btn l-c-b" style="color: white;">
                    取消</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn_m elem_btn l-c-b-t2 ft000"
                                         style="color: white;">确定下注</a></p></li>
        <li class="success" style="">
            <table class="t1 bg_white dataArea">
                <thead style="visibility:hidden;">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </thead>
                <tbody id="s-list">
                <?php if ($action == 'fn2') {
                    if ($stringList['type'] == '选二连直') {
                        $ball_array = explode('|', $s_ball);
                        $s_ball = "前位 " . $ball_array[0] . '</br>后位 ' . $ball_array[1];
                    }
                    ?>
                    <tr>
                        <td colspan="3"><p>注单号：<span class="greener"><?php  echo $ListArr[0]['id']?></span></p>

                            <p class="text-i-em3"><span class="bluer"><?php echo $lang->hk_cn($stringList['type'])?></span>&nbsp; @ &nbsp;<b class="red"><?php echo $odds ?></b></p>

                            <p class="text-i-em3"><span class="black">复式[<?php echo $results[0] ?>组]</span></p>

                            <p class="text-i-em3" style="text-indent:0"><span class="black"><?php echo $s_ball ?></span></p>

                            <p>分组：<span class="black" style="padding-left:1em">
                                    <?php echo $s_money?>x<?php echo $results[0] ?>组</span>
                            </p>


                            <p>下注额：<span class="black"><?php echo $countZhuEr?></span></p>
                            <p>可赢额：<span class="black"><?php echo $ListArr[0]['KeYing']?></span></p></td>
                    </tr>
                    <tr>
                        <th class="db-bg">ID</th>
                        <th class="db-bg">号码组合</th>
                        <th class="db-bg">下注额</th>
                    </tr>
                    <?php
                    for ($i=0; $i<count($results[1]); $i++) {
                        $s = $i+1;
                        ?>
                        <tr>
                            <td><?php  echo $s?></td>
                            <td><?php echo $results[1][$i]?></td>
                            <td><?php echo $s_money?></td>
                        </tr>
                    <?php } ?>
                <?php
                } else if ($action == 'fn' || $action == 'fn1' || $action == 'fn3') { //單筆循環投注單
                    for ($i=0; $i<count($ListArr); $i++) {
                        //$nn = $ListArr[$i]['g_mingxi_1'] == '總和、龍虎' ? $ListArr[$i]['g_mingxi_2'] : $ListArr[$i]['g_mingxi_1'].' '.$ListArr[$i]['g_mingxi_2'].'';
                        $nn = $ListArr[$i]['g_mingxi_2'];
                        ?>
                        <tr>
                            <td colspan="3"><p>注单号：<span class="greener"><?php echo  $ListArr[$i]['id']?></span></p>

                                <p class="text-i-em3"><span class="bluer"><?php echo $lang->hk_cn($nn)?></span>&nbsp; @ &nbsp;<b
                                        class="red"><?php echo $ListArr[$i]['g_odds'] ?></b></p>

                                <p>下注额：<span class="black"><?php echo $ListArr[$i]['g_jiner'] ?></span></p>

                                <p>可赢额：<span class="black"><?php echo $ListArr[$i]['KeYing'] ?></span></p></td>
                        </tr>
                    <?php
                    }
                }?>
                </tbody>
                <tfoot>
                <tr>
                    <td class="inner_text td_h" colspan="2" style="width:75px">下注笔数</td>
                    <td class="db-bg" style="width:147px"><span class="black suc_zhus"><?php echo $countBiShu?>笔</span></td>
                </tr>
                <tr>
                    <td class="inner_text td_h" colspan="2" style="width:75px">合计注额</td>
                    <td class="db-bg"><b class="reder suc_t_amount"><?php echo $countZhuEr?></b></td>
                </tr>
                </tfoot>
            </table>
        </li>
    </ul>
</div>

