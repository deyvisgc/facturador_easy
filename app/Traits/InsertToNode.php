<?php

namespace App\Traits;
use App\Models\System\Plan;
use GuzzleHttp\Client;

trait InsertToNode
{
    public function __construct(Client  $client)
    {
        $this->client = $client;
    }
    private function insertToNode($request, $token) {
        $plan = Plan::findOrFail($request->input('plan_id'));
        $data = [
            'name_users' => 'Administrador',
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'api_token' => $token,
            'number' => $request->number,
            'razonSocial' => $request->name,
            'trade_name' => $request->input('name'),
            'temp_path' => $request->input('temp_path'),
            'plan' => $request->plan_id,
            'limit_documents' =>  $plan->limit_documents,
            'soap_type_id' => $request->soap_type_id,
            'soap_send_id' => $request->soap_send_id,
            'soap_username' => $request->soap_username,
            'soap_password' => $request->soap_password,
            'soap_url' => $request->soap_url,
            'certificate' => $request->certificate,
            'password_certificate' => $request->password_certificate
        ];
        $response = $this->client->request('POST', 'to_server/insert', [
            'form_params' => $data
        ]);
        $responseData = $response->getBody()->getContents();
        $result = json_decode($responseData);
        return $result;
    }
}
