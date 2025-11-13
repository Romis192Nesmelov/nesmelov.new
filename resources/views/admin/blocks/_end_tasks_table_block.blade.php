        </table>
        @include('admin.blocks._sum_tasks_block',[
            'sum' => $sum,
            'duty' => (isset($duty) && $duty ? $duty : null),
            'percents' => $percents, 'useDuty' => $useDuty
        ])
        @if ($slug && isset($useAddButton) && $useAddButton && Auth::user()->is_admin && $data['year'] == date('Y'))
            @include('admin.blocks._add_button_block',['href' => 'tasks/add/'.$slug, 'text' => __('Add a task')])
        @endif
        @include('admin.blocks._forming_csv_block')
    </div>
</div>
