<?php

function getUserId() {
    return session('?user') ? session('user')['ID'] : null;
}

function list_to_tree($list, $root = 0, $pk = 'ID', $pid = 'PID', $child = '_CHILD') {
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = 0;
            if (isset($data[$pid])) {
                $parentId = $data[$pid];
            }
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
   +----------------------------------------------------------
   * Export Excel | 2013.08.23
   * Author:HongPing <hongping626@qq.com>
   +----------------------------------------------------------
   * @param $expTitle     string File name
   +----------------------------------------------------------
   * @param $expCellName  array  Column name
   +----------------------------------------------------------
   * @param $expTableData array  Table data
   +----------------------------------------------------------
*/
function exportExcel($expTitle,$expCellName,$expTableData){
    $xlsTitle = $expTitle;//文件名称
    $fileName = $_SESSION['loginAccount'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);
    vendor("PHPExcel.PHPExcel");
    $objPHPExcel = new PHPExcel();
    $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
    for($i=0;$i<$cellNum;$i++){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
    }
    // Miscellaneous glyphs, UTF-8
    for($i=0;$i<$dataNum;$i++){
        for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
        }
    }

    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
    header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function getDataFromExcel($excelFile) {
    vendor('PHPExcel.PHPExcel');
    $data = array();

    try {
        $excelType = PHPExcel_IOFactory::identify($excelFile);
        $excelReader = PHPExcel_IOFactory::createReader($excelType);
        $objPHPExcel = $excelReader->load($excelFile);
        $sheetIterator = $objPHPExcel->getWorksheetIterator();
        $sheetIndex = 0;
        while ($sheetIterator->valid()) {
            $sheet = $sheetIterator->current();
            array_push($data, array());
            $rowIterator = $sheet->getRowIterator();
            $rowIndex = 0;
            while ($rowIterator->valid()) {
                $row = $rowIterator->current();
                array_push($data[$sheetIndex], array());
                $cellIterator = $row->getCellIterator();
                $cellIndex = 0;
                while ($cellIterator->valid()) {
                    $cell = $cellIterator->current();
                    array_push($data[$sheetIndex][$rowIndex], array());
                    $data[$sheetIndex][$rowIndex][$cellIndex] = $cell->getFormattedValue();
                    $cellIndex += 1;
                    $cellIterator->next();
                }
                $rowIndex += 1;
                $rowIterator->next();
            }
            $sheetIndex += 1;
            $sheetIterator->next();
        }
    } catch (PHPExcel_Reader_Exception $e) {
        $this->error('Error loading file: '.$e->getMessage());
    }

    unlink($excelFile);
    return $data;
}

function todo_status($status) {
    switch ($status) {
        case '0': return '未阅';
        case '1': return '已阅';
        case '2': return '已完成';
    }
}

function currency($currency) {
    if ($currency == '0') {
        return 'RMB';
    } else {
        return 'USD';
    }
}

function currency_val($currency) {
    if ($currency == 'RMB') {
        return '0';
    } else {
        return '1';
    }
}

function enterpriseNameToId($enterpriseName) {
    $userModel = D('User');
    return $userModel->queryIdByName($enterpriseName)['ID'];
}

function fillKCArrayFields($kcArray) {
    $fields = array(
        'SERIOUS_NO' => '',
        'BUYER' => '',
        'OUT_DATE' => '',
        'OUT_AMOUNT' => '',
        'RECEIVE_AMOUNT' => '',
        'RECEIVE_TYPE' => '',
        'RATE' => '',
        'ORDER_NO' => '',
        'IN_DATE' => '',
        'IN_AMOUNT' => '',
        'BUILD_DATE' => '',
        'OPER_USER_ID' => '',
    );
    $result = array();
    foreach ($kcArray as $row) {
        $newArray = $row + $fields;
        array_push($result, $newArray);
    }
    return $result;
}

?>