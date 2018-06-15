<?php
/**
 * @desc Created by PhpStorm.
 * @author: 蒹葭苍苍
 * @since: 2018-04-12 18:59
 */
namespace app\api\controller;
use app\api\common\Base;
use think\Db;

/**
 * 首页控制器
 * Class Index
 * @package app\api\controller
 */
class Index extends Base
{
    /**
     * 添加常用
     * @return \think\response\Json
     * @throws \Exception
     */
    public function menuAdd()
    {
        $data = input('title/a');
        if($this->isMariner) return error_data('船员无权限进行此操作');
        $insert = [];
        $result = [];
        Db::startTrans();
        try{
            Db::name('menu')->where(['user_id'=>self::$id])->delete();
            foreach ($data as $k=>$v){
                $insert['user_id'] = self::$id;
                $insert['title'] = $v;
                $result[] = $insert;
            }
            Db::name('menu')->insertAll($result);
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            return error_data();
        }
    }

    /**
     * 常用功能列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function menuList()
    {
        $info = Db::name('menu')->where(['user_id'=>self::$id])->select();
        $result = [];
        $fleet = [];
        $duty = [];
        $vessel = [];
        $extent = [];
        if(in_array("船员统计",$this->privilege) || !$this->is_cehck_rule) {
        //船员统计--船东
        $shipowner = Db::name('shipowner')->field('id,title,alias')->select();
        foreach ($shipowner as $k=>$v){
            $shipowner[$k]['number'] = Db::name('mariner')->where(['owner_pool'=>$v['id']])->count();
        }
        //排序
        $shipowner = f_order($shipowner,'number',2);
        $result = array_slice($shipowner,0,5);
        $else = array_slice($shipowner,5,-1);
        $elseCount = 0;
        if($else){
            foreach ($else as $v1){
                $elseCount += $v1['number'];
            }
            $ping = [
                'id'=>0,
                'title'=>"其它",
                'alias'=>"其它",
                'number'=>$elseCount
            ];
            //追加到结果中
            array_push($result,$ping);
        }
            //船员统计--船队
            $count = Db::name('mariner')->field('fleet,COUNT(id) number')->group('fleet')->count();
            $fleet = Db::name('mariner')->field('fleet,COUNT(id) number')->group('fleet')->order('number desc')->limit(5)->select();
            $else = Db::name('mariner')->field('fleet,COUNT(id) number')->group('fleet')->order('number desc')->limit(5, $count)->select();
            $elseCount = 0;
            if ($else) {
                foreach ($else as $k => $v) {
                    $elseCount += $v['number'];
                }
                $ping = [
                    'fleet' => '其它',
                    'number' => $elseCount
                ];
                //追加到结果中
                array_push($fleet, $ping);
            }
            //船员统计--船名统计
            $vessel = Db::name('mariner')
                ->alias('a')
                ->field('b.title,COUNT(a.id) number')
                ->join('vessel b', 'a.vessel=b.id', 'LEFT')
                ->group('vessel')
                ->order('number desc')
                ->limit(5)
                ->select();
            $list = Db::name('mariner')
                ->group('vessel')
                ->count();
            $else = Db::name('mariner')
                ->alias('a')
                ->field('b.title,COUNT(a.id) number')
                ->join('vessel b', 'a.vessel=b.id', 'LEFT')
                ->group('vessel')
                ->order('number desc')
                ->limit(5, $list)
                ->select();
            $elseCount = 0;
            if ($else) {
                foreach ($else as $k => $v) {
                    $elseCount += $v['number'];
                }
                $ping = [
                    'title' => '其它',
                    'number' => $elseCount
                ];
                //追加到结果中
                array_push($vessel, $ping);
            }

            //船名统计--职位统计
            $list = Db::name('mariner')
                ->group('duty')
                ->count();
            $duty = Db::name('mariner')
                ->field('duty,COUNT(id) number')
                ->group('duty')
                ->order('number desc')
                ->limit(5)
                ->select();
            $else = Db::name('mariner')
                ->field('duty,COUNT(id) number')
                ->group('duty')
                ->order('number desc')
                ->limit(5, $list)
                ->select();
            $elseCount = 0;
            if ($else) {
                foreach ($else as $k => $v) {
                    $elseCount += $v['number'];
                }
                $ping = [
                    'duty' => '其它',
                    'number' => $elseCount
                ];
                //追加到结果中
                array_push($duty, $ping);
            }

            //船员统计--年龄统计
            //所有船员
            $marinerId = Db::name('mariner')->column('id_number');
            $twenty0 = 0;
            $twenty1 = 0;
            $twenty2 = 0;
            $thirty1 = 0;
            $thirty2 = 0;
            $forty1 = 0;
            $forty2 = 0;
            $fifty1 = 0;
            $fifty2 = 0;
            $sixty = 0;
            foreach ($marinerId as $k => &$v) {
                $v = birthday(substr($v, 6, 4) . "-" . substr($v, 10, 2) . "-" . substr($v, 12, 12));
                if ($v <= 20) $twenty0++;
                if ($v >= 21 && $v <= 25) $twenty1++;
                if ($v >= 26 && $v <= 30) $twenty2++;
                if ($v >= 31 && $v <= 35) $thirty1++;
                if ($v >= 36 && $v <= 40) $thirty2++;
                if ($v >= 41 && $v <= 45) $forty1++;
                if ($v >= 46 && $v <= 50) $forty2++;
                if ($v >= 51 && $v <= 55) $fifty1++;
                if ($v >= 56 && $v <= 60) $fifty2++;
                if ($v >= 60) $sixty++;
            }
            $extent = [
                ['title' => '20及以下', 'number' => $twenty0],
                ['title' => '21～25', 'number' => $twenty1],
                ['title' => '26～30', 'number' => $twenty2],
                ['title' => '31～35', 'number' => $thirty1],
                ['title' => '36～40', 'number' => $thirty2],
                ['title' => '41～45', 'number' => $forty1],
                ['title' => '46～50', 'number' => $forty2],
                ['title' => '51～55', 'number' => $fifty1],
                ['title' => '56～60', 'number' => $fifty2],
                ['title' => '60及以下', 'number' => $sixty]
            ];
        }
        $res = [
            'info'=>$info,
            'shipowner'=>$result,
            'fleet'=>$fleet,
            'vessel'=>$vessel,
            'duty'=>$duty,
            'age'=>$extent,
        ];
        return json($res);
    }

    /**
     * 社保预算
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function socialInfo()
    {
        $result = [];
        if(!in_array("社保预算",$this->privilege) && $this->is_cehck_rule) return json($result);
        $type = input('type');
        $startDate = input('date/a')[0];
        $endDate = input('date/a')[1];
        $monarr = [];
        if($startDate && $endDate){
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
        }else{
            $startDate = strtotime(date('Y-m',strtotime("-11 month")));
            $endDate = strtotime(date('Y-m'),time());
        }
        $monarr[] = date('Y-m',$startDate);
        while( ($startDate = strtotime('+1 month', $startDate)) <= $endDate){
            $monarr[] = date('Y-m',$startDate); // 取得递增月;
        }
        switch($type){
            //月度
            case 1:
                if($endDate > date('Y-m',time())){
                    foreach ($monarr as $k1=>$v1){
                        if($v1 > date('Y-m',time())){
                            $list = $info = Db::name('insured')
                                ->alias('a')
                                ->join('insured_stop b','a.mariner_id=b.mariner_id','LEFT')
                                ->where(['is_stop'=>0])
                                ->count();
                            $info = Db::name('insured')
                                ->alias('a')
                                ->field("a.mariner_id,a.area,max(a.starttime) starttime,max(b.starttime) stoptime")
                                ->join('insured_stop b','a.mariner_id=b.mariner_id','LEFT')
                                ->where(['is_stop'=>0])
                                ->group('mariner_id')
                                ->select();
                            $finalPerson = 0;//实际个人
                            $finalCompany = 0;//实际公司
                            $totalCompany = 0;
                            $totalPerson = 0;
                            $addPerson = 0;
                            $addCompany = 0;
                            $final = 0;//合计
                            foreach ($info as $k=>&$v) {
                                //是否停保
                                if ($v['stoptime'] > $v['starttime']) continue;
                                $areaInfo = Db::name('social_security')
                                    ->where(['area' => $v['area'], 'starttime' => ['ELT', date('Y-m', time()), 'endtime' => ['EGT', date('Y-m', time())]]])
                                    ->order('starttime desc')
                                    ->find();
                                $setInfo = Db::name('social_security_set')
                                    ->where(['pid' => $areaInfo['id']])
                                    ->select();
                                foreach ($setInfo as $key => $value) {
                                    if ($value['title'] == "养老保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "生育保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "工伤保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "失业保险") {
                                        $data['shiye_company'] = $value['base_company'] * $value['rate_company'];
                                        $data['shiye_person'] = $value['base_person'] * $value['rate_person'];
                                        $totalPerson += $data['shiye_person'];
                                        $totalCompany += $data['shiye_company'];
                                    } elseif ($value['title'] == "医疗保险") {
                                        $data['yiliao_company'] = $value['base_company'] * $value['rate_company'];
                                        $data['yiliao_person'] = $value['base_person'] * $value['rate_person'];
                                        $totalPerson += $data['yiliao_person'];
                                        $totalCompany += $data['yiliao_company'];
                                    } else {
                                        $addPerson += $value['base_person'] * $value['rate_person'];
                                        $addCompany += $value['base_company'] * $value['rate_company'];
                                    }
                                }
                                $finalPerson += $totalPerson + $addPerson;
                                $finalCompany += $totalCompany + $addCompany;
                                $final += $finalPerson + $finalCompany;
                            }
                            $result[] = [
                                'month'=>$v1,
                                'list'=>$list,
                                'total'=>$final
                            ];
                        }else{
                            $list = Db::name('social_info')->where(['pay_month'=>$v1])->count();
                            $info = Db::name('social_info')->field('SUM(final_company)+SUM(final_person) total')->where(['pay_month'=>$v1])->find();
                            $result[] = [
                                'month'=>$v1,
                                'list'=>$list,
                                'total'=>$info['total']?$info['total']:0
                            ];
                        }
                    }
                }else{
                    foreach ($monarr as $k=>$v){
                        $list = Db::name('social_info')->where(['pay_month'=>$v])->count();
                        $info = Db::name('social_info')->field('SUM(final_company)+SUM(final_person) total')->where(['pay_month'=>$v])->find();
                        $result[] = [
                            'month'=>$v,
                            'list'=>$list,
                            'total'=>$info['total']?$info['total']:0
                        ];
                    }
                }
                return json($result);
                break;
            //参保地
            case 2:
                $area = Db::name('social_security')->where(['endtime'=>['GT',date('Y-m',time())]])->group('area')->column('area');
                foreach ($area as $k1=>$v1) {
                    $list = Db::name('social_info')->where(['area' => $v1, 'pay_month' => ['BETWEEN', [current($monarr), $monarr[count($monarr)-1]]]])->count();
                    $info = Db::name('social_info')->field('SUM(final_company)+SUM(final_person) total')->where(['area' => $v1, 'pay_month' => ['BETWEEN', [current($monarr), $monarr[count($monarr)-1]]]])->find();
                    $final = 0;//合计
                    foreach ($monarr as $k2=>$v2){
                        //预算
                        $finalPerson = 0;//实际个人
                        $finalCompany = 0;//实际公司
                        $totalCompany = 0;
                        $totalPerson = 0;
                        $addPerson = 0;
                        $addCompany = 0;
                        if($v2 > date('Y-m',time())){
                            $list += Db::name('insured')
                                ->alias('a')
                                ->join('insured_stop b','a.mariner_id=b.mariner_id','LEFT')
                                ->where(['is_stop'=>0,'a.area'=>$v1])
                                ->group('a.mariner_id')
                                ->count();
                            $insured = Db::name('insured')
                                ->alias('a')
                                ->field("a.mariner_id,a.area,max(a.starttime) starttime,max(b.starttime) stoptime")
                                ->join('insured_stop b','a.mariner_id=b.mariner_id','LEFT')
                                ->where(['is_stop'=>0,'a.area'=>$v1])
                                ->group('mariner_id')
                                ->select();
                            foreach ($insured as $k=>&$v) {
                                //是否停保
                                if ($v['stoptime'] > $v['starttime']) continue;
                                $areaInfo = Db::name('social_security')
                                    ->where(['area' => $v['area'], 'starttime' => ['ELT', date('Y-m', time()), 'endtime' => ['EGT', date('Y-m', time())]]])
                                    ->order('starttime desc')
                                    ->find();
                                $setInfo = Db::name('social_security_set')
                                    ->where(['pid' => $areaInfo['id']])
                                    ->select();
                                foreach ($setInfo as $key => $value) {
                                    if ($value['title'] == "养老保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "生育保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "工伤保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "失业保险") {
                                        $data['shiye_company'] = $value['base_company'] * $value['rate_company'];
                                        $data['shiye_person'] = $value['base_person'] * $value['rate_person'];
                                        $totalPerson += $data['shiye_person'];
                                        $totalCompany += $data['shiye_company'];
                                    } elseif ($value['title'] == "医疗保险") {
                                        $data['yiliao_company'] = $value['base_company'] * $value['rate_company'];
                                        $data['yiliao_person'] = $value['base_person'] * $value['rate_person'];
                                        $totalPerson += $data['yiliao_person'];
                                        $totalCompany += $data['yiliao_company'];
                                    } else {
                                        $addPerson += $value['base_person'] * $value['rate_person'];
                                        $addCompany += $value['base_company'] * $value['rate_company'];
                                    }
                                    $finalPerson += $totalPerson + $addPerson;
                                    $finalCompany += $totalCompany + $addCompany;
                                    $final += $finalPerson + $finalCompany;
                                }
                            }
                        }
                    }
                    $result[] = [
                        'area' => $v1,
                        'list' => $list,
                        'total' => $info['total']+$final
                    ];
                }
                return json($result);
                break;
            //船队
            case 3:
                $fleet = Db::name('mariner')
                    ->group('fleet')
                    ->column('fleet');
                foreach ($fleet as $k1=>$v1){
                    $list = Db::name('social_info')
                        ->alias('a')
                        ->join('mariner b','a.mariner_id=b.id','LEFT')
                        ->where(['b.fleet'=>$v1,'pay_month' => ['BETWEEN', [current($monarr), $monarr[count($monarr)-1]]]])
                        ->count();
                    $info = Db::name('social_info')
                        ->alias('a')
                        ->field('SUM(final_company)+SUM(final_person) total')
                        ->join('mariner b','a.mariner_id=b.id','LEFT')
                        ->where(['b.fleet'=>$v1,'pay_month' => ['BETWEEN', [current($monarr), $monarr[count($monarr)-1]]]])
                        ->find();
                    $final = 0;//合计
                    foreach ($monarr as $k2=>$v2){
                        //预算
                        $finalPerson = 0;//实际个人
                        $finalCompany = 0;//实际公司
                        $totalCompany = 0;
                        $totalPerson = 0;
                        $addPerson = 0;
                        $addCompany = 0;
                        if($v2 > date('Y-m',time())){
                            $list += Db::name('insured')
                                ->alias('a')
                                ->join('insured_stop b','a.mariner_id=b.mariner_id','LEFT')
                                ->join('mariner c','a.mariner_id=c.id','LEFT')
                                ->where(['is_stop'=>0,'c.fleet'=>$v1])
                                ->group('a.mariner_id')
                                ->count();
                            $insured = Db::name('insured')
                                ->alias('a')
                                ->field("a.mariner_id,a.area,max(a.starttime) starttime,max(b.starttime) stoptime")
                                ->join('insured_stop b','a.mariner_id=b.mariner_id','LEFT')
                                ->join('mariner c','a.mariner_id=c.id','LEFT')
                                ->where(['is_stop'=>0,'c.fleet'=>$v1])
                                ->group('a.mariner_id')
                                ->select();
                            foreach ($insured as $k=>&$v) {
                                //是否停保
                                if ($v['stoptime'] > $v['starttime']) continue;
                                $areaInfo = Db::name('social_security')
                                    ->where(['area' => $v['area'], 'starttime' => ['ELT', date('Y-m', time()), 'endtime' => ['EGT', date('Y-m', time())]]])
                                    ->order('starttime desc')
                                    ->find();
                                $setInfo = Db::name('social_security_set')
                                    ->where(['pid' => $areaInfo['id']])
                                    ->select();
                                foreach ($setInfo as $key => $value) {
                                    if ($value['title'] == "养老保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "生育保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "工伤保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "失业保险") {
                                        $data['shiye_company'] = $value['base_company'] * $value['rate_company'];
                                        $data['shiye_person'] = $value['base_person'] * $value['rate_person'];
                                        $totalPerson += $data['shiye_person'];
                                        $totalCompany += $data['shiye_company'];
                                    } elseif ($value['title'] == "医疗保险") {
                                        $data['yiliao_company'] = $value['base_company'] * $value['rate_company'];
                                        $data['yiliao_person'] = $value['base_person'] * $value['rate_person'];
                                        $totalPerson += $data['yiliao_person'];
                                        $totalCompany += $data['yiliao_company'];
                                    } else {
                                        $addPerson += $value['base_person'] * $value['rate_person'];
                                        $addCompany += $value['base_company'] * $value['rate_company'];
                                    }
                                    $finalPerson += $totalPerson + $addPerson;
                                    $finalCompany += $totalCompany + $addCompany;
                                    $final += $finalPerson + $finalCompany;
                                }
                            }
                        }
                    }
                    $result[] = [
                        'area' => $v1,
                        'list' => $list,
                        'total' => $info['total']+$final
                    ];
                }
                return json($result);
                break;
            //船东
            case 4:
                $shipowner = Db::name('shipowner')
                    ->field('id,title')
                    ->select();
                foreach ($shipowner as $k1=>$v1){
                    $list = Db::name('social_info')
                        ->alias('a')
                        ->join('mariner b','a.mariner_id=b.id','LEFT')
                        ->where(['b.owner_pool'=>$v1['id'],'pay_month' => ['BETWEEN', [current($monarr), $monarr[count($monarr)-1]]]])
                        ->count();
                    $info = Db::name('social_info')
                        ->alias('a')
                        ->field('SUM(final_company)+SUM(final_person) total')
                        ->join('mariner b','a.mariner_id=b.id','LEFT')
                        ->where(['b.owner_pool'=>$v1['id'],'pay_month' => ['BETWEEN', [current($monarr), $monarr[count($monarr)-1]]]])
                        ->find();
                    $final = 0;//合计
                    foreach ($monarr as $k2=>$v2){
                        //预算
                        $finalPerson = 0;//实际个人
                        $finalCompany = 0;//实际公司
                        $totalCompany = 0;
                        $totalPerson = 0;
                        $addPerson = 0;
                        $addCompany = 0;
                        if($v2 > date('Y-m',time())){
                            $list += Db::name('insured')
                                ->alias('a')
                                ->join('insured_stop b','a.mariner_id=b.mariner_id','LEFT')
                                ->join('mariner c','a.mariner_id=c.id','LEFT')
                                ->where(['is_stop'=>0,'c.owner_pool'=>$v1['id']])
                                ->count();
                            $insured = Db::name('insured')
                                ->alias('a')
                                ->field("a.mariner_id,a.area,max(a.starttime) starttime,max(b.starttime) stoptime")
                                ->join('insured_stop b','a.mariner_id=b.mariner_id','LEFT')
                                ->join('mariner c','a.mariner_id=c.id','LEFT')
                                ->where(['is_stop'=>0,'c.owner_pool'=>$v1['id']])
                                ->select();
                            foreach ($insured as $k=>&$v) {
                                //是否停保
                                if ($v['stoptime'] > $v['starttime']) continue;
                                $areaInfo = Db::name('social_security')
                                    ->where(['area' => $v['area'], 'starttime' => ['ELT', date('Y-m', time()), 'endtime' => ['EGT', date('Y-m', time())]]])
                                    ->order('starttime desc')
                                    ->find();
                                $setInfo = Db::name('social_security_set')
                                    ->where(['pid' => $areaInfo['id']])
                                    ->select();
                                foreach ($setInfo as $key => $value) {
                                    if ($value['title'] == "养老保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "生育保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "工伤保险") {
                                        $totalPerson += $value['base_person'] * $value['rate_person'];
                                        $totalCompany += $value['base_company'] * $value['rate_company'];
                                    } elseif ($value['title'] == "失业保险") {
                                        $data['shiye_company'] = $value['base_company'] * $value['rate_company'];
                                        $data['shiye_person'] = $value['base_person'] * $value['rate_person'];
                                        $totalPerson += $data['shiye_person'];
                                        $totalCompany += $data['shiye_company'];
                                    } elseif ($value['title'] == "医疗保险") {
                                        $data['yiliao_company'] = $value['base_company'] * $value['rate_company'];
                                        $data['yiliao_person'] = $value['base_person'] * $value['rate_person'];
                                        $totalPerson += $data['yiliao_person'];
                                        $totalCompany += $data['yiliao_company'];
                                    } else {
                                        $addPerson += $value['base_person'] * $value['rate_person'];
                                        $addCompany += $value['base_company'] * $value['rate_company'];
                                    }
                                    $finalPerson += $totalPerson + $addPerson;
                                    $finalCompany += $totalCompany + $addCompany;
                                    $final += $finalPerson + $finalCompany;
                                }
                            }
                        }
                    }
                    $result[] = [
                        'area' => $v1['title'],
                        'list' => $list,
                        'total' => $info['total']+$final
                    ];
                }
                return json($result);
                break;
            //月度社保预算
            case 5:
                $month = input('month');
               $info = Db::name('social_info')->field('SUM(yiliao_person+shengyu_person+shiye_person+yanglao_person+gongshang_person)  social_person,SUM(yiliao_company+shengyu_company+shiye_company+yanglao_company+gongshang_company)  social_company,SUM(else_person) else_person,SUM(else_company) else_company,SUM(amount_person) amount_person,SUM(amount_company) amount_company,SUM(assume_person) assume_person,SUM(add_person) add_person,SUM(add_company) add_company,SUM(final_person) final_person,SUM(final_company) final_company')->where(['pay_month'=>$month])->select();
                return json($info);
                break;
            default:
                return error_data('必要参数错误');
        }
    }

    /**
     * 供应商费用统计
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function supplierInfo()
    {
        $result = [];
        if(!in_array("供应商统计",$this->privilege) && $this->is_cehck_rule) return json($result);
        $startDate = input('date/a')[0];
        $endDate = input('date/a')[1];
        $supplier = input('supplier');
        $monarr = [];
        if($startDate && $endDate){
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
        }else{
            $startDate = strtotime(date('Y-m',strtotime("-11 month")));
            $endDate = strtotime(date('Y-m'),time());
        }
        $monarr[] = date('Y-m',$startDate); // 当前月;
        while( ($startDate = strtotime('+1 month', $startDate)) <= $endDate){
            $monarr[] = date('Y-m',$startDate); // 取得递增月;
        }
        $where = [];
        if($supplier) $where['pid'] = $supplier;
        $result = [];
        foreach ($monarr as $k=>$v){
            $where['month'] = $v;
            $payInfo = Db::name('supplier_info')->field('SUM(pay_before) pay_before,SUM(pay_after) pay_after')->where($where)->find();
            $result[] = [
                'month'=>$v,
                'pay_before'=>$payInfo['pay_before']?$payInfo['pay_before']:0,
                'pay_after'=>$payInfo['pay_after']?$payInfo['pay_after']:0
            ];
        }
        return json($result);
    }

    /**
     * 借还款统计
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function borrowInfo()
    {
        $result = [];
        if(!in_array("借还款统计",$this->privilege) && $this->is_cehck_rule) return json($result);
        $startDate = input('date/a')[0];
        $endDate = input('date/a')[1];
        $currency = input('currency')?input('currency'):"人民币";
        $monarr = [];
        if($startDate && $endDate){
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
        }else{
            $startDate = strtotime(date('Y-m',strtotime("-11 month")));
            $endDate = strtotime(date('Y-m'),time());
        }
        $monarr[] = date('Y-m',$startDate); // 当前月;
        while( ($startDate = strtotime('+1 month', $startDate)) <= $endDate){
            $monarr[] = date('Y-m',$startDate); // 取得递增月;
        }
        $result = [];
        foreach ($monarr as $k=>$v){
            $money = Db::name('borrow')->field('currency,SUM(amount) amount,SUM(repayment) repayment')->where(['tally'=>$v,'currency'=>$currency])->group('currency')->find();
            $result[] = [
                'month'=>$v,
                'currency'=>$currency,
                'amount'=>$money['amount']?$money['amount']:0,
                'repayment'=>$money['repayment']?$money['repayment']:0,
            ];
        }
        return json($result);
    }

    /**
     * 收费统计
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function chargeInfo()
    {
        $result = [];
        if(!in_array("收费统计",$this->privilege) && $this->is_cehck_rule) return json($result);
        $startDate = input('date/a')[0];
        $endDate = input('date/a')[1];
        $monarr = [];
        if($startDate && $endDate){
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
        }else{
            $startDate = strtotime(date('Y-m',strtotime("-11 month")));
            $endDate = strtotime(date('Y-m'),time());
        }
        $monarr[] = date('Y-m',$startDate); // 当前月;
        while( ($startDate = strtotime('+1 month', $startDate)) <= $endDate){
            $monarr[] = date('Y-m',$startDate); // 取得递增月;
        }
        $result = [];
        foreach ($monarr as $k=>$v){
            $money = Db::name('charge')->field('SUM(amount) amount,SUM(surplus) surplus')->where(['month'=>$v])->find();
            $result[] = [
                'month'=>$v,
                'amount'=>$money['amount']?$money['amount']:0,
                'surplus'=>$money['surplus']?$money['surplus']:0,
            ];
        }
        return json($result);
    }

    /**
     *报销统计
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function expenseInfo()
    {
        $result = [];
        if(!in_array("报销统计",$this->privilege) && $this->is_cehck_rule) return json($result);
        $type = input('type');
        $startDate = input('date/a')[0];
        $endDate = input('date/a')[1];
        $monarr = [];
        if($startDate && $endDate){
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
        }else{
            $startDate = strtotime(date('Y-m',strtotime("-11 month")));
            $endDate = strtotime(date('Y-m'),time());
        }
        $monarr[] = date('Y-m',$startDate);
        while( ($startDate = strtotime('+1 month', $startDate)) <= $endDate){
            $monarr[] = date('Y-m',$startDate); // 取得递增月;
        }
        switch ($type)
        {
            //月度
            case 1:
                $result = [];
                $list = 0;
                foreach ($monarr as $k=>$v){
                    $list += Db::name('expense')->field('SUM(really) really')->where(['status'=>1,'month'=>$v])->count();
                    $list += Db::name('user_expense')->field('SUM(really) really')->where(['status'=>1,'month'=>$v])->count();
                    $list += Db::name('office_expense')->field('SUM(really) really')->where(['status'=>1,'month'=>$v])->count();
                    $mariner = Db::name('expense')->field('SUM(really) really')->where(['status'=>1,'month'=>$v])->find();
                    $user = Db::name('user_expense')->field('SUM(really) really')->where(['status'=>1,'month'=>$v])->find();
                    $office = Db::name('office_expense')->field('SUM(really) really')->where(['status'=>1,'month'=>$v])->find();
                    $result[] = [
                        'month'=>$v,
                        'list'=>$list,
                        'mariner'=>$mariner['really']?$mariner['really']:0,
                        'user'=>$user['really']?$user['really']:0,
                        'office'=>$office['really']?$user['really']:0,
                        'total'=>$mariner['really']+$user['really']+$office['really']
                    ];
                }
                return json($result);
                break;
            //客户
            case 2:
               $mariner = Db::name('expense')
                   ->alias('a')
                    ->field('SUM(shiper_traffic)+SUM(shiper_hotel)+SUM(shiper_city)+SUM(shiper_examination)+SUM(shiper_train)+SUM(shiper_subsidy)+SUM(shiper_else) mariner,shipowner_id,c.title')
                    ->join('expense_assume b','a.id=b.pid','LEFT')
                    ->join('shipowner c','c.id=a.shipowner_id','LEFT')
                    ->where(['status'=>1,'month'=>['IN',$monarr]])
                    ->group('shipowner_id')
                    ->select();
                $user = Db::name('user_expense')
                    ->alias('a')
                    ->field('SUM(shiper_traffic)+SUM(shiper_hotel)+SUM(shiper_city)+SUM(shiper_travel)+SUM(shiper_meal)+SUM(shiper_exchange)+SUM(shiper_office)+SUM(shiper_communication)+SUM(shiper_post)+SUM(shiper_partner)+SUM(shiper_else) user,shipowner_id,c.title')
                    ->join('user_expense_assume b','a.id=b.pid','LEFT')
                    ->join('shipowner c','c.id=a.shipowner_id','LEFT')
                    ->where(['status'=>1,'month'=>['IN',$monarr]])
                    ->group('shipowner_id')
                    ->select();
                foreach ($mariner as $k=>&$v){
                    $v['total'] = 0;
                    $v['user'] = 0;
                    foreach ($user as $k1=>$v1){
                        if($v['shipowner_id'] == $v1['shipowner_id']){
                            $v['user'] = $v1['user'];
                            $v['total'] = $v['mariner']+$v1['user'];
                            $v['mariner'] += $v1['user'];
                        }
                    }
                }
                return json($mariner);
                break;
            //报销地点
            case 3:
                $mariner = Db::name('expense')->group('address')->column('address');
                $user = Db::name('user_expense')->group('address')->column('address');
                $office = Db::name('office_expense')->group('address')->column('address');
                $address = array_unique(array_merge($mariner,$user,$office));
                if(empty($address)) return error_data('没有数据');
                $result = [];
                foreach ($address as $k=>$v){
                   $marinerReally = Db::name('expense')->where(['address'=>$v])->sum('really');
                   $userReally = Db::name('user_expense')->where(['address'=>$v])->sum('really');
                   $officeReally = Db::name('office_expense')->where(['address'=>$v])->sum('really');
                   $result[] = [
                        'address'=>$v,
                       'mariner'=>$marinerReally,
                       'user'=>$userReally,
                       'office'=>$officeReally,
                       'total'=>$marinerReally+$userReally+$officeReally
                   ];
                }
                return json($result);
                break;
            default:
                return error_data('必要参数错误');
        }
    }

    /**
     * 获取提醒数据
     * @return \think\response\Json
     */
    public function warn()
    {
        $res = [
            'mariner_sign'=>$this->marinerSign,
            'user_sign'=>$this->userSign,
            'office_sign'=>$this->officeSign,
            'mariner_warn'=>$this->marinerWarn,
            'user_warn'=>$this->userWarn,
            'office_warn'=>$this->officeWarn,
            'isMariner'=>$this->isMariner
        ];
        return json($res);
    }
}