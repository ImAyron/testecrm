<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FormSubmissionController extends Controller
{
    public function index()
    {
        try {
            $submissions = FormSubmission::orderBy('created_at', 'desc')->paginate(10);
    
            return view('submissions.index', compact('submissions'));
    
        } catch (\Exception $e) {
            Log::error('Error fetching submissions: ' . $e->getMessage());
    
            return view('submissions.index')->with('error', 'Failed to retrieve submissions');
        }
    }
    

    public function store(Request $request)
    {
        // Validação mais completa
        $validator = Validator::make($request->all(), [
            'form_name' => 'required|string|max:255',
            'nome' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:100',
            'phone' => 'sometimes|string|max:20',
            // Adicione outros campos conforme necessário
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepara os dados para salvar
            $formData = [
                'form_name' => $request->input('form_name'),
                'form_data' => json_encode($request->except('_token')) // Remove token se existir
            ];

            // Cria o registro
            $submission = FormSubmission::create($formData);

            // Log para depuração
            Log::info('New form submission received:', $submission->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Form submission received successfully',
                'data' => $submission
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error processing form submission: ' . $e->getMessage());
            Log::error('Submission data: ' . json_encode($request->all()));
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing your submission',
                'error' => $e->getMessage() // Apenas para desenvolvimento
            ], 500);
        }
    }
}