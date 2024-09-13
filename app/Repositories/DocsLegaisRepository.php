<?php

namespace App\Repositories;

use App\Interfaces\DocsLegaisRepositoryInterface;
use App\Models\DocsLegais;
use Illuminate\Support\Facades\Auth;

class DocsLegaisRepository implements DocsLegaisRepositoryInterface
{
    public function index()
    {
        return DocsLegais::all();
    }

    public function getByTipoVeiculo(int $tipo_veiculo_id)
    {
        return DocsLegais::where('tipo_veiculo', $tipo_veiculo_id)->get();
    }


    public function create(array $data)
    {
        return DocsLegais::create($data);
    }

    public function store(array $data)
    {
        foreach ($data['nome_documento'] as $key => $create_docs) {
            $create_docs_legais = new DocsLegais;

            // Como só há um tipo de veículo, sempre pegamos o primeiro item [0]
            $create_docs_legais->tipo_veiculo = $data['tipo_veiculo'][0];
            $create_docs_legais->nome_documento = $create_docs;
            $create_docs_legais->validade = $data['validade'][$key];
            $create_docs_legais->user_create = Auth::user()->email ?? "samuel@email.com";

            $create_docs_legais->save();
        }

        return true; // Retorna true após salvar todos os registros
    }



    public function edit(int $id)
    {
        return DocsLegais::findOrFail($id);
    }

    public function update(int $id, $data)
    {
        $edit_doc_legal = DocsLegais::findOrFail($id);
        $edit_doc_legal->user_edit = Auth::user()->email ?? "samuel@email.com";
        $edit_doc_legal->update($data);

        return true;
    }

    public function show(int $id)
    {
        return DocsLegais::findOrFail($id);
    }

    public function delete(int $id)
    {
        $doc = DocsLegais::findOrFail($id);
        $doc->delete();
        return $doc;
    }

    public function search(string $query)
    {
        return DocsLegais::where('nome_documento', 'like', '%' . $query . '%')->get();
    }

    public function paginate(int $perPage)
    {
        return DocsLegais::paginate($perPage);
    }
}
