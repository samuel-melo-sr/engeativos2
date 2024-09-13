<div class="card">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="8%">ID</th>
                        <th>Obra</th>
                        <th>Matrícula</th>
                        <th>Nome Completo</th>
                        <th>Função</th>
                        <th>WhatsApp</th>
                        <th>E-mail</th>
                        <th>Status</th>
                        <th class="text-center {{ session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 14 ? 'd-block' : 'd-none' }}" width="13%">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lista as $v)
                    <tr>
                        <td class="text-center">{{ $v->id }}</span></td>
                        <td>{{ $v->obra->codigo_obra ?? "Obra desativada"}}</td>
                        <td>{{ $v->matricula ?? '-' }}</td>
                        <td class="text-uppercase">{{ $v->nome }}
                            @php
                                $count_1 = $v->qualificacoes->where('situacao', 1)->count();
                                $count_18 = $v->qualificacoes->where('situacao', 18)->count();
                            @endphp
                            
                            @if($count_1 > 0 || $count_18 > 0)
                                
                                <lord-icon data-bs-toggle="tooltip" data-bs-placement="top" title="Falta  {{ $count_1}} documento(s)" target="div" loading="interaction" trigger="hover" src="https://media.lordicon.com/icons/wired/outline/1140-error.json">
                                    <img alt="" loading="eager" src="https://media.lordicon.com/icons/wired/outline/1140-error.svg">
                                </lord-icon>
                            
                            @else
                             
                            @endif
                        </td>
                        
                        @if($v->funcao && $v->funcao->funcao)
                            <td class="text-uppercase">{{ $v->funcao->funcao }}</td>
                        @else
                            <td class="text-danger">Falta cadastrar a função</td>
                        @endif
                        
                        <td>{{ $v->celular }}</td>
                        <td>{{ $v->email }}</td>
                        <td>{{ $v->status }} </td>
                        <td class="d-flex text-center {{ session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 14 ? 'd-block' : 'd-none' }}">
    
                            <a class="btn btn-warning  btn-sm mr-2" href="{{ route('cadastro.funcionario.editar', $v->id) }}" title="Editar">
                                <i class="mdi mdi-pencil"></i>
                            </a>
    
                            <a class="btn btn-info btn-sm mx-2" href="{{ route('cadastro.funcionario.show', $v->id) }}" title="Visualizar">
                                <i class="mdi mdi-eye"></i>
                            </a>
    
                            @if (session()->get('usuario_vinculo')->id_nivel == 1 
                                or session()->get('usuario_vinculo')->id_nivel == 15
                                or session()->get('usuario_vinculo')->id_nivel == 10)
                                <form action="{{ route('cadastro.funcionario.destroy', $v->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <!-- Botão para gerar a etiqueta -->
                            <a class="btn btn-success btn-sm mx-2" id="etiqueta_funcionario"
                                data-id="{{ $v->id }}" data-bs-toggle="modal"
                                data-bs-target="#modal_funcionario"
                                href="{{ route('cadastro.funcionario.show', $v->id) }}" title="Imprimir etiqueta">
                                <i class="mdi mdi-printer-settings"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">

            <div class="paginacao mx-3">
                {{$lista->onEachSide(2)->links()}}
            </div>          
        </div>
    </div>

</div>