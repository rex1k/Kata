<?php

use PHPUnit\Framework\TestCase;

function permutations(string $s): array
{
    if (strlen($s) <= 1) {
        return [$s];
    }

    $split = str_split($s);
    $result = [];

    for ($i = 0; $i <= strlen($s) - 1; $i++) {
        if ($i > 0 && $split[$i] === $split[$i - 1]) {
            continue;
        }

        $current = $split[$i];
        $substr = substr($s, 0, $i) . substr($s, $i + 1);

        foreach (permutations($substr) as $perm) {
            $result[] = $current . $perm;
        }
    }

    return array_unique($result);

}

final class PermutationsTest extends TestCase
{
    private function assertEquivalent(array $expected, array $actual, string $msg = ''): void
    {
        sort($expected);
        sort($actual);
        $this->assertSame($expected, $actual, $msg);
    }

    public function testDescriptionExamples()
    {
        $this->assertEquivalent(['a'], permutations('a'));
        $this->assertEquivalent(['ab', 'ba'], permutations('ab'));
        $this->assertEquivalent(['aabb', 'abab', 'abba', 'baab', 'baba', 'bbaa'], permutations('aabb'));
        $this->assertEquivalent([
            0 => 'abcd',
            1 => 'abdc',
            2 => 'acbd',
            3 => 'acdb',
            4 => 'adbc',
            5 => 'adcb',
            6 => 'bacd',
            7 => 'badc',
            8 => 'bcad',
            9 => 'bcda',
            10 => 'bdac',
            11 => 'bdca',
            12 => 'cabd',
            13 => 'cadb',
            14 => 'cbad',
            15 => 'cbda',
            16 => 'cdab',
            17 => 'cdba',
            18 => 'dabc',
            19 => 'dacb',
            20 => 'dbac',
            21 => 'dbca',
            22 => 'dcab',
            23 => 'dcba',
        ], permutations('bcad'));
    }
}