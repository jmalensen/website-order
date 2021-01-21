<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get 404 error if not ajax request
     * @param $request
     */
    protected function isAjaxOrFail($request){
        //debug($request->ajax());
        if(!$request->ajax()){
            abort(404);
        }
    }


    /**
     * Check if data missing
     * @return \Illuminate\Http\JsonResponse
     */
    protected  function ajaxDataMissing(){
        return response()->json(['success' => false,'message' => 'Données manquantes']);
    }


    /**
     * Return success to ajax request
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonSuccess($data = null){
        return response()->json(['success' => true,'data' => $data]);
    }


    /**
     * Return error to ajax request
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonError($message = null){
        $default = 'Une erreur est survenue';
        if(!empty($message)){
            $default .=' : '.$message;
        }
        return response()->json(['success' => false,'message' => $default]);
    }

}
