<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
 
class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }
 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'branch_name' => 'required|string',
            'address' => 'required|string'
        ]);
        Branch::create($request->all());
        return redirect()->route('branches.index')->with('success', 'Thêm cơ sở thành công!');
    }
  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
      
        return view('branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $branch->update($request->all());
        return redirect()->route('branches.index')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
