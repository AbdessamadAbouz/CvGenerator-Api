<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Competence;
use App\CompetenceType;
use Illuminate\Support\Facades\Validator;

class CompetenceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $user->competences->append('competence_type');

        return $this->respondWithSuccess([
            'message' => 'All your skills are here!!',
            'competences' => $user->competences
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();
        
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:50',
            'competence_type' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        $type = CompetenceType::where('label',$request->input('competence_type'))->first();
        if(! $type)
            $type = CompetenceType::create([
                'label' => $request->input('competence_type')
            ]);
        
        $competence = $user->competences()->create([
            'label' => $request->input('label'),
            'competence_type_id' => $type->id
        ]);

        return $this->respondWithSuccess([
            'message' => 'Skill Created succesfully',
            'competence' => $competence
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();
        
        $competence = Competence::find($id);
        if(! $competence)
            return $this->NotFound();

        if($competence->competence_users->user_id != $user->id)
            return $this->notAuthorized();
        
        $competence->append('competence_type');
        $competence->append('user');        
        return $this->respondWithSuccess([
            'message' => 'Your skill is here',
            'competence' => $competence
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();
        
        $competence = Competence::find($id);
        if(! $competence)
            return $this->NotFound();

        if($competence->competence_users->user_id != $user->id)
            return $this->notAuthorized();
            
        $validator = Validator::make($request->all(), [
            'label' => 'sometimes|string|max:50',
            'competence_type' => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        if($request->input('competence_type'))
        {
            $type = CompetenceType::where('label',$request->input('competence_type'))->first();
            if(! $type)
                $type = CompetenceType::create([
                    'label' => $request->input('competence_type')
                ]);
        }

        $competence->update([
            'label' => $request->input('label'),
            'competence_type_id' => $type->id
        ]);

        return $this->respondWithSuccess([
            'message' => 'Skill updated succesfully',
            'competence' => $competence
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();
        
        $competence = Competence::find($id);
        if(! $competence)
            return $this->NotFound();

        if($competence->competence_users->user_id != $user->id)
            return $this->notAuthorized();
        
        $competence->delete();

        return $this->respondWithSuccess([
            'message' => 'Skill deleted succesfully!!!'
        ]);
    }
}
