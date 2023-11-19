<?php

namespace Modules\ApiPeruDev\Data;

use App\Models\Tenant\ExchangeRate;
use App\Models\Tenant\Person;
use App\Models\Tenant\WarehouseDocuments;
use GuzzleHttp\Client;
use App\Models\System\Configuration;
use Illuminate\Support\Facades\DB;

class ServiceData
{
    protected $client;
    protected $parameters;

    public function __construct()
    {
        $configuration = Configuration::query()->first();
        $url = $configuration->url_apiruc =! '' ? $configuration->url_apiruc : config('configuration.api_service_url');
        $token = $configuration->token_apiruc =! '' ? $configuration->token_apiruc : config('configuration.api_service_token');

        $this->client = new Client(['base_uri' => $url]);
        $this->parameters = [
            'http_errors' => false,
            'connect_timeout' => 10,
            'verify' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ];
    }

    public function service($type, $number)
    {
        if ($type === 'dni') {
            // busqueda por dni
            $persons = WarehouseDocuments::where('numberDocument', $number)->first();
             if ($persons) {
                 $res_data = [
                     'name' => $persons['name'],
                     'trade_name' => '',
                     'location_id' => $persons['district_id'],
                     'address' => $persons['address'],
                     'department_id' => $persons['department_id'],
                     'province_id' => $persons['province_id'],
                     'district_id' => $persons['district_id'],
                     'condition' => '',
                     'state' => '',
                 ];
                 $response = [
                     'source' => 'apiperu.dev',
                     'success' => true,
                     'data' => $res_data
                 ];

             } else {
                 $res = $this->client->request('GET', '/api/'.$type.'/'.$number, $this->parameters);
                 $response = json_decode($res->getBody()->getContents(), true);
                 if($response['success']) {
                     $data = $response['data'];
                     $department_id = '';
                     $province_id = null;
                     $district_id = null;
                     $address = null;
                     if(key_exists('source', $response) && $response['source'] === 'apiperu.dev') {
                         if (strlen($data['ubigeo_sunat'])) {
                             $department_id = $data['ubigeo'][0];
                             $province_id = $data['ubigeo'][1];
                             $district_id = $data['ubigeo'][2];
                             $address = $data['direccion'];
                         }
                     } else {
                         $department_id = $data['ubigeo'][0];
                         $province_id = $data['ubigeo'][1];
                         $district_id = $data['ubigeo'][2];
                         $address = $data['direccion'];
                     }
                     $res_data = [
                         'name' => $data['nombre_completo'],
                         'numberDocument' => $number,
                         'trade_name' => '',
                         'location_id' => $district_id,
                         'address' => $address,
                         'department_id' => $department_id,
                         'province_id' => $province_id,
                         'district_id' => $district_id,
                         'condition' => '',
                         'state' => '',
                     ];
                     WarehouseDocuments::create($res_data); // inserto la informacion a la base de datos
                     $response['data'] = $res_data;
                 }
             }
        } else {
            //busqueda por ruc
            $res = $this->client->request('GET', '/api/'.$type.'/'.$number, $this->parameters);
            $response = json_decode($res->getBody()->getContents(), true);
            if($response['success']) {
                $data = $response['data'];
                $address = '';
                $department_id = null;
                $province_id = null;
                $district_id = null;
                if(key_exists('source', $response) && $response['source'] === 'apiperu.dev') {
                    $trade_name = key_exists('nombre_comercial', $data)?$data['nombre_comercial']:'';
                    if(isset($data['direccion'])) {
                        $address = $data['direccion'];
                        $department_id = $data['ubigeo'][0];
                        $province_id = $data['ubigeo'][1];
                        $district_id = $data['ubigeo'][2];
                    } else {
                        if(isset($data['domicilio_direccion'])) {
                            $address = $data['domicilio_direccion'];
                            $department_id = $data['domicilio_ubigeo'][0];
                            $province_id = $data['domicilio_ubigeo'][1];
                            $district_id = $data['domicilio_ubigeo'][2];
                        }
                    }
                } else {
                    $trade_name = $data['nombre_o_razon_social'];
                    $address = $data['direccion'];
                    $department_id = $data['ubigeo'][0];
                    $province_id = $data['ubigeo'][1];
                    $district_id = $data['ubigeo'][2];
                }
                $res_data = [
                    'name' => $data['nombre_o_razon_social'],
                    'trade_name' => $trade_name,
                    'address' => $address,
                    'department_id' => $department_id === '-' ? '' : $department_id,
                    'province_id' => $province_id === '-' ? '' : $province_id,
                    'district_id' => $district_id === '-' ? '' : $district_id,
                    'condition' => $data['condicion'],
                    'state' => $data['estado'],
                ];
                $response['data'] = $res_data;
            }
        }

        return $response;
    }

    public function massive_validate_cpe($data)
    {
        $this->parameters['form_params'] = $data;
        $res = $this->client->request('POST', '/api/validacion_multiple_cpe', $this->parameters);

        return json_decode($res->getBody()->getContents(), true);
    }
    public function cpe($company_number, $document_type_id, $series, $number, $date_of_issue, $total)
    {
        $form_params = [
            'ruc_emisor' => $company_number,
            'codigo_tipo_documento' => $document_type_id,
            'serie_documento' => $series,
            'numero_documento' => $number,
            'fecha_de_emision' => $date_of_issue,
            'total' => $total
        ];

        $this->parameters['form_params'] = $form_params;
        $res = $this->client->request('POST', '/api/cpe', $this->parameters);

        return json_decode($res->getBody()->getContents(), true);
    }

    public function exchange($date)
    {
        $exchange = ExchangeRate::query()->where('date', $date)->first();
        if($exchange) {
            return [
                'date' => $date,
                'purchase' => $exchange->purchase,
                'sale' => $exchange->sale
            ];
        }
        $form_params = [
            'fecha' => $date,
        ];

        $this->parameters['form_params'] = $form_params;
        $res = $this->client->request('POST', '/api/tipo_de_cambio', $this->parameters);
        $response = json_decode($res->getBody()->getContents(), true);

        if($response['success']) {
            $data = $response['data'];
            ExchangeRate::query()->create([
                'date' => $data['fecha_busqueda'],
                'date_original' => $data['fecha_sunat'],
                'sale_original' => $data['venta'],
                'sale' => $data['venta'],
                'purchase_original' => $data['compra'],
                'purchase' => $data['compra'],
            ]);

            return [
                'date' => $data['fecha_busqueda'],
                'purchase' => $data['compra'],
                'sale' => $data['venta']
            ];
        }
        return [
            'date' => $date,
            'purchase' => 1,
            'sale' => 1,
        ];
    }
}
