<?php

namespace App\Predicate;

class isEqualOrGreater
{
    public function compare($orderValue, $ruleValue): bool
    {
        if ($orderValue >= $ruleValue)
        {
            return true;
        }
        return false;
    }
}

