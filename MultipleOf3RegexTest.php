<?php

use PHPUnit\Framework\TestCase;

$multiple_of_3_regex = '/^(0|1(01*0)*1)*$/';

class MultipleOf3RegexTest extends TestCase
{
    public function testExamples() {
        global $multiple_of_3_regex;
        $this->assertDoesNotMatchRegularExpression($multiple_of_3_regex, " 0");
        $this->assertDoesNotMatchRegularExpression($multiple_of_3_regex, "abc");
        $this->assertMatchesRegularExpression($multiple_of_3_regex, "000");
        $this->assertMatchesRegularExpression($multiple_of_3_regex, "110");
        $this->assertDoesNotMatchRegularExpression($multiple_of_3_regex, "111");
        $this->assertMatchesRegularExpression($multiple_of_3_regex, decbin(12345678));
    }
}