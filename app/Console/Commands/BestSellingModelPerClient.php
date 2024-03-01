<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BestSellingModelPerClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:modelPerClient {sellerId : The id of the seller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sellerId = $this->argument('sellerId');
        $res = DB::table('data')
            ->select('car_models.modelName', DB::raw('count(*) as total'))
            ->join('car_models', 'data.modelId', '=', 'car_models.id')
            ->where('data.inhouseSellerId', $sellerId)
            ->groupBy('car_models.modelName')
            ->orderBy('total', 'desc')
            ->limit(1)
            ->get()
            ->toArray();

        $this->info('Most selling model per client is ' . $res[0]->modelName . ' with total of ' . $res[0]->total);
    }
}
