<?php

namespace App\Subject;

class orderWeight
{
    public function setValue($orderValue)
    {
        $orderArray = json_decode($orderValue, true);
        $this->value = $orderArray['orderWeight'];
    }
}
