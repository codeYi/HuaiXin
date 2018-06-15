<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018-05-15 15:37
 */
namespace app\api\controller;
use app\api\common\Base;
use think\Db;
use think\Loader;
use think\Request;

/**
 * 报销控制器
 * Class Expenses
 * @package app\api\controller
 */
class Expenses extends Base
{
    /**
     * 船员报销添加
     * @return \think\response\Json
     * @throws \Exception
     */
    public function marinerAdd()
    {
        $id = input('id');
        $date = input('date');
        $marinerId = input('mariner_id');
        $shipownerId = input('shipowner_id');
        $vesselId = input('vessel_id');
        $address = input('address');
        $fleet = input('fleet');
        $reason = input('reason');
        $explain = input('explain');
        $traffic = input('traffic/a');
        $hotel = input('hotel/a');
        $city = input('city/a');
        $examination = input('examination/a');
        $train = input('train/a');
        $subsidy = input('subsidy/a');
        $else = input('else');
        $assume = input('assume/a');
        $remark = input('remark');
        $data = input('data/a');
        if (empty($marinerId)) return error_data('缺少船员信息');
        if(!$this->isMariner){
            //判断是否是对应的业务主管进行操作
            $shipownerIds = Db::name('business')->where(['user_id'=>self::$id])->column('pid');
            $roleId = Db::name('role_user')->where(['user_id'=>self::$id])->value('role_id');
            if(!in_array($shipownerId,$shipownerIds) && $roleId != 1) return error_data('无权限为该船员进行报销');
        }
        $shiperTraffic = input('shiper_traffic');
        $shiperHotel = input('shiper_hotel');
        $shiperCity = input('shiper_city');
        $shiperExamination = input('shiper_examination');
        $shiperTrain = input('shiper_train');
        $shiperSubsidy = input('shiper_subsidy');
        $shiperElse = input('shiper_else');
        $number = input('number/a');
        Db::startTrans();
        try {
            if($id){
                $update = [
                    'date'=>$date,
                    'month'=>date('Y-m',time()),
                    'mariner_id' => $marinerId,
                    'shipowner_id' => $shipownerId,
                    'vessel_id' =>$vesselId,
                    'fleet' => $fleet,
                    'reason' => $reason,
                    'address' => $address,
                    'explain' => $explain,
                    'total'=>0,
                    'really'=>0,
                    'over_date'=>"",
                    'status'=>0,
                    'warn'=>0
                ];
                //重新编辑船员报销
                Db::name('sign_mariner')->where(['pid'=>$id])->delete();
                Db::name('invoice')->where(['pid'=>$id,'is_mariner'=>1])->delete();
                Db::name('expense_assume')->where(['pid'=>$id])->delete();
                Db::name('expense_data')->where(['pid'=>$id])->delete();
                Db::name('expense_debt')->where(['pid'=>$id])->delete();
                Db::name('expense_option')->where(['pid'=>$id])->delete();
                Db::name('expense')->where(['id'=>$id])->update($update);
                $pid = $id;
            }else {
                $insert = [
                    'mariner_id' => $marinerId,
                    'date' => date('Y-m-d', time()),
                    'month' => date('Y-m', time()),
                    'shipowner_id' => $shipownerId,
                    'vessel_id' =>$vesselId,
                    'fleet' => $fleet,
                    'reason' => $reason,
                    'address' => $address,
                    'explain' => $explain
                ];
                $pid = Db::name('expense')->insertGetId($insert);
            }
                $insertIn = [];
                foreach ($number as $k=>$v){
                    if(empty($v)) continue;
                    $repeat = Db::name('invoice')->where(['number'=>$v])->value('pid');
                    if($repeat) return error_data("电子发票重复:$v");
                    if(empty($v)) continue;
                    $insertIn[] = [
                        'pid'=>$pid,
                        'number'=>$v,
                        'is_mariner'=>1
                    ];
                }
                if($insertIn[0]) Db::name('invoice')->insertAll($insertIn);
                $option = [
                    'pid' => $pid,
                    'traffic' => implode(',', $traffic),
                    'hotel' => implode(',', $hotel),
                    'city' => implode(',', $city),
                    'examination' => implode(',', $examination),
                    'train' => implode(',', $train),
                    'subsidy' => implode(',', $subsidy),
                    'else' => $else,
                    'assume' => implode(',', $assume),
                    'remark' => $remark,
                ];
                Db::name('expense_option')->insert($option);
                $total = 0;
                foreach ($data as $k =>$v) {
                    $data[$k]['pid'] = $pid;
                    $total += $v['traffic_cost'];
                    $total += $v['hotel_cost'];
                    $total += $v['city_cost'];
                    $total += $v['examination_cost'];
                    $total += $v['train_cost'];
                    $total += $v['subsidy_cost'];
                    $total += $v['else_cost'];
                }
                Db::name('expense_data')->insertAll($data);
                Db::name('expense')->where(['id'=>$pid])->update(['total'=>$total]);
                $shipowner = [
                    'pid'=>$pid,
                    'shiper_traffic' => $shiperTraffic,
                    'shiper_hotel' => $shiperHotel,
                    'shiper_city' => $shiperCity,
                    'shiper_examination' => $shiperExamination,
                    'shiper_train' => $shiperTrain,
                    'shiper_subsidy' => $shiperSubsidy,
                    'shiper_else' => $shiperElse,
                ];
                Db::name('expense_assume')->insert($shipowner);
                Db::name('sign_mariner')->insert(['pid'=>$pid]);
            Db::commit();
            return ok_data();
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 员工报销添加
     * @return \think\response\Json
     * @throws \Exception
     */
    public function userAdd()
    {
        $id = input('id');
        $userId = input('user_id');
        if (!$userId) return error_data('缺少员工信息');
        $date = input('date');
        $startDate = input('start_date');
        $endDate = input('end_date');
        $days = input('days');
        $shipownerId = input('shipowner_id');
        $vesselId = input('vessel_id');
        $address = input('address');
        $reason = input('reason');
        $explain = input('explain');
        $traffic = input('traffic/a');
        $hotel = input('hotel/a');
        $city = input('city/a');
        $travel = input('travel/a');
        $meal = input('meal/a');
        $exchange = input('exchange/a');
        $office = input('office/a');
        $communication = input('communication/a');
        $post = input('post/a');
        $partner = input('partner');
        $else = input('else');
        $data = input('data/a');
        $number = input('number/a');
        Db::startTrans();
        try {
            if($id){
                $update = [
                    'user_id' => $userId,
                    'date' => $date,
                    'month' => date('Y-m',time()),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'days' => $days,
                    'shipowner_id' => $shipownerId,
                    'vessel_id' => $vesselId,
                    'address' => $address,
                    'reason' => $reason,
                    'explain' => $explain,
                    'total' => 0,
                    'type' => 1,
                    'really' => 0,
                    'over_date' => "",
                    'status' => 0,
                    'warn' => 0
                ];
                //重新编辑船员报销
                Db::name('sign_user')->where(['pid'=>$id])->delete();
                Db::name('invoice')->where(['pid'=>$id,'is_mariner'=>2])->delete();
                Db::name('user_expense_assume')->where(['pid'=>$id])->delete();
                Db::name('user_expense_data')->where(['pid'=>$id])->delete();
                Db::name('user_expense_principal')->where(['pid'=>$id])->delete();
                Db::name('user_expense_option')->where(['pid'=>$id])->delete();
                Db::name('user_expense')->where(['id'=>$id])->update($update);
                $pid = $id;
            }else {
                $insert = [
                    'user_id' => $userId,
                    'date' => $date,
                    'month' => date('Y-m', time()),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'days' => $days,
                    'shipowner_id' => $shipownerId,
                    'vessel_id' => $vesselId,
                    'address' => $address,
                    'reason' => $reason,
                    'explain' => $explain,
                ];
                $pid = Db::name('user_expense')->insertGetId($insert);
            }
        $insertIn = [];
        foreach ($number as $k=>$v){
            if(empty($v)) continue;
            $repeat = Db::name('invoice')->where(['number'=>$v])->value('pid');
            if($repeat) return error_data("电子发票重复:$v");
            $insertIn[] = [
                    'pid'=>$pid,
                    'number'=>$v,
                    'is_mariner'=>2
                ];
            }
            if($insertIn[0]) Db::name('invoice')->insertAll($insertIn);
            $option = [
                'pid' => $pid,
                'traffic' => implode(',', $traffic),
                'hotel' => implode(',', $hotel),
                'city' => implode(',', $city),
                'travel' => implode(',', $travel),
                'meal' => implode(',', $meal),
                'exchange' => implode(',', $exchange),
                'office' => implode(',', $office),
                'communication' => implode(',', $communication),
                'post' => implode(',', $post),
                'partner' =>  $partner,
                'else' =>  $else
            ];
            Db::name('user_expense_option')->insert($option);
            $total = 0;
            foreach ($data as $k => &$v) {
                $v['pid'] = $pid;
                $total += $v['traffic_cost'];
                $total += $v['hotel_cost'];
                $total += $v['city_cost'];
                $total += $v['examination_cost'];
                $total += $v['train_cost'];
                $total += $v['subsidy_cost'];
                $total += $v['else_cost'];
            }
            Db::name('user_expense_data')->insertAll($data);
            $shipowner = [
                'pid'=>$pid,
                'shiper_traffic' => input('shiper_traffic'),
                'shiper_hotel' => input('shiper_hotel'),
                'shiper_city' => input('shiper_city'),
                'shiper_travel' => input('shiper_travel'),
                'shiper_meal' => input('shiper_meal'),
                'shiper_exchange' => input('shiper_exchange'),
                'shiper_office' => input('shiper_office'),
                'shiper_communication' => input('shiper_communication'),
                'shiper_post' => input('shiper_post'),
                'shiper_partner' => input('shiper_partner'),
                'shiper_else' => input('shiper_else'),
            ];
            Db::name('user_expense')->where(['id'=>$pid])->update(['total'=>$total]);
            Db::name('user_expense_assume')->insert($shipowner);
            Db::name('sign_user')->insert(['pid'=>$pid]);
            Db::commit();
            return ok_data();
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }

    }

    /**
     * 办公室报销添加
     * @return \think\response\Json
     * @throws \Exception
     */
    public function officeAdd()
    {
        $id = input('id');
        $userId = input('user_id');
        $date = input('date');
        $shipownerId = input('shipowner_id');
        $address = input('address');
        $total = input('total');
        if(!$userId) return error_data('用户未登录');
        $data = input('data/a');
        $number = input('number/a');
        Db::startTrans();
        try{
            if($id){
                $update = [
                    'user_id'=>$userId,
                    'date'=>$date,
                    'month' => date('Y-m', time()),
                    'shipowner_id'=>$shipownerId,
                    'address'=>$address,
                    'total'=>0,
                    'really'=>0,
                    'status'=>0,
                    'warn'=>0
                ];
                Db::name('invoice')->where(['pid'=>$id,'is_mariner'=>2])->delete();
                Db::name('office_expense_data')->where(['pid'=>$id])->delete();
                Db::name('sign_office')->where(['pid'=>$id])->delete();
                Db::name('office_expense')->where(['id'=>$id])->update($update);
                $pid = $id;
            }else{
                $insert = [
                    'user_id'=>$userId,
                    'date'=>$date,
                    'month' => date('Y-m', time()),
                    'shipowner_id'=>$shipownerId,
                    'address'=>$address,
                    'total'=>$total,
                ];
                $pid = Db::name('office_expense')->insertGetId($insert);
            }
            $insertIn = [];
            foreach ($number as $k=>$v){
                if(empty($v)) continue;
                $repeat = Db::name('invoice')->where(['number'=>$v])->value('pid');
                if($repeat) return error_data("电子发票重复:$v");
                $insertIn[] = [
                    'pid'=>$pid,
                    'number'=>$v,
                    'is_mariner'=>2
                ];
            }
            if($insertIn[0]) Db::name('invoice')->insertAll($insertIn);
            foreach ($data as $k=>&$v){
                if(empty($v['project'])) return error_data('项目名称不能为空');
                $v['pid'] = $pid;
            }
            Db::name('office_expense_data')->insertAll($data);
            Db::name('sign_office')->insert(['pid'=>$pid]);
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 船员报销页面
     * @return \think\response\Json
     */
    public function marinerDetail()
    {
        $user = [];
        if(!session('is_mariner')) return json($user);
        $user = $this->user;
        $user['name'] = $user['name'] . "/" . $user['duty'] . "/" . substr($user['id_number'], 6, 4) . "-" . substr($user['id_number'], 10, 2) . "-" . substr($user['id_number'], 12, 2);
        unset($user['password']);
        return json($user);
    }

    /**
     * 员工报销页面
     * @return \think\response\Json
     */
    public function userDetail()
    {
        $user = $this->user;
        unset($user['password']);
        return json($user);
    }

    /**
     * 查询条件--供应商列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function supplierList()
    {
        $info = Db::name('supplier')->field('id,title')->select();
        $res = [
            'data' => $info
        ];
        return json($res);
    }

    /**
     * 船员报销记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listMariner()
    {
        $page = input('page')?input('page'):1;
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $id = input('id');
        $fleet = strtolower(input('fleet'));
        $shipownerId = input('shipownerId');
        $vesselId = input('vesselId');
        $cid = input('cid');
        $status = input('status/a');
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $startTime = input('date/a')[0];
        $endTime = input('date/a')[1];
        $address = input('address/a');
        $where = [];
        if($id) $where['mariner_id'] = $id;
        if($fleet) $where['a.fleet'] = ['LIKE',"%$fleet%"];
        if($shipownerId) $where['a.shipowner_id'] = $shipownerId;
        if($vesselId) $where['vessel_id'] = $vesselId;
        if($cid) $where['mariner_id'] = Db::name('mariner')->where(['cid'=>$cid])->value('id');
        if($status) $where['status'] = ['IN',$status];
        if($firstTotal && empty($secondTotal)) $where['total'] = ['EGT',$firstTotal];
        if($firstTotal && $secondTotal) $where['total'] = ['BETWEEN',[$firstTotal,$secondTotal]];
        if($address) $where['address'] = ['IN',$address];
        if($startTime && $endTime) $where['date'] = ['BETWEEN',[$startTime,$endTime]];
        //业务主管对应的船东id
       $shipownerIds = Db::name('business')->where(['user_id'=>self::$id])->column('pid');
       $where['shipowner_id'] = ['IN',$shipownerIds];
       if($this->user['duty'] == config('department').config('leader') || !$this->is_cehck_rule) unset($where['shipowner_id']);
        $list = Db::name('expense')
            ->alias('a')
            ->field('a.id,a.date,b.name,b.duty,c.title shipowner,d.title vessel,a.fleet,a.address,a.reason,a.explain,a.total,a.status')
            ->join('mariner b','a.mariner_id=b.id','LEFT')
            ->join('shipowner c','a.shipowner_id=c.id','LEFT')
            ->join('vessel d','a.vessel_id=d.id','LEFT')
            ->where($where)
            ->count();
       $info = Db::name('expense')
           ->alias('a')
           ->field('a.id,a.date,b.name,b.duty,c.title shipowner,d.title vessel,a.fleet,a.address,a.reason,a.explain,a.total,a.status')
           ->join('mariner b','a.mariner_id=b.id','LEFT')
           ->join('shipowner c','a.shipowner_id=c.id','LEFT')
           ->join('vessel d','a.vessel_id=d.id','LEFT')
           ->where($where)
           ->order('date')
           ->page($page,$listRows)
           ->select();
       $total = 0;
        foreach ($info as $k=>&$v){
            if($v['status'] == 0){
                $v['status'] = "待签批";
            }elseif ($v['status'] == 2){
                $v['status'] = "未通过";
            }elseif($v['status'] == 1){
                $v['status'] = "已通过";
            }
            $total += $v['total'];
        }
        $res = [
            'list'=> $list,
            'data'=> $info,
            'total'=>$total
        ];
        return json($res);
    }

    /**
     * 导出船员报销记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportMariner()
    {
        $id = input('id');
        $fleet = strtolower(input('fleet'));
        $shipownerId = input('shipownerId');
        $vesselId = input('vesselId');
        $cid = input('cid');
        $status = input('status/a');
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $startTime = input('date/a')[0];
        $endTime = input('date/a')[1];
        $address = input('address/a');
        $where = [];
        if($id) $where['mariner_id'] = $id;
        if($fleet) $where['a.fleet'] = ['LIKE',"%$fleet%"];
        if($shipownerId) $where['a.shipowner_id'] = $shipownerId;
        if($vesselId) $where['vessel_id'] = $vesselId;
        if($cid) $where['mariner_id'] = Db::name('mariner')->where(['cid'=>$cid])->value('id');
        if($status) $where['status'] = ['IN',$status];
        if($firstTotal && empty($secondTotal)) $where['total'] = ['EGT',$firstTotal];
        if($firstTotal && $secondTotal) $where['total'] = ['BETWEEN',[$firstTotal,$secondTotal]];
        if($address) $where['address'] = ['IN',$address];
        if($startTime && $endTime) $where['date'] = ['BETWEEN',[$startTime,$endTime]];
        //业务主管对应的船东id
        $shipownerIds = Db::name('business')->where(['user_id'=>self::$id])->column('pid');
        $where['shipowner_id'] = ['IN',$shipownerIds];
        if($this->user['duty'] == config('department').config('leader') || !$this->is_cehck_rule) unset($where['shipowner_id']);
        $info = Db::name('expense')
            ->alias('a')
            ->field('a.id,a.date,b.name,b.duty,c.title shipowner,d.title vessel,a.fleet,a.address,a.reason,a.explain,a.total,a.status')
            ->join('mariner b','a.mariner_id=b.id','LEFT')
            ->join('shipowner c','a.shipowner_id=c.id','LEFT')
            ->join('vessel d','a.vessel_id=d.id','LEFT')
            ->where($where)
            ->order('date')
            ->select();
        $total = 0;
        foreach ($info as $k=>&$v){
            if($v['status'] == 0){
                $v['status'] = "待签批";
            }elseif ($v['status'] == 2){
                $v['status'] = "未通过";
            }elseif($v['status'] == 1){
                $v['status'] = "已通过";
            }
            $total += $v['total'];
        }
        $res = [
            'data'=> $info,
            'total'=>$total
        ];
        return json($res);
    }

    /**
     * 员工报销记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listUser()
    {
        if($this->isMariner) return error_data('无权限查看');
        $page = input('page');
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $status = input('status/a');
        $firstDate = input('date/a')[0];
        $secondDate = input('date/a')[1];
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $shipownerId = input('shipownerId');
        $address = input('address/a');
        $firstDays = input('days/a')[0];
        $secondDays = input('days/a')[1];
        $where['user_id'] = self::$id;
        if($this->user['principal'] == "是" || $this->user['duty'] == $this->user['department'].config('leader')) unset($where['user_id']);
        if($status) $where['status'] = ['IN',$status];
        if($firstDate && $secondDate) $where['date'] = ['BETWEEN TIME',[$firstDate,$secondDate]];
        if($firstTotal && empty($secondTotal)) $where['total'] = ['EGT',$firstTotal];
        if($firstTotal && $secondTotal) $where['total'] = ['BETWEEN',[$firstTotal,$secondTotal]];
        if($shipownerId) $where['shipowner_id'] = $shipownerId;
        if($address) $where['address'] = ['IN',$address];
        if($firstDays && $secondDays) $where['days'] = ['BETWEEN',[$firstDays,$secondDays]];
        $list = Db::name('user_expense')
            ->where($where)
            ->count();
        $info = Db::name('user_expense')
            ->alias('a')
            ->field('a.*,b.username,b.department')
            ->join('user b','a.user_id=b.id','LEFT')
            ->where($where)
            ->order('date')
            ->page($page,$listRows)
            ->select();
        $total = 0;
        foreach ($info as $k=>&$v){
            if($v['status'] == 0){
                $v['status'] = "待签批";
            }elseif ($v['status'] == 2){
                $v['status'] = "未通过";
            }elseif($v['status'] == 1){
                $v['status'] = "已通过";
            }
            $total += $v['total'];
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'total'=>$total
        ];
        return json($res);
    }

    /**
     * 导出船员报销记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportUser()
    {
        if($this->isMariner) return error_data('无权限查看');
        $status = input('status/a');
        $firstDate = input('date/a')[0];
        $secondDate = input('date/a')[1];
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $shipownerId = input('shipownerId');
        $address = input('address/a');
        $firstDays = input('days/a')[0];
        $secondDays = input('days/a')[1];
        $where['user_id'] = self::$id;
        if($this->user['principal'] == "是" || $this->user['duty'] == $this->user['department'].config('leader')) unset($where['user_id']);
        if($status) $where['status'] = ['IN',$status];
        if($firstDate && $secondDate) $where['date'] = ['BETWEEN TIME',[$firstDate,$secondDate]];
        if($firstTotal && empty($secondTotal)) $where['total'] = ['EGT',$firstTotal];
        if($firstTotal && $secondTotal) $where['total'] = ['BETWEEN',[$firstTotal,$secondTotal]];
        if($shipownerId) $where['shipowner_id'] = $shipownerId;
        if($address) $where['address'] = ['IN',$address];
        if($firstDays && $secondDays) $where['days'] = ['BETWEEN',[$firstDays,$secondDays]];
        $info = Db::name('user_expense')
            ->alias('a')
            ->field('a.*,b.username,b.department')
            ->join('user b','a.user_id=b.id','LEFT')
            ->where($where)
            ->order('date')
            ->select();
        $total = 0;
        foreach ($info as $k=>&$v){
            if($v['status'] == 0){
                $v['status'] = "待签批";
            }elseif ($v['status'] == 2){
                $v['status'] = "未通过";
            }elseif($v['status'] == 1){
                $v['status'] = "已通过";
            }
            $total += $v['total'];
        }
        $res = [
            'data'=>$info,
            'total'=>$total
        ];
        return json($res);
    }

    /**
     * 办公室报销记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listOffice()
    {
        if($this->isMariner) return error_data('无权限查看');
        $page = input('page')?input('page'):1;
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $status = input('status/a');
        $username = input('username');
        $abbreviation = strtolower(input('abbreviation'));
        $department = input('department');
        $firstTotal = input('expense/a')[0];
        $secondTotal = input('expense/a')[1];
        $startDate = input('date/a')[0];
        $endDate = input('date/a')[1];
        $address = input('address/a');
        $shipownerId = input('shipowner_id');
        $project = input('project/a');
        $where['user_id'] = self::$id;
        if($this->user['principal'] == "是" || $this->user['duty'] == $this->user['department'].config('leader')) unset($where['user_id']);
        if($status[0]) $where['status'] = ['IN',$status];
        if($username || $abbreviation || $department) {
            $id = Db::name('user')->where(['username'=>['LIKE',"%$username%"],'abbreviation'=>"%$abbreviation%",'department'=>["%$department%"]])->column('id');
            $where['id'] = ['IN',$id];
        }
        if($firstTotal && !$secondTotal) $where['total'] = ['EGT',$firstTotal];
        if($secondTotal && $secondTotal) $where['total'] = ['BETWEEN',[$firstTotal,$secondTotal]];
        if($startDate && $endDate) $where['date'] = ['BETWEEN',[$startDate,$endDate]];
        if($address[0]) $where['address'] = ['IN',$address];
        if($shipownerId) $where['shipowner_id'] = $shipownerId;
        if($shipownerId) $where['shipowner_id'] = $shipownerId;
        if($project[0]) {
           $ids = Db::name('expense_data')->where(['project'=>['IN',$project]])->column('pid');
           $ids = array_unique($ids);
           $where['id'] = ['IN',$ids];
        }
        $list = Db::name('office_expense')
            ->where($where)
            ->count();
        $info = Db::name('office_expense')
            ->alias('a')
            ->field("a.*,b.username")
            ->join('user b','a.user_id=b.id','LEFT')
            ->where($where)
            ->order('date')
            ->page($page,$listRows)
            ->select();
        $first_total = 0;
        $second_total = 0;
        foreach ($info as $k=>&$v){
          $v['project'] = Db::name('office_expense_data')->field('project,total sum')->where(['pid'=>$v['id']])->select();
           foreach ($v['project'] as $k1=>$v1){
               $first_total += $v1['sum'];
           }
            if($v['status'] == 0){
                $v['status'] = "待签批";
            }elseif ($v['status'] == 2){
                $v['status'] = "未通过";
            }elseif($v['status'] == 1){
                $v['status'] = "已通过";
            }
           $second_total += $v['total'];
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'first_total'=>$first_total,
            'second_total'=>$second_total
        ];
        return json($res);
    }

    /**
     * 导出办公室报销记录
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportOffice()
    {
        if($this->isMariner) return error_data('无权限查看');
        $status = input('status/a');
        $username = input('username');
        $abbreviation = strtolower(input('abbreviation'));
        $department = input('department');
        $firstTotal = input('expense/a')[0];
        $secondTotal = input('expense/a')[1];
        $startDate = input('date/a')[0];
        $endDate = input('date/a')[1];
        $address = input('address/a');
        $shipownerId = input('shipowner_id');
        $project = input('project/a');
        $where['user_id'] = self::$id;
        $where['user_id'] = 1;
        if($this->user['principal'] == "是" || $this->user['duty'] == $this->user['department'].config('leader')) unset($where['user_id']);
        if($status[0]) $where['status'] = ['IN',$status];
        if($username || $abbreviation || $department) {
            $id = Db::name('user')->where(['username'=>['LIKE',"%$username%"],'abbreviation'=>"%$abbreviation%",'department'=>["%$department%"]])->column('id');
            $where['id'] = ['IN',$id];
        }
        if($firstTotal && !$secondTotal) $where['total'] = ['EGT',$firstTotal];
        if($secondTotal && $secondTotal) $where['total'] = ['BETWEEN',[$firstTotal,$secondTotal]];
        if($startDate && $endDate) $where['date'] = ['BETWEEN',[$startDate,$endDate]];
        if($address[0]) $where['address'] = ['IN',$address];
        if($shipownerId) $where['shipowner_id'] = $shipownerId;
        if($shipownerId) $where['shipowner_id'] = $shipownerId;
        if($project[0]) {
            $ids = Db::name('expense_data')->where(['project'=>['IN',$project]])->column('pid');
            $ids = array_unique($ids);
            $where['id'] = ['IN',$ids];
        }
        $info = Db::name('office_expense')
            ->alias('a')
            ->field("a.id,a.date,b.username,a.address,a.total,a.status")
            ->join('user b','a.user_id=b.id','LEFT')
            ->where($where)
            ->order('date')
            ->select();
        foreach ($info as $k=>$v){
            $info[$k]['project'] = Db::name('office_expense_data')->field('project,total sum')->where(['pid'=>$v['id']])->select();
            if($v['status'] == 0){
                $info[$k]['status'] = "待签批";
            }elseif ($v['status'] == 2){
                $info[$k]['status'] = "未通过";
            }elseif($v['status'] == 1){
                $info[$k]['status'] = "已通过";
            }
            unset($info[$k]['id']);
        }
        $headArr = ['序号','报销时间','报销人','报销地点','报销项目','费用','报销总计','状态'];
        $fileName = "办公费用报销记录_" . date("Y_m_d", Request::instance()->time()) . ".xls";
        //引入PHPExcel类
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        Loader::import(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();

        $key = ord("A"); // 设置表头
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach ($info as $key => $rows) { // 列写入
            $span = ord("A");
            $objActSheet->setCellValue(chr($span) . $column, $column-1);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            foreach ($rows as $keyName => $value) { // 行写入
                if(!is_array($value)){
                    if($keyName == "date")$objActSheet->setCellValue("B" . $column, $value);
                    if($keyName == "username")$objActSheet->setCellValue("C" . $column, $value);
                    if($keyName == "address")$objActSheet->setCellValue("D" . $column, $value);
                    if($keyName == "total")$objActSheet->setCellValue("G" . $column, $value);
                    if($keyName == "status")$objActSheet->setCellValue("H" . $column, $value);
                }else{
                    $count = count($value,0);
                    if($count >1){
                        $maxColumn = $count + $column-1;
                            $objPHPExcel->getActiveSheet()->mergeCells( "A$column".":"."A$maxColumn");
                            $objPHPExcel->getActiveSheet()->mergeCells( "B$column".":"."B$maxColumn");
                            $objPHPExcel->getActiveSheet()->mergeCells( "C$column".":"."C$maxColumn");
                            $objPHPExcel->getActiveSheet()->mergeCells( "D$column".":"."D$maxColumn");
                            $objPHPExcel->getActiveSheet()->mergeCells( "G$column".":"."G$maxColumn");
                            $objPHPExcel->getActiveSheet()->mergeCells( "H$column".":"."H$maxColumn");
                    }
                    foreach ($value as $k1=>$v1){
                        foreach ($v1 as $k2=>$v2){
                            if($k2 == "project") $objActSheet->setCellValue("E".$column, $v2);
                            if($k2 == "sum") $objActSheet->setCellValue("F".$column, $v2);
                        }
                        $column++;
                    }
                }
                $span++;
            }
        }
        $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
        $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='$fileName'");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    /**
     * 业务主管替船员报销
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listStaff()
    {
        $search = strtolower(input('search'));
        $where = [];
        if($search) $where['name|abbreviation'] = ['LIKE',"%$search%"];
        //获取业务主管对应的船东id
        $shipownerIds = Db::name('business')->where(['user_id'=>self::$id])->column('pid');
        if($this->is_cehck_rule) $where['id'] = ['IN',$shipownerIds];
        //获取船员
       $info = Db::name('mariner')->field('id,name,duty,id_number,owner_pool,vessel')->where($where)->select();
       foreach ($info as $k=>&$v){
            $v['name'] = $v['name']."/".$v['duty']."/".substr($v['id_number'],6,4)."-".substr($v['id_number'],10,2)."-".substr($v['id_number'],12,2);
       }
       return json($info);
    }

    /**
     * 费用承担方
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shiperList()
    {
        $info = Db::name('shipowner')->field('id,title')->select();
        $insert1 = [
            'id'=>0,
            'title'=>"SN"
        ];
        $insert2 = [
            'id'=>-1,
            "title"=>"个人承担"
        ];
        array_push($info,$insert1,$insert2);
        return json($info);
    }

    /**
     * 船员签批
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function signMariner()
    {
        $page = input('page')?input('page'):1;
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $marinerId = input('mariner_id');
        $fleet = input('fleet');
        $shipownerId = input('shipowner_id');
        $vesselId = input('vessel_id');
        $cid = input('cid');
        $status = input('status/a');
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $startTime = input('time/a')[0];
        $endTime = input('time/a')[1];
        $address = input('address/a');
        $where = [];
        if($marinerId) $where['mariner_id'] = $marinerId;
        if($fleet){
            $fleet = strtolower($fleet);
            $where['a.fleet'] = ['LIKE',"%$fleet%"];
        }
        if($shipownerId) $where['a.shipowner_id'] = $shipownerId;
        if($vesselId) $where['a.vessel_id'] = $vesselId;
        if($cid){
            $ids = Db::name('mariner')->where(['cid'=>['LIKE',"%".strtolower($cid)."%"]])->column('id');
            $where['mariner_id'] = ['IN',$ids];
        }
        if($firstTotal && !$secondTotal) $where['total'] = ['EGT',$firstTotal];
        if($firstTotal && $secondTotal) $where['total'] = ['BETWEEN',[$firstTotal,$secondTotal]];
        if($startTime && $endTime) $where['date'] = ['BETWEEN',[$startTime,$endTime]];
        if($address[0]) $where['address'] = ['IN',$address];
        if($this->isMariner) return error_data('船员不能进行此操作');
        //是否业务主管
        $shipownerIds =  Db::name('business')->where(['user_id'=>self::$id])->column('pid');
        $total = 0;
        $selfInfo = Db::name('agent')->where(['agent_id'=>self::$id,'start_date'=>['LT',formatTime(time(),'Y-m-d')],'end_date'=>['GT',formatTime(time(),'Y-m-d')],'status'=>1])->find();
        if($selfInfo) $this->user = Db::name('user')->find($selfInfo['user_id']);
        if($shipownerIds){
            //业务主管
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['b.first_result'] = ['IN',$status];
            }
            $where['shipowner_id'] = ['IN',$shipownerIds];
            $list = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b','a.id=b.pid','LEFT')
                ->join('mariner c','c.id=a.mariner_id','LEFT')
                ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                ->join('vessel e','e.id=a.vessel_id','LEFT')
                ->where($where)
                ->count();
            $info = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b','a.id=b.pid','LEFT')
                ->join('mariner c','c.id=a.mariner_id','LEFT')
                ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                ->join('vessel e','e.id=a.vessel_id','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k0=>&$v0){
               $sign = Db::name('sign_mariner')->field('pid,first_id,first_result')->where(['pid'=>$v0['id']])->find();
               if($sign['first_id']){
                   if($sign['first_result'] == 1){
                       $v0['status'] = "已通过";
                   }elseif($sign['first_result'] == 2){
                       $v0['status'] = "未通过";
                   }
               }else{
                   $v0['status'] = "待签批";
               }
               $total += $v0['total'];
            }
        }elseif($this->user['duty'] == $this->user['department'].config('leader')){
                $userIds = Db::name('user')->where(['department'=>$this->user['department']])->column('id');
                $where['f.first_id'] = ['IN',$userIds];
                $where['f.first_result'] = 1;
            $list = Db::name('expense')
                ->alias('a')
                ->field('a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.total,a.status')
                ->join('mariner c','c.id=a.mariner_id','LEFT')
                ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                ->join('vessel e','e.id=a.vessel_id','LEFT')
                ->join('sign_mariner f','f.pid=a.id','LEFT')
                ->where($where)
                ->count();
                $info = Db::name('expense')
                    ->alias('a')
                    ->field('a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.total,a.status')
                    ->join('mariner c','c.id=a.mariner_id','LEFT')
                    ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                    ->join('vessel e','e.id=a.vessel_id','LEFT')
                    ->join('sign_mariner f','f.pid=a.id','LEFT')
                    ->where($where)
                    ->order('date')
                    ->page($page,$listRows)
                    ->select();
            foreach ($info as $k=>&$v){
                $sign = Db::name('sign_mariner')->field('pid,first_id,second_id')->where(['pid'=>$v['id']])->find();
                if($sign['second_id']){
                    if($sign['second_result'] == 1){
                        $v['status'] = "已通过";
                    }elseif($sign['first_result'] == 2){
                        $v['status'] = "未通过";
                    }
                }else{
                    $v['status'] = "待签批";
                }
                $total += $v['total'];
            }
        }elseif (!$this->is_cehck_rule) {
            //超级管理员
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['status'] = ['IN',$status];
            }
            $list = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b', 'a.id=b.pid', 'LEFT')
                ->join('mariner c', 'c.id=a.mariner_id', 'LEFT')
                ->join('shipowner d', 'd.id=a.shipowner_id', 'LEFT')
                ->join('vessel e', 'e.id=a.vessel_id', 'LEFT')
                ->where($where)
                ->count();
            $info = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b', 'a.id=b.pid', 'LEFT')
                ->join('mariner c', 'c.id=a.mariner_id', 'LEFT')
                ->join('shipowner d', 'd.id=a.shipowner_id', 'LEFT')
                ->join('vessel e', 'e.id=a.vessel_id', 'LEFT')
                ->where($where)
                ->order('date')
                ->page($page, $listRows)
                ->select();
            foreach ($info as $k1 => &$v1) {
                if($v1['status'] == 0) $v1['status'] = "待签批";
                if($v1['status'] == 1) $v1['status'] = "已通过";
                if($v1['status'] == 2) $v1['status'] = "未通过";
                $total += $v1['total'];
            }
        }else{
            //财务
            $shipownerIds = Db::name('principal')->where(['user_id'=>self::$id])->column('pid');
            if(!$shipownerIds) return json([]);
            $where['shipowner_id'] = ['IN',$shipownerIds];
            $where['b.second_result'] = ['NEQ',2];
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['b.first_result'] = ['IN',$status];
            }
            $list = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b','a.id=b.pid','LEFT')
                ->join('mariner c','c.id=a.mariner_id','LEFT')
                ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                ->join('vessel e','e.id=a.vessel_id','LEFT')
                ->where($where)
                ->count();
            $info = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b','a.id=b.pid','LEFT')
                ->join('mariner c','c.id=a.mariner_id','LEFT')
                ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                ->join('vessel e','e.id=a.vessel_id','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k2=>&$v2){
                $sign = Db::name('sign_mariner')->field('pid,principal')->where(['pid'=>$v2['id']])->find();
                if($sign['principal']){
                    if($v2['status'] == 1){
                        $v2['status'] = "已通过";
                    }elseif($v2['status'] == -1){
                        $v2['status'] = "未通过";
                    }
                }else{
                    $v2['status'] = "待签批";
                }
                $total += $v2['total'];
            }
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'total'=>$total
        ];
        return json($res);
    }

    /**
     * 导出船员签批记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportSignMariner()
    {
        $marinerId = input('mariner_id');
        $fleet = input('fleet');
        $shipownerId = input('shipowner_id');
        $vesselId = input('vessel_id');
        $cid = input('cid');
        $status = input('status/a');
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $startTime = input('time/a')[0];
        $endTime = input('time/a')[1];
        $address = input('address/a');
        $where = [];
        if($marinerId) $where['mariner_id'] = $marinerId;
        if($fleet){
            $fleet = strtolower($fleet);
            $where['a.fleet'] = ['LIKE',"%$fleet%"];
        }
        if($shipownerId) $where['a.shipowner_id'] = $shipownerId;
        if($vesselId) $where['a.vessel_id'] = $vesselId;
        if($cid){
            $ids = Db::name('mariner')->where(['cid'=>['LIKE',"%".strtolower($cid)."%"]])->column('id');
            $where['mariner_id'] = ['IN',$ids];
        }
        if($firstTotal && !$secondTotal) $where['total'] = ['EGT',$firstTotal];
        if($firstTotal && $secondTotal) $where['total'] = ['BETWEEN',[$firstTotal,$secondTotal]];
        if($startTime && $endTime) $where['date'] = ['BETWEEN',[$startTime,$endTime]];
        if($address[0]) $where['address'] = ['IN',$address];
        if($this->isMariner) return error_data('船员不能进行此操作');
        //是否业务主管
        $shipownerIds =  Db::name('business')->where(['user_id'=>self::$id])->column('pid');
        $total = 0;
        $selfInfo = Db::name('agent')->where(['agent_id'=>self::$id,'start_date'=>['LT',formatTime(time(),'Y-m-d')],'end_date'=>['GT',formatTime(time(),'Y-m-d')],'status'=>1])->find();
        if($selfInfo) $this->user = Db::name('user')->find($selfInfo['user_id']);
        if($shipownerIds){
            //业务主管
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['b.first_result'] = ['IN',$status];
            }
            $where['shipowner_id'] = ['IN',$shipownerIds];
            $info = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b','a.id=b.pid','LEFT')
                ->join('mariner c','c.id=a.mariner_id','LEFT')
                ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                ->join('vessel e','e.id=a.vessel_id','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k0=>&$v0){
                $sign = Db::name('sign_mariner')->field('pid,first_id,first_result')->where(['pid'=>$v0['id']])->find();
                if($sign['first_id']){
                    if($sign['first_result'] == 1){
                        $v0['status'] = "已通过";
                    }elseif($sign['first_result'] == 2){
                        $v0['status'] = "未通过";
                    }
                }else{
                    $v0['status'] = "待签批";
                }
                $total += $v0['total'];
            }
        }elseif($this->user['duty'] == $this->user['department'].config('leader')){
            $userIds = Db::name('user')->where(['department'=>$this->user['department']])->column('id');
            $where['f.first_id'] = ['IN',$userIds];
            $where['f.first_result'] = 1;
            $info = Db::name('expense')
                ->alias('a')
                ->field('a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.total,a.status')
                ->join('mariner c','c.id=a.mariner_id','LEFT')
                ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                ->join('vessel e','e.id=a.vessel_id','LEFT')
                ->join('sign_mariner f','f.pid=a.id','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k=>&$v){
                $sign = Db::name('sign_mariner')->field('pid,first_id,second_id')->where(['pid'=>$v['id']])->find();
                if($sign['second_id']){
                    if($sign['second_result'] == 1){
                        $v['status'] = "已通过";
                    }elseif($sign['first_result'] == 2){
                        $v['status'] = "未通过";
                    }
                }else{
                    $v['status'] = "待签批";
                }
                $total += $v['total'];
            }
        }elseif (!$this->is_cehck_rule) {
            //超级管理员
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['status'] = ['IN',$status];
            }
            $info = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b', 'a.id=b.pid', 'LEFT')
                ->join('mariner c', 'c.id=a.mariner_id', 'LEFT')
                ->join('shipowner d', 'd.id=a.shipowner_id', 'LEFT')
                ->join('vessel e', 'e.id=a.vessel_id', 'LEFT')
                ->where($where)
                ->order('date')
                ->page($page, $listRows)
                ->select();
            foreach ($info as $k1 => &$v1) {
                if($v1['status'] == 0) $v1['status'] = "待签批";
                if($v1['status'] == 1) $v1['status'] = "已通过";
                if($v1['status'] == 2) $v1['status'] = "未通过";
                $total += $v1['total'];
            }
        }else{
            //财务
            $shipownerIds = Db::name('principal')->where(['user_id'=>self::$id])->column('pid');
            if(!$shipownerIds) return json([]);
            $where['shipowner_id'] = ['IN',$shipownerIds];
            $where['b.second_result'] = ['NEQ',2];
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['b.first_result'] = ['IN',$status];
            }
            $info = Db::name('expense')
                ->alias('a')
                ->field('a.id,a.date,c.name,c.duty,d.title shipowner,e.title vessel,a.fleet,a.address,a.explain,a.reason,a.total,a.status')
                ->join('sign_mariner b','a.id=b.pid','LEFT')
                ->join('mariner c','c.id=a.mariner_id','LEFT')
                ->join('shipowner d','d.id=a.shipowner_id','LEFT')
                ->join('vessel e','e.id=a.vessel_id','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k2=>&$v2){
                $sign = Db::name('sign_mariner')->field('pid,principal')->where(['pid'=>$v2['id']])->find();
                if($sign['principal']){
                    if($v2['status'] == 1){
                        $v2['status'] = "已通过";
                    }elseif($v2['status'] == -1){
                        $v2['status'] = "未通过";
                    }
                }else{
                    $v2['status'] = "待签批";
                }
                $total += $v2['total'];
            }
        }
        $res = [
            'data'=>$info,
            'total'=>$total
        ];
        return json($res);
    }

    /**
     * 员工报销签批
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function signUser()
    {
        $page = input('page');
        $listRows = input('listRows') ? input('listRows') : self::$listRows;
        $userId = input('userId');
        $abbreviation = strtolower(input('abbreviation'));
        $department = input('department');
        $duty = input('duty');
        $status = input('status/a');
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $startTimes = input('time/a')[0];
        $endTimes = input('time/a')[1];
        $address = input('address/a');
        $firstStart = input('start_date/a')[0];
        $secondStart = input('start_date/a')[1];
        $firstEnd = input('end_date/a')[0];
        $secondEnd = input('end_date/a')[1];
        $startDays = input('days/a')[0];
        $endDays = input('days/a')[1];
        $where = [];
        if ($userId) $where['user_id'] = $userId;
        if ($abbreviation) {
           $ids = Db::name('user')->where(['abbreviation'=>['LIKE',"%$abbreviation%"]])->column('id');
           $where['user_id'] = ['IN',$ids];
        }
        if ($department) {
           $ids = Db::name('user')->where(['department'=>['LIKE',"%$department%"]])->column('id');
           $where['user_id'] = ['IN',$ids];
        }
        if ($duty) {
           $ids = Db::name('user')->where(['duty'=>$duty])->column('id');
            $where['user_id'] = ['IN',$ids];
        }
        if ($firstTotal && !$secondTotal) $where['total'] = ['EGT', $firstTotal];
        if ($firstTotal && $secondTotal) $where['total'] = ['BETWEEN', [$firstTotal, $secondTotal]];
        if ($startTimes && $endTimes) $where['date'] = ['BETWEEN', [$startTimes, $endTimes]];
        if ($address) $where['address'] = ['IN', $address];
        if ($firstStart && $secondStart) $where['start_date'] = ['BETWEEN', [$firstStart, $secondStart]];
        if ($firstEnd & $secondEnd) $where['end_date'] = ['BETWEEN', [$firstEnd, $secondEnd]];
        if ($startDays && !$endDays) $where['days'] = ['EGT', $startDays];
        if ($startDays && $endDays) $where['days'] = ['BETWEEN', [$startDays, $endDays]];
        $total = 0;
        $list = 0;
        $info = [];
        $selfInfo = Db::name('agent')->where(['agent_id'=>self::$id,'start_date'=>['LT',formatTime(time(),'Y-m-d')],'end_date'=>['GT',formatTime(time(),'Y-m-d')],'status'=>1])->find();
        if($selfInfo) $this->user = Db::name('user')->find($selfInfo['user_id']);
        if ($this->user['duty'] == $this->user['department'] . config('leader')) {
            //获取部门下的所有员工
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['b.second_result'] = ['IN',$status];
            }
            $userIds = Db::name('user')->where(['department'=>$this->user['department']])->column('id');
            $where['user_id'] = ['IN',$userIds];
            $list = Db::name('user_expense')
                ->alias('a')
                ->field('a.id,a.date,d.username,d.duty,d.department,a.address,a.start_date,a.end_date,a.days,a.reason,a.explain,a.total,a.status')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('vessel c','a.vessel_id=c.id','LEFT')
                ->join('user d','a.user_id=d.id','LEFT')
                ->where($where)
                ->count();
            $info = Db::name('user_expense')
                ->alias('a')
                ->field('a.id,a.date,d.username,d.duty,d.department,a.address,a.start_date,a.end_date,a.days,a.reason,a.explain,a.total,a.status')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('vessel c','a.vessel_id=c.id','LEFT')
                ->join('user d','a.user_id=d.id','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k=>&$v){
                    $sign = Db::name('sign_user')->field('pid,first_id,principal')->where(['pid'=>$v['id']])->find();
                    if($sign['first_id']){
                        if($v['status'] == 0){
                            $v['status'] = "已通过";
                        }elseif($v['status'] == -1){
                            $v['status'] = "未通过";
                        }
                    }else{
                        $v['status'] = "待签批";
                    }
                    $total += $v['total'];
            }
        }elseif($this->user['principal'] == "是"){
            $count = Db::name('user_expense')
                ->where($where)
                ->count();
            if(!$count){
                return json([
                    'list'=>0,
                    'data'=>[],
                    'total'=>0
                ]);
            }
            $where['e.first_result'] = 1;
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['e.principal'] = ['IN',$status];
            }
            $list = Db::name('user_expense')
                ->alias('a')
                ->field('a.id,a.date,d.username,d.duty,d.department,a.address,a.start_date,a.end_date,a.days,a.reason,a.explain,a.total,a.status')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('vessel c','a.vessel_id=c.id','LEFT')
                ->join('user d','a.user_id=d.id','LEFT')
                ->join('sign_user e','e.pid=a.id','LEFT')
                ->where($where)
                ->count();
            $info = Db::name('user_expense')
                ->alias('a')
                ->field('a.id,a.date,d.username,d.duty,d.department,a.address,a.start_date,a.end_date,a.days,a.reason,a.explain,a.total,a.status')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('vessel c','a.vessel_id=c.id','LEFT')
                ->join('user d','a.user_id=d.id','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k=>&$v){
                $sign = Db::name('sign_user')->field('pid,first_id,principal')->where(['pid'=>$v['id']])->find();
                if($sign['principal']){
                    if($v['status'] == 1){
                        $v['status'] = "已通过";
                    }elseif($v['status'] == -1){
                        $v['status'] = "未通过";
                    }
                }else{
                    $v['status'] = "待签批";
                }
                $total += $v['total'];
            }
        }elseif (!$this->is_cehck_rule){
            //超级管理员
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['status'] = ['IN',$status];
            }
            $list = Db::name('user_expense')
                ->alias('a')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('vessel c','a.vessel_id=c.id','LEFT')
                ->where($where)
                ->count();
           $info = Db::name('user_expense')
                ->alias('a')
               ->field('a.id,a.date,d.username,d.duty,d.department,a.address,a.start_date,a.end_date,a.days,a.reason,a.explain,a.total,a.status')
               ->join('shipowner b','a.shipowner_id=b.id','LEFT')
               ->join('vessel c','a.vessel_id=c.id','LEFT')
               ->join('user d','a.user_id=d.id','LEFT')
               ->where($where)
               ->page($page,$listRows)
               ->select();
            foreach ($info as $k1 => &$v1) {
                if($v1['status'] == 0) $v1['status'] = "待签批";
                if($v1['status'] == 1) $v1['status'] = "已通过";
                if($v1['status'] == 2) $v1['status'] = "未通过";
                $total += $v1['total'];
            }
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'total'=>$total
        ];
        return json($res);
    }

    /**
     * 导出员工签批记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportSignUser()
    {
        $userId = input('userId');
        $abbreviation = strtolower(input('abbreviation'));
        $department = input('department');
        $duty = input('duty');
        $status = input('status/a');
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $startTimes = input('time/a')[0];
        $endTimes = input('time/a')[1];
        $address = input('address/a');
        $firstStart = input('start_date/a')[0];
        $secondStart = input('start_date/a')[1];
        $firstEnd = input('end_date/a')[0];
        $secondEnd = input('end_date/a')[1];
        $startDays = input('days/a')[0];
        $endDays = input('days/a')[1];
        $where = [];
        if ($userId) $where['user_id'] = $userId;
        if ($abbreviation) {
            $ids = Db::name('user')->where(['abbreviation'=>['LIKE',"%$abbreviation%"]])->column('id');
            $where['user_id'] = ['IN',$ids];
        }
        if ($department) {
            $ids = Db::name('user')->where(['department'=>['LIKE',"%$department%"]])->column('id');
            $where['user_id'] = ['IN',$ids];
        }
        if ($duty) {
            $ids = Db::name('user')->where(['duty'=>$duty])->column('id');
            $where['user_id'] = ['IN',$ids];
        }
        if ($firstTotal && !$secondTotal) $where['total'] = ['EGT', $firstTotal];
        if ($firstTotal && $secondTotal) $where['total'] = ['BETWEEN', [$firstTotal, $secondTotal]];
        if ($startTimes && $endTimes) $where['date'] = ['BETWEEN', [$startTimes, $endTimes]];
        if ($address) $where['address'] = ['IN', $address];
        if ($firstStart && $secondStart) $where['start_date'] = ['BETWEEN', [$firstStart, $secondStart]];
        if ($firstEnd & $secondEnd) $where['end_date'] = ['BETWEEN', [$firstEnd, $secondEnd]];
        if ($startDays && !$endDays) $where['days'] = ['EGT', $startDays];
        if ($startDays && $endDays) $where['days'] = ['BETWEEN', [$startDays, $endDays]];
        $total = 0;
        $info = [];
        $selfInfo = Db::name('agent')->where(['agent_id'=>self::$id,'start_date'=>['LT',formatTime(time(),'Y-m-d')],'end_date'=>['GT',formatTime(time(),'Y-m-d')],'status'=>1])->find();
        if($selfInfo) $this->user = Db::name('user')->find($selfInfo['user_id']);
        if ($this->user['duty'] == $this->user['department'] . config('leader')) {
            //获取部门下的所有员工
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['b.second_result'] = ['IN',$status];
            }
            $userIds = Db::name('user')->where(['department'=>$this->user['department']])->column('id');
            $where['user_id'] = ['IN',$userIds];
            $info = Db::name('user_expense')
                ->alias('a')
                ->field('a.id,a.date,d.username,d.duty,d.department,a.address,a.start_date,a.end_date,a.days,a.reason,a.explain,a.total,a.status')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('vessel c','a.vessel_id=c.id','LEFT')
                ->join('user d','a.user_id=d.id','LEFT')
                ->where($where)
                ->order('date')
                ->select();
            foreach ($info as $k=>&$v){
                $sign = Db::name('sign_user')->field('pid,first_id,principal')->where(['pid'=>$v['id']])->find();
                if($sign['first_id']){
                    if($v['status'] == 0){
                        $v['status'] = "已通过";
                    }elseif($v['status'] == -1){
                        $v['status'] = "未通过";
                    }
                }else{
                    $v['status'] = "待签批";
                }
                $total += $v['total'];
            }
        }elseif($this->user['principal'] == "是"){
            $where['e.first_result'] = 1;
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['e.principal'] = ['IN',$status];
            }
            $info = Db::name('user_expense')
                ->alias('a')
                ->field('a.id,a.date,d.username,d.duty,d.department,a.address,a.start_date,a.end_date,a.days,a.reason,a.explain,a.total,a.status')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('vessel c','a.vessel_id=c.id','LEFT')
                ->join('user d','a.user_id=d.id','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k=>&$v){
                $sign = Db::name('sign_user')->field('pid,first_id,principal')->where(['pid'=>$v['id']])->find();
                if($sign['principal']){
                    if($v['status'] == 1){
                        $v['status'] = "已通过";
                    }elseif($v['status'] == -1){
                        $v['status'] = "未通过";
                    }
                }else{
                    $v['status'] = "待签批";
                }
                $total += $v['total'];
            }
        }elseif (!$this->is_cehck_rule){
            //超级管理员
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['status'] = ['IN',$status];
            }
            $info = Db::name('user_expense')
                ->alias('a')
                ->field('a.id,a.date,d.username,d.duty,d.department,a.address,a.start_date,a.end_date,a.days,a.reason,a.explain,a.total,a.status')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('vessel c','a.vessel_id=c.id','LEFT')
                ->join('user d','a.user_id=d.id','LEFT')
                ->where($where)
                ->select();
            foreach ($info as $k1 => &$v1) {
                if($v1['status'] == 0) $v1['status'] = "待签批";
                if($v1['status'] == 1) $v1['status'] = "已通过";
                if($v1['status'] == 2) $v1['status'] = "未通过";
                $total += $v1['total'];
            }
        }
        $res = [
            'data'=>$info,
            'total'=>$total
        ];
        return json($res);
    }

    /**
     * 办公室费用签批
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function signOffice()
    {
        $page = input('page') ? input('page') : 1;
        $listRows = input('listRows') ? input('listRows') : self::$listRows;
        $userId = input('userId');
        $username = input('username');
        $abbreviation = strtolower(input('abbreviation'));
        $department = input('department');
        $duty = input('duty');
        $status = input('status/a');
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $startTime = input('time/a')[0];
        $endTime = input('time/a')[1];
        $address = input('address/a');
        $project = input('project/a');
        $where = [];
        if($username){
           $userIds = Db::name('user')->where(['username'=>['LIKE',"%$username%"]])->column('id');
            $where['user_id'] = $userIds;
        }
        if ($userId) $where['user_id'] = $userId;
        if ($abbreviation) {
            $ids = Db::name('user')->where(['abbreviation' => ['LIKE', "%$abbreviation%"]])->column('id');
            $where['user_id'] = ['IN', $ids];
        }
        if ($department) {
            $ids = Db::name('user')->where(['department' => $department])->column('id');
            $where['user_id'] = ['IN', $ids];
        }
        if ($duty) {
            $ids = Db::name('user')->where(['duty' => $duty])->column('id');
            $where['user_id'] = ['IN', $ids];
        }
        if ($firstTotal && !$secondTotal) $where['total'] = ['EGT', $firstTotal];
        if ($firstTotal && $secondTotal) $where['total'] = ['BETWEEN', [$firstTotal, $secondTotal]];
        if ($startTime && $endTime) $where['date'] = ['BETWEEN', [$startTime, $endTime]];
        if ($address) $where['address'] = ['IN', $address];
        if ($project[0]) {
            $ids = Db::name('office_expense_data')->where(['project', ['IN', $project]])->column('pid');
            $where['user_id'] = $ids;
        }
        $first_total = 0;
        $second_total = 0;
        $selfInfo = Db::name('agent')->where(['agent_id'=>self::$id,'start_date'=>['LT',formatTime(time(),'Y-m-d')],'end_date'=>['GT',formatTime(time(),'Y-m-d')],'status'=>1])->find();
        if($selfInfo) $this->user = Db::name('user')->find($selfInfo['user_id']);
        if ($this->user['duty'] == $this->user['department'] . config('leader')) {
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['e.principal'] = ['IN',$status];
            }
            $list = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('user b', 'a.user_id = b.id', 'LEFT')
                ->join('shipowner c', 'c.id = a.shipowner_id', 'LEFT')
                ->join('sign_office d', 'd.pid = a.id', 'LEFT')
                ->where($where)
                ->count();
            $info = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('user b', 'a.user_id = b.id', 'LEFT')
                ->join('shipowner c', 'c.id = a.shipowner_id', 'LEFT')
                ->join('sign_office d', 'd.pid = a.id', 'LEFT')
                ->where($where)
                ->order('date')
                ->page($page, $listRows)
                ->select();
            $first_total = 0;
            $second_total = 0;
            foreach ($info as $k => &$v) {
                $v['project'] = Db::name('office_expense_data')->field('project,total sum')->where(['pid' => $v['id']])->select();
                foreach ($v['project'] as $k1 => $v1) {
                    $first_total += $v1['sum'];
                }
                $second_total += $v['total'];
                $sign = Db::name('sign_office')->field('pid,first_id,principal')->where(['pid' => $v['id']])->find();
                if ($sign['first_result']) {
                    if ($v['status'] == 1) $v['status'] = "已通过";
                    if ($v['status'] == 2) $v['status'] = "未通过";
                } else {
                    $v['status'] = "待签批";
                }
            }
        } elseif ($this->user['department'] == config('department') && $this->user['principal'] == "是") {
            $where['first_result'] = ['NEQ',0];
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['d.principal_result'] = ['IN',$status];
            }
            $list = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('user b', 'a.user_id = b.id', 'LEFT')
                ->join('shipowner c', 'c.id = a.shipowner_id', 'LEFT')
                ->join('sign_office d', 'd.pid = a.id', 'LEFT')
                ->where($where)
                ->count();
            $info = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('user b', 'a.user_id = b.id', 'LEFT')
                ->join('shipowner c', 'c.id = a.shipowner_id', 'LEFT')
                ->join('sign_office d', 'd.pid = a.id', 'LEFT')
                ->where($where)
                ->order('date')
                ->page($page, $listRows)
                ->select();
            foreach ($info as $k => &$v) {
                $v['project'] = Db::name('office_expense_data')->field('project,total sum')->where(['pid' => $v['id']])->select();
                foreach ($v['project'] as $k1 => $v1) {
                    $first_total += $v1['sum'];
                }
                $second_total += $v['total'];
                $sign = Db::name('sign_office')->field('pid,first_id,principal')->where(['pid' => $v['id']])->find();
                if ($sign['principal_result']) {
                    if ($v['status'] == 1) $v['status'] = "已通过";
                    if ($v['status'] == 2) $v['status'] = "未通过";
                } else {
                    $v['status'] = "待签批";
                }
            }
        }elseif (!$this->is_cehck_rule){
            //超级管理员
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['status'] = ['IN',$status];
            }
            $list = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('shipowner b','a.shipowner_id=b.id','LEFT')
                ->join('sign_office c','a.id=c.pid','LEFT')
                ->where($where)
                ->count();
            $info = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('user b', 'a.user_id = b.id', 'LEFT')
                ->join('sign_office c','a.id=c.pid','LEFT')
                ->where($where)
                ->order('date')
                ->page($page,$listRows)
                ->select();
            foreach ($info as $k2 => &$v2) {
                $v2['project'] = Db::name('office_expense_data')->field('project,total sum')->where(['pid' => $v2['id']])->select();
                foreach ($v2['project'] as $k1 => $v1) {
                    $first_total += $v1['sum'];
                }
                if($v2['status'] == 0) $v2['status'] = "待签批";
                if($v2['status'] == 1) $v2['status'] = "已通过";
                if($v2['status'] == 2) $v2['status'] = "未通过";
                $second_total += $v2['total'];
            }
        }else{
            $list = 0;
            $info = [];
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'first_total'=>$first_total,
            'second_total'=>$second_total
            ];
        return json($res);
    }

    /**
     *导出办公费用签批记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportSignOffice()
    {
        $userId = input('userId');
        $username = input('username');
        $abbreviation = strtolower(input('abbreviation'));
        $department = input('department');
        $duty = input('duty');
        $status = input('status/a');
        $firstTotal = input('total/a')[0];
        $secondTotal = input('total/a')[1];
        $startTime = input('time/a')[0];
        $endTime = input('time/a')[1];
        $address = input('address/a');
        $project = input('project/a');
        $where = [];
        if($username){
            $userIds = Db::name('user')->where(['username'=>['LIKE',"%$username%"]])->column('id');
            $where['user_id'] = $userIds;
        }
        if ($userId) $where['user_id'] = $userId;
        if ($abbreviation) {
            $ids = Db::name('user')->where(['abbreviation' => ['LIKE', "%$abbreviation%"]])->column('id');
            $where['user_id'] = ['IN', $ids];
        }
        if ($department) {
            $ids = Db::name('user')->where(['department' => $department])->column('id');
            $where['user_id'] = ['IN', $ids];
        }
        if ($duty) {
            $ids = Db::name('user')->where(['duty' => $duty])->column('id');
            $where['user_id'] = ['IN', $ids];
        }
        if ($firstTotal && !$secondTotal) $where['total'] = ['EGT', $firstTotal];
        if ($firstTotal && $secondTotal) $where['total'] = ['BETWEEN', [$firstTotal, $secondTotal]];
        if ($startTime && $endTime) $where['date'] = ['BETWEEN', [$startTime, $endTime]];
        if ($address) $where['address'] = ['IN', $address];
        if ($project[0]) {
            $ids = Db::name('office_expense_data')->where(['project', ['IN', $project]])->column('pid');
            $where['user_id'] = $ids;
        }
        $first_total = 0;
        $second_total = 0;
        $selfInfo = Db::name('agent')->where(['agent_id'=>self::$id,'start_date'=>['LT',formatTime(time(),'Y-m-d')],'end_date'=>['GT',formatTime(time(),'Y-m-d')],'status'=>1])->find();
        if($selfInfo) $this->user = Db::name('user')->find($selfInfo['user_id']);
        if ($this->user['duty'] == $this->user['department'] . config('leader')) {
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['e.principal'] = ['IN',$status];
            }
            $info = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('user b', 'a.user_id = b.id', 'LEFT')
                ->join('shipowner c', 'c.id = a.shipowner_id', 'LEFT')
                ->join('sign_office d', 'd.pid = a.id', 'LEFT')
                ->where($where)
                ->order('date')
                ->select();
            $first_total = 0;
            $second_total = 0;
            foreach ($info as $k => &$v) {
                $v['project'] = Db::name('office_expense_data')->field('project,total sum')->where(['pid' => $v['id']])->select();
                foreach ($v['project'] as $k1 => $v1) {
                    $first_total += $v1['sum'];
                }
                $second_total += $v['total'];
                $sign = Db::name('sign_office')->field('pid,first_id,principal')->where(['pid' => $v['id']])->find();
                if ($sign['first_result']) {
                    if ($v['status'] == 1) $v['status'] = "已通过";
                    if ($v['status'] == 2) $v['status'] = "未通过";
                } else {
                    $v['status'] = "待签批";
                }
            }
        } elseif ($this->user['department'] == config('department') && $this->user['principal'] == "是") {
            $where['first_result'] = ['NEQ',0];
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['d.principal_result'] = ['IN',$status];
            }
            $info = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('user b', 'a.user_id = b.id', 'LEFT')
                ->join('shipowner c', 'c.id = a.shipowner_id', 'LEFT')
                ->join('sign_office d', 'd.pid = a.id', 'LEFT')
                ->where($where)
                ->order('date')
                ->page($page, $listRows)
                ->select();
            foreach ($info as $k => &$v) {
                $v['project'] = Db::name('office_expense_data')->field('project,total sum')->where(['pid' => $v['id']])->select();
                foreach ($v['project'] as $k1 => $v1) {
                    $first_total += $v1['sum'];
                }
                $second_total += $v['total'];
                $sign = Db::name('sign_office')->field('pid,first_id,principal')->where(['pid' => $v['id']])->find();
                if ($sign['principal_result']) {
                    if ($v['status'] == 1) $v['status'] = "已通过";
                    if ($v['status'] == 2) $v['status'] = "未通过";
                } else {
                    $v['status'] = "待签批";
                }
            }
        }elseif (!$this->is_cehck_rule){
            //超级管理员
            if($status[0]){
                foreach ($status as &$v){
                    if($v == -1) $v = 2;
                }
                $where['status'] = ['IN',$status];
            }
            $info = Db::name('office_expense')
                ->alias('a')
                ->field('a.id,a.date,b.username,b.duty,b.department,a.address,a.total,a.status')
                ->join('user b', 'a.user_id = b.id', 'LEFT')
                ->join('sign_office c','a.id=c.pid','LEFT')
                ->where($where)
                ->order('date')
                ->select();
            foreach ($info as $k2 => &$v2) {
                $v2['project'] = Db::name('office_expense_data')->field('project,total sum')->where(['pid' => $v2['id']])->select();
                foreach ($v2['project'] as $k1 => $v1) {
                    $first_total += $v1['sum'];
                }
                if($v2['status'] == 0) $v2['status'] = "待签批";
                if($v2['status'] == 1) $v2['status'] = "已通过";
                if($v2['status'] == 2) $v2['status'] = "未通过";
                $second_total += $v2['total'];
            }
        }else{
            $list = 0;
            $info = [];
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'first_total'=>$first_total,
            'second_total'=>$second_total
        ];
        return json($res);
    }

    /**
     * 船员签批详情
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function detailMariner()
    {
        $id = input('id');
        $show = 0;
        $editor = 0;
        //业务主管可以编辑报销单
        $shipownerIds1 =  Db::name('business')->where(['user_id'=>self::$id])->column('pid');
        //财务负责人负责的船东
        $shipownerIds2 = Db::name('principal')->where(['user_id'=>self::$id])->column('pid');
        $main = Db::name('expense')
            ->where(['id'=>$id])
            ->find();
        //查看后取消通知
        if (self::$id == $main['mariner_id']) Db::name('expense')->where(['id'=>$id])->update(['warn'=>0]);
        if(in_array($main['shipowner_id'],$shipownerIds1)) $editor = 1;
        if(in_array($main['shipowner_id'],$shipownerIds2) || !$this->is_cehck_rule) $show = 1;
        $option = Db::name('expense_option')->where(['pid'=>$id])->find();
        $else_option = [];
        if($option){
            foreach ($option as $k=>$v){
                if($k == "remark" || $k == "else" || $k=="pid" || $k=="assume"){
                    $else_option[$k] = $v;
                    continue;
                }
                $optionData = explode(',',$v);
                $option[$k] = $optionData;
                $else_option[$k] = [$optionData[0],Db::name('supplier')->where(['id'=>$optionData[1]])->value('title')];
            }
        }
        $data = Db::name('expense_data')
            ->where(['pid'=>$id])
            ->select();
        $assume = Db::name('expense_assume')
            ->where(['pid'=>$id])
            ->select();
        $userIds = Db::name('principal')->where(['pid'=>$id])->column('user_id');
        $borrowInfo = [];
        $socialInfo = [];
        $suranceInfo = [];
        if(in_array(self::$id,$userIds) || !$this->is_cehck_rule){
            //获取船员截至当前欠款信息
            $borrowInfo1 = Db::name('borrow')->field('currency,SUM(amount) rmb_debt,SUM(repayment) rmb_receipt')->where(['mariner_id'=>$main['mariner_id'],'reason'=>['NEQ',config('project')],"currency"=>"人民币"])->find();
            $borrowInfo2 = Db::name('borrow')->field('currency,SUM(amount) us_debt,SUM(repayment) us_receipt')->where(['mariner_id'=>$main['mariner_id'],'reason'=>['NEQ',config('project')],"currency"=>"美元"])->find();
            $borrowInfo = [
                'rmb_debt'=>$borrowInfo1['rmb_debt'],
                'rmb_receipt'=>$borrowInfo1['rmb_receipt'],
                'us_receipt'=>$borrowInfo2['us_receipt'],
                'us_debt'=>$borrowInfo2['us_debt']

            ];
            $socialInfo = Db::name('social_info')->field('SUM(debt) rmb_debt,SUM(receipt) rmb_receipt')->where(['mariner_id'=>$main['mariner_id']])->find();
            $suranceInfo = Db::name('borrow')->field('SUM(amount) rmb_debt,SUM(repayment) rmb_receipt')->where(['mariner_id'=>$main['mariner_id'],'reason'=>['EQ',config('project')]])->find();
           $debtInfo = Db::name('expense_debt')->where(['pid'=>$id])->select();
           if($debtInfo[0]){
               foreach ($debtInfo as $k1=>$v1){
                   if($v1['project'] == "借款") $borrowInfo = $v1;
                   if($v1['project'] == "社保欠款") $socialInfo = $v1;
                   if($v1['project'] == "意外险欠款") $suranceInfo = $v1;
               }
           }
        }
        //获取签批人信息
       $signInfo = Db::name('sign_mariner')->where(['pid'=>$id])->find();
        $sign = [
            'manager'=>Db::name('user')->where(['id'=>$signInfo['first_id']])->value('username'),
            'lastManager'=>Db::name('user')->where(['id'=>$signInfo['second_id']])->value('username'),
            'financeManager'=>Db::name('user')->where(['id'=>$signInfo['principal']])->value('username'),
        ];
        $res = [
            'main'=>$main,
            'option'=>$option,
            'else_option'=>$else_option,
            'data'=>$data,
            'assume'=>$assume,
            'show'=>$show,
            'editor'=>$editor,
            'borrow'=>$borrowInfo,
            'social'=>$socialInfo,
            'surance'=>$suranceInfo,
            'checkin'=>$sign
        ];
        return json($res);
    }

    /**
     * 员工签批详情
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public  function detailUser()
    {
        $id = input('id');
        $main = Db::name('user_expense')
            ->alias('a')
            ->field('a.*,b.department')
            ->join('user b','a.user_id=b.id','LEFT')
            ->where(['a.id'=>$id])
            ->find();
        //取消提醒
        if (self::$id == $main['user_id']) Db::name('user_expense')->where(['id'=>$id])->update(['warn'=>0]);
        //部门经理
        $show = 0;
        if($this->user['principal'] == "是" || !$this->is_cehck_rule) $show = 1;
        $option = Db::name('user_expense_option')->where(['pid'=>$id])->find();
        $else_option = [];
        if($option) {
            foreach ($option as $k => $v) {
                if ($k == "partner" || $k == "else" || $k == "pid") {
                    $else_option[$k] = $v;
                    continue;
                }
                $optionData = explode(',',$v);
                $option[$k] = $optionData;
                $else_option[$k] =  [$optionData[0],Db::name('supplier')->where(['id'=>$optionData[1]])->value('title')];;
            }
        }
        $data = Db::name('user_expense_data')
                ->where(['pid' => $id])
                ->select();
        $assume = Db::name('user_expense_assume')
                ->where(['pid' => $id])
                ->find();
        $principal = [];
        if($show == 1) $principal = Db::name('user_expense_principal')->where(['pid'=>$id])->select();
        //获取签批人信息
        $signInfo = Db::name('sign_user')->where(['pid'=>$id])->find();
        $sign = [
            'lastManager'=>Db::name('user')->where(['id'=>$signInfo['first_id']])->value('username'),
            'financeManager'=>Db::name('user')->where(['id'=>$signInfo['principal']])->value('username'),
        ];
        $res = [
                'main' => $main,
                'option' => $option,
                'else_option' => $else_option,
                'data' => $data,
                'assume' => $assume,
                'principal' => $principal,
                'show' => $show,
                'checkin'=>$sign
            ];
            return json($res);
    }

    /**
     * 办公室签批详情
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function detailOffice()
    {
        $id = input('id');
        $main = Db::name('office_expense')
            ->alias('a')
            ->field('a.*,b.username')
            ->join('user b','a.user_id=b.id','LEFT')
            ->where(['a.id'=>$id])
            ->find();
        //取消提醒
        if (self::$id == $main['user_id']) Db::name('office_expense')->where(['id'=>$id])->update(['warn'=>0]);
        $show = 0;
        $principal = [];
        if($this->user['principal'] == "是" || !$this->is_cehck_rule) $show = 1;
        $data = Db::name('office_expense_data')->where(['pid'=>$id])->select();
        //获取签批人信息
        $signInfo = Db::name('sign_office')->where(['pid'=>$id])->find();
        $sign = [
            'lastManager'=>Db::name('user')->where(['id'=>$signInfo['first_id']])->value('username'),
            'financeManager'=>Db::name('user')->where(['id'=>$signInfo['principal']])->value('username'),
        ];
        $res = [
            'main'=>$main,
            'data'=>$data,
            'principal'=>$principal,
            'show'=>$show,
            'checkin'=>$sign
        ];
        return json($res);
    }

    /**
     * 船员签批
     * @return \think\response\Json
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sureMariner()
    {
        $id = input('id');
        $value = input('sure'); //1通过 2拒绝
        $type = input('type');// 1现金  2转账
        $really = input('really');// 实际报销金额
        //是否业务主管
        $shipownerIds1 =  Db::name('business')->where(['user_id'=>self::$id])->column('pid');
        $info = Db::name('expense')
            ->alias('a')
            ->where(['id'=>$id])
            ->find();
        Db::startTrans();
        try{
            $shipownerIds2 =  Db::name('principal')->where(['user_id'=>self::$id])->column('pid');
            $sign = Db::name('sign_mariner')->where(['pid'=>$id])->find();
            if(in_array($info['shipowner_id'],$shipownerIds1)){
                //业务主管签批
                $update = [
                    'pid'=>$id,
                    'first_id'=>self::$id,
                    'first_result'=>$value
                ];
                Db::name('sign_mariner')->update($update);
                if($value == -1){
                    Db::name('expense')->where(['id'=>$id])->update(['status'=>$value]);
                }
            }elseif ($this->user['duty'] == $this->user['department'].config('leader')){
                if($sign['second_result']) return error_data('请勿重复签批');
                if($info['total'] < 2000)  return error_data('未超过2000元无需部门经理签批');
                if($value == -1){
                    Db::name('expense')->where(['id'=>$id])->update(['status'=>$value]);
                }
                Db::name('sign_mariner')->where(['pid'=>$id])->update(['second_id'=>self::$id,'second_result'=>$value]);
            }elseif (in_array($info['shipowner_id'],$shipownerIds2) || !$this->is_cehck_rule){
                $data = input('data/a');
                if($sign['principal']) return error_data('请勿重复签批');
                Db::name('sign_mariner')->where(['pid'=>$id])->update(['principal'=>self::$id,'principal_result'=>$value]);
                Db::name('expense')->where(['id'=>$id])->update(['warn'=>1,'status'=>$value,'really'=>$really,'type'=>$type,'over_date'=>formatTime(time(),'Y-m-d')]);
                //生成应付供应商金额
                $optionInfo = Db::name('expense_option')->field('traffic,hotel,city,examination,train,subsidy')->where(['pid'=>$id])->find();
                $dataInfo = Db::name('expense_data')
                    ->field('SUM(traffic_cost) traffic,SUM(hotel_cost) hotel,SUM(city_cost) city,SUM(examination_cost) examination,SUM(train_cost) train,SUM(subsidy_cost) subsidy')
                    ->where(['pid'=>$id])
                    ->find();
                foreach ($data as $k=>&$v){
                    $v['pid'] = $id;
                }
                Db::name('expense_debt')->insertAll($data);
                $insert = [];
                if($optionInfo){
                    foreach ($optionInfo as $k1=>$v1){
                        $pid = explode(',',$v1)[1];
                        if(!$pid) continue;
                        foreach ($dataInfo as $k2=>$v2){
                            if($k2 == $k1) {
                                $insert[] = [
                                    'pid'=>$pid,
                                    'date'=>date('Y-m-d',time()),
                                    'month'=>date('Y-m',time()),
                                    'pay_before'=>$v2,
                                    'pay_after'=>0
                                ];
                            }
                        }
                    }
                }
                Db::name('supplier_info')->insertAll($insert);
            }
            Db::commit();
            return ok_data();
        }catch(\Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 员工签批
     * @return \think\response\Json
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sureUser()
    {
        $id = input('id');
        $value = input('sure'); //1通过 2拒绝
        $type = input('type');// 1现金  2转账
        $really = input('really');// 实际报销金额
        $info = Db::name('user_expense')
            ->where(['id'=>$id])
            ->find();
        //报销人信息
        $userInfo = Db::name('user')->field('id,department')->where(['id'=>$info['user_id']])->find();
        Db::startTrans();
        try{
            $sign = Db::name('sign_user')->where(['pid'=>$id])->find();
            if ($this->user['duty'] == $userInfo['department'].config('leader')){
                //是否设置
                //部门经理
                if($sign['first_id']) return error_data('请勿重复签批');
                if($value == 2)Db::name('user_expense')->where(['id'=>$id])->update(['status'=>$value]);
                Db::name('sign_user')->where(['pid'=>$id])->update(['first_id'=>self::$id,'first_result'=>$value]);
            }elseif ($this->user['principal'] == "是" || !$this->is_cehck_rule){
                //财务负责人
                $data = input('data/a');
                foreach ($data as $k=>&$v){
                    $v['pid'] = $id;
                }
                Db::name('user_expense_principal')->insertAll($data);
                if($sign['principal']) return error_data('请勿重复签批');
                Db::name('user_expense')->where(['id'=>$id])->update(['warn'=>1,'status'=>$value,'really'=>$really,'type'=>$type]);
                Db::name('sign_user')->where(['pid'=>$id])->update(['principal'=>self::$id]);
                //生成应付供应商金额
                $optionInfo = Db::name('user_expense_option')->field('traffic,hotel,city,travel,meal,exchange,office,communication,post')->where(['pid'=>$id])->find();
                $dataInfo = Db::name('user_expense_data')
                    ->field('SUM(traffic_cost) traffic,SUM(hotel_cost) hotel,SUM(city_cost) city,SUM(travel_cost) travel,SUM(meal_cost) meal,SUM(exchange_cost) exchange,SUM(office_cost) office,SUM(communication_cost) communication,SUM(post_cost) post')
                    ->where(['pid'=>$id])
                    ->find();
                $insert = [];
                if($optionInfo){
                    foreach ($optionInfo as $k1=>$v1){
                        $pid = explode(',',$v1)[1];
                        if(!$pid) continue;
                        foreach ($dataInfo as $k2=>$v2){
                            if($k2 == $k1) {
                                $insert[] = [
                                    'pid'=>$pid,
                                    'date'=>date('Y-m-d',time()),
                                    'month'=>date('Y-m',time()),
                                    'pay_before'=>$v2,
                                    'pay_after'=>0
                                ];
                            }
                        }
                    }
                    Db::name('supplier_info')->insertAll($insert);
                }
            }
            Db::commit();
            return ok_data();
        }catch(\Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 办公室签批
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function sureOffice()
    {
        $id = input('id');
        $value = input('sure'); //1通过 2拒绝
        $info = Db::name('office_expense')->find($id);
        $sign = Db::name('sign_office')->where(['pid'=>$id])->find();
        //报销人信息
        Db::startTrans();
        try {
            $userInfo = Db::name('user')->field('id,department')->where(['id' => $info['user_id']])->find();
            if ($this->user['duty'] == $userInfo['department'] . config('leader')) {
                if ($sign['first_result']) return error_data('请勿重复签批');
                if ($value == 2) Db::name('office_expense')->where(['id' => $id])->update(['status' => $value]);
                Db::name('sign_office')->where(['pid' => $id])->update(['first_id' => self::$id, 'first_result' => $value]);
            } elseif ($this->user['principal'] == "是" || !$this->is_cehck_rule) {
                $type = input('type');// 1现金  2转账
                $really = input('really');// 实际报销金额
                $borrow = input('borrow'); //借款金额
                if ($sign['principal_result']) return error_data('请勿重复签批');
                Db::name('office_expense')->where(['id' => $id])->update(['warn'=>1,'status' => $value, 'type' => $type, 'really' => $really, 'borrow' => $borrow]);
                Db::name('sign_office')->where(['pid' => $id])->update(['principal' => self::$id, 'principal_result' => $value]);
            }
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            return error_data();
        }
    }

    /**
     *判断电子发票编号是否重复
     * @return \think\response\Json
     */
    public function repeatNumber()
    {
        $number = input('number');
        $repeat = Db::name('invoice')->where(['number'=>$number])->value('pid');
        if($repeat) return error_data('电子发票编号重复');
        return ok_data();
    }
}