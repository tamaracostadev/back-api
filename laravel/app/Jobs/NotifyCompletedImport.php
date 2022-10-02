<?php
namespace App\Jobs;

use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCompletedImport implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->request->status = 'Completo';
        $this->request->mensagem = "Arquivo processado com sucesso";
        $this->request->save();
    }
}