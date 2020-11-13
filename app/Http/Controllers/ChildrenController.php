<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Child;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class ChildrenController extends Controller
{
    
    public function top()
    {
        //ログインユーザーのユーザーIDを取得する
        if(\Auth::check()){
            $user_id = Auth::user()->id;
            $children = Auth::user()->children()->get();
            //welcomeにユーザーIDを渡す
            return view('welcome',[
                'user_id'=>$user_id,
                'children' => $children,
            ]);
        
        //ログインユーザーのユーザーIDを取得する
    //if(\Auth::check()){
        
      //  $user_id = Auth::user()->id;
        
        //welcomeにユーザーIDを渡す

        //return view('welcome',[
          //  'user_id'=>$user_id,
            //]);
            
        }
    
    else{
        return view('welcome');
    }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $child = new Child;

        return view('children.create',[
            'child'=>$child,
            ]);   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $child = new Child;
        $child->user_id = \Auth::id();
        $child->nickname = $request->nickname;
        $child->gender = $request->gender;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;
        $child->birthday = Carbon::createFromDate($year,$month,$day);
        $child->save();

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $child = Child::findOrFail($id);
        
        return view('children.edit',[
            'child' => $child,
            ]);
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
        $child = Child::findOrFail($id);
        
        $child->user_id = \Auth::id();
        $child->nickname = $request->nickname;
        $child->gender = $request->gender;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;
        $child->birthday = Carbon::createFromDate($year,$month,$day);
        $child->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
