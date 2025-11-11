<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use Illuminate\View\View;

class StaticController extends Controller
{
    use SettingsTrait;

    public function __invoke(): View
    {
        $data = [];
        $mainMenu = [];
        $data['seo'] = $this->getSeoTags();

        $data['branches'] = Branch::query()->where('active',1)->with('works')->get();
        foreach ($data['branches'] as $item) {
            $mainMenu[] = ['href' => $item->eng, 'name' => $item->rus];
        }

        return view('home', [
            'mainMenu' => $mainMenu,
            'data' => $data,
            'metas' => $this->metas
        ]);

    }
}
