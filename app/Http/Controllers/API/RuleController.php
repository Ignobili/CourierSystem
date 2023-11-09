<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\CourierService;
use App\Models\Eshop;
use App\Models\Rule;
use App\Subject\isCity;
use App\Subject\isCountry;
use App\Subject\orderSize;
use App\Subject\orderWeight;
use App\Subject\orderSum;
use App\Predicate\isEqual;
use App\Predicate\isEqualOrGreater;
use App\Predicate\isEqualOrSmaller;
use App\Predicate\isGreater;
use App\Predicate\isSmaller;

use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function resolve(Request $order)
    {

        $result = "NOT_FOUND";
        $eshop_id = Eshop::where('eshop_name', '=', $order['eshop'])->first()->id;
        $rules = Rule::where('eshop_id', '=', $eshop_id)->where('active', '=', 1)->orderBy('priority')->get();

        foreach ($rules as $rule)
        {
            $subject = app($rule->subject->class);
            $predicate = app($rule->predicate->class);
            $ruleValue = $rule->value;
            $subject->setValue($order->getContent());

            $compareResult = $predicate->compare($subject->value, $ruleValue);

            if ($compareResult)
            {
                $courier_service_id = $rule->courier_service_id;
                $select = CourierService::find($courier_service_id);
                $courier_name = Courier::where('id', '=', $select->courier_id)->first()->courier_name;

                $result = [

                    "service_code" => $select->service_code,
                    "service_name" => $select->service_name,
                    "courier_name" => $courier_name

                ];

                break;
            }

        }

        return response()->json($result);

    }

}
