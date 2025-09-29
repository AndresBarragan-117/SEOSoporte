<?php

namespace App\Http\Requests\Implementacion;
use Illuminate\Foundation\Http\FormRequest;

class TipoCapturaValidator extends FormRequest{
    protected $redirect = "/tipoCaptura";
    
    public function rules(){
        return [
            'nombre' => 'required|max:250|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
            'descripcion' => 'required|max:250|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
        ];  
    }
    
    public function messages(){
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.max' => 'El máximo permitido son 250 caracteres',
            'nombre.regex' => 'Sólo se aceptan letras',
            'descripcion.required' => 'La descripción es requerido',
            'descripcion.max' => 'El máximo permitido son 250 caracteres',
            'descripcion.regex' => 'Sólo se aceptan letras',
        ];
    }
    
    public function response(array $errors){
        //return back()->with('error', $errors);
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
