<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Contact_Person;
use DB;

class OfficeController extends Controller
{
    //
    public function index() {
        return view('office');
    }

    public function getrecords()
    {
        $offices = Office::leftJoin('Contact_Persons', 'Offices.contact_id', '=', 'Contact_Persons.contact_id')
        ->select('Offices.*', DB::raw("CONCAT(Contact_Persons.lname,', ',Contact_Persons.fname) AS fullname"), 'Contact_Persons.contact_no')
        ->get();       

        $contact = DB::table('contact_persons')
			->select('contact_id', 'lname', 'fname', DB::raw("CONCAT(lname, ', ', fname) AS fullname"))
			->get(); 
             
        return response()->json(['offices' => $offices, 'contact' => $contact],
            200);       
    }

    public function store(Request $request)
    {        
        $request->validate([
            'name' => 'required', 
            'contact_id' => 'required' /* using regular expression to validate if the user enter an integer --|regex:/^[0-9]*$/ */
        ], [
            'name.required' => 'Name is required.',
            'contact_id.required' => 'Contact Person is required.'
        ]);

        $offices = new Office();
        $offices->name = $request->name;
        $offices->contact_id = $request->contact_id;
        $offices->save();

        return response()->json([
            'message' => 'Office successfully added.'
        ]);
    }

    public function edit($id)
    {
        $office = Office::where('office_id', '=', $id)->first();
        return response()->json(['office' => $office],
            200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required' /* using regular expression to validate if the user enter an integer |regex:/^[0-9]*$/*/
            
        ], [
            'name.required' => 'Last name is required.'
            
        ]);

        $office = Office::where('office_id', '=', $request->office_id)->first();
        if(!empty($office )) {
            $office->name = $request->name;
            $office->contact_id = $request->contact_id;
            $office->save();
        }
        return response()->json([
            'message' => 'Office successfully updated.'
        ]);
    }

    public function destroy($id)
    {
        
        $data = Office::where('office_id', '=', $id)->first();
        if(!empty($data)) {
            $data->delete();
        }
        return response()->json([
            'message' => 'Office has been deleted.'
        ]);
    }

}
