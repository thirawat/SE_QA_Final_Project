<?php

use PHPUnit\Framework\TestCase;
use Operation\DepositService;

require_once __DIR__.'./../../src/deposit/DepositService.php';

final class DepositServiceTest extends TestCase {
    
    function testNoAccountInDBWith2ServiceAuthenticationAndSaveTransactionStub() {
        $acc_no = '7666555444';
        $deposit = '2500';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['accountAuthenticationProvider','saveTransaction'])
            ->getMock();
        $stub1 = $mock->method('accountAuthenticationProvider');
        $stub2 = $mock->method('saveTransaction');
        if($acc_no == '4312531892')
        {
            $stub1->willReturn([
                "accNo" => '4312531892',
                "accBalance" => 2000,
                "accName" => 'Demo',
            ]);
        }
        else
        {
            $stub1->will($this->throwException(new AccountInformationException("Account number : {$acc_no} not found.")));
        }
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account number : 7666555444 not found.', $result['message']);
    }

    function testAccountNotNumberWith2ServiceAuthenticationAndSaveTransactionStub() {
        $acc_no = 'abcdefgash';
        $deposit = '3000';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['accountAuthenticationProvider','saveTransaction'])
            ->getMock();
        $stub1 = $mock->method('accountAuthenticationProvider');
        $stub1->willReturn([
            "accNo" => '4312531892',
            "accBalance" => 2000,
            "accName" => 'Demo',
        ]);

        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must be numeric!', $result['message']);
    }

    function testDepositNotNumberWith2ServiceAuthenticationAndSaveTransactionStub() {
        $acc_no = '1234567890';
        $deposit = 'Twenty';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['accountAuthenticationProvider','saveTransaction'])
            ->getMock();
        $stub1 = $mock->method('accountAuthenticationProvider');
        $stub1->willReturn([
            "accNo" => '4312531892',
            "accBalance" => 2000,
            "accName" => 'Demo',
        ]);

        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Amount must be numeric!', $result['message']);
    }

    function testAccountNumberLengthMoreThan10With2ServiceAuthenticationAndSaveTransactionStub() {
        $acc_no = '11248291321';
        $deposit = '3000';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['accountAuthenticationProvider','saveTransaction'])
            ->getMock();
        $stub1 = $mock->method('accountAuthenticationProvider');
        $stub1->willReturn([
            "accNo" => '4312531892',
            "accBalance" => 2000,
            "accName" => 'Demo',
        ]);

        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must have 10 digit!', $result['message']);
    }

    function testAccountNumberLengthLessThan10With2ServiceAuthenticationAndSaveTransactionStub() {
        $acc_no = '198182923';
        $deposit = '3000';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['accountAuthenticationProvider','saveTransaction'])
            ->getMock();
        $stub1 = $mock->method('accountAuthenticationProvider');
        $stub1->willReturn([
            "accNo" => '4312531892',
            "accBalance" => 2000,
            "accName" => 'Demo',
        ]);

        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must have 10 digit!', $result['message']);
    }

    function testDeposit0BahtWith2ServiceAuthenticationAndSaveTransactionStub() {
        $acc_no = '1234567890';
        $deposit = '0';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['accountAuthenticationProvider','saveTransaction'])
            ->getMock();
        $stub1 = $mock->method('accountAuthenticationProvider');
        $stub1->willReturn([
            "accNo" => '1234567890',
            "accBalance" => 2000,
            "accName" => 'Demo',
        ]);

        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('จำนวนเงินฝากเข้าระบบต้องมากกว่า 0 บาท', $result['message']);
    }

    function testDeposit100001BahtWith2ServiceAuthenticationAndSaveTransactionStub() {
        $acc_no = '0238218583';
        $deposit = '100001';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['accountAuthenticationProvider','saveTransaction'])
            ->getMock();
        $stub1 = $mock->method('accountAuthenticationProvider');
        $stub1->willReturn([
            "accNo" => '0238218583',
            "accBalance" => 2000,
            "accName" => 'Demo',
        ]);

        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('จำนวนเงินฝากเข้าระบบต้องไม่เกิน 100,000 บาทต่อครั้ง', $result['message']);
    }
    
    function testDepositCompleteWith2ServiceAuthenticationAndSaveTransactionStub() {
        $acc_no = '4312531892';
        $deposit = '15000';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['accountAuthenticationProvider','saveTransaction'])
            ->getMock();
        $stub1 = $mock->method('accountAuthenticationProvider');
        $stub1->willReturn([
            "accNo" => '4312531892',
            "accBalance" => 2000,
            "accName" => 'Demo',
        ]);

        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(false, $result['isError']);
    }

    function testNoAccountInDBWithSaveTransactionStub() {
        $acc_no = '7666555444';
        $deposit = '2500';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['saveTransaction'])
            ->getMock();
    
        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account number : 7666555444 not found.', $result['message']);
    }
    
    function testAccountNotNumberWithSaveTransactionStub() {
        $acc_no = 'abcdefgash';
        $deposit = '3000';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['saveTransaction'])
            ->getMock();
    
        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must be numeric!', $result['message']);
    }
    
    function testDepositNotNumberWithSaveTransactionStub() {
        $acc_no = '1234567890';
        $deposit = 'Twenty';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['saveTransaction'])
            ->getMock();
    
        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Amount must be numeric!', $result['message']);
    }
    
    function testAccountNumberLengthMoreThan10WithSaveTransactionStub() {
        $acc_no = '11248291321';
        $deposit = '3000';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['saveTransaction'])
            ->getMock();
    
        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must have 10 digit!', $result['message']);
    }
    
    function testAccountNumberLengthLessThan10WithSaveTransactionStub() {
        $acc_no = '198182923';
        $deposit = '3000';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['saveTransaction'])
            ->getMock();
    
        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must have 10 digit!', $result['message']);
    }
    
    function testDeposit0BahtWithSaveTransactionStub() {
        $acc_no = '1234567890';
        $deposit = '0';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['saveTransaction'])
            ->getMock();
        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('จำนวนเงินฝากเข้าระบบต้องมากกว่า 0 บาท', $result['message']);
    }
    
    function testDeposit100001BahtWithSaveTransactionStub() {
        $acc_no = '0238218583';
        $deposit = '100001';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['saveTransaction'])
            ->getMock();
    
        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('จำนวนเงินฝากเข้าระบบต้องไม่เกิน 100,000 บาทต่อครั้ง', $result['message']);
    }
    
    function testDepositCompleteWithSaveTransactionStub() {
        $acc_no = '4312531892';
        $deposit = '15000';
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->setMethods(['saveTransaction'])
            ->getMock();
    
        $stub2 = $mock->method('saveTransaction');
        $stub2->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals(false, $result['isError']);
    }   

    function testNoAccountInDBWithRealService() {
        $acc_no = '7666555444';
        $deposit = '2500';
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account number : 7666555444 not found.', $result['message']);
    }
    
    function testAccountNotNumberWithRealService() {
        $acc_no = 'abcdefgash';
        $deposit = '3000';
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must be numeric!', $result['message']);
    }
    
    function testDepositNotNumberWithRealService() {
        $acc_no = '1234567890';
        $deposit = 'Twenty';
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Amount must be numeric!', $result['message']);
    }
    
    function testAccountNumberLengthMoreThan10WithRealService() {
        $acc_no = '11248291321';
        $deposit = '3000';
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must have 10 digit!', $result['message']);
    }
    
    function testAccountNumberLengthLessThan10WithRealService() {
        $acc_no = '198182923';
        $deposit = '3000';
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('Account no. must have 10 digit!', $result['message']);
    }
    
    function testDeposit0BahtWithRealService() {
        $acc_no = '1234567890';
        $deposit = '0';
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('จำนวนเงินฝากเข้าระบบต้องมากกว่า 0 บาท', $result['message']);
    }
    
    function testDeposit100001BahtWithRealService() {
        $acc_no = '0238218583';
        $deposit = '100001';
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals(true, $result['isError']);
        $this->assertEquals('จำนวนเงินฝากเข้าระบบต้องไม่เกิน 100,000 บาทต่อครั้ง', $result['message']);
    }
    
    function testDepositCompleteWithRealService() {
        $acc_no = '4312531892';
        $deposit = '15000';
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals(false, $result['isError']);
    }   
}
