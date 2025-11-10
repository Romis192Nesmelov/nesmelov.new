<?php

namespace App\Http\Controllers;
//use App\Bank;
//use App\Bill;
//use App\Branch;
//use App\Customer;
//use App\FixTax;
//use App\Jobs\SendMessage;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\Gate;
//use Illuminate\Support\Facades\Helper;
//use App\SubTask;
//use App\Task;
//use App\Message;
//use App\User;
//use App\SentEmail;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Mail;
//use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

trait HelperTrait
{

    public $breadcrumbs = [];
    public $data = [];

    public $taskGetCondition = [
        'done' => 'Оплачено',
        'wait' => 'Завершено',
        'work' => 'В работе',
        'hold' => 'Отложено',
        'returned' => 'Доработка',
        'fake_made' => 'Фейк создан',
        'fake_done' => 'Фейк оплачен'
    ];
    public $billsStatuses = ['Оплачен','Выставлен на всю сумму','Выставлен на часть суммы'];
    public $incomeStatuses = ['Выплачено','Завершено','Предоплата'];

    public $validationPhone = 'regex:/^((\+)?(\d)(\s)?(\()?[0-9]{3}(\))?(\s)?([0-9]{3})(\-)?([0-9]{2})(\-)?([0-9]{2}))$/';
    public $validationId = 'required|integer|exists:';
    public $validationPassword = 'required|confirmed|min:3|max:50';
    public $validationLoginPassword = 'required|min:3|max:50';
    public $validationImage = 'mimes:jpeg|min:5|max:5000';
    public $validationContactPerson = 'nullable|min:3|max:255';
    public $validationEmail = 'nullable|email';
    public $validationName = 'required|min:5|max:255';
    public $validationValue = 'required|integer|max:2000000';
    public $validationDate = 'required|regex:/^((\d){2}\/(\d){2}\/(\d){4})$/';
    public $validationBillNumber = 'required|integer|min:1|unique:bills,number';
    public $validationTaskId = 'required|integer|exists:tasks,id';
    public $validationCustomerId = 'required|integer|exists:customers,id';
    public $validationBankName = 'required|min:10|max:255';
    public $validationBankId = 'required|size:9';
    public $validationCheckingAccount = 'required|min:20|max:24';
    public $validationCorrespondentAccount = 'required|min:20|max:24';
    public $regYear = '/^(20(\d){2})$/';
//    public $metas = [
//        'meta_description' => ['name' => 'description', 'property' => false],
//        'meta_keywords' => ['name' => 'keywords', 'property' => false],
//        'meta_twitter_card' => ['name' => 'twitter:card', 'property' => false],
//        'meta_twitter_size' => ['name' => 'twitter:size', 'property' => false],
//        'meta_twitter_creator' => ['name' => 'twitter:creator', 'property' => false],
//        'meta_og_url' => ['name' => false, 'property' => 'og:url'],
//        'meta_og_type' => ['name' => false, 'property' => 'og:type'],
//        'meta_og_title' => ['name' => false, 'property' => 'og:title'],
//        'meta_og_description' => ['name' => false, 'property' => 'og:description'],
//        'meta_og_image' => ['name' => false, 'property' => 'og:image'],
//        'meta_robots' => ['name' => 'robots', 'property' => false],
//        'meta_googlebot' => ['name' => 'googlebot', 'property' => false],
//        'meta_google_site_verification' => ['name' => 'robots', 'property' => false],
//    ];

//    public $youHaveNoRights = 'Для совершения данной операции у Вас нет прав!';
//    public $wrongCompletionTime = 'Не верное время выполнения!';
//    public $changedTaskStatus = 'Изменен статус задачи';
//    public $changedSubTaskStatus = 'Изменен статус задачи подзадачи';
//    public $changedTaskCompletionTime = 'Изменено время выполнения задачи';
//    public $changedSubTaskCompletionTime = 'Изменено время выполнения подзадачи';
//    public $changedTaskPaymentTime = 'Изменено время предполагаемой оплаты';

