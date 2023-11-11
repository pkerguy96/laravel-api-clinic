<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrdonanceRequest;
use App\Http\Resources\V1\OrdonanceCollection;
use App\Http\Resources\V1\OrdonanceResource;
use App\Models\Ordonance as ModelsOrdonance;
use App\Models\OrdonanceDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ordonance extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordonances = ModelsOrdonance::with('OrdonanceDetails', 'Patient')->orderBy('id', 'desc')->get();

        return new OrdonanceCollection($ordonances);
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
    public function store(OrdonanceRequest $request)
    {

        try {

            $medicineArray = $request->medicine;

            // Start a database transaction
            DB::beginTransaction();
            $user = Auth::user();

            // Create the Ordonance record
            $ordonance = ModelsOrdonance::create([
                'doctor_id' => $user->id,
                'patient_id' => $request->input('patient_id'),
                'date' => $request->input('date'),
            ]);
            // Validate and create OrdonanceDetails records

            foreach ($medicineArray as $medicine) {
                OrdonanceDetails::create([
                    'ordonance_id' => $ordonance->id,
                    'medicine_name' => $medicine['medicine_name'],
                    'note' => $medicine['note'],
                ]);
            }

            // Commit the transaction
            DB::commit();
            $data = new OrdonanceResource(ModelsOrdonance::with('OrdonanceDetails')->where('id', $ordonance->id)->first());
            // Return a response with the created Ordonance and OrdonanceDetails
            return response()->json([
                'message' => 'Ordonance created successfully',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Return an error response
            return response()->json(['message' => 'Error creating Ordonance'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new OrdonanceResource(ModelsOrdonance::with('OrdonanceDetails')->where('id', $id)->first());
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
        //
    }
}
