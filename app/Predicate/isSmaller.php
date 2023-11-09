<?php

namespace App\Predicate;

class isSmaller
{
    public function compare($orderValue, $ruleValue): bool
    {
        if ($orderValue < $ruleValue)
        {
            return true;
        }
        return false;
    }
}
