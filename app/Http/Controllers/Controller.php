<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $status_code = 400;

    // Getter and Setter for the status code
    //
    public function setStatusCode($status_code) {
        $this->status_code = $status_code;
        return $this;
    }

    public function getStatusCode() {
        return $this->status_code;
    }


    // Generating respond in the followed structure
    //
    public function respond($data) {
        return Response::json($data, $this->getStatusCode());
    }

    public function respondWithError($message, $error='')
	{
		return $this->respond([
			'error' => [
				'message'           => $message,
				'status_code'       => $this->getStatusCode(),
				'errors_validation' => $error
			]
		]);
	}


    public function notAuthorized($message = 'You have no rights!!') {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    public function notFound($message = 'Item Not found!!') {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    public function respondWithSuccess(array $data) {
        return $this->setStatusCode(200)->respond($data);
    }

    public function ValidationError($error) {
        return $this->setStatusCode(422)->respondWithError('Validation Error over the fields' , $error);
    }


    
}
