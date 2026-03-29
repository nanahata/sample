<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeBudget;

class HomebudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $homebudgets = HomeBudget::with('category')->orderBy('date', 'desc')->paginate(3);
        return view('homebudget.index', compact('homebudgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $result = HomeBudget::create([
            'date' => $request->date,
            'category_id' => $request->category,
            'price' => $request->price,
        ]);

        if (!empty($result)) {
            session()->flash('flash_message', '支出を登録しました');
        } else {
            session()->flash('flash_error_message', '支出の登録に失敗しました');
        }

        return redirect()->route('index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $homebudget = HomeBudget::find($id);
        return view('homebudget.edit', compact('homebudget'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $hasData = HomeBudget::where('id', '=', $request->id);
        if ($hasData->exists()) {
            $hasData->update([
                'date' => $request->date,
                'category_id' => $request->category_id,
                'price' => $request->price,
            ]);
            session()->flash('flash_message', '支出を更新しました');
        } else {
            session()->flash('flash_error_message', '支出の更新に失敗しました');
        }

        return redirect()->route('index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $homebudget = HomeBudget::find($id);
        $homebudget->delete();
        session()->flash('flash_message', '支出を削除しました');
        return redirect()->route('index');
    }
}
