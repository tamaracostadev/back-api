<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Request as ModelsRequest;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    public function buscaStatus(Request $request){
        $validator = Validator::make($request->all(),[ 
            'id' => ['required','integer']
        ]);
        if($validator->fails()) {          
            return response()->json(['errors'=>true, 'message'=>$validator->errors()], 401);                        
        }

        $requestStatus = ModelsRequest::where('id',$request->id)->first();
        if(!$requestStatus){
            return response()->json([
                'errors'=>true,
                'message'=>'Requisição não encontrada'
            ], 404);
        }
        return response()->json([
            'errors'=>'',
            'data'=> $requestStatus
        ]);
    }
}
