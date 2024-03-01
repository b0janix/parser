<?php

namespace App\Http\Controllers;

use App\Services\Parser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class DataParserController extends Controller
{
    public function getData(Parser $parser): Response
    {
        $response = Http::get('https://admin.b2b-carmarket.com//test/project');

        $values = $parser->parse($response);

        return response($values);
    }
}
