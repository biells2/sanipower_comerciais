<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Visitas;
use App\Models\Comentarios;
use Illuminate\Http\JsonResponse;
use App\Models\ComentariosPropostas;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ClientesInterface;
use App\Models\ComentariosEncomendas;
use App\Models\VisitasAgendadas;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientesRepository implements ClientesInterface
{
    public function getListagemClientes($perPage,$page): LengthAwarePaginator
    {
        
        $nomeCliente = '&Name=';
        $numeroCliente = '&Customer_number=0';
        $zonaCliente = '&Zone=';
        $mobileCliente = '&Mobile_phone=';
        $emailCliente = '&Email=';
        $nifCliente = '&Nif=';

        $string = $nomeCliente.$numeroCliente.$zonaCliente.$mobileCliente.$emailCliente.$nifCliente;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/customers/GetCustomers?perPage='.$perPage.'&Page='.$page.$string.'&Salesman_number='.Auth::user()->id_phc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
      
        $response = curl_exec($curl);
       
        curl_close($curl);

        $response_decoded = json_decode($response);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if($response_decoded != null)
        {
            $currentItems = array_slice($response_decoded->customers, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);

        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);
        }

    
        return $itemsPaginate; 

    }

    public function getListagemAnalisesCliente($perPage,$page,$idCliente): LengthAwarePaginator
    {
        $curl = curl_init();
 
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/documents/orders?perPage='.$perPage.'&Page='.$page.'&customer_id='.$idCliente,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
 
        $response = curl_exec($curl);
 
        curl_close($curl);
 
        $response_decoded = json_decode($response);
 
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
 
        if($response_decoded != null)
        {
            $currentItems = array_slice($response_decoded->orders, $perPage * ($currentPage - 1), $perPage);
 
            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);
 
        }
        else {
 
            $currentItems = [];
 
            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);
        }
 
   
        return $itemsPaginate;
    }

    public function getNumberOfPages($perPage): array
    {
        $nomeCliente = '&Name=';
        $numeroCliente = '&Customer_number=0';
        $zonaCliente = '&Zone=';
        $mobileCliente = '&Mobile_phone=';
        $emailCliente = '&Email=';
        $nifCliente = '&Nif=';

        $string = $nomeCliente.$numeroCliente.$zonaCliente.$mobileCliente.$emailCliente.$nifCliente;
        

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/customers/GetCustomers?perPage='.$perPage.$string.'&Page=1&Salesman_number='.Auth::user()->id_phc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
       
        $response = curl_exec($curl);

        curl_close($curl);
      
        $response_decoded = json_decode($response);

        $arrayInfo = [];

        $arrayInfo = ["nr_paginas" => $response_decoded->total_pages, "nr_registos" => $response_decoded->total_records];

        return $arrayInfo;
    }

    /*** FILTROS ***/

    public function getListagemClienteFiltro($perPage,$page,$nomeCliente,$numeroCliente,$zonaCliente,$telemovelCliente,$emailCliente,$nifCliente): LengthAwarePaginator
    {
        
        if ($nomeCliente != "") {
            $nomeCliente = '&Name='.urlencode($nomeCliente);
        } else {
            $nomeCliente = '&Name=';
        }
        
        if ($numeroCliente != "") {
            $numeroCliente = '&Customer_number='.urlencode($numeroCliente);
        } else {
            $numeroCliente = '&Customer_number=0';
        }
        
        if ($zonaCliente != "") {
            $zonaCliente = '&Zone='.urlencode($zonaCliente);
        } else {
            $zonaCliente = '&Zone=';
        }

        if ($telemovelCliente != "") {
            $telemovelCliente = '&Mobile_phone='.urlencode($telemovelCliente);
        } else {
            $telemovelCliente = '&Mobile_phone=';
        }

        if ($emailCliente != "") {
            $emailCliente = '&Email='.urlencode($emailCliente);
        } else {
            $emailCliente = '&Email=';
        }

        if ($nifCliente != "") {
            $nifCliente = '&Nif='.urlencode($nifCliente);
        } else {
            $nifCliente = '&Nif=';
        }

        $string = $nomeCliente.$numeroCliente.$zonaCliente.$telemovelCliente.$emailCliente.$nifCliente;


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/customers/GetCustomers?perPage='.$perPage.'&Page='.$page.'&Salesman_number='.Auth::user()->id_phc.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));


        $response = curl_exec($curl);

        curl_close($curl);
     
        $response_decoded = json_decode($response);
       
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if($response_decoded != null)
        {
            $currentItems = array_slice($response_decoded->customers, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);
        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);
        }

    
        return $itemsPaginate; 
    }

    public function getNumberOfPagesClienteFiltro($perPage,$nomeCliente,$numeroCliente,$zonaCliente,$telemovelCliente,$emailCliente,$nifCliente): array
    {

        if ($nomeCliente != "") {
            $nomeCliente = '&Name='.urlencode($nomeCliente);
        } else {
            $nomeCliente = '&Name=';
        }
        
        if ($numeroCliente != "") {
            $numeroCliente = '&Customer_number='.urlencode($numeroCliente);
        } else {
            $numeroCliente = '&Customer_number=0';
        }
        
        if ($zonaCliente != "") {
            $zonaCliente = '&Zone='.urlencode($zonaCliente);
        } else {
            $zonaCliente = '&Zone=';
        }

        if ($telemovelCliente != "") {
            $telemovelCliente = '&Mobile_phone='.urlencode($telemovelCliente);
        } else {
            $telemovelCliente = '&Mobile_phone=';
        }

        if ($emailCliente != "") {
            $emailCliente = '&Email='.urlencode($emailCliente);
        } else {
            $emailCliente = '&Email=';
        }

        if ($nifCliente != "") {
            $nifCliente = '&Nif='.urlencode($nifCliente);
        } else {
            $nifCliente = '&Nif=';
        }

        $string = $nomeCliente.$numeroCliente.$zonaCliente.$telemovelCliente.$emailCliente.$nifCliente;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/customers/GetCustomers?perPage='.$perPage.'&Page=1&Salesman_number='.Auth::user()->id_phc.$string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));


        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);
      

        $arrayInfo = [];

        $arrayInfo = ["nr_paginas" => $response_decoded->total_pages, "nr_registos" => $response_decoded->total_records];

        return $arrayInfo;
    }


    /**** END FILTROS ****/


    public function getDetalhesCliente($id): object
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/customers/GetCustomers?id='.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        return $response_decoded;
    }

   


    /***  DETALHES DO CLIENTE *****/

    public function getEncomendasCliente($perPage,$page,$idCliente): LengthAwarePaginator
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/documents/orders?perPage='.$perPage.'&Page='.$page.'&customer_id='.$idCliente,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if($response_decoded != null)
        {
            $currentItems = array_slice($response_decoded->orders, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);

        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);
        }

    
        return $itemsPaginate; 
    }

    public function getNumberOfPagesAnalisesCliente($perPage,$idCliente): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/documents/orders?perPage='.$perPage.'&Page=1&customer_id='.$idCliente,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $arrayInfo = [];

        $arrayInfo = ["nr_paginas" => $response_decoded->total_pages, "nr_registos" => $response_decoded->total_records];

        return $arrayInfo;
    }

    public function getNumberOfPagesEncomendasCliente($perPage,$idCliente): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/documents/orders?perPage='.$perPage.'&Page=1&customer_id='.$idCliente,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $arrayInfo = [];

        $arrayInfo = ["nr_paginas" => $response_decoded->total_pages, "nr_registos" => $response_decoded->total_records];

        return $arrayInfo;
    }


    
    public function getPropostasCliente($perPage,$page,$idCliente): LengthAwarePaginator
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/documents/budgets?perPage='.$perPage.'&Page='.$page.'&customer_id='.$idCliente,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if($response_decoded != null)
        {
            $currentItems = array_slice($response_decoded->budgets, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);

        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);
        }

    
        return $itemsPaginate; 
    }

    public function getNumberOfPagesPropostasCliente($perPage,$idCliente): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/documents/budgets?perPage='.$perPage.'&Page=1&customer_id='.$idCliente,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $arrayInfo = [];


        $arrayInfo = ["nr_paginas" => $response_decoded->total_pages, "nr_registos" => $response_decoded->total_records];

        return $arrayInfo;
    }

    public function sendComentarios($idProposta, $comentario, $type): JsonResponse
    {
        $comentarioCreated = Comentarios::create([
            "stamp" => $idProposta,
            "tipo" => $type,
            "comentario" => $comentario,
            'id_user' => Auth::user()->id
        ]);

        if ($comentarioCreated) {
            // Inserção bem-sucedida
            return response()->json([
                'success' => true,
                'data' => $comentarioCreated
            ], 201);
        } else {
            // Falha na inserção
            return response()->json([
                'success' => false,
                'message' => 'Falha ao inserir o comentário na base de dados.'
            ], 500);
        }

        return $comentarioCreated;
    }

    public function getOcorrenciasCliente($perPage,$page,$idCliente): LengthAwarePaginator
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/documents/occurrences?perPage='.$perPage.'&Page='.$page.'&customer_id='.$idCliente,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if($response_decoded != null)
        {
            $currentItems = array_slice($response_decoded->occurrences, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);

        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, $response_decoded->total_pages,$perPage);
        }

    
        return $itemsPaginate; 
    }

    public function getNumberOfPagesOcorrenciasCliente($perPage,$idCliente): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SANIPOWER_URL_DIGITAL').'/api/documents/occurrences?perPage='.$perPage.'&Page=1&customer_id='.$idCliente,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $arrayInfo = [];

        $arrayInfo = ["nr_paginas" => $response_decoded->total_pages, "nr_registos" => $response_decoded->total_records];

        return $arrayInfo;
    }


    public function storeVisita($idVisita,$numero_cliente,$assunto,$relatorio,$pendentes,$comentario_encomendas,$comentario_propostas,$comentario_financeiro,$comentario_occorencias): JsonResponse
    {
    
        $checkVisitaAgendada = Visitas::where('id_visita_agendada',$idVisita)->first();

        if(!empty($checkVisitaAgendada)){

            return response()->json([
                'success' => false,
                "type" => 1,
                'data' => "Essa Visita já foi registada"
            ], 201);
        }

        $visitaCreate = Visitas::create([
            "id_visita_agendada" => $idVisita,
            "numero_cliente" => $numero_cliente,
            "assunto" => $assunto,
            "relatorio" => $relatorio,
            "pendentes_proxima_visita" => $pendentes,
            "comentario_encomendas" => $comentario_encomendas,
            "comentario_propostas" => $comentario_propostas,
            "comentario_financeiro" => $comentario_financeiro,
            "comentario_ocorrencias" => $comentario_occorencias,
            "data" => date('Y-m-d'),
            "user_id" => Auth::user()->id
        ]);

        VisitasAgendadas::where('id',$idVisita)->update([
            "finalizado" => 1
        ]);

        if ($visitaCreate) {
            // Inserção bem-sucedida
            return response()->json([
                'success' => true,
                'data' => $visitaCreate
            ], 201);
        } else {
            // Falha na inserção
            return response()->json([
                'success' => false,
                'type' => 0,
                'message' => 'Falha ao inserir visita na base de dados.'
            ], 500);
        }

        return $visitaCreate;
    }


}