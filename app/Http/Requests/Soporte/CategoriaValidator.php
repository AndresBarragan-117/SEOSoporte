<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class CategoriaValidator extends FormRequest{
    protected $redirect = "/categoria";
    
    public function rules(){
        return [
            'codigo' => 'required|max:20',
            'nombre' => 'required|max:300|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
        ];  
    }
    
    public function messages(){
        return [
            'codigo.required' => 'El código es requerido',
            'codigo.max' => 'El máximo permitido son 20 caracteres',
            'nombre.required' => 'La descripción es requerido',
            'nombre.max' => 'El máximo permitido son 250 caracteres',
            'nombre.regex' => 'Sólo se aceptan letras',
        ];
    }
    
    public function response(array $errors){
        //return back()->with('error', $errors);
        if($this->isMethod("PUT"))
        {
            return back()->withErrors($errors, 'error')->withInput();
        }
        return redirect($this->redirect)
                ->withErrors($errors, 'error')
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
