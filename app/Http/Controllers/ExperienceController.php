<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Experience;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        return $this->respondWithSuccess([
            'message' => 'List of experiences',
            'experiences' => $user->experiences,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:100',
            'societe' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        $experience = $user->experiences()->create($request->only(
            'label',
            'societe',
            'description',
            'date_debut',
            'date_fin'
        ));

        return $this->respondWithSuccess([
            'message' => 'Experience added succesfully!!',
            'experience' => $experience
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $experience = Experience::find($id);

        if ( $user->id != $experience->user_id)
            return $this->notAuthorized();
        
        return $this->respondWithSuccess([
            'message' => 'Your experience',
            'experience' => $experience
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $experience = Experience::find($id);
        if(! $experience)
            return $this->NotFound();
        
        if ( $user->id != $experience->user_id)
            return $this->notAuthorized();
        
        $validator = Validator::make($request->all(), [
            'label' => 'sometimes|string|max:100',
            'societe' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:255',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'sometimes|date|after:date_debut',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        $experience->update($request->only(
            'label',
            'societe',
            'description',
            'date_debut',
            'date_fin'
        ));

        return $this->respondWithSuccess([
            'message' => 'Experience added succesfully!!',
            'experience' => $experience
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $experience = Experience::find($id);
        if(! $experience)
            return $this->NotFound();
        
        if ( $user->id != $experience->user_id)
            return $this->notAuthorized();
        
        $experience->delete();

        return $this->respondWithSuccess([
            'message' => 'Destroyed succesfully'
        ]);
    }
}
