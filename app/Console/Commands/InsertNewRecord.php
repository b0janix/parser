<?php

namespace App\Console\Commands;

use App\Models\Buyer;
use App\Models\CarModel;
use App\Models\Data;
use App\Models\InhouseSeller;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertNewRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:insertRecord {sellerId} {buyerId} {vehicleId} {modelId} {buyDate} {sellDate}';

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
        //dd($this->arguments());

        $data = [];

        $data['inhouseSellerId'] = $this->argument('sellerId');
        $data['buyerId'] = $this->argument('buyerId');
        $data['vehicleId'] = $this->argument('vehicleId');
        $data['modelId'] = $this->argument('modelId');
        $data['saleDate'] = $this->argument('sellDate');
        $data['buyDate'] = $this->argument('buyDate');

        try {
            Data::create($data);

            $res = Data::where([
                'inhouseSellerId' => $data['inhouseSellerId'],
                'buyerId' => $data['buyerId'],
                'vehicleId' => $data['vehicleId'],
                'modelId' => $data['modelId'],
                'saleDate' => $data['saleDate'],
                'buyDate' => $data['buyDate']
                ])->count();

            if ($res > 0) {
                $this->info('The record is ssuccesfully created with the params passed');
            } else {
                $this->error('The record was not created');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }
}
