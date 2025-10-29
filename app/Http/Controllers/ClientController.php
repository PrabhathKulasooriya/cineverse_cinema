<?php


namespace App\Http\Controllers;

use App\User;
use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller

{
    public function index(){


        $userClients = User::where('user_role_iduser_role',4)->get();

        return view('client_management.clientManagement',['title'=>'Client Management', 'userClients'=>$userClients]);
    }





//Save Client by Sign Up Start
    public function saveClient(Request $request){

        $validator = \Validator::make($request->all(), [

            'fName' => 'required|max:115',
            'lName' => 'required|max:115',
            'contactNo' => 'required|max:10|min:10|regex:/^07\d{8}$/',
            'email' => 'required',
            'password' => 'required|min:6',

        ], [
            'fName.required' => 'First Name should be provided!',
            'fName.max' => 'First Name must be less than 115 characters.',

            'lName.required' => 'Last Name should be provided!',
            'lName.max' => 'Last Name must be less than 115 characters.',

            'contactNo.required' => 'Contact No should be provided!',
            'contactNo.max' => 'Contact No must be include 10 numbers.',
            'contactNo.min' => 'Contact No must be include 10 numbers.',
            'contactNo.regex' => 'Enter a valid phone number.',

            'email.required' => 'Email should be provided!',

            'password.required' => 'Password should be provided.',
            'password.min' => 'Password must be include minimum 6 characters.',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' =>$validator->errors()]);
        }

        $existingUser = User::where('email', $request['email'])->first();
        if ($existingUser) {
            return response()->json(['errors' => ['email'=> ['Email already exists!']]]);
        }


        $advanceEncryption = (new  \App\MyResources\AdvanceEncryption($request['password'],"Nova6566", 256));

        $saveUser = new User();

        $saveUser->first_name = strtoupper($request['fName']);
        $saveUser->last_name = strtoupper($request['lName']);
        $saveUser->contact_number = $request['contactNo'];
        $saveUser->email=strtolower($request['email']);
        $saveUser->password = $advanceEncryption->encrypt();
        $saveUser->status = 1;
        $saveUser->user_role_iduser_role = 4;

        $saveUser->save();

        session()->flash('success', 'Account Created Successfully!');
        return response()->json(['success' => 'Client saved successfully.']);
    }
//Save Client by Sign Up End






//Save Client by Admin Start
    public function saveClientByAdmin(Request $request){


        $validator = \Validator::make($request->all(), [

            'fName' => 'required|max:115',
            'lName' => 'required|max:115',
            'contactNo' => 'required|max:10|min:10|regex:/^07\d{8}$/',
            'email' => 'required',
            'password' => 'required|min:6',

        ], [


            'fName.required' => 'First Name should be provided!',
            'fName.max' => 'First Name must be less than 115 characters.',

            'lName.required' => 'Last Name should be provided!',
            'lName.max' => 'Last Name must be less than 115 characters.',

            'contactNo.required' => 'Contact No should be provided!',
            'contactNo.max' => 'Contact No must be include 10 numbers.',
            'contactNo.min' => 'Contact No must be include 10 numbers.',
            'contactNo.regex' => 'Enter a valid phone number.',

            'email.required' => 'Email should be provided!',

            'password.required' => 'Password should be provided.',
            'password.min' => 'Password must be include minimum 6 characters.',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' =>$validator->errors()]);
        }

        $existingUser = User::where('email', $request['email'])->first();
        if ($existingUser) {
            return response()->json(['errors' => 'Email already exists!']);
        }


        $advanceEncryption = (new  \App\MyResources\AdvanceEncryption($request['password'],"Nova6566", 256));

        $saveUser = new User();

        $saveUser->first_name = strtoupper($request['fName']);
        $saveUser->last_name = strtoupper($request['lName']);
        $saveUser->contact_number = $request['contactNo'];
        $saveUser->email=strtolower($request['email']);
        $saveUser->password = $advanceEncryption->encrypt();
        $saveUser->status = 1;
        $saveUser->user_role_iduser_role = 4;

        $saveUser->save();

        return response()->json(['success' => 'Client Saved Successfully.']);

    }
    //Save Client by Admin End



    //Update Client Start

    public function updateClient(Request $request){

        $hiddenUserId = $request['hiddenUserId'];
        $firstName = $request['firstName'];
        $lastName = $request['lastName'];
        $contactNo = $request['contactNo'];
        $email = $request['email'];

        //Validation
        $validator = \Validator::make($request->all(), [

            'firstName' => 'required|max:115',
            'lastName' => 'required|max:115',
            'contactNo' => 'required|max:10|min:10|regex:/^07\d{8}$/',
            'email' => 'required|email', 
           
        ], [
            'firstName.required' => 'First Name should be provided!',
            'firstName.max' => 'First Name must be less than 115 characters.',

            'lastName.required' => 'Last Name should be provided!',
            'lastName.max' => 'Last Name must be less than 115 characters.',

            'contactNo.required' => 'Contact No should be provided!',
            'contactNo.max' => 'Contact No must be include 10 numbers.',
            'contactNo.min' => 'Contact No must be include 10 numbers.',
            'contactNo.regex' => 'Enter a valid phone number.',

            'email.required' => 'Email should be provided!',
            'email.email' => 'Please provide a valid email address!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $updateUser = User::find($hiddenUserId);

        if(!$updateUser){
            return response()->json(['errors' => ['general' => ['User not found!']]]);
        }

        $updateUser->first_name=strtoupper($firstName);
        $updateUser->last_name=strtoupper($lastName);
        $updateUser->contact_number=$contactNo;
        
        $updateUser->email=strtolower($email);
        
        $updateUser->save();

        return response()->json(['success'=>'Client Updated']);
    }
    //Update Client End


}