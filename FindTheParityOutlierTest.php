<?php

function find(array $array): int
{
    if (count($array) < 3) {
        return 0;
    }

    $find = getFind($array);
    foreach ($array as $element) {
        switch ($find) {
            case 'even':
                if ($element % 2 !== 0) {
                    return $element;
                }
                break;
            case 'odd':
                if ($element % 2 === 0) {
                    return $element;
                }
                break;
        }
    }

    return 0;
}

function getFind(array $array): string
{
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] % 2 === 0 && $array[count($array) - ($i + 1)] % 2 === 0) {
            return 'even';
        }

        if ($array[$i] % 2 !== 0 && $array[count($array) - ($i + 1)] % 2 !== 0) {
            return 'odd';
        }
    }

    return '';
}

class FindTheParityOutlierTest extends \PHPUnit\Framework\TestCase
{
    public function testBasic() {
//        $this->assertSame(101, find([100, 101, 102]));
//        $this->assertSame(100, find([1, 9, 27, 81, 100]));
//        $this->assertSame(11, find([2, 4, 0, 100, 4, 11, 2602, 36]));
        $this->assertSame(160, find([160, 3, 1719, 19, 11, 13, -21]));
    }
}