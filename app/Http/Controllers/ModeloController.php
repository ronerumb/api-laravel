<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
     }
    public function index()
    {

         return response()->json($this->with('marca')->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem/modelos','public');


        $modelo = $this->modelo->create([
            'marca_id'=>$request->marca_id,
            'nome' =>$request->nome,
            'imagem' =>$imagem_urn,
            'numero_portas'=>$request->numero_portas,
            'lugares'=>$request->lugares,
            'air_bag'=>$request->air_bag,
            'abs'=>$request->abs
        ]);
        return response()->json($modelo,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null){
            return response()->json('Modelo não encontrado', 404);
        }
        return response()->json($modelo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null){
         return response()->json('Modelo não encontrado', 404);
        }
       
 
        if($request->method() === 'PATCH'){
         
         $regrasDinamicas = array();
 
         foreach($modelo->rules() as $input => $regra){
             
             if(array_key_exists($input, $request->all())) {
                 $regrasDinamicas[$input] = $regra;
             }
 
             $request->validate($regrasDinamicas);
         }
        }else{
         $request->validate($modelo->rules());
        }
 
        if($request->file('imagem')){
         Storage::disk('public')->delete($modelo->imagem);
        }
 
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem/modelos', 'public');
 
  
        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();

        return $modelo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
   
        if($modelo == null){
            return response()->json('Modelo não encontrado', 404);
        }

        
            Storage::disk('public')->delete($modelo->imagem);
           
        
        $modelo->delete();
    }
}
