<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Education;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function getEducations() {
        $educations = Education::get();
        $details = User::with('education')->paginate(3);
        return view('index',compact('educations','details'));
    }
    function getRecords(Request $request)  {
        $perPage = 3;
        $currentPage = $request->get('page', 1);

        // Fetch the next set of records
        $details = User::with('education')->paginate($perPage, ['*'], 'page', $currentPage);
        $data = $details->items();

        return response()->json($data);
    }
    function postDetails(Request $request)  {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:10',
            'gender' => 'required',
            'education_id' => 'required',
            'hobbies' => 'required',
            'experience' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
    
            $imagePath = 'storage/' . $imagePath; 
        } else {
            $imagePath = null;
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'education_id' => $request->education_id,
            'hobbies' => is_array($request->hobbies) ? implode(',',$request->hobbies) : $request->hobbies,
            'experience' => is_array($request->experience) ? implode(',',$request->experience) : $request->experience,
            'image' => $imagePath,
            'message' => $request->message
        ]);
        return redirect()->back()->with('success', 'Data has been successfully submitted!');
    }
    }
    function deleteData($id) {
        $user = User::find($id);
        if($user){
            $user->delete();
        }
        return redirect()->back();
    }
    function edit($id)  {
        $user = User::with('education')->where('id',$id)->first();
        $educations = Education::get();
        return view('edit',compact('user','educations'));        
    }
    function update(Request $request,$id) {
        $data = $request->only(['name','email','phone','gender','education_id','hobbies','experience','message']);
        $user = User::find($id);
        if ($request->has('name')) {
            $user->name = $data['name'];
        }
    
        if ($request->has('email')) {
            $user->email = $data['email'];
        }
        if ($request->has('phone')) {
            $user->phone = $data['phone'];
        }
    
        if ($request->has('gender')) {
            $user->gender = $data['gender'];
        }
        if ($request->has('education_id')) {
            $user->education_id = $data['education_id'];
        }
    
        if ($request->has('hobbies')) {
            $user->hobbies = $data['hobbies'];
        }
        if ($request->has('experience')) {
            $user->experience = $data['experience'];
        }
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
    
            $user->image = 'storage/' . $imagePath;
        } 
        if ($request->has('message')) {
            $user->message = $data['message'];
        }

        $user->save();
        return redirect()->route('index');
    }
    function search(Request $request)  {
        $data = $request->input('q');
        $users = User::where('name','LIKE','%'.$data.'%')->get();
        return response()->json($users);
    }
}
