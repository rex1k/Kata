<?php

function sudoku(array $sudoku): array // [ [5, 0,0,2,4 ...], [2,4,6,3...]
{
    list($row, $position) = findEmpty($sudoku);
    if ($row === null || $position === null) {
        return $sudoku;
    }

    for ($number = 0; $number <= 9; $number++) {


        if (checkIsValidPlace($row, $position, $number, $sudoku)) {
            $sudoku[$row][$position] = $number;

            if (isSolved($sudoku)) {
                return $sudoku;
            } elseif (isSolved(sudoku($sudoku))) {
                return $sudoku;
            } else {
                $sudoku[$row][$number] = 0;
            }
        }
    }

    return $sudoku;
}

function isSolved($sudoku) {
    // Проверяем строки
    for ($row = 0; $row < 9; $row++) {
        $seenColumn = [];
        $seenRow = [];
        for ($col = 0; $col < 9; $col++) {
            $num = $sudoku[$row][$col];
            if ($num != 0) {
                if (in_array($num, $seenRow) || in_array($num, $seenColumn)) {
                    return false;
                }

                $seenRow[] = $num;
                $seenColumn[] = $num;
            } else {
                return false;
            }
        }
    }

    // Проверяем квадраты 3x3
    for ($box = 0; $box < 9; $box++) {
        $seen = [];
        $startRow = floor($box / 3) * 3;
        $startCol = ($box % 3) * 3;

        for ($r = $startRow; $r < $startRow + 3; $r++) {
            for ($c = $startCol; $c < $startCol + 3; $c++) {
                $num = $sudoku[$r][$c];
                if ($num != 0) {
                    if (in_array($num, $seen)) {
                        return false;
                    }
                    $seen[] = $num;
                }
            }
        }
    }

    return true;
}

function findEmpty($sudoku) {
    foreach ($sudoku as $rowNumber => $row) {
        foreach ($row as $positionNumber => $number) {
            if ($number === 0) {
                return [$rowNumber, $positionNumber];
            }
        }
    }

    return null;
}

function printBoard($board, bool $showOriginal = false): void {
    echo "┌───────┬───────┬───────┐\n";

    for ($row = 0; $row < 10; $row++) {
        echo "│ ";

        for ($col = 0; $col < 10; $col++) {
            $num = $board[$row][$col];
            echo ($num == 0 ? "·" : $num) . " ";

            if (($col + 1) % 3 == 0 && $col != 8) {
                echo "│ ";
            }
        }

        echo "│\n";

        if (($row + 1) % 3 == 0 && $row != 8) {
            echo "├───────┼───────┼───────┤\n";
        }
    }

    echo "└───────┴───────┴───────┘\n";
}

function checkIsValidPlace($rowNumber, $position, $number, $board): bool
{
    for ($pos = 0; $pos < 9; $pos++) {
        if ($board[$rowNumber][$pos] === $number) {
            return false;
        }

        if ($board[$pos][$position] === $number) {
            return false;
        }
    }

    $boxRow = (int)($rowNumber / 3) * 3;
    $boxColumn = (int)($position / 3) * 3;

    for ($row = $boxRow; $row < $boxRow + 3; $row++) {
        for ($column = $boxColumn; $column < $boxColumn + 3; $column++) {
            if ($board[$row][$column] === $number) {
                return false;
            }
        }
    }

    return true;
}

class SudokuTest extends \PHPUnit\Framework\TestCase
{
    public function testDescriptionExample()
    {
        $this->assertSame([
            [5, 3, 4, 6, 7, 8, 9, 1, 2],
            [6, 7, 2, 1, 9, 5, 3, 4, 8],
            [1, 9, 8, 3, 4, 2, 5, 6, 7],
            [8, 5, 9, 7, 6, 1, 4, 2, 3],
            [4, 2, 6, 8, 5, 3, 7, 9, 1],
            [7, 1, 3, 9, 2, 4, 8, 5, 6],
            [9, 6, 1, 5, 3, 7, 2, 8, 4],
            [2, 8, 7, 4, 1, 9, 6, 3, 5],
            [3, 4, 5, 2, 8, 6, 1, 7, 9]
        ], sudoku([
            [5, 3, 0, 0, 7, 0, 0, 0, 0],
            [6, 0, 0, 1, 9, 5, 0, 0, 0],
            [0, 9, 8, 0, 0, 0, 0, 6, 0],
            [8, 0, 0, 0, 6, 0, 0, 0, 3],
            [4, 0, 0, 8, 0, 3, 0, 0, 1],
            [7, 0, 0, 0, 2, 0, 0, 0, 6],
            [0, 6, 0, 0, 0, 0, 2, 8, 0],
            [0, 0, 0, 4, 1, 9, 0, 0, 5],
            [0, 0, 0, 0, 8, 0, 0, 7, 9]
        ]));
    }
}