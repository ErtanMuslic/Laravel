<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Courses;
use App\Models\NewsFeed;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{

    //Home Page
    public function home(){
        return view('home');
    }

    //Contact Page
    public function contact(){
        return view('contact');
    }


    //Get All Courses
    public function index(){
        return view('courses.index', [
            'courses' => Courses::latest()->filter(request(['tag', 'search']))
            ->paginate(2),
            'newsFeed' => NewsFeed::latest()->get()
        ]);
    }
    

    //Show Form For Course Creation
    public function create(){
        return view('courses.create');
    }

    //Get Single Course
    public function show(Courses $course){
        return view('courses.show' , [
            'course' => $course
        ]);
    }


    //Create Course
    public function store(Request $request){
        $formFields = $request-> validate([
            'title' => ['required', Rule::unique('courses','title')],
            'description' => 'required',
            'duration' => ['required','min:1'],
            'tags' => ['required','min:1'],
            'price' => ['required','min:0']
        ]);

         $formFields['user_id'] = auth()->id();
         $formFields['image'] = $request->file('image')->store('course_images','public');
         $courseTitle = $formFields['title'];

        Courses::create($formFields);

        NewsFeed::create([
            'content' => auth()->user()->name . " created a new Course named $courseTitle",
        ]);


        return redirect('/')-> with('message','Course Created Successfuly!');
    }


    //Show Edit Form
    public function edit(Courses $course){
        return view('courses.edit', ['course' => $course]);
    }


    //Update Course Data
    public function update(Request $request, Courses $course){

        //Make sure logged in user is owner
        if($course->user_id != auth()->id()){
            abort(403,'Unauthorized Action');
        }

        $formFields = $request-> validate([
            'title' => ['required'],
            'description' => 'required',
            'duration' => ['required','min:1'],
            'tags' => ['required','min:1'],
            'price' => ['required','min:0']
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('course_images', 'public');
            $formFields['image'] = $imagePath;
        }
        $course->update($formFields);
        

        $courseTitle = $formFields['title'];
        NewsFeed::create([
            'content' => auth()->user()->name . " updated a Course named $courseTitle",
        ]);


        return back()->with('message','Course Updated Successfuly!');
    }


    //Delete Course
    public function destroy(Courses $course){

        if($course->user_id != auth()->id()){
            abort(403,'Unauthorized Action');
        }

        $courseTitle = $course->title;
        NewsFeed::create([
            'content' => auth()->user()->name . " Removed a Course named $courseTitle",
        ]);

        $course->delete();
        return redirect('/')->with('message', "Course Deleted Successfully!");
    }


    //Manage Courses
    public function manage(){

        return view('courses.manage',['courses' => auth()->user()->courses()->get()]);
    }


    
}
