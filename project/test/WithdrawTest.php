<?php

use PHPUnit\Framework\TestCase;

/*require_once __DIR__.'./../src/outputs/Outputs.php';*/
require_once __DIR__.'./../src/withdraw/Withdrawal.php';
require_once __DIR__.'./../src/withdraw/StubWithdrawal.php';
require_once __DIR__.'./../src/serviceauthentication/DBConnection.php';
use Operation\Withdrawal;
use Stub\StubWithdrawal;

final class WithdrawTest extends TestCase {
    function testAccountNumberIsNumeric() {
        $result = new Withdrawal("abc");
        $response = $result->withdraw(20000);
        $this->assertEquals(array("isError" => true, "message" => "หมายเลขบัญชีต้องเป็นตัวเลขเท่านั้น"), $response);
    }

    function testAccountNumberMoreThan10Digits() {
        $result = new Withdrawal("23112121213");
        $response = $result->withdraw(20000);
        $this->assertEquals(array("isError" => true, "message" => "หมายเลขบัญชีต้องมีครบทั้ง 10 หลัก"), $response);
    }

    function testAccountNumberLessThan10Digits() {
        $result = new Withdrawal("231121212");
        $response = $result->withdraw(20000);
        $this->assertEquals(array("isError" => true, "message" => "หมายเลขบัญชีต้องมีครบทั้ง 10 หลัก"), $response);
    }

    function testAccountNumberNotFound() {
        $accNo = "2311212121";
        $result = new Withdrawal($accNo);
        $response = $result->withdraw(20000);
        $this->assertEquals(array("isError" => true, "message" => "Account number : " . $accNo . " not found."), $response);
    }

    function testWithdrawalAmountIsNotNumeric() {
        $result = new Withdrawal("1234567890");
        $response = $result->withdraw("abc");
        $this->assertEquals(array("isError" => true, "message" => "จำนวนเงินถอนต้องเป็นตัวเลขเท่านั้น"), $response);
    }

    function testWithdrawalAmountLessThanOne() {
        $result = new Withdrawal("1234567890");
        $response = $result->withdraw(-1);
        $this->assertEquals(array("isError" => true, "message" => "จำนวนเงินถอนต้องมากกว่า 0 บาท"), $response);
    }

    function testWithdrawalAmountWithDecimal() {
        $result = new Withdrawal("1234567890");
        $response = $result->withdraw(1.5);
        $this->assertEquals(array("isError" => true, "message" => "จำนวนเงินถอนต้องเป็นจำนวนเต็มเท่านั้น"), $response);
    }

    function testWithdrawalAmountIsValid() {
        $accNo = "1234567890";
        $amount = 5000;
        $result = new Withdrawal($accNo);
        $response = $result->withdraw($amount);
        $this->assertEquals(false, $response['isError']);
    }

     function testWithdrawalAmountLimit() {
        $result = new Withdrawal("1234567890");
        $response = $result->withdraw(60000);
        $this->assertEquals(array("isError" => true, "message" => "ยอดเงินที่ต้องการถอนต้องไม่เกิน 50,000 บาทต่อรายการ"), $response);
    }

    function testSufficientAmount() {
        $result = new Withdrawal("1234567890");
        $response = $result->withdraw(20000);
        $this->assertEquals(array("isError" => true, "message" => "ยอดเงินในบัญชีไม่เพียงพอ"), $response);
    }

    function testUpdatedBalance() {
        $result = new StubWithdrawal();
        $response = $result->saveTransaction("1234567890",20000);
	    $this->assertEquals(true, $response);
        
    }
}
