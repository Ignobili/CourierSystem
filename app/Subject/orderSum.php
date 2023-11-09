<?php

namespace App\Subject;

class orderSum
{
    public function setValue($orderValue)
    {
        $orderArray = json_decode($orderValue, true);
        $this->value = $orderArray['orderSum'];
    }
}
