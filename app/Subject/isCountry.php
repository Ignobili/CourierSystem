<?php

namespace App\Subject;

class isCountry
{
    public function setValue($orderValue)
    {
        $orderArray = json_decode($orderValue, true);
        $this->value = $orderArray['isCountry'];
    }
}
