<?php

namespace App\Predicate;

class isEqualOrSmaller
{
    public function compare($orderValue, $ruleValue): bool
    {
        if ($orderValue <= $ruleValue)
        {
            return true;
        }
        return false;
    }
}
