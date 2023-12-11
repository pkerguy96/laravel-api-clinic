<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OperationDetailRequest;
use App\Http\Requests\V1\OperationRequest;
use App\Http\Requests\V1\PayementRequest;
use App\Http\Resources\V1\OperationCollection;
use App\Models\Operation;
use App\Models\OperationDetail;
use App\Models\Payement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operations = Operation::with('payments')->orderBy('id', 'desc')->get();
        return new OperationCollection($operations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->all();
        //TODO: refactor this
        try {
            $validator = validator($request->all(), [
                'is_paid' => 'required|boolean',
                'note' => 'nullable|string',
                'patient_id' => 'required|integer|exists:patients,id',
                'amount_paid' => 'nullable|numeric|min:0.01',
                'operations' => 'required|array',
                'operations.*.operation_type' => 'required|numeric',
                'operations.*.price' => 'required|numeric|between:0,9999999.99',
                'operations.*.amount_paid' => 'nullable|numeric|between:0,9999999.99',
                'operations.*.tooth_id' => 'required|array',
            ], [
                'is_paid.required' => 'Le champ "is_paid" est requis.',
                'is_paid.boolean' => 'Le champ "is_paid" doit être un booléen.',

                'note.string' => 'Le champ "note" doit être une chaîne de caractères.',

                'patient_id.required' => 'Le champ "patient_id" est requis.',
                'patient_id.integer' => 'Le champ "patient_id" doit être un entier.',
                'patient_id.exists' => 'Le patient sélectionné n\'existe pas.',

                'amount_paid.numeric' => 'Le champ "amount_paid" doit être un nombre.',
                'amount_paid.min' => 'Le champ "amount_paid" doit être d\'au moins :min.',

                'operations.required' => 'Le champ "operations" est requis.',
                'operations.array' => 'Le champ "operations" doit être un tableau.',

                'operations.*.operation_type.required' => 'Le champ "operation_type" est requis.',
                'operations.*.operation_type.numeric' => 'Le champ "operation_type" doit être un nombre.',

                'operations.*.price.required' => 'Le champ "price" est requis.',
                'operations.*.price.numeric' => 'Le champ "price" doit être un nombre.',
                'operations.*.price.between' => 'Le champ "price" doit être compris entre :min et :max.',

                'operations.*.amount_paid.numeric' => 'Le champ "amount_paid" doit être un nombre.',
                'operations.*.amount_paid.between' => 'Le champ "amount_paid" doit être compris entre :min et :max.',

                'operations.*.tooth_id.required' => 'Le champ "tooth_id" est requis.',
                'operations.*.tooth_id.array' => 'Le champ "tooth_id" doit être un tableau.',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }



            $user = Auth::user();
            $data = $request->json()->all();
            $calculator = 0;
            foreach ($data['operations'] as $item) {
                $calculator += $item['price'];
            }
            DB::beginTransaction();
            $operation = Operation::create([
                'doctor_id' => $user->id,
                'patient_id' => $data['patient_id'],
                'total_cost' => $calculator,
                'note' =>  $data['note'],
            ]);
            foreach ($data['operations'] as $item) {
                $operationDetail = OperationDetail::create([
                    'operation_id' =>  $operation->id,
                    'tooth_id' => implode(',', $item['tooth_id']),
                    'operation_type' => $item['operation_type'],
                    'price' => $item['price'],
                ]);
            }

            $payment = Payement::create([
                'operation_id' =>  $operation->id,
                'total_cost' => $calculator,
                'amount_paid' => $data['is_paid'] ? $calculator : $data['amount_paid'],
                'is_paid' => $data['is_paid'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'operation created successfully',

            ], 201);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'message' => 'Oops something went wrong',
                'errors' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Operation::findorfail($id)->delete();
        return response()->json(['message' => 'Operation deleted successfully']);
    }
}
