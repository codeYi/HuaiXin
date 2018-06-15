<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018-04-19 16:36
 */
namespace app\api\controller;
use app\api\common\Base;
use PhpOffice\PhpSpreadsheet\Calculation\Exception;
use think\Db;
use think\Loader;
/**
 * 船员控制器
 * Class Mariner
 * @package app\api\controller
 */
class Mariner extends Base
{
    /**
     * 添加船东
     * @return \think\response\Json
     */
    public function addShipowner()
    {
        $data = input('post.');
        if(empty($data['title']) || empty($data['alias'])) return error_data('客户名称及简称不能为空');
        $id = Db::name('shipowner')->where(['title'=>$data['title']])->value('id');
        if($id) return error_data('请勿重复添加客户');
        if($data['developDate']){
            $data['developDate'] = formatTime($data['developDate'],'Y-m-d');
        }else{
            $data['developDate'] = null;
        }
        if(empty($data['business'])) return error_data('业务主管项不能为空');
        if(empty($data['principal'])) return error_data('财务负责人不能为空');
        $insert = [
            'title'=>$data['title'],
            'alias'=>$data['alias'],
            'attribute'=>$data['attribute'],
            'develop_date'=>$data['developDate'],
            'group'=>$data['group'],
            'time'=>formatTime(time(),'Y-m-d')
        ];
        $pid = Db::name('shipowner')->insertGetId($insert);
        $business = [];
        $principal = [];
        foreach($data['business'] as $k=>$v){
            $business[] = [
                'pid'=>$pid,
                'user_id'=>$v
            ];
        }
        foreach ($data['principal'] as $k=>$v){
            $principal[] = [
                'pid'=>$pid,
                'user_id'=>$v
            ];
        }
        Db::startTrans();
        try{
            Db::name("principal")->insertAll($principal);
            Db::name('business')->insertAll($business);
            Db::commit();
            return ok_data('添加成功');
        }catch (\Exception $e){
            Db::rollback();
            return error_data('添加失败');
        }
    }

    /**
     * 船东列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listShipowner()
    {
        $search = input('title');
        $page = input('page');
        $alias = input('alias');
        $startTime = input('time/a')[0];
        $endtTime = input('time/a')[1];
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $where = [];
        if($search) $where['title'] = ['LIKE',"%$search%"];
        if($alias) $where['alias'] = ["LIKE","$alias%"];
        if(input('attribute')) $where['attribute'] = input('attribute');
        if(input('group')) $where['group'] = input('group');
        if($startTime && $endtTime) $where['develop_date'] = ['BETWEEN',[$startTime,$endtTime]];
        $list = Db::name('shipowner')
            ->where($where)
            ->order('develop_date')
            ->count();
        $info = Db::name('shipowner')
            ->where($where)
            ->order('develop_date')
            ->page($page,$listRows)
            ->select();
        foreach ($info as $k=>&$v){
            $userId = Db::name('business')->where(['pid'=>$v['id']])->column('user_id');
            $principalId = Db::name('principal')->where(['pid'=>$v['id']])->column('user_id');
            $userInfo = Db::name('user')->field('id,CONCAT_WS("-",department,username) username')->where(['id'=>['IN',$userId]])->select();
            $v['business'] = "";
            foreach ($userInfo as $k1=>$v1){
                $v['business'] .= ",".$v1['username'];
            }
            $v['business'] = ltrim($v['business'],',');
            $principal = Db::name('user')->where(['id'=>['IN',$principalId]])->column('username');
            $v['principal'] = implode('/',$principal);
        }
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 船东详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailShipowner()
    {
        $id = input('id');
        $info = Db::name('shipowner')->find($id);
        $businessId = Db::name('business')->where(['pid'=>$id])->column('user_id');
        $principalId = Db::name('principal')->where(['pid'=>$id])->column('user_id');
        $business = Db::name('user')->field('id,username')->where(['id'=>['IN',$businessId]])->select();
        $principal = Db::name('user')->field('id,username')->where(['id'=>['IN',$principalId]])->select();
        $res = [
            'data'=>$info,
            'business'=>$business,
            'principal'=>$principal
        ];
        return json($res);
    }

    /**
     * 更新船东
     * @return \think\response\Json
     */
    public function editShipowner()
    {
        $data = input('post.');
        if(empty($data['title'])) return error_data('客户名称不能为空');
        $id = Db::name('shipowner')->where(['title'=>$data['title'],'id'=>['NEQ',$data['id']]])->value('id');
        if($id) return error_data('请勿重复添加客户');
        if($data['developDate']){
            $data['developDate'] = formatTime($data['developDate'],'Y-m-d');
        }else{
            $data['developDate'] = null;
        }
        if(empty($data['business'])) return error_data('业务主管项不能为空');
        if(empty($data['principal'])) return error_data('财务负责人不能为空');
        $update = [
            'id'=>$data['id'],
            'title'=>$data['title'],
            'alias'=>$data['alias'],
            'attribute'=>$data['attribute'],
            'develop_date'=>$data['developDate'],
            'group'=>$data['group'],
            'time'=>formatTime(time(),'Y-m-d')
        ];
        Db::startTrans();
        try{
            $business = [];
            Db::name('business')->where(['pid'=>$data['id']])->delete();
            Db::name('principal')->where(['pid'=>$data['id']])->delete();
            foreach($data['business'] as $k=>$v){
                $business[] = [
                    'pid'=>$data['id'],
                    'user_id'=>$v
                ];
            }
            foreach ($data['principal'] as $k=>$v){
                $principal[] = [
                    'pid'=>$data['id'],
                    'user_id'=>$v
                ];
            }
            Db::name('shipowner')->insertAll($update);
            Db::name('principal')->insertAll($principal);
            Db::name('business')->insertAll($business);
            Db::commit();
            return ok_data('更新成功');
        }catch (\Exception $eS){
            return error_data('更新失败');
        }
    }

