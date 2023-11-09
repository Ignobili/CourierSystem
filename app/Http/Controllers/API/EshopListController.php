<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Eshop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EshopListController extends Controller
{
    private $apiToken;

    public function __construct()
    {
        //: Create Token.
        $this->apiToken = uniqid(base64_encode(Str::random(40)));
    }

    /**
     * Users List API
     *
     * @return JsonResponse
     */
    public function getEshopList()
    {
        $eshop = DB::select("SELECT id, eshop_name, created_at, updated_at FROM eshop");

        if ($eshop)
        {
            return response()->json($eshop);
        }
        else
        {
            return response()->json(array("success" => false));
        }
    }

    public function eshopNameExists($name): bool
    {
        $eshopExists = Eshop::where('eshop_name', '=', $name)->count();
        if($eshopExists>=1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //ESHOP ADD
    public function insertEshop(Request $request)
    {
        try
        {
            if($this->eshopNameExists(trim($request->eshopName)))
            {
                $success = false;
                $message = 'EShop ' . $request->eshopName . ' už existuje!';
            }
            else
            {
                $eshop = new Eshop();
                $eshop->eshop_name = trim($request->eshopName);
                $eshop->created_at = date("Y-m-d H:i:s");
                $eshop->updated_at = date("Y-m-d H:i:s");

                $eshop->save();

                $success = true;
                $message = 'EShop ' . $request->eshopName . ' úspešne vytvorený.';
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

    public function eshopIdExists($id): bool
    {
        $eshopExists = Eshop::where('id', '=', $id)->count();
        if($eshopExists >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //ESHOP DELETE
    public function deleteEshop($id)
    {
        try
        {
            if(!$this->eshopIdExists($id))
            {
                $success = false;
                $message = 'EShop s ID: ' . $id . ' neexistuje!';
            }
            else
            {
                $eshop = Eshop::find($id);
                $eshop->delete();
                $success = true;
                $message = 'EShop s ID: ' . $id . ' úspešne vymazaný.';
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

    //ESHOP UPDATE
    public function updateEshop(Request $request,$id)
    {
        try
        {
            $eshop = Eshop::find($id);
            $eshop->eshop_name = $request->input("eshopName");
            $eshop->updated_at = date("Y-m-d H:i:s");
            $eshop->update();
            $success=true;
            $message='EShop ' . $request->eshopName . ' úspešne aktualizovaný.';
        }
        catch(\Illuminate\Database\QueryException $ex)
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

}
