<?php

function dblLinear(int $int): int
{
    $result = [1];
    $pointerY = 0;
    $pointerZ = 0;

    while (count($result) <= $int) {
        $y = $result[$pointerY] * 2 + 1;
        $z = $result[$pointerZ] * 3 + 1;

        if ($y < $z) {
            if (end($result) !== $y) {
                $result[] = $y;
                $pointerY++;
            }
        } elseif ($z < $y) {
            if (end($result) !== $z) {
                $result[] = $z;
                $pointerZ++;
            }
        } else {
            if (end($result) !== $y) {
                $result[] = $y;
            }
            $pointerZ++;
            $pointerY++;
        }
    }

    return $result[$int];
}

class DoubleLinearTest extends \PHPUnit\Framework\TestCase
{
    private function revTest($actual, $expected)
    {
        $this->assertSame($expected, $actual);
    }

    public function testBasics()
    {
        $this->revTest(dblLinear(10), 22);
        $this->revTest(dblLinear(20), 57);
        $this->revTest(dblLinear(30), 91);
        $this->revTest(dblLinear(50), 175);
        $this->revTest(dblLinear(100), 447);
    }
}