<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class MensajePlantillaValidator extends FormRequest{
    protected $redirect = "/mensajePlantilla";
    
    public function rules() {
        return [
            'pregunta' => 'required|max:300',
            'respuesta' => 'required|max:300',
        ];  
    }
    
    public function messages(){
        return [
            'pregunta.required' => 'La pregunta es requerida',
            'pregunta.max' => 'El máximo permitido son 20 caracteres',
            'respuesta.required' => 'La respuesta es requerida',
            'respuesta.max' => 'El máximo permitido son 20 caracteres',
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
