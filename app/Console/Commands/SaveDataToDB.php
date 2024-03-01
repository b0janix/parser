<?php

namespace App\Console\Commands;

use App\Models\Buyer;
use App\Models\CarModel;
use App\Models\Data;
use App\Models\InhouseSeller;
use App\Models\Vehicle;
use App\Services\Parser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Faker;

class SaveDataToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:parsedToDB';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Parser $parser)
    {
        $this->info("Start processing");

        $faker = Faker\Factory::create();

        $response = Http::get('https://admin.b2b-carmarket.com//test/project');

        $values = $parser->parse($response);

        $vehicles = $values['vehicles'];

        sort($vehicles);

        $vehiclesRes = [];

        foreach ($vehicles as $index => $vehicle) {
            $vehiclesRes[$index]['id'] = $vehicle;
            $vehiclesRes[$index]['vehicleName'] = 'vehicle '. $index;
        }

        unset($values['vehicles']);

        $inhouseSellers = $values['inhouseSellers'];

        sort($inhouseSellers);

        $inhouseSellersRes = [];

        foreach ($inhouseSellers as $index => $sellerId) {
            $inhouseSellersRes [$index]['id'] = $sellerId;
            $inhouseSellersRes [$index]['sellerName'] = 'seller ' . $index;
        }

        unset($values['inhouseSellers']);

        $models = $values['models'];

        sort($models);

        $modelsRes = [];

        foreach ($models as $index => $sellerId) {
            $modelsRes[$index]['id'] = $sellerId;
            $modelsRes[$index]['modelName'] = 'model ' . $index;
        }

        unset($values['models']);

        $buyers = $values['buyers'];

        sort($buyers);

        $buyersRes = [];

        foreach ($buyers as $index => $sellerId) {
            $buyersRes[$index]['id'] = $sellerId;
            $buyersRes[$index]['buyerFirstName'] = $faker->firstName;
            $buyersRes[$index]['buyerLastName'] = $faker->lastName;
        }

        unset($values['buyers']);

        try {
            DB::beginTransaction();

            Vehicle::query()->truncate();

            Vehicle::insert($vehiclesRes);

            InhouseSeller::query()->truncate();

            foreach ($inhouseSellersRes as $res) {
                InhouseSeller::create($res);
            }

            CarModel::query()->truncate();

            CarModel::insert($modelsRes);

            Buyer::query()->truncate();

            Buyer::insert($buyersRes);

            Data::query()->truncate();

            $data = array_chunk($values, 20);

            foreach ($data as $index => $batch) {
                Data::insert($batch);

                $this->info("Batch #$index successfully inserted.");

                sleep(0.2);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }

        $this->info("Done");
    }
}
