<?php

namespace App\Http\Controllers;

use App\JsonRpc\Client;
use Illuminate\Http\Request;

class GetHistory extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $client = new Client('json_rpc.service/api/rpc');//указываем реальный адрес сервис
        $params = new \stdClass();
        $params->lastDays = '30';
        $result = $client->weather->getHistory($params);
        return view('welcome', ['history' => $result ? $result->result : null]);
    }
}
