<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Student;
use Validator;

class StudentController extends Controller
{
    public function index()
    {
    	return view('student.create');
    }

    public function store(Request $request)
    {
        // $this->validate($request,[
        //         'name' => 'required|max:255',
        //         'address' => 'required|max:255|min:4'
        //     ]);

        $rulse = [ 'name' => 'required|max:10','address' => 'required|max:255|min:4'];

        $msg = ['name.required' => 'Please enter a name',
                 'name.max' => 'Maximum is 4 characters',
                 'address.required' => 'Please enter a name',
                 'address.max' => 'Maximum is 255 characters',
                 'address.min' => 'Minimum is 4 characters',
            ];

        $validate = Validator::make($request->all(), $rulse,$msg);

        if ($validate->fails()) {
            return response($validate->errors(),401);
        }

        try {
            $student = new Student();

            $student->name = $request->name;
            $student->address = $request->address;

            $student->save();
            return 'true';

        } catch (Exception $e) {
            return 'false';
        }
    	
   	

    }

    public function show()
    {
        try {
            return Student::get();
        } catch (Exception $e) {
            return 'false';
        }
    }

    public function edit($id)
    {
        try {
            return Student::find($id);
        } catch (Exception $e) {
            return 'false';
        }

       
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        $student->name = $request->name;
        $student->address = $request->address;

        $student->save();

        return redirect('student');
    }

    public function delete($id)
    {
        try {
            Student::destroy($id);
            return 'true';
            
        } catch (Exception $e) {
            return 'false';
        }
    }
}
