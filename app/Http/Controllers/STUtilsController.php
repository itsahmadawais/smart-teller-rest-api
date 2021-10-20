<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\STUser;
use App\Models\STAuth;

use DB;

class STUtilsController extends Controller
{
    /**
     * @params user_id, token
     * @returns timeline_data
     * @description This function get the timeline data for a specific user
     * and return in the JSON form.
     */
    public function get_timeline_data($user_id,$token){
        if($this->login_checker($token))
        {
            //Getting Course Details
            $courses = $this->get_course_details($user_id);
            //Gettings Quizes
            $quizes=[];
            foreach($courses as $course)
            {
                $quizes_db = DB::table('mdlub_quiz')
                        ->where('course','=',$course->courseid)
                        ->get();
                foreach($quizes_db as $quiz)
                {
                    if(isset($quiz) && !empty($quiz))
                    {
                        $today_date=date('m/d/Y H:i:s');
                        $quizEndingDate = date('m/d/Y H:i:s', $quiz->timeclose);
                        if($today_date<=$quizEndingDate)
                        {
                            $temp_quiz['type']="quiz";
                            $temp_quiz['courseTitle']=$course->shortname;
                            $temp_quiz['endingDate']=date('d/m/Y', $quiz->timeclose);
                            $temp_quiz['endingTime']=date('H:i A', $quiz->timeclose);
                            $quizes[]=$temp_quiz;
                        }
                    }
                }
            }
            foreach($courses as $course)
            {
                $quizes_db = DB::table('mdlub_assign')
                        ->where('course','=',$course->courseid)
                        ->get();
                foreach($quizes_db as $quiz)
                {
                    if(isset($quiz) && !empty($quiz))
                    {
                        $today_date=date('m/d/Y H:i:s');
                        $quizEndingDate = date('m/d/Y H:i:s', $quiz->gradingduedate);
                        if($today_date<=$quizEndingDate)
                        {
                            $temp_quiz['type']="assignment";
                            $temp_quiz['courseTitle']=$course->shortname;
                            $temp_quiz['endingDate']=date('d/m/Y', $quiz->gradingduedate);
                            $temp_quiz['endingTime']=date('H:i A', $quiz->gradingduedate);
                            $quizes[]=$temp_quiz;
                        }
                    }
                }
            }
            $quiz_data=[];
            if(is_array($quizes) && count($quizes)>4)
            {
                $quiz_data=array_splice($quizes, 4);
            }
            else
            {
                $quiz_data=$quizes;
            }
            return response()->json([
                "data"=>$quiz_data
            ],200);
        }
    }
    /**
     * @params user_id, token
     * @returns timeline_data
     * @description This function get the timeline data for a specific user
     * and return in the JSON form.
     */
    public function get_attendence_data($user_id,$token){
        if($this->login_checker($token))
        {
            $attendence=[];
            $class['courseTitle']="Web Design";
            $class['courseCode']="CS-403";
            $class['attendence']="78%";
            $class['totalClassesTaken']="16";
            $class['absents']="3";
            $attendence[]=$class;
            $class['courseTitle']="Python";
            $class['courseCode']="CS-503";
            $class['attendence']="90%";
            $class['totalClassesTaken']="17";
            $class['absents']="2";
            $attendence[]=$class;
            return response()->json([
                "data"=>$attendence
            ],200);
        }
        $error['msg']="Invalid token!";
        return response()->json([
            "error"=>$error
        ],401);
    }
    /**
     * @params user_id, token
     * @returns timeline_data
     * @description This function get the timeline data for a specific user
     * and return in the JSON form.
     */
    public function get_time_table($user_id,$token){
        if($this->login_checker($token))
        {
            $timeTable=[];
            $class['classTitle']="Web Design";
            $class['courseCode']="CS-403";
            $class['time']="12:00 PM";
            $timeTable[]=$class;

            $class['classTitle']="Python";
            $class['courseCode']="CS-503";
            $class['time']="01:00 PM";
            $timeTable[]=$class;

            $class['classTitle']="TOA";
            $class['courseCode']="CS-203";
            $class['time']="03:00 PM";
            $timeTable[]=$class;

            return response()->json([
                "data"=>$timeTable
            ],200);
        }
        $error['msg']="Invalid token!";
        return response()->json([
            "error"=>$error
        ],401);
    }
    /**
     * @params user_id, token
     * @returns timeline_data
     * @description This function get the timeline data for a specific user
     * and return in the JSON form.
     */
    public function get_todo_data($user_id,$token){
        if($this->login_checker($token))
        {
            $data['sStartDate']="12 March, 2021";
            $data['midTerms']="12 May, 2021";
            $data['finals']="12 July, 2021";
            $data['upcomingEvent']="Eid, 12 March, 2021";
            return response()->json([
                'data'=>$data
            ],200);
        }
        $data['msg']="User not authenticated!";
        return response()->json([
            "error"=>$data
        ],401);
    }
    /**
     * @params token
     * @returns boolean value (true or false)
     * @purpose Checks if user is authenticated or not
     */
    public function login_checker($token)
    {
        $st_auth = STAuth::where('secret_key',$token)->first();
        /**
         * If token is valid, return the settings array.
         */
        if(isset($st_auth))
        {
            return true;
        }
        return false;
    }


    public function get_course_details($user_id)
    {
        $courses = DB::table('mdlub_course')
                       ->join('mdlub_enrol','mdlub_enrol.courseid','=','mdlub_course.id')
                       ->join('mdlub_user_enrolments','mdlub_user_enrolments.enrolid','=','mdlub_enrol.id')
                       ->select('mdlub_course.fullname','mdlub_course.shortname','mdlub_course.id as courseid')
                       ->get();
        return $courses;
    }
}
