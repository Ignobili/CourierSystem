<?php

namespace App\Predicate;

class isEqual
{
    public function compare($orderValue, $ruleValue): bool
    {
        if ($orderValue === $ruleValue)
        {
            return true;
        }
        return false;
    }
}
