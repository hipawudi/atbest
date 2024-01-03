<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Form;
use Inertia\Inertia;
use App\Exports\EntryExport;
use App\Models\Entry;
use App\Models\EntryRecord;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;


class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Form $form)
    {
        //$form=Form::with('fields')->find($form->id);
        $entries = $form->tableEntries();
        return Inertia::render('Organization/FormEntries', [
            'organization' => session('organization'),
            'form' => $form,
            'entries' => $entries,
            'fields' => $form->fields,
            'entryColumns' => $form->entry_columns()
        ]);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form, $id)
    {
        Entry::where('id', $id)->delete();
        EntryRecord::where('entry_id', $id)->delete();

        return Redirect()->back();
        //
    }

    public function export(Form $form)
    {
        return Excel::download(new EntryExport($form), 'member.xlsx');
    }
}
