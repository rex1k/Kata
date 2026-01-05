<?php

function loop_size($node): int
{
    $nodes = [];
    $counter = 0;

    while ($node = $node->getNext()) {
        $counter++;
        if (in_array($node, $nodes, true)) {
            $firstAppearance = array_search($node, $nodes);
            unset($nodes[$firstAppearance]);
            $secondAppearance = array_search($node, $nodes);

            return ($secondAppearance + 1) - $firstAppearance;
        }

        $nodes[] = $node;
    }

    return $counter === 1 ? 1 : 0;
}
class LoopSizeTest extends \PHPUnit\Framework\TestCase
{
    public function testFixed() {
        $this->assertSame(1, loop_size(Node::createChain(0, 1)));
        $this->assertSame(23, loop_size(Node::createChain(8778, 23)));
        $this->assertSame(8778, loop_size(Node::createChain(23, 8778)));
        $this->assertSame(6, loop_size(Node::createChain(12, 6)));
    }
}

final class Node implements ArrayAccess, Iterator
{
    protected Node $next;

    public array $nodes = [];

    public function getNext(): Node
    {
        return $this->next;
    }

    public function setNext(Node $next): void
    {
        $this->next = $next;
        $this->nodes[] =$next;
    }

    public static function createChain(int $tail_size, int $loop_size): Node
    {
        if ($tail_size < 0) throw new DomainException('The resulting chain cannot have a negative tail size');
        if ($loop_size <= 0) throw new DomainException('The resulting chain must contain exactly one loop of minimum size 1');
        $prev_node = new Node;
        $start = $prev_node;
        $start->setNext($start);
        if ($loop_size === 1) return $start;// $start=$prev->$start
        for ($i = 1; $i <= $tail_size; $i++) { // $start=$prev->newNode
            $prev_node->setNext(new Node);
            $prev_node = $prev_node->getNext();
        }
        $end_loop = $prev_node;
        for ($i = 1; $i < $loop_size; $i++) {
            $prev_node->setNext(new Node);
            $prev_node = $prev_node->getNext();
        }
        $prev_node->setNext($end_loop);
        return $start;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->nodes[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->nodes[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->nodes[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->nodes[$offset]);
    }

    public function current(): mixed
    {
        return current($this->nodes);
    }

    public function next(): void
    {
        next($this->nodes);
    }

    public function key(): mixed
    {
        return key($this->nodes);
    }

    public function valid(): bool
    {
        return current($this->nodes);
    }

    public function rewind(): void
    {
        reset($this->nodes);
    }
}