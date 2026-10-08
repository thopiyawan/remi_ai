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
  		return view('test_api',[ 'graphbar'=>$graphbar]);
 	}
/////////////////////////////////////////////////////////////////////////////
   //new remi
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
           0 => 'ไม่มีการบันทึก',
           1 => 'น้ำหนักปกติ',
           2 => 'น้ำหนักน้อยกว่าเกณฑ์',
           3 => 'น้ำหนักเกินเกณฑ์',
           4 => 'ภาวะแทรกซ้อน',
    ];

    private const FOLLOW_UP_WEIGHT_CODES = [
        // ใส่รหัสน้ำหนักที่ต้องติดตาม
    ];
   public function dashboard_overview(Request $request)
{
    // $doctor_id = Session::get('doctor_id');

    // if ($doctor_id === null || $doctor_id === '') {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'กรุณาเข้าสู่ระบบแพทย์',
    //     ], 401);
    // }

    $validated = $request->validate([
        'doctor_id' => 'required|string|max:255',
    ]);

    $doctor_id = $validated['doctor_id'];

    /*
     * ปรับรหัสให้ตรงกับค่าที่ระบบบันทึกจริง
     * SQL ไม่ได้ระบุความหมายของรหัสเหล่านี้
     */
    $waitingStatus = 0;
    $continuingStatus = 1;
    $activeStatuses = [$waitingStatus, $continuingStatus];

    $aiCompleted = 'completed';
    $reviewPending = 'pending';
    $complicationYes = 1;

    // เติมความหมายของ weight_status ตามระบบจริง
    $weightLabels = [
        0 => 'ไม่มีการบันทึก',
        1 => 'น้ำหนักปกติ',
        2 => 'น้ำหนักน้อยกว่าเกณฑ์',
        3 => 'น้ำหนักเกินเกณฑ์',
        4 => 'ภาวะแทรกซ้อน',
    ];

    /*
     * เวลาแสดงผลใช้ประเทศไทย
     * ตั้ง config/dashboard.php ให้ตรงกับ timezone ที่ DB เก็บ
     * รองรับ UTC หรือ Asia/Bangkok
     */
    $dbTimezone = config('dashboard.db_timezone', 'UTC');

    $today = CarbonImmutable::now('Asia/Bangkok')->startOfDay();
    $firstDay = $today->subDays(6);

    $todayStart = $today
        ->setTimezone($dbTimezone)
        ->toDateTimeString();

    $tomorrowStart = $today->addDay()
        ->setTimezone($dbTimezone)
        ->toDateTimeString();

    $sevenDaysStart = $firstDay
        ->setTimezone($dbTimezone)
        ->toDateTimeString();

    /*
     * คนไข้ทั้งหมดของแพทย์ที่ไม่ถูกลบ
     * whereExists ไม่ทำให้คนไข้ซ้ำจากตารางความสัมพันธ์
     */
    $patients = DB::table('users_register as p')
        ->whereNull('p.deleted_at')
        ->whereExists(function ($query) use ($doctor_id) {
            $query->selectRaw('1')
                ->from('personal_doctor_mom as pd')
                ->whereColumn('pd.user_id', 'p.user_id')
                ->where('pd.doctor_id', $doctor_id)
                ->whereNull('pd.deleted_at');
        });

    $activePatients = (clone $patients)
        ->whereIn('p.status', $activeStatuses);

    /*
     * คีย์สำหรับตารางใหม่และตารางเดิมต่างกัน
     *
     * meal_transactions / exercise_logs:
     * user_id -> users_register.id
     *
     * blood_sugar / fetal_movement / RecordOfPregnancy:
     * user_id -> users_register.user_id
     */
    $activeIds = (clone $activePatients)->select('p.id');
    $activeLineIds = (clone $activePatients)->select('p.user_id');

    // ---------------------------------------
    // 1. ข้อมูลผู้รับบริการพื้นฐาน
    // ---------------------------------------

    $activeTotal = (clone $activePatients)->count();

    $patientSummary = [
        'total' => (clone $patients)->count(),

        'new_today' => (clone $patients)
            ->where('p.created_at', '>=', $todayStart)
            ->where('p.created_at', '<', $tomorrowStart)
            ->count(),

        'active_total' => $activeTotal,

        'waiting' => (clone $activePatients)
            ->where('p.status', $waitingStatus)
            ->count(),

        'continuing_care' => (clone $activePatients)
            ->where('p.status', $continuingStatus)
            ->count(),
    ];

    // ---------------------------------------
    // 2. สรุป AI วิเคราะห์อาหาร นับเป็นมื้อ
    // ---------------------------------------

    $meals = DB::table('meal_transactions as m')
        ->whereIn('m.user_id', clone $activeIds);

    $foodSummary = [
        'total' => (clone $meals)->count(),

        'analyzed' => (clone $meals)
            ->where('m.ai_status', $aiCompleted)
            ->count(),

        'waiting_review' => (clone $meals)
            ->where('m.ai_status', $aiCompleted)
            ->where('m.review_status', $reviewPending)
            ->count(),
    ];

    // ---------------------------------------
    // 3. กิจกรรมย้อนหลัง 7 วัน รวมวันนี้
    // หนึ่งแถวที่บันทึก = หนึ่งกิจกรรม
    // ---------------------------------------

    $activitySources = [
        ['meal_transactions', 'numeric', false],
        ['exercise_logs', 'numeric', true],
        ['blood_sugar', 'line', false],
        ['fetal_movement', 'line', true],
        ['RecordOfPregnancy', 'line', true],
    ];

    $dailyCounts = [];

    foreach ($activitySources as [$table, $keyType, $softDeletes]) {
        $query = DB::table("$table as a")
            ->whereIn(
                'a.user_id',
                $keyType === 'numeric'
                    ? clone $activeIds
                    : clone $activeLineIds
            )
            ->where('a.created_at', '>=', $sevenDaysStart)
            ->where('a.created_at', '<', $tomorrowStart);

        if ($softDeletes) {
            $query->whereNull('a.deleted_at');
        }

        if ($table === 'RecordOfPregnancy') {
            $query->where('a.deleted_status', 0);
        }

        // เปลี่ยนเวลาที่เก็บใน UTC ให้เป็นวันที่ประเทศไทย
        $dateSql = $dbTimezone === 'UTC'
            ? 'DATE(DATE_ADD(a.created_at, INTERVAL 7 HOUR))'
            : 'DATE(a.created_at)';

        $rows = $query
            ->selectRaw("$dateSql as activity_date")
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw($dateSql)
            ->get();

        foreach ($rows as $row) {
            $date = $row->activity_date;

            $dailyCounts[$date] =
                ($dailyCounts[$date] ?? 0) + (int) $row->total;
        }
    }

    $activities = [];

    for ($i = 0; $i < 7; $i++) {
        $date = $firstDay->addDays($i)->toDateString();

        $activities[] = [
            'date' => $date,
            'count' => $dailyCounts[$date] ?? 0,
        ];
    }

    // ---------------------------------------
    // 4. ภาวะแทรกซ้อนและน้ำหนัก
    // เปอร์เซ็นต์คิดจากผู้รับบริการ active
    // ---------------------------------------

    $percentage = static function ($count) use ($activeTotal) {
        return $activeTotal > 0
            ? round($count * 100 / $activeTotal, 2)
            : 0.0;
    };

    $complications = [];

    $complicationFields = [
        'compli_diabete' => 'เบาหวาน',
        'compli_hypertension' => 'ความดันโลหิตสูง',
        'compli_preterm_birth' => 'คลอดก่อนกำหนด',
    ];

    foreach ($complicationFields as $field => $label) {
        $count = (clone $activePatients)
            ->where("p.$field", $complicationYes)
            ->count();

        $complications[] = [
            'type' => $field,
            'label' => $label,
            'count' => $count,
            'percentage' => $percentage($count),
        ];
    }

    // ใช้ weight_status ใน users_register ตาม Query เดิม
    $weightSummary = (clone $activePatients)
        ->select('p.weight_status')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('p.weight_status')
        ->orderBy('p.weight_status')
        ->get()
        ->map(function ($row) use ($percentage, $weightLabels) {
            $code = $row->weight_status === null
                ? null
                : (int) $row->weight_status;

            return [
                'type' => 'weight',
                'code' => $code,
                'label' => $code === null
                    ? 'ไม่มีข้อมูล'
                    : ($weightLabels[$code] ?? "รหัสน้ำหนัก {$code}"),
                'count' => (int) $row->total,
                'percentage' => $percentage((int) $row->total),
            ];
        });

    // ---------------------------------------
    // 5. รายการที่ต้องจัดการวันนี้
    // รวมรายการรอตรวจที่ค้างจนถึงวันนี้
    // ---------------------------------------

    $foodTasks = (clone $meals)
        ->join('users_register as p', 'p.id', '=', 'm.user_id')
        ->where('m.ai_status', $aiCompleted)
        ->where('m.review_status', $reviewPending)
        ->where('m.created_at', '<', $tomorrowStart)
        ->selectRaw("'food_review' as type")
        ->selectRaw("'ตรวจสอบอาหาร' as type_label")
        ->selectRaw('m.id as reference_id')
        ->selectRaw('p.id as patient_id')
        ->selectRaw('p.user_name as name')
        ->selectRaw('p.hospital_num as hn')
        ->selectRaw("
            CASE
                WHEN m.gdm_risk = 'high' THEN 'high'
                ELSE 'normal'
            END as urgency
        ")
        ->selectRaw("
            CASE
                WHEN m.gdm_risk = 'high' THEN 1
                ELSE 2
            END as priority
        ")
        ->selectRaw('COALESCE(m.updated_at, m.created_at) as updated_at');

    $patientTasks = (clone $activePatients)
        ->where('p.status', $waitingStatus)
        ->where('p.created_at', '<', $tomorrowStart)
        ->selectRaw("'patient_review' as type")
        ->selectRaw("'ตรวจสอบผู้รับบริการ' as type_label")
        ->selectRaw('p.id as reference_id')
        ->selectRaw('p.id as patient_id')
        ->selectRaw('p.user_name as name')
        ->selectRaw('p.hospital_num as hn')
        ->selectRaw("'normal' as urgency")
        ->selectRaw('2 as priority')
        ->selectRaw('COALESCE(p.updated_at, p.created_at) as updated_at');

    $taskUnion = $foodTasks->unionAll($patientTasks);

    $tasks = DB::query()
        ->fromSub($taskUnion, 'tasks')
        ->orderBy('priority')
        ->orderBy('updated_at')
        ->orderBy('type')
        ->orderBy('reference_id')
        ->paginate(20, ['*'], 'task_page');

    // ---------------------------------------
    // 6. รายชื่อคนไข้ของแพทย์
    // ส่ง name โดยไม่แยกชื่อและนามสกุล
    // ---------------------------------------

    $users = (clone $patients)
        ->select(
            'p.id',
            'p.user_id',
            'p.user_name as name',
            'p.hospital_num as hn',
            'p.preg_week as gestational_week',
            'p.due_date',
            'p.user_weight as weight',
            'p.weight_status',
            'p.status as status_code',
            'p.created_at'
        )
        ->selectRaw('COALESCE(p.updated_at, p.created_at) as updated_at')
        ->orderBy('p.id')
        ->paginate(50, ['*'], 'patient_page');

    $users->getCollection()->transform(
        function ($user) use (
            $waitingStatus,
            $continuingStatus,
            $activeStatuses,
            $weightLabels
        ) {
            $statusCode = (int) $user->status_code;
            $weightCode = $user->weight_status === null
                ? 'ไม่มีการบันทึก'
                : (int) $user->weight_status;

            $user->is_active = in_array(
                $statusCode,
                $activeStatuses,
                true
            );

            $user->status = match ($statusCode) {
                $waitingStatus => 'รอตรวจ',
                $continuingStatus => 'ดูแลต่อเนื่อง',
                default => 'สถานะอื่น',
            };

            $user->weight_signal = $weightCode === null
                ? 'ไม่มีข้อมูล'
                : ($weightLabels[$weightCode] ?? "รหัสน้ำหนัก {$weightCode}");

            return $user;
        }
    );

    $pageData = static function ($page) {
        return [
            'items' => $page->items(),
            'total' => $page->total(),
            'current_page' => $page->currentPage(),
            'per_page' => $page->perPage(),
            'last_page' => $page->lastPage(),
        ];
    };

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
            'patient_list' => $pageData($users),
        ],
        'meta' => [
            'date' => $today->toDateString(),
            'timezone' => 'Asia/Bangkok',
            'database_timezone' => $dbTimezone,
        ],
    ]);
}

