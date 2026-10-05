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
   public function dashboard_overview()
{
    $doctor_id = 'test';

    if ($doctor_id === null || $doctor_id === '') {
        return response()->json([
            'success' => false,
            'message' => 'กรุณาเข้าสู่ระบบแพทย์',
        ], 401);
    }

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

//public function get_patient_detail(Request $request, $patient_id)
public function get_patient_detail()
{
    $patient_id = 'Ucd1d3d9310f1afd627bbd1ea729f5be5';
    $doctorId = 'test';
    //$doctorId = Session::get('doctor_id');

    if ($doctorId === null || $doctorId === '') {
        return response()->json([
            'success' => false,
            'message' => 'กรุณาเข้าสู่ระบบแพทย์',
        ], 401);
    }

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
        'weight_status_code' => $patient->weight_status === null
                 ? 'ไม่มีข้อมูล'
                 : ($weightLabels[ (int) $patient->weight_status] ?? "รหัสน้ำหนัก {$patient->weight_status}"),
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

}
    
