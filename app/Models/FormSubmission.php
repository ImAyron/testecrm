<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'form_name', 
        'form_data'
        // Adicione outros campos que deseja permitir
    ];
    
    protected $casts = [
        'form_data' => 'array'
    ];
}