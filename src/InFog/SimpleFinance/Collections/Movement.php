<?php

namespace InFog\SimpleFinance\Collections;

use InFog\SimpleFinance\Types\Money;

class Movement implements \Countable, \IteratorAggregate
{

    private $items = array();

    public function add(\InFog\SimpleFinance\Entities\Movement $movement)
    {
        array_push($this->items, $movement);
    }

    public function count()
    {
        return count($this->items);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return Money
     */
    public function getGrandTotal()
    {
        $grandTotal = 0;
        foreach ($this->getIterator() as $movement) {
            /** @var \InFog\SimpleFinance\Entities\Movement $movement */
            $grandTotal += $movement->getAmount()->getValue();
        }

        return new Money($grandTotal);
    }
}
