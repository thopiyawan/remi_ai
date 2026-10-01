<?php

namespace App\Http\Controllers;

use App\Models\pregnants as pregnants;
use App\Models\RecordOfPregnancy as RecordOfPregnancy;
use App\Models\sequents as sequents;
use App\Models\sequentsteps as sequentsteps;
use App\Models\users_register as users_register;
use App\Models\tracker as tracker;
use App\Models\question as question;
use App\Models\quizstep as quizstep;
use App\Models\reward as reward;
use App\Models\doctor as doctor;
use App\Models\personal_doctor_mom as personal_doctor_mom;
use App\Models\logmessage as logmessage;
use App\Models\blood_sugar as blood_sugar;
use App\Models\fetal_movement as fetal_movement;
use App\Models\tracker_activity as tracker_activity;

use App\Http\Controllers\checkmessageController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SqlController;
use App\Http\Controllers\diaryController;
use Auth;
use Hash;
use Session;
use Illuminate\Support\Facades\Redirect;

use Image; 
use Carbon\Carbon;
use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
class ApiController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


  public function addChild_api($token,$user) {
                      
    $users_register = (new SqlController)->users_register_select($user);
    $weight = $users_register->user_Pre_weight;
    $height = $users_register->user_height;
    $men = $users_register->date_preg;
    $addChild_api = array( 'access_token'=> $token,
                           'last_menstruation'=>  $men,
                           'time'=> '1',
                           'weight'=> $weight,
                           'height'=> $height
                          );
                             
    $addChild_json = json_encode($addChild_api);  
    $url ='http://128.199.147.57/api/v1/peat/addChild';
    $ch = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $addChild_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch1,CURLOPT_URL, $url2);
    // curl_setopt($ch1, CURLOPT_POST, 1);
    // curl_setopt($ch1,CURLOPT_POSTFIELDS, $graph_json);
    // curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;
  }

  public function tracker_api($key,$user) {
    $tracker = tracker::where('user_id',$user)
                      ->whereNull('deleted_at')
                      ->where('data_to_ulife','0')
                      ->count();
    if($tracker>=1) {
      $tracker = tracker::where('user_id',$user)
                        ->whereNull('deleted_at')
                        ->where('data_to_ulife','0')
                        ->get();
                      
        foreach ( $tracker as $track) {
          $cre= $track->created_at;
          $up= $track->updated_at;
          $created_at = $cre->format('Y-m-d H:m:s');
          $updated_at =  $up->format('Y-m-d H:m:s');
                                               
            $tracker_api[] = array( 'id'=>$track->id,
                                    'user_key'=> $key,
                                    'breakfast'=>$track->breakfast,
                                    'lunch'=>$track->lunch,
                                    'dinner'=>$track->dinner,
                                    'dessert_lu'=>$track->dessert_lu,
                                    'dessert_din'=>$track->dessert_din,
                                    'exercise'=>$track->exercise,
                                    'vitamin'=>$track->vitamin,
                                    'created_at'=>$created_at,
                                    'updated_at'=>$updated_at,
                                    'deleted_at'=>$track->deleted_at
                                  );                                              
        }
                             
        $tracker_json = json_encode($tracker_api);  
        $url3 ='http://128.199.147.57/api/v1/peat/setTrackers';
        $ch3 = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch3,CURLOPT_URL, $url3);
                               
        curl_setopt($ch3, CURLOPT_POST, 1);
        curl_setopt($ch3,CURLOPT_POSTFIELDS, $tracker_json);
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch1,CURLOPT_URL, $url2);
        // curl_setopt($ch1, CURLOPT_POST, 1);
        // curl_setopt($ch1,CURLOPT_POSTFIELDS, $graph_json);
        // curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch3);
        //close connection
        curl_close($ch3);

        $tracker = NOW();
        $tracker_update = tracker::where('user_id', $user)
                                 ->whereNull('deleted_at')
                                 ->update([ 'data_to_ulife' =>$tracker]);
        return $result;
    }
           
  }
  public function setgraph_api($key,$user) {
                  
      $RecordOfPregnancy = RecordOfPregnancy::where('user_id',$user)
                                            ->whereNull('deleted_at')
                                            ->where('data_to_ulife','0')
                                            ->count();
                           
        if($RecordOfPregnancy>=1) {                  
          $RecordOfPregnancy = RecordOfPregnancy::where('user_id',$user)
                                                ->whereNull('deleted_at')
                                                ->where('data_to_ulife','0')
                                                ->get();
          $weight = [];
          $time = [];
            foreach ($RecordOfPregnancy as $object) {
              array_push($weight, $object->preg_weight);
              //$weight[] = $object->preg_weight;   
              array_push($time, $object->preg_week);    
              //$time []= $object->preg_week;     
              $data_graph = array( 'user_key'=> $key,
                                   'OFFSPRING'=>1,
                                   'GRAPH_WEIGHT'=>$weight,
                                   'GRAPH_TIME'=> $time,
                                   'deleted_at'=>NULL
                                 );                                 
            }
                        
            $graph_json = json_encode($data_graph);  
                                       
            $url2 ='http://128.199.147.57/api/v1/peat/setGraphWeight';
            $ch2 = curl_init();
            //set the url, number of POST vars, POST data
            curl_setopt($ch2,CURLOPT_URL, $url2);
            curl_setopt( $ch2, CURLOPT_POST, true );
            curl_setopt($ch2,CURLOPT_POSTFIELDS,  $graph_json);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            //execute post
            $result = curl_exec($ch2);
            //close connection
            curl_close($ch2);
            $tracker = NOW();
            $tracker_update = RecordOfPregnancy::where('user_id', $user)
                                               ->whereNull('deleted_at')
                                               ->update([ 'data_to_ulife' =>$tracker]);
            return $result;
        }       
  }
  public function check_ulife_weight_edit($user,$date) {
    $users_register =   (new SqlController)->users_register_select($user);
    $key = $users_register->ulife_connect;
      if($key!== 0) {
        $RecordOfPregnancy = RecordOfPregnancy::where('user_id',$user)
                                              ->whereNull('deleted_at')
                                              ->where('preg_week', $date)
                                              ->get();
        $weight = [];
        $time = [];
          foreach ($RecordOfPregnancy as $object) {
            array_push($weight, $object->preg_weight);
            //$weight[] = $object->preg_weight;   
            array_push($time, $object->preg_week);    
            //$time []= $object->preg_week;     
            $data_graph = array( 'user_key'=> $key,
                                 'OFFSPRING'=>1,
                                 'GRAPH_WEIGHT'=>$weight,
                                 'GRAPH_TIME'=> $time
                               );                                 
          }
                        
        $graph_json = json_encode($data_graph);  
        $url2 ='http://128.199.147.57/api/v1/peat/setGraphWeight';
        $ch2 = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch2,CURLOPT_URL, $url2);
        curl_setopt( $ch2, CURLOPT_POST, true );
        curl_setopt($ch2,CURLOPT_POSTFIELDS,  $graph_json);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        //execute post
        $result = curl_exec($ch2);
        //close connection
        curl_close($ch2);
        $tracker = NOW();
        $tracker_update = RecordOfPregnancy::where('user_id', $user)
                                           ->whereNull('deleted_at')
                                           ->update([ 'data_to_ulife' =>$tracker]);
        return $result;
                                   
      }
            
  }
  public function check_ulife_tracker_edit($user,$dt) {
              
    $users_register =   (new SqlController)->users_register_select($user);
    $key = $users_register->ulife_connect;
      if($key!== '0') {
        $tracker = tracker::where('user_id',$user)
                          ->whereNull('deleted_at')
                          ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                          ->get();

        $tracker_api = [];
          foreach ( $tracker as $track) {
            $cre= $track->created_at;
            $up= $track->updated_at;
            $created_at = $cre->format('Y-m-d H:m:s');
            $updated_at =  $up->format('Y-m-d H:m:s');                           
            $tracker_api[] = array( 'id'=>$track->id,
                                    'user_key'=> $key,
                                    'breakfast'=>$track->breakfast,
                                    'lunch'=>$track->lunch,
                                    'dinner'=>$track->dinner,
                                    'dessert_lu'=>$track->dessert_lu,
                                    'dessert_din'=>$track->dessert_din,
                                    'exercise'=>$track->exercise,
                                    'vitamin'=>$track->vitamin,
                                    'created_at'=>$created_at,
                                    'updated_at'=>$updated_at,
                                    'deleted_at'=>$track->deleted_at
                                  );         
                                                          
          }
                             
        $tracker_json = json_encode($tracker_api);  
        $url3 ='http://128.199.147.57/api/v1/peat/setTrackers';
        $ch3 = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch3,CURLOPT_URL, $url3);
                                 
        curl_setopt($ch3, CURLOPT_POST, 1);
        curl_setopt($ch3,CURLOPT_POSTFIELDS, $tracker_json);
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch1,CURLOPT_URL, $url2);
        // curl_setopt($ch1, CURLOPT_POST, 1);
        // curl_setopt($ch1,CURLOPT_POSTFIELDS, $graph_json);
        // curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        //execute post
        $result = curl_exec($ch3);
        //close connection
        curl_close($ch3);
        $tracker = NOW();
        $tracker_update = tracker::where('user_id', $user)
                                 ->whereNull('deleted_at')
                                 ->update([ 'data_to_ulife' =>$tracker]);
        return $result;                            
      }
  }
  public function api_delete($user) {

    $date   = NOW();
    $deleted_at =  $date->format('Y-m-d H:m:s');
    $users_register =   (new SqlController)->users_register_select($user);
    $key = $users_register->ulife_connect;
      if($key!== 0) {
        $RecordOfPregnancy = RecordOfPregnancy::where('user_id',$user)
                                              ->whereNull('deleted_at')
                                              ->get();
        $weight = [];
        $time = [];
          foreach ($RecordOfPregnancy as $object) {
            array_push($weight, $object->preg_weight);
            //$weight[] = $object->preg_weight;   
            array_push($time, $object->preg_week);    
            //$time []= $object->preg_week;     
            $data_graph = array( 'user_key'=> $key,
                                 'OFFSPRING'=>1,
                                 'GRAPH_WEIGHT'=>$weight,
                                 'GRAPH_TIME'=> $time,
                                 'deleted_at'=>$deleted_at
                               );         
          }
        $graph_json = json_encode($data_graph);  
        $url2 ='http://128.199.147.57/api/v1/peat/setGraphWeight';
        $ch2 = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch2,CURLOPT_URL, $url2);
        curl_setopt( $ch2, CURLOPT_POST, true );
        curl_setopt($ch2,CURLOPT_POSTFIELDS,  $graph_json);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        //execute post
        $result = curl_exec($ch2);
        //close connection
        curl_close($ch2);

        $tracker_count = tracker::where('user_id',$user)
                                ->whereNull('deleted_at')
                                ->count();
                                  
        if( $tracker_count >= 1 ) {
                               
          $tracker = tracker::where('user_id',$user)
                            ->whereNull('deleted_at')
                            ->get();
                      
            foreach ( $tracker as $track) {
              $cre= $track->created_at;
              $up= $track->updated_at;
              $created_at = $cre->format('Y-m-d H:m:s');
              $updated_at =  $up->format('Y-m-d H:m:s');
                                               
              $tracker_api[] = array( 'id'=>$track->id,
                                      'user_key'=> $key,
                                      'breakfast'=>$track->breakfast,
                                      'lunch'=>$track->lunch,
                                      'dinner'=>$track->dinner,
                                      'dessert_lu'=>$track->dessert_lu,
                                      'dessert_din'=>$track->dessert_din,
                                      'exercise'=>$track->exercise,
                                      'vitamin'=>$track->vitamin,
                                      'created_at'=>$created_at,
                                      'updated_at'=>$updated_at,
                                      'deleted_at'=> $deleted_at,
                                    );                                          
            }
                             
          $tracker_json = json_encode($tracker_api);  
          $url3 ='http://128.199.147.57/api/v1/peat/setTrackers';
          $ch3 = curl_init();
          //set the url, number of POST vars, POST data
          curl_setopt($ch3,CURLOPT_URL, $url3);
          curl_setopt($ch3, CURLOPT_POST, 1);
          curl_setopt($ch3,CURLOPT_POSTFIELDS, $tracker_json);
          curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
          // curl_setopt($ch1,CURLOPT_URL, $url2);
          // curl_setopt($ch1, CURLOPT_POST, 1);
          // curl_setopt($ch1,CURLOPT_POSTFIELDS, $graph_json);
          // curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
          //execute post
          $result2 = curl_exec($ch3);
          //close connection
          curl_close($ch3);
          // print($result2);

        }
      }          
  }
  public function create() {
    return view('registration_doctor');
  }
  public function doctor_register(Request $request) {

    $doctor_id = $this->validate(request(), ['doctor_id' => 'required',]);
    $doctor = (new SqlController)->personal_doctor_select($doctor_id);
      if($doctor == NULL){
        $doctor_id = $request->input('doctor_id'); 
        $name = $request->input('name'); 
        $lastname = $request->input('lastname');
        $hospital = $request->input('hospital'); 
        $password = $request->input('password'); 
        $type_user = '1'; 
        $url ="https://health-track.in.th/personal_doctor/".$doctor_id;
        $qrcode =  (new diaryController)->generateQRCode($url);  
        // $url = $this->api_gen_qrcode($url);
       // $qrcode ='https://chart.googleapis.com/chart?chs=300x300&cht=qr&choe=UTF-8&chl=line://app/1656991660-K8bDpjZ9?key='.$doctor_id;
        $doctor = doctor::create(request(['doctor_id','name', 'lastname','qr_code','hospital','type_user' ,'password']));
        $qrcode_update = doctor::where('doctor_id', $doctor_id)
                               ->update(['qr_code' => $qrcode, 
                                        'type_user' => $type_user ]);
        $message = 'ลงทะเบียนแล้วค่ะ';   
         return Redirect::to('/')->with('success', true)->with('message','ลงทะเบียนแล้วค่ะ');  

      }else{
        $message = 'มีรหัสคุณหมอท่านนี้แล้วค่ะ';
         return Redirect::to('/doctor_register') ->with('message','มีรหัสคุณหมอท่านนี้แล้วค่ะ');
      }

         // return Redirect::to('layouts.home',compact('message'));
    return $message;  

  }
  
  public function api_gen_qrcode($url) {  
    $post_view = array (
                  'view' => 
                  array (
                    'type' => 'compact',
                    'url' => $url,
                  ),
                );
      $post = json_encode($post_view);   
      $authorization = "Authorization: Bearer UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=";
      $url ='https://api.line.me/liff/v1/apps';    
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$url);                               
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      $result = curl_exec($ch);
      curl_close($ch);
      $result = json_decode($result);  

      return $result->liffId; 
            
  }
  public function doctor_get_userdata(Request $request) {  
       $doctor_id = $request->input('doctor_id');
       $get_data = (new SqlController)->doctor_select_mom($doctor_id);
       return $get_data;       
  }
  public function get_log_message_mom(Request $request) {  
       $user = $request->input('user_id_line');
       $message_type = $request->input('message_type');
    
       $get_data = (new SqlController)->get_log_message_mom($user,$message_type);
       return $get_data;      
  }
  public function get_tracker_mom(Request $request) {  
       $user = $request->input('user_id_line');
       $get_data = (new SqlController)->get_tracker_mom($user);
       return $get_data;      
  }
  public function get_weight_mom(Request $request) {  
         $user = $request->input('user_id_line');
         $get_data = (new SqlController)->get_weight_mom($user);
         return $get_data;      
  }
  public function get_profile_doctor(Request $request) {  
         $doctor_id = $request->input('doctor_id');
         $get_data = (new SqlController)->personal_doctor_select($doctor_id);
         return $get_data;      
  }
  public function get_profile_mom(Request $request) {  
         $user = $request->input('user_id_line');
         $get_data = (new SqlController)->users_register_select($user);
         return $get_data;      
  }


