<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\PersonalInfo;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;


class PersonalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        // $pers_info = PersonalInfo::create([
        //     'nom' => $request->input('nom'),
        //     'prenom' => $request->input('prenom'),
        //     'email' => $request->input('email'),
        //     'phone' => $request->input('phone'),
        //     'adresse' => $request->input('adresse'),
        //     'propos' => $request->input('propos'),
        //     'code_postal' => $request->input('code_postal'),
        //     'linkedin' => $request->input('linkedin'),
        //     'github' => $request->input('github'),
        //     'facebook' => $request->input('facebook'),
        //     'portfolio' => $request->input('portfolio'),
        //     'user_id' => $user->id
        // ]);

        if ($image = $request->file('profil_picture')) {
            $pers_info->saveImage($image);
        }

        return $this->respondWithSuccess([
            'message' => 'Personal infos saved succesfully',
            'personal_infos' => $pers_info
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
