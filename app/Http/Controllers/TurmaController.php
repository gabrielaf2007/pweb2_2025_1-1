<?php

namespace App\Http\Controllers;

use App\Models\CategoriaTurma;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;

class TurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Curso $curso)
    {
        //select * from turmas
        $dados = $curso->turmas;

        return view(
            'turma.list',
            ['dados' => $dados]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Curso $cur)
    {
        return view('turma.form', ['curso' => $curso,]);
    }

    public function edit(string $id)
    {
        $dado = Turma::findOrFail($id);

        $cursos = Curso::orderBy('nome')->get();

        return view(
            'turma.form',
            [
                'dado' => $dado,
                'cursos' => $cursos
            ]
        );
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'nome' => 'required|min:3|max:100',
            'cpf' => 'required|max:14',
            'telefone' => 'nullable|min:10|max:40',
            'categoria_id' => 'required',
           // 'imagem' => 'nullable|image|mimes:png,jpeg,jpg',
        ], [
            'nome.required' => 'O :attribute é obrigatório',
            'cpf.required' => 'O :attribute é obrigatório',
            'categoria_id.required' => 'O :attribute é obrigatório',
            'imagem.imagem' => 'O :attribute deve ser enviado',
            'imagem.mimes' => 'A :attribute deve ser das extensões: PNG, JPEG e JPG',

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $data = $request->all();

        $turma = Turma::create($data);

        return redirect('turma')->route('curso_turmas', $turma->curso_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validateRequest($request);

        $data = $request->all();

        $turma = Turma::updateOrCreate(
            ['id' => $id],
            $data
        );

        return redirect('turma')->route('curso.turmas', $turma->curso_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //  dd("teste");
        $dado = Turma::find($id);

        $dado->delete();

         return redirect('turma')->route('curso.turmas', $turma->curso_id);
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            //select * from turmas
            $dados = Turma::where(
                $request->tipo,
                'like',
                "%$request->valor%"
            )->get();
        } else {
            $dados = Turma::all();
        }

        return view(
            'turma.list',
            ['dados' => $dados]
        );
    }

    public function report()
    {
        $turmas = Turma::orderBy('nome')->get();

        $data = [
            'titulo' => "Listagem Turmas",
            'turmas' => $turmas,
        ];

        $pdf = Pdf::loadView('turma.report', $data);
        return $pdf->download('relatorio_listagem_turmas.pdf');
    }
}
