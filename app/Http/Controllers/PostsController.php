<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Auth;
use App\Child;
use \Storage; 
use App\User;

class PostsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if(\Auth::check()){
            $user = \Auth::user();
            $posts = $user->posts()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user'=>$user,
                'posts'=>$posts,
            ];
        }
        
        return view('posts.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 子供一覧を取得し、ビューに渡す
        if(\Auth::check()){
            // $user_id = Auth::user()->id;
            $children = Auth::user()->children()->get();
        
            // $children_list = null;
                
            foreach($children as $child)
            {
            $children_list[$child->id] = $child->nickname;
            }
            
        }
            $post = new Post;

            //welcomeにユーザーIDを渡す
            return view('posts.create',[
                // 'user_id'=>$user_id,
                'children' => $children,
                'post' => $post,
                'nickname'=> $children_list
            ]);
    }



    const GENTEIKOUKAI = 2; 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // DB保存前に値が意図した通りにきているかの確認
        //(gettype((int)$request->open));
        //dd($request->child_id);
        //dd($request->file('photo'));
        
        // バリデーション
        // 限定公開のときはメールアドレスが必須となる
        // ��撮影時の年齢」ー 範囲チェック（0〜15）。数値のみ。未入力不可。
        // 写真は必須入力、拡張子というか本当に画像ファイルかのチェック。ファイルサイズチェック。

       
         //if ($request->open === GENTEIKOUKAI) // 2は定数に置き換える
         if ($request->open ==2)   //2は限定公開
        {
        // もし公開なら
        $request->validate([
            'age' => 'required',
            'photo'=>['required','image','max:10240'],  //10MB
            'allowemail1'=>'required',
            ]);              
        }
        else
        {
        // そうでないなら
        $request->validate([
            'age' => 'required',
            'photo'=>['required','image','max:10240'],  //10MB
            ]);            
        }
        

        // 画像ファイルをS3に格納する
        $disk = Storage::disk('s3');
        // dd($request->file('photo'));
        // 保存した直後にファイル名が取得できる
        $fileName = $disk->put('',$request->file('photo'));
        
        // Diskクラスのurlメソッドで画像ファイルへのフルパスが取得できる
        $path = $disk->url($fileName);
        //dd($path);
        
        // テーブルにレコードを登録する
        
        if($request->open ==2){
        $post = new Post;
        $post->user_id = \Auth::id();
        $post->child_id = (int)$request->child_id;   //もとはchild_idではなくnicknameとしていた
        $post->photo = $path;
        // $post->photo = $request->file('photo');
        $post->age = $request->age;
        $post->open = (int)$request->open;
        $post->allowemail1 = $request->allowemail1;
        $post->allowemail2 = $request->allowemail2;
        }
        else{
        $post = new Post;
        $post->user_id = \Auth::id();
        $post->child_id =$request->child_id;
        $post->photo = $path;
        $post->age = $request->age;
        $post->open = (int)$request->open;
        }
        
        // postsテーブルに保存
    
        $post->save();

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
        // idの値でpostを検索して取得
        $post = Post::findOrFail($id);
        $child = Child::findOrFail($post->child_id);

        // メッセージ詳細ビューでそれを表示
        return view('posts.show', [
            'post' => $post,
            'child' =>$child,
        ]);        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
     
    public function limit()
    {
         if(\Auth::check()){
            $user = \Auth::user();
            $children = Auth::user()->children()->get();
            
            // 条件句に「限定公開である」かつ（「許可メールアドレス1と自分のメールアドレスが等しい」または「許可メールアドレス2と自分のメールアドレスが等しい」）
             //$posts = Post::where('open', 2)->orderBy('created_at', 'desc')->paginate(10);
             //$posts = Post::where('open'==2 AND ('allowemail1'==$user->email or 'allowemail2'==$user->email))->orderBy('created_at', 'desc')->paginate(10);
            $posts=Post::where('open',2)->where(function($post) use ($user){
                $post->where('allowemail1','=',$user->email)
                ->orwhere('allowemail2','=',$user->email);
            })->get();

                    return view('posts.limit',[
                        // 'user_id'=>$user_id,
                        'children' => $children,
                        'posts'=>$posts,
                    ]);
   

            // foreach($posts as $post){
                
                // 投稿データの許可メールアドレス1、許可メールアドレス2
                
                //
                
                
                
                // if ($post->open==2 && (\Auth::id() === $post->user_id || $user->email===$post->allowemail1 || $user->email===$post->allowemail2)) {
            
                //     return view('posts.limit',[
                //         // 'user_id'=>$user_id,
                //         'children' => $children,
                //         'posts'=>$posts,
                //     ]);
                // }

                
            }
                // else{
                //      return redirect('/');
                // }
     }


    public function edit($id)
    {
        // idの値で検索して取得
        $post = Post::findOrFail($id);
        $children = Auth::user()->children()->get();
        
            foreach($children as $child)
            {
            $children_list[$child->id] = $child->nickname;
            }

        if (\Auth::id() === $post->user_id) {
        // 編集ビューでそれを表示
        return view('posts.edit', [
             'children' => $children,
                'post' => $post,
                'nickname'=> $children_list
        ]);
        }
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
         if ($request->open ==2)   //2は限定公開
        {
        // もし公開なら
        $request->validate([
            'age' => 'required',
            // 'photo'=>['required','image','max:10240'],  //10MB
            'allowemail1'=>'required',
            ]);              
        }
        else
        {
        // そうでないなら
        $request->validate([
            'age' => 'required',
            // 'photo'=>['required','image','max:10240'],  //10MB
            ]);            
        }
        
         $post = Post::findOrFail($id);
        $path = $post->photo;
        
         if($request->open ==2){
        $post->user_id = \Auth::id();
        $post->child_id = (int)$request->child_id;   //もとはchild_idではなくnicknameとしていた
        // $post->photo = $path;
        $post->age = $request->age;
        $post->open = (int)$request->open;
        $post->allowemail1 = $request->allowemail1;
        $post->allowemail2 = $request->allowemail2;
        }
        else{
        $post->user_id = \Auth::id();
        $post->child_id =$request->child_id;
        // $post->photo = $path;
        $post->age = $request->age;
        $post->open = (int)$request->open;
        }
        
        // postsテーブルに保存
    
        $post->save();

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
        $post = Post::findOrFail($id);
      if (\Auth::id() === $post->user_id) {
        $post->delete();
        }
        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
