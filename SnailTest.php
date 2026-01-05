<?php

use PHPUnit\Framework\TestCase;

function snail(array $array): array
{
    if (empty($array)) {
        return [];
    }

    $totalElements = count($array) * count($array[0]);
    $result = [];
    $direction = 'row';
    $forward = count($array[0]) - 1;
    $backward = 0;
    $up = 1;
    $down = count($array) - 1;
    $index = 0;
    $row = 0;

    while (count($result) < $totalElements) {
        $result[] = $array[$row][$index];

        switch ($direction) {
            case 'row':
                if ($index === $forward) {
                    $forward -= 1;
                    $direction = 'column';
                    $row++;
                    break;
                }

                $index++;
                break;
            case 'column':
                if ($row === $down) {
                    $down -= 1;
                    $direction = 'rowReverse';
                    $index--;
                    break;
                }

                $row++;
                break;
            case 'rowReverse':
                if ($index === $backward) {
                    $backward += 1;
                    $direction = 'columnReverse';
                    $row--;
                    break;
                }

                $index--;
                break;
            case 'columnReverse':
                if ($row === $up) {
                    $up += 1;
                    $direction = 'row';
                    $index++;
                    break;
                }

                $row--;
                break;
            default:
                break;
        }
    }

    return $result;
}

class SnailTest extends TestCase
{
    public function testDescriptionExamples()
    {
        $this->assertSame([1, 2, 3, 6, 9, 8, 7, 4, 5], snail([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9]
        ]));
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9], snail([
            [1, 2, 3],
            [8, 9, 4],
            [7, 6, 5]
        ]));
        $this->assertSame([1, 2, 3, 1, 4, 7, 7, 9, 8, 7, 7, 4, 5, 6, 9, 8], snail([
            [1, 2, 3, 1],
            [4, 5, 6, 4],
            [7, 8, 9, 7],
            [7, 8, 9, 7]
        ]));
        $this->assertSame([], snail([[]]), 'Your solution should also work properly for an empty matrix');
    }
}