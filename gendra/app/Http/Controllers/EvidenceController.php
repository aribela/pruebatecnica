<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evidence;

class EvidenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        header("Access-Control-Allow-Origin: *");
        $evidencias = Evidence::join('users as u', 'evidence.user_id', '=', 'u.id')
        ->join('categories as c', 'c.id', 'evidence.category_id')
        ->select('evidence.*', 'c.name as category_name', 'u.name as user_name', 
        DB::raw('IF(evidence.status = 1, "Activo", "Inactivo") as status_name'),
        DB::raw('IF(evidence.image IS NULL, "default.png", CONCAT(\'evidence/\',evidence.image)) as url_image')
        );
        return $evidencias->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        header("Access-Control-Allow-Origin: *");
        $evidence = Evidence::create([
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => $request->user_id,
            'status' => $request->status,
            'image' => $request->image,
        ]);
        

        $customFileName = '';

        if($request->image){
            $customFileName = uniqid().'_.'.$request->image->extension();
            $request->image->storeAs('public/evidence', $customFileName);
            $evidence->image = $customFileName;
            $evidence->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $task = Evidence::findOrFail($request->id);
        return $task;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $evidence = Evidence::find($id);

        $evidence->update([
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);
                                  
        if($request->image){
            $customFileName = uniqid().'_.'.$request->image->extension();
            $request->image->storeAs('public/evidence', $customFileName);
            $imageName = $evidence->image;//imagen anterior
            $evidence->image = $customFileName;
            $evidence->save();

            if($imageName != null){
                if(file_exists('public/evidence/'.$imageName)){
                    unlink('public/evidence/'.$imageName);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $evidence = Evidence::find($request->id);
        //
        $imageName = $evidence->image;
        $evidence->delete();

        if($imageName != null){
            if(file_exists('public/evidence/'.$imageName)){
                unlink('public/evidence/'.$imageName);
            }
        }
    }
}
