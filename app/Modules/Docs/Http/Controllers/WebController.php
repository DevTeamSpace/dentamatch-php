<?php

namespace App\Modules\Docs\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class WebController extends Controller
{
    public function __construct() {
       
    }
    
    private function getClassDetails($class){
        $refClass = new \ReflectionClass($class);
        $classDocs = new \zpt\anno\Annotations($refClass);
        $classDocs = $classDocs->asArray();
        $methods = $refClass->getMethods();
        $methodRefs = [];
        foreach ($methods as $method){
            if($method->class != $class || $method->name == "__construct")
                continue;
            $methodRefs[] = (new \zpt\anno\Annotations($method))->asArray();
        }
        return [
            "methods" => $methodRefs,
            "classDoc" => $classDocs,
            "class" => $refClass->name
          ];
    }
    

    /**
     * List Methods of the Classes 
     * 
     * 
     * @return text/json
     */
    public function index(){
        
        return view("docs::doc");
    }
    
    /**
     * List Methods of the Classes 
     * 
     * 
     * @return text/json
     */
    public function getApi(){
        $classes = [
                    'Api' => 
                        [
                             [
                                "classTitle" => "Users Mobile API Controller", 
                                "classRoutes" => [],
                                "class" => \App\Http\Controllers\Api\UserApiController::class
                            ],
                            
                        ]
                    
            ];
        $classesApi = [];
       
        foreach($classes['Api'] as $docClass){
            $classDetails = $this->getClassDetails($docClass['class']);
            $classesApi[] = $classDetails;
        }
        return view("docs::apiDoc")->with("api", $classesApi);
    }
    
    
}
