<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Breed;
use App\Models\PetCategory;
use App\Models\PetProfile;
use App\Models\PetGallery;
use App\Models\User;
use DataTables;

class PetprofileController extends Controller
{
    public function index()
    {
        
        $profile=PetProfile::get();
        return view('admin.petprofile.index',[
            'profiles'=>$profile]);

    }
  
    public function create()
    {
       $breed=Breed::get();
       $profile=PetProfile::get();
       $pet=PetCategory::get();
	   $users=User::where('user_type','user')->get();
       return view('admin.petprofile.create',[
            'profiles'=>$profile,
            'pets'=>$pet,
            'breeds'=>$breed,
			'users'=>$users
	   ]);
    }
  
    public function store(Request $request)
    {
        $request->validate([
            'pet_name'=>'required',
            'date_of_birth'=>'required',
            'gender'=>'required',
        ]);
        $explodehiddenVal=explode(',',$request->hiddenVal);
        $data = new PetProfile();
        $data->pet_name = $request->pet_name;
        $data->pet_category_id = $request->pet_category_id;
        $data->breed_id = $request->breed_id;
        $data->gender = $request->gender;
        $data->date_of_birth = $request->date_of_birth;
        $data->user_id = $request->user_id;
        $data->about = $request->about;
        $data->created_by=\Auth::user()->id;
        $data->updated_by=\Auth::user()->id;
        $data->created_at=date('Y-m-d H:i:s');
        $data->updated_at=date('Y-m-d H:i:s');     
        if($data->save())
        {
            if($request->has('pet_image'))
            {
                foreach($request->file('pet_image') as $key => $image)
                { 
                    $path=public_path().'/images/pet/files';  
                    $name = date('dymhis').time().'.'.$image->getClientOriginalName();
                    $image->move($path, $name);  
                    $img= new PetGallery();
                    $img->pet_id=$data->id;
                    $img->image=$name;
                    if($request->is_default==$explodehiddenVal[$key])
                    {
                        $img->is_default='yes';
                    }else
                    {
                        $img->is_default='no';
                    }
                    $img->save();  
                }  
            }
        }
		
        \Session::flash('message','Created Successfully');
        return redirect('pet-profile');    
    }

    public function filter_pet_profile(Request $request)
    {
        $data = PetProfile::with(['breed','category']);
        if($request->has('pet_name') && !empty($request->pet_name))
        {
            $data=$data->where('pet_name','like','%'.$request->pet_name.'%');
        }
        $data=$data->get();  

        return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('pet_name', function($data) { return ucfirst($data->pet_name); })
                ->addColumn('category_name', function($data) { return isset($data->category)  ? ucfirst($data->category->name): 'N/A'; })
                ->addColumn('breed', function($data) { return isset($data->breed) ? ucfirst($data->breed->breed_name): 'N/A'; })
                ->rawColumns(['pet_name','category_name','breed','action'])
                ->addColumn('action',function($data){
                    $edit=url('pet-profile/'.$data->id.'/edit');
                    return '<div class="d-flex"><button class="btn bg-primary btn-sm text-white rounded-circle petViewBtn sharp mr-1" type="button" value="'.$data->id.'" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>&nbsp
                            <a href="'.$edit.'" class="btn bg-secondary btn-sm text-white rounded-circle"><i class="fa fa-pencil"></i></a>&nbsp

                            <button class="btn bg-danger btn-sm text-white rounded-circle deleteBtn" type="button" value="'.$data->id.'"><i class="fa fa-trash"></i></button></div>';
                         
                })
                ->setRowId(function ($data) {
                return "row_".$data->id;
                })
                ->make(true);
    }
     

    public function petprofileView(Request $request)
    {
        $petitems=PetGallery::where('pet_id',$request->id)->get();
        $profile= PetProfile::find($request->id);
        $view=view('admin.petprofile.view',[
            'profiles'=>$profile,
            'petitems'=>$petitems    
        ]);
        echo $view;
    }


    public function edit($id)
    {
        $profile=PetProfile::find($id);
        $petitems=PetGallery::where('pet_id', $id)->get();
        $item_count=PetGallery::where('pet_id',$profile->id)->count();
        $breed=Breed::get();
        $pet=PetCategory::get();
		$users=User::where('user_type','user')->get();
        return view('admin.petprofile.edit',[
            'breeds'=>$breed,
            'pets'=>$pet,
            'profiles'=>$profile,
            'petitems'=>$petitems,
            'item_count'=>$item_count,
			'users'=>$users
		]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pet_name'=>'required',
            'date_of_birth'=>'required',
            'gender'=>'required',
        ]);

       $explodehiddenVal=explode(',',$request->hiddenVal);
       $ex_img_array=explode(',',$request->image_edit_id);
       $data=PetGallery::find($id);
       $data =PetProfile::find($id);
       $data->pet_name = $request->pet_name;
       $data->gender = $request->gender;
       $data->date_of_birth = $request->date_of_birth;
       $data->pet_category_id = $request->pet_category_id;
       $data->breed_id = $request->breed_id;
	   $data->user_id = $request->user_id;
	   $data->about = $request->about;
       $data->updated_by = \Auth::user()->id;
       $data->updated_at = date('Y-m-d H:i:s');            
       if($data->save())
        {
            if(count($request->rowid) != 0)   
            {
                foreach($request->rowid as $key => $item)
                {
                    $img= PetGallery::find($item);
                    if($request->is_default == explode(',',$request->imagesvalue)[$key])
                    {
                        $img->is_default='yes';
                    }else
                    {
                        $img->is_default='no';
                    }
                    $img->save(); 
               } 
            } 
            if($request->hasFile('pet_image'))
            {
                foreach($request->file('pet_image') as $key => $image)
                { 
                    $path=public_path().'/images/pet/files';  
                    $name = date('dymhis').time().'.'.$image->extension();
                    $image->move($path, $name);  
                    $img= new PetGallery();
                    $img->pet_id=$data->id;
                    $img->image=$name;
                    if($request->is_default == explode(',',$request->imagesvalue1)[$key])
                    {
                        $img->is_default='yes';
                    } 

                    $img->save();  
                }  

            }
            if($request->hasFile('pet_image_edit'))
            { 
        
                foreach($request->file('pet_image_edit') as $key => $image)
                {  
                    
                    $path=public_path().'/images/pet/files';      
                    $name = date('dymhis').time().'.'.$image->extension();
                    $image->move($path, $name);  
                    $img= PetGallery::find($request->rowid[$key]);
                    $img->pet_id=$data->id;
                    $img->image=$name;           
                    if($request->is_default == explode(',',$request->imagesvalue)[$key])
                    {
                        $img->is_default='yes';
                    }else
                    {
                        $img->is_default='no';
                    }
                    $img->save();           
                }
            }     
            \Session::flash('message','Updated Successfully');
            return redirect('pet-profile');
        }
    }

    public function removeImage(Request $request)
    {
        $image_row=$request->image_row;
        $imagedata=PetGallery::find($image_row);
        $path=public_path().'/images/pet/files/'.$imagedata->image; 
        if(file_exists( $path)){         
            //File::delete($path);
            unlink($path);
        }
        $imagedata=PetGallery::find($image_row)->delete();
        echo "Image deleted";
    }

   
    public function destroy(Request $request)
    {
        $data=PetProfile::find($request->id)->delete();
        echo 1; 
    }
    
}


   