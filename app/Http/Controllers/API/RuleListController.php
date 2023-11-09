<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CourierService;
use App\Models\Eshop;
use App\Models\Predicate;
use App\Models\Rule;
use App\Models\Subject;
use Exception;
use GrahamCampbell\ResultType\Success;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Whoops\Run;

class RuleListController extends Controller
{

    public function getRuleList()
    {
        $rules = DB::table('rules')->
        select(DB::raw('rules.id as rule_id, eshop.id as eshop_id,predicate.id as predicate_id, subject.id as subject_id,courier_services.id as service_id, rules.rule_name, rules.value, rules.value, rules.active, rules.priority, eshop.eshop_name, predicate.name_predicate, subject.name_subject, courier_services.service_code'))->
        join('courier_services', 'rules.courier_service_id', '=', 'courier_services.id')->
        join('subject', 'rules.subject_id', '=', 'subject.id')->
        join('predicate', 'rules.predicate_id', '=', 'predicate.id')->
        join('eshop', 'rules.eshop_id', '=', 'eshop.id')->
        get();

        return response()->json($rules);
    }

    public function getFilteredRules(Request $eshopId)
    {
        $eId = $eshopId->eshopFilter;
        $rulesExist = Rule::where('eshop_id', '=', $eId)->count();
        if($rulesExist >= 1)
        {
            $rules = DB::table('rules')->
            select(DB::raw('rules.id as rule_id, eshop.id as eshop_id,predicate.id as predicate_id, subject.id as subject_id,courier_services.id as service_id, rules.rule_name, rules.value, rules.value, rules.active, rules.priority, eshop.eshop_name, predicate.name_predicate, subject.name_subject, courier_services.service_code'))->
            join('courier_services', 'rules.courier_service_id', '=', 'courier_services.id')->
            join('subject', 'rules.subject_id', '=', 'subject.id')->
            join('predicate', 'rules.predicate_id', '=', 'predicate.id')->
            join('eshop', 'rules.eshop_id', '=', 'eshop.id')->where('eshop_id', '=', $eId)->
            get();

            if ($rules)
            {
                return response()->json($rules);
            }
            else
            {
                $response = [
                    'success' => false,
                    'message' => "Serverová chyba!",
                ];
                return response()->json($response);
            }
        }
        else
        {
            $response = [
                'success' => false,
                'message' => "Pre tento EShop zatiaľ neexistujú žiadne pravidlá!",
            ];
            return response()->json($response);
        }
    }

    public function ruleIdExists($id): bool
    {
        $RuleExists = Rule::where('id', '=', $id)->count();
        if ($RuleExists >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getCourierServices()
    {
        $services = CourierService::all();
        return response()->json($services);
    }

    public function getSubject()
    {
        $subject = Subject::all();
        return response()->json($subject);
    }

    public function getPredicate()
    {
        $predicate = Predicate::all();
        return response()->json($predicate);
    }


    public function editRule(Request $request, $id)
    {
        $rule = Rule::find($id);
        return response()->json($rule);
    }

    public function updateRule(Request $request, $id)
    {
        try
        {
            $rule = Rule::find($id);
            $rule->rule_name = $request->input("RuleName");
            $rule->value = $request->input("value");
            $rule->active = $request->input("active");
            $rule->priority = $request->input("priority");
            $rule->courier_service_id = $request->input("serviceCode");
            $rule->eshop_id = $request->input("eshopID");
            $rule->subject_id = $request->input("subjectID");
            $rule->predicate_id = $request->input("predicateID");
            $rule->update();
            $success = true;
            $message = 'Pravidlo ' . $request->RuleName . ' úspešne aktualizované.';
        }
        catch (\Illuminate\Database\QueryException $ex)
        {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response);
    }

    public function insertRule(Request $request)
    {
        try
        {
            $rule = new Rule();
            $rule->rule_name = ($request->RuleName);
            $rule->value = ($request->value);
            $rule->active = ($request->active);
            $rule->priority = ($request->priority);
            $rule->courier_service_id = ($request->serviceCode);
            $rule->eshop_id = ($request->eshopID);
            $rule->subject_id = ($request->subjectID);
            $rule->predicate_id = ($request->predicateID);
            $rule->save();
            $success = true;
            $message = 'Pravidlo ' . $request->RuleName . ' úspešne vytvorené.';
        }
        catch (\Illuminate\Database\QueryException $ex)
        {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response);
    }

    public function deleteRule($id)
    {
        try
        {
            if (!$this->ruleIdExists($id))
            {
                $success = false;
                $message = 'Pravidlo s ID: ' . $id . ' neexistuje!';
            }
            else
            {
                $rule = Rule::find($id);
                $rule->delete();
                $success = true;
                $message = 'Pravidlo s ID: ' . $id . ' úspešne vymazané.';
            }
        }
        catch (\Illuminate\Database\QueryException $ex)
        {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response);
    }

}
