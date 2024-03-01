<?php

namespace App\Services;

use Illuminate\Http\Client\Response;

class Parser

{     public function parse(Response $response): array
     {
         $string = preg_replace('~[\n]+~', '', $response->body());
         $data = explode('<br>', $string);
         $keys = explode(',', array_splice($data, 0, 1)[0]);
         $keys = array_replace($keys, [
             'vehicleId',
             'inhouseSellerId',
             'buyerId',
             'modelId',
             'saleDate',
             'buyDate'
         ]);
         $values = [
             'vehicles' => [],
             'inhouseSellers' => [],
             'buyers' => [],
             'models' => []
         ];
         foreach ($data as $row) {
             $temp = explode(',', $row);
             if (!in_array($temp[0],$values['vehicles'])) {
                 $values['vehicles'][] = $temp[0];
             }
             if (!in_array($temp[1],$values['inhouseSellers'])) {
                 $values['inhouseSellers'][] = $temp[1];
             }
             if (!in_array($temp[2],$values['buyers'])) {
                 $values['buyers'][] = $temp[2];
             }
             if (!in_array($temp[3],$values['models'])) {
                 $values['models'][] = $temp[3];
             }
             $values[] = array_combine($keys, $temp);
         }

         return $values;
     }
}
