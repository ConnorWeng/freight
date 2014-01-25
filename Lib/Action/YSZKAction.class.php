<?php

class YSZKAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    public function queryPool() {
        $this->display();
    }

    public function queryPoolBySupplier() {
        $supplier = I('supplier');
        $YSZKModel = D('YSZK');
        $this->ajaxReturn($YSZKModel->queryPool($supplier), 'JSON');
    }

    public function queryFuturePool() {
        $supplier = I('supplier');
        $YSZKModel = D('YSZK');
        $this->ajaxReturn($YSZKModel->queryFuturePool($supplier), 'JSON');
    }

    public function yszk() {
        $this->display();
    }

    public function searchYSZK() {
        $supplier = I('supplier');
        $buyerName = I('buyer_name');
        $ysNo = I('ys_no');
        $currency = I('currency');
        $expFlag = I('exp_flag');
        $xzFlag = I('xz_flag');
        $srOpDate = I('sr_op_date');
        $xzOpDate = I('xz_op_date');

        $yszkModel = D('YSZK');
        $rs = $yszkModel->queryYSZK($supplier, $buyerName, $ysNo, $currency, $expFlag, $xzFlag, $srOpDate, $xzOpDate);
        $this->ajaxReturn($rs, 'JSON');
    }

    public function importSrExcel() {
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();
        $upload->allowExts = array('xlsm', 'xls', 'xlsx');
        $upload->savePath = APP_PATH.'Upload/';
        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        } else {
            $info = $upload->getUploadFileInfo();
            $savePath = $info[0]['savepath'];
            $saveName = $info[0]['savename'];
            $sheetsData = getDataFromExcel($savePath.$saveName);

            $srTempModel = D('SrTemp');
            $supplierLimitModel = D('SupplierLimit');
            $yszkModel = D('YSZK');
            $batchId = uniqid();
            $errorCode = 0;
            $srSheetRows = $sheetsData[0];
            $srSheetRowCount = count($srSheetRows);
            for ($rowIndex = 0; $rowIndex < $srSheetRowCount; $rowIndex++) {
                if ($rowIndex != 0 && $rowIndex != $srSheetRowCount - 1) {
                    $row = $srSheetRows[$rowIndex];

                    $supplierId = session('user')['ID'];
                    $buyerName = $row[1];
                    $ysNo = $row[2];
                    $kpDate = $this->formatDateString($row[3]);
                    $ysEndDate = $this->formatDateString($row[4]);
                    $oriAmount = $row[5];
                    $currency = currency_val($row[6]);

                    if ($yszkModel->checkDuplicate($ysNo)) {
                        $errorCode = 1;
                        break;
                    }

                    if ($supplierLimitModel->isExpire($supplierId, $kpDate)) {
                        $errorCode = 2;
                        break;
                    }

                    $result = $srTempModel->importData($batchId, $supplierId, $buyerName, $ysNo, $kpDate, $ysEndDate, $oriAmount, $currency, $saveName);
                    if (!$result) {
                        $errorCode = 3;
                        break;
                    }
                }
            }

            if ($errorCode == 0) {
                $todoModel = D('Todo');
                $todoModel->addTodo(C('TODO_SR_NAME'), U('YSZK/srConfirm', array('batchId' => $batchId)), C('BANK_USER_ID'), null, null, 0, null);
                $this->success('上传成功');
            } else {
                $srTempModel->deleteBatch($batchId);

                $errorMessage = 'Excel第'.($rowIndex + 1).'行: ';
                if ($errorCode == 1) {
                    $this->error($errorMessage.'导入的应收账款已存在，请不要重复导入');
                } else if ($errorCode == 2) {
                    $this->error($errorMessage.'已逾期，无法导入');
                } else if ($errorCode == 3) {
                    $this->error($errorMessage.'格式错误，无法导入。');
                }
            }
        }
    }

    public function importHxExcel() {
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();
        $upload->allowExts = array('xlsm', 'xls', 'xlsx');
        $upload->savePath = APP_PATH.'Upload/';
        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        } else {
            $info = $upload->getUploadFileInfo();
            $savePath = $info[0]['savepath'];
            $saveName = $info[0]['savename'];
            $sheetsData = getDataFromExcel($savePath.$saveName);

            $hxTempModel = D('HxTemp');
            $supplierLimitModel = D('SupplierLimit');
            $yszkModel = D('YSZK');
            $batchId = uniqid();
            $errorCode = 0;
            $hxSheetRows = $sheetsData[0];
            $hxSheetRowCount = count($hxSheetRows);
            for ($rowIndex = 0; $rowIndex < $hxSheetRowCount; $rowIndex++) {
                if ($rowIndex != 0 && $rowIndex != $hxSheetRowCount - 1) {
                    $row = $hxSheetRows[$rowIndex];

                    $supplierId = session('user')['ID'];
                    $buyerName = $row[1];
                    $ysNo = $row[2];
                    $amount = $row[3];
                    $xzAmount = $row[4];
                    $xzDate = $row[5];

                    if (!$yszkModel->checkDuplicate($ysNo)) {
                        $errorCode = 1;
                        break;
                    }

                    $result = $hxTempModel->importData($batchId, $supplierId, $buyerName, $ysNo, $amount, $xzAmount, $xzDate, $saveName);
                    if (!$result) {
                        $errorCode = 2;
                        break;
                    }
                }
            }

            if ($errorCode == 0) {
                $todoModel = D('Todo');
                $todoModel->addTodo(C('TODO_HX_NAME'), U('YSZK/hxConfirm', array('batchId' => $batchId)), C('BANK_USER_ID'), null, null, 0, null);
                $this->success('上传成功');
            } else {
                $hxTempModel->deleteBatch($batchId);

                $errorMessage = 'Excel第'.($rowIndex + 1).'行: ';
                if ($errorCode == 1) {
                    $this->error($errorMessage.'应收账款不存在，请检查');
                } else if ($errorCode == 2) {
                    $this->error($errorMessage.'格式错误，无法导入。');
                }
            }
        }
    }

    public function srConfirm() {
        $batchId = I('batchId');
        $todoId = I('todoId');
        $srTempModel = D('SrTemp');
        $rs = $srTempModel->queryByBatchId($batchId);
        if ($rs) {
            $data = json_encode($rs);
        } else {
            $data = '[]';
        }
        $this->assign(array(
            'data' => $data,
            'todoId' => $todoId,
            'excel' => $rs[0]['EXCEL']
        ));
        $this->display();
    }

    public function hxConfirm() {
        $batchId = I('batchId');
        $todoId = I('todoId');
        $hxTempModel = D('HxTemp');
        $rs = $hxTempModel->queryByBatchId($batchId);
        if ($rs) {
            $data = json_encode($rs);
        } else {
            $data = '[]';
        }
        $this->assign(array(
            'data' => $data,
            'todoId' => $todoId,
            'excel' => $rs[0]['EXCEL'],
        ));
        $this->display();
    }

    public function srImport() {
        $importData = $_REQUEST['import-data'];
        $todoId = I('todoId');
        $dataArray = json_decode($importData);
        if (count($dataArray) > 0) {
            $supplierLimitModel = D('SupplierLimit');
            $zxTime = intval($supplierLimitModel->getZxTime($dataArray[0]->SUPPLIER_ID));

            $batchId = $dataArray[0]->BATCH_ID;
            $yszkModel = D('YSZK');
            for ($index = 0; $index < count($dataArray); $index++) {
                $obj = $dataArray[$index];

                $data['ys_no'] = $obj->YS_NO;
                $data['supplier_id'] = $obj->SUPPLIER_ID;
                $data['buyer_name'] = $obj->BUYER_NAME;
                $data['kp_date'] = $obj->KP_DATE;
                $data['amount'] = $obj->AMOUNT;
                $data['currency'] = $obj->CURRENCY;
                $data['rmb_amount'] = $this->computeRmbAmount($obj->CURRENCY, $obj->AMOUNT);
                $data['sr_amount'] = $data['amount'];

                $kpDateObj = new DateTime($data['kp_date']);
                $kpDateObj->add(new DateInterval('P'.$zxTime.'D'));
                $data['zx_end_date'] = $kpDateObj->format('m/d/Y');

                $data['sr_op_date'] = date('m/d/Y');

                $yszkModel->import($data);
            }
            $yszkModel->calBuyerRate($dataArray[0]->SUPPLIER_ID);

            $srTempModel = D('SrTemp');
            $srTempModel->deleteBatch($batchId);
            $todoModel = D('Todo');
            $todoModel->completeTodo($todoId);
            $this->success('确认成功', U('Todo/index'));
        } else {
            $this->error('没有数据');
        }
    }

    public function hxImport() {
        $importData = $_REQUEST['import-data'];
        $todoId = I('todoId');
        $dataArray = json_decode($importData);
        if (count($dataArray) > 0) {
            $batchId = $dataArray[0]->BATCH_ID;
            $yszkModel = D('YSZK');
            for ($index = 0; $index < count($dataArray); $index++) {
                $obj = $dataArray[$index];

                $ysNo = $obj->YS_NO;
                $xzAmount = $obj->XZ_AMOUNT;
                $xzDate = $obj->XZ_DATE;
                $xzOpDate = date('m/d/Y');

                $yszkModel->hx($ysNo, $xzAmount, $xzDate, $xzOpDate);
            }
            $yszkModel->updateXzFlag();
            $yszkModel->calBuyerRate($dataArray[0]->SUPPLIER_ID);

            $hxTempModel = D('HxTemp');
            $hxTempModel->deleteBatch($batchId);
            $todoModel = D('Todo');
            $todoModel->completeTodo($todoId);
            $this->success('确认成功', U('Todo/index'));
        } else {
            $this->error('没有数据');
        }
    }

    public function exportYSZK() {
        $supplier = I('supplier');
        $buyerName = I('buyer_name');
        $ysNo = I('ys_no');
        $currency = I('currency');
        $expFlag = I('exp_flag');
        $xzFlag = I('xz_flag');
        $srOpDate = I('sr_op_date');
        $xzOpDate = I('xz_op_date');

        $yszkModel = D('YSZK');
        $rs = $yszkModel->queryYSZK($supplier, $buyerName, $ysNo, $currency, $expFlag, $xzFlag, $srOpDate, $xzOpDate);
        exportExcel('应收账款信息', array(
            array('SUPPLIER','物流金融服务商'),
            array('BUYER_NAME','买方名称'),
            array('YS_NO','应收账款编号'),
            array('KP_DATE', '开票日'),
            array('AMOUNT', '金额'),
            array('CURRENCY', '币种'),
            array('RMB_AMOUNT', '金额'),
            array('SR_AMOUNT', '受让金额'),
            array('HX_AMOUNT', '核销金额'),
            array('BUYER_RATE', '买方比例'),
            array('SR_OP_DATE', '受让操作日期'),
            array('ZX_END_DATE', '债项到期日'),
            array('EXP_FLAG', '是否已到期'),
            array('XZ_FLAG', '是否销账'),
            array('XZ_DATE', '销账日期'),
            array('XZ_OP_DATE', '销账操作日期')), $rs);
    }

    private function computeRmbAmount($currency, $amount) {
        if ($currency == '0') {
            return $amount;
        } else {
            $rmbUsdModel = D('RmbUsd');
            $rate = $rmbUsdModel->getRate($currency);
            return $amount * $rate;
        }
    }

    private function formatDateString($dateString) {
        $dateString = str_replace('-', '/', $dateString);
        $strArray = explode('/', $dateString);
        return $strArray[0].'/'.$strArray[1].'/20'.$strArray[2];
    }

}

?>