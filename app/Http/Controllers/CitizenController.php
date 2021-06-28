<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Citizen;

class CitizenController extends Controller
{
    //
    public function index() {
        return view('citizen');
    }

    public function getrecords()
    {
        $citizens = Citizen::all();
        return response()->json(['citizens' => $citizens],
            200);       
    }

    public function store(Request $request)
    {        
        $request->validate([
            'lname' => 'required', 
            'fname' => 'required', /* using regular expression to validate if the user enter an integer --|regex:/^[0-9]*$/ */
            'contact_no' => 'regex:/[0-9]+$/'
        ], [
            'lname.required' => 'Last name is required.',
            'fname.required' => 'First name is required.',
            'contact_no.regex' => 'Contact # must be integer only'
        ]);

        $citizens = new Citizen();
        $citizens->lname = $request->lname;
        $citizens->fname = $request->fname;
        $citizens->mname = $request->mname;
        $citizens->address = $request->address;
        $citizens->barangay = $request->barangay;
        $citizens->contact_no = $request->contact_no;
        $citizens->save();

        return response()->json([
            'message' => 'Citizen successfully added.'
        ]);
    }

    public function edit($id)
    {
        $citizen = Citizen::where('citizen_id', '=', $id)->first();
        return response()->json(['citizen' => $citizen],
            200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'lname' => 'required', /* using regular expression to validate if the user enter an integer |regex:/^[0-9]*$/*/
            'fname' => 'required',
            'contact_no' => 'regex:/[0-9]+$/'
        ], [
            'lname.required' => 'Last name is required.',
            'fname.required' => 'First name is required.',
            'contact_no.regex' => 'Contact # must be integer only'
        ]);

        $citizen = Citizen::where('citizen_id', '=', $request->citizen_id)->first();
        if(!empty($citizen )) {
            $citizen->lname = $request->lname;
            $citizen->fname = $request->fname;
            $citizen->mname = $request->mname;
            $citizen->address = $request->address;
            $citizen->barangay = $request->barangay;
            $citizen->contact_no = $request->contact_no;                       
            $citizen->save();
        }
        return response()->json([
            'message' => 'Citizen successfully updated.'
        ]);
    }

    public function destroy($id)
    {
        
        $data = Citizen::where('citizen_id', '=', $id)->first();
        if(!empty($data)) {
            $data->delete();
        }
        return response()->json([
            'message' => 'Citizen has been deleted.'
        ]);
    }

    // public function export() {
    //     return Excel::download(new ZipCodeExport, 'zipcode_' . date('mdy') . '.xlsx');
    // }

    // public function pdf($size, $orientation) {
    //     $zipcodes = ZipCode::orderBy('id')->select('id', 'name')->get();

    //     $pdf = \App::make('dompdf.wrapper');
    //     $pdf->getDomPDF()->set_option("enable_php", true);
    //     $pdf->loadView('pdf.pdf_zipcode', compact('zipcodes'))
    //         ->setPaper($size, $orientation);

    //     return $pdf->stream();
    // }
}
