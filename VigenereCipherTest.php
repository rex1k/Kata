<?php

use PHPUnit\Framework\TestCase;

class VigenèreCipher
{
    private string $key;
    private string $alphabet;
    private array $splitted;
    private array $offset = [];


    public function __construct(string $key, string $alphabet)
    {
        $this->alphabet = $alphabet;
        $this->splitted = str_split($alphabet);
        $this->key = $key;
        $this->offset = $this->calculateOffset();
    }

    public function encode($s): string
    {
        $this->resetOffset();
        $split = str_split($s);
        $result = [];

        foreach ($split as $char) {
            if (!in_array($char, $this->splitted)) {
                $result[] = $char;
            } else {
                $result[] = $this->getEncryptedChar($char);
            }

            $this->updateOffset();

        }

        return implode('', $result);
    }

    public function decode($s): string
    {
        $this->resetOffset();
        $split = str_split($s);
        $result = [];

        foreach ($split as $char) {
            if (!in_array($char, $this->splitted)) {
                $result[] = $char;
            } else {
                $result[] = $this->getDecryptedChar($char);
            }

            $this->updateOffset();
        }

        return implode('', $result);
    }

    private function calculateOffset(): array
    {
        $offset = [];
        foreach (str_split($this->key) as $char) {
            $offset[] = array_search($char, str_split($this->alphabet));
        }

        return $offset;
    }

    private function getOffset()
    {
        return current($this->offset);
    }

    private function updateOffset(): void
    {
        $next = next($this->offset);

        if ($next === false) {
            reset($this->offset);
        }
    }

    private function getEncryptedChar(string $char)
    {
        $offset = $this->getOffset();
        $position = array_search($char, $this->splitted);
        $shiftPosition = $position + $offset;

        if ($shiftPosition > count($this->splitted) - 1) {
            $shiftPosition = $position - (count($this->splitted)) + $offset;
        }

        return $this->splitted[$shiftPosition];
    }

    private function resetOffset(): void
    {
        reset($this->offset);
    }

    private function getDecryptedChar(string $char): string
    {
        $offset = $this->getOffset();
        $position = array_search($char, $this->splitted);
        $shiftPosition = $position - $offset;

        if ($shiftPosition < 0) {
            $shiftPosition = $shiftPosition + count($this->splitted);
        }

        return $this->splitted[$shiftPosition];
    }
}

class VigenereCipherTest extends TestCase
{
    public function test1()
    {
        $c = new VigenèreCipher('password', 'abcdefghijklmnopqrstuvwxyz');

        $this->assertSame('rovwsoiv', $c->encode('codewars'));
        $this->assertSame('codewars', $c->decode('rovwsoiv'));

        $this->assertSame('laxxhsj', $c->encode('waffles'));
        $this->assertSame('waffles', $c->decode('laxxhsj'));

        $this->assertSame('CODEWARS', $c->encode('CODEWARS'));
        $this->assertSame('CODEWARS', $c->decode('CODEWARS'));

        $this->assertSame("it's a shift cipher!", $c->decode("xt'k o vwixl qzswej!"));
        $this->assertSame("xt'k o vwixl qzswej!", $c->encode("it's a shift cipher!"));
    }
}
