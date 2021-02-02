<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetByDate extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $date)
    {
        try {
            $this->validate($request, ['date' => 'requered|date_format:Y-m-d']);
        } catch (Exception $e) {
            return '{result:false}';
        }

        $client = new \App\JsonRpc\Client('http://json_rpc.service/api/rpc');//указываем реальный адрес сервис
        $params = new \stdClass();
        $params->date = $date;
        $result = $client->weather->getByDate($params);
        return json_encode($result->result);
    }
}
