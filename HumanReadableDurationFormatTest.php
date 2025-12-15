<?php

function format_duration(int $seconds): string
{
    if ($seconds === 0) {
        return 'now';
    }

    $rules = [
        'year' => 60 * 60 * 24 * 365,
        'day' => 60 * 60 * 24,
        'hour' => 60 * 60,
        'minute' => 60,
        'second' => 1,
    ];

    $result = [];
    foreach ($rules as $key => $rule) {
        if ($seconds >= $rule) {
            $res = (int)($seconds / $rule);
            $result[] = ($res > 1) ? $res . ' ' . $key . 's' : $res . ' ' . $key;
            $seconds = $seconds - ($res * $rule);
        }
    }
    unset($key);

    $resultString = '';
    foreach ($result as $key => $item) {
        if ($key === 0) {
            $resultString .= $item;
        } elseif ($key === count($result) - 1) {
            $resultString .= ' and ' . $item;
        } else {
            $resultString .= ', ' . $item;
        }
    }

    return $resultString;
}

class HumanReadableDurationFormatTest extends \PHPUnit\Framework\TestCase
{
    public function testExample()
    {
        $this->assertSame("1 second", format_duration(1));
        $this->assertSame("1 minute and 2 seconds", format_duration(62));
        $this->assertSame("2 minutes", format_duration(120));
        $this->assertSame("1 hour", format_duration(3600));
        $this->assertSame("1 hour, 1 minute and 2 seconds", format_duration(3662));
    }
}