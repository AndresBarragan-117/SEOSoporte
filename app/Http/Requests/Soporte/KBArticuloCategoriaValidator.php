<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class KBArticuloCategoriaValidator extends FormRequest{
    protected $redirect = "/kbArticuloCategoria";
    
    public function rules(){
        return [
            'nombre' => 'required|max:300|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
            'nombreEtiqueta' => 'required|max:300|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
            'orden' => 'required|max:5|regex:/^[0-9]+$/'
        ];  
    }
    
    public function messages(){
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.max' => 'El máximo permitido son 250 caracteres',
            'nombre.regex' => 'Sólo se aceptan letras',
            'nombreEtiqueta.required' => 'El nombre de la etiqueta es requerida',
            'nombreEtiqueta.max' => 'El máximo permitido son 250 caracteres',
            'nombreEtiqueta.regex' => 'Sólo se aceptan letras',
            'orden.required' => 'El orden es requerido',
            'orden.max' => 'El máximo permitido son 5 caracteres',
            'orden.regex' => 'Sólo se aceptan números'
        ];
    }
    
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
