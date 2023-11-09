<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Courierservice;

class ServicesController extends Controller
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
    public function getCourierServices()
    {
        $services = DB::table('courier_services')->
            select(DB::raw("courier_services.id, courier_services.service_code, courier_services.service_name, courier.courier_name, courier.id AS courier_id"))->
            join('courier','courier_services.courier_id','=','courier.id')->
            get();

        return response()->json($services);
    }

    public function serviceNameExists($name): bool
    {
        $servicepExists = CourierService::where('service_name', '=', $name)->count();
        if($servicepExists >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //SERVICE ADD
    public function insertService(Request $request)
    {
        try
        {
            $services = new CourierService();
            $services->courier_id = ($request->courierID);
            $services->service_name = ($request->serviceName);
            $services->service_code = ($request->serviceCode);
            $services->created_at = date("Y-m-d H:i:s");
            $services->updated_at = date("Y-m-d H:i:s");
            $services->save();
            $success = true;
            $message = 'Kuriérska služba ' . $request->serviceName . ' úspešne vytvorená.';
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

    public function serviceIdExists($id): bool
    {
        $serviceExists = CourierService::where('id', '=', $id)->count();
        if($serviceExists >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //SERVICE UPDATE
    public function editService(Request $request, $id)
    {
        $services = CourierService::find($id);
        return response()->json($services);
    }

    public function updateService(Request $request, $id)
    {
        try
        {
            $services = CourierService::find($id);
            $services->service_name = $request->input("serviceName");
            $services->courier_id = $request->input("courierID");
            $services->service_code = $request->input("serviceCode");
            $services->update();
            $success = true;
            $message = 'Kuriérska služba ' . $request->serviceName . ' úspešne aktualizovaná.';
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

    //SERVICE DELETE
    public function deleteService($id)
    {
        try
        {
            if(!$this->serviceIdExists($id))
            {
                $success = false;
                $message = 'Kuriérska služba s ID: ' . $id . ' neexistuje!';
            }
            else
            {
                $eshop = CourierService::find($id);
                $eshop->delete();
                $success = true;
                $message = 'Kuriérska služba s ID: ' . $id . ' úspešne vymazaná.';
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
