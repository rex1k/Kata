<?php

function calc(string $expression): float
{
    if (empty($expression)) {
        return 0;
    }

    $split = explode(' ', $expression);
    $stack = [];

    if (count($split) === 1) {
        return (float)$split[0];
    }

    if (count($split) === 2) {
        return (float)($split[1] . $split[0]);
    }

    $position = 0;
    while (count(explode(' ', $expression)) > 1) {
        $split = explode(' ', $expression);
        $element = $split[$position];

        if ($element === ' ') {
            $position++;
            continue;
        }

        if (!is_numeric($element)) {
            $result = calculate(reset($stack), end($stack), $element);
            $expression = str_replace(
                reset($stack) . ' ' . end($stack) . ' ' . $element,
                $result,
                $expression);
            $stack = [];
            $position = 0;
            continue;
        }

        if (count($stack) === 2) {
            unset($stack[array_key_first($stack)]);
        }

        $stack[$position] = $element;
        $position++;
    }

    return (float)$expression;
}

function calculate($first, $second, $operator): float
{
    return match ($operator) {
        '*' => $first * $second,
        '/' => $first / $second,
        '+' => $first + $second,
        '-' => $first - $second,
        default => 0,
    };
}

class ReversePolishCalcTest extends \PHPUnit\Framework\TestCase
{
    public function testExamples() {
        self::doTest(0, '');
        self::doTest(3, '3');
        self::doTest(3.5, '3.5');
        self::doTest(4, '1 3 +');
        self::doTest(3, '1 3 *');
        self::doTest(-2, '1 3 -');
        self::doTest(2, '4 2 /');
        self::doTest(10123, '10000 123 +');
        self::doTest(14, '5 1 2 + 4 * + 3 -');
        self::doTest(-2.44016439, '-4.556 3.7743 2.7 3.1 * 1.23 / + 5 / +');
        self::doTest(1, '-10 -2 -0.0133333333333333333333 300 2.5 -0.002 / - 50 - * / * -1 *');
    }

    private static function doTest(float $expected, string $expr) {
        $message = "expression = " . var_export($expr, true) . "\n";
        $actual = calc($expr);
// We want strong typing, but also to allow integer solutions when the
// result is exactly representable as an integer.
        if (is_int($actual))
            $actual = (float)$actual;
        self::assertIsFloat($actual, $message);
        self::assertEqualsWithDelta($expected, $actual, 1e-3, $message);
    }
}