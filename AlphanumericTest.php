<?php

function alphanumeric(string $input): bool
{
    if (strlen($input) === 0) {
        return false;
    }

    return preg_match('#^[a-zA-Z0-9]+$#', $input) === 1;
}

class AlphanumericTest extends \PHPUnit\Framework\TestCase
{
    public function testExamples()
    {
        $this->doTest('Mazinkaiser', true);
        $this->doTest('hello world_', false);
        $this->doTest('PassW0rd', true);
        $this->doTest('     ', false);
    }
    private function doTest(string $inp, bool $exp)
    {
        $msg = "Input = ".json_encode($inp);
        $res = alphanumeric($inp);
        $this->assertSame($exp, $res, $msg);
    }
}