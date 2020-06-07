<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Formation;
use Illuminate\Support\Facades\Validator;

class FormationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();
        
        return $this->respondWithSuccess([
            'message' => 'List of your formations',
            'formations' => $user->formations
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:100',
            'titre' => 'required|string|max:100',
            'nom_ecole' => 'required|string|max:100',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        $formation = $user->formations()->create($request->only(
            'label',
            'titre',
            'nom_ecole',
            'date_debut',
            'date_fin',
        ));

        return $this->respondWithSuccess([
            'message' => 'Formation has been saved succesfully',
            'personal_infos' => $formation
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $formation = Formation::find($id);
        if(! $formation)
            return $this->NotFound();

        if($formation->user_id != $user->id)
            return $this->notAuthorized();

        return $this->respondWithSuccess([
            'message' => 'Your formation is here',
            'formation' => $formation
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $formation = Formation::find($id);
        if(! $formation)
            return $this->NotFound();
        
        if($formation->user_id != $user->id)
            return $this->notAuthorized();

        $validator = Validator::make($request->all(), [
            'label' => 'sometimes|string|max:100',
            'titre' => 'sometimes|string|max:100',
            'nom_ecole' => 'sometimes|string|max:100',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'sometimes|date|after:date_debut',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        $formation->update($request->only(
            'label',
            'titre',
            'nom_ecole',
            'date_debut',
            'date_fin',
        ));

        return $this->respondWithSuccess([
            'message' => 'Formation has been updated succesfully',
            'personal_infos' => $formation
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $formation = Formation::find($id);
        if(! $formation)
            return $this->NotFound();
        
        if($formation->user_id != $user->id)
            return $this->notAuthorized();

        $formation->delete();

        return $this->respondWithSuccess([
            'message' => 'Formation deleted succesfully'
        ]);
    }
}
