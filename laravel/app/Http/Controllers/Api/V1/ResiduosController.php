<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Residuo;
use App\Jobs\ImportFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Request as ModelRequest;
use Illuminate\Support\Facades\Validator;

class ResiduosController extends Controller
{

    public function index(Request $request){
        $validator = Validator::make($request->all(),[ 
            'col' => ['string', 'max:100'],
            'busca' => ['string', 'max:100']
        ]);
        if($validator->fails()) {          
            return response()->json([
                'errors'=>true, 
                'message'=>$validator->errors()
            ], 401);                        
        }

        $residuo = Residuo::where($request->col, $request->busca)
        ->get();
        if(!$residuo){
            return response()->json([
                'errors'=>true, 
                'message'=>'A busca não retornou resultados'
            ], 404);
        }
        return response()->json([
            'errors'=>'',
            'data'=> $residuo
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[ 
            'file' => 'required|mimes:xls,xlsx,csv|max:2048',
        ]);

        if($validator->fails()) {          
            return response()->json([
                'errors'=>true, 
                'message'=>$validator->errors()
            ], 401);                        
        }
        if ($file = $request->file('file')) {
            
            $path = $file->store('public/files');
            $name = $file->getRealPath();
            $importedBy = new ModelRequest();
            $importedBy->status = 'processando';
            $importedBy->save();

            //inserir arquivo na fila
            ImportFile::dispatch($importedBy,$path)->delay(now()->addSeconds(15));
          /*   $importJob = (new ImportFile($importedBy,$name))->delay(now()->addMinutes(10));;
            dispatch($importJob)->delay(now()->addMinutes(10));; */
 
            return response()->json([
                'errors'=>'',
                "message" => "Upload efetuado com sucesso!",
                "request" => $importedBy->id,
                "path" => $path
            ]);
  
        } 
    }

    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'nome'          => ['string', 'max:100'],
            'tipo'          => ['string', 'max:100'],
            'categoria'     => ['string', 'max:100'],
            'tratamento'    => ['string', 'max:100'],
            'classe'        => ['string', 'max:100'],
            'medida'        => ['string', 'max:100'],
            'peso'          => ['decimal']
        ]);
        if($validator->fails()){
            return response(['errors'=>true, 'message'=>$validator->errors()->all()],422);
        }

        $residuo = Residuo::where('id', $id)->first();
        $residuo->update($request->all());
        return response()->json([
            "id" => $id,
            "request" => $request->all(),
            "success" => true,
            "message" => "Resíduo atualizado com sucesso",
            "item" => $residuo
        ]);
    }

    public function delete($id){
        $residuo = Residuo::where('id', $id)->first();

        if($residuo){
            $residuo->delete();
            return response([
                'errors'=>'',
                'id'=> $id,
                'message' =>'Residuo excluído com sucesso!']);
        }
        return response([
            'errors'=>true,
            'message'=>'Residuo não encontrado'
        ],401);
    }
}
