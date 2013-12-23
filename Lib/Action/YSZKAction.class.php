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
        $lfSupplier = I('l_f_supplier');
        $buyerName = I('buyer_name');
        $ysNo = I('ys_no');
        $currency = I('currency');
        $expFlag = I('exp_flag');
        $xzFlag = I('xz_flag');
        $srDate = I('sr_date');
        $xzDate = I('xz_date');

        if ($lfSupplier != '') {
            $where['l_f_supplier'] = $lfSupplier;
        }
        if ($buyerName != '') {
            $where['buyer_name'] = $buyerName;
        }
        if ($ysNo != '') {
            $where['ys_no'] = $ysNo;
        }
        if ($currency != '') {
            $where['currency'] = $currency;
        }
        if ($expFlag != '') {
            //$where['exp_flag'] = $expFlag;
        }
        if ($xzFlag != '') {
            $where['xz_flag'] = $xzFlag;
        }
        if ($srDate != '') {
            $where['sr_date'] = $srDate;
        }
        if ($xzDate != '') {
            $where['xz_date'] = $xzDate;
        }
        $yszx = D('YSZK');
        $rs = $yszx->where($where)->select();
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

                    $lfSupplier = $row[0];
                    $buyerName = $row[1];
                    $ysNo = $row[2];
                    $kpDate = $this->formatDateString($row[3]);
                    $ysEndDate = $this->formatDateString($row[4]);
                    $oriAmount = $row[5];
                    $currency = $row[6];

                    if ($yszkModel->checkDuplicate($ysNo)) {
                        $errorCode = 1;
                        break;
                    }

                    if ($supplierLimitModel->isExpire($lfSupplier, $kpDate)) {
                        $errorCode = 2;
                        break;
                    }

                    $result = $srTempModel->importData($batchId, $lfSupplier, $buyerName, $ysNo, $kpDate, $ysEndDate, $oriAmount, $currency);
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

                    $lfSupplier = $row[0];
                    $buyerName = $row[1];
                    $ysNo = $row[2];
                    $amount = $row[3];
                    $xzAmount = $row[4];
                    $xzDate = $row[5];

                    if (!$yszkModel->checkDuplicate($ysNo)) {
                        $errorCode = 1;
                        break;
                    }

                    $result = $hxTempModel->importData($batchId, $lfSupplier, $buyerName, $ysNo, $amount, $xzAmount, $xzDate);
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
        $where['batch_id'] = $batchId;
        $rs = $srTempModel->where($where)->select();
        if ($rs) {
            $data = json_encode($rs);
        } else {
            $data = '[]';
        }
        $this->assign(array(
            'data' => $data,
            'todoId' => $todoId,
        ));
        $this->display();
    }

    public function hxConfirm() {
        $batchId = I('batchId');
        $todoId = I('todoId');
        $hxTempModel = D('HxTemp');
        $where['batch_id'] = $batchId;
        $rs = $hxTempModel->where($where)->select();
        if ($rs) {
            $data = json_encode($rs);
        } else {
            $data = '[]';
        }
        $this->assign(array(
            'data' => $data,
            'todoId' => $todoId,
        ));
        $this->display();
    }

    public function srImport() {
        $importData = $_REQUEST['import-data'];
        $todoId = I('todoId');
        $dataArray = json_decode($importData);
        if (count($dataArray) > 0) {
            $supplierLimitModel = D('SupplierLimit');
            $zxMgtDays = intval($supplierLimitModel->getZxMgtDays($dataArray[0]->L_F_SUPPLIER));
            dump($zxMgtDays);

            $batchId = $dataArray[0]->BATCH_ID;
            $yszkModel = D('YSZK');
            for ($index = 0; $index < count($dataArray); $index++) {
                $obj = $dataArray[$index];

                $data['ys_no'] = $obj->YS_NO;
                $data['l_f_supplier'] = $obj->L_F_SUPPLIER;
                $data['buyer_name'] = $obj->BUYER_NAME;
                $data['kp_date'] = $obj->KP_DATE;
                $data['amount'] = $obj->ORI_AMOUNT;
                $data['currency'] = $obj->CURRENCY;
                $data['rmb_amount'] = $this->computeRmbAmount($obj->CURRENCY, $obj->ORI_AMOUNT);
                $data['sr_amount'] = $data['rmb_amount'];

                $kpDateObj = new DateTime($data['kp_date']);
                $kpDateObj->add(new DateInterval('P'.$zxMgtDays.'D'));
                $data['zx_end_date'] = $kpDateObj->format('m/d/Y');

                $data['sr_op_date'] = date('m/d/Y');

                $yszkModel->import($data);
            }
            $yszkModel->calBuyerRate($dataArray[0]->L_F_SUPPLIER);

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
            $yszkModel->calBuyerRate($dataArray[0]->L_F_SUPPLIER);

            $hxTempModel = D('HxTemp');
            $hxTempModel->deleteBatch($batchId);
            $todoModel = D('Todo');
            $todoModel->completeTodo($todoId);
            $this->success('确认成功', U('Todo/index'));
        } else {
            $this->error('没有数据');
        }
    }

    private function computeRmbAmount($currency, $amount) {
        if ($currency == 'RMB') {
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