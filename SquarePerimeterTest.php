<?php

function perimeter(int $n): float|int
{
    $index = 0;
    $result = [1, 1];

    while (count($result) <= $n) {
        $result[] = $result[$index] + $result[$index + 1];
        $index++;
    }

    return array_sum($result) * 4;
}

class SquarePerimeterTest extends \PHPUnit\Framework\TestCase
{
    private static function dotest(int $n, int $expected) {
        $message = "n = " . (string)$n . "\n";
        $actual = perimeter($n);
        self::assertSame($expected, $actual, $message);
    }

    public function testBasics() {
        self::dotest(5, 80);
//        self::dotest(7, 216);
//        self::dotest(20, 114624);
//        self::dotest(30, 14098308);

//        self::dotest(40, 1733977744);
//        self::dotest(50, 213265164688);
//        self::dotest(60, 26229881279364);
//        self::dotest(70, 3226062132197568);
    }
}