<?php

namespace App\Http\Requests\Seguridad;
use Illuminate\Foundation\Http\FormRequest;

class FormularioValidator extends FormRequest{
    protected $redirect = "/formulario";
    
    public function rules(){
        return [
            'nombre' => 'required|max:250|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
            'path' => 'required|max:250|regex:/^[aA-zZ \/]+$/i',
            'tag' => 'required|max:4|regex:/^[0-9]+$/i',
            'carpeta' => 'required',
        ];  
    }
    
    public function messages(){
        return [
            'nombre.required' => 'el nombre es requerido',
            'nombre.max' => 'El máximo de letras permitido son 250 caracteres',
            'nombre.regex' => 'Sólo se aceptan letras',
            'path.required' => 'el path es requerido',
            'path.max' => 'El máximo de letras permitido son 250 caracteres',
            'path.regex' => 'Sólo se aceptan letras',
            'tag.required' => 'el path es requerido',
            'tag.max' => 'El máximo de letras permitido son 4 caracteres',
            'tag.regex' => 'Sólo se aceptan números',
            'carpeta.required' => 'la carpeta es requerida',
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
