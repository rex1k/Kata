<?php

function isValidIP(string $input): bool
{
    if ($input === '0.0.0.0') {
        return true;
    }

    foreach (explode('.', $input) as $chunk) {
        if (strlen($chunk) > 1 && str_starts_with($chunk, '0')) {
            return false;
        }

        if ((int)$chunk > 255) {
            return false;
        }
    }

    return preg_match('#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#', $input) === 1;
}

class ValidIpTest extends \PHPUnit\Framework\TestCase
{
//    public function testValid()
//    {
//        $valid = [
//            '0.0.0.0',
//            '255.255.255.255',
//            '238.46.26.43',
//            '0.34.82.53',
//        ];
//
//        foreach ($valid as $input) {
//            $this->assertTrue(isValidIP($input), "Failed asserting that '$input' is a valid IP4 address.");
//        }
//    }

    public function testInvalid()
    {
        $invalid = [
//            '',
//            'abc.def.ghi.jkl',
//            '123.456.789.0',
//            ' 1.2.3.4',
            '03.45.20.1',
//            '192.168.00.3'
        ];

        foreach($invalid as $input) {
            $this->assertFalse(isValidIP($input), "Failed asserting that '$input' is an invalid IP4 address.");
        }
    }
}