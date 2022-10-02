<?php

namespace App\Imports;

use App\Models\Request;
use App\Models\Residuo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Events\ImportFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ResiduosImport implements ToModel, WithChunkReading, ShouldQueue, WithStartRow
{

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


    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->importedBy->status = 'erro';
                $this->importedBy->mensagem = $event->getException()->getMessage();
                $this->importedBy->save();
            },
        ];
    } 

    
}