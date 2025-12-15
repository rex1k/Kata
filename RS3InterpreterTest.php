<?php

function execute(string $code): string {
    // Implement your RS3 interpreter here
    // You may import your RS1/RS2 interpreter here and build upon it
    // Have fun! :D
}

class RS3InterpreterTest extends \PHPUnit\Framework\TestCase
{
    protected function randomize(array $a): array {
        for ($i = 0; $i < 2 * count($a); $i++) {
            $v = array_rand($a);
            $w = array_rand($a);
            list($a[$v], $a[$w]) = [$a[$w], $a[$v]];
        }
        return $a;
    }
    public function testRS2Only() {
        foreach ($this->randomize([
            function () {
                $this->assertSame("    **   **      *\r\n    **   ***     *\r\n  **** *** **  ***\r\n  *  * *    ** *  \r\n***  ***     ***  ", execute('(F2LF2R)2FRF4L(F2LF2R)2(FRFL)4(F2LF2R)2'));
            }
        ]) as $assertion) $assertion();
    }
    public function testPatternDefinitionsOnly() {
        foreach ($this->randomize([
            function () {
                $this->assertSame('*', execute('p0(F2LF2R)2q'));
            },
            function () {
                $this->assertSame('*', execute('p312(F2LF2R)2q'));
            }
        ]) as $assertion) $assertion();
    }
    public function testDefineAndInvoke() {
        foreach ($this->randomize([
            function () {
                $this->assertSame("    *\r\n    *\r\n  ***\r\n  *  \r\n***  ", execute('p0(F2LF2R)2qP0'));
            },
            function () {
                $this->assertSame("    *\r\n    *\r\n  ***\r\n  *  \r\n***  ", execute('p312(F2LF2R)2qP312'));
            }
        ]) as $assertion) $assertion();
    }
    public function testParseOrder() {
        foreach ($this->randomize([
            function () {
                $this->assertSame("    *\r\n    *\r\n  ***\r\n  *  \r\n***  ", execute('P0p0(F2LF2R)2q'));
            },
            function () {
                $this->assertSame("    *\r\n    *\r\n  ***\r\n  *  \r\n***  ", execute('P312p312(F2LF2R)2q'));
            }
        ]) as $assertion) $assertion();
    }
    public function testMixedCodeBasic() {
        foreach ($this->randomize([
            function () {
                $this->assertSame("       *\r\n       *\r\n       *\r\n       *\r\n     ***\r\n     *  \r\n******  ", execute('F3P0Lp0(F2LF2R)2qF2'));
            }
        ]) as $assertion) $assertion();
    }
    public function testMultipleInvocations() {
        foreach ($this->randomize([
            function () {
                $this->assertSame("      *\r\n      *\r\n    ***\r\n    *  \r\n  ***  \r\n  *    \r\n***    ", execute('(P0)2p0F2LF2RqP0'));
            }
        ]) as $assertion) $assertion();
    }
    /**
     * @expectedException ParseError
     */
    public function testInvalidInvocation1() {
        execute('p0(F2LF2R)2qP1');
    }
    /**
     * @expectedException ParseError
     */
    public function testInvalidInvocation2() {
        execute('P0p312(F2LF2R)2q');
    }
    /**
     * @expectedException ParseError
     */
    public function testInvalidInvocation3() {
        execute('P312');
    }
    public function testMultiplePatternDefinitions() {
        foreach ($this->randomize([
            function () {
                $this->assertSame("  ***\r\n  * *\r\n*** *", execute('P1P2p1F2Lqp2F2RqP2P1'));
            },
            function () {
                $this->assertSame("  *** *** ***\r\n  * * * * * *\r\n*** *** *** *", execute('p1F2Lqp2F2Rqp3P1(P2)2P1q(P3)3'));
            }
        ]) as $assertion) $assertion();
    }
    /**
     * @expectedException ParseError
     */
    public function testInvalidDefinitionOverwrite() {
        execute('p1F2Lqp1(F3LF4R)5qp2F2Rqp3P1(P2)2P1q(P3)3');
    }
    /**
     * @expectedException ParseError
     */
    public function testInfiniteRecursion() {
        execute('p1F2RP1F2LqP1');
    }
    /**
     * @expectedException ParseError
     */
    public function testInfiniteMutualRecursion() {
        execute('p1F2LP2qp2F2RP1qP1');
    }
}