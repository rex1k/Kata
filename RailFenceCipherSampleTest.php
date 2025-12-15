<?php

function encodeRailFenceCipher(string $string, int $numberRails): string
{
    $result = [];
    $split = str_split($string);

    $direction = 1;
    $currentRail = 0;
    foreach ($split as $item) {
        $result[$currentRail][] = $item;
        $currentRail += $direction;

        if ($currentRail === 0) {
            $direction = 1;
        } elseif ($currentRail === $numberRails - 1) {
            $direction = -1;
        }
    }

    return implode('', array_map('implode', $result));
}

function decodeRailFenceCipher(string $text, int $rails): string
{
    $length = strlen($text);
    if ($rails <= 1 || $rails >= $length || $length === 0) {
        return $text;
    }


    $pattern = [];
    $currentRail = 0;
    $direction = 1;

    for ($i = 0; $i < $length; $i++) {
        $pattern[$i] = $currentRail;

        if ($currentRail === 0) {
            $direction = 1;
        } elseif ($currentRail === $rails - 1) {
            $direction = -1;
        }

        $currentRail += $direction;
    }

    $railCounts = array_fill(0, $rails, 0);
    foreach ($pattern as $rail) {
        $railCounts[$rail]++;
    }

    $fence = array_fill(0, $rails, []);
    $textIndex = 0;

    for ($rail = 0; $rail < $rails; $rail++) {
        for ($j = 0; $j < $railCounts[$rail]; $j++) {
            if ($textIndex < $length) {
                $fence[$rail][] = $text[$textIndex];
                $textIndex++;
            }
        }
    }

    $result = '';
    $railPointers = array_fill(0, $rails, 0);
    for ($i = 0; $i < $length; $i++) {
        $rail = $pattern[$i];
        $result .= $fence[$rail][$railPointers[$rail]];
        $railPointers[$rail]++;
    }

    return $result;
}

class RailFenceCipherSampleTest extends \PHPUnit\Framework\TestCase
{
    public function testSample()
    {
        $this->assertSame(encodeRailFenceCipher("Hello, World!", 3), "Hoo!el,Wrdl l");
        $this->assertSame(decodeRailFenceCipher("Hoo!el,Wrdl l", 3), "Hello, World!");

        $this->assertSame(encodeRailFenceCipher("", 3), "");
        $this->assertSame(decodeRailFenceCipher("", 3), "");

        $this->assertSame(encodeRailFenceCipher("WEAREDISCOVEREDFLEEATONCE", 3), "WECRLTEERDSOEEFEAOCAIVDEN");
        $this->assertSame(decodeRailFenceCipher("WECRLTEERDSOEEFEAOCAIVDEN", 3), "WEAREDISCOVEREDFLEEATONCE");
    }
}