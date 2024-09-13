@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span>
     Tacografo
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h3 class="page-title">
        <a class="btn btn-sm btn-danger" href="{{ route('ativo.veiculo.tacografo.adicionar', $veiculo->id) }}">
            Adicionar
        </a>
    </h3>
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

                {{-- DADOS DO VEÍCULO/MÁQUINA --}}
                @include('pages.ativos.veiculos.partials.header')

                <table id="exemplo" class="table display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center" width="8%">ID</th>
                            <th>Descrição</th>
                            <th>Data da emissão</th>
                            <th>Data do vencimento</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



@endsection

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        carregaTacografos();
    });

    function carregaTacografos() {
        $("#exemplo").dataTable().fnDestroy();
        var idATacografo = '{{$veiculo->id}}';
        var url = "{{route('ativo/veiculo/tacografo/index',['veiculo' => ':id'])}}";
        url = url.replace(':id', idATacografo);

        $('#exemplo').DataTable({

            processing: true,
            serverSide: true,

            ajax: {
                url: url,
                method: 'GET',

            },
            

            columns: [{
                    "data": "id",
                    "render": function(data, type, row) {
                    var teste = row.id;
                    var idATacografo =
                        `
                        <div class="text-center">`+row.id+`
                                    
                        </div>
                        
                        `;
                    return idATacografo;
                }
                },
                {
                    "data": "descricao"
                },
                {
                    "data": "data_da_emissao"
                },

                {
                    "data": "data_do_vencimento"
                }
                
            ],
           
            
            columnDefs: [{ //ativo/veiculo/tacografo/index
                "targets": 4,
                "render": function(data, type, row) {

                    var idEdit = row.id;
                    var urlEdit = "{{ route('ativo/veiculo/tacografo/edit',['id' => ':idEdit'] ) }}";
                    urlEdit = urlEdit.replace(':idEdit',  idEdit)

                    var idDelete = row.id;
                    var urlDelet = "{{ route('ativo/veiculo/tacografo/delete',['id' => ':idDelete']) }}";
                    urlDelet = urlDelet.replace(':idDelete',idDelete);
                    
                    var Btn =
                        `
                            <div class="d-flex flex-row bd-highlight m-0 justify-content-center">
                                <div class="p-1 bd-highlight">
                                    <a href="`+urlEdit+`" title="Editar">
                                        <button type="button" class="btn btn-warning btn-sm"><i class="mdi mdi-pencil mdi-18px"></i></button>
                                    </a>
                                </div>
                                <div class="p-1 bd-highlight">
                                    <form class="m-0 p-0" action="` + urlDelet + `" method="POST">
                                        @csrf
                                        @method('delete')
                                            <a class="excluir-padrao" data-id="` + row.id + `" data-table="empresas" data-module="cadastro/empresa">
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                    <i class="mdi mdi-delete mdi-18px"></i>
                                                </button>
                                            </a>
                                    </form>                                   
                                </div>                              
                            </div>

                                  `;
                    return Btn;
                }
            }],
            language: {
                search: 'Buscar informação da Lista',
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json',
            },
        });
    }
</script>