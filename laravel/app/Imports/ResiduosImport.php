<?php

namespace App\Imports;

use Exception;
use App\Models\Request;
use App\Models\Residuo;
use App\Models\importError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ResiduosImport implements ToModel,
ShouldQueue, 
WithValidation,
WithStartRow,
WithChunkReading,
SkipsEmptyRows,
SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function __construct(Request $importedBy)
    {
        $this->importedBy = $importedBy;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

            return new Residuo([
                'nome'          => $row[0],
                'tipo'          => $row[1],
                'categoria'     => $row[2],
                'tratamento'    => $row[3],
                'classe'        => $row[4],
                'medida'        => $row[5],
                'peso'          => $row[6]
            ]); 

    }
    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            '0' => ['string'],
            '1' => ['string'],
            '2' => ['string'],
            '3' => ['string'],
            '4' => ['string'],
            '5' => ['string'],
            '6' => ['numeric'],
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $data = [];
        foreach ($failures as $failure) {
            $data[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'values' => json_encode($failure->values()),
                'errors' => json_encode($failure->errors()),
                'request' => $this->importedBy->id,
            ];
        }
        importError::insert($data);
        $this->importedBy->status = 'erro';
        $this->importedBy->mensagem = 'Ocorreu um erro ao importar a tabela';
        $this->importedBy->save();
    }
    public function failed(Exception $e){
        $this->importedBy->status = 'erro';
        $this->importedBy->mensagem = 'Ocorreu um erro ao importar a tabela. \n'.$e->getMessage();
        $this->importedBy->save();
    }

    
}