<?php

namespace App\Subject;

class orderSize
{
    public function setValue($orderValue)
    {
        $orderArray = json_decode($orderValue, true);
        $this->value = $orderArray['orderSize'];
    }
}
