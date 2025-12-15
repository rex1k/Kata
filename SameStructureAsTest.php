<?php

function same_structure_as(array $first, array $second): bool
{
    $countFirst = count($first);
    $countSecond = count($second);

    if ($countFirst !== $countSecond) {
        return false;
    }

    $index = 0;
    while ($index < $countSecond) {
        if (
            (is_array($first[$index]) && !is_array($second[$index]))
            || (!is_array($first[$index]) && is_array($second[$index]))
        ) {
            return false;
        }

        if (is_array($first[$index]) && is_array($second[$index])) {
            $same = same_structure_as($first[$index], $second[$index]);

            if ($same === false) {
               return false;
            }
        }

        $index++;
    }

    return true;
}

class SameStructureAsTest extends \PHPUnit\Framework\TestCase
{
    public function testDescriptionExamples() {
        $this->assertTrue(same_structure_as([1, 1, 1], [2, 2, 2]));
        $this->assertTrue(same_structure_as([1, [1, 1]], [2, [2, 2]]));
        $this->assertFalse(same_structure_as([1, [1, 1]], [[2, 2], 2]));
        $this->assertFalse(same_structure_as([1, [1, 1]], [[2], 2]));
        $this->assertTrue(same_structure_as([[[], []]], [[[], []]]));
        $this->assertFalse(same_structure_as([[[], []]], [[1, 1]]));
    }
}