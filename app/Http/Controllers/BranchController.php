<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    /**
     * Display a listing of branches.
     */
    public function index(): View
    {
        $branches = Branch::withCount('schedules')->get();
        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create(): View
    {
        return view('branches.create');
    }

    /**
     * Store a newly created branch.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'branch_name' => ['required', 'string', 'max:255', 'unique:branches,branch_name'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')->with('success', 'Thêm cơ sở đào tạo thành công!');
    }

    /**
     * Show the form for editing the branch.
     */
    public function edit(Branch $branch): View
    {
        return view('branches.edit', compact('branch'));
    }

    /**
     * Update the specified branch.
     */
    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'branch_name' => ['required', 'string', 'max:255', 'unique:branches,branch_name,' . $branch->id],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Cập nhật thông tin cơ sở thành công!');
    }

    /**
     * Remove the specified branch.
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        $schedulesCount = $branch->schedules()->count();
        $branch->delete();

        return redirect()->route('branches.index')->with(
            'success',
            "Đã xóa cơ sở thành công" . ($schedulesCount > 0 ? " (và {$schedulesCount} lịch dạy liên quan)!" : "!")
        );
    }
}
