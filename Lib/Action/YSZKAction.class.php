<?php

class YSZKAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    public function queryPool() {
        $this->display();
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
                    $kpDate = $row[3];
                    $ysEndDate = $row[4];
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

    public function srConfirm() {
        $batchId = I('batchId');
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
        ));
        $this->display();
    }

    public function srImport() {
        $importData = $_REQUEST['import-data'];
        $dataArray = json_decode($importData);
        if (count($dataArray) > 0) {
            $batchId = $dataArray[0]->BATCH_ID;
            $yszkModel = D('YSZK');
            for ($index = 0; $index < count($dataArray); $index++) {
                $obj = $dataArray[$index];

                $data['batch_id'] = $obj->BATCH_ID;
                $data['l_f_supplier'] = $obj->L_F_SUPPLIER;
                $data['buyer_name'] = $obj->BUYER_NAME;
                $data['ys_no'] = $obj->YS_NO;
                $data['kp_date'] = $obj->KP_DATE;
                $data['ys_end_date'] = $obj->YS_END_DATE;
                $data['amount'] = $obj->ORI_AMOUNT;
                $data['currency'] = $obj->CURRENCY;

                $yszkModel->import($data);
            }

            $srTempModel = D('SrTemp');
            $srTempModel->deleteBatch($batchId);
        }
    }

}

?>