<?php

function dutyFree(int $price, int $discount, int $cost): int
{
    return $cost / ($price * ($discount / 100));
}

class HollydayDutyFreeTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicTests() {
        $this->assertSame(166, dutyFree(12, 50, 1000));
        $this->assertSame(294, dutyFree(17, 10, 500));
        $this->assertSame(357, dutyFree(24, 35, 3000));
    }
}