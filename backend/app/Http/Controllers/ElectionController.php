<?php

namespace App\Http\Controllers;

use App\Models\Elections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $elections = Elections::all();
        return response()->json([
            'status' => 200,
            'elections' => $elections
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
            'start' => 'required|date_format:Y-m-d\TH:i',
            'end' => 'required|date_format:Y-m-d\TH:i|after:start',

        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => '422',
                'errors' => $validator->messages()
            ]);
        }

        $filename = '';


        if($request->hasFile('img'))
        {
            $filename = Str::random(32).".".$request->img->getClientOriginalExtension();

        }

        $election = Elections::create([
            'electionPicture' => $filename,
            'electionName' => $request->name,
            'electionDescription' => $request->description,
            'startDate' => $request->start,
            'endDate' => $request->end,
        ]);

        Storage::disk('public')->put($filename,file_get_contents($request->img));


        if($election)
        {
            return response()->json([
                'status' => '200',
                'message' => 'Election stored successfully'
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
    public function show(string $id)
    {
        $election = Elections::find($id);
        if(!$election)
        {
            return response()->json([
                'status' => '404',
                'message' => 'Election not found'
            ]);
        }

        return response()->json([
            'status' => '200',
            'election' => $election
        ]);
    }

    public function edit($id)
    {
        $election = Elections::find($id);
    if ($election) {
        return response()->json([
            'status' => '200',
            'election' => $election
        ]);
    }
    else{

        return response()->json([
            'status' => '404',
            'message' => 'Not Such Election Found!'
        ]);

    }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validator = Validator::make($request->all(),
        [
            'img' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'start' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'required|date_format:Y-m-d H:i:s|after:start',

        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => '422',
                'errors' => $validator->messages()
            ]);
        }

        $filename = '';


        if($request->hasFile('img'))
        {
            $filename = $request->getSchemeAndHttpHost() . '/assets/images' . time() . '.' . $request->img->extension();
            $request->img->move(public_path('/assets/images'),$filename);
        }

        $election = Elections::find($id);

        if($election)
        {
            $election->update([
                'electionPicture' => $filename,
                'electionName' => $request->name,
                'electionDescription' => $request->description,
                'startDate' => $request->start,
                'endDate' => $request->end,
            ]);

            return response()->json([
                'status' => '200',
                'message' => 'Election updated successfully'
            ]);
        }else{
            return response()->json([
                'status' => '404',
                'message' => 'No Such Election Found!'
            ]);
        }



}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $election = Elections::find($id);
        if(!$election)
        {
            return response()->json([
                'status' => '404',
                'message' => 'Election not found'
            ]);
        }

        $election->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Election deleted successfully'
        ]);
    }
}
