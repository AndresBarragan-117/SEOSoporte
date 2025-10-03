<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class KBArticuloValidator extends FormRequest{
    protected $redirect = "/kbArticulo";
    
    public function rules(){
        return [
            'categoria' => 'required|max:250',
            'asunto' => 'required|max:300',
            'contenido' => 'required',
            'tipo' => 'required|max:250'
        ];  
    }
    
    public function messages(){
        return [
            'categoria.required' => 'La categoría es requerida',
            'asunto.required' => 'El asunto es requerido',
            'asunto.max' => 'El máximo permitido son 250 caracteres',
            'contenido.required' => 'El contenido es requerido',
            'tipo.required' => 'El tipo es requerido'
        ];
    }
    
    // El método response() que tienes actualmente no es necesario en FormRequest modernos. 
    // Laravel automáticamente hace:
    // Errors($validator)->withInput();
    // por sí solo. Podrías eliminar totalmente el response() para evitar duplicar 
    // comportamiento o posibles errores futuros.
    public function response(array $errors){
        if($this->isMethod("PUT"))
        {
            return back()->withErrors($errors)->withInput();
        }
        return redirect($this->redirect)
                ->withErrors($errors)
                ->withInput();
    }
    
    public function authorize(){
        return true;
    }

    public function setRedirect($redirect)
    {
    	$this->redirect .= $redirect;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }
}