public function get_patient_detail(Request $request)
{
    // $patient_id = 'Ucd1d3d9310f1afd627bbd1ea729f5be5';
    // $doctorId = 'test';
    //$doctorId = Session::get('doctor_id');

    // if ($doctorId === null || $doctorId === '') {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'กรุณาเข้าสู่ระบบแพทย์',
    //     ], 401);
    // }

      $validated = $request->validate([
        'doctor_id' => 'required|string|max:255',
        'user_id' => 'required|string|max:255',

    ]);

    $doctorId = $validated['doctor_id'];
    $patient_id = $validated['user_id'];

    // $validated = $request->validate([
    //     'weight_page' => 'sometimes|integer|min:1',
    //     'plan_page' => 'sometimes|integer|min:1',
    //     'issue_page' => 'sometimes|integer|min:1',
    // ]);

    // ตรวจสิทธิ์แพทย์ก่อน Query ข้อมูลสุขภาพ
    $patient = DB::table('users_register as p')
        ->where('p.user_id', $patient_id)
        ->whereNull('p.deleted_at')
        ->whereExists(function ($query) use ($doctorId) {
            $query->selectRaw('1')
                ->from('personal_doctor_mom as pd')
                ->whereColumn('pd.user_id', 'p.user_id')
                ->where('pd.doctor_id', $doctorId)
                ->whereNull('pd.deleted_at');
        })
        ->select(
            'p.id',
            'p.user_id',
            'p.user_name',
            'p.hospital_num',
            'p.user_age',
            'p.preg_week',
            'p.due_date',
            'p.user_Pre_weight',
            'p.user_weight',
            'p.weight_status',
            'p.compli_diabete',
            'p.compli_hypertension',
            'p.compli_preterm_birth',
            'p.updated_at'
        )
        ->first();

    if (!$patient) {
        return response()->json([
            'success' => false,
            'message' => 'ไม่พบผู้รับบริการหรือไม่มีสิทธิ์เข้าถึง',
        ], 404);
    }

    $id = $patient->id;
    $lineUserId = $patient->user_id;

    // ต้องตรวจค่าเหล่านี้ให้ตรงกับระบบจริง
    $aiCompleted = 'completed';
    $reviewPending = 'pending';
    $complicationYes = 1;

    $pageData = static function ($page) {
        return [
            'items' => $page->items(),
            'total' => $page->total(),
            'current_page' => $page->currentPage(),
            'per_page' => $page->perPage(),
            'last_page' => $page->lastPage(),
        ];
    };

    // -----------------------------------------
    // 1. ข้อมูลพื้นฐาน
    // อายุและอายุครรภ์เป็นค่าที่บันทึกไว้ในระบบ
    // -----------------------------------------

    $basicInfo = [
        'id' => (int) $id,
        'name' => $patient->user_name,
        'hn' => $patient->hospital_num,
        'age' => $patient->user_age,
        'gestational_week' => $patient->preg_week,
        'due_date' => $patient->due_date,
        'is_first_pregnancy' => null,
        'risk_level' => null,
        'pre_pregnancy_weight' => $patient->user_Pre_weight,
        'weight' => $patient->user_weight,
        'weight_status_code' => $patient->weight_status,
        // 'weight_status_code' => $patient->weight_status === null
        //         ? 'ไม่มีข้อมูล'
        //         : ($weightLabels[$patient->weight_status] ?? "รหัสน้ำหนัก {$patient->weight_status}"),
        'updated_at' => $patient->updated_at,
    ];

    // -----------------------------------------
    // 2. แนวโน้มน้ำหนัก
    // วันที่ใช้เวลาบันทึก เพราะไม่มีวันที่ชั่งแยก
    // -----------------------------------------

    $weights = DB::table('RecordOfPregnancy')
        ->where('user_id', $lineUserId)
        ->whereNull('deleted_at')
        ->where('deleted_status', 0)
        ->select(
            'id',
            'created_at as recorded_at',
            'preg_week as gestational_week',
            'preg_weight as weight',
            'weight_status as weight_status_code'
        )
        ->orderBy('created_at')
        ->orderBy('id')
        ->paginate(
            100,
            ['*'],
            'weight_page',
            (int) ($validated['weight_page'] ?? 1)
        );

    $weights->getCollection()->transform(function ($row) {
        return [
            'id' => $row->id,
            'recorded_at' => $row->recorded_at,
            'gestational_week' => $row->gestational_week,
            'weight' => $row->weight,
          //  'weight_status_code' => $row->weight_status_code
            'weight_status_code' => $row->weight_status_code === null
                ? 'ไม่มีข้อมูล'
                : ($weightLabels[$row->weight_status_code] ?? "รหัสน้ำหนัก {$row->weight_status_code}"),
            
            // ยังไม่มีเกณฑ์ช่วงน้ำหนักแนะนำในฐานข้อมูล
            'recommended_weight_range' => [
                'min' => null,
                'max' => null,
                'unit' => 'kg',
            ],
        ];
    });

    // -----------------------------------------
    // 3. สรุปการบันทึกสุขภาพ
    // นับทั้งหมด + วันที่บันทึกล่าสุด
    // -----------------------------------------

    $summarize = static function ($query) {
        $row = (clone $query)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('MAX(created_at) as last_recorded_at')
            ->first();

        return [
            'total' => (int) $row->total,
            'last_recorded_at' => $row->last_recorded_at,
        ];
    };

    $foodQuery = DB::table('meal_transactions')
        ->where('user_id', $id);

    $vitaminQuery = DB::table('tracker')
        ->where('user_id', $lineUserId)
        ->whereNull('deleted_at')
        ->whereNotNull('vitamin')
        ->whereRaw("TRIM(vitamin) <> ''");

    $exerciseQuery = DB::table('exercise_logs')
        ->where('user_id', $id)
        ->whereNull('deleted_at');

    $fetalQuery = DB::table('fetal_movement')
        ->where('user_id', $lineUserId)
        ->whereNull('deleted_at');

    $quizQuery = DB::table('quizstep')
        ->where('user_id', $lineUserId)
        ->whereNull('deleted_at');

    $healthSummary = [
        'food' => $summarize($foodQuery),
        'vitamin' => $summarize($vitaminQuery),
        'exercise' => $summarize($exerciseQuery),
        'fetal_movement' => $summarize($fetalQuery),
        'questions' => [
            'patient_questions' => [
                'total' => null,
                'last_recorded_at' => null,
            ],
            'questionnaire_answers' => $summarize($quizQuery),
        ],
    ];

    // -----------------------------------------
    // 4. ประเด็นที่ต้องเฝ้าระวัง
    // ไม่กำหนดระดับความสำคัญทางคลินิกเอง
    // -----------------------------------------

    $complicationIssues = [];

    foreach ([
        'compli_diabete' => 'มีข้อมูลภาวะเบาหวาน',
        'compli_hypertension' => 'มีข้อมูลภาวะความดันโลหิตสูง',
        'compli_preterm_birth' => 'มีข้อมูลภาวะคลอดก่อนกำหนด',
    ] as $field => $detail) {
        if ((int) $patient->{$field} === $complicationYes) {
            $complicationIssues[] = [
                'type' => $field,
                'detail' => $detail,
                'priority' => null,
                'status' => null,
                'source' => 'users_register',
                'updated_at' => $patient->updated_at,
            ];
        }
    }

    $foodIssues = (clone $foodQuery)
        ->where('ai_status', $aiCompleted)
        ->where('review_status', $reviewPending)
        ->select(
            'id',
            'meal_date',
            'meal_type',
            'gdm_risk',
            'recommendation',
            'review_status',
            'created_at',
            'updated_at'
        )
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->paginate(
            20,
            ['*'],
            'issue_page',
            (int) ($validated['issue_page'] ?? 1)
        );

    $foodIssues->getCollection()->transform(function ($meal) {
        return [
            'type' => 'food_review',
            'reference_id' => $meal->id,
            'detail' => 'อาหารที่ AI วิเคราะห์แล้วรอตรวจสอบ',
            'meal_date' => $meal->meal_date,
            'meal_type' => $meal->meal_type,
            'ai_risk_level' => $meal->gdm_risk,
            'recommendation' => $meal->recommendation,
            'priority' => null,
            'status' => $meal->review_status,
            'source' => 'meal_transactions',
            'updated_at' => $meal->updated_at ?? $meal->created_at,
        ];
    });

    // -----------------------------------------
    // 5. แผนดูแลและนัดหมาย
    // มีเฉพาะแผนอินซูลินใน SQL ปัจจุบัน
    // -----------------------------------------

    $plans = DB::table('insulin_plans as ip')
        ->join('insulins as i', 'i.id', '=', 'ip.insulin_id')
        ->where('ip.user_id', $id)
        ->select(
            'ip.id',
            'ip.start_date as date',
            'ip.end_date',
            'i.name_th as item',
            'ip.dose_units',
            'ip.injection_period',
            'ip.injection_time',
            'ip.prescribed_by',
            'ip.status',
            'ip.note',
            'ip.updated_at'
        )
        ->orderByDesc('ip.start_date')
        ->orderByDesc('ip.id')
        ->paginate(
            20,
            ['*'],
            'plan_page',
            (int) ($validated['plan_page'] ?? 1)
        );

    $plans->getCollection()->transform(function ($plan) {
        return [
            'id' => $plan->id,
            'type' => 'insulin_plan',
            'date' => $plan->date,
            'end_date' => $plan->end_date,
            'item' => $plan->item,
            'dose_units' => $plan->dose_units,
            'injection_period' => $plan->injection_period,
            'injection_time' => $plan->injection_time,
            // เป็นผู้สั่งแผนตามข้อมูลที่มี ไม่ใช่ผู้รับผิดชอบนัดหมาย
            'prescribed_by' => $plan->prescribed_by,
            'responsible_person' => null,
            'status' => $plan->status,
            'note' => $plan->note,
            'updated_at' => $plan->updated_at,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => [
            'basic_info' => $basicInfo,
            'weight_trend' => $pageData($weights),
            'health_record_summary' => $healthSummary,
            'monitoring_issues' => [
                'complications' => $complicationIssues,
                'food_reviews' => $pageData($foodIssues),
            ],
            'care_plans_and_appointments' => [
                'care_plans' => $pageData($plans),
                // null หมายถึงยังไม่มีแหล่งข้อมูล
                'appointments' => null,
            ],
        ],
    ]);
}


public function patient_health_history(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'sometimes|string|max:255',
        'doctor_id' => 'sometimes|string|max:255',
        'exercise_page' => 'sometimes|integer|min:1',
        'fetal_page' => 'sometimes|integer|min:1',
    ]);

    // ใช้ Session เมื่อมี; รับ doctor_id สำหรับทดสอบเฉพาะ local
    $doctorId =  $validated['doctor_id'];
    $patient_id = $validated['user_id'];

    if (($doctorId === null || $doctorId === '')
        && app()->environment('local')) {
        $doctorId = $validated['doctor_id'] ?? null;
    }

    if ($doctorId === null || $doctorId === '') {
        return response()->json([
            'success' => false,
            'message' => 'กรุณาเข้าสู่ระบบแพทย์',
        ], 401);
    }

    // ตรวจว่าคนไข้เป็นของแพทย์ก่อนดึงข้อมูลสุขภาพ
    $patient = DB::table('users_register as p')
        ->where('p.user_id', $patient_id)
        ->whereNull('p.deleted_at')
        ->whereExists(function ($query) use ($doctorId) {
            $query->selectRaw('1')
                ->from('personal_doctor_mom as pd')
                ->whereColumn('pd.user_id', 'p.user_id')
                ->where('pd.doctor_id', $doctorId)
                ->whereNull('pd.deleted_at');
        })
        ->select(
            'p.id',
            'p.user_id',
            'p.user_name',
            'p.hospital_num',
            'p.user_age',
            'p.preg_week',
            'p.user_height',
            'p.user_Pre_weight',
            'p.user_weight'
        )
        ->first();

    if (!$patient) {
        return response()->json([
            'success' => false,
            'message' => 'ไม่พบผู้รับบริการหรือไม่มีสิทธิ์เข้าถึง',
        ], 404);
    }

    $id = $patient->id;
    $lineUserId = $patient->user_id;

    // ฟิลด์น้ำหนัก/ส่วนสูงบางส่วนเป็น varchar
    $number = static function ($value) {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return is_numeric($value) ? (float) $value : null;
    };

    $pageData = static function ($page) {
        return [
            'items' => $page->items(),
            'total' => $page->total(),
            'current_page' => $page->currentPage(),
            'per_page' => $page->perPage(),
            'last_page' => $page->lastPage(),
        ];
    };

    // ----------------------------------
    // 1. ประวัติน้ำหนัก
    // ส่งทั้งหมดสำหรับกราฟและคำนวณการเปลี่ยนแปลง
    // ----------------------------------

    $weightRows = DB::table('RecordOfPregnancy')
        ->where('user_id', $lineUserId)
        ->whereNull('deleted_at')
        ->where('deleted_status', 0)
        ->orderBy('created_at')
        ->orderBy('id')
        ->get([
            'id',
            'created_at',
            'preg_week',
            'preg_weight',
            'weight_status',
        ]);

    $preWeight = $number($patient->user_Pre_weight);
    $previousWeight = null;

    $weightHistory = $weightRows->map(
        function ($row) use (
            $number,
            $preWeight,
            &$previousWeight
        ) {
            $weight = $number($row->preg_weight);

            $change = $weight !== null && $previousWeight !== null
                ? round($weight - $previousWeight, 2)
                : null;

            if ($weight !== null) {
                $previousWeight = $weight;
            }

            return [
                'id' => $row->id,
                'date' => $row->created_at === null
                    ? null
                    : substr($row->created_at, 0, 10),
                'gestational_week' => $row->preg_week,
                'weight' => $weight,
                'unit' => 'kg',
                'target_weight' => null,
                'change_from_previous' => $change,
                'change_from_pre_pregnancy' =>
                    $weight !== null && $preWeight !== null
                        ? round($weight - $preWeight, 2)
                        : null,
                'weight_status_code' => $row->weight_status,
                'recorded_at' => $row->created_at,
            ];
        }
    );

    // ----------------------------------
    // 2. ข้อมูลพื้นฐาน + BMI
    // ใช้บันทึกน้ำหนักล่าสุดที่เป็นตัวเลข
    // ----------------------------------

    $latestValidWeight = $weightRows->last(
        fn ($row) => $number($row->preg_weight) !== null
    );

    $weight = $latestValidWeight
        ? $number($latestValidWeight->preg_weight)
        : $number($patient->user_weight);

    $heightCm = $number($patient->user_height);
    $heightM = $heightCm !== null && $heightCm > 0
        ? $heightCm / 100
        : null;

    $calculateBmi = static function ($weight) use ($heightM) {
        return $weight !== null && $weight > 0 && $heightM !== null
            ? round($weight / ($heightM * $heightM), 2)
            : null;
    };

    $basicInfo = [
        'id' => (int) $id,
        'name' => $patient->user_name,
        'hn' => $patient->hospital_num,
        'age' => $patient->user_age,
        'gestational_week' => $patient->preg_week,
        'height_cm' => $heightCm,
        'weight' => $weight,
        'weight_unit' => 'kg',
        'weight_source' => $latestValidWeight
            ? 'RecordOfPregnancy'
            : 'users_register',
        'weight_recorded_at' => $latestValidWeight?->created_at,
        'bmi' => $calculateBmi($weight),
        'pre_pregnancy_bmi' => $calculateBmi($preWeight),
    ];

    // ----------------------------------
    // 3. ประวัติออกกำลังกาย
    // user_id เชื่อมกับ users_register.id
    // ----------------------------------

    $exerciseBase = DB::table('exercise_logs')
        ->where('user_id', $id)
        ->whereNull('deleted_at');

    $exercises = (clone $exerciseBase)
        ->select(
            'id',
            'exercise_date as date',
            'start_time as time',
            'exercise_type as type',
            'duration_minutes',
            'intensity',
            'note',
            'created_at as recorded_at'
        )
        ->orderByDesc('exercise_date')
        ->orderByDesc('id')
        ->paginate(
            20,
            ['*'],
            'exercise_page',
            (int) ($validated['exercise_page'] ?? 1)
        );

    $exercises->getCollection()->transform(function ($row) {
        $row->status = null;
        return $row;
    });

    // ----------------------------------
    // 4. ประวัติลูกดิ้น
    // แยกเช้า/กลางวัน/เย็นในแต่ละวัน
    // ----------------------------------

    $fetalBase = DB::table('fetal_movement')
        ->where('user_id', $lineUserId)
        ->whereNull('deleted_at');

    $fetalHistory = (clone $fetalBase)
        ->orderByDesc('date')
        ->orderByDesc('id')
        ->paginate(
            20,
            [
                'id',
                'date',
                'preg_week',
                'num_morning',
                'num_noon',
                'num_evening',
                'created_at',
            ],
            'fetal_page',
            (int) ($validated['fetal_page'] ?? 1)
        );

    $fetalHistory->getCollection()->transform(function ($row) {
        $periods = [];

        foreach ([
            'morning' => 'num_morning',
            'noon' => 'num_noon',
            'evening' => 'num_evening',
        ] as $period => $field) {
            $periods[] = [
                'period' => $period,
                // null = ไม่มีข้อมูล; 0 = บันทึกว่าได้ศูนย์ครั้ง
                'count' => $row->{$field} === null
                    ? null
                    : (int) $row->{$field},
                'counting_duration_minutes' => null,
                'is_abnormal' => null,
            ];
        }

        return [
            'id' => $row->id,
            'date' => $row->date,
            'gestational_week' => $row->preg_week,
            'periods' => $periods,
            'recorded_at' => $row->created_at,
        ];
    });

    // ----------------------------------
    // 5. รายการล่าสุด 20 รายการ
    // น้ำหนัก / ออกกำลังกาย / ลูกดิ้น /
    // พลังงานอาหาร / บันทึกวิตามิน
    // ----------------------------------

    $recent = collect();

    foreach ($weightRows->sortByDesc('created_at')->take(20) as $row) {
        $recent->push([
            'id' => $row->id,
            'activity_type' => 'weight',
            'value' => $number($row->preg_weight),
            'unit' => 'kg',
            'recorded_at' => $row->created_at,
        ]);
    }

    foreach ((clone $exerciseBase)
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->limit(20)
        ->get(['id', 'exercise_type', 'duration_minutes', 'created_at']) as $row) {
        $recent->push([
            'id' => $row->id,
            'activity_type' => 'exercise',
            'detail' => $row->exercise_type,
            'value' => $row->duration_minutes,
            'unit' => 'minutes',
            'recorded_at' => $row->created_at,
        ]);
    }

    foreach ((clone $fetalBase)
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->limit(20)
        ->get() as $row) {
        foreach ([
            'morning' => 'num_morning',
            'noon' => 'num_noon',
            'evening' => 'num_evening',
        ] as $period => $field) {
            if ($row->{$field} !== null) {
                $recent->push([
                    'id' => $row->id,
                    'activity_type' => 'fetal_movement',
                    'period' => $period,
                    'value' => (int) $row->{$field},
                    'unit' => 'times',
                    'recorded_at' => $row->created_at,
                ]);
            }
        }
    }

    $latestMeals = DB::table('meal_transactions')
        ->where('user_id', $id)
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->limit(20)
        ->get(['id', 'meal_type', 'total_calorie', 'created_at']);

    foreach ($latestMeals as $row) {
        $recent->push([
            'id' => $row->id,
            'activity_type' => 'food',
            'detail' => $row->meal_type,
            'value' => $number($row->total_calorie),
            'unit' => 'kcal',
            'recorded_at' => $row->created_at,
        ]);
    }

    // ใช้ tracker เพราะ vitamin_logs ยังไม่มี user_id
    $latestVitamins = DB::table('tracker')
        ->where('user_id', $lineUserId)
        ->whereNull('deleted_at')
        ->whereNotNull('vitamin')
        ->whereRaw("TRIM(vitamin) <> ''")
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->limit(20)
        ->get(['id', 'vitamin', 'created_at']);

    foreach ($latestVitamins as $row) {
        $recent->push([
            'id' => $row->id,
            'activity_type' => 'vitamin',
            'value' => $row->vitamin,
            // ค่านี้เป็นค่าที่บันทึก ไม่ใช่จำนวนเม็ด
            'unit' => null,
            'recorded_at' => $row->created_at,
        ]);
    }

    $recent = $recent
        ->sort(function ($a, $b) {
            $timeOrder = strcmp(
                $b['recorded_at'] ?? '',
                $a['recorded_at'] ?? ''
            );

            if ($timeOrder !== 0) {
                return $timeOrder;
            }

            $typeOrder = strcmp($a['activity_type'], $b['activity_type']);

            return $typeOrder !== 0
                ? $typeOrder
                : ($b['id'] <=> $a['id']);
        })
        ->take(20)
        ->values();

    return response()->json([
        'success' => true,
        'data' => [
            'basic_info' => $basicInfo,
            'weight_history' => $weightHistory->values(),
            'exercise_history' => $pageData($exercises),
            'fetal_movement_history' => $pageData($fetalHistory),
            'recent_activities' => $recent,
        ],
    ]);
}


