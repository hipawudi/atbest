<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;
use App\Models\Entry;
use App\Models\EntryRecord;
use App\Models\Event;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FormController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Organization::class);
        $this->authorizeResource(Form::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Form::find($formId)->entries()->delete());
        // echo ($entries);
        // echo ($form);
        //dd(Organization::find(session('organization')->id)->forms);
        //$this->authorize('view',$organization);
        return Inertia::render('Organization/Forms',[
            //'organization' => Organization::find(session('organization')->id),
            'forms'=>Organization::find(session('organization')->id)->forms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form=Form::make([
            'organization_id'=>session('organization')->id,
            'require_login'=>false,
            'for_member'=>false,
            'published'=>false
        ]);
        $form->media;
        return Inertia::render('Organization/Form',[
            //'organization' => Organization::find(session('organization')->id),
            //'forms'=>Organization::find(session('organization')->id)->forms
            'form'=>$form
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
        $this->validate($request,[
            'organization_id' => 'required',
            'name'=>'required',
            'title'=>'required',
        ]);
        $form=Form::create($request->all());
        if($request->file('thumbnail_upload')){
            $file=$request->file('thumbnail_upload');
            $fileName=$form->id.'_'.$file->getClientOriginalName();
            $file->move(public_path('thumbnail/forms'), $fileName);
            $form->thumbnail='/thumbnail/forms/'.$fileName;
            $form->save();
        }
        
        // if($request->file('image')){
        //     $form->addMedia($request->file('image')[0]['originFileObj'])->toMediaCollection('form_banner');
        // }

        return to_route('manage.forms.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Form $form)
    {
        // $this->authorize('view',$organization);
        // $this->authorize('view',$form);

        echo 'edit form';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        //dd(Organization::find($form->organization_id));
        $form->media;
        return Inertia::render('Organization/Form',[
            //'organization' => Organization::find(session('organization')->id),
            //'forms'=>Organization::find(session('organization')->id)->forms
            'form'=>$form
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        $this->validate($request,[
            'organization_id' => 'required',
            'name'=>'required',
            'title'=>'required',
        ]);
        $data=$request->all();
        if($request->file('thumbnail_upload')){
            $file=$request->file('thumbnail_upload');
            $fileName=$form->id.'_'.$file->getClientOriginalName();
            $file->move(public_path('thumbnail/forms'), $fileName);
            $data['thumbnail']='/thumbnail/forms/'.$fileName;
        }
        $form->update($data);
        // if($request->file('image')){
        //     $form->addMedia($request->file('image')[0]['originFileObj'])->toMediaCollection('form_banner');
        // }
        return to_route('manage.forms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Form $form)
    {
        if($form->hasChild()){
            return redirect()->back()->withErrors(['message'=>'No permission or restriced deletion of records with child records.']);
        }else{
            $form->delete();
            return redirect()->back();
        }
    }
    public function backup(Form $form){
        //return response()->json($form);
        $data=new \stdClass();
        $form->fields;
        //$data->form=Form::with('fields')->find($formId);
        $data->form=$form;

        if($data->form){
            $data->entries=Entry::where('form_id',3)->with('records')->get();
            $file=\Storage::put('dbbackup/'.$data->form->organization_id.'/form_'.$data->form->id.'_'.time().'.txt',json_encode($data));
            if($file){
                $data->entries;
            };
            //dd($file);
            $ids=Entry::where('form_id',$data->form->id)->pluck('id')->toArray();
            EntryRecord::whereIn('entry_id',$ids)->delete();
            Entry::where('form_id',$data->form->id)->delete();
        }
        return response()->json($data);
    }

    public function deleteImage(Form $form){
        unlink(public_path($form->thumbnail));
        $form->thumbnail=null;
        $form->save();
        return redirect()->back();
    }

}