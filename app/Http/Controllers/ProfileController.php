<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;

class ProfileController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        // gets the selects colums only
        $profile = Profile::select(['id','name','age','gender','image', 'status']);

        return DataTables::of($profile)->make();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        $response['status'] = false;
        $response['message'] = 'Oops! Something went wrong.';

        $id = $request->input('id');
        $status = $request->input('status');

        $item = Profile::find($id);

        if ($item->update(['status' => $status])) {


            $response['status'] = true;
            $response['message'] = 'Profile status updated successfully.';
            return response()->json($response, 200);
        }

        return response()->json($response, 409);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'isEdit' => false,
        ];

        return view('profile.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $input =  $request->validate([
            'name' => 'required',
            'age' => "required|numeric",
            'gender' => 'required',
            'image' => 'file|mimes:jpg,jpeg,png,gif|max:1024'
        ]);

        if($request->image){
            $input['image'] = Storage::disk('public')->putFile('', $request->image);
         }

         Profile::create($input);
         return redirect()->route('profile.index');



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        $data = [
            'profile' => $profile,
            'isEdit' => true,
        ];

        return view('profile.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $input =  $request->validate([
            'name' => 'required',
            'age' => "required|numeric",
            'gender' => 'required',
            'image' => 'file|mimes:jpg,jpeg,png,gif|max:1024'
        ]);


        if($request->image)
        {
            Storage::disk('public')->delete($profile->image);
            $input['image'] = Storage::disk('public')->putFile('', $request->image);
        }

        $profile->update($input);


        return redirect()->route('profile.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $profile = Profile::findOrFail($request->id);

        // apply your conditional check here
        if ( false ) {
            $response['error'] = 'This Profile has something assigned to it.';
            return response()->json($response, 409);
        } else {
            $response = $profile->delete();

            return response()->json($response, 200);
        }
    }
}