    public function saveCompleteMessage()
    {
        Session::flash('message','Сохранение произведено');
    }

//    public function getStatuses()
//    {
//        $this->data['statuses'] = [];
//        $key = 1;
//        foreach ($this->taskGetCondition as $description) {
//            $this->data['statuses'][] = ['val' => $key, 'descript' => $description];
//            if ($key == 5 && ( !isset($this->data['task']) || (isset($this->data['task']) && $this->data['task']->status <= 5) ) ) break;
//            else $key++;
//        }
//    }
//
//    public function getStatusesSimple()
//    {
//        $this->data['statuses_simple'] = [];
//        foreach ($this->taskGetCondition as $description) {
//            $this->data['statuses_simple'][] = $description;
//        }
//    }
//
//    public function getBackUri($path)
//    {
//        Session::put('back_uri',$path);
//    }
//
//    public function getSidebar()
//    {
//        $this->data['sidebar'] = count($this->data['tasks']) || (isset($this->data['own_tasks']) && count($this->data['own_tasks']));
//    }
//
//    public function getFixTax()
//    {
//        $this->data['fix_tax'] = FixTax::where('year',isset($this->data['year']) ? $this->data['year'] : date('Y'))->first();
//    }
//
//    public function getTaskValidationSomeFields($customerId, $validationArr, $timeFields)
//    {
//        $customer = Customer::findOrFail($customerId);
//        $validationArr['status'] = 'required|integer|min:1|max:5';
//        if ($customer->ltd != 2 && !in_array('convention_date', $timeFields)) $timeFields[] = 'convention_date';
//        return [$validationArr, $timeFields];
//    }
//
//    public function getSimpleTaskStatus($taskStatus)
//    {
//        $k = 1;
//        $status = '';
//        foreach ($this->taskGetCondition as $status => $description) {
//            if ($taskStatus == $k) {
//                $status = $description;
//                break;
//            }
//            $k++;
//        }
//        return $status;
//    }
//
//    protected function getTasksInWork($status=null)
//    {
//        if ((int)$this->data['year'] == (int)date('Y') && (!$status || $status == 3)) {
//            if (Gate::allows('is-admin')) $this->data['work_tasks'] = Task::where('status',3)->count();
//            else $this->data['work_tasks'] = Task::where('status',3)->where('user_id',Auth::id())->orWhere('owner_id',Auth::id())->count();
//        }
//    }
//
//    public function getBaseFieldsMailMessage($task)
//    {
//        $mailFields = [];
//        $mailFields['id'] = $task->id;
//        $mailFields['name'] = $task->name;
//        $mailFields['customer'] = isset($task->task) ? $task->task->customer->name : $task->customer->name;
//        $mailFields['parent_id'] = isset($task->task) ? $task->task->id : null;
//        $mailFields['parent_name'] = isset($task->task) ? $task->task->name : null;
//        return $mailFields;
//    }
//
//    public function getNewTaskFieldsMailMessage($task,$subTask,$fields)
//    {
//        $fields['id'] = $subTask ? $subTask->id : $task->id;
//        $fields['parent_id'] = $subTask ? $subTask->task->id : null;
//        $fields['parent_name'] = $subTask ? $subTask->task->name : null;
//        $fields['customer'] = $task->customer->name;
//        $fields['email'] = $task->email ? $task->email : $task->customer->email;
//        $fields['phone'] = $task->phone ? $task->phone : $task->customer->phone;
//        $fields['contact_person'] = $task->contact_person ? $task->contact_person : $task->customer->contact_person;
//        $fields['status'] = $this->getSimpleTaskStatus($subTask ? $subTask->status : $task->status);
//        $fields['owner'] = $task->owner->name;
//        $fields['user'] = $task->user->name;
//        return $fields;
//    }
//
//    public function processingWorkImageFields($work)
//    {
//        return array_merge(
//            $this->processingImage($work, 'preview', $work->branch->eng.'_'.$work->id.'_preview', 'images/portfolio/'.$work->branch->eng),
//            $this->processingImage($work, 'full', $work->branch->eng.'_'.$work->id.'_full', 'images/portfolio/'.$work->branch->eng)
//        );
//    }
//
//    public function processingImage(Model $model, $field, $name=null, $path=null)
//    {
//        $imageField = [];
//        if ($this->request->hasFile($field)) {
//            $this->unlinkFile($model, $field);
//
//            $info = pathinfo($model[$field]);
//            $imageName = ($name ? $name : $info['filename']).'.'.$this->request->file($field)->getClientOriginalExtension();
//            $path = $path ? $path : $info['dirname'];
//
//            $this->request->file($field)->move(base_path('public/'.$path),$imageName);
//            $imageField[$field] = $path.'/'.$imageName;
//        }
//        return $imageField;
//    }
//
//    public function processingFields($checkboxFields = null, $ignoreFields = null, $timeFields = null, $colorFields = null)
//    {
//        $exceptFields = ['_token','id'];
//        if ($ignoreFields) {
//            if (is_array($ignoreFields)) $exceptFields = array_merge($exceptFields, $ignoreFields);
//            else $exceptFields[] = $ignoreFields;
//        }
//        $fields = $this->request->except($exceptFields);
//
//        if ($checkboxFields) {
//            if (is_array($checkboxFields)) {
//                foreach ($checkboxFields as $field) {
//                    $fields[$field] = isset($fields[$field]) && $fields[$field] == 'on' ? 1 : 0;
//                }
//            } else {
//                $fields[$checkboxFields] = isset($fields[$checkboxFields]) && $fields[$checkboxFields] == 'on' ? 1 : 0;
//            }
//        }
//
//        if ($timeFields) {
//            if (is_array($timeFields)) {
//                foreach ($timeFields as $field) {
//                    $fields[$field] = strtotime($this->convertTime($fields[$field]));
//                }
//            } else {
//                $fields[$timeFields] = strtotime($this->convertTime($fields[$timeFields]));
//            }
//        }
//
//        if ($colorFields) {
//            if (is_array($colorFields)) {
//                foreach ($colorFields as $field) {
//                    $fields[$field] = $this->convertColor($fields[$field]);
//                }
//            } else {
//                $fields[$colorFields] = $this->convertColor($fields[$colorFields]);
//            }
//        }
//        return $fields;
//    }
//
//    protected function checkTaskMessages()
//    {
//        foreach ($this->data['task']->messages as $message) {
//            if ($message->owner && $message->user) {
//                if (Gate::allows('owner-or-user-message-not-admin',$message)) {
//                    $this->setSeenMessage($message);
//                }
//            } else $message->delete();
//        }
//    }
//
//    protected function checkTaskEdit($task)
//    {
//        if (Helper::forbbidenTaskEdit($task)) abort(403);
//    }
//
//    public function sendNewTaskMessage($sendMail,$task,$fields)
//    {
//        if ($sendMail)
//            $this->sendMessage(
//                $task->owner->email,
//                ($task->owner->email != $task->user->email && $task->user->send_email ? $task->user->email : null),
//                'new_task',
//                $fields
//            );
//    }
//
//    public function checkTaskStatus($fields)
//    {
//        if ($fields['status'] == 5) $fields['completion_time'] = time() + (60 * 60 * 24 * 2);
//        return $fields;
//    }
//
//    public function changeBillsBrothersStatus($signing, Task $task, $billId=null)
//    {
//        if ($signing == 3 && $task->paid_off && count($task->bills) > 1) {
//            foreach ($task->bills as $bill) {
//                if (!$billId || ($billId && $bill->id != $billId)) {
//                    $bill->signing = 3;
//                    $bill->save();
//                }
//            }
//        }
//    }
//
//    public function changeTaskStatus($task,$status)
//    {
//        $this->updateStatistics($status, $task);
//        if ( ($status == 1 || $status == 2) && count($task->subTasks) ) {
//            SubTask::where('task_id',$task->id)->update(['status' => $status]);
//        }
//
//        if ($status == 1 && count($task->bills)) {
//            Bill::where('task_id',$task->id)->update(['status' => 1]);
//        }
//    }
//
//    public function deleteSomething(Model $model, $files=null, $checkRightsField=null)
//    {
//        $table = $model->findOrFail($this->request->id);
//        if (
//            ($model instanceof User && $this->request->id == 1) ||
//            ($checkRightsField && Gate::denies('check-rights',[$table, $checkRightsField]))
//        ) response()->json(['success' => false]);
//
//        if ($model instanceof User) {
//
//            if (count($table->tasks)) {
//                $this->changeUserTask($table->tasks, 'user_id');
//            }
//            if (count($table->ownTasks)) {
//                $this->changeUserTask($table->ownTasks, 'owner_id');
//            }
//            if (count($table->bills)) {
//                foreach ($table->bills as $bill) {
//                    $bill->user_id = 1;
//                    $bill->save();
//                }
//            }
//        }
//
//        $table->delete();
//
//        if ($files) {
//            if (is_array($files)) {
//                foreach ($files as $file) {
//                    $this->unlinkFile($table, $file);
//                }
//            } else $this->unlinkFile($table, $files);
//        }
//        return response()->json(['success' => true]);
//    }
//
//    public function createTaskMessage($task,$mailView,$mailFields,$messageText,$messageStatus,$sendMail)
//    {
//        if (isset($task->task)) {
//            $taskId = $task->task->id;
//            $subTaskId = $task->id;
//            $owner = $task->task->owner;
//            $user = $task->task->user;
//        } else {
//            $taskId = $task->id;
//            $subTaskId = null;
//            $owner = $task->owner;
//            $user = $task->user;
//        }
//
//        if ($sendMail)
//            $this->sendMessage(
//                $owner->email,
//                ($owner->email != $user->email && $user->send_email ? $user->email : null),
//                $mailView,
//                $mailFields
//            );
//
//        Message::create([
//            'message' => $messageText,
//            'owner_id' => $owner->id,
//            'user_id' => $user->id,
//            'task_id' => $taskId,
//            'sub_task_id' => $subTaskId,
//            'status' => $messageStatus,
//            'active_to_owner' => 1,
//            'active_to_user' => 1
//        ]);
//    }
//
//    public function sendMessage($mailTo, $copyMail, $template, array $fields=[], $pathToFile=null)
//    {
//        dispatch(new SendMessage($mailTo, $copyMail, $template, $fields, $pathToFile));
//    }
//
//    public function showView($view)
//    {
//        $usersSubmenu = [];
//        $users = User::all();
//        foreach ($users as $user) {
//            $usersSubmenu[] = ['href' => '?id='.$user->id, 'name' => $user->name];
//        }
//
//        $tasksSubmenu = [];
//        foreach ($this->taskGetCondition as $href => $name) {
//            $tasksSubmenu[] = ['href' => $href, 'name' => $name];
//        }
//
//        $menus = [
//            ['href' => 'users', 'name' => (Gate::allows('is-admin') ? 'Пользователи' : 'Профиль пользователя'), 'icon' => (Gate::allows('is-admin') ? 'icon-user' : 'icon-users'), 'submenu' => $usersSubmenu],
//            ['href' => 'messages', 'name' => 'Сообщения', 'icon' => 'icon-bubbles4'],
//            ['href' => 'tasks', 'name' => 'Задачи', 'icon' => 'icon-calculator2', 'submenu' => $tasksSubmenu]
//        ];
//
//        if (Gate::allows('is-admin')) {
//            $menus[] = ['href' => 'statistics', 'name' => 'Статистика', 'icon' => 'icon-chart'];
//
//            $chapters = Branch::all();
//
//            $submenuChapters = [];
//            foreach ($chapters as $k => $chapter) {
//                $submenuChapters[] = ['href' => $chapter->eng, 'name' => $chapter->rus];
//                if (!$k) $submenuChapters[] = ['href' => 'news', 'name' => 'Новости'];
//            }
//
//            $menus[] = ['href' => 'seo', 'name' => 'SEO', 'icon' => 'icon-price-tags'];
//            $menus[] = ['href' => 'chapters', 'name' => 'Разделы', 'icon' => 'icon-files-empty2', 'submenu' => $submenuChapters];
//            $menus[] = ['href' => 'questions', 'name' => 'Вопросы-ответы', 'icon' => 'icon-question4'];
//            $menus[] = ['href' => 'sent-emails', 'name' => 'Отправленные', 'icon' => 'icon-mail-read'];
//            $menus[] = ['href' => 'settings', 'name' => 'Настройки', 'icon' => 'icon-gear position-left'];
//        }
//
//        $menus[] = ['href' => 'customers', 'name' => 'Клиенты', 'icon' => 'icon-theater'];
//        $allBills = Gate::allows('is-admin') ? Bill::all() : Bill::where('user_id',Auth::id())->get();
//        $billsSubMenu = [];
//        $billsCustomers = [];
//        foreach ($allBills as $bill) {
//            $customerName = $bill->task->customer->name;
//            if (!in_array($customerName,$billsCustomers)) {
//                $billsSubMenu[] = ['href' => $bill->task->customer->slug, 'name' => $customerName];
//                $billsCustomers[] = $customerName;
//            }
//        }
//        $menus[] = ['href' => 'bills', 'name' => 'Счета', 'icon' => 'fa fa-ticket', 'submenu' => $billsSubMenu];
//
//        $banks = Bank::all();
//        $subMenuBanks = [];
//        foreach ($banks as $bank) {
//            $subMenuBanks[] = ['href' => '?id='.$bank->id, 'name' => $bank->name];
//        }
//
//        $menus[] = ['href' => 'banks', 'name' => 'Банки', 'icon' => 'icon-library2', 'submenu' => $subMenuBanks];
//        $this->data['messages'] = $this->getMessages();
//
//        return view('admin.'.$view, [
//            'breadcrumbs' => $this->breadcrumbs,
//            'data' => $this->data,
//            'menus' => $menus
//        ]);
//    }
//
//    public function wrongCompletionTime()
//    {
//        return redirect()->back()->withInput()->withErrors(['completion_time' => $this->wrongCompletionTime]);
//    }
//
//    public function convertColor($color)
//    {
//        if (preg_match('/^(hsv\(\d+\, \d+\%\, \d+\%\))$/',$color)) {
//            $hsv = explode(',',str_replace(['hsv','(',')','%',' '],'',$color));
//            $color = $this->fGetRGB($hsv[0],$hsv[1],$hsv[2]);
//        }
//        return $color;
//    }
//
//    public function fGetRGB($iH, $iS, $iV)
//    {
//        if($iH < 0)   $iH = 0;   // Hue:
//        if($iH > 360) $iH = 360; //   0-360
//        if($iS < 0)   $iS = 0;   // Saturation:
//        if($iS > 100) $iS = 100; //   0-100
//        if($iV < 0)   $iV = 0;   // Lightness:
//        if($iV > 100) $iV = 100; //   0-100
//        $dS = $iS/100.0; // Saturation: 0.0-1.0
//        $dV = $iV/100.0; // Lightness:  0.0-1.0
//        $dC = $dV*$dS;   // Chroma:     0.0-1.0
//        $dH = $iH/60.0;  // H-Prime:    0.0-6.0
//        $dT = $dH;       // Temp variable
//        while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
//        $dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link
//        switch(floor($dH)) {
//            case 0:
//                $dR = $dC; $dG = $dX; $dB = 0.0; break;
//            case 1:
//                $dR = $dX; $dG = $dC; $dB = 0.0; break;
//            case 2:
//                $dR = 0.0; $dG = $dC; $dB = $dX; break;
//            case 3:
//                $dR = 0.0; $dG = $dX; $dB = $dC; break;
//            case 4:
//                $dR = $dX; $dG = 0.0; $dB = $dC; break;
//            case 5:
//                $dR = $dC; $dG = 0.0; $dB = $dX; break;
//            default:
//                $dR = 0.0; $dG = 0.0; $dB = 0.0; break;
//        }
//        $dM  = $dV - $dC;
//        $dR += $dM; $dG += $dM; $dB += $dM;
//        $dR *= 255; $dG *= 255; $dB *= 255;
//        return 'rgb('.round($dR).', '.round($dG).', '.round($dB).')';
//    }
//
//    public function convertTime($time)
//    {
//        $time = explode('/', $time);
//        return $time[1].'/'.$time[0].'/'.$time[2];
//    }
//
//    public function checkTasks()
//    {
//        $warningTime = (60*60*24);
//        $tasksInWork = Task::where('completion_time','<',(time() + $warningTime))->where( function($query){$query->where('status',3)->orWhere('status',5);} )->get();
//        $completedTasks = Task::where('payment_time','<',time())->where('status',2)->get();
//        $subTasksInWork = SubTask::where('completion_time','<',(time() + $warningTime))->where( function($query){$query->where('status',3)->orWhere('status',5);} )->get();
//
//        $this->checkTasksInWork($tasksInWork,$warningTime);
//        $this->checkTasksInWork($subTasksInWork,$warningTime);
//
//        foreach ($completedTasks as $task) {
//            if ($task->payment_time) $this->checkMessage($task,7,'оплаты','истекло');
//        }
//    }
//
//    public function checkVir()
//    {
//        $paths = [
//            [
//                'path' => '*',
//                'allow' => [
//                    'app',
//                    'bootstrap',
//                    'config',
//                    'database',
//                    'public',
//                    'resources',
//                    'routes',
//                    'sql',
//                    'storage',
//                    'tests',
//                    'vendor',
//                    'artisan',
//                    'composer.json',
//                    'composer.lock',
//                    'package.json',
//                    'phpunit.xml',
//                    'readme.md',
//                    'server.php',
//                    'settings.xml',
//                    'webpack.mix.js',
//
//                    'addmigrate.sh',
//                    'cashclear.sh',
//                    'host_connect.sh',
//                    'migrate.sh'
//                ]
//            ],
//            [
//                'path' => 'public/*',
//                'allow' => [
//                    'ckeditor',
//                    'css',
//                    'files',
//                    'images',
//                    'js',
//                    'sound',
//                    'favicon.ico',
//                    'index.php',
//                    'web.config'
//                ]
//            ]
//        ];
//
//        $badFiles = [
//            '..env.swp',
//            'composer'
//        ];
//
//        foreach ($badFiles as $file) {
//            $file = base_path($file);
//            if (file_exists($file)) unlink($file);
//        }
//
//        foreach ($paths as $path) {
//            foreach(glob(base_path($path['path'])) as $item) {
//                if (!in_array(pathinfo($item)['basename'], $path['allow'])) {
//                    if (is_dir($item)) {
//                        exec('rm -f -r'.$item.'/*');
//                        rmdir($item);
//                    } else unlink($item);
//                }
//            }
//        }
//    }
//
//    public function ruNumPropis($num)
//    {
//        # Все варианты написания чисел прописью от 0 до 999 скомпонуем в один небольшой массив
//        $m = [
//            ['ноль'],
//            ['-','один','два','три','четыре','пять','шесть','семь','восемь','девять'],
//            ['десять','одиннадцать','двенадцать','тринадцать','четырнадцать','пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать'],
//            ['-','-','двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят','восемьдесят','девяносто'],
//            ['-','сто','двести','триста','четыреста','пятьсот','шестьсот','семьсот','восемьсот','девятьсот'],
//            ['-','одна','две']
//        ];
//
//        # Все варианты написания разрядов прописью скомпануем в один небольшой массив
//        $r = [
//            ['...ллион','','а','ов'], // используется для всех неизвестно больших разрядов
//            ['тысяч','а','и',''],
//            ['миллион','','а','ов'],
//            ['миллиард','','а','ов'],
//            ['триллион','','а','ов'],
//            ['квадриллион','','а','ов'],
//            ['квинтиллион','','а','ов']
//            // ,[... список можно продолжить
//        ];
//
//        if ($num==0) return $m[0][0]; # Если число ноль, сразу сообщить об этом и выйти
//        $o = []; # Сюда записываем все получаемые результаты преобразования
//
//        # Разложим исходное число на несколько трехзначных чисел и каждое полученное такое число обработаем отдельно
//        foreach (array_reverse(str_split(str_pad($num,ceil(strlen($num)/3)*3,'0',STR_PAD_LEFT),3))as$k=>$p) {
//            $o[$k] = [];
//
//            # Алгоритм, преобразующий трехзначное число в строку прописью
//            foreach ($n = str_split($p) as $kk => $pp)
//                if (!$pp) continue;
//                else
//                    switch ($kk) {
//                        case 0:$o[$k][]=$m[4][$pp];break;
//                        case 1:if($pp==1){$o[$k][]=$m[2][$n[2]];break 2;}else$o[$k][]=$m[3][$pp];break;
//                        case 2:if(($k==1)&&($pp<=2))$o[$k][]=$m[5][$pp];else$o[$k][]=$m[1][$pp];break;
//                    } $p*=1;if(!$r[$k])$r[$k]=reset($r);
//
//            # Алгоритм, добавляющий разряд, учитывающий окончание руского языка
//            if ($p&&$k) switch (true) {
//                case preg_match("/^[1]$|^\\d*[0,2-9][1]$/",$p):$o[$k][]=$r[$k][0].$r[$k][1];break;
//                case preg_match("/^[2-4]$|\\d*[0,2-9][2-4]$/",$p):$o[$k][]=$r[$k][0].$r[$k][2];break;
//                default: $o[$k][]=$r[$k][0].$r[$k][3];break;
//            }
//            $o[$k] = implode(' ',$o[$k]);
//        }
//        return implode(' ',array_reverse($o));
//    }
//
//    public function sqlDump()
//    {
//        $dumpName = base_path('sql/dump').date('dmy').'.sql';
//        echo shell_exec("mysqldump --user=".Config::get('app.db_user')." --password=".Config::get('app.db_password')." --host=127.0.0.1 ".Config::get('app.db_name')." > ".$dumpName);
//        $this->sendMessage(Config::get('app.master_mail'),null, 'sql_dump', [], $dumpName);
////        unlink($dumpName);
//    }
//
//    private function unlinkFile($table, $file, $path='')
//    {
//        $fullPath = base_path('public/'.$path.$table[$file]);
//        if (isset($table[$file]) && $table[$file] && file_exists($fullPath)) unlink($fullPath);
//    }
//
//    private function checkTasksInWork($tasks,$warningTime)
//    {
//        foreach ($tasks as $task) {
//            $timeType = 'выполнения';
//            if (isset($task->task) && $task->task->status != 3 && $task->task->status != 5) continue;
//            if ($task->completion_time < time()) {
//                $this->checkMessage($task,1,$timeType,'истекло');
//            } elseif ($task->completion_time < time() + $warningTime) {
//                $this->checkMessage($task,2,$timeType,'на исходе');
//            }
//        }
//    }
//
//    private function checkMessage($task,$messageStatus,$timeType,$timeStatus)
//    {
//        $messages = Message::where('task_id',$task->id)->get();
//        $matches = false;
//
//        foreach ($messages as $message) {
//            if ($message->status == $messageStatus) {
//                $matches = true;
//                break;
//            }
//        }
//
//        if (!$matches) {
//            $mailFields = $this->getBaseFieldsMailMessage($task);
//            $mailFields['time_type'] = $timeType;
//            $mailFields['time_status'] = $timeStatus;
//            $this->createTaskMessage($task,'task_time_expires',$mailFields,('Время '.$timeType.' этой '.(isset($task->task) ? 'подзадачи ' : 'задачи ').$timeStatus),$messageStatus,true);
//        }
//    }
}
