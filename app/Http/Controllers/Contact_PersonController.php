<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact_Person;

class Contact_PersonController extends Controller
{
    //
    public function index() {
        return view('contact_person');
    }

    public function getrecords() {
        $contact_persons = Contact_Person::all();
        return response()->json(['contact_persons' => $contact_persons],
            200);       
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'lname' => 'required', 
            'fname' => 'required', /* using regular expression to validate if the user enter an integer --|regex:/^[0-9]*$/ */
            'contact_no' => 'required|regex:/[0-9]+$/'
        ], [
            'lname.required' => 'Last name is required.',
            'fname.required' => 'First name is required.',
            'contact_no.regex' => 'Contact # must be integer only'
        ]);

        $contact_person = new Contact_Person();
        $contact_person->lname = $request->lname;
        $contact_person->fname = $request->fname;
        $contact_person->mname = $request->mname;
        $contact_person->contact_no = $request->contact_no;
        $contact_person->designation = $request->designation;
        $contact_person->save();

        return response()->json([
            'message' => 'Contact Person successfully added.'
        ]);
    }

    public function edit($id)
    {
        $contact_person = Contact_Person::where('contact_id', '=', $id)->first();
        return response()->json(['contact_person' => $contact_person],
            200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'lname' => 'required', /* using regular expression to validate if the user enter an integer |regex:/^[0-9]*$/*/
            'fname' => 'required',
            'contact_no' => 'required|regex:/[0-9]+$/'
        ], [
            'lname.required' => 'Last name is required.',
            'fname.required' => 'First name is required.',
            'contact_no.required' => 'Contact # is required',
            'contact_no.regex' => 'Contact # must be integer only'
        ]);

        $contact_person = Contact_Person::where('contact_id', '=', $request->contact_id)->first();
        if(!empty($contact_person )) {
            $contact_person->lname = $request->lname;
            $contact_person->fname = $request->fname;
            $contact_person->mname = $request->mname;
            $contact_person->contact_no = $request->contact_no;
            $contact_person->designation = $request->designation;
            $contact_person->save();
        }
        return response()->json([
            'message' => 'Contact Person successfully updated.'
        ]);
    }

    public function destroy($id)
    {
        
        $data = Contact_Person::where('contact_id', '=', $id)->first();
        if(!empty($data)) {
            $data->delete();
        }
        return response()->json([
            'message' => 'Contact Person has been deleted.'
        ]);
    }

}
