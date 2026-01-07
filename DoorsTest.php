<?php

function doors(int $doors): int
{
    return (int)sqrt($doors);
}

class DoorsTest extends \PHPUnit\Framework\TestCase
{
    public function testFixed() {
        $this->assertSame(2, doors(5));
        $this->assertSame(3, doors(10));
        $this->assertSame(10, doors(100));
    }
}