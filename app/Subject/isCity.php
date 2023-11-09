<?php

namespace App\Subject;

class isCity
{
    public function setValue($orderValue)
    {
        $orderArray = json_decode($orderValue, true);
        $this->value = $orderArray['isCity'];
    }
}
