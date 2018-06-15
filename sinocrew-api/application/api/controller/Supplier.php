<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018-04-19 10:29
 */
namespace app\api\controller;
use app\api\common\Base;
use think\Db;
use think\Loader;

/**
 * 供应商控制器
 * Class Supplier
 * @package app\api\controller
 */
class Supplier extends Base
{
    /**
     * 添加供应商
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addSupplier()
    {
        $data = input('post.');
        if(empty($data['title']) || empty($data['attribute'])) return error_data('供应商名称和属性不能为空');
        $repeatInfo = Db::name('supplier')->where(['title'=>$data['title'],'attribute'=>$data['attribute']])->find();
        if($repeatInfo) return error_data('供应商重复添加');
        $insert = [
            'title'=>$data['title'],
            'attribute'=>$data['attribute'],
            'develop_date'=>$data['developDate'],
            'remark'=>$data['remark'],
            'time'=>formatTime(time())
        ];
        $res = Db::name('supplier')->insert($insert);
        if($res) return ok_data("添加成功");
        return error_data("添加失败");
    }

    /**
     * 供应商列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listSupplier()
    {
        $search = input('title');
        $page = input('page')?input('page'):1;
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $attribute = input('attribute');
        $startTime = input('time/a')[0];
        $endTime = input('time/a')[1];
        $where['title'] = ['LIKE',"%$search%"];
        if($attribute) $where['attribute'] = $attribute;
        if($startTime && $endTime) $where['develop_date'] = ['BETWEEN TIME',[$startTime,$endTime]];
        $list = Db::name('supplier')
            ->where($where)
            ->order('time')
            ->count();
        $info = Db::name('supplier')
            ->where($where)
            ->order('time')
            ->page($page,$listRows)
            ->select();
        foreach ($info as $k=>&$v){
            $payInfo = Db::name('supplier_info')->field('SUM(pay_before) pay_before,SUM(pay_after) pay_after')->where(['pid'=>$v['id']])->find();
            $v['pay_before'] = $payInfo['pay_before']?$payInfo['pay_before']:0;
            $v['pay_after'] = $payInfo['pay_after']?$payInfo['pay_after']:0;
        }
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 供应商详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailSupplier()
    {
        $id = input('id');
        $info = Db::name('supplier')->where(['id'=>$id])->find();
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 编辑供应商
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function editSupplier()
    {
        $data = input('post.');
        if(empty($data['title']) || empty($data['attribute'])) return error_data('供应商名称和属性不能为空');
        $repeatInfo = Db::name('supplier')->where(['title'=>$data['title'],'attribute'=>$data['attribute'],'id'=>['NEQ',$data['id']]])->find();
        if($repeatInfo) return error_data('供应商重复添加');
        $update = [
            'id'=>$data['id'],
            'title'=>$data['title'],
            'attribute'=>$data['attribute'],
            'develop_date'=>$data['developDate'],
            'remark'=>$data['remark'],
        ];
        $res = Db::name('supplier')->update($update);
        if($res) return ok_data("更新成功");
        return error_data("更新失败");
    }

    /**
     * 导出供应商
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportSupplier()
    {
        $search = input('title');
        $startTime = input('time/a')[0];
        $endTime = input('time/a')[1];
        $where['title'] = ['LIKE',"%$search%"];
        if($startTime && $endTime) $where['develop_date'] = ['BETWEEN TIME',[$startTime,$endTime]];
        $info = Db::name('supplier')
            ->where($where)
            ->order('time')
            ->select();
        foreach ($info as $k=>&$v){
            $payInfo = Db::name('supplier_info')->field('SUM(pay_before) pay_before,SUM(pay_after) pay_after')->where(['pid'=>$v['id']])->find();
            $v['pay_before'] = $payInfo['pay_before']?$payInfo['pay_before']:0;
            $v['pay_after'] = $payInfo['pay_after']?$payInfo['pay_before']:0;
        }
        return json($info);
    }

    /**
     * 导入供应商
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importSupplier()
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
        $insert = [];
        foreach ($info as $k=>$v){
            $data['title'] = (string)$v[0];
            $data['attribute'] = (string)$v[1];
            $result =  Db::name('supplier')->where(['title'=>$data['title'],'attribute'=>$data['attribute']])->value('id');
            if($result) continue;
            $data['develop_date'] = formatTime(strtotime((string)$v[2]),'Y-m-d');
            $data['time'] = formatTime(time());
            $data['remark'] = (string)$v[3];
            $insert[] = $data;
        }
        $res = Db::name('supplier')->insertAll($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 供应商属性列表
     * @return \think\response\Json
     */
    public function attributeSupplier()
    {
        $info = Db::name('supplier')->column('attribute');
        $info = array_filter(array_unique($info));
        $res = [
            'attribute'=>$info
        ];
        return json($res);
    }

    /**
     * 供应商费用明细
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailInfo()
    {
        $id = input('id');
        $page = input('page');
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $info = Db::name('supplier')->field('title,attribute')->find($id);
        $payInfo = Db::name('supplier_info')->field('SUM(pay_before) pay_before,SUM(pay_after) pay_after')->where(['pid'=>$id])->find();
        $info['pay_before'] = $payInfo['pay_before']?$payInfo['pay_before']:0;
        $info['pay_after'] = $payInfo['pay_after']?$payInfo['pay_after']:0;
        //费用明细
        $list = Db::name('supplier_info')->field('id,date,pay_before,pay_after')
            ->where(['pid'=>$id])
            ->count();
        $detail = Db::name('supplier_info')->field('id,date,pay_before,pay_after')
            ->where(['pid'=>$id])
            ->order('date')
            ->page($page,$listRows)
            ->select();
        $res = [
            'list'=>$list,
            'info'=>$info,
            'detail'=>$detail
        ];
        return json($res);
    }

    /**
     *编辑供应商费用明细
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function editInfo()
    {
        $id = input('id');
        $money = input('money');
        $info = Db::name('supplier_info')->field('pay_before,pay_after')->where(['id'=>$id])->find();
        if($money>$info['pay_before']) return error_data('输入金额有误');
        $res = Db::name('supplier_info')->where(['id'=>$id])->update(['pay_after'=>$money]);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 导出供应商费用明细
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportDetail()
    {
        $id = input('id');
        $info = Db::name('supplier')->find($id);
        $detail = Db::name('supplier_info')->field('id,date,pay_before,pay_after')
            ->where(['pid'=>$id])
            ->order('date')
            ->select();
        $res = [
            'info'=>$info,
            'detail'=>$detail
        ];
        return json($res);
    }
}