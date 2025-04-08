<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class LeadController extends Controller
{
    /**
     * Display a listing of the leads for admin panel.
     */
    public function index(Request $request)
    {
        try {
            $query = FormSubmission::query()->orderBy('created_at', 'desc');

            // Filtro de busca
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where(function($q) use ($searchTerm) {
                    $q->where('form_name', 'like', "%{$searchTerm}%")
                      ->orWhere('form_data', 'like', "%{$searchTerm}%");
                });
            }

            // Filtro por data
            if ($request->has('date_filter')) {
                $dateFilter = Carbon::parse($request->input('date_filter'))->format('Y-m-d');
                $query->whereDate('created_at', $dateFilter);
            }

            $submissions = $query->paginate(15);

            return view('admin.leads.index', compact('submissions'));

        } catch (\Exception $e) {
            Log::error('Erro ao listar leads: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar lista de leads');
        }
    }

    /**
     * Display the specified lead details.
     */
    public function show($id)
    {
        try {
            $submission = FormSubmission::findOrFail($id);
            $formData = json_decode($submission->form_data, true);

            return response()->json([
                'success' => true,
                'data' => [
                    'submission' => $submission,
                    'form_data' => $formData,
                    'created_at_formatted' => $submission->created_at->format('d/m/Y H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lead não encontrado'
            ], 404);
        }
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy($id)
    {
        try {
            $submission = FormSubmission::findOrFail($id);
            $submission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lead removido com sucesso'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao deletar lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover lead'
            ], 500);
        }
    }

    /**
     * Export leads to CSV.
     */
    public function export()
    {
        try {
            $submissions = FormSubmission::all();
            $fileName = 'leads_export_' . date('Y-m-d_His') . '.csv';

            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use ($submissions) {
                $file = fopen('php://output', 'w');
                
                // Cabeçalho do CSV
                fputcsv($file, [
                    'ID',
                    'Nome',
                    'Email',
                    'Telefone',
                    'Formulário',
                    'UTM Source',
                    'UTM Medium',
                    'UTM Campaign',
                    'Data de Criação'
                ]);

                // Dados
                foreach ($submissions as $submission) {
                    $data = json_decode($submission->form_data, true);
                    fputcsv($file, [
                        $submission->id,
                        $data['name'] ?? '',
                        $data['email'] ?? '',
                        $data['phone'] ?? '',
                        $submission->form_name,
                        $data['utm_source'] ?? '',
                        $data['utm_medium'] ?? '',
                        $data['utm_campaign'] ?? '',
                        $submission->created_at->format('d/m/Y H:i:s')
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Erro ao exportar leads: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar arquivo de exportação');
        }
    }

    /**
     * API endpoint to store form submissions.
     */
    public function store(Request $request)
    {
        try {
            $formData = [
                'form_name' => $request->input('form_name', 'formulario_padrao'),
                'form_data' => json_encode($request->except(['_token', 'form_name']))
            ];

            $submission = FormSubmission::create($formData);

            Log::channel('leads')->info('Novo lead recebido:', $submission->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Lead recebido com sucesso',
                'data' => $submission
            ], 201);

        } catch (\Exception $e) {
            Log::channel('leads')->error('Erro ao processar lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar o lead'
            ], 500);
        }
    }
}