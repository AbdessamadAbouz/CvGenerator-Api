<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PersonalInfo;
use Illuminate\Support\Facades\Validator;


class PersonalInfoController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user)
            return $this->notAuthorized();

        return $this->respondWithSuccess([
            'message' => 'Personal Infos for you',
            'personal_infos' => $user->personal_infos,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user)
            return $this->notAuthorized();

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|string',
            'adresse' => 'required|string|max:255',
            'propos' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'linkedin' => 'nullable|string',
            'github' => 'nullable|string',
            'facebook' => 'nullable|string',
            'portfolio' => 'nullable|string',
            'profil_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }
        
        $pers_info = $user->personal_infos()->create($request->only(
            'nom',
            'prenom',
            'email',
            'phone',
            'adresse',
            'propos',
            'code_postal',
            'linkedin',
            'github',
            'facebook',
            'portfolio'
        ));

        if ($image = $request->file('profil_picture')) {
            $pers_info->saveImage($image);
        }

        return $this->respondWithSuccess([
            'message' => 'Personal infos saved succesfully',
            'personal_infos' => $pers_info
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if (!$user)
            return $this->notAuthorized();

        $personal_info = PersonalInfo::find($id);
        if (!$personal_info)
            return $this->notFound();

        return $this->respondWithSuccess([
            'Personal_infos' => $personal_info
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user)
            return $this->notAuthorized();

        $pers_info = PersonalInfo::find($id);
        if(! $pers_info)
            return $this->NotFound();
        
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:50',
            'prenom' => 'sometimes|string|max:50',
            'email' => 'sometimes|email',
            'phone' => 'sometimes|string',
            'adresse' => 'sometimes|string|max:255',
            'propos' => 'sometimes|string|max:255',
            'code_postal' => 'sometimes|string|max:10',
            'linkedin' => 'nullable|string',
            'github' => 'nullable|string',
            'facebook' => 'nullable|string',
            'portfolio' => 'nullable|string',
            'profil_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        if ($validator->fails()) {
            return $this->ValidationError($validator->errors());
        }

        $pers_info->update($request->only(
            'nom',
            'prenom',
            'email',
            'phone',
            'adresse',
            'propos',
            'code_postal',
            'linkedin',
            'github',
            'facebook',
            'portfolio'
        ));

        if ($image = $request->file('profil_picture')) {
            $pers_info->saveImage($image);
        }

        return $this->respondWithSuccess([
            'message' => 'Personal infos saved succesfully',
            'personal_infos' => $pers_info
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user)
            return $this->notAuthorized();

        $pers_info = PersonalInfo::find($id);
        if(! $pers_info)
            return $this->NotFound();
        
        $pers_info->delete();

        return $this->respondWithSuccess([
            'message' => 'Element destroyed'
        ]);
    }
}
