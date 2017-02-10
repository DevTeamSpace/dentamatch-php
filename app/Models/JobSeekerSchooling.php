<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class JobSeekerSchooling extends Model
{
    use SoftDeletes;
  
    protected $table  = 'jobseeker_schoolings';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'deleted_at'
    ];
    
    public static function getUserSchoolingList($userId)
    {   
        $query = static::where('user_id',$userId)->orderBy('id');
        $list = $query->get()->toArray();

        return $list;
    }
    
    public static function getJobSeekerSchooling($userId)
    {   
        $query = static::select('schoolings.id as parentId', 'schoolingsChild.id as childId', 
                    'schoolings.school_name as schoolName', 'schoolingsChild.school_name as schoolChildName',
                    'jobseeker_schoolings.year_of_graduation as yearOfGraduation', 'jobseeker_schoolings.other_schooling as otherSchooling')
                ->join('schoolings AS schoolingsChild','jobseeker_schoolings.schooling_id', '=', 'schoolingsChild.id')
                ->join('schoolings', 'schoolings.id','=', 'schoolingsChild.parent_id')
                ->where('schoolings.is_active', 1)
                ->where('jobseeker_schoolings.user_id', $userId)
                ->whereNull('schoolings.parent_id')
                ->orderBy('schoolings.id')
                ->orderBy('schoolingsChild.id');
        
        $list = $query->get()->toArray();

        return $list;
    }
    
    public static function getJobseekerOtherSchooling($userId)
    {
        $schoolId = [];
        $schoolingModel = Schooling::select('id')->whereNull('parent_id')->get()->toArray();
        foreach($schoolingModel as $value) {
            $schoolId[] = $value['id'];
        }
        
        $query = static::select('jobseeker_schoolings.schooling_id as parentId', 'jobseeker_schoolings.schooling_id as childId', 
                                'schoolings.school_name as schoolName', 'jobseeker_schoolings.other_schooling as schoolChildName',
                                'jobseeker_schoolings.year_of_graduation as yearOfGraduation', 'jobseeker_schoolings.other_schooling as otherSchooling')
                            ->join('schoolings', 'schoolings.id','=', 'jobseeker_schoolings.schooling_id')
                            ->whereIn('schooling_id', $schoolId)
                            ->where('user_id', $userId);
        $list = $query->get()->toArray();

        return $list;
    }

    public static function getParentJobSeekerSchooling($userId){
        $schoolings = array();
        if($userId){
            $schoolings = static::where('jobseeker_schoolings.user_id',$userId)
                            ->leftJoin('schoolings','jobseeker_schoolings.schooling_id','=','schoolings.id')
                            ->leftJoin('schoolings as school_title','schoolings.parent_id','=','school_title.id')
                            ->select('jobseeker_schoolings.other_schooling','jobseeker_schoolings.year_of_graduation','schoolings.school_name','school_title.school_name as school_title')
                            ->groupby('schoolings.parent_id')
                            ->whereNotNull('schoolings.parent_id')
                            ->where('schoolings.is_active', 1)
                            ->addSelect(DB::raw("group_concat(schoolings.school_name SEPARATOR ', ') AS school_name"))
                            ->get()
                            ->toArray();
        }

        return $schoolings;
    }

}
