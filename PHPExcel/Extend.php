<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018/3/24 16:00
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Loader;
use think\Request;

/**
 * 扩展控制器
 * Class Extend
 * @package app\index\controller
 */
class Extend extends Controller
{
    /**
     * 上传Excel文件
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importExcel()
    {
        header("content-type:text/html;charset=utf-8");
        //上传Excel
        $file = $this->request->file('excel');
        $info = $file->validate(['size' => 15678, 'ext' => 'xlsx,xls,csv'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'excel');
        if ($info) {
            //引入PHPExcel类
            Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
            $fileName = $info->getSaveName();
            $file_name = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'excel' . DS . $fileName;   //上传文件的地址
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
            $sheet = $objPHPExcel->getSheet(0);
            //获取总行数
            $allRow = $sheet->getHighestRow();
            //获取纵列数
            //$allColumn = $sheet->getHighestColumn();
            //从第二行开始插入数据到数据库
            $insertData = [];
            for ($i = 2; $i <= $allRow; $i++) {
                $data['name'] = $objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
                $data['age'] = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
                $insertData[] = $data;
            }
            $res = Db::name('table')->insertAll($insertData);
            if($res) return json(['status' => 1, 'msg' => "上传文件成功"]);
        } else {
            return json(['status' => 0, 'msg' => "上传文件失败"]);
        }

    }

    /**
     * 导出Excel文件
     * @param string $fileName
     * @param array $headArr
     * @param array $data
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function excelExport($fileName = '', $headArr = [], $data = []) {
        $fileName .= "_" . date("Y_m_d", Request::instance()->time()) . ".xls";
        //引入PHPExcel类
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();

        $key = ord("A"); // 设置表头
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach ($data as $key => $rows) { // 行写入
            $span = ord("A");
            foreach ($rows as $keyName => $value) { // 列写入
                $objActSheet->setCellValue(chr($span) . $column, $value);
                $span++;
            }
            $column++;
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

    public function excel() {

        $name='测试导出';

        $header=['表头A','表头B'];

        $data=[

            ['嘿嘿','heihei'],

            ['哈哈','haha']

        ];

        $this->excelExport($name,$header,$data);
    }
}