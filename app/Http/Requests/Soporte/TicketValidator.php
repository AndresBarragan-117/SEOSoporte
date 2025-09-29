<?php

namespace App\Http\Requests\Soporte;
use Illuminate\Foundation\Http\FormRequest;

class TicketValidator extends FormRequest{
    protected $redirect = "/ticket";
    
    public function rules() {
        return [
            'anexo1' =>  'mimetypes:image/jpeg,image/gif,png,image/png,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/msword|max:5000',
            'anexo2' =>  'mimetypes:image/jpeg,image/gif,png,image/png,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/msword|max:5000',
            'anexo3' =>  'mimetypes:image/jpeg,image/gif,png,image/png,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/msword|max:5000',
            'categoria' => 'required',
        ];  
    }
    
    public function messages(){
        return [
            'anexo1.mimetypes' => 'Se permite solo archivos con extensión jpeg, jpg, png, gif, pdf, xls, doc, docx',
            'anexo1.max' => 'Tamaño máximo 5 mb',
            'anexo2.mimetypes' => 'Se permite solo archivos con extensión jpeg, jpg, png, gif, pdf, xls, doc, docx',
            'anexo2.max' => 'Tamaño máximo 5 mb',
            'anexo3.mimetypes' => 'Se permite solo archivos con extensión jpeg, jpg, png, gif, pdf, xls, doc, docx',
            'anexo3.max' => 'Tamaño máximo 5 mb',
            'categoria.required' => 'Seleccione la categoría.',
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
