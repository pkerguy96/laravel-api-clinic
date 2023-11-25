<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Operation;
use App\Models\Payement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        try {
            $user = Auth::user();
            DB::beginTransaction();
            $operation = Operation::create([
                'doctor_id' => $user->id,
                'patient_id' => $request->input('patient_id'),
                'tooth_id' => $request->input('tooth_id'),
                'operation_type' => $request->input('operation_type'),
                'note' => $request->input('note'),


            ]);
            Payement::create([
                'operation_id' => $operation->id,
                'total_cost' => $request->input('total_cost'),
                'amount_paid' => $request->input('amount_paid'),
                'is_paid' => $request->input('is_paid'),

            ]);
            DB::commit();
            return response()->json([
                'message' => 'operation created successfully',

            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'message' => 'chi blan hada',
                'msg' => $th
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