public function patient_nutrition_history(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'sometimes|string|max:255',
        'start_date' => 'required|date_format:Y-m-d',
        'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        'meal_page' => 'sometimes|integer|min:1',
        'vitamin_page' => 'sometimes|integer|min:1',
        'doctor_id' => 'sometimes|string|max:255',
    ]);

    $start = CarbonImmutable::parse($validated['start_date']);
    $end = CarbonImmutable::parse($validated['end_date']);
    $totalDays = (int) $start->diffInDays($end) + 1;

    if ($totalDays > 366) {
        return response()->json([
            'success' => false,
            'message' => 'เลือกช่วงวันที่ได้ไม่เกิน 366 วัน',
        ], 422);
    }

    $doctorId = Session::get('doctor_id');

    // รับ doctor_id แบบไม่ใช้ Session เฉพาะทดสอบ local
    if (($doctorId === null || $doctorId === '')
        && app()->environment('local')) {
        $doctorId = $validated['doctor_id'] ?? null;
    }

    if ($doctorId === null || $doctorId === '') {
        return response()->json([
            'success' => false,
            'message' => 'กรุณาเข้าสู่ระบบแพทย์',
        ], 401);
    }

    $patient = DB::table('users_register as p')
        ->where('p.user_id', $validated['user_id'])
        ->whereNull('p.deleted_at')
        ->whereExists(function ($query) use ($doctorId) {
            $query->selectRaw('1')
                ->from('personal_doctor_mom as pd')
                ->whereColumn('pd.user_id', 'p.user_id')
                ->where('pd.doctor_id', $doctorId)
                ->whereNull('pd.deleted_at');
        })
        ->select(
            'p.id',
            'p.user_id',
            'p.user_name',
            'p.hospital_num',
            'p.user_age',
            'p.preg_week',
            'p.due_date',
            'p.calorie'
        )
        ->first();

    if (!$patient) {
        return response()->json([
            'success' => false,
            'message' => 'ไม่พบผู้รับบริการหรือไม่มีสิทธิ์เข้าถึง',
        ], 404);
    }

    $number = static function ($value) {
        if ($value === null || !is_numeric(trim((string) $value))) {
            return null;
        }

        return (float) trim((string) $value);
    };

    $pageData = static function ($page) {
        return [
            'items' => $page->items(),
            'total' => $page->total(),
            'current_page' => $page->currentPage(),
            'per_page' => $page->perPage(),
            'last_page' => $page->lastPage(),
        ];
    };

    $startDate = $validated['start_date'];
    $endDate = $validated['end_date'];

    // ----------------------------------
    // 1. เป้าหมายโภชนาการ
    // เป็นเป้าหมายปัจจุบัน ไม่ใช่ประวัติเป้าหมาย
    // ----------------------------------

    $energyTarget = $number($patient->calorie);

    if ($energyTarget !== null && $energyTarget <= 0) {
        $energyTarget = null;
    }

    $plan = $energyTarget === null
        ? null
        : DB::table('meal_planing')
            ->where('caloric_level', $energyTarget)
            ->orderBy('id')
            ->first();

    /*
     * เปิดเป็น true เมื่อยืนยันว่า c / p / f
     * คือเปอร์เซ็นต์พลังงานจากสารอาหารจริง
     */
    $percentagesConfirmed = (bool) config(
        'dashboard.meal_plan_percentages_confirmed',
        false
    );

    $canCalculate = $plan !== null && $percentagesConfirmed;

    $targets = [
        'energy_kcal' => $energyTarget,

        'carbohydrate_g' => $canCalculate
            ? round($energyTarget * $plan->c / 100 / 4, 2)
            : null,

        'protein_g' => $canCalculate
            ? round($energyTarget * $plan->p / 100 / 4, 2)
            : null,

        'fat_g' => $canCalculate
            ? round($energyTarget * $plan->f / 100 / 9, 2)
            : null,

        'fiber_g' => null,
        'scope' => 'current_target',
    ];

    // ----------------------------------
    // 2. ประวัติมื้ออาหารตามวันที่รับประทาน
    // ----------------------------------

    $mealBase = DB::table('meal_transactions')
        ->where('user_id', $patient->user_id)
        ->whereBetween('meal_date', [$startDate, $endDate]);

    $meals = (clone $mealBase)
        ->orderByDesc('meal_date')
        ->orderByDesc('meal_time')
        ->orderByDesc('id')
        ->paginate(
            20,
            ['*'],
            'meal_page',
            (int) ($validated['meal_page'] ?? 1)
        );

    // ดึงรายการอาหารครั้งเดียว ป้องกัน Query ทีละมื้อ
    $mealIds = $meals->getCollection()->pluck('id');

    $itemsByMeal = DB::table('meal_items')
        ->whereIn('meal_transaction_id', $mealIds)
        ->whereNull('deleted_at')
        ->orderBy('id')
        ->get([
            'id',
            'meal_transaction_id',
            'food_name',
            'portion',
            'unit',
            'weight_g',
            'calorie',
            'carbohydrate',
            'protein',
            'fat',
            'fiber',
            'confidence_score',
            'created_source',
            'last_updated_source',
        ])
        ->groupBy('meal_transaction_id');

    $meals->getCollection()->transform(
        function ($meal) use ($itemsByMeal, $number) {
            $items = $itemsByMeal->get($meal->id, collect());

            return [
                'id' => $meal->id,
                'date' => $meal->meal_date,
                'time' => $meal->meal_time,
                'meal' => $meal->meal_type,
                'image_url' => $meal->image_url,
                'energy_kcal' => $number($meal->total_calorie),
                'review_status' => $meal->review_status,

                'foods' => $items->map(function ($item) use ($number) {
                    return [
                        'id' => $item->id,
                        'name' => $item->food_name,
                        'portion' => $number($item->portion),
                        'unit' => $item->unit,
                        'weight_g' => $number($item->weight_g),
                        'energy_kcal' => $number($item->calorie),
                        'nutrients' => [
                            'carbohydrate_g' => $number($item->carbohydrate),
                            'protein_g' => $number($item->protein),
                            'fat_g' => $number($item->fat),
                            'fiber_g' => $number($item->fiber),
                        ],
                        // ส่งค่าตาม DB ยังไม่สมมติว่าเป็น 0–1 หรือ 0–100
                        'confidence_score' => $number($item->confidence_score),
                        'created_source' => $item->created_source,
                        'last_updated_source' => $item->last_updated_source,
                    ];
                })->values(),

                'analysis' => [
                    'ai_status' => $meal->ai_status,
                    'value_scope' => 'latest_stored_values',
                    'energy_kcal' => $number($meal->total_calorie),
                    'nutrients' => [
                        'carbohydrate_g' => $number($meal->total_carbohydrate),
                        'protein_g' => $number($meal->total_protein),
                        'fat_g' => $number($meal->total_fat),
                        'fiber_g' => $number($meal->total_fiber),
                    ],
                    // ไม่มี confidence ระดับมื้อใน SQL
                    'meal_confidence_score' => null,
                    'gdm_risk' => $meal->gdm_risk,
                    'recommendation' => $meal->recommendation,
                ],
                'recorded_at' => $meal->created_at,
                'updated_at' => $meal->updated_at,
            ];
        }
    );

    // ----------------------------------
    // 3. ประวัติวิตามิน
    // tracker.date ต้องเก็บเป็น YYYY-MM-DD
    // ----------------------------------

    $vitaminBase = DB::table('tracker')
        ->where('user_id', $patient->user_id)
        ->whereNull('deleted_at')
        ->whereBetween('date', [$startDate, $endDate])
        ->whereNotNull('vitamin')
        ->whereRaw("TRIM(vitamin) <> ''");

    $vitaminRecordedDays = (clone $vitaminBase)
        ->distinct()
        ->count('date');

    $vitamins = (clone $vitaminBase)
        ->orderByDesc('date')
        ->orderByDesc('id')
        ->paginate(
            20,
            ['id', 'date', 'vitamin', 'created_at'],
            'vitamin_page',
            (int) ($validated['vitamin_page'] ?? 1)
        );

    $vitamins->getCollection()->transform(function ($row) {
        return [
            'id' => $row->id,
            'vitamin_type' => null,
            'date' => $row->date,
            'status' => null,
            'raw_value' => $row->vitamin,
            'recorded_at' => $row->created_at,
        ];
    });

    // ----------------------------------
    // 4. สรุปย้อนหลัง
    // ใช้เฉพาะมื้อที่ AI วิเคราะห์เสร็จ
    // เพื่อไม่เอาค่า default 0 ของมื้อรอวิเคราะห์มารวม
    // ----------------------------------

    $aiCompleted = config('dashboard.ai_completed_status', 'completed');

    $daily = (clone $mealBase)
        ->where('ai_status', $aiCompleted)
        ->select('meal_date')
        ->selectRaw('COUNT(*) as meal_count')
        ->selectRaw('SUM(total_calorie) as energy_kcal')
        ->selectRaw('SUM(total_carbohydrate) as carbohydrate_g')
        ->selectRaw('SUM(total_protein) as protein_g')
        ->selectRaw('SUM(total_fat) as fat_g')
        ->selectRaw('SUM(total_fiber) as fiber_g')
        ->groupBy('meal_date')
        ->orderBy('meal_date')
        ->get();

    $recordedDays = $daily->count();

    $nutrientFields = [
        'energy_kcal',
        'carbohydrate_g',
        'protein_g',
        'fat_g',
        'fiber_g',
    ];

    $averageRecordedDays = [];
    $totalNutrition = [];

    foreach ($nutrientFields as $field) {
        $sum = (float) $daily->sum($field);

        $totalNutrition[$field] = round($sum, 2);

        $averageRecordedDays[$field] = $recordedDays > 0
            ? round($sum / $recordedDays, 2)
            : null;
    }

    /*
     * จำนวนวันที่ถึงเป้าหมาย:
     * ใช้เกณฑ์ช่วงพลังงานจาก configuration ที่ทีมกำหนด
     * ไม่กำหนดเกณฑ์ทางคลินิกเอง
     *
     * ตัวอย่าง tolerance 10 = ภายใน ±10% ของเป้าหมาย
     * หากยังไม่ได้กำหนด ส่ง null
     */
    $tolerance = config('dashboard.energy_target_tolerance_percent');

    $targetRange = null;
    $daysReachingTarget = null;

    if ($energyTarget !== null
        && is_numeric($tolerance)
        && (float) $tolerance >= 0
        && (float) $tolerance <= 100) {
        $fraction = (float) $tolerance / 100;

        $min = $energyTarget * (1 - $fraction);
        $max = $energyTarget * (1 + $fraction);

        $targetRange = [
            'min_kcal' => round($min, 2),
            'max_kcal' => round($max, 2),
        ];

        $daysReachingTarget = $daily->filter(
            fn ($row) =>
                (float) $row->energy_kcal >= $min
                && (float) $row->energy_kcal <= $max
        )->count();
    }

    return response()->json([
        'success' => true,
        'data' => [
            'basic_info' => [
                'id' => (int) $patient->id,
                'name' => $patient->user_name,
                'hn' => $patient->hospital_num,
                'age' => $patient->user_age,
                'gestational_week' => $patient->preg_week,
                'due_date' => $patient->due_date,
            ],

            'selected_period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => $totalDays,
            ],

            'nutrition_targets' => $targets,
            'meal_history' => $pageData($meals),

            'vitamin_history' => [
                'records' => $pageData($vitamins),
                'recorded_days' => $vitaminRecordedDays,
                'days_without_record' => $totalDays - $vitaminRecordedDays,
                // ไม่มีบันทึก ≠ ไม่ได้กิน
                'missed_intake_days' => null,
            ],

            'retrospective_summary' => [
                'total_meals' => (clone $mealBase)->count(),
                'analyzed_meals' => (int) $daily->sum('meal_count'),
                'days_with_analyzed_meals' => $recordedDays,
                'total_nutrition' => $totalNutrition,
                'average_per_recorded_day' => $averageRecordedDays,
                'days_reaching_energy_target' => $daysReachingTarget,
                'energy_target_range' => $targetRange,
                'daily_totals' => $daily,
            ],
        ],
    ]);
}


