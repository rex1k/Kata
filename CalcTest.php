<?php

use PHPUnit\Framework\TestCase;

function calc(string $expression): float
{
    $queue = ['(\*|\/)', '+', '-'];
    $expression = loseBrackets($expression);
    $expression = normalizeDoubleMinus($expression);
    $match = [];

    while (containsOperator($expression)) {
        $operator = null;
        $split = str_split($expression);

        foreach ($split as $char) {
            if ($char === '*') {
                $operator = '\*';
                break;
            } elseif ($char === '/') {
                $operator = '\/';
                break;
            }
        }

        if ($operator === null) {
            foreach ($split as $char) {
                if ($char === '+') {
                    $operator = '\+';
                    break;
                } elseif ($char === '-') {
                    $operator = '\-';
                    break;
                }
            }
            if ($operator === null) {
                break;
            }
        }

        $regex = '/(?<expression>\s?[0-9]+(\.[0-9]+)?\s?' . $operator . '\s?[0-9]+(\.[0-9]+)?)/';
        preg_match($regex, $expression, $match);

        if (empty($match['expression'])) {
            if (preg_match('/^-?[0-9]+(\.[0-9]+)?\s?$/', $expression) === 1) {
                return (float)$expression;
            }
        }

        $result = evaluateExpression(0, strlen($match['expression']), $match['expression']);
        $expression = str_replace($match['expression'], $result, $expression);
    }

    return (float)$expression;
}

function normalizeDoubleMinus(string $expression): string
{
    $match = [];
    preg_match_all('/(?<exp>-\s?\(?\s?-([0-9]+(\.?[0-9]+)?)\)?)/', $expression, $match);

    foreach ($match['exp'] as $exp) {
        $current = [];
        preg_match('/(?<digit>[0-9]+(\.[0-9]+)?)/', $exp, $current);
        $expression = str_replace($exp, '+' . $current['digit'], $expression);
    }

    return $expression;
}

function containsOperator(string $expression): bool
{
    foreach (['*', '/', '+', '-'] as $operator) {
        if (str_contains($expression, $operator)) {
            return true;
        }
    }

    return false;
}

function loseBrackets(string $expression): string
{
    $start = null;
    $finish = null;
    $position = 0;
    $split = str_split($expression);

    while (str_contains($expression, '(') && str_contains($expression, ')')) {
        $char = $split[$position];

        if ($char === '(') {
            $start = $position;
        }

        if ($char === ')') {
            $finish = $position;
        }

        if ($start !== null && $finish !== null) {
            $expression = evaluateExpression($start, $finish, $expression);
            $split = str_split($expression);
            $start = null;
            $finish = null;
            $position = 0;
            continue;
        }

        $position++;
    }

    return $expression;
}

function evaluateExpression(int $start, int $finish, string $expression): string
{
    $result = '';
    $substr = substr($expression, $start, $finish - $start + 1);
    $match = [];
    preg_match('#(?<first>-?[0-9]+(\.[0-9]+)?)\s?(?<operator>[+\-/*])\s?(?<second>-?[0-9]+(\.[0-9]+)?)#', $substr, $match);
    $operator = $match['operator'];
    $first = $match['first'];
    $second = $match['second'];

    if (empty($second)) {
        return $first;
    }

    switch ($operator) {
        case '+':
            $result = $first + $second;
            break;
        case '-':
            $result = $first - $second;
            break;
        case '*':
            $result = $first * $second;
            break;
        case '/':
            $result = $first / $second;
            break;
        default:
            break;
    }

    return str_replace($substr, $result, $expression);
}

class CalcTest extends TestCase
{
    protected function randomize(array $a): array
    {
        for ($i = 0; $i < 2 * count($a); $i++) list($a[$j], $a[$k]) = [$a[$k = array_rand($a)], $a[$j = array_rand($a)]];
        return $a;
    }

    public function testShuffledExamples()
    {
        foreach ($this->randomize([
            ['1+1', 2.0],
            ['1 - 1', 0.0],
            ['1* 1', 1.0],
            ['1 /1', 1.0],
            ['-123', -123.0],
            ['123', 123.0],
            ['2 /2+3 * 4.75- -6', 21.25],
            ['12* 123', 1476.0],
            ['2 / (2 + 3) * 4.33 - -6', 7.732],
        ]) as $a) $this->assertSame($a[1], floatval(calc($a[0])));
    }
}