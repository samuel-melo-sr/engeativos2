<?php

namespace App\Http\Controllers;

use App\Models\CadastroFornecedor;
use Illuminate\Http\Request;
use App\Interfaces\VeiculoAbastecimentoRepositoryInterface;
use App\Models\Veiculo;
use App\Models\VeiculoAbastecimento;
use App\Models\Anexo;
use App\Models\CadastroFuncionario;
use App\Models\VeiculoQuilometragem;
use App\Models\VeiculoHorimetro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class VeiculoAbastecimentoController extends Controller
{
    protected $repository;

    public function __construct(VeiculoAbastecimentoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Veiculo $veiculo)
    {
        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();
        $funcionarios = CadastroFuncionario::all();
        $abastecimentos = VeiculoAbastecimento::with('veiculo')
            ->where('veiculo_id', $veiculo->id)
            ->get();

        $media_quantidade = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('quantidade');

        $media_valor_do_litro = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('valor_do_litro');

        $media_valor_total = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('valor_total');

        $last = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->orderByDesc('id')
            ->first();

        $abastecimentos = $this->repository->getAll();

        return view('pages.ativos.veiculos.abastecimento.index', compact('veiculo', 'abastecimentos', 'last', 'fornecedores', 'media_quantidade', 'media_valor_do_litro', 'media_valor_total', 'funcionarios'));
    }

    public function create(Veiculo $veiculo)
    {
        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();
        $funcionarios = CadastroFuncionario::all();

        $maiorQuilometragem = $veiculo->quilometragem()->max('quilometragem_nova');

        return view('pages.ativos.veiculos.abastecimento.create', compact('veiculo', 'fornecedores', 'funcionarios', 'maiorQuilometragem'));
    }

    public function store(Request $request)
    {
        try {
            $abastecimento = $this->repository->create($request->all());
            Toastr::success('Abastecimento cadastrado com sucesso!');
            Log::info('Abastecimento cadastrado', ['abastecimento' => $abastecimento]);
            return redirect()->route('veiculo_abastecimentos.index');
        } catch (\Exception $e) {
            Toastr::error('Erro ao cadastrar abastecimento.');
            Log::error('Erro ao cadastrar abastecimento', ['error' => $e->getMessage()]);
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $abastecimento = $this->repository->getById($id);
        return view('veiculo_abastecimentos.edit', compact('abastecimento'));
    }

    public function update(Request $request, $id)
    {
        try {
            $abastecimento = $this->repository->update($id, $request->all());
            Toastr::success('Abastecimento atualizado com sucesso!');
            Log::info('Abastecimento atualizado', ['abastecimento' => $abastecimento]);
            return redirect()->route('veiculo_abastecimentos.index');
        } catch (\Exception $e) {
            Toastr::error('Erro ao atualizar abastecimento.');
            Log::error('Erro ao atualizar abastecimento', ['error' => $e->getMessage()]);
            return redirect()->back();
        }
    }

    public function show(Veiculo $veiculo)
    {
        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();
        $funcionarios = CadastroFuncionario::all();
        $abastecimentos = VeiculoAbastecimento::with('veiculo')
            ->where('veiculo_id', $veiculo->id)
            ->get();

        $media_quantidade = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('quantidade');

        $media_valor_do_litro = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('valor_do_litro');

        $media_valor_total = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->avg('valor_total');

        $last = VeiculoAbastecimento::where('veiculo_id', $veiculo->id)
            ->orderByDesc('id')
            ->first();

        return view('pages.ativos.veiculos.abastecimento.show', compact('veiculo', 'abastecimentos', 'last', 'fornecedores', 'media_quantidade', 'media_valor_do_litro', 'media_valor_total', 'funcionarios'));
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            Toastr::success('Abastecimento deletado com sucesso!');
            Log::info('Abastecimento deletado', ['id' => $id]);
            return redirect()->route('veiculo_abastecimentos.index');
        } catch (\Exception $e) {
            Toastr::error('Erro ao deletar abastecimento.');
            Log::error('Erro ao deletar abastecimento', ['error' => $e->getMessage()]);
            return redirect()->back();
        }
    }
}
