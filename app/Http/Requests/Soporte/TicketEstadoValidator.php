<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class TicketEstadoValidator extends FormRequest{
    protected $redirect = "/ticketEstado";
    
    public function rules() {
        return [
            'nombre' => 'required|max:300',
            'orden' => 'required|max:5|regex:/^[0-9]+$/'
        ];  
    }
    
    public function messages(){
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.max' => 'El máximo permitido son 300 caracteres',
            'orden.required' => 'El orden es requerido',
            'orden.max' => 'El máximo permitido son 5 caracteres',
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
