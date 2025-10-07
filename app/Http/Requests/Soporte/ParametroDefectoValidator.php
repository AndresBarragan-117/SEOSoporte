<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class ParametroDefectoValidator extends FormRequest{
    protected $redirect = "/parametroDefecto";
    
    public function rules() {
        return [
            'ticketPrioridad' => 'required',
            'ticketEstado' => 'required',
            'funcionario' => 'required',
            'ticketEstadoFinalizar' => 'required',
            'ticketEstadoArchivar' => 'required',
            'ticketEstadoRechazar' => 'required',
            'diasArchivar' => 'required|integer|max:5'
        ];  
    }
    
    public function messages(){
        return [
            'ticketPrioridad.required' => 'La prioridad del ticket es requerida',
            'ticketEstado.required' => 'El estado del ticket es requerido',
            'funcionario.required' => 'El funcionario es requerido',
            'ticketEstadoFinalizar.required' => 'El estado de finalización es requerido',
            'ticketEstadoArchivar.required' => 'El estado de archivar es requerido',
            'ticketEstadoRechazar.required' => 'El estado de rechazar es requerido',
            'diasArchivar.required' => 'Los días a archivar es requerido',
            'diasArchivar.max' => 'El máximo permitido son 5 caracteres'
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
