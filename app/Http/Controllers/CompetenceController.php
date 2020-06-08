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
            'message' => 'All your competeneces are here!!',
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
            'message' => 'Competence Created succesfully',
            'competence' => $competence
        ]);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
