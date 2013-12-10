<?php

class YSZKAction extends CommonAction {

    protected $config = array(
        'need_login' => true,
    );

    public function queryPool() {
        $this->defaultDisplay();
    }

    public function yszk() {
        $this->defaultDisplay();
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
            $batchId = uniqid();
            $hasError = false;
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

                    $result = $srTempModel->importData($batchId, $lfSupplier, $buyerName, $ysNo, $kpDate, $ysEndDate, $oriAmount, $currency);
                    if (!$result) {
                        $hasError = true;
                        break;
                    }
                }
            }

            if ($hasError) {
                $this->error('入库时出错: Excel第'.($rowIndex + 1).'行格式错误，无法导入。');
            } else {
                $this->success('上传成功');
            }
        }
    }

}

?>