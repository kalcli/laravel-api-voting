<?php

namespace App\Http\Controllers;

use App\Models\Candidates;
use App\Models\User;
use App\Models\Votes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VotingController extends Controller
{
    public function voting(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'candidateId' => 'required|exists:candidates,id',
            'userId' => 'required|exists:users,id',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => '422',
                'errors' => $validator->messages()
            ]);
        }

        $candidateId = $request->input('candidateId');
        $userId = $request->input('userId');

        $user = User::findOrFail($userId);
        if ($user->statusVote == 1) {
            return response()->json([
                'status' => '403',
                'message' => 'L\'utilisateur a déjà voté'
            ]);
        }


        $vote = new Votes();
        $vote->candidate_id = $candidateId;
        $vote->user_id = $userId;
        $vote->nbVotes = 1;
        $vote->save();





        // Mettre à jour la propriété statusVote de l'utilisateur
        $user->statusVote = 1;
        $user->save();


        return response()->json([
            'status' => '200',
            'message' => 'Vote enregistré avec succès'
        ]);
    }

    public function Results(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
        ]);

        $electionId = $request->input('election_id');

        // Récupérer les candidats et leurs nombres de votes associés à cette élection
        $candidates = Candidates::where('election_id', $electionId)
            ->leftJoin('votes', 'candidates.id', '=', 'votes.candidate_id')
            ->select('candidates.*', DB::raw('SUM(votes.nbvotes) as total_votes'))
            ->groupBy('candidates.id')
            ->get();

        return response()->json(['results' => $candidates]);
    }
}
