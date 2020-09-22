<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Competence;
use App\Experience;
use App\Formation;
use App\Langue;
use App\Cv;
use App\PersonalInfo;
use PDF;

class CvController extends Controller
{
    public function __construct() {
        $this->middleware('cors',['except' => ['store','index']]);
    }

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

    public function index() {
        $user = auth()->user();
        if(! $user) 
        {
            return $this->notAuthorized();
        }

        $cvs = Cv::where('user_id',$user->id)->get();

        return $this->respondWithSuccess([
            'Message' => 'Here are your resumes',
            'resumes' => $cvs
        ]);
    }

    public function createPDF($id) {
        $user = auth()->user();
        if(! $user) 
        {
            return $this->notAuthorized();
        }

        $cv = Cv::find($id);
        if(!$cv) {
            return $this->notFound();
        }

        $pers_infos = PersonalInfo::find($cv->personal_info_id);
        if(!$pers_infos) {
            return $this->notFound();
        }

        //Prepare skills for next use
        $competence = ltrim($cv->competence_ids,'[');
        $competence = rtrim($competence,']');
        $competence = explode(',',$competence);

        $skills = Competence::whereIn('id',$competence)->get();
        if(!$skills) {
            return $this->notFound();
        }
        
        //Prepare experiences for next use
        $experience = ltrim($cv->experience_ids,'[');
        $experience = rtrim($experience,']');
        $experience = explode(',',$experience);
        
        $experiences = Experience::whereIn('id',$experience)->get();
        if(!$experiences) {
            return $this->notFound();
        }

        //Prepare formations for next use
        $formation = ltrim($cv->formation_ids,'[');
        $formation = rtrim($formation,']');
        $formation = explode(',',$formation);
        
        $formations = Formation::whereIn('id',$formation)->get();
        if(!$formations) {
            return $this->notFound();
        }

        //Prepare langues for next use
        $langue = ltrim($cv->langue_ids,'[');
        $langue = rtrim($langue,']');
        $langue = explode(',',$langue);
        
        $langues = Langue::whereIn('id',$langue)->get();
        if(!$langues) {
            return $this->notFound();
        }

        view()->share('langues',$langues);
        view()->share('formations',$formations);
        view()->share('experiences',$experiences);
        view()->share('skills',$skills);
        view()->share('personal_infos',$pers_infos);
        view()->share('resume',$cv);
        $pdf = PDF::loadView('pdf_view', $cv);
        
        // return $cv;
        // return $pdf->stream();
        return $pdf->download('resume.pdf');
    }
}
