<?php

namespace App\Http\Controllers\web;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skills;
use App\Models\JobTitles;
use App\Models\JobTemplates;
use App\Models\TemplateSkills;

class JobtemplateController extends Controller
{
    protected $user,$viewData;
    
    public function __construct() {
        $this->middleware('auth');
        $this->user = Auth::user();
        $this->viewData = ['navActive'=>'template',];
    }
    
    public function returnView($viewFileName){
        return view('web.jobtemplate.'.$viewFileName,$this->viewData);
    }

    public function listJobTemplates(){
        try{
            $this->viewData['jobTemplates'] = JobTemplates::getAllUserTemplates(Auth::user()->id);
            
            return $this->returnView('list');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    public function viewTemplate($templateId) {
        try{
            $this->viewData['templateId'] = $templateId;
            $this->viewData['templateData'] = JobTemplates::findById($templateId,Auth::user()->id);
            $this->viewData['templateSkillsData'] = TemplateSkills::getTemplateSkills($templateId);
            
            return $this->returnView('view');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    public function editJobTemplate($templateId){
        try{
            $this->viewData['templateData'] = JobTemplates::findById($templateId,Auth::user()->id);
            $this->viewData['skillsData'] = Skills::getAllParentChildSkillList($templateId);
            //dd($this->viewData['skillsData']);
            $this->viewData['jobTitleData'] = JobTitles::getAll(JobTitles::ACTIVE);

            return $this->returnView('create');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    public function createJobTemplate(){
        try{
            $this->viewData['skillsData'] = Skills::getAllParentChildSkillList();
            
            $this->viewData['jobTitleData'] = JobTitles::getAll(JobTitles::ACTIVE);

            return $this->returnView('create');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    public function deleteJobTemplate(Request $request){
        try{
            
            JobTemplates::where('id',$request->templateId)->where('user_id',Auth::user()->id)->delete();
            return redirect('jobtemplates');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    public function saveOrUpdate(Request $request){
        $this->validate($request, [
                'jobTitleId' => 'required|integer',
                'templateName' => 'required',
                'templateDesc' => 'required',
                'skills'=>'sometimes',
                'action' =>'required|in:add,edit',
                'id'=>'integer|required_if:action,edit'
            ]);
            try{
            
            $jobTemplate = new JobTemplates();
            if ($request->action=="edit" && !empty($request->id)) {
                $jobTemplate = JobTemplates::findById($request->id);
            }
            
            $jobTemplate->user_id = Auth::user()->id;
            $jobTemplate->job_title_id = $request->jobTitleId;
            $jobTemplate->template_name = $request->templateName;
            $jobTemplate->template_desc = $request->templateDesc;
            $jobTemplate->save();
            $jobTemplate->templateSkills()->delete();
            if(count($request->skills)>0){
                $skillArrObj = [];
                foreach($request->skills as $skill){
                    $skillArrObj[] = new TemplateSkills(['skillId' => $skill]);
                }
                $jobTemplate->templateSkills()->saveMany($skillArrObj);
                unset($skillArrObj);
            }
            unset($jobTemplate);
            return redirect('jobtemplates');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
}
