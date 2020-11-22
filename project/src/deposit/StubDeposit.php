<?php namespace Stub;

class StubDeposit
{
    private $accNo;

    public function __construct(string $accNo)
    {
        $this->accNo = $accNo;
    }

    public function deposit(string $amount): array
    {
        if ($this->accNo == '9876543210') {
            return array("isError" => false,"accNo" => $this->accNo, "accName" => "TestAccountName", "accBalance" => $amount);
        } else {
            return array("isError" => true, "message" => "Account number : " . $this->accNo . " not found.");
        }
    }
}