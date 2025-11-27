<?php

namespace App\Http\Controllers;
use App\Http\Requests\EditChapterRequest;
use App\Http\Requests\EditCustomerRequest;
use App\Http\Requests\EditSeoRequest;
use App\Http\Requests\EditSettingsRequest;
use App\Http\Requests\EditWorkRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Task;
use App\Models\User;
use App\Models\Work;
use App\Models\SentEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use SimpleXMLElement;

class AdminController extends UserController
{
    use HelperTrait;

    public function seo(): View
    {
        $this->breadcrumbs = ['seo' => 'SEO'];
        $this->data['metas'] = getMetas();
        $this->data['seo'] = getSeoTags();
        return $this->showView('seo');
    }

    public function settings(): View
    {
        $this->breadcrumbs = ['settings' => 'Настройки'];
        $this->data['settings'] = getSettings();
        $this->data['requisites'] = getRequisites();
        $this->getFixTax();
        return $this->showView('settings');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function chapters($slug=null, $subSlug=null): View
    {
        $this->breadcrumbs = ['chapters' => __('Landing\'s chapters')];
        if ($slug) {
            $this->data['chapter'] = Branch::query()->where('slug',$slug)->first();
            if (!$this->data['chapter']) abort(404);
            $this->breadcrumbs['chapters/'.$this->data['chapter']->slug] = $this->data['chapter'][app()->getLocale()];

            if (request()->has('id')) {
                $this->validate(request(), ['id' => $this->validationId.'works']);
                $this->data['work'] = Work::query()->where('id',request()->id)->first();
                $this->breadcrumbs['chapters/' . $this->data['chapter']->slug.'?id='.$this->data['work']->id] = $this->data['work']->name;
                return $this->showView('work');
            } elseif ($subSlug == 'add') {
                $this->breadcrumbs['chapters/' . $this->data['chapter']->slug.'/add'] = __('Adding Work');
                return $this->showView('work');
            } else {
                return $this->showView('chapter');
            }
        } else {
            $this->data['chapters'] = Branch::all();
            return $this->showView('chapters');
        }
    }

    public function statistics($slug=null): View
    {
        $this->data['years'] = [];
        $this->data['done_tasks_for_all_years'] = [];

        if ($slug && preg_match($this->regYear,$slug)) {
            if ($slug < 2018) abort(404);
            $this->data['year'] = $slug;
        } else $this->data['year'] = date('Y');

        if (request()->has('slider-months') && request()->input('slider-months')) {
            list($minVal, $maxVal) = explode(',', request()->input('slider-months'));
        } else {
            $minVal = 1;
            $maxVal = $this->data['year'] != date('Y') ? 12 : date('n');
        }

        for($y=2018;$y<=(int)date('Y');$y++) {
            $this->data['years'][] = $y;
            $this->data['done_tasks_for_all_years'][$y] = [];
            for ($m=$minVal;$m<=$maxVal;$m++) {
                $this->data['done_tasks_for_all_years'][$y][$m] = 0;
            }
        }

        $this->data['max_month'] = $this->data['year'] != date('Y') ? 12 : date('n');
        $this->data['min_val'] = $minVal;
        $this->data['max_val'] = $maxVal;

        $tasks = Task::query()->with(['subTasks','statistics'])->get();
        foreach ($tasks as $task) {
            // Merge statistics
            if (count($task->statistics)) {
                $statusesTask = [];
                foreach ($task->statistics as $statistic) {
                    $add = true;
                    if (count($statusesTask)) {
                        foreach ($statusesTask as $status) {
                            if ($status['status'] == $statistic->status && $status['year'] == $statistic->created_at->format('Y') && $status['month'] == $statistic->created_at->format('Y')) {
                                $statistic->delete();
                                $add = false;
                            }
                        }
                    }
                    if ($add) $statusesTask[] = [
                        'id' => $statistic->id,
                        'status' => $statistic->status,
                        'year' => $statistic->created_at->format('Y'),
                        'month' => $statistic->created_at->format('Y')
                    ];
                }
            }

            // Filling data of done tasks for all years
            $month = null;
            $year = null;
            if ($task->status == 1 || $task->status == 2 || $task->status == 7) {
                if (date('n',$task->completion_time) >= $minVal && date('n',$task->completion_time) <= $maxVal) {
                    $year = date('Y',$task->completion_time);
                    $month = date('n',$task->completion_time);
                } elseif ($task->payment_time && date('n',$task->payment_time) >= $minVal && date('n',$task->payment_time) <= $maxVal) {
                    $year = date('Y',$task->payment_time);
                    $month = date('n',$task->payment_time);
                }
                if ($month && $year) $this->data['done_tasks_for_all_years'][$year][$month] += (int)calculateOverTaskVal($task, true, true, true, true);
            }
        }

        $this->getStatuses();
        $this->getStatusesSimple();
        $this->getFixTax();
        $this->getBackUri(request()->path());
        $this->data['income_statuses'] = getIncomeStatuses();

        $this->breadcrumbs['statistics/'.$this->data['year']] = 'Статистика за '.$this->data['year'].' год';
        $min = strtotime('01.'.$this->data['min_val'].'.'.$this->data['year']);
        $max = $this->data['max_val'] == 12 ? strtotime('01.01'.($this->data['year']+1)) : strtotime('01.'.($this->data['max_val']+1).'.'.$this->data['year']);

        $this->data['last_day_in_month'] = $this->data['year'] != date('Y') ? date('t',$min) : date('j');

        $this->data['tasks'] = Task::query()->where(function($query) use ($min, $max) {
            $query->where('start_time','>=',$min)->where('start_time','<=',$max);
//                                })->orWhere(function($query) {
//                                    $minDate = Carbon::createFromDate($this->data['year'], $this->data['min_val'], 1, 'Europe/Moscow');
//                                    $maxDate = Carbon::createFromDate($this->data['year'], $this->data['max_val']+1, 1, 'Europe/Moscow');
//                                    $query->where('created_at','>=',$minDate)->where('created_at','<=',$maxDate);
        })->orWhere(function($query) use ($min, $max) {
            $query->where('completion_time','>=',$min)->where('completion_time','<=',$max);
        })->orWhere(function($query) use ($min, $max) {
            $query->where('payment_time','>=',$min)->where('payment_time','<=',$max);
        })->orderBy('start_time','desc')
            ->with(['subTasks','statistics','customer'])
            ->get();

        $this->getTasksInWork();

        $this->data['done_tasks'] = Task::query()->where(function($query) use ($min, $max) {
            $query->where('status',1)->where('completion_time','>=',$min)->where('completion_time','<=',$max);
        })->orWhere(function($query) use ($min, $max) {
            $query->where('status',1)->where('payment_time','>=',$min)->where('payment_time','<=',$max);
        })
            ->with(['subTasks','statistics','customer'])
            ->get();

        $this->data['wait_tasks'] = Task::query()
            ->where('status',2)
            ->where('completion_time','>=',$min)
            ->where('completion_time','<=',$max)
            ->with(['subTasks','statistics','customer'])
            ->get();
        $this->data['in_work_tasks'] = Task::query()
            ->where('status',3)
            ->where('paid_off','>',0)
            ->where('start_time','>=',$min)
            ->where('start_time','<=',$max)
            ->with(['subTasks','statistics','customer'])
            ->get();

        return $this->showView('statistics');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sentEmails(): View
    {
        $this->breadcrumbs = ['sent-emails' => __('Sent')];
        if (request()->has('id')) {
            $this->validate(request(), ['id' => $this->validationId.'sent_emails']);
            $this->data['email'] = SentEmail::query()->find(request()->id);
            $this->breadcrumbs['sent-emails/?id='.request()->id] = $this->data['email']->created_at;
            return $this->showView('sent_email');
        } else {
            $this->data['emails'] = SentEmail::query()->orderBy('id','desc')->with('user')->get();
            return $this->showView('sent_emails');
        }
    }

    public function editSettings(EditSettingsRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        $settings = getSettingsXML();

        foreach ($fields as $field => $val) {
            if (isset($settings->settings->$field)) $settings->settings->$field = $val;
            elseif (isset($settings->requisites->$field)) $settings->requisites->$field = $val;
        }

        $this->saveSettings($settings);

        $this->getFixTax();
        $this->data['fix_tax']->update(['value' => $fields['fix_tax']]);
        $this->saveCompleteMessage();
        return redirect(url('/admin/settings'));
    }

    public function editChapter(EditChapterRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        $fields = $this->convertCheckFields($fields, ['active']);

        if (!$chapter = Branch::query()->where('id',$request->id)->first()) abort(404);

        foreach (['icon','image'] as $imageField) {
            if (isset($fields[$imageField])) {
                $this->deleteFile($chapter[$imageField]);
                $fields[$imageField] = $this->putFile($fields[$imageField]);
            }
        }

        $chapter->update($fields);
        $this->saveCompleteMessage();
        return redirect(url('/admin/chapters/'.$chapter->slug));
    }

    public function editCustomer(EditCustomerRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        $fields = $this->convertTimeFields($fields, ['contract_date']);
        $fields = $this->convertCheckFields($fields, ['save_contract']);
        if (!$fields['save_contract']) $fields['contract'] = null;

        if ($request->has('id')) {
            $this->getBackUri(request()->path());
            $customer = Customer::query()->where('id',$request->id)->first();
            if (Gate::allows('customer-edit', $customer)) $customer->update($fields);
            else abort(403, __('You do not have the rights to perform this operation!'));
        } else {
            if (Gate::denies('is-admin')) $fields['type'] = 2;
            Customer::query()->create($fields);
        }
        $this->saveCompleteMessage();
        return redirect(url('/admin/customers'));
    }

    public function editSeo(EditSeoRequest $request): RedirectResponse
    {
        $fields = $request->validated();

        $settings = getSettingsXML();
        if ($request->has('title')) $settings->seo->title = $fields['title'];
        $metas = getMetas();

        foreach ($metas as $meta => $params) {
            $settings->seo->$meta = $fields($meta);
        }
        $this->saveSettings($settings);

        $this->saveCompleteMessage();
        return redirect(url('/admin/seo'));
    }

    public function editWork(EditWorkRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        $fields = $this->convertCheckFields($fields, ['active']);

        if ($request->has('id')) {
            $work = Work::query()->where('id',$request->id)->with('branch')->first();
            $fields = $this->processingWorkImages($fields);
            foreach (['preview','full'] as $imageField) {
                if (isset($fields[$imageField]) && $work[$imageField]) {
                    $this->deleteFile($work[$imageField]);
                }
            }
            $work->update($fields);
        } else {
            $fields = $this->processingWorkImages($fields);
            $work = Work::query()->create($fields);
        }

        $this->saveCompleteMessage();
        return redirect('/admin/chapters/'.$work->branch->slug);
    }

    public function deleteUser(): JsonResponse
    {
        return $this->deleteSomething(new User());
    }

    public function deleteCustomer(): JsonResponse
    {
        return $this->deleteSomething(new Customer());
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deleteWork(): JsonResponse
    {
        $this->validate(request(), ['id' => $this->validationId.'works']);
        $work = Work::query()->where('id',request()->id)->with('branch')->first();

        if ($work->branch->id == 2) $filesFields = ['preview'];
        elseif ($work->branch->id == 5) $filesFields = ['preview','url'];
        else $filesFields = ['full','preview'];

        foreach ($filesFields as $imageField) {
            $this->deleteFile($work[$imageField]);
        }
        return $this->deleteSomething(new Work());
    }

    public function deleteSentEmail()
    {
        return $this->deleteSomething(new SentEmail());
    }

    private function processingWorkImages(array $fields): array
    {
        foreach (['preview','full'] as $imageField) {
            if (isset($fields[$imageField])) {
                $fields[$imageField] = $this->putFile($fields[$imageField], 'portfolio');
            }
        }
        return $fields;
    }

    private function saveSettings(SimpleXMLElement $settings): void
    {
        $settings->asXML(env('SETTINGS_XML'));
    }
}
