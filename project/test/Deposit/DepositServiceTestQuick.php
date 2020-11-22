<?php

use PHPUnit\Framework\TestCase;
use Operation\DepositService;

require_once __DIR__.'./../../src/deposit/DepositService.php';

final class DepositServiceTestQuick extends TestCase {
    
    /**
    * @dataProvider depositDataProvider
    */
    function testDepositWith2StubMethod($acc_no,$deposit,$is_error,$error_message) {
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->onlyMethods(['accountAuthenticationProvider','saveTransaction'])
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
        $this->assertEquals($is_error, $result['isError']);
        $this->assertEquals($error_message, isset($result['message'])?$result['message']:'');
    }

    /**
    * @dataProvider depositDataProvider
    */
    function testDepositWith1StubMethod($acc_no,$deposit,$is_error,$error_message) {
        $mock = $this->getMockBuilder(DepositService::class)
            ->setConstructorArgs(['acctNum'=>$acc_no])    
            ->onlyMethods(['saveTransaction'])
            ->getMock();
        $stub = $mock->method('saveTransaction');
        $stub->willReturn(true);
        $result = $mock->deposit($deposit);
        $this->assertEquals($is_error, $result['isError']);
        $this->assertEquals($error_message, isset($result['message'])?$result['message']:'');
    }

    /**
    * @dataProvider depositDataProvider
    */
    function testDepositWithRealData($acc_no,$deposit,$is_error,$error_message) {
        $depositObj =  new DepositService($acc_no);
        $result = $depositObj->deposit($deposit);
        $this->assertEquals($is_error, $result['isError']);
        $this->assertEquals($error_message, isset($result['message'])?$result['message']:'');
    }
    
    function depositDataProvider()
    {
        return [
            

            ['acc_no'=>'7666555444','deposit'=>'2500','is_error'=>true,'error_message'=>'Account number : 7666555444 not found.'],
            ['acc_no'=>'abcdefgash','deposit'=>'3000','is_error'=>true,'error_message'=>'Account no. must be numeric!'],
            ['acc_no'=>'1234567890','deposit'=>'Twenty','is_error'=>true,'error_message'=>'Amount must be numeric!'],
            ['acc_no'=>'11248291321','deposit'=>'3000','is_error'=>true,'error_message'=>'Account no. must have 10 digit!'],
            ['acc_no'=>'198182938','deposit'=>'4444','is_error'=>true,'error_message'=>'Account no. must have 10 digit!'],
            ['acc_no'=>'1234567890','deposit'=>'0','is_error'=>true,'error_message'=>'จำนวนเงินฝากเข้าระบบต้องมากกว่า 0 บาท'],
            ['acc_no'=>'0238218583','deposit'=>'100001','is_error'=>true,'error_message'=>'จำนวนเงินฝากเข้าระบบต้องไม่เกิน 100,000 บาทต่อครั้ง'],
            ['acc_no'=>'4312531892','deposit'=>'15000','is_error'=>false,'error_message'=>''],

        ];
    }   
}