    /**
     * 导入船东
     * @return \think\response\Json
     * @throws \Exception
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importShipowner()
    {
        if (!$_FILES) return error_data('请选择文件');
        $key = array_keys($_FILES);
        $file = $_FILES[$key[0]]["tmp_name"];
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($file)) {
                return error_data('文件类型不符');
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        //将最大列转换为数字
        $length = strlen($end);
        if (strlen($end) > 1) {
            $single = ord(substr($end, $length - 1)) - 64;
            $end = ($length - 1) * 26 + $single;
        } else {
            $end = ord($end) - 64;
        }
        $info = [];
        $i = 0;
        for ($row = 2; $row <= $line; $row++) {
            for ($column = 1; $column < $end; $column++) {
                $val = $cur->getCellByColumnAndRow($column, $row)->getValue();
                if(is_object($val)){
                    $info[$i][] = $val->__toString();
                }else{
                    $info[$i][] = $val;
                }
            }
            $i++;
        }
        $title = Db::name('shipowner')->column('title');
        $info_ = [];
        $fail = 0;
        $success = 0;
        foreach ($info as $k=>$v){
            $users = explode('/',$v[4]);
                foreach ($users as $k1=>$v1){
                    $num = Db::name('user')->where(['username'=>$v1])->count();
                    if($num>1){
                        $fail++;
                        continue;
                    }
                   $v['userId'][] = Db::name('user')->where(['username'=>$v1])->value('id');
                }
                $principal = explode('/',$v[5]);
                foreach ($principal as $k2=>$v2){
                    $v['principal'][] = Db::name('user')->where(['department'=>config('department'),'username'=>$v2])->value('id');
                }
            if (!in_array((string)$v[0],$title,true)){
                $info_[] = $v;
            }else{
                $fail++;
            }
        }
        if(empty($info_)) return error_data("请勿重复导入");
        Db::startTrans();
        try{
            foreach ($info_ as $k=>$v){
                $insert = [];
                $principals = [];
                $data['title'] = (string)$v[0];
                $data['alias'] = (string)$v[1];
                $data['attribute'] = (string)$v[2];
                $data['group'] = (string)$v[3];
                $data['develop_date'] = formatTime(strtotime((string)$v[6]),'Y-m-d');
                $data['time'] = formatTime(time());
                $success++;
                $pid = Db::name('shipowner')->insertGetId($data);
                if($v['userId']){
                    foreach ($v['userId'] as $k1=>$v1){
                        if(empty($v1)) continue;
                        $insert[] = [
                            'pid'=>$pid,
                            'user_id'=>$v1
                        ];
                    }
                    Db::name('business')->insertAll($insert);
                }
                if($v['principal']){
                    foreach ($v['principal'] as $k1=>$v1){
                        $principals[] = [
                            'pid'=>$pid,
                            'user_id'=>$v1
                        ];
                    }
                    Db::name('principal')->insertAll($principals);
                }

            }
            Db::commit();
            return ok_data("成功导入".$success."条数据,"."失败".$fail."条");
        }catch (\Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 简称查询船员
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMariner()
    {
        $abbreviation = strtolower(input('abbreviation'));
        if(empty($abbreviation)) return error_data('请输入简称');
        $info = Db::name('mariner')->where(['abbreviation'=>['LIKE',"$abbreviation%"]])->limit(10)->select();
        if(!$info) return error_data('查无此人');
        $query = [];
        foreach ($info as $k=>$v){
            $length = strlen($v['id_number']);
            if($length == 18){
               $birthday = substr($v['id_number'],6,4)."-".substr($v['id_number'],10,2)."-".substr($v['id_number'],12,2);

            }else{
                $birthday = $v['id_number'];
            }
            $query[] = [
                'id'=>$v['id'],
               'info'=> $v['name']."/".$v['duty']."/".$birthday,
                'id_number'=>$v['id_number']
            ];
        }
        return json($query);
    }

    /**
     * 添加船名
     * @return \think\response\Json
     */
    public function addVessel()
    {
        $data = input('post.');
        if(empty($data['title'])) return error_data('船名不能为空');
        //判断船名是否重复
        $id = Db::name('vessel')->where(['title'=>$data['title']])->value('id');
        if($id) return error_data('船名已经存在');
        $insert = [
            'title'=>$data['title'],
            'attribute'=>$data['attribute'],
            'fleet'=>$data['fleet'],
            'develop_date'=>$data['developDate'],
            'time'=>formatTime(time())
        ];
        $res = Db::name('vessel')->insert($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 船名列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listVessel()
    {
        $vessel = input('vessel');
        $attribute = input('attribute');
        $page = input('page')?input('page'):1;
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $fleet = strtoupper(input('fleet'));
        $startTime = input('time/a')[0];
        $endTime = input('time/a')[1];
        $where['title'] = ['LIKE',"%$vessel%"];
        if($attribute) $where['attribute'] = $attribute;
        if($fleet) $where['fleet'] = ['LIKE',"%$fleet%"];
        if($startTime && $endTime) $where['develop_date'] = ['BETWEEN',[$startTime,$endTime]];
        $list = Db::name('vessel')
            ->where($where)
            ->order('develop_date')
            ->count();
        $info = Db::name('vessel')
            ->where($where)
           ->order('develop_date')
            ->page($page,$listRows)
            ->select();
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 查询条件--所有船名
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function searchVessel()
    {
        $info = Db::name('vessel')->field('id,title')->select();
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 船名详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailVessel()
    {
        $id = input('id');
        $info = Db::name('vessel')->find($id);
        $res = [
            'info'=>$info
        ];
        return json($res);
    }

    /**
     * 船名更新
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editVessel()
    {
        $data = input('post.');
        if(empty($data['title'])) return error_data('船名不能为空');
        //判断船名是否重复
        $id = Db::name('vessel')->where(['title'=>$data['title'],'id'=>['NEQ',$data['id']]])->value('id');
        if($id) return error_data('船名已经存在');
        $update = [
            'title'=>$data['title'],
            'attribute'=>$data['attribute'],
            'fleet'=>$data['fleet'],
            'develop_date'=>formatTime(strtotime($data['developDate']),"Y-m-d"),
        ];
        $res = Db::name('vessel')->where(['id'=>$data['id']])->update($update);
        if($res) return ok_data();
        return error_data();


    }

    /**
     * 导入船名
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importVessel()
    {
        if (!$_FILES) return error_data('请选择文件');
        $key = array_keys($_FILES);
        $file = $_FILES[$key[0]]["tmp_name"];
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($file)) {
                return error_data('文件类型不符');
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        //将最大列转换为数字
        $length = strlen($end);
        if (strlen($end) > 1) {
            $single = ord(substr($end, $length - 1)) - 64;
            $end = ($length - 1) * 26 + $single;
        } else {
            $end = ord($end) - 64;
        }
        $info = [];
        $i = 0;
        for ($row = 2; $row <= $line; $row++) {
            for ($column = 1; $column < $end; $column++) {
                $val = $cur->getCellByColumnAndRow($column, $row)->getValue();
                if(is_object($val)){
                    $info[$i][] = $val->__toString();
                }else{
                    $info[$i][] = $val;
                }
            }
            $i++;
        }
        $vesselInfo = Db::name('vessel')->column('title');
        $info_ = [];
        foreach ($info as $k=>$v){
            if (!in_array((string)$v[1],$vesselInfo,true)) $info_[] = $v;
        }
        if(empty($info_)) return error_data("请勿重复导入");
        $insert = [];
        foreach ($info_ as $k=>$v){
            $data['title'] = (string)$v[0];
            $data['attribute'] = (string)$v[1];
            $data['fleet'] = (string)$v[2];
            $data['develop_date'] = formatTime(strtotime((string)$v[3]),'Y-m-d');
            $data['time'] = formatTime(time());
            $insert[] = $data;
        }
        $res = Db::name('vessel')->insertAll($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 添加船员
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addMariner()
    {
        $data = input('post.');
        if(empty($data['idNumber']) || empty($data['name']) || empty($data['cid'])) return error_data('身份证、CID、姓名均不能为空');
        //判断是否重复添加
        $repeat = Db::name('mariner')->where(['id_number'=>$data['idNumber']])->value('id');
        if($repeat) return error_data('该身份证号已存在船员');
        //船东是否存在
        $shipperId = Db::name('shipowner')->find($data['ownerPool']);
        //船名是否存在
        $vesselId = Db::name('vessel')->find($data['vessel']);
        if(empty($shipperId)) return error_data('船东不存在');
        if(empty($vesselId)) return error_data('船名不存在');
        $insert = [
            'id_number'=>$data['idNumber'],
            'cid'=>$data['cid'],
            'name'=>$data['name'],
            'password'=>md5(substr($data['idNumber'],-6)),
            'abbreviation'=>abbreviation($data['name']),
            'english'=>$data['english'],
            'duty'=>$data['duty'],
            'vessel'=>$data['vessel'],
            'fleet'=>$data['fleet'],
            'owner_pool'=>$data['ownerPool'],
            'manning_office'=>$data['manningOffice'],
            'time'=>formatTime(time())
        ];
        $res = Db::name('mariner')->insert($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 船员列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listMariner()
    {
        $page = input('page')?input('page'):1;
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $where = [];
        if(input('id')) $where['a.id'] = input('id');
        if(input('idNumber')) $where['id_number'] = ['LIKE',"%".input('idNumber')."%"];
        if(input('cid')) $where['cid'] = ['LIKE',"%".input('cid')."%"];
        if (input('fleet')) $where['a.fleet'] = ['LIKE',"%".strtoupper(input('fleet'))."%"];
        if (input('ownerPool')) $where['owner_pool'] = input('ownerPool');
        if (input('vessel')) $where['a.vessel'] = input('vessel');
        if (input('manningOffice')) $where['manning_office'] = input('manningOffice');
        if (input('duty')) $where['duty'] = input('duty');
        $list = Db::name('mariner')
            ->alias('a')
            ->field('a.id,a.cid,a.name,a.id_number,a.duty,a.manning_office,a.fleet,a.time,b.title ownerPool,c.title vessel,a.is_delete')
            ->join('shipowner b','a.owner_pool = b.id','LEFT')
            ->join('vessel c','c.id=a.vessel','LEFT')
            ->where($where)
            ->order('duty')
            ->count();
        $info = Db::name('mariner')
            ->alias('a')
            ->field('a.id,a.cid,a.name,a.id_number,a.duty,a.manning_office,a.fleet,a.time,b.title ownerPool,c.title vessel,a.is_delete')
            ->join('shipowner b','a.owner_pool = b.id','LEFT')
            ->join('vessel c','c.id=a.vessel','LEFT')
            ->where($where)
            ->order('duty')
            ->page($page,$listRows)
            ->select();
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 导出船员数据
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportMariner()
    {
        if(input('id')) $where['a.id'] = input('id');
        if(input('idNumber')) $where['id_number'] = input('idNumber');
        if(input('cid')) $where['cid'] = input('cid');
        if (input('fleet')) $where['a.fleet'] = ['LIKE',"%".input('fleet')."%"];
        if (input('ownerPool')) $where['owner_pool'] = input('ownerPool');
        if (input('vessel')) $where['a.vessel'] = input('vessel');
        if (input('manningOffice')) $where['manning_office'] = input('manningOffice');
        $info = Db::name('mariner')
            ->alias('a')
            ->field('a.id,a.cid,a.name,a.id_number,a.duty,a.manning_office,a.fleet,a.time,b.title ownerPool,c.title vessel')
            ->join('shipowner b','a.owner_pool = b.id','LEFT')
            ->join('vessel c','c.id=a.vessel','LEFT')
            ->where($where)
            ->order('duty')
            ->select();
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 船员职务
     * @return \think\response\Json
     */
    public function dutyMariner()
    {
        $info = Db::name('mariner')->column('duty');
        $info = array_filter($info);
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 船员详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  detailMariner()
    {
        $id = input('id');
       $info =  Db::name('mariner')
           ->alias('a')
           ->field('a.id,a.cid,a.name,a.id_number,a.duty,a.manning_office,a.fleet,a.owner_pool,a.vessel,a.time,b.title ownerPool,c.title vesselName')
           ->join('shipowner b','a.owner_pool = b.id','LEFT')
           ->join('vessel c','c.id=a.vessel','LEFT')
           ->find($id);
       $res = [
           'data'=>$info
       ];
       return json($res);
    }

    /**
     * 船员编辑
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editMariner()
    {
        $data = input('post.');
        if(empty($data['idNumber']) || empty($data['name']) || empty($data['cid'])) return error_data('身份证、CID、姓名均不能为空');
        //船东是否存在
        $shipperId = Db::name('shipowner')->find($data['owner_pool']);
        //船名是否存在
        $vesselId = Db::name('vessel')->find($data['vessel']);
        if(empty($shipperId)) return error_data('船东不存在');
        if(empty($vesselId)) return error_data('船名不存在');
        $update = [
            'id'=>$data['id'],
            'id_number'=>$data['idNumber'],
            'cid'=>$data['cid'],
            'name'=>$data['name'],
            'abbreviation'=>abbreviation($data['name']),
            'english'=>$data['english'],
            'duty'=>$data['duty'],
            'vessel'=>$vesselId,
            'fleet'=>$data['fleet'],
            'owner_pool'=>$shipperId,
            'manning_office'=>$data['manning_office'],
        ];
        $res = Db::name('mariner')->update($update);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 批量导入船员
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importMariner()
    {
        if (!$_FILES) return error_data('请选择文件');
        $key = array_keys($_FILES);
        $file = $_FILES[$key[0]]["tmp_name"];
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($file)) {
                return error_data('文件类型不符');
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        //将最大列转换为数字
        $length = strlen($end);
        if (strlen($end) > 1) {
            $single = ord(substr($end, $length - 1)) - 64;
            $end = ($length - 1) * 26 + $single;
        } else {
            $end = ord($end) - 64;
        }
        $info = [];
        $i = 0;
        for ($row = 2; $row <= $line; $row++) {
            for ($column = 1; $column < $end; $column++) {
                $val = $cur->getCellByColumnAndRow($column, $row)->getValue();
                if(is_object($val)){
                    $info[$i][] = $val->__toString();
                }else{
                    $info[$i][] = $val;
                }
            }
            $i++;
        }
        $vesselInfo = Db::name('mariner')->column('id_number');
        $info_ = [];
        foreach ($info as $k=>$v){
            if (!in_array((string)$v[2],$vesselInfo,true)) $info_[] = $v;
        }
        if(empty($info_)) return error_data("请勿重复导入");
        $insert = [];
        foreach ($info_ as $k=>$v){
            $data['cid'] = (string)$v[0];
            $data['name'] = (string)$v[1];
            $data['id_number'] = (string)$v[2];
            $data['password'] = md5(substr($data['id_number'],-6));
            //别名和英文名
            $data['abbreviation'] = abbreviation($data['name']);
            $str = encode($data['name'],true);
            $str = explode(' ',$str);
            if($str[0]) $str[0] = ucfirst($str[0]);
            if($str[1]) $str[1] = " ".ucfirst($str[1]);
            $data['english'] = implode('',$str);
            $data['duty'] = (string)$v[3];
            $data['manning_office'] = (string)$v[4];
            $data['fleet'] = (string)$v[5];
            $shipperId = Db::name('shipowner')->where(['title'=>(string)$v[6]])->value('id');
            $vesselId = Db::name('vessel')->where(['title'=>(string)$v[7]])->value('id');
            if(!$shipperId || !$vesselId) continue;
            $data['owner_pool'] = $shipperId;
            $data['vessel'] = $vesselId;
            $data['time'] = formatTime(time());
            $data['is_delete'] = 0;
            $insert[] = $data;
        }
        $res = Db::name('mariner')->insertAll($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     *家汇信息导入
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importRemittance()
    {
        if (!$_FILES) return error_data('请选择文件');
        $key = array_keys($_FILES);
        $file = $_FILES[$key[0]]["tmp_name"];
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($file)) {
                return error_data('文件类型不符');
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        //将最大列转换为数字
        $length = strlen($end);
        if (strlen($end) > 1) {
            $single = ord(substr($end, $length - 1)) - 64;
            $end = ($length - 1) * 26 + $single;
        } else {
            $end = ord($end) - 64;
        }
        $info = [];
        $i = 0;
        for ($row = 2; $row <= $line; $row++) {
            for ($column = 1; $column < $end; $column++) {
                $val = $cur->getCellByColumnAndRow($column, $row)->getValue();
                if(is_object($val)){
                    $info[$i][] = $val->__toString();
                }else{
                    $info[$i][] = $val;
                }
            }
            $i++;
        }
        $vesselInfo = Db::name('remittance')->column('pid');
        $info_ = [];
        $updateInfo = [];
        foreach ($info as $k=>$v){
            //用身份证获取船员id
            $id =  Db::name('mariner')->where(['id_number'=>$v[1]])->value('id');
            if(!$id) continue;
            $v['pid'] = $id;
            if (!in_array($id,$vesselInfo,true)){
                $info_[] = $v;
            }else{
                $updateInfo[] = $v;
            }
        }
        $insert = [];
        foreach ($info_ as $k=>$v){
            $data['pid'] = $v['pid'];
            $data['bank'] = (string)$v[2];
            $data['short_name'] = (string)$v[3];
            $data['english'] = (string)$v[4];
            $data['number'] = (string)$v[5];
            $data['name_cn'] = (string)$v[6];
            $data['name_en'] = (string)$v[7];
            $data['birthday'] = (string)$v[8];
            $data['relation'] = (string)$v[9];
            $data['telnumber'] = (string)$v[10];
            $data['swift'] = (string)$v[11];
            $data['line_number'] = (string)$v[12];
            $data['changer'] = (string)$v[13];
            $data['change_time'] = formatTime(time(),'Y-m-d');
            $insert[] = $data;
        }
        Db::startTrans();
        try{
            $res = Db::name('remittance')->insertAll($insert);
            if($updateInfo){
                $update = [];
                foreach ($updateInfo as $k=>$v){
                    $update['bank'] = (string)$v[2];
                    $update['short_name'] = (string)$v[3];
                    $update['english'] = (string)$v[4];
                    $update['number'] = (string)$v[5];
                    $update['name_cn'] = (string)$v[6];
                    $update['name_en'] = (string)$v[7];
                    $update['birthday'] = str_replace('/','-',(string)$v[8]);
                    $update['relation'] = (string)$v[9];
                    $update['telnumber'] = (string)$v[10];
                    $update['swift'] = (string)$v[11];
                    $update['line_number'] = (string)$v[12];
                    $update['changer'] = (string)$v[13];
                    $update['change_time'] = formatTime(time(),'Y-m-d');
                    Db::name('remittance')->where(['pid'=>$v['pid']])->update($update);
                }
            }
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            return error_data();
        }
    }

    /**
     * 编辑家汇
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editRemittance()
    {
        $data = input('post.');
        $update['bank'] = $data['bank'];
        $update['short_name'] = $data['shortName'];
        $update['english'] = $data['english'];
        $update['number'] = $data['number'];
        $update['name_cn'] = $data['nameCn'];
        $update['name_en'] = $data['nameEn'];
        $update['birthday'] = $data['birthday'];
        $update['relation'] = $data['relation'];
        $update['telnumber'] = $data['telnumber'];
        $update['swift'] = $data['swift'];
        $update['line_number'] = $data['lineNumber'];
        $update['changer'] = $this->user['username'];
        $update['change_time'] = formatTime(time(),'Y-ms-d');
        if(Db::name('remittance')->where(['pid'=>$data['id']])->value('id')){
            $res = Db::name('remittance')->where(['pid'=>$data['id']])->update($update);
        }else{
            $update['pid'] = $data['id'];
            $res = Db::name('remittance')->insert($update);
        }
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 家汇详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailRemittance()
    {
        $pid = input('id');
        $info = Db::name('remittance')->where(['pid'=>$pid])->find();
        $info['operate'] = $info['changer']."/".formatTime(strtotime($info['change_time']),"Y-m-d");
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 导出人民币汇款单
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportRemittanceCn()
    {
        if(!in_array("导出人民币汇款",$this->privilege) && $this->is_cehck_rule) return warning_data();
        $fileName = "人民币汇款单";
        $header = ["序号","船员姓名","出生日期","职务","收款账号","收款人姓名","金额","用途","收款行行号","收款行名称","银行简称","船东（简称）","船名","汇款日期"
];
        if(input('id')) $where['a.id'] = input('id');
        if(input('idNumber')) $where['id_number'] = input('idNumber');
        if(input('cid')) $where['cid'] = input('cid');
        if (input('fleet')) $where['a.fleet'] = ['LIKE',"%".input('fleet')."%"];
        if (input('ownerPool')) $where['owner_pool'] = input('ownerPool');
        if (input('vessel')) $where['a.vessel'] = input('vessel');
        if (input('manningOffice')) $where['manning_office'] = input('manningOffice');
        $info = Db::name('mariner')
            ->alias('a')
            ->field('a.name,a.id_number,a.duty,d.number,d.name_cn,d.line_number,d.bank,d.short_name,b.alias,c.title vessel')
            ->join('shipowner b','a.owner_pool = b.id','LEFT')
            ->join('vessel c','c.id=a.vessel','LEFT')
            ->join('remittance d','d.pid= a.id','LEFT')
            ->where($where)
            ->order('duty')
            ->select();
        $data = [];
        $i = 1;
       foreach ($info as $k=>&$v){
           if(strlen($v['id_number']) == 18){
               $v['id_number'] = substr($v['id_number'],6,4)."/".substr($v['id_number'],10,2)."/".substr($v['id_number'],12,2);
           }
               $v['money'] = "";
               $v['use']  = "家汇";
               $v['time'] = date("Y-m-d",strtotime("+1 day"));
               $insert = [
                   'order'=>$i++,
                   'name'=>$v['name'],
                   'birthday'=>$v['id_number'],
                   'duty'=>$v['duty'],
                   'number'=>$v['number'],
                   'nameCn'=>$v['name_cn'],
                   'money'=>"",
                   "use"=>"家汇",
                   "lineNumber"=>$v['line_number'],
                    "bank"=>$v['bank'],
                   "shortName"=>$v['short_name'],
                   "alias"=>$v['alias'],
                   "vessel"=>$v['vessel'],
                   "time"=>date("Y-m-d",strtotime("+1 day"))
               ];
               $data[] = $insert;

       }
        $this->excelExport($fileName,$header,$data);
    }

    /**
     * 导出外币汇款单
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportRemittanceEn()
    {
        if(!in_array("导出外币汇款单",$this->privilege)  && $this->is_cehck_rule) return warning_data();
        $fileName = "英文汇款单";
        $header = ["序号","船员姓名","出生日期","职务","收款人","收款行名称","账号","Swift Code","金额(USD)","船东+职务+船员姓名"	,"收款人生日","收款人联系电话","船东","船名","收款人与船员关系","汇款日期"];
        $where = [];
        if(input('id')) $where['a.id'] = input('id');
        if(input('idNumber')) $where['id_number'] = input('idNumber');
        if(input('cid')) $where['cid'] = input('cid');
        if (input('fleet')) $where['a.fleet'] = ['LIKE',"%".strtoupper(input('fleet'))."%"];
        if (input('ownerPool')) $where['owner_pool'] = input('ownerPool');
        if (input('vessel')) $where['a.vessel'] = input('vessel');
        if (input('manningOffice')) $where['manning_office'] = input('manningOffice');
        if (input('duty')) $where['duty'] = input('duty');
            $info = Db::name('mariner')
                ->alias('a')
                ->field('a.name,a.id_number,a.duty,d.english,d.name_en,d.number,d.swift,b.title ownerPool,c.title vessel,d.telnumber,d.relation,d.birthday')
                ->join('shipowner b','a.owner_pool = b.id','LEFT')
                ->join('vessel c','c.id=a.vessel','LEFT')
                ->join('remittance d','d.pid= a.id','LEFT')
                ->where($where)
                ->order('duty')
                ->select();
        $data = [];
        $i = 1;
        foreach ($info as $k=>&$v){
            $v['id_number'] = substr($v['id_number'],6,4)."/".substr($v['id_number'],10,2)."/".substr($v['id_number'],12,2);
            $insert = [
                'order'=>$i++,
                'name'=>$v['name'],
                'birthday'=>$v['id_number'],
                'duty'=>$v['duty'],
                'nameEn'=>$v['name_en'],
                "bank"=>$v['english'],
                'number'=>$v['number'],
                'swift'=>$v['swift'],
                'money'=>"",
                "userInfo"=>$v['ownerPool']."/".$v['duty']."/".$v['name'],
                'receiverBirth'=>$v['birthday'],
                "telnumber"=>$v['telnumber'],
                "ownerPool"=>$v['ownerPool'],
                "vessel"=>$v['vessel'],
                "relation"=>$v['relation'],
                "time"=>date("Y-m-d",strtotime("+1 day"))
            ];
            $data[] = $insert;
        }
        $this->excelExport($fileName,$header,$data);
    }

    /**
     * 船员社保费用信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function chargeInfo()
    {
        $marinerId = input('marinerId');
        $starttime = input('time/a')[0];
        $endtime = input('time/a')[1];
        if($starttime && $endtime) {
            $socialInfo = Db::name('social_info')
                ->field('sum(debt) debt,sum(receipt) receipt')
                ->where(['mariner_id'=>$marinerId,'pay_month'=>['BETWEEN',[$starttime,$endtime]]])
                ->find();
            $borrowInfo = Db::name('borrow')
                ->field('SUM(amount) amount,SUM(repayment) repayment')
                ->where(['mariner_id'=>$marinerId,'tally'=>['BETWEEN',[$starttime,$endtime]]])
                ->find();
            $chargeInfo = Db::name('charge')->field('SUM(amount) amount,SUM(amount)-SUM(surplus) repayment')->where(['mariner_id'=>$marinerId,'month'=>['BETWEEN',[$starttime,$endtime]]])->find();
        }else{
            $socialInfo = Db::name('social_info')
                ->field('sum(debt) debt,sum(receipt) receipt')
                ->where(['mariner_id'=>$marinerId])
                ->find();
            $borrowInfo = Db::name('borrow')
                ->field('SUM(amount) amount,SUM(repayment) repayment')
                ->where(['mariner_id'=>$marinerId])
                ->find();
            $chargeInfo = Db::name('charge')->field('SUM(amount) amount,SUM(amount)-SUM(surplus) repayment')->where(['mariner_id'=>$marinerId])->find();
        }
        $res = [
            'social'=>$socialInfo,
            'borrow'=>$borrowInfo,
            'charge'=>$chargeInfo,
        ];
        return json($res);
    }

    /**
     * 船员费用信息详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function chargeDetail()
    {
        $type = input('type');
        $page = input('page') ? input('page') : 1;
        $listRows = input('listRows') ? input('listRows') : self::$listRows;
        $marinerId = input('marinerId');
        $starttime = input('time/a')[0];
        $endtime = input('time/a')[1];
        $where['mariner_id'] = $marinerId;
        switch ($type) {
            case 1:

//                $max = Db::name('social_info')->field('MAX(pay_month) maxMonth')->where($where)->find();
                if ($starttime && $endtime) $where['pay_month'] = ['BETWEEN', [$starttime, $endtime]];
                $list = Db::name('social_info')->field('id,pay_month,area,debt,receipt')->where($where)->count();
                $info = Db::name('social_info')->field('id,pay_month,area,debt,receipt')->where($where)->order('pay_month')->page($page,$listRows)->select();
//                $maxMonth = $max['maxMonth'];
//                $addInfo = [];
//                if ($maxMonth) {
//                    $nextMonth = date('Y-m', strtotime("$maxMonth +1 month"));
//                    $info = Db::name('insured')
//                        ->where(['mariner_id' => $marinerId, 'starttime' => ['ELT', $nextMonth], 'is_stop' => 0])
//                        ->order('starttime')
//                        ->find();
//                    if ($info) {
//                        $overInfo = Db::name('social_security')->field('id,endtime')->where(['area' => $info['area'],'starttime'=>['ELT',$nextMonth]])->order('starttime')->find();
//                        $time = [];
//                        $nextMonth = strtotime( $nextMonth ); //转换一下
//                        $overTime   = strtotime( $overInfo['endtime'] ); //一样转换一下
//                        $i            = false; //开始标示
//                        while( $nextMonth < $overTime ) {
//                            $newMonth = !$i ? date('Y-m', strtotime('+0 Month', $nextMonth)) : date('Y-m', strtotime('+1 Month', $nextMonth));
//                            $nextMonth = strtotime( $newMonth );
//                            $i = true;
//                            $time[] = $newMonth ;
//                        }
//                        //查询每月的社保欠款
//                       $amount =  Db::name('social_security_set')
//                            ->field('SUM(total_person) amount')
//                            ->where(['pid'=>$overInfo['id']])
//                            ->find();
//                       foreach ($time as $k=>$v){
//                           $addInfo = [
//                               'pay_month'=>$v,
//                               'area'=>$info['area'],
//                               'debt'=>$amount['amount'],
//                               'receipt'=>0
//                           ];
//                       }
//                       if($addInfo) $produceInfo = array_merge($produceInfo,$addInfo);
//                    }
//                }
                break;
            case 2:
                if ($starttime && $endtime) $where['tally'] = ['BETWEEN', [$starttime, $endtime]];
                $list = Db::name('borrow')
                    ->field('id,date,currency,reason,amount,repayment')
                    ->where($where)
                    ->count();
                $info = Db::name('borrow')
                    ->field('id,date,currency,reason,amount,repayment,if_settle')
                    ->where($where)
                    ->page($page,$listRows)
                    ->select();
                break;
            case 3:
                if ($starttime && $endtime) $where['month'] = ['BETWEEN', [$starttime, $endtime]];
                $list = Db::name('charge')
                    ->field('amount,amount-surplus repayment')
                    ->where($where)
                    ->count();
                $info = Db::name('charge')
                    ->field('id,amount,amount-surplus repayment')
                    ->where($where)
                    ->page($page,$listRows)
                    ->select();
                break;
            default:
                return error_data('参数错误');
        }
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 业务主管列表
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function staffData()
    {
        $search = input('search');
        if($search) $where['department|username'] = ['LIKE',"%$search%"];
        if($where) {
            $list = Db::name('user')->field('id')->where($where)->count();
            $info = Db::name('user')->field('id,CONCAT_WS("-",department,username) username')->where($where)->select();
        }else{
            $list = Db::name('user')->field('id')->count();
            $info = Db::name('user')->field('id,CONCAT_WS("-",department,username) username')->select();
        }
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 所有船东名称
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shipowner()
    {
        $search = input('search');
        if($search) $where['title'] = ['LIKE',"%$search%"];
        if($where){
           $info = Db::name('shipowner')->field('id,title,alias')->where($where)->select();
        }else{
            $info = Db::name('shipowner')->field('id,title,alias')->select();
        }
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 禁用/启用操作
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forbidden()
    {
        $id = input('id/a');
        $value = input('value');
        $res = Db::name('mariner')->where(['id'=>['IN',$id]])->update(['is_delete'=>$value]);
        if($res) return ok_data();
        return error_data("设置失败");
    }

    /**
     * 重置船员密码
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function restPassword()
    {
        $id = input('id/a');
        $idNumber = Db::name('mariner')->field('id,id_number')->where(['id'=>['IN',$id]])->select();
        foreach ($idNumber as $k=>$v){
            $password = md5(substr($v['id_number'],-6));
            $res = Db::name('mariner')->where(['id'=>$v['id']])->update(['password'=>$password]);
        }
        return ok_data();
    }

    /**
     * 财务部人员
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function financeList()
    {
        $info = Db::name('user')->field('id,username')->where(['department'=>config('department')])->select();
        return json($info);
    }
}