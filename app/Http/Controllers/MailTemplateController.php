<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MailTemplate;
use Illuminate\Support\Str;

class MailTemplateController extends Controller
{

public function index()
{
    return view('superadmin.mail_templates.index');
}

public function getData(Request $request)
{
    $query = MailTemplate::query();

    $total = $query->count();

    $filtered = $query;

    // Search
    if ($request->has('search') && $request->search['value']) {
        $search = $request->search['value'];
        $filtered = $filtered->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('subject', 'like', "%$search%")
              ->orWhere('slug', 'like', "%$search%");
        });
    }

    $totalFiltered = $filtered->count();

    // Sorting
    $columns = ['id', 'name', 'slug', 'subject', 'status']; // Add slug here
    $orderColumn = $columns[$request->order[0]['column']] ?? 'id';
    $orderDir = $request->order[0]['dir'];

    $data = $filtered
        ->orderBy($orderColumn, $orderDir)
        ->offset($request->start)
        ->limit($request->length)
        ->get();

    $jsonData = [
        "draw" => intval($request->draw),
        "recordsTotal" => $total,
        "recordsFiltered" => $totalFiltered,
        "data" => []
    ];

    foreach ($data as $row) {
        $jsonData['data'][] = [
            'id' => $row->id,
            'name' => $row->name,
            'slug' => $row->slug,
            'subject' => $row->subject,
            'status' => $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>',
           'action' => '
    <a href="'.route('superadmin.mailtemplates.preview', $row->id).'" class="btn btn-sm btn-info" target="_blank">Preview</a>
    <a href="'.route('superadmin.mailtemplates.edit', $row->id).'" class="btn btn-sm btn-primary">Edit</a>
    <form action="'.route('superadmin.mailtemplates.destroy', $row->id).'" method="POST" style="display:inline;" class="delete-template-form">
        '.csrf_field().method_field('DELETE').'
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>
'

        ];
    }

    return response()->json($jsonData);
}

public function preview(MailTemplate $mailTemplate)
{
    return view('superadmin.mail_templates.preview', compact('mailTemplate'));
}
public function create()
{
    return view('superadmin.mail_templates.create');
}


public function edit($id)
{
    $mailTemplate = MailTemplate::findOrFail($id);
    // dd($mailTemplate);
    return view('superadmin.mail_templates.edit', compact('mailTemplate'));
}




public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'template' => 'required|string',
        'status' => 'required|boolean',
    ]);

    $slug = Str::slug($request->name);

    // Ensure slug is unique (optional enhancement)
    $originalSlug = $slug;
    $counter = 1;
    while (MailTemplate::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $counter++;
    }

    MailTemplate::create([
        'name' => $request->name,
        'slug' => $slug,
        'subject' => $request->subject,
        'template' => $request->template,
        'status' => $request->status,
    ]);

    return redirect()->route('superadmin.mailtemplates.index')->with('success', 'Mail template created.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'template' => 'required|string',
        'status' => 'required|boolean',
    ]);

    $mailTemplate = MailTemplate::findOrFail($id);

    $slug = Str::slug($request->name);

    // Ensure updated slug is unique if name has changed
    $originalSlug = $slug;
    $counter = 1;
    while (MailTemplate::where('slug', $slug)->where('id', '!=', $id)->exists()) {
        $slug = $originalSlug . '-' . $counter++;
    }

    $mailTemplate->update([
        'name' => $request->name,
        'slug' => $slug,
        'subject' => $request->subject,
        'template' => $request->template,
        'status' => $request->status,
    ]);

    return redirect()->route('superadmin.mailtemplates.index')->with('success', 'Mail template updated.');
}


public function destroy($id)
{
    $mailTemplate = MailTemplate::findOrFail($id);
    $mailTemplate->delete();

    return redirect()->route('superadmin.mailtemplates.index')->with('success', 'Mail template deleted.');
}

}