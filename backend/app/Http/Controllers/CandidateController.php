<?php

namespace App\Http\Controllers;

use App\Models\Candidates;
use App\Models\Elections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = Candidates::with('election')->get();
        $formattedCandidates = $candidates->map(function ($candidate) {
            return [
                'id' => $candidate->id,
                'candidatePicture' => $candidate->candidatePicture,
                'candidateName' => $candidate->candidateName,
                'description' => $candidate->description,
                'election_id' => $candidate->election_id,
                'election_name' => $candidate->election->electionName,
            ];
        });

        return response()->json([
            'status' => '200',
            'candidates' => $formattedCandidates,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'img' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'election_name' => 'required|string|max:50'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => '422',
                'errors' => $validator->messages()
            ]);
        }

        $election = Elections::where('electionName',$request->election_name)->first();

        if (!$election) {
            return response()->json([
                'status' => '404',
                'error' => 'Election not found.',
            ]);
        }
        $electionId = $election->id;

        $filename = '';


        if($request->hasFile('img'))
        {
            $filename = Str::random(32).".".$request->img->getClientOriginalExtension();

        }

        $candidate = Candidates::create([
            'candidatePicture' => $filename,
            'candidateName' => $request->name,
            'description' => $request->description,
            'election_id' =>$electionId
        ]);

        Storage::disk('public')->put($filename,file_get_contents($request->img));

        if($candidate)
        {
            return response()->json([
                'status' => '200',
                'message' => 'Candidate stored successfully'
            ]);
        }else{
            return response()->json([
                'status' => '500',
                'message' => 'Something Went Wrong'
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $candidate = Candidates::with('election')->find($id);

    if(!$candidate) {
        return response()->json([
            'status' => '404',
            'message' => 'No Such Candidate Found!'
        ]);
    }

    return response()->json([
        'status' => '200',
        'candidate' => [
            'id' => $candidate->id,
            'candidatePicture' => $candidate->candidatePicture,
            'candidateName' => $candidate->candidateName,
            'description' => $candidate->description,
            'election_id' => $candidate->election_id,
            'election_name' => $candidate->election->electionName,
        ],
    ]);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $candidate = Candidates::with('election')->find($id);

    if(!$candidate) {
        return response()->json([
            'status' => '404',
            'message' => 'No Such Candidate Found!'
        ]);
    }

    return response()->json([
        'status' => '200',
        'candidate' => [
            'id' => $candidate->id,
            'candidatePicture' => $candidate->candidatePicture,
            'candidateName' => $candidate->candidateName,
            'description' => $candidate->description,
            'election_id' => $candidate->election_id,
            'election_name' => $candidate->election->electionName,
        ],
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),
        [
            'img' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'election_name' => 'required|string|max:75'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => '422',
                'errors' => $validator->messages()
            ]);
        }

        $election = Elections::where('electionName',$request->election_name)->first();

        if (!$election) {
            return response()->json([
                'status' => '404',
                'error' => 'Election not found.',
            ]);
        }
        $electionId = $election->id;

        $filename = '';


        if($request->hasFile('img'))
        {
            $filename = Str::random(32).".".$request->img->getClientOriginalExtension();

        }

        $candidate = Candidates::find($id);

        if($candidate)
        {
            $candidate->update([
                'candidatePicture' => $filename,
                'candidateName' => $request->name,
                'description' => $request->description,
                'election_id' =>$electionId
            ]);
            return response()->json([
                'status' => '200',
                'message' => 'Candidate updated successfully'
            ]);
        }else{
            return response()->json([
                'status' => '404',
                'message' => 'No Such Candidate Found!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $candidate = Candidates::find($id);
        if(!$candidate)
        {
            return response()->json([
                'status' => '404',
                'message' => 'Candidate not found'
            ]);
        }

        $candidate->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Candidate deleted successfully'
        ]);
    }
}
