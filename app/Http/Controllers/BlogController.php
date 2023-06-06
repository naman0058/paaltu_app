<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use DataTables;

class BlogController extends Controller
{
	public function index()
	{
		$data=Blog::get();
		return view('admin.blog.index',[
			'datas'=>$data]);
	}

	public function create()
	{
		$data=Blog::get();
		return view('admin.blog.create',[
			'datas'=>$data]);
	}

	public function store(Request $request)
	{
        $request->validate([
            'title'=>'required',
            'image'=>'required',
            'description'=>'required',
            'background_color'=>'required',
           
        ]);

        $newData = new Blog();
        $newData->title = $request->title;
        $newData->description=$request->description;
        $newData->link = $request->link;
        $newData->background_color = $request->background_color;  
        $newData->created_by=\Auth::user()->id;
        $newData->updated_by=\Auth::user()->id;
        $newData->created_at=date('Y-m-d H:i:s');
        $newData->updated_at=date('Y-m-d H:i:s');
        $newData->image = $request->image;
        if($request->hasFile('image'))  
        {
          $file = ($request->file('image'));
          $name = $file->getClientOriginalName();
          $path = public_path('/blogImage'); 
          $file->move($path,$name);
          $newData->image=$name;
        }
        $newData->save();
        \Session::flash('message','Created Successfully');
        return redirect('blog');
    }

    public function filter_blog(Request $request)
    {
        if($request->ajax()){
          $data = Blog::select('blog.*');
          if($request->has('title') && !empty($request->title))
          {
            $data=$data->where('title','like','%'.$request->title.'%');
          }
         
          $data=$data->get();  
          return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('title', function($data) { return ucfirst($data->title); })
                ->addColumn('image', function($data) { 
                    if($data->image!='')
                    {
                        return '<img  src="'.asset('/public/blogImage').'/'.$data->image.'" style="width:30px;height:30px">';          
                    }
                    else
                    {
                        return '<img src="'. asset('/public/files/no image.jpg').'/'.$data->image.'" style="width:30px;height:30px">';
                    }
                    })

                ->rawColumns(['title','image','action'])
                ->addColumn('action',function($data){
                    $edit=url('blog/'.$data->id.'/edit');
                    return '<div class="d-flex"><button class="btn bg-primary btn-sm text-white rounded-circle blogViewBtn sharp mr-1" type="button" value="'.$data->id.'" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>&nbsp
                            <a href="'.$edit.'" class="btn bg-secondary btn-sm text-white rounded-circle"><i class="fa fa-pencil"></i></a>&nbsp
                            <button class="btn bg-danger btn-sm text-white rounded-circle deleteBtn" type="button" value="'.$data->id.'"><i class="fa fa-trash"></i></button></div>';
                         
                })
                ->setRowId(function ($data) {
                return "row_".$data->id;
                })
                ->make(true);     
            }
    }

    public function edit($id)
    {
        $blog=Blog::find($id);
        return view('admin.blog.edit',[
            'blogs'=>$blog]);
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'background_color'=>'required',
        ]);
   
       $newData =Blog::find($id);
       $newData->title = $request->title;
       $newData->description=$request->description;
       $newData->link = $request->link; 
       $newData->background_color = $request->background_color;
       $newData->updated_by = \Auth::user()->id;
       $newData->updated_at = date('Y-m-d H:i:s');
        if($request->hasFile('image'))
        {
          $file = ($request->file('image'));
          $name = $file->getClientOriginalName();
          $path = public_path('/blogImage'); 
          $file->move($path,$name);
          $newData->image=$name;
        }
        $newData->save();
        \Session::flash('message','Updated Successfully');
        return redirect('blog');
        
    }

    public function blogView(Request $request)
    {
        $data= Blog::find($request->id);
        $view=view('admin.blog.view',[
            'datas'=>$data]);
        echo $view;
    }

    public function updateImages(Request $request)
    {
        $data=Blog::find($request->update_image);
        $file=$request->file('edit_blog_image');
        $name=$file->getClientOriginalName();
        $path = public_path('/blogImage'); 
        $file->move($path,$name);
        $data->image = $name;  
        echo asset('public/blogImage/'.$name);
        $data->save();
    } 
    
    public function imagesDelete(Request $request)
    {
        $data=Blog::find($request->id);
        $file =public_path()."/blogImage/".$data->image;  
        unlink($file);
        $data->image='';
        $data->save(); 
    }

    public function destroy(Request $request)
    {
        $data=Blog::find($request->id)->delete();
        echo 1; 
    }

}