public function patient_diabetes_history(Request $request)
{
    $input = $request->validate([
        'user_id' => 'sometimes|string|max:255',
        'start_date' => 'required|date_format:Y-m-d',
        'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        'doctor_id' => 'sometimes|string|max:255',
    ]);

    $start = CarbonImmutable::parse($input['start_date'], 'Asia/Bangkok');
    $end = CarbonImmutable::parse($input['end_date'], 'Asia/Bangkok');

    if ($start->diffInDays($end) > 365) {
        return response()->json([
            'success' => false,
            'message' => 'เลือกช่วงวันที่ได้ไม่เกิน 366 วัน',
        ], 422);
    }

    $doctorId = Session::get('doctor_id');

    // ทดสอบแบบส่ง doctor_id ได้เฉพาะ local
    if (($doctorId === null || $doctorId === '')
        && app()->environment('local')) {
        $doctorId = $input['doctor_id'] ?? null;
    }

    if ($doctorId === null || $doctorId === '') {
        return response()->json([
            'success' => false,
            'message' => 'กรุณาเข้าสู่ระบบแพทย์',
        ], 401);
    }

    // 1. ผู้รับบริการและสิทธิ์เข้าถึง
    $patient = DB::table('users_register as p')
        ->where('p.user_id', $input['user_id'])
        ->whereNull('p.deleted_at')
        ->whereExists(function ($query) use ($doctorId) {
            $query->selectRaw('1')
                ->from('personal_doctor_mom as pd')
                ->whereColumn('pd.user_id', 'p.user_id')
                ->where('pd.doctor_id', $doctorId)
                ->whereNull('pd.deleted_at');
        })
        ->select(
            'p.id',
            'p.user_id',
            'p.user_name',
            'p.hospital_num',
            'p.user_age',
            'p.preg_week',
            'p.compli_diabete',
            'p.compli_hypertension',
            'p.compli_preterm_birth'
        )
        ->first();

    if (!$patient) {
        return response()->json([
            'success' => false,
            'message' => 'ไม่พบผู้รับบริการหรือไม่มีสิทธิ์เข้าถึง',
        ], 404);
    }

    // เปลี่ยนเป็น UTC ถ้าฐานข้อมูลเก็บเวลา UTC
    $dbTimezone = 'Asia/Bangkok';

    $from = $start->setTimezone($dbTimezone)->toDateTimeString();
    $until = $end->addDay()
        ->setTimezone($dbTimezone)
        ->toDateTimeString();

    $mealLabels = [
        1 => 'เช้า',
        2 => 'กลางวัน',
        3 => 'เย็น',
    ];

    $periodMap = [
        1 => 'before_meal',
        3 => 'after_meal_1h',
        4 => 'after_meal_2h',
    ];

    $periodLabels = [
        'before_meal' => 'ก่อนอาหาร',
        'after_meal_1h' => 'หลังอาหาร 1 ชั่วโมง',
        'after_meal_2h' => 'หลังอาหาร 2 ชั่วโมง',
        'bedtime' => 'ก่อนนอน',
        'unknown' => 'ไม่ทราบช่วงตรวจ',
    ];

    $targets = [
        'before_meal' => ['min' => 60, 'max' => 95],
        'after_meal_1h' => ['min' => 60, 'max' => 140],
        'after_meal_2h' => ['min' => 60, 'max' => 120],
    ];

    $statusLabels = [
        'low' => 'ต่ำกว่าเกณฑ์',
        'normal' => 'ปกติ',
        'high' => 'เกินเกณฑ์',
        'unknown' => 'ไม่ทราบเกณฑ์ช่วงตรวจ',
    ];

    // 2. ค่าน้ำตาล: เชื่อมด้วย users_register.user_id
    $readings = DB::table('blood_sugar')
        ->where('user_id', $patient->user_id)
        ->whereNull('deleted_at')
        ->where('datetime', '>=', $from)
        ->where('datetime', '<', $until)
        ->orderBy('datetime')
        ->orderBy('id')
        ->get([
            'id',
            'datetime',
            'meal',
            'time_of_day',
            'blood_sugar',
            'preg_week',
            'created_at',
        ])
        ->map(function ($row) use (
            $dbTimezone,
            $mealLabels,
            $periodMap,
            $periodLabels,
            $targets,
            $statusLabels
        ) {
            $mealCode = trim((string) $row->meal);
            $timeCode = trim((string) $row->time_of_day);

            $period = $periodMap[$timeCode] ?? 'unknown';
            $target = $targets[$period] ?? null;
            $value = (float) $row->blood_sugar;

            if ($value < 60) {
                $status = 'low';
            } elseif ($target === null) {
                $status = 'unknown';
            } elseif ($value > $target['max']) {
                $status = 'high';
            } else {
                $status = 'normal';
            }

            $datetime = CarbonImmutable::parse(
                $row->datetime,
                $dbTimezone
            )->setTimezone('Asia/Bangkok');

            return [
                'id' => $row->id,
                'date' => $datetime->toDateString(),
                'time' => $datetime->format('H:i:s'),
                'meal_code' => $mealCode,
                'meal' => $mealLabels[$mealCode] ?? 'ไม่ทราบมื้อ',
                'time_of_day_code' => $timeCode,
                'period' => $period,
                'period_label' => $periodLabels[$period],
                'value' => $value,
                'unit' => 'mg/dL',
                'target' => $target === null ? null : [
                    'min' => $target['min'],
                    'max' => $target['max'],
                    'unit' => 'mg/dL',
                ],
                'status' => $status,
                'status_label' => $statusLabels[$status],
                'gestational_week' => $row->preg_week,
                'measured_at' => $datetime->toIso8601String(),
                'recorded_at' => $row->created_at,
            ];
        });

    // ฟังก์ชันสรุปค่าที่วัด
    $summarize = static function ($items) {
        $total = $items->count();
        $low = $items->where('status', 'low')->count();
        $normal = $items->where('status', 'normal')->count();
        $high = $items->where('status', 'high')->count();

        $classified = $low + $normal + $high;

        $percent = static fn ($count) => $classified > 0
            ? round($count * 100 / $classified, 2)
            : null;

        return [
            'total' => $total,
            'classified_total' => $classified,
            'unknown_total' => $total - $classified,
            'average_mg_dl' => $total > 0
                ? round($items->avg('value'), 2)
                : null,
            'min_mg_dl' => $total > 0 ? $items->min('value') : null,
            'max_mg_dl' => $total > 0 ? $items->max('value') : null,
            'low' => [
                'count' => $low,
                'percentage' => $percent($low),
            ],
            'normal' => [
                'count' => $normal,
                'percentage' => $percent($normal),
            ],
            'high' => [
                'count' => $high,
                'percentage' => $percent($high),
            ],
        ];
    };

    // 3. กลุ่มเช้า/กลางวัน/เย็น ก่อน/หลัง และรวม
    $groupDefinitions = [
        ['morning_before', 'เช้า-ก่อน', '1', ['before_meal']],
        ['morning_after', 'เช้า-หลัง', '1',
            ['after_meal_1h', 'after_meal_2h']],
        ['noon_before', 'กลางวัน-ก่อน', '2', ['before_meal']],
        ['noon_after', 'กลางวัน-หลัง', '2',
            ['after_meal_1h', 'after_meal_2h']],
        ['evening_before', 'เย็น-ก่อน', '3', ['before_meal']],
        ['evening_after', 'เย็น-หลัง', '3',
            ['after_meal_1h', 'after_meal_2h']],
    ];

    $mealPeriodSummary = [];

    foreach ($groupDefinitions as [$key, $label, $mealCode, $periods]) {
        $items = $readings
            ->filter(fn ($item) => $item['meal_code'] === $mealCode)
            ->whereIn('period', $periods)
            ->values();

        $mealPeriodSummary[] = array_merge([
            'group' => $key,
            'label' => $label,
        ], $summarize($items));
    }

    $mealPeriodSummary[] = array_merge([
        'group' => 'all',
        'label' => 'รวม',
    ], $summarize($readings));

    // 4. แนวโน้มและสรุปตามช่วงตรวจ
    $periodSummary = [];

    foreach ($periodLabels as $period => $label) {
        $periodSummary[$period] = array_merge([
            'label' => $label,
        ], $summarize(
            $readings->where('period', $period)->values()
        ));
    }

    $afterMealSummary = $summarize(
        $readings->whereIn('period', [
            'after_meal_1h',
            'after_meal_2h',
        ])->values()
    );

    $dailyTrend = $readings
        ->groupBy('date')
        ->map(function ($items, $date) use ($summarize) {
            return [
                'date' => $date,
                'periods' => $items->groupBy('period')
                    ->map(function ($periodItems, $period) use ($summarize) {
                        return array_merge([
                            'period' => $period,
                        ], $summarize($periodItems));
                    })
                    ->values(),
            ];
        })
        ->values();

    $graphData = $readings->map(fn ($item) => [
        'id' => $item['id'],
        'datetime' => $item['measured_at'],
        'value' => $item['value'],
        'series' => $item['period'],
        'meal' => $item['meal'],
        'status' => $item['status'],
        'target' => $item['target'],
    ])->values();

    // 5. Insulin ปัจจุบัน: เชื่อมด้วย users_register.id
    $today = CarbonImmutable::now('Asia/Bangkok')->toDateString();

    $currentInsulin = DB::table('insulin_plans as ip')
        ->join('insulins as i', 'i.id', '=', 'ip.insulin_id')
        ->where('ip.user_id', $patient->id)
        ->where('ip.status', 'active')
        ->where('ip.start_date', '<=', $today)
        ->where(function ($query) use ($today) {
            $query->whereNull('ip.end_date')
                ->orWhere('ip.end_date', '>=', $today);
        })
        ->select(
            'ip.id',
            'i.name_th',
            'i.insulin_type',
            'ip.dose_units',
            'ip.injection_period',
            'ip.injection_time',
            'ip.start_date',
            'ip.end_date',
            'ip.prescribed_by',
            'ip.note'
        )
        ->orderBy('ip.injection_time')
        ->orderBy('ip.id')
        ->get()
        ->map(fn ($row) => [
            'id' => $row->id,
            'name' => $row->name_th,
            'type' => $row->insulin_type,
            'dose_units' => (float) $row->dose_units,
            'injection_period' => $row->injection_period,
            'injection_time' => $row->injection_time,
            'injection_method' => null,
            'start_date' => $row->start_date,
            'end_date' => $row->end_date,
            'prescribed_by' => $row->prescribed_by,
            'note' => $row->note,
        ]);

    // 6. ประวัติปรับยา: กรองตามเวลาบันทึก created_at
    $adjustments = DB::table('insulin_history as ih')
        ->join('insulins as i', 'i.id', '=', 'ih.insulin_id')
        ->where('ih.user_id', $patient->id)
        ->whereNull('ih.deleted_at')
        ->where('ih.created_at', '>=', $from)
        ->where('ih.created_at', '<', $until)
        ->select(
            'ih.id',
            'ih.insulin_plan_id',
            'i.name_th',
            'ih.dose_units',
            'ih.note',
            'ih.status',
            'ih.injection_date',
            'ih.injection_time',
            'ih.created_at'
        )
        ->orderByDesc('ih.created_at')
        ->orderByDesc('ih.id')
        ->get()
        ->map(function ($row) use ($dbTimezone) {
            return [
                'id' => $row->id,
                'insulin_plan_id' => $row->insulin_plan_id,
                'insulin_name' => $row->name_th,
                'old_dose_units' => null,
                'new_dose_units' => null,
                'stored_dose_units' => $row->dose_units === null
                    ? null
                    : (float) $row->dose_units,
                'reason' => null,
                'recommendation' => null,
                'prescribed_by' => null,
                'note' => $row->note,
                'status' => $row->status,
                'injection_date' => $row->injection_date,
                'injection_time' => $row->injection_time,
                'recorded_at' => CarbonImmutable::parse(
                    $row->created_at,
                    $dbTimezone
                )->setTimezone('Asia/Bangkok')->toIso8601String(),
            ];
        });

    return response()->json([
        'success' => true,
        'data' => [
            'basic_info' => [
                'id' => (int) $patient->id,
                'name' => $patient->user_name,
                'hn' => $patient->hospital_num,
                'age' => $patient->user_age,
                'gestational_week' => $patient->preg_week,
                'risk_level' => null,
                'complication_codes' => [
                    'diabetes' => $patient->compli_diabete,
                    'hypertension' => $patient->compli_hypertension,
                    'preterm_birth' => $patient->compli_preterm_birth,
                ],
            ],
            'selected_period' => [
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
                'timezone' => 'Asia/Bangkok',
            ],
            'blood_sugar_history' => $readings,
            'blood_sugar_by_meal_period' => $mealPeriodSummary,
            'trend_and_summary' => [
                'overall' => $summarize($readings),
                'by_period' => $periodSummary,
                'after_meal_combined' => $afterMealSummary,
                'daily_trend' => $dailyTrend,
                'graph_data' => $graphData,
            ],
            'current_insulin' => [
                'as_of_date' => $today,
                'items' => $currentInsulin,
            ],
            'medication_adjustment_history' => $adjustments,
        ],
    ]);
}

