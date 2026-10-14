<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Bonanza;
use App\Models\BonanzaAchiever;
use Illuminate\Http\Request;

class BonanzaController extends Controller
{
    public function index()
    {
        $bonanzas = Bonanza::latest()->paginate(10);
        return view('admin.bonanza.index', compact('bonanzas'));
    }
    
    public function create()
    {
        return view('admin.bonanza.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'trip_name' => 'required|string|max:255',
            'self_id' => 'nullable|integer',
            'direct' => 'required|numeric|min:0',
            'matching_business' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);
        
        Bonanza::create($request->all());
        return redirect()->route('admin.bonanza.index')->with('success','Bonanza created');
    }
    
    public function edit($id)
    {
        $bonanza = Bonanza::findOrFail($id);
        return view('admin.bonanza.edit', compact('bonanza'));
    }
    
    public function update(Request $request, $id)
    {
        $bonanza = Bonanza::findOrFail($id);
        
        $request->validate([
            'trip_name' => 'required|string|max:255',
            'self_id' => 'nullable|integer',
            'direct' => 'required|numeric|min:0',
            'matching_business' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);
        
        $bonanza->update($request->all());
        return redirect()->route('admin.bonanza.index')->with('success','Bonanza updated');
    }
    
    public function destroy($id)
    {
        Bonanza::findOrFail($id)->delete();
        return redirect()->route('admin.bonanza.index')->with('success','Bonanza deleted');
    }
    
    public function achieversList()
    {
        $achievers = BonanzaAchiever::with(['bonanza', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.bonanza.achievers', compact('achievers'));
    }
}