<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class TicketEmailPlantillaValidator extends FormRequest{
    protected $redirect = "/ticketEmailPlantilla";
    
    public function rules() {
        return [
            'asunto' => 'required|max:150',
            'contenido' => 'required|max:300'
        ];  
    }
    
    public function messages(){
        return [
            'asunto.required' => 'El asunto es requerido',
            'asunto.max' => 'El máximo permitido son 300 caracteres',
            'contenido.required' => 'El contenido es requerido',
            'contenido.max' => 'El máximo permitido son 5 caracteres'
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
