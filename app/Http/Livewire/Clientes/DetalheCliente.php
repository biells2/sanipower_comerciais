<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use App\Interfaces\ClientesInterface;
use Livewire\WithPagination;

class DetalheCliente extends Component
{
    use WithPagination;

    private ?object $clientesRepository = NULL;
    protected ?object $clientes = NULL;
    public string $idCliente = "";

    public int $perPage = 10;
    public int $pageChosen = 1;
    public int $numberMaxPages;
    public int $totalRecords = 0;

    private ?object $detailsClientes = NULL;
    private ?object $analysisClientes = NULL;

    public string $tabDetail = "show active";
    public string $tabAnalysis = "";
    public string $tabEncomendas = "";
    public string $tabPropostas = "";
    public string $tabFinanceiro = "";
    public string $tabOcorrencias = "";
    public string $tabVisitas = "";
    public string $tabAssistencias = "";
    public string $tabCampanhas = "";

    public function boot(ClientesInterface $clientesRepository)
    {
        $this->clientesRepository = $clientesRepository;
    }

    private function initProperties(): void
    {
        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }

    }

    public function mount($cliente)
    {
        $this->initProperties();
        $this->idCliente = $cliente;
        $this->detailsClientes = $this->clientesRepository->getDetalhesCliente($this->idCliente);
        $this->analysisClientes = $this->clientesRepository->getListagemAnalisesCliente($this->perPage,$this->pageChosen,$this->idCliente);
        
        $getInfoClientes = $this->clientesRepository->getNumberOfPagesAnalisesCliente($this->perPage,$this->idCliente);

        $this->numberMaxPages = $getInfoClientes["nr_paginas"] + 1;
        $this->totalRecords = $getInfoClientes["nr_registos"];
    }

    
    public function gotoPage($page)
    {
        $this->pageChosen = $page;
        $this->detailsClientes = $this->clientesRepository->getDetalhesCliente($this->idCliente);
        $this->analysisClientes = $this->clientesRepository->getListagemAnalisesCliente($this->perPage,$this->pageChosen,$this->idCliente);
    
        $this->tabDetail = "";
        $this->tabAnalysis = "show active";
        $this->tabEncomendas = "";
        $this->tabPropostas = "";
        $this->tabFinanceiro = "";
        $this->tabOcorrencias = "";
        $this->tabVisitas = "";
        $this->tabAssistencias = "";
        $this->tabCampanhas = "";
    }

   
    public function previousPage()
    {
        $this->detailsClientes = $this->clientesRepository->getDetalhesCliente($this->idCliente);

        if ($this->pageChosen > 1) {
            $this->pageChosen--;
            $this->analysisClientes = $this->clientesRepository->getListagemAnalisesCliente($this->perPage,$this->pageChosen,$this->idCliente);
        }
        else if($this->pageChosen == 1){
            $this->analysisClientes = $this->clientesRepository->getListagemAnalisesCliente($this->perPage,$this->pageChosen,$this->idCliente);
        }

        $this->tabDetail = "";
        $this->tabAnalysis = "show active";
        $this->tabEncomendas = "";
        $this->tabPropostas = "";
        $this->tabFinanceiro = "";
        $this->tabOcorrencias = "";
        $this->tabVisitas = "";
        $this->tabAssistencias = "";
        $this->tabCampanhas = "";
    }

    public function nextPage()
    {
        $this->detailsClientes = $this->clientesRepository->getDetalhesCliente($this->idCliente);

        if ($this->pageChosen < $this->numberMaxPages) {
            $this->pageChosen++;
            $this->analysisClientes = $this->clientesRepository->getListagemAnalisesCliente($this->perPage,$this->pageChosen,$this->idCliente);
        }

        $this->tabDetail = "";
        $this->tabAnalysis = "show active";
        $this->tabEncomendas = "";
        $this->tabPropostas = "";
        $this->tabFinanceiro = "";
        $this->tabOcorrencias = "";
        $this->tabVisitas = "";
        $this->tabAssistencias = "";
        $this->tabCampanhas = "";
    }

    public function getPageRange()
    {
        $currentPage = $this->pageChosen;
        $lastPage = $this->numberMaxPages;

        $start = max(1, $currentPage - 2);
        $end = min($lastPage, $currentPage + 2);

        return range($start, $end);
    }

    public function isCurrentPage($page)
    {
        return $page == $this->pageChosen;
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
        session()->put('perPage', $this->perPage);

        $this->tabDetail = "";
        $this->tabAnalysis = "show active";
        $this->tabEncomendas = "";
        $this->tabPropostas = "";
        $this->tabFinanceiro = "";
        $this->tabOcorrencias = "";
        $this->tabVisitas = "";
        $this->tabAssistencias = "";
        $this->tabCampanhas = "";

        $this->detailsClientes = $this->clientesRepository->getDetalhesCliente($this->idCliente);

        $this->analysisClientes = $this->clientesRepository->getListagemAnalisesCliente($this->perPage,$this->pageChosen,$this->idCliente);

        $getInfoClientes = $this->clientesRepository->getNumberOfPagesAnalisesCliente($this->perPage,$this->idCliente);

        $this->numberMaxPages = $getInfoClientes["nr_paginas"] + 1;
        $this->totalRecords = $getInfoClientes["nr_registos"];
    }

    
    public function paginationView()
    {
        return 'livewire.pagination';
    }

    public function render()
    {
        return view('livewire.clientes.detalhe-cliente',["detalhesCliente" => $this->detailsClientes, "analisesCliente" =>$this->analysisClientes]);
    }
}
