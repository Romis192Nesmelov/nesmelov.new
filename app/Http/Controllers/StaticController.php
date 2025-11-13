<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use Illuminate\View\View;

class StaticController extends Controller
{
    public function __invoke(): View
    {
        $data = [];
        $mainMenu = [];

        $data['branches'] = Branch::query()->where('active',1)->with('works')->get();
        foreach ($data['branches'] as $item) {
            $mainMenu[] = ['href' => $item->en, 'name' => $item->ru];
        }

        return view('home', [
            'mainMenu' => $mainMenu,
            'data' => $data,
        ]);

    }
}
