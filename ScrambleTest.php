<?php

function scramble(string $string, array $array): string
{
    $split = str_split($string);
    $result = array_combine($array, $split);
    ksort($result);

    return implode('', $result);
}

class ScrambleTest extends \PHPUnit\Framework\TestCase
{
    public function testBasic() {
//        $this->assertSame('acdb', scramble('abcd',[0,3,1,2]));
        $this->assertSame("c0s3s1", scramble('sc301s', [4,0,3,1,5,2]));
        $this->assertSame("5sblk", scramble('bskl5', [2,1,4,3,0]));
    }
}