<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    public function index(){
        $linha = Curso::all(); //como um select * from cursos
        return view('admin.cursos.index', compact('linhas'));
    }

    public function adicionar(){
        return view('admin.cursos.adicionar');
    }

    public function editar($id){
        $linha = Curso::find($id);
        //carrega o registro (realiza select e um fetch internamente) e guarda na variável $row
        return view('admin.cursos.editar', compact('linha'));
    }

    public function excluir($id){
        Curso::find($id)->delete();
        // apos selecionar o registro, é chamado o método DELETE do OBJETO registro
        return redirect()->route('admin.cursos');
        //depois de excluir volta a listar os cursos
    }

    private function ajusteDados(Request $req){
        $dados = $req->all();
        if(isset($dados['publicado'])){
            $dados['publicado'] = 'sim';
        }else{
            $dados['publicado'] = 'nao';
        }
        if($req->hasFile('arquivo')){
            $imagem = $req->file('arquivo');
            $num = rand(1111,9999);
            $dir = "img/cursos/";
            $ex = $imagem->guessClientExtension();
            $nomeImagem = "imagem_".$num.".".$ex;
            $imagem->move($dir,$nomeImagem);
            $dados['imagem'] = $dir."/".$nomeImagem;
        }
        return $dados;
    }

    public function salvar(Request $req)
    {
        $dados = $this->ajusteDados($req);
        Curso::create($dados);
        return redirect()->route('admin.cursos');
    }

    public function atualizar(Request $req, $id)
    {
        $dados = $this->ajusteDados($req);
        Curso::find($id)->update($dados);
        return redirect()->route('admin.cursos');
    }

    
}