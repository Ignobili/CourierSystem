<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourierListController extends Controller
{
    private $apiToken;

    public function __construct()
    {
        //: Create Token.
        $this->apiToken = uniqid(base64_encode(Str::random(40)));
    }

    /**
     * Courier List API
     *
     * @return JsonResponse
     */
    public function getCourierList()
    {
        $courier = DB::select("SELECT courier.id, courier.courier_name, courier.courier_code, eshop.eshop_name, eshop.id AS eshop_id FROM courier INNER JOIN eshop ON courier.eshop_id=eshop.id");

        if ($courier)
        {
            return response()->json($courier);
        }
        else
        {
            return response()->json(array("success" => false));
        }
    }

    public function courierNameExists($name): bool
    {
        $courierExists = Courier::where('courier_name', '=', $name)->count();
        if($courierExists >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function courierIdExists($id): bool
    {
        $courierExists = Courier::where('id', '=', $id)->count();
        if($courierExists >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    //Courier Update

    public function editCourier(Request $request, $id)
    {
        $courier = Courier::find($id);
        return response()->json($courier);
    }

    public function updateCourier(Request $request,$id)
    {
        try
        {
            $courier = Courier::find($id);
            $courier->courier_name = $request->input("courierName");
            $courier->courier_code = $request->input("courierCode");
            $courier->eshop_id = $request->input("eshopID");
            $courier->update();
            $success = true;
            $message = 'Kuriér ' . $request->courierName . ' úspešne aktualizovaný.';
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

    //Courier Add
    public function insertCourier(Request $request)
    {
        try
        {
            if($this->courierIdExists(trim($request->courierName)))
            {
                $success = false;
                $message = 'Kuriér ' . $request->courierName . ' už existuje!';
            }
            else
            {
                $courier = new Courier();
                $courier->courier_name = trim($request->courierName);
                $courier->courier_code = trim($request->courierCode);
                $courier->eshop_id = trim($request->eshopID);
                $courier->save();
                $success = true;
                $message = 'Kuriér ' . $request->courierName . ' úspešne vytvorený.';
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

    //Courier delete
    public function deleteCourier($id)
    {
        try
        {
            if(!$this->courierIdExists($id))
            {
                $success = false;
                $message = 'Kuriér s ID: ' . $id . ' neexistuje!';
            }
            else
            {
                $courier = Courier::find($id);
                $courier->delete();
                $success = true;
                $message = 'Kuriér s ID: ' . $id . ' úspešne vymazaný.';
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
