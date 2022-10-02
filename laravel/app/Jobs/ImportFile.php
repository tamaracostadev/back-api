<?php

namespace App\Jobs;

use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Imports\ResiduosImport;
use App\Jobs\NotifyCompletedImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request, $nome;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $request,String $nome)
    {
        $this->request = $request;
        $this->nome = $nome;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::queueImport(new ResiduosImport($this->request), $this->nome)
            ->chain([
                new NotifyCompletedImport($this->request),
            ]);
    }
}
