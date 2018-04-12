<?php
/**
 * Created by PhpStorm.
 * User: CodeYi
 * Date: 2018/3/20
 * Time: 19:38
 */

namespace app\studentwx\controller;

use app\common\MobileBase;
use think\Db;

/**
 * 问卷调查控制器--小程序
 * Class Questionnaire
 * @package app\studentwx\controller
 */
class Questionnaire extends MobileBase
{
    /**
     * 填写问卷调查
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function answerQuestionnaire()
    {
        $data = $this->request->param();
        $anwserInfo = json_decode(htmlspecialchars_decode($data['data']), true);
        $id = $data['id'];
        //判断是否已经填过该问卷
        $info = Db::name('questionnaire_answers')->where(['questionnaire_id' => $id, 'number' => self::$number])->find();
        if ($info) return error_data('您已经完成该问卷');
        Db::startTrans();
        try {
            foreach ($anwserInfo as $k => $v) {
                if ($v['type'] == 1 || $v['type'] == 3) {
                    $content = isset($v['content'][0]) ? $v['content'][0] : "";
                    $insert[] = [
                        'questionnaire_id' => $id,
                        'topic_id' => $v['id'],
                        'content' => $content,
                        'type' => $v['type'],
                        'label' => ""
                    ];
                }
                if ($v['type'] == 2) {
                    $content = implode(',', $v['content']);
                    $insert[] = [
                        'questionnaire_id' => $id,
                        'topic_id' => $v['id'],
                        'content' => $content,
                        'type' => $v['type'],
                        'label' => ""
                    ];
                }
                if ($v['type'] == 4) {
                    $label = explode(';', $v['label']);
                    foreach ($label as $key => $value) {
                        $content = $v['content'];
                        $insert[] = [
                            'questionnaire_id' => $id,
                            'topic_id' => $v['id'],
                            'content' => $content,
                            'type' => $v['type'],
                            'label' => $value,
                        ];
                    }
                }
                Db::name('questionnaire_reply')->insertAll($insert);
            }
            Db::name('questionnaire_answers')->insert(['questionnaire_id' => $id, 'number' => self::$number]);
            Db::commit();
            return json(['error' => 0, 'msg' => '提交成功']);
        } catch (\Exception $e) {
            Db::rollback();
            return json(['error' => 1, 'msg' => '提交失败']);
        }
    }

    /**
     * 问卷列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function questionnaire()
    {
        $page = input('page');
        $page = isset($page) ?: 1;
        //找出用户已经回答的问卷
        $ids = Db::name('questionnaire_answers')->where(['number' => self::$number])->column('questionnaire_id');
        $questionnaire = Db::name('questionnaire')
            ->alias('a')
            ->field('a.*,b.name')
            ->join('teacher b', 'a.number=b.number', 'LEFT')
            ->where(['a.id' => ['NOT IN', $ids]])
            ->page($page, self::$page)
            ->order('sub_time desc')
            ->select();
        foreach ($questionnaire as &$value)
            $value['sub_time'] = formatTime($value['sub_time']);
        return json($questionnaire);
    }

    /**
     * 问卷详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailQuestionnaire()
    {
        $id = input('id');
        $questionnaire = Db::name('questionnaire')
            ->alias('a')
            ->field('a.*,b.name')
            ->join('teacher b', 'b.number=a.number', 'LEFT')
            ->where(['a.id' => $id])
            ->find();
        //获取问卷所有的题目和选项
        $topicInfo = Db::name('questionnaire_questions')->where(['questionnaire_id' => $id])->select();
        foreach ($topicInfo as $k => $v) {
            if ($v['type'] == 1) {
                $topicInfo[$k]['type'] = "单选题";
            }
            if ($v['type'] == 2) {
                $topicInfo[$k]['type'] = "多选题";
            }
            if ($v['type'] == 3) {
                $topicInfo[$k]['type'] = "问答题";
            }
            if ($v['type'] == 4) {
                $topicInfo[$k]['type'] = "矩阵题";
                $topicInfo[$k]['label'] = str_replace(',', "\n", $v['label']);
            }
            if ($v['type'] != 3) {
                $topicInfo[$k]['option'] = Db::name('questionnaire_option')->where(['topic_id' => $v['id']])->select();
            }
        }
        $res = [
            'info' => $questionnaire,
            'data' => $topicInfo
        ];
        return json($res);
    }

    /**
     *投票列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listVote()
    {
        $search = input('search');
        $page = input('page');
        $page = isset($page) ? $page : 1;
        isset($search) ? $where['a.title'] = ['LIKE', "%$search%"] : "";
        $where['a.endtime'] = ['GT', formatTime(time())];
        $data = Db::name('vote')
            ->alias('a')
            ->field('a.*,b.name')
            ->join('xgjw_teacher b', 'a.number=b.number', 'LEFT')
            ->where($where)
            ->order('time desc')
            ->page($page, self::$page)
            ->select();
        return json($data);
    }

    /**
     * 投票详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailVote()
    {
        $pid = input('id');
        //判断是否已经提交
        $voteId = Db::name("vote_result")->where(['vote_id'=>$pid])->value("vote_id");
        $info = Db::name('vote_options')->where(['pid' => $pid])->select();
        if($voteId){
            foreach ($info as $k => $v) {
                $info[$k]['number'] = Db::name('vote_result')->where(['pid' => $v['id']])->count();
            }
        }
        return json($info);
    }

    /**
     * 提交投票
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addVote()
    {
        $ids = input('id/a');
        $pid = input('pid');//投票id
        //判断是否重复投票
        $repeat = Db::name('vote_result')->where(['vote_id'=>$pid,'number'=>self::$number])->value('id');
        if($repeat) return error_data("请不要重复投票");
        $max = Db::name('vote')->where(['id' => $pid])->value('maximum');
        if (count($ids) > $pid) return error_data("最多只能选择" . $max . "项");
        foreach ($ids as $k => $v) {
            $insert[] = [
                'pid' => $v,
                'vote_id' => $pid,
                'number' => self::$number
            ];

        }
        $res = Db::name('vote_result')->insertAll($insert);
        if ($res) return ok_data();
        return error_data();
    }
}