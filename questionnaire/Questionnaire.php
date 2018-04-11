<?php

namespace app\teacherpc\controller;

use app\common\PcBase;
use think\Db;
use think\Request;

/**
 * 问卷调查控制器
 * Class Questionnaire
 * @package app\teacherpc\controller
 */
class Questionnaire extends PcBase
{
    /**
     * 添加调查问卷
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function addQuestionnaire()
    {
        $data = input();
        if (isset($data['list']) && isset($data['title'])) {
            Db::startTrans();
            try {
                $questionnaire_id = Db::name('questionnaire')->insertGetId(['title' => $data['title'], 'sub_time' => formatTime(time()),
                    'number' => '010001', 'describe' => $data['describe']]);
                $option = [];
                foreach ($data['list'] as $key => $value) {
                    $questions['questionnaire_id'] = $questionnaire_id;
                    $questions['title'] = $value['title'];
                    $questions['type'] = $value['type'];
                    if ($value['type'] == 4) $questions['label'] = deal_string(';', $value['label']);
                    $questions_id = Db::name('questionnaire_questions')->insertGetId($questions);
                    if ($value['type'] != 3) {
                        foreach ($value['option'] as $k => $v) {
                            $one['topic_id'] = $questions_id;
                            $one['option'] = $v;
                            $option[] = $one;
                        }
                    }
                }
                Db::name('questionnaire_option')->insertAll($option);
                Db::commit();
                return ok_data('发布成功');
            } catch (\Exception $exception) {
                Db::rollback();
                return error_data();
            }
        } else {
            throw new \think\Exception('提交参数格式错误');
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
        $list = Db::name('questionnaire')->page($page, self::$listRow)->count();
        $questionnaire = Db::name('questionnaire')
            ->alias('a')
            ->field('a.*,b.name')
            ->join('teacher b', 'a.number=b.number', 'LEFT')
            ->page($page, self::$listRow)
            ->order('sub_time desc')
            ->select();
        foreach ($questionnaire as &$value)
            $value['sub_time'] = formatTime($value['sub_time']);
        $res = [
            'list' => $list,
            'data' => $questionnaire
        ];
        return json($res);
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
        $page = input('page');
        $page = isset($page) ?: 1;
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
                $count = Db::name('questionnaire_reply')
                    ->where(['topic_id' => $v['id']])
                    ->count();
                $topicInfo[$k]['result'] = Db::name('questionnaire_reply')
                    ->alias('a')
                    ->field('b.option,count(content) content')
                    ->join('questionnaire_option b', 'a.content = b.id', 'LEFT')
                    ->where(['a.topic_id' => $v['id']])
                    ->group('content')
                    ->select();
                foreach ($topicInfo[$k]['result'] as $key => $value) {
                    $present = $value['content'] / $count;
                    $topicInfo[$k]['result'][$key]['present'] = sprintf("%.2f", ($present * 100)) . "%";
                }
            }
            if ($v['type'] == 2) {
                $topicInfo[$k]['type'] = "多选题";
                $result = Db::name('questionnaire_reply')
                    ->where(['topic_id' => $v['id']])
                    ->column('content');
                //获取问题的所有选项
                $optionInfo = Db::name('questionnaire_option')->where(['topic_id' => $v['id']])->select();
                foreach ($result as $key => $value) {
                    $option = explode(',', $value);
                    if (!empty($value) && count($option) > 1) {
                        $content = array_values($option);
                    } else {
                        $content = $value;
                    }
                }
                //总次数
                $total = count($content);
                //获取每个选项出现的次数
                foreach ($optionInfo as $k1 => $v1) {
                    if (isset(array_count_values($content)[$v1['id']])) {
                        $insert['content'] = array_count_values($content)[$v1['id']];
                    } else {
                        $insert['content'] = 0;
                    }
                    $insert['option'] = $v1['option'];
                    $insert['present'] = sprintf("%.2f", ($insert['content'] / $total * 100)) . "%";
                    $topicInfo[$k]['result'][] = $insert;
                }
                if (empty($result)) $topicInfo[$k]['result'] = [];
            }
            if ($v['type'] == 3) {
                $topicInfo[$k]['type'] = "问答题";
                //获取所有的回复内容
                $topicInfo[$k]['list'] = Db::name('questionnaire_reply')
                    ->field('id,content')
                    ->where(['topic_id' => $v['id']])
                    ->count();
                $topicInfo[$k]['result'] = Db::name('questionnaire_reply')
                    ->field('id,content')
                    ->where(['topic_id' => $v['id']])
                    ->page($page, self::$listRow)
                    ->select();
            }
            if ($v['type'] == 4) {
                $topicInfo[$k]['type'] = "矩阵题";
                $topicInfo[$k]['label'] = str_replace(',', ";\n", $v['label']);
                $result = Db::name('questionnaire_reply')
                    ->field('label,GROUP_CONCAT(content) content')
                    ->where(['topic_id' => $v['id']])
                    ->group('label')
                    ->select();
                //获取问题的所有选项
                $options = Db::name('questionnaire_option')->where(['topic_id' => $v['id']])->select();
                if ($result) {
                    //矩阵统计
                    foreach ($options as $key=>$value){
                        $all = [];
                        foreach ($result as $k1=>$v1){
                            $option = [];
                            $content = explode(',', $v1['content']);
                            if(is_array($content)){
                                $option = array_merge(explode(',', $v1['content']),$option);
                            }else{
                                $option[] = $v1['content'];
                            }
                            $all[] = [
                                'label'=>$v1['label'],
                                'content'=>isset(array_count_values($option)[$value['id']])?array_count_values($option)[$value['id']]:0
                            ];
                        }
                        $topicInfo[$k]['result'][] = [
                            'id'=>$value['id'],
                            'option'=>$value['option'],
                            'result'=>$all
                        ];
                    }
                } else {
                    $topicInfo[$k]['result'][] = [];
                }
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
     * 更新问卷
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function editQuestionnaire()
    {
        $data = \request()->param();
        if (isset($data['list']) && isset($data['title']) && isset($data['id'])) {
            Db::startTrans();
            try {
                Db::name('questionnaire')->update(['id' => $data['id'], 'title' => $data['title'], 'describe' => $data['describe']]);
                $option = [];
                $insert = [];
                foreach ($data['list'] as $key => $value) {
                    if (isset($value['id'])) {
                        $questions['id'] = $value['id'];
                        $questions['title'] = $value['title'];
                        $questions['type'] = $value['type'];
                        if ($value['type'] == 4) $questions['label'] = deal_string(',', $value['label']);
                    } else {
                        $newQuestions['questionnaire_id'] = $data['id'];
                        $newQuestions['title'] = $value['title'];
                        $newQuestions['type'] = $value['type'];
                        if ($value['type'] == 4) $newQuestions['label'] = deal_string(',', $value['label']);
                    }
                    if (isset($questions)) Db::name('questionnaire_questions')->update($questions);
                    if ($newQuestions) $insertId = Db::name('questionnaire_questions')->insertGetId($newQuestions);
                    if ($value['type'] != 3) {
                        foreach ($value['option'] as $k => $v) {
                            if (isset($v['id'])) {
                                $one['id'] = $v['id'];
                                $one['option'] = $v;
                                $option[] = $one;
                            } else {
                                $two['option'] = $v;
                                $two['topic_id'] = $insertId;
                                $insert[] = $two;
                            }
                        }
                    }
                }
                if (!empty($option)) Db::name('questionnaire_option')->update($option);
                if (!empty($insert)) Db::name('questionnaire_option')->insertAll($insert);
                Db::commit();
                return ok_data('更新成功');
            } catch (\Exception $exception) {
                Db::rollback();
                return error_data();
            }
        } else {
            throw new \think\Exception('提交参数格式错误');
        }
    }

    /**
     * 删除问卷调查
     * @return \think\response\Json
     */
    public function deleteQuestionnaire()
    {
        $id = input('id');
        Db::startTrans();
        try {
            $topicIds = Db::name('questionnaire_questions')->where(['questionnaire_id' => ['IN', $id]])->column('id');
            Db::name('questionnaire_option')->where(['topic_id' => ['IN', $topicIds]])->delete();
            Db::name('questionnaire_questions')->where(['questionnaire_id' => ['IN', $id]])->delete();
            Db::name('questionnaire_answers')->where(['questionnaire_id' => ['IN', $id]])->delete();
            Db::name('questionnaire_reply')->where(['questionnaire_id' => ['IN', $id]])->delete();
            Db::name('questionnaire')->where(['id' => ['IN', $id]])->delete();
            Db::commit();
            return ok_data();
        } catch (\Exception $exception) {
            Db::rollback();
            return error_data();
        }
    }
}