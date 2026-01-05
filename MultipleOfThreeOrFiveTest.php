<?php

use PHPUnit\Framework\TestCase;

function solution(int $int): int
{
    $result = 0;
    for ($i = 0; $i < $int; $i++) {
        if ($i < 3) {
            continue;
        }

        if ($i % 3 === 0 || $i % 5 === 0) {
            $result += $i;
        }
    }

    return $result;
}

class MultipleOfThreeOrFiveTest extends TestCase
{
    public function testSample() {
        $this->assertSame(23, solution(10));
    }
}