// Update
	public function doctor_login(Request $request) {
	
	  	if (Session::get('doctor_id') == NULL){
		    $doctor_id = $request->input('doctor_id');
		    $password = $request->input('password');
		    $doctor = (new SqlController)->personal_doctor_select($doctor_id);
		      if ($doctor == NULL ) {
		        $message = 'รหัสประจำตัวหรือรหัสผ่าน ไม่ถูกต้อง';
		        return view('management.login')->with('message', 'Login Failed');        
		      } else {
              
              $doctor_qr =$doctor->qr_code;
		        if (Hash::check($password, $doctor->password)){
    				    	Session::put('doctor_id',$doctor_id);
    		        	Session::put('doctor_name',$doctor->name." ".$doctor->lastname);
                  Session::put('qr_code',$doctor_qr);     
		          	  $message = 'เข้าสู่ระบบเรียบร้อย';
                	$type_user = (new SqlController)->doctor_sel_typuser($doctor_id);
                	//$get_data = (new SqlController)->doctor_select_mom($doctor_id);
                	$datas = $this->get_dashboard($doctor_id);  
                  $weight_status = (new SqlController)->weight_status($doctor_id);
                         
                	return view('management.doctor', compact(['datas', 'weight_status'])); 
		        } else {
		            	$message = 'รหัสประจำตัวหรือรหัสผ่าน ไม่ถูกต้อง';
		          	  return view('management.login')->with('message', 'Login Failed');
		        }
		      }  
				return \Redirect::to('layouts.home',compact('message'));   
      } else {
          $datas = $this->get_dashboard(Session::get('doctor_id'));
          $weight_status = (new SqlController)->weight_status(Session::get('doctor_id'));
          return view('management.doctor', compact(['datas', 'weight_status']));	
      }
	
  }
  public function doctor_logout() {
    	Session::flush();
    	return redirect('/');
    
  }

	public function viewInfo($user_id) {
	
		$doctor_id = Session::get('doctor_id');
    	$message_type = '03';
		$chat = (new SqlController)->get_log_message_mom($user_id, $message_type);
		$message = (new SqlController)->get_log($user_id, $message_type);
		$record = DB::table('RecordOfPregnancy')
		                  ->select('preg_week','preg_weight')
		                  ->where('user_id', $user_id)
		                  ->whereNull('deleted_at')
		                  ->distinct()
		                  ->orderBy('preg_week', 'asc')
		                  ->get();
		$record1 = DB::table('users_register')
		                  ->select('user_Pre_weight')
		                  ->where('user_id', $user_id)
		                  ->whereNull('deleted_at')
		                  ->get();
		$user = DB::table('users_register')
		               // ->select('user_Pre_weight')
		                  ->where('user_id', $user_id)
		                  ->whereNull('deleted_at')
		                  ->first();
		$preg_week= DB::table('RecordOfPregnancy')
		                  ->select('preg_week')
		                  ->where('user_id', $user_id)
		                  ->whereNull('deleted_at')
		                  ->orderBy('preg_week', 'asc')
		                  ->get();
		$preg_weight = DB::table('RecordOfPregnancy')
		                     ->select('preg_weight')
		                     ->where('user_id', $user_id)
		                     ->whereNull('deleted_at')
		                     ->orderBy('preg_week', 'asc')
		                     ->get();
		$preg_week = $preg_week->pluck('preg_week');
		$preg_weight = $preg_weight ->pluck('preg_weight');
		
		$user_height = $user->user_height;
		$user_weight = $user->user_Pre_weight;
		$height = $user_height*0.01;
		$bmi = $user_weight/($height*$height);
		$bmi = number_format($bmi, 2, '.', '');
		    
		$record_food = tracker::where('user_id',$user_id)
		                        ->whereNull('deleted_at')
		                        // ->where('created_at', '>=',Carbon::now()->subDays(15))
		                        ->get();
			
		$record_vitamin = tracker::where('user_id',$user_id)
                               ->whereNull('deleted_at')
                              //  ->where('created_at', '>=',Carbon::now()->subDays(15))
                               ->get();
          
		$record_exercise = tracker::where('user_id',$user_id)
                               ->whereNull('deleted_at')
                              //  ->where('created_at', '>=',Carbon::now()->subDays(15))
                               ->get();		                        

    $graphdata = (new SqlController)->blood_sugar_select($user_id);
    $blood_sugar = blood_sugar::where('user_id',$user_id)
                                ->whereNull('deleted_at')
                                ->get();   
    // dd($graphdata);

    // $blood_sugar =  blood_sugar::orderBy('datetime', 'DESC')->where('user_id',$user_id)->whereNull('deleted_at')->select("datetime", \DB::raw('(CASE 
    // WHEN (( blood_sugar.time_of_day = 4 AND blood_sugar.blood_sugar >120) OR (( blood_sugar.time_of_day  = 1 AND blood_sugar.blood_sugar>95) OR (blood_sugar.time_of_day = 3 and blood_sugar.blood_sugar>140 ))) THEN "HIGHเกินเกณฑ์" 
    // WHEN blood_sugar.blood_sugar < 60 THEN "LOWต่ำกว่าเกณฑ์" 
    // ELSE "NORMALปกติ" 
    // END) AS status_lable'))->groupBy('datetime')
    //                        ->get();
    $blood_sugar = blood_sugar::where('user_id', $user_id)
                    ->whereNull('deleted_at')
                    ->select(
                        'datetime',
                        \DB::raw('MAX(meal) as meal'),
                        \DB::raw('MAX(time_of_day) as time_of_day'),
                        \DB::raw('MAX(blood_sugar) as blood_sugar'),
                        \DB::raw("
                            CASE
                                WHEN (
                                    (MAX(meal) = 4 AND MAX(blood_sugar) > 120)
                                    OR (
                                        (MAX(time_of_day) = 1 AND MAX(blood_sugar) > 95)
                                        OR (MAX(time_of_day) = 3 AND MAX(blood_sugar) > 140)
                                    )
                                ) THEN 'HIGHเกินเกณฑ์'
                                WHEN MAX(blood_sugar) < 60 THEN 'LOWต่ำกว่าเกณฑ์'
                                ELSE 'NORMALปกติ'
                            END AS status_lable
                        ")
                    )
                    ->groupBy('datetime')
                    ->get();
                           
    $fetal_movement = fetal_movement::where('user_id',  $user_id)
                      // ->where('date', $today)
                      ->whereNull('deleted_at')
                      ->orderBy('date', 'DESC')
                      ->get(); 
      

		$mom_info = (new SqlController)->users_register_select($user_id);

    $tracker_act = tracker_activity::where('user_id',  $user_id)
                                  // ->where('date', $today)
                                  ->whereNull('deleted_at')
                                  ->orderBy('date', 'DESC')
                                  ->get(); 

///////////////////////////////////////////////////////////////////////////

$strunit = Array(" ","ทัพพี","ช้อน","ช้อนโต๊ะ","ลูก","ฟอง","ตัว","มล.","ชิ้น");
$strmeal = Array(" ","เช้า","กลางวัน","เย็น","ว่างเช้า","ว่างบ่าย");
$strmeal = Array(" ","breakfast","lunch","dinner","dessert_lu","dessert_din");
// $unit = $strunit[$tkact->unit];
// $meal = $strmeal[$tkact->meal];
$breakfast = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                    ->where('tracker.user_id',  $user_id)
                    ->where('meal',1)
                    ->whereNull('tracker_activity.deleted_at')
                    ->select('tracker.date','tracker.time_breakfast as time','tracker.breakfast as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id','tracker_activity.calorie')
                    // ->groupBy(['breakfast','food_name'])
                    ->get();
                    // ->groupBy(['date','meal','time','food_name'])
                    // ->toArray();    

$lunch = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                    ->where('tracker.user_id',  $user_id)
                    ->where('meal',2)
                    ->whereNull('tracker_activity.deleted_at')
                    ->select('tracker.date','tracker.time_lunch as time','tracker.lunch as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id','tracker_activity.calorie')
                    // ->groupBy(['lunch','food_name'])
                    ->get();
                    // ->groupBy(['date','meal','time','food_name'])
                    // ->toArray();   

$dinner = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                    ->where('tracker.user_id',  $user_id)
                    ->where('meal',3)
                    ->whereNull('tracker_activity.deleted_at')
                    ->select('tracker.date','tracker.time_dinner as time','tracker.dinner as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id','tracker_activity.calorie')
                    // ->groupBy(['dinner','food_name'])
                    ->get();
                    // ->groupBy(['date','meal','time','food_name'])
                    // ->toArray();          
$dessert_lu = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                    ->where('tracker.user_id',  $user_id)
                    ->where('meal',4)
                    ->whereNull('tracker_activity.deleted_at')
                    ->select('tracker.date','tracker_activity.time as time','tracker.dessert_lu as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id','tracker_activity.calorie')
                    // ->groupBy(['dessert_lu','food_name'])
                    ->get();
                    // ->groupBy(['date','meal','time','food_name'])
                    // ->toArray();     
                    
$dessert_din = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')  
                    ->where('tracker.user_id',  $user_id)
                    ->where('meal',5)
                    ->whereNull('tracker_activity.deleted_at')
                    ->select('tracker.date','tracker_activity.time as time','tracker.dessert_din as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id','tracker_activity.calorie')
                    // ->groupBy(['dessert_din','food_name'])
                    ->get();
                    // ->groupBy(['date','meal','time','food_name'])
                    // ->toArray();       

    // $array = array_merge_recursive($breakfast, $lunch);
    // $array1 = array_merge_recursive($array, $dinner);
    // $array2 = array_merge_recursive($array1, $dessert_lu);
    // $array3 = array_merge_recursive($array2, $dessert_din);
    $array =  $breakfast->merge($dessert_lu);
    $array1 =  $array->merge($lunch);
    $array2 =  $array1->merge($dessert_din);
    $array3 =  $array2->merge($dinner);


  
    $array3 = $array3->sortByDesc(function($post) {
      return sprintf($post->date, $post->time);
    });

// $array3 = (object) $array3;
// dd($array3);
// dd(gettype($array3));
  $graphbar = $this->summary_bloodsuger($user_id);
// dd($graphbar);
/////////////////////////////////////////////////////////////////////////////
  		return view('management.info',["doctor_id" => $doctor_id,'user_id' => $user_id,'all_message' => $message,'record' => $record, 'record1' => $record1 , 'bmi' => $bmi, 'preg_week' => $preg_week , 'preg_weight' => $preg_weight ,'record_food' => $record_food ,'record_vitamin' => $record_vitamin ,'record_exercise' => $record_exercise,'mom_info' => $mom_info , 'chats' => $chat , 'chats' => $chat , 'graphdata'=>$graphdata, 'fetal_movement'=>$fetal_movement,'blood_sugar'=>$blood_sugar,'tracker_act'=> $tracker_act,'array3'=> $array3, 'graphbar'=>$graphbar]);
 	}
///admin
  public function list_user() {

     $users = (new SqlController)->doctor_sel();
      return view('admin_edit_user',['users'=>$users]);
    
  }

  public function show_edit($doctor_id) {
     $users =  (new SqlController)->personal_doctor_select($doctor_id);
     return view('user_update',['users'=>$users]);
  }

  public function edit(Request $request,$doctor_id){
        $name = $request->input('name');
        $lastname = $request->input('lastname');
        $hospital = $request->input('hospital');
        $password = $request->input('password');
        $users_register = doctor::where('doctor_id', $doctor_id)
                                ->update(['name' => $name ,'lastname' => $lastname,'hospital' => $hospital  ,'password' => $password  ]);
        echo "Record updated successfully.
        ";
  }

//get user data
  public function get_dashboard($doctor_id){
      //$doctor_id = $request->input('doctor_id');
        $users = DB::table('personal_doctor_mom')
                ->where('personal_doctor_mom.doctor_id',$doctor_id)
                ->join('users_register', function ($join) {
                $join->on('personal_doctor_mom.user_id', '=', 'users_register.user_id');
                })
                ->whereNull('personal_doctor_mom.deleted_at')
                ->whereNull('users_register.deleted_at')
                ->select('users_register.hospital_num','users_register.user_name','users_register.preg_week','users_register.due_date','users_register.user_weight', 'users_register.created_at', 'users_register.weight_status','users_register.user_id')
                ->orderBy('users_register.id', 'asc')
                // ->distinct('users_register')
                ->paginate(1000);
                // ->get();


            return $users;
  }

  public function update_hospital_num($hospital_num,$user){
//        $hospital_num = $request->input('hospital_num');
//        $user = $request->input('user_id_line');
        $users_register = users_register::where('user_id', $user)
                                ->whereNull('deleted_at')
                                ->update(['hospital_num' => $hospital_num ]);
        return $users_register;
  }
  
  public function hnnumber_save(Request $request) {
	$hu_number = $request->input('hn_number');
	$user_id = $request->input('user_id');
	$this->update_hospital_num($hu_number,$user_id);
	return $this->viewInfo($user_id);
  }


  public function remove_user($user_id) {
    
    $users_register = personal_doctor_mom::where('user_id', $user_id)
                       ->update(['deleted_at'=>NOW()]);
    return redirect()->back()->with('message', 'IT WORKS!');

  }
  
  public function summary_bloodsuger($user_id) {
    // $graphbar =  blood_sugar::orderBy('datetime', 'DESC')->where('user_id',$user_id)->whereNull('deleted_at')->select("datetime", "meal","time_of_day",\DB::raw('(CASE 
    // WHEN (( blood_sugar.time_of_day = 4 AND blood_sugar.blood_sugar >120) OR (( blood_sugar.time_of_day  = 1 AND blood_sugar.blood_sugar>95) OR (blood_sugar.time_of_day = 3 and blood_sugar.blood_sugar>140 ))) THEN "high" 
    // WHEN blood_sugar.blood_sugar < 60 THEN "low" 
    // ELSE "normal" 
    // END) AS status'))->groupBy('datetime')
    //                  ->get();

    $graphbar = blood_sugar::where('user_id', $user_id)
    ->whereNull('deleted_at')
    ->select(
        'datetime',
        'meal',
        'time_of_day',
        DB::raw('(CASE 
            WHEN (
                (time_of_day = 4 AND blood_sugar > 120)
                OR (time_of_day = 1 AND blood_sugar > 95)
                OR (time_of_day = 3 AND blood_sugar > 140)
            ) THEN "high"
            WHEN blood_sugar < 60 THEN "low"
            ELSE "normal"
        END) AS status')
    )
    ->groupBy('datetime', 'meal', 'time_of_day', 'blood_sugar')
    ->orderBy('datetime', 'DESC')
    ->get();


	  // $graphdata = (new SqlController)->blood_sugar_select($user_id);
    $a = $graphbar->transform(function ($graphbar) {
    $bullet_des = ["-","ก่อน", "ก่อน", "หลัง", "หลัง"];
    $bullet_meal = ["-","เช้า","กลางวัน", "เย็น", "ก่อนนอน"];

      $datetime = strtotime($graphbar->datetime);
      $graphbar->date = date('Y-m-d',$datetime);
      return [
          'date' => $graphbar->date,
          'category' =>  $bullet_meal[$graphbar->meal]."-".$bullet_des[$graphbar->time_of_day],
          'status' => $graphbar->status,
      ];
  });



  // dd($graphbar);


/////////////////////////////////////////////////////////////////////////////
  		// return view('test_api',[ 'graphbar'=>$graphbar]);
      return $graphbar;
 	}

  public function test_graph($user_id = "Uc305004a2182c70e5d46431bfe37dc36") {
    $graphbar =  blood_sugar::orderBy('datetime', 'DESC')->where('user_id',$user_id)->whereNull('deleted_at')->select("datetime", "meal","time_of_day",\DB::raw('(CASE 
    WHEN (( blood_sugar.time_of_day = 4 AND blood_sugar.blood_sugar >120) OR (( blood_sugar.time_of_day  = 1 AND blood_sugar.blood_sugar>95) OR (blood_sugar.time_of_day = 3 and blood_sugar.blood_sugar>140 ))) THEN "high" 
    WHEN blood_sugar.blood_sugar < 60 THEN "low" 
    ELSE "normal" 
    END) AS status'))->groupBy('datetime')
                     ->get();

	  // $graphdata = (new SqlController)->blood_sugar_select($user_id);
    $a = $graphbar->transform(function ($graphbar) {
    $bullet_des = ["-","ก่อน", "ก่อน", "หลัง", "หลัง"];
    $bullet_meal = ["-","เช้า","กลางวัน", "เย็น", "ก่อนนอน"];

      $datetime = strtotime($graphbar->datetime);
      $graphbar->date = date('Y-m-d',$datetime);
      return [
          'date' => $graphbar->date,
          'category' =>  $bullet_meal[$graphbar->meal]."-".$bullet_des[$graphbar->time_of_day],
          'status' => $graphbar->status,
      ];
  });



  


/////////////////////////////////////////////////////////////////////////////
  		return view('test_api',[ 'graphbar'=>$graphbar]);
 	}

   // ต้องตรวจให้ตรงกับค่าที่ระบบบันทึกจริง
    private const ACTIVE_STATUSES = [0, 1];
    private const WAITING_STATUS = 0;
    private const CONTINUING_STATUS = 1;

    private const AI_COMPLETED = 'completed';
    private const REVIEW_PENDING = 'pending';
    private const COMPLICATION_YES = 1;
    private const HIGH_RISK = 'high';

    // เติมหลังตรวจความหมายรหัส weight_status ของระบบ
    private const WEIGHT_LABELS = [
        // 0 => '...',
        // 1 => '...',
    ];

    private const FOLLOW_UP_WEIGHT_CODES = [
        // ใส่รหัสน้ำหนักที่ต้องติดตาม
    ];
   public function dashboard_overview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'task_page' => 'sometimes|integer|min:1',
            'follow_up_page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $perPage = (int) ($validated['per_page'] ?? 20);

        $today = CarbonImmutable::now('Asia/Bangkok')->startOfDay();
        $tomorrow = $today->addDay();
        $sevenDaysAgo = $today->subDays(6);

        /*
         * แปลงขอบเขตเวลาเป็น timezone ที่ใช้เก็บในฐานข้อมูล
         * ตัวอย่าง: DB_TIMEZONE=UTC หรือ Asia/Bangkok
         */
        $dbTimezone = config('dashboard.db_timezone', 'UTC');

        $startToday = $today->setTimezone($dbTimezone)->toDateTimeString();
        $endToday = $tomorrow->setTimezone($dbTimezone)->toDateTimeString();
        $startSevenDays = $sevenDaysAgo
            ->setTimezone($dbTimezone)
            ->toDateTimeString();

        // ทุกส่วนของ dashboard ใช้ผู้รับบริการ active ที่ไม่ถูก soft delete
        $active = DB::table('users_register as p')
            ->whereNull('p.deleted_at')
            ->whereIn('p.status', self::ACTIVE_STATUSES);

        /*
         * 1. ข้อมูลผู้รับบริการพื้นฐาน
         * จำนวนทั้งหมด = ผู้รับบริการทุกสถานะที่ไม่ถูกลบ
         */
        $patientSummary = [
            'total' => DB::table('users_register')
                ->whereNull('deleted_at')
                ->count(),

            'new_today' => DB::table('users_register')
                ->whereNull('deleted_at')
                ->where('created_at', '>=', $startToday)
                ->where('created_at', '<', $endToday)
                ->count(),

            'active_total' => (clone $active)->count(),

            'waiting' => (clone $active)
                ->where('p.status', self::WAITING_STATUS)
                ->count(),

            'continuing_care' => (clone $active)
                ->where('p.status', self::CONTINUING_STATUS)
                ->count(),
        ];

        /*
         * 2. สรุป AI วิเคราะห์อาหาร
         * นับจำนวนมื้อ ไม่ใช่จำนวน meal_items
         */
        $meals = DB::table('meal_transactions as m')
            ->join('users_register as p', 'p.id', '=', 'm.user_id')
            ->whereNull('p.deleted_at')
            ->whereIn('p.status', self::ACTIVE_STATUSES);

        $foodSummary = [
            'total' => (clone $meals)->count(),

            'analyzed' => (clone $meals)
                ->where('m.ai_status', self::AI_COMPLETED)
                ->count(),

            'waiting_review' => (clone $meals)
                ->where('m.ai_status', self::AI_COMPLETED)
                ->where('m.review_status', self::REVIEW_PENDING)
                ->count(),
        ];

        /*
         * 3. กิจกรรมย้อนหลัง 7 วัน รวมวันนี้
         * หนึ่งแถวในตารางต้นทาง = หนึ่งกิจกรรม
         * ใช้ created_at เป็นเวลาบันทึกกิจกรรม
         *
         * ไม่นับ meal_items และ logs การแก้ไข เพื่อไม่ให้นับมื้อซ้ำ
         * vitamin_logs ไม่มี user_id จึงยังนำมานับแยกผู้รับบริการไม่ได้
         */
        $activitySources = [
            ['meal_transactions', 'id', false],
            ['exercise_logs', 'id', true],
            ['RecordOfPregnancy', 'user_id', true],
            ['blood_sugar', 'user_id', false],
            ['fetal_movement', 'user_id', true],
        ];

        $activityUnion = null;

        foreach ($activitySources as [$table, $patientKey, $softDeletes]) {
            $query = DB::table("$table as a")
                ->join('users_register as p', "p.$patientKey", '=', 'a.user_id')
                ->whereNull('p.deleted_at')
                ->whereIn('p.status', self::ACTIVE_STATUSES)
                ->where('a.created_at', '>=', $startSevenDays)
                ->where('a.created_at', '<', $endToday);

            if ($softDeletes) {
                $query->whereNull('a.deleted_at');
            }

            if ($table === 'RecordOfPregnancy') {
                $query->where('a.deleted_status', 0);
            }

            /*
             * แปลงเวลาเป็นไทยก่อนจัดกลุ่มรายวัน
             * Asia/Bangkok = UTC+7 ไม่มี DST
             * DB_TIMEZONE ต้องเป็น UTC หรือ Asia/Bangkok
             */
            $dateExpression = $dbTimezone === 'UTC'
                ? 'DATE(DATE_ADD(a.created_at, INTERVAL 7 HOUR))'
                : 'DATE(a.created_at)';

            $query->selectRaw("$dateExpression as activity_date")
                ->selectRaw('COUNT(*) as activity_count')
                ->groupByRaw($dateExpression);

            if ($activityUnion === null) {
                $activityUnion = $query;
            } else {
                $activityUnion->unionAll($query);
            }
        }

        $activityCounts = DB::query()
            ->fromSub($activityUnion, 'activity')
            ->select('activity_date')
            ->selectRaw('SUM(activity_count) as total')
            ->groupBy('activity_date')
            ->pluck('total', 'activity_date');

        $activities = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $sevenDaysAgo->addDays($i)->toDateString();

            $activities[] = [
                'date' => $date,
                'count' => (int) ($activityCounts[$date] ?? 0),
            ];
        }

        /*
         * น้ำหนักล่าสุด: เรียงตาม created_at แล้ว id
         * ไม่ใช้ JOIN ทุกแถว เพราะจะทำให้จำนวนผู้รับบริการเพิ่มซ้ำ
         */
        $latestWeight = DB::table('RecordOfPregnancy as w')
            ->whereNull('w.deleted_at')
            ->where('w.deleted_status', 0)
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('RecordOfPregnancy as newer')
                    ->whereColumn('newer.user_id', 'w.user_id')
                    ->whereNull('newer.deleted_at')
                    ->where('newer.deleted_status', 0)
                    ->whereRaw("
                        COALESCE(newer.created_at, '1000-01-01')
                            > COALESCE(w.created_at, '1000-01-01')
                        OR (
                            COALESCE(newer.created_at, '1000-01-01')
                                = COALESCE(w.created_at, '1000-01-01')
                            AND newer.id > w.id
                        )
                    ");
            })
            ->select(
                'w.user_id',
                'w.weight_status',
                'w.preg_week',
                'w.preg_weight',
                'w.created_at',
                'w.updated_at'
            );

        // ใช้สถานะน้ำหนักจากบันทึกล่าสุด ถ้าไม่มีจึงใช้ข้อมูลสมัคร
        $patientsWithWeight = (clone $active)
            ->leftJoinSub($latestWeight, 'w', function ($join) {
                $join->on('w.user_id', '=', 'p.user_id');
            });

        $weightCodeSql = 'COALESCE(w.weight_status, p.weight_status)';

        /*
         * 4. ภาวะแทรกซ้อนและน้ำหนัก
         * เปอร์เซ็นต์หารด้วย active_total
         * ผู้รับบริการหนึ่งคนอาจมีหลายภาวะแทรกซ้อน
         */
        $activeTotal = $patientSummary['active_total'];

        $percentage = static fn (int $count): float =>
            $activeTotal > 0
                ? round($count * 100 / $activeTotal, 2)
                : 0.0;

        $complications = [];

        foreach ([
            'compli_diabete' => 'เบาหวาน',
            'compli_hypertension' => 'ความดันโลหิตสูง',
            'compli_preterm_birth' => 'คลอดก่อนกำหนด',
        ] as $field => $label) {
            $count = (clone $active)
                ->where("p.$field", self::COMPLICATION_YES)
                ->count();

            $complications[] = [
                'type' => $field,
                'label' => $label,
                'count' => $count,
                'percentage' => $percentage($count),
            ];
        }

        $weightSummary = (clone $patientsWithWeight)
            ->selectRaw("$weightCodeSql as code")
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw($weightCodeSql)
            ->get()
            ->map(fn ($row) => [
                'code' => $row->code === null ? null : (int) $row->code,
                'type' => $row->code === null
                    ? 'ไม่มีข้อมูล'
                    : (self::WEIGHT_LABELS[(int) $row->code]
                        ?? "รหัสน้ำหนัก {$row->code}"),
                'count' => (int) $row->total,
                'percentage' => $percentage((int) $row->total),
            ]);

        /*
         * 5. รายการต้องจัดการวันนี้
         * รวมงานค้างก่อนวันนี้ด้วย ไม่ใช่เฉพาะงานที่สร้างวันนี้
         * urgency เป็นลำดับจัดการงาน ไม่ใช่การประเมินฉุกเฉินทางการแพทย์
         */
        $reviewTasks = (clone $meals)
            ->where('m.ai_status', self::AI_COMPLETED)
            ->where('m.review_status', self::REVIEW_PENDING)
            ->where('m.created_at', '<', $endToday)
            ->selectRaw("'food_review' as type")
            ->selectRaw('m.id as reference_id, p.id as patient_id')
            ->selectRaw('p.user_name as full_name, p.hospital_num as hn')
            ->selectRaw(
                "CASE WHEN m.gdm_risk = ? THEN 'high' ELSE 'normal' END as urgency",
                [self::HIGH_RISK]
            )
            ->selectRaw(
                'CASE WHEN m.gdm_risk = ? THEN 1 ELSE 2 END as priority',
                [self::HIGH_RISK]
            )
            ->selectRaw('COALESCE(m.updated_at, m.created_at) as updated_at');

        $patientTasks = (clone $active)
            ->where('p.status', self::WAITING_STATUS)
            ->where('p.created_at', '<', $endToday)
            ->selectRaw("'patient_review' as type")
            ->selectRaw('p.id as reference_id, p.id as patient_id')
            ->selectRaw('p.user_name as full_name, p.hospital_num as hn')
            ->selectRaw("'normal' as urgency, 2 as priority")
            ->selectRaw('COALESCE(p.updated_at, p.created_at) as updated_at');

        $taskUnion = $reviewTasks->unionAll($patientTasks);

        $tasks = DB::query()
            ->fromSub($taskUnion, 'tasks')
            ->orderBy('priority')
            ->orderBy('updated_at')
            ->orderBy('type')
            ->orderBy('reference_id')
            ->paginate($perPage, ['*'], 'task_page');

        /*
         * 6. ผู้รับบริการที่ควรติดตาม
         * รอตรวจ / มีภาวะแทรกซ้อน / น้ำหนักเข้าเกณฑ์ที่ตั้งค่า /
         * มีมื้ออาหาร high risk ที่ยังรอตรวจ
         */
        $mealUpdates = DB::table('meal_transactions')
            ->select('user_id')
            ->selectRaw('MAX(COALESCE(updated_at, created_at)) as updated_at')
            ->groupBy('user_id');

        $followUpQuery = (clone $patientsWithWeight)
            ->leftJoinSub($mealUpdates, 'mu', function ($join) {
                $join->on('mu.user_id', '=', 'p.id');
            })
            ->where(function ($query) use ($weightCodeSql) {
                $query->where('p.status', self::WAITING_STATUS)
                    ->orWhere('p.compli_diabete', self::COMPLICATION_YES)
                    ->orWhere('p.compli_hypertension', self::COMPLICATION_YES)
                    ->orWhere('p.compli_preterm_birth', self::COMPLICATION_YES);

                if (self::FOLLOW_UP_WEIGHT_CODES !== []) {
                    $query->orWhereIn(
                        DB::raw($weightCodeSql),
                        self::FOLLOW_UP_WEIGHT_CODES
                    );
                }

                $query->orWhereExists(function ($meal) {
                    $meal->selectRaw('1')
                        ->from('meal_transactions as risk')
                        ->whereColumn('risk.user_id', 'p.id')
                        ->where('risk.ai_status', self::AI_COMPLETED)
                        ->where('risk.review_status', self::REVIEW_PENDING)
                        ->where('risk.gdm_risk', self::HIGH_RISK);
                });
            })
            ->select(
                'p.id',
                'p.user_name as full_name',
                'p.hospital_num as hn',
                'p.status'
            )
            ->selectRaw('COALESCE(w.preg_week, p.preg_week) as gestational_week')
            ->selectRaw("$weightCodeSql as weight_status")
            ->selectRaw('COALESCE(w.preg_weight, p.user_weight) as weight')
            ->selectRaw("
                GREATEST(
                    COALESCE(p.updated_at, p.created_at, '1000-01-01'),
                    COALESCE(w.updated_at, w.created_at, '1000-01-01'),
                    COALESCE(mu.updated_at, '1000-01-01')
                ) as updated_at
            ")
            ->orderByDesc('updated_at')
            ->orderBy('p.id');

        $followUp = $followUpQuery
            ->paginate($perPage, ['*'], 'follow_up_page');

        $followUp->getCollection()->transform(function ($patient) {
            $code = $patient->weight_status === null
                ? null
                : (int) $patient->weight_status;

            return [
                'id' => (int) $patient->id,
                'full_name' => $patient->full_name,
                'first_name' => null,
                'last_name' => null,
                'hn' => $patient->hn,
                // อายุครรภ์จากข้อมูลล่าสุดที่บันทึก ไม่คำนวณเพิ่มตามวัน
                'gestational_week' => $patient->gestational_week,
                'status_code' => (int) $patient->status,
                'status' => (int) $patient->status === self::WAITING_STATUS
                    ? 'รอตรวจ'
                    : 'ดูแลต่อเนื่อง',
                'weight' => $patient->weight,
                'weight_signal' => [
                    'code' => $code,
                    'label' => $code === null
                        ? 'ไม่มีข้อมูล'
                        : (self::WEIGHT_LABELS[$code] ?? "รหัสน้ำหนัก {$code}"),
                    'requires_follow_up' => $code === null
                        || self::FOLLOW_UP_WEIGHT_CODES === []
                            ? null
                            : in_array($code, self::FOLLOW_UP_WEIGHT_CODES, true),
                ],
                'updated_at' => $patient->updated_at === '1000-01-01'
                    ? null
                    : $patient->updated_at,
            ];
        });

        $pageData = static fn ($page): array => [
            'items' => $page->items(),
            'total' => $page->total(),
            'current_page' => $page->currentPage(),
            'per_page' => $page->perPage(),
            'last_page' => $page->lastPage(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'patients' => $patientSummary,
                'ai_food_analysis' => $foodSummary,
                'activities_last_7_days' => $activities,
                'complications_and_weight' => [
                    'denominator' => $activeTotal,
                    'complications' => $complications,
                    'weight' => $weightSummary,
                ],
                'tasks_today' => $pageData($tasks),
                'patients_to_follow_up' => $pageData($followUp),
            ],
            'meta' => [
                'date' => $today->toDateString(),
                'timezone' => 'Asia/Bangkok',
                'database_timezone' => $dbTimezone,
                'generated_at' => CarbonImmutable::now('Asia/Bangkok')
                    ->toIso8601String(),
            ],
        ]);
    }
}
    
