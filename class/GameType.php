<?php
/**
 *
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-1-18
 * Time: 下午2:33
 */

include_once './User_formater.php';
include_once './ReportFactory.php';

abstract class GameType {
    public $type;
    public $user;


    function __construct($type,$user) {
        $this->type = $type;
        $this->user = $user;
    }

    public function getZhudan($type) {

    }

    public static $type_list = array(
        '[广东快乐十分]第一球'=>2,
        '[广东快乐十分]第二球'=>2,
        '[广东快乐十分]第三球'=>2,
        '[广东快乐十分]第四球'=>2,
        '[广东快乐十分]第五球'=>2,
        '[广东快乐十分]第六球'=>2,
        '[广东快乐十分]第七球'=>2,
        '[广东快乐十分]第八球'=>2,
        '[广东快乐十分]1~8 单双'=>2,
        '[广东快乐十分]1~8 大小'=>2,
        '[广东快乐十分]1~8 尾大尾小'=>2,
        '[广东快乐十分]1~8 合数单双'=>2,
        '[广东快乐十分]总和单双'=>2,
        '[广东快乐十分]总和大小'=>2,
        '[广东快乐十分]总和尾大尾小'=>2,
        '[广东快乐十分]1~8 中发白'=>2,
        '[广东快乐十分]1~8 方位'=>2,
        '[广东快乐十分]1~4 龙虎'=>2,
        '[广东快乐十分]任选二'=>1,
        '[广东快乐十分]选二连组'=>1,
        '[广东快乐十分]任选三'=>1,
        '[广东快乐十分]选三前组'=>1,
        '[广东快乐十分]任选四'=>1,
        '[广东快乐十分]任选五'=>1,
        '[广东快乐十分]正码'=>2,
        '[幸运农场]第一球'=>2,
        '[幸运农场]第二球'=>2,
        '[幸运农场]第三球'=>2,
        '[幸运农场]第四球'=>2,
        '[幸运农场]第五球'=>2,
        '[幸运农场]第六球'=>2,
        '[幸运农场]第七球'=>2,
        '[幸运农场]第八球'=>2,
        '[幸运农场]1~8 单双'=>2,
        '[幸运农场]1~8 大小'=>2,
        '[幸运农场]1~8 尾大尾小'=>2,
        '[幸运农场]1~8 合数单双'=>2,
        '[幸运农场]总和单双'=>2,
        '[幸运农场]总和大小'=>2,
        '[幸运农场]总和尾大尾小'=>2,
        '[幸运农场]1~8 中发白'=>2,
        '[幸运农场]1~8 东南西北'=>2,
        '[幸运农场]1~4 龙虎'=>2,
        '[幸运农场]任选二'=>1,
        '[幸运农场]选二连组'=>1,
        '[幸运农场]选二连直'=>1,
        '[幸运农场]任选三'=>1,
        '[幸运农场]任选四'=>1,
        '[幸运农场]任选五'=>1,
        '[幸运农场]正码'=>2,
        '[幸运农场]选三前组'=>1,
        '[北京赛车]冠军'=>2,
        '[北京赛车]亚军'=>2,
        '[北京赛车]第三名'=>2,
        '[北京赛车]第四名'=>2,
        '[北京赛车]第五名'=>2,
        '[北京赛车]第六名'=>2,
        '[北京赛车]第七名'=>2,
        '[北京赛车]第八名'=>2,
        '[北京赛车]第九名'=>2,
        '[北京赛车]第十名'=>2,
        '[北京赛车]大小'=>2,
        '[北京赛车]单双'=>2,
        '[北京赛车]龙虎'=>2,
        '[北京赛车]冠亚大小'=>2,
        '[北京赛车]冠亚单双'=>2,
        '[北京赛车]冠亚和'=>2,
        '[重庆时时彩]单码'=>2,
        '[重庆时时彩]两面'=>2,
        '[重庆时时彩]龙虎'=>2,
        '[重庆时时彩]和'=>2,
        '[重庆时时彩]豹子'=>2,
        '[重庆时时彩]顺子'=>2,
        '[重庆时时彩]对子'=>2,
        '[重庆时时彩]半顺'=>2,
        '[重庆时时彩]杂六'=>2,
        '[江苏骰宝]大小'=>2,
        '[江苏骰宝]三军'=>2,
        '[江苏骰宝]围骰'=>2,
        '[江苏骰宝]全骰'=>2,
        '[江苏骰宝]点数'=>2,
        '[江苏骰宝]长牌'=>2,
        '[江苏骰宝]短牌'=>2,

    );
}


class SingleGameType extends GameType
{

}

class MultiGameType extends GameType
{
    public $sub_type = array();

    public function get_sub_types() {
        $this->sub_type = array();
        if ($this->type == 'all') {
            foreach (GameType::$type_list as $k=>$v) {
                $this->sub_type[] = ReportFactory::CreateType($k,$this->user);
            }
        }
    }
}
