<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Langue;
use Illuminate\Support\Facades\Validator;

class LangueController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        return $this->respondWithSuccess([
            'message' => 'List of your languages!!',
            'langues' => $user->langues
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:20',
            'level' => 'required|string|in:beginner,intermediate,advanced',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        $langue = $user->langues()->create($request->only(
            'label',
            'level'
        ));

        return $this->respondWithSuccess([
            'message' => 'language added succesfully',
            'langue' => $langue
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $langue = Langue::find($id);
        if(! $langue)
            return $this->notFound();
        
        if($langue->user_id != $user->id)
            return $this->notAuthorized();

        return $this->respondWithSuccess([
            'message' => 'language selected',
            'langue' => $langue
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $langue = Langue::find($id);
        if(! $langue)
            return $this->notFound();
        
        if($langue->user_id != $user->id)
            return $this->notAuthorized();

        $validator = Validator::make($request->all(), [
            'label' => 'sometimes|string|max:20',
            'level' => 'sometimes|string|in:beginner,intermediate,advanced',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        $langue->update($request->only(
            'label',
            'level'
        ));

        return $this->respondWithSuccess([
            'message' => 'language updated succesfully',
            'langue' => $langue
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if(! $user)
            return $this->notAuthorized();

        $langue = Langue::find($id);
        if(! $langue)
            return $this->notFound();
        
        if($langue->user_id != $user->id)
            return $this->notAuthorized();

        $langue->delete();

        return $this->respondWithSuccess([
            'message' => 'Language has been deleted!!'
        ]);
    }
}
