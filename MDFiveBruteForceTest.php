<?php

function crack(string $hash): string
{
    $max = 99999;

    for ($i = 0; $i <= $max; $i++) {
        $str = '';
        $length = strlen((string)$i);
        if ($length < 5) {
            $str = str_repeat('0', 5 - $length);
        }

        $str .= $i;
        if (md5($str) === $hash) {
            return $str;
        }
    }

    return '';
}

class MDFiveBruteForceTest extends \PHPUnit\Framework\TestCase
{
    public function test_easyPIN() {
        $result = crack("86aa400b65433b608a9db30070ec60cd");
        $this->assertSame($result, "00078");
    }
    public function test_hardPIN() {
        $result = crack("827ccb0eea8a706c4c34a16891f84e7b");
        $this->assertSame($result, "12345");
    }
}