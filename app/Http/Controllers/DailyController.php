<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Daily;   
use Illuminate\Support\Facades\Validator;

class DailyController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Daily::count();
	        $daily = array();

	        foreach (Daily::all() as $p) {
	            $item = [
                    
                    "id_users"            => $p->id_users,
	                "team"                => $p->team,
	                "activity_yesterday"  => $p->activity_yesterday,
                    "activity_today"      => $p->activity_today,
                    "problem_yesterday"   => $p->problem_yesterday,
                    "solution"    	      => $p->solution,
	                "created_at"          => $p->created_at,
	                "updated_at"          => $p->updated_at
	            ];

	            array_push($daily, $item);
	        }
	        $data["daily"] = $daily;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function getAll($limit = 10, $offset = 0)
    {
    	try{
	        $data["count"] = Daily::count();
	        $daily = array();

	        foreach (Daily::take($limit)->skip($offset)->get() as $p) {
	            $item = [
                    
                    "id_users"            => $p->id_users,
	                "team"                => $p->team,
	                "activity_yesterday"  => $p->activity_yesterday,
                    "activity_today"      => $p->activity_today,
                    "problem_yesterday"   => $p->problem_yesterday,
                    "solution"    	      => $p->solution,
	                "created_at"          => $p->created_at,
	                "updated_at"          => $p->updated_at
	               
	            ];

	            array_push($daily, $item);
	        }
	        $data["daily"] = $daily;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function store(Request $request)
    {
      try{
    		$validator = Validator::make($request->all(), [
				
				'id_users'              => 'required|numeric',
				'team'			        => 'required|string|max:255',
                'activity_yesterday'	=> 'required|string|max:255',
                'activity_today'		=> 'required|string|max:255',
                'problem_yesterday'		=> 'required|string|max:255',
                'solution'			    => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

			$data = new Daily();
			
	        $data->id_users = $request->input('id_users');
	        $data->team = $request->input('team');
            $data->activity_yesterday = $request->input('activity_yesterday');
            $data->activity_today = $request->input('activity_today');
            $data->problem_yesterday = $request->input('problem_yesterday');
            $data->solution = $request->input('solution');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data daily scrum berhasil ditambahkan!'
    		], 201);

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
  	}


    public function update(Request $request, $id)
    {
      try {
      	$validator = Validator::make($request->all(), [
			
            'id_users'              => 'required|numeric',
            'team'			        => 'required|string|max:255',
            'activity_yesterday'	=> 'required|string|max:255',
            'activity_today'		=> 'required|string|max:255',
            'problem_yesterday'		=> 'required|string|max:255',
            'solution'		
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data = Daily::where('id', $id)->first();
          $data->id_users = $request->input('id_users');
          $data->team = $request->input('team');
          $data->activity_yesterday = $request->input('activity_yesterday');
          $data->activity_today = $request->input('activity_today');
          $data->problem_yesterday = $request->input('problem_yesterday');
          $data->solution = $request->input('solution');
          $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data daily scrum berhasil diubah'
      	]);
        
      } catch(\Exception $e){
          return response()->json([
              'status' => '0',
              'message' => $e->getMessage()
          ]);
      }
    }

    public function delete($id)
    {
        try{

            $delete = Daily::where("id", $id)->delete();

            if($delete){
              return response([
              	"status"	=> 1,
                  "message"   => "Data daily scrum berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data daily scrum gagal dihapus."
              ]);
            }
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }

}
