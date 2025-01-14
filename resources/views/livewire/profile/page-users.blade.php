<div>
<div class="col-12">
    <div class="card mb-3">
        <div class="card-header uppercase">
            <div class="caption">
                <i class="ti-filter"></i> Filtros
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-4">
                        <label class="mt-2">Nome do Utilizador</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nome do Utilizador" wire:model.debounce.300ms="filterNome">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="mt-2">Email do Utilizador</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ti-ticket"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Email do Utilizador" wire:model.debounce.300ms="filterEmail">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="mt-2">Telemóvel</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ti-microphone-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Telemóvel" wire:model.debounce.300ms="filterTelemovel">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-xl-8 col-xs-12">
                            <div class="caption uppercase">
                                <i class="ti-user"></i> Utilizadores
                            </div>
                        </div>
                        <div class="col-xl-4 col-xs-12 text-right">
                            <button class="btn btn-primary" id="abrirModalCriaUser"><i class="ti-plus"></i> Criar Utilizador</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="dataTables_wrapper" class="dataTables_wrapper container" style="margin-left:0px;padding-left:0px;margin-bottom:10px;">
                        <div class="left">
                            <label>Mostrar
                                <select name="perPage" wire:model="perPage">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                registos</label>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover init-datatable" id="tabela-cliente">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nome do Utilizador</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Nível de Acesso</th>
                                    <th>ID Vendedor PHC</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr data-href="#">
                                    <td>
                                        <div style="display: flex;align-items: center;">
                                            @if ($user->imagem == null || $user->imagem == '')
                                                <div class="img-temporary-navbar" style="width: 42px;height: 42px;margin-right: 10px;">{{ ucfirst(substr($user->name, 0, 1)) }}</div>
                                            @else
                                                <img class="img-navbar" style="width: 42px;height: 42px;margin-right: 10px;" src="{{ asset('storage/profile/' . $user->imagem) }}" alt=""/>
                                            @endif
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->telefone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->nivel }}</td>
                                    <td>{{ $user->id_phc }}</td>
                                    <td>
                                        @if($user->status == "Inativo")
                                            <button class="btn btn-sm btn-chili btn-round" disabled>{{ $user->status }}</button>
                                        @else
                                            <button class="btn btn-sm btn-jade btn-round" disabled>{{ $user->status }}</button>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalEditarUser{{$user->id}}">
                                            <i class="fas fa-user-cog"></i>
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modalEditarUser{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="modalEditarUserLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEditarUserLabel">Editar Utilizador</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            @livewire('profile.edit-profile', ['userId' => $user->id], key($user->id))
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAdicionarUser" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdicionarUserLabel">Adicionar utilizador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @livewire('profile.create-profile')
        </div>
    </div>
</div>
</div>