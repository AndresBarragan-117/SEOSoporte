<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class KBArticuloValidator extends FormRequest{
    protected $redirect = "/kbArticulo";
    
    public function rules(){
        return [
            'asunto' => 'required|max:300',
        ];  
    }
    
    public function messages(){
        return [
            'asunto.required' => 'El asunto es requerido',
            'asunto.max' => 'El máximo permitido son 250 caracteres'
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