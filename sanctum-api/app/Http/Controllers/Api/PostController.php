<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $data['posts']= Post::all();

        return response()->json([
            'status'=>true,
            'message'=>"ALl post data",
            'data'=>$data,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validateUser=Validator::make(
        $request->all(),
        [
            'title'=> 'required',
            'description'=> 'required',
            'image'=> 'required|mimes:png,jpg,jpeg.gif',
        ]
        );

        if($validateUser->fails()){ // fails is the inbuit medthod of validator
            return response()->json([
                'status'=>false,
                'message'=>'validation Error',
                'errors'=> $validateUser->errors()->all()
            ],401);
        }
        $img=$request->image;
        $ext=$img->getClientOriginalExtension();
        $imageName= time().'.'.$ext;
        $img->move(public_path(). '/uploads',$imageName);

        $post = Post::create([
            'title'=> $request->title,
            'description'=> $request->description,
            'image'=> $imageName,

        ]);
        return response()->json([
            'status'=>True,
            'message'=>'post created sucessfully',
            'post'=> $post,
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data['post']=Post::select(
            'id',
            'title',
            'description',
            'image',

        )->where(['id'=>$id])->get();
       return response()->json([
            'status'=>True,
            'message'=>'Your single post',
            'data'=> $data,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          //
        $validateUser=Validator::make(
        $request->all(),
        [
            'title'=> 'required',
            'description'=> 'required',
            'image'=> 'required|mimes:png,jpg,jpeg.gif',
        ]
        );

        if($validateUser->fails()){ // fails is the inbuit medthod of validator
            return response()->json([
                'status'=>false,
                'message'=>'validation Error',
                'errors'=> $validateUser->errors()->all()
            ],401);
        }
        $post=Post::select('id','image')->get();
        if($request->image!=''){
            $path=public_path().'/uploads';

            if($post->image !=''&& $post->image!=null){
                $old_file=$path. $post->image;
                if(file_exists($old_file)){
                    unlink($old_file);#remove or unlink the old image

                }
            }
            
        $img=$request->image;
        $ext=$img->getClintOriginalExtension();
        $imageName= time().'.'.$ext;
        $img->move(public_path(). '/uploads',$imageName);
        }else{
            $imageName= $post->image;
        }


        $post = Post::where(['id'=>$id])->update([
            'title'=> $request->title,
            'description'=> $request->description,
            'image'=> $imageName,

        ]);
        return response()->json([
            'status'=>True,
            'message'=>'post updated sucessfully',
            'post'=> $post,
        ],200);
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $imagePath =Post::select('image')->where('id',$id)->get();
        $filepath=public_path().'/uploads'. $post[0]['image'];
        
        unlink($filepath);
        
        $post=Post::where('id',$id)->delete();

        $filepath=public_path().'/uploads'. $post[0]['image'];
        
        return response()->json([
            'status'=>true,
            'message'=>'your post has been removed',
            'post'=>$post,
        ],200);
    }
}
