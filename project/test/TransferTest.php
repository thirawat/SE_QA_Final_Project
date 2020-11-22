<?php

use PHPUnit\Framework\TestCase;
use Operation\transfer;

require_once __DIR__.'./../src/transfer/transfer.php';


final class TransferTest extends TestCase {

    function testTransferSuccess() {

        $srcAccNo =  '1234567890';
        $srcAccName = 'Walter Greenhalgh';
        $tran = new transfer($srcAccNo,$srcAccName);
        $srcAccBalance = 10000;

        $targetNumber = '9876543210';
        $targetAmount = 2000;

        $result = $tran->doTransfer($targetNumber,$targetAmount);

        $this->assertEquals(($srcAccBalance - $targetAmount), $result['accBalance']);
        $this->assertFalse($result['isError']);
        $this->assertEquals("",$result['message']);
    }

    // //TC-TF-001
    function testTransferInvalidAccount() {

        $srcAccNo =  '1234567890';
        $srcAccName = 'Walter Greenhalgh';
        $tran = new transfer($srcAccNo,$srcAccName);
        $srcAccBalance = '10000';

        $targetNumber = '6565656565';
        $targetAmount = '2000';

        $result = $tran->doTransfer($targetNumber,$targetAmount);

        $this->assertFalse(!$result['isError']);
        $this->assertEquals("Account number : " . $targetNumber . " not found.",$result['message']);
    }

    //TC-TF-002
    function testTransferInvalidTarget() {

        $srcAccNo =  '1234567890';
        $srcAccName = 'Walter Greenhalgh';
        $tran = new transfer($srcAccNo,$srcAccName);
        $srcAccBalance = 500000;

        $targetNumber = 'degcbtynft';
        $targetAmount = 20000;

        $result = $tran->doTransfer($targetNumber,$targetAmount);

        $this->assertFalse(!$result['isError']);
        $this->assertEquals("หมายเลขบัญชีต้องเป็นตัวเลขเท่านั้น",$result['message']);
    }

    //TC-TF-003
    function testTransferOnlyNumber() {

        $srcAccNo =  '3455677565';
        $srcAccName = 'Omar Reilly';
        $tran = new transfer($srcAccNo,$srcAccName);
        $srcAccBalance = 500000;

        $targetNumber = '1234567890';
        $targetAmount = 'one-hundred';

        $result = $tran->doTransfer($targetNumber,$targetAmount);

        $this->assertFalse(!$result['isError']);
        $this->assertEquals("จำนวนเงินต้องเป็นตัวเลขเท่านั้น",$result['message']);
    }

    //TC-TF-004  TC-TF-005
    function testTransferTenDigitAccount() {

        $srcAccNo =  '3455677565';
        $srcAccName = 'Omar Reilly';
        $tran = new transfer($srcAccNo,$srcAccName);
        $srcAccBalance = 500000;

        $targetNumber = '2222';
        $targetAmount = 20000;

        $result = $tran->doTransfer($targetNumber,$targetAmount);

        $this->assertFalse(!$result['isError']);
        $this->assertEquals("หมายเลขบัญชีต้องมีจำนวน 10 หลัก",$result['message']);
    }

    //TC-TF-006
    function testTransferZeroAmount() {

        $srcAccNo =  '3455677565';
        $srcAccName = 'Omar Reilly';
        $tran = new transfer($srcAccNo,$srcAccName);
        $srcAccBalance = 500000;

        $targetNumber = '1234567890';
        $targetAmount = 0;

        $result = $tran->doTransfer($targetNumber,$targetAmount);

        $this->assertFalse(!$result['isError']);
        $this->assertEquals("ยอดการโอนต้องมากกว่า 0 บาท",$result['message']);
    }

    //TC-TF-007
    function testTransferNotEnoughMoney() {

        $srcAccNo =  '3455677565';
        $srcAccName = 'Omar Reilly';
        $tran = new transfer($srcAccNo,$srcAccName);
        $srcAccBalance = 500000;

        $targetNumber = '1234567890';
        $targetAmount = 5000000;

        $result = $tran->doTransfer($targetNumber,$targetAmount);

        $this->assertFalse(!$result['isError']);
        $this->assertEquals("คุณมียอดเงินในบัญชีไม่เพียงพอ",$result['message']);
    }

    //TC-TF-008
    function testTransferBeyondLimit() {

        $srcAccNo =  '3455677565';
        $srcAccName = 'Omar Reilly';
        $tran = new transfer($srcAccNo,$srcAccName);
        $srcAccBalance = 500000;

        $targetNumber = '1234567890';
        $targetAmount = 100000000000000;

        $result = $tran->doTransfer($targetNumber,$targetAmount);

        $this->assertFalse(!$result['isError']);
        $this->assertEquals("ยอดการโอนต้องไม่มากกว่า 9,999,999 บาท",$result['message']);
    }
}