private function doctorMealQuery($doctorId)
{
    return DB::table('meal_transactions as m')
        ->join('users_register as p', 'p.user_id', '=', 'm.user_id')
        ->whereNull('p.deleted_at')
        ->whereExists(function ($query) use ($doctorId) {
            $query->selectRaw('1')
                ->from('personal_doctor_mom as pd')
                ->whereColumn('pd.user_id', 'p.user_id')
                ->where('pd.doctor_id', $doctorId)
                ->whereNull('pd.deleted_at');
        });
}

public function food_review_queue(Request $request)
{
    $input = $request->validate([
        'doctor_id' => 'sometimes|string|max:255',
        'start_date' => 'nullable|date_format:Y-m-d',
        'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        'review_status' => 'sometimes|string|max:30',
        'page' => 'sometimes|integer|min:1',
        'per_page' => 'sometimes|integer|min:1|max:100',
    ]);

   // $doctorId = Session::get('doctor_id');
     $doctorId = $input['doctor_id'];

    // if (($doctorId === null || $doctorId === '')
    //     && app()->environment('local')) {
    //     $doctorId = $input['doctor_id'] ?? null;
    // }

    // if ($doctorId === null || $doctorId === '') {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'กรุณาเข้าสู่ระบบแพทย์',
    //     ], 401);
    // }

    // ค่าเฉลี่ย confidence ของรายการอาหารที่ยังไม่ถูกลบ
    $confidenceQuery = DB::table('meal_items')
        ->whereNull('deleted_at')
        ->select('meal_transaction_id')
        ->selectRaw('AVG(confidence_score) as confidence_average')
        ->selectRaw('COUNT(confidence_score) as confidence_item_count')
        ->selectRaw('COUNT(*) as food_item_count')
        ->groupBy('meal_transaction_id');

    $query = $this->doctorMealQuery($doctorId)
        ->leftJoinSub($confidenceQuery, 'c', function ($join) {
            $join->on('c.meal_transaction_id', '=', 'm.id');
        })
        // เปลี่ยนให้ตรงกับค่าที่ระบบใช้จริง
        ->where('m.ai_status', 'completed')
        ->where(
            'm.review_status',
            $input['review_status'] ?? 'pending'
        );

    if (!empty($input['start_date'])) {
        $query->where('m.meal_date', '>=', $input['start_date']);
    }

    if (!empty($input['end_date'])) {
        $query->where('m.meal_date', '<=', $input['end_date']);
    }

    $queue = $query
        ->select(
            'm.id',
            'p.id as patient_id',
            'p.user_name as name',
            'p.hospital_num as hn',
            'm.meal_type as meal',
            'm.meal_date as date',
            'm.meal_time as time',
            'm.ai_status',
            'm.review_status',
            'm.gdm_risk',
            'm.created_at',
            'm.updated_at',
            'c.confidence_average',
            'c.confidence_item_count',
            'c.food_item_count'
        )
        // ความเร่งด่วนของคิว ไม่ใช่การวินิจฉัยฉุกเฉิน
        ->selectRaw("
            CASE
                WHEN m.gdm_risk = 'high' THEN 'high'
                ELSE 'normal'
            END as urgency
        ")
        ->selectRaw("
            CASE
                WHEN m.gdm_risk = 'high' THEN 1
                ELSE 2
            END as priority
        ")
        ->orderBy('priority')
        ->orderBy('m.created_at')
        ->orderBy('m.id')
        ->paginate(
            (int) ($input['per_page'] ?? 20),
            ['*'],
            'page',
            (int) ($input['page'] ?? 1)
        );

    $queue->getCollection()->transform(function ($row) {
        $row->ai_confidence = [
            'average' => $row->confidence_average === null
                ? null
                : round((float) $row->confidence_average, 2),
            'method' => 'mean_of_available_item_scores',
            'items_with_score' => (int) $row->confidence_item_count,
            'total_items' => (int) $row->food_item_count,
            // ยังไม่ยืนยันว่า confidence ใช้สเกล 0–1 หรือ 0–100
            'scale' => null,
        ];

        unset(
            $row->confidence_average,
            $row->confidence_item_count,
            $row->food_item_count
        );

        return $row;
    });

    return response()->json([
        'success' => true,
        'data' => [
            'items' => $queue->items(),
            'total' => $queue->total(),
            'current_page' => $queue->currentPage(),
            'per_page' => $queue->perPage(),
            'last_page' => $queue->lastPage(),
        ],
    ]);
}

public function food_review_detail(Request $request)
{
    $input = $request->validate([
        'meal_id' => 'required|integer|min:1',
        'doctor_id' => 'sometimes|string|max:255',
        'meal_log_page' => 'sometimes|integer|min:1',
        'item_log_page' => 'sometimes|integer|min:1',
    ]);
   $doctorId = $input['doctor_id'];
    //$doctorId = Session::get('doctor_id');

    // if (($doctorId === null || $doctorId === '')
    //     && app()->environment('local')) {
    //     $doctorId = $input['doctor_id'] ?? null;
    // }

    // if ($doctorId === null || $doctorId === '') {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'กรุณาเข้าสู่ระบบแพทย์',
    //     ], 401);
    // }

    $meal = $this->doctorMealQuery($doctorId)
        ->where('m.id', $input['meal_id'])
        ->select(
            'm.*',
            'p.user_name as patient_name',
            'p.hospital_num as hn'
        )
        ->first();

    if (!$meal) {
        return response()->json([
            'success' => false,
            'message' => 'ไม่พบมื้ออาหารหรือไม่มีสิทธิ์เข้าถึง',
        ], 404);
    }

    $number = static fn ($value) => $value === null
        ? null
        : (float) $value;

    $pageData = static fn ($page) => [
        'items' => $page->items(),
        'total' => $page->total(),
        'current_page' => $page->currentPage(),
        'per_page' => $page->perPage(),
        'last_page' => $page->lastPage(),
    ];

    // รายการอาหารปัจจุบันที่แก้ไขได้
    $items = DB::table('meal_items')
        ->where('meal_transaction_id', $meal->id)
        ->whereNull('deleted_at')
        ->orderBy('id')
        ->get([
            'id',
            'food_name',
            'portion',
            'unit',
            'weight_g',
            'calorie',
            'carbohydrate',
            'protein',
            'fat',
            'fiber',
            'sugar',
            'sodium',
            'confidence_score',
            'created_source',
            'last_updated_source',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
        ]);

    $editableItems = $items->map(function ($item) use ($number) {
        return [
            'id' => $item->id,
            'name' => $item->food_name,
            'portion' => $number($item->portion),
            'unit' => $item->unit,
            'weight_g' => $number($item->weight_g),
            'energy_kcal' => $number($item->calorie),
            'nutrients' => [
                'carbohydrate_g' => $number($item->carbohydrate),
                'protein_g' => $number($item->protein),
                'fat_g' => $number($item->fat),
                'fiber_g' => $number($item->fiber),
                'sugar_g' => $number($item->sugar),
                'sodium_mg' => $number($item->sodium),
            ],
            'confidence_score' => $number($item->confidence_score),
            'created_source' => $item->created_source,
            'last_updated_source' => $item->last_updated_source,
            'created_by' => $item->created_by,
            'updated_by' => $item->updated_by,
            'updated_at' => $item->updated_at,
        ];
    })->values();

    $confidenceScores = $items
        ->pluck('confidence_score')
        ->filter(fn ($score) => $score !== null);

    $confidence = [
        'average' => $confidenceScores->isEmpty()
            ? null
            : round((float) $confidenceScores->avg(), 2),
        'method' => 'mean_of_available_item_scores',
        'items_with_score' => $confidenceScores->count(),
        'total_items' => $items->count(),
        'scale' => null,
    ];

    // ประวัติระดับมื้อ
    $mealLogsBase = DB::table('meal_logs')
        ->where('meal_transaction_id', $meal->id);

    $mealLogs = (clone $mealLogsBase)
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->paginate(
            20,
            [
                'id',
                'action',
                'field_name',
                'old_value',
                'new_value',
                'change_reason',
                'actor_type',
                'note',
                'created_at',
            ],
            'meal_log_page',
            (int) ($input['meal_log_page'] ?? 1)
        );

    /*
     * ประวัติระดับรายการอาหาร
     * รวมรายการที่ถูก soft delete เพื่อให้เห็นประวัติการลบด้วย
     */
    $itemLogs = DB::table('meal_item_logs as l')
        ->join('meal_items as i', 'i.id', '=', 'l.meal_item_id')
        ->where('i.meal_transaction_id', $meal->id)
        ->select(
            'l.id',
            'l.meal_item_id',
            'l.meal_log_id',
            'l.action',
            'l.field_name',
            'l.old_value',
            'l.new_value',
            'l.change_reason',
            'l.actor_type',
            'l.created_at'
        )
        ->orderByDesc('l.created_at')
        ->orderByDesc('l.id')
        ->paginate(
            20,
            ['*'],
            'item_log_page',
            (int) ($input['item_log_page'] ?? 1)
        );

    // การเปลี่ยน review_status ล่าสุดที่มี log
    $latestStatusChange = (clone $mealLogsBase)
        ->where('field_name', 'review_status')
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->first([
            'id',
            'old_value',
            'new_value',
            'actor_type',
            'note',
            'created_at',
        ]);

    // หมายเหตุล่าสุดที่มีข้อความ ไม่ถือว่าเป็นข้อความส่งกลับ
    $latestNote = (clone $mealLogsBase)
        ->whereNotNull('note')
        ->whereRaw("TRIM(note) <> ''")
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->first(['note', 'created_at']);

    return response()->json([
        'success' => true,
        'data' => [
            'meal_info' => [
                'id' => $meal->id,
                'patient_id' => $meal->user_id,
                'name' => $meal->patient_name,
                'hn' => $meal->hn,
                'meal' => $meal->meal_type,
                'date' => $meal->meal_date,
                'time' => $meal->meal_time,
                'urgency' => $meal->gdm_risk === 'high'
                    ? 'high'
                    : 'normal',
                'ai_status' => $meal->ai_status,
                'review_status' => $meal->review_status,
            ],

            'food_image' => [
                'url' => $meal->image_url,
                'meal_date' => $meal->meal_date,
                'meal_time' => $meal->meal_time,
                'recorded_at' => $meal->created_at,
                'captured_at' => null,
            ],

            'ai_analysis' => [
                'status' => $meal->ai_status,
                'value_scope' => 'latest_stored_values',
                'original_ai_snapshot_available' => false,

                'foods' => $editableItems,

                'energy_kcal' => $number($meal->total_calorie),
                'nutrients' => [
                    'carbohydrate_g' => $number($meal->total_carbohydrate),
                    'protein_g' => $number($meal->total_protein),
                    'fat_g' => $number($meal->total_fat),
                    'fiber_g' => $number($meal->total_fiber),
                    'sugar_g' => $number($meal->total_sugar),
                    'sodium_mg' => $number($meal->total_sodium),
                ],
                'gdm_analysis' => [
                    'risk_level' => $meal->gdm_risk,
                    'recommendation' => $meal->recommendation,
                ],
                'confidence' => $confidence,
            ],

            'editable_food_items' => $editableItems,

            'recommendations_and_notes' => [
                'recommendation' => $meal->recommendation,
                'latest_note' => $latestNote?->note,
                'note_recorded_at' => $latestNote?->created_at,
            ],

            'review_result' => [
                'status' => $meal->review_status,

                // SQL ยังไม่มีผู้ตรวจและเวลายืนยันตรวจโดยเฉพาะ
                'reviewer' => null,
                'reviewed_at' => null,

                'latest_status_change' => $latestStatusChange,

                // Logs อาจมีทั้งการแก้โดย AI คนไข้ และบุคลากร
                // ส่ง actor_type เพื่อแยกผู้กระทำตามชนิด
                'meal_changes' => $pageData($mealLogs),
                'food_item_changes' => $pageData($itemLogs),

                'reply_message' => null,
                'reply_sent_at' => null,
            ],
        ],
    ]);
}


public function insert_food(Request $request)
{
    $data = $request->validate([
        // users_register.id ไม่ใช่ LINE user_id
        'user_id' => 'sometimes|string|max:255',
        'doctor_id' => 'sometimes|string|max:255',

        'meal_type' => 'required|string|max:30',
        'meal_date' => 'required|date_format:Y-m-d',
        'meal_time' => 'nullable|date_format:H:i',
        'image_url' => 'nullable|url',
        'recommendation' => 'nullable|string|max:5000',
        'note' => 'nullable|string|max:5000',

        'foods' => 'required|array|min:1|max:100',
        'foods.*.name' => 'required|string|max:255',
        'foods.*.portion' => 'nullable|numeric|min:0|max:99999999.99',
        'foods.*.unit' => 'nullable|string|max:50',
        'foods.*.weight_g' => 'nullable|numeric|min:0|max:99999999.99',

        'foods.*.calorie' => 'required|numeric|min:0|max:99999999.99',
        'foods.*.carbohydrate' => 'required|numeric|min:0|max:99999999.99',
        'foods.*.protein' => 'required|numeric|min:0|max:99999999.99',
        'foods.*.fat' => 'required|numeric|min:0|max:99999999.99',
        'foods.*.fiber' => 'required|numeric|min:0|max:99999999.99',
        'foods.*.sugar' => 'nullable|numeric|min:0|max:99999999.99',
        'foods.*.sodium' => 'nullable|numeric|min:0|max:99999999.99',
    ]);

    //$doctorId = Session::get('doctor_id');

    // // ทดสอบโดยส่ง doctor_id ได้เฉพาะ local
    // if (($doctorId === null || $doctorId === '')
    //     && app()->environment('local')) {
    //     $doctorId = $data['doctor_id'] ?? null;
    // }

    // if ($doctorId === null || $doctorId === '') {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'กรุณาเข้าสู่ระบบแพทย์',
    //     ], 401);
    // }

    $doctorId = $data['doctor_id'];
    // ตรวจสิทธิ์แพทย์ต่อผู้ป่วย
    $patient = DB::table('users_register as p')
        ->where('p.user_id', $data['user_id'])
        ->whereNull('p.deleted_at')
        ->whereExists(function ($query) use ($doctorId) {
            $query->selectRaw('1')
                ->from('personal_doctor_mom as pd')
                ->whereColumn('pd.user_id', 'p.user_id')
                ->where('pd.doctor_id', $doctorId)
                ->whereNull('pd.deleted_at');
        })
        ->first(['p.id', 'p.user_id', 'p.user_name']);

    if (!$patient) {
        return response()->json([
            'success' => false,
            'message' => $doctorId,
        ], 404);
    }

    // รวมเป็นจำนวนเต็มหน่วย 0.01 เพื่อให้ยอดตรงกับค่าที่บันทึก
    $nutritionFields = [
        'calorie',
        'carbohydrate',
        'protein',
        'fat',
        'fiber',
        'sugar',
        'sodium',
    ];

    $foods = [];
    $totalCents = array_fill_keys($nutritionFields, 0);

    foreach ($data['foods'] as $food) {
        $item = [
            'food_name' => $food['name'],
            'portion' => isset($food['portion'])
                ? round((float) $food['portion'], 2)
                : null,
            'unit' => $food['unit'] ?? null,
            'weight_g' => isset($food['weight_g'])
                ? round((float) $food['weight_g'], 2)
                : null,
        ];

        foreach ($nutritionFields as $field) {
            $cents = (int) round((float) ($food[$field] ?? 0) * 100);

            $item[$field] = number_format($cents / 100, 2, '.', '');
            $totalCents[$field] += $cents;
        }

        $foods[] = $item;
    }

    // ป้องกันยอดรวมเกิน decimal(10,2)
    foreach ($totalCents as $value) {
        if ($value > 9999999999) {
            return response()->json([
                'success' => false,
                'message' => 'สารอาหารรวมเกินขนาดที่ฐานข้อมูลรองรับ',
            ], 422);
        }
    }

    $result = DB::transaction(function () use (
        $data,
        $patient,
        $doctorId,
        $foods,
        $totalCents
    ) {
        $now = now();

        $mealData = [
            'id' => $patient->id,
            'user_id' => $patient->user_id,
            'meal_type' => $data['meal_type'],
            'meal_date' => $data['meal_date'],
            'meal_time' => $data['meal_time'] ?? null,
            'image_url' => $data['image_url'] ?? null,

            // เพิ่มโดยคน ไม่ใช่ผล AI
            'ai_status' => 'manual',
            'review_status' => 'pending',

            'gdm_risk' => null,
            'recommendation' => $data['recommendation'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        foreach ($totalCents as $field => $cents) {
            $mealData['total_' . $field] =
                number_format($cents / 100, 2, '.', '');
        }

        $mealId = DB::table('meal_transactions')
            ->insertGetId($mealData);

        $itemIds = [];

        foreach ($foods as $food) {
            $itemIds[] = DB::table('meal_items')->insertGetId(
                array_merge($food, [
                    'meal_transaction_id' => $mealId,
                    'confidence_score' => null,
                    'created_source' => 'doctor',
                    'last_updated_source' => 'doctor',
                    'created_by' => (string) $doctorId,
                    'updated_by' => (string) $doctorId,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ])
            );
        }

        DB::table('meal_logs')->insert([
            'meal_transaction_id' => $mealId,
            'action' => 'create',
            'field_name' => null,
            'old_value' => null,
            'new_value' => null,
            'actor_type' => 'doctor',
            'change_reason' => 'manual_entry',
            'note' => $data['note'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return [
            'meal_id' => $mealId,
            'meal_item_ids' => $itemIds,
            'patient_id' => $patient->id,
            'name' => $patient->user_name,
            'ai_status' => 'manual',
            'review_status' => 'pending',
            'total_calorie' => (float) $mealData['total_calorie'],
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'บันทึกอาหารเรียบร้อย',
        'data' => $result,
    ], 201);
}

}