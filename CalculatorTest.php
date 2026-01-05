<?php

class Calculator
{
    private static Calculator|null $instance = null;

    private array $map = [
        'zero'  => 0,
        'one'   => 1,
        'two'   => 2,
        'three' => 3,
        'four'  => 4,
        'five'  => 5,
        'six'   => 6,
        'seven' => 7,
        'eight' => 8,
        'nine'  => 0,
    ];

    private array $operators = [
        'plus'     => '+',
        'minus'    => '-',
        'divide'   => '/',
        'multiply' => '*',
    ];

    private ?float $result = null;

    private ?string $action = null;

    public function __call(string $name, array $arguments)
    {
        $this->__get($name);
        return $this->result;
    }

    public function __get(string $name)
    {
        $digit = $this->map[$name];

        if ($digit !== null) {
            $this->calculate($digit);
            return $this;
        }

        $this->action = $this->operators[$name];
        return $this;
    }

    private function calculate(int $digit): void
    {
        if ($this->action === null) {
            $this->action = '+';
        }

        $str = '$this->result=$this->result' . $this->action . $digit . ';';
        eval($str);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new Calculator();
        }

        self::$instance->action = null;
        self::$instance->result = null;

        return self::$instance;
    }
}

class CalculatorTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple(): void
    {
        $this->assertEquals(4, Calculator::getInstance()->five->minus->four->plus->three());
        $this->assertEquals(-1, Calculator::getInstance()->minus->one());
        $this->assertEquals(-25, Calculator::getInstance()->minus->one->multiply->five->divide->multiply->five());
    }
}