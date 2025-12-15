<?php

function getPINs(string $pin): array
{
    $possibleNumbers = [];
    foreach (str_split($pin) as $number) {
        $possibleNumbers[] = getPossibleNumber($number);
    }
    unset($number, $pin);

    return getAllCombinationsRecursive($possibleNumbers);
}

function getAllCombinationsRecursive(array $digits): array
{
    if (empty($digits)) {
        return [''];
    }

    $firstArray = array_shift($digits);
    $remains = getAllCombinationsRecursive($digits);

    $result = [];
    foreach ($firstArray as $value) {
        foreach ($remains as $remain) {
            $result[] = $value . $remain;
        }
    }

    return $result;
}

function getPossibleNumber(int $number): array
{
    $rules = [
        1 => [1, 2, 4],
        2 => [1, 2, 3, 5],
        3 => [2, 3, 6],
        4 => [1, 4, 5, 7],
        5 => [2, 4, 5, 6, 8],
        6 => [3, 5, 6, 9],
        7 => [4, 7, 8],
        8 => [5, 7, 8, 9, 0],
        9 => [6, 8, 9],
        0 => [8, 0]
    ];

    return $rules[$number];
}

class ObservedPINTest extends \PHPUnit\Framework\TestCase
{
    public function testSample()
    {
        $expectations = [
            "8" => ["5", "7", "8", "9", "0"],
            "11" => ["11", "22", "44", "12", "21", "14", "41", "24", "42"],
            "369" => ["339", "366", "399", "658", "636", "258", "268", "669", "668", "266", "369", "398", "256", "296", "259", "368", "638", "396", "238", "356", "659", "639", "666", "359", "336", "299", "338", "696", "269", "358", "656", "698", "699", "298", "236", "239"],
            "58" => [
                0 => '20',
                1 => '25',
                2 => '27',
                3 => '28',
                4 => '29',
                5 => '40',
                6 => '45',
                7 => '47',
                8 => '48',
                9 => '49',
                10 => '50',
                11 => '55',
                12 => '57',
                13 => '58',
                14 => '59',
                15 => '60',
                16 => '65',
                17 => '67',
                18 => '68',
                19 => '69',
                20 => '80',
                21 => '85',
                22 => '87',
                23 => '88',
                24 => '89',
            ],
        ];
        foreach ($expectations as $pin => $expect) {
            $actual = getPINs($pin);
            sort($actual);
            sort($expect);
            $this->assertSame($expect, $actual);
        }
    }
}