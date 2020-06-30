<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Competence;
use App\Experience;
use App\Formation;
use App\Langue;

class CvController extends Controller
{
    public function store(Request $request) {
        $user = auth()->user();
        if(! $user) 
        {
            return $this->notAuthorized();
        }

        $validator = Validator::make($request->all(), [
        'personal_info_id' => 'required|integer',
            'competence_ids' => 'required|array',
            'experience_ids' => 'required|array',
            'formation_ids' => 'required|array',
            'langue_ids' => 'required|array',
        ]);

        

        if($validator->fails())
        {
            return $this->ValidationError($validator->errors());
        }

        //Check on skills ids if exists and belongs to the user
        //
        $competence_ids = collect($request->input('competence_ids'));
        $i = 0;
		$skills_are_valid = $competence_ids->reduce(function ($truth, $skill_id) use ($i,$user) {
            $skill = Competence::find($skill_id);
            if(! $skill) {
                dd('test');
                return false;
            }

            return $truth && $skill->competence_users->user_id == $user->id ;
        }, true);
        if( !$skills_are_valid)
        {
            return $this->NotFound('You don\'t have the right, Check your skills.');
        }

        //Check on experiences ids if exists and belongs to the user
        //
        $experience_ids = collect($request->input('experience_ids'));
		$experiences_are_valid = $experience_ids->reduce(function ($truth, $experience_id) use ($user) {
            $experience = Experience::find($experience_id);
            if(! $experience) {
                return false;
            }
            return $truth && $experience->user_id == $user->id ;
        }, true);
        
        if(! $experiences_are_valid)
        {
            return $this->NotFound('You don\'t have the right, check your experiences');
        }

        //Check on formations ids if exists and belongs to the user
        //
        $formation_ids = collect($request->input('formation_ids'));
		$formations_are_valid = $formation_ids->reduce(function ($truth, $formation_id) use ($user) {
            $formation = Formation::find($formation_id);
            if(! $formation) {
                return false;
            }
            return $truth && $formation->user_id == $user->id ;
        }, true);
        
        if(! $formations_are_valid)
        {
            return $this->NotFound('You don\'t have the right, check your formations');
        }

        //Check on languages ids if exists and belongs to the user
        //
        $langue_ids = collect($request->input('langue_ids'));
		$langues_are_valid = $langue_ids->reduce(function ($truth, $langue_id) use ($user) {
            $langue = Langue::find($langue_id);
            if(! $langue) {
                return false;
            }
            return $truth && $langue->user_id == $user->id ;
        }, true);
        
        if(! $langues_are_valid)
        {
            return $this->NotFound('You don\'t have the right, check your languages');
        }

        $cv = $user->cvs()->create([
            'personal_info_id' => $request->input('personal_info_id'),
            'competence_ids' => $competence_ids,
            'formation_ids' => $formation_ids,
            'experience_ids' => $experience_ids,
            'langue_ids' => $langue_ids,
        ]);

        return $this->respondWithSuccess([
            'message' => 'Resume generated',
            'resume' => $cv
        ]);
    }
}
