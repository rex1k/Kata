<?php

function mystery(int $int): int
{
    if ($int >= 100000) {
        return $int ^ ($int >> 1);
    }

    $example = [0, 1];
    $temp = [];
    while (count($temp) <= $int) {
        $temp = empty($temp) ? $example : $temp;
        $straight = array_map(fn ($item) => '0' . $item, $temp);
        $reverse = array_map(fn ($item) => '1' . $item, array_reverse($temp));
        $temp = array_merge($straight, $reverse);
        unset($straight, $reverse);
        unset($example);
    }

    return bindec($temp[$int]);
}

function mystery_inv(int $int): int
{
    if ($int >= 100000) {
        $result = 0;
        while ($int > 0) {
            $result ^= $int;
            $int >>= 1;
        }
        return $result;
    }

    $bin = decbin($int);
    $example = [0, 1];
    $temp = [];

    while (!in_array($bin, $temp)) {
        $temp = empty($temp) ? $example : $temp;
        $straight = array_map(fn ($item) => '0' . $item, $temp);
        $reverse = array_map(fn ($item) => '1' . $item, array_reverse($temp));
        $temp = array_merge($straight, $reverse);
    }

    return array_search($bin, $temp);
}

class MysteryFunctionTest extends \PHPUnit\Framework\TestCase
{
    public function testExamples() {
        $this->assertSame(5, mystery(6), "mystery(6) ");
        $this->assertSame(6, mystery_inv(5), "mysteryInv(5)");
        $this->assertSame(13, mystery(9), "mystery(9) ");
        $this->assertSame(9, mystery_inv(13), "mysteryInv(13)");
        $this->assertSame(26, mystery(19), "mystery(19) ");
        $this->assertSame(19, mystery_inv(26), "mysteryInv(26)");
    }
}