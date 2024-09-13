@extends('dashboard')
@section('title', 'Retirada de Ferramentas')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Retirada de Ferramentas
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ferramental <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav> 
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong><br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @php
                $action = isset($store) ? route('ferramental.retirada.update', $store->id) : route('ferramental.retirada.store');
                @endphp
                <form method="post" action="{{ $action }}">
                    @csrf

                    @if (Auth::user()->user_level == 1)
                    <div class="row">
                        <div class="col-12">
                            @include('components.fields.id_obra')
                        </div>
                    </div>
                    @endif

                    @if (Auth::user()->user_level >= 2)
                    <input id="id_obra" name="id_obra" type="hidden" value="{{ session('obra')->id_obra }}">
                    @endif

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label" for="id_obra">Obra</label> <button class="badge badge-primary" data-toggle="modal" data-target="#modal-add" type="button"><i class="mdi mdi-plus"></i></button>
                            <select class="form-select select2" id="id_obra" name="id_obra" required>
                                <option value="">Selecione uma Obra</option>
                                @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}" {{ old('id_obra') == $obra->id ? 'selected' : '' }}>
                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <label class="form-label" id="helper" for="id_funcionario">Funcionário</label>

                            <select class="form-select select2" id="id_funcionario" name="id_funcionario">
                                <option value="">Selecione um Funcionário</option>
                                @foreach ($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}" @php if(old('id_funcionario', @$store->id_funcionario) == $funcionario->id) echo "selected"; @endphp>
                                    {{ $funcionario->matricula }} - {{ $funcionario->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-3">
                            <label class="form-label" for="data_solicitacao">Data de Solicitação</label>
                            <input class="form-control" name="data_solicitacao" type="date" value="@php echo date('Y-m-d'); @endphp" disabled>
                        </div>

                        <div class="col-3">
                            <label class="form-label" for="devolucao_prevista">Devolução Prevista</label>
                            <input class="form-control" id="devolucao_prevista" name="devolucao_prevista" type="datetime-local" value="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-3">
                            <label class="form-label" for="observacoes">Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-5">
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <label class="form-label">Pesquisar </label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Busque pelo item ou código do patrimônio">
                                </div>
                            </div>
                            <table class="table-striped table-hover table mt-5" id="retirada-itens">
                                <thead>
                                    <tr class="">
                                        <th width="10%">Patrimônio</th>
                                        <th width="30%">Estoque na Obra</th>
                                        <th>Item</th>
                                        <th>Marcar/Desmarcar</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($estoques as $estoque)
                                    <tr>
                                        <td><span class="bg-primary p-1 rounded">{{ $estoque->patrimonio }}</span></td>
                                        <td><span class="bg-secondary p-1 rounded">{{ $estoque->obra->codigo_obra ?? '' }}</span> {{ $estoque->obra->razao_social ?? '' }}
                                        </td>
                                        <td>{{ $estoque->ativo_externo->titulo }}</td>
                                        <td>
                                            <div class="form-switch">
                                                <input class="form-check-input" id="id_ativo_externo" name="id_ativo_externo[]" type="checkbox" value="{{ $estoque->id }}" role="switch">
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button class="btn btn-gradient-primary font-weight-medium" id="btn-submit" type="submit">Salvar</button>

                    <a href="{{ route('ferramental.retirada') }}">
                        <button class="btn btn-gradient-danger font-weight-medium" type="button">Cancelar</button>
                    </a>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL INCLUSAO RAPIDA DE OBRAS --}}
@include('pages.cadastros.obra.partials.inclusao-rapida')

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#id_funcionario').change(function() {
            var usuario = $(this).val();
            var url = "{{ route('ferramental.retirada.bloqueio', ':usuario') }}".replace(':usuario', usuario);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    
                    var quantidade = response.quantidade;

                    if (quantidade >= 1) {
                        $('#helper').html('<span class="text-danger"><strong>FUNCIONÁRIO BLOQUEADO</strong></span>');
                        $('#devolucao_prevista').attr('disabled', 'disabled');
                        $('#observacoes').attr('disabled', 'disabled');
                        $('#btn-submit').attr('disabled', 'disabled');
                    } else {
                        $('#helper').html('<span class="text-primary">Nenhum bloqueio encontrado</span>');
                        $('#devolucao_prevista').removeAttr('disabled');
                        $('#observacoes').removeAttr('disabled');
                        $('#btn-submit').removeAttr('disabled');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

    });
</script>