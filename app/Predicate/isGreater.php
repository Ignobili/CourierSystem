<?php

namespace App\Predicate;

class isGreater
{
    public function compare($orderValue, $ruleValue): bool
    {
        if ($orderValue > $ruleValue)
        {
            return true;
        }
        return false;
    }
}
