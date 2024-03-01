<?php

namespace App\Console\Commands;

use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BestSellingModelPerClientThreeMonthInARow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:modelPerClientThreeMonths {sellerId : The id of the seller}';

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
        $dateStart = new DateTime('3 months ago');
        $dateStart = $dateStart->format('Y-m-d H:i:s');
        $dateFinish = new \DateTime();
        $sellerId = $this->argument('sellerId');
        $dateFinish = $dateFinish->format('Y-m-d H:i:s');

        $res = DB::table('data')
            ->select('car_models.modelName', DB::raw('count(*) as total'))
            ->join('car_models', 'data.modelId', '=', 'car_models.id')
            ->where('data.inhouseSellerId', $sellerId)
            ->whereBetween('data.saleDate', [$dateStart, $dateFinish])
            ->groupBy('car_models.modelName', 'data.saleDate')
            ->orderBy('total', 'desc')
            ->limit(1)
            ->get()
            ->toArray();

        $this->info('Most selling model per client or the last three months is ' . $res[0]->modelName . ' with total of ' . $res[0]->total);
    }
}
