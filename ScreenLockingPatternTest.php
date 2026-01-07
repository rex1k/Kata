<?php

function countPatternsFrom(string $startChar, int $length, array $visited = []): int
{
    if ($length === 0 || $length > 9) {
        return 0;
    }

    if ($length === 1) {
        return 1;
    }

    $board = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
    $rules = [
        'A' => ['D' => 'G', 'E' => 'I', 'B' => 'C'],
        'B' => ['E' => 'H'],
        'C' => ['E' => 'G', 'F' => 'I', 'B' => 'A'],
        'D' => ['E' => 'F'],
        'E' => [],
        'F' => ['E' => 'D'],
        'G' => ['D' => 'A', 'E' => 'C', 'H' => 'I'],
        'H' => ['E' => 'B'],
        'I' => ['E' => 'A', 'F' => 'C', 'H' => 'G'],
    ];
    $result = 0;
    $visited[] = $startChar;

    $moves = getMoves($startChar, $visited, $board, $rules);
    foreach ($moves as $move) {
        $result += countPatternsFrom($move, $length - 1, $visited);
    }

    return $result;
}

function getMoves(string $current, array $visited, array $board, array $rules): array
{
    $moves = [];

    foreach ($board as $element) {
        if (in_array($element, $visited)) {
            continue;
        }

        $hasBlock = in_array($element, $rules[$current]);
        if ($hasBlock) {
            $blockKey = array_search($element, $rules[$current]);

            if (in_array($blockKey, $visited)) {
                $moves[] = $element;
            }
        } else {
            $moves[] = $element;
        }
    }

    return array_unique($moves);
}

function findPosition(string $start, array $board): array
{
    foreach ($board as $rowNumber => $row) {
        foreach ($row as $columnNumber => $element) {
            if ($element === $start) {
                return [$rowNumber, $columnNumber];
            }
        }
    }

    return [];
}

class ScreenLockingPatternTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicTests()
    {
        $this->assertSame(0, countPatternsFrom('A', 0));
        $this->assertSame(0, countPatternsFrom('A', 10));
        $this->assertSame(1, countPatternsFrom('B', 1));
        $this->assertSame(5, countPatternsFrom('C', 2));
        $this->assertSame(37, countPatternsFrom('D', 3));
        $this->assertSame(256, countPatternsFrom('E', 4));
        $this->assertSame(23280, countPatternsFrom('E', 8));
    }
}