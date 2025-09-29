<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioValidator extends FormRequest{
    protected $redirect = "/usuario";
    
    public function rules(){
        return [
            'name' => 'required|max:250|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
            'email' => 'required|max:250',
            'password' => 'required|max:250',
        ]; 
    }
    
    public function messages(){
        return [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El máximo permitido son 250 caracteres',
            'name.regex' => 'Sólo se aceptan letras',
            'password.required' => 'La descripción es requerido',
            'password.max' => 'El máximo permitido son 250 caracteres',
            'email.required' => 'El email es requerido',
            'email.max' => 'El máximo permitido son 250 caracteres',
        ];
    }
    
    public function response(array $errors){
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
