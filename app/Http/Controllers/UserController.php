<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditBankRequest;
use App\Http\Requests\EditBillRequest;
use App\Http\Requests\EditSubTaskRequest;
use App\Http\Requests\EditTaskRequest;
use App\Http\Requests\EditUserRequest;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;
use App\Models\SubTask;
use App\Models\Statistic;
use App\Models\Message;
use App\Models\Customer;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\FixTax;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use JetBrains\PhpStorm\Pure;

class UserController extends Controller
{
    use HelperTrait;

    private int $yearTime = (60 * 60 * 24 * 365);
    /**
     * @var string[]
     */
    protected array $breadcrumbs;
    protected array $data = [];
    protected string $regYear = '/^(20(\d){2})$/';

    public function users($slug=null): View
    {
        $this->breadcrumbs = ['users' => __('Users')];
        if (request()->has('id')) {
            $this->data['user'] = User::query()->where('id',request()->id)->first();
            if (!$this->data['user']) abort(404);
            if (Gate::denies('user-edit',$this->data['user'])) abort(403, __('You do not have the rights to perform this operation!'));
            $this->breadcrumbs['users?id='.$this->data['user']->id] = $this->data['user']->name;
            return $this->showView('user');
        } else if ($slug == 'add') {
            if (Gate::denies('is-admin')) abort(403, __('You do not have the rights to perform this operation!'));
            $this->breadcrumbs['users/add'] = __('Adding a user');
            return $this->showView('user');
        } else {
            $this->data['users'] = Gate::allows('is-admin') ? User::all() : User::query()->where('id',Auth::id())->get();
            return $this->showView('users');
        }
    }

    public function tasks($slug=null, $subSlug=null): View
    {
        $this->breadcrumbs = ['tasks' => __('Tasks')];
        $this->getStatusesSimple();

        if (request()->has('id') || $slug == 'add') {
            $this->data['users'] = User::all();
            $this->getStatuses();

            if (request()->has('id')) {
                $this->data['task'] = Task::query()->where('id',request()->id)->with(['messages.owner','messages.user','bills.task'])->first();
                if (!$this->data['task']) abort(404);

                $this->data['customers'] = Customer::query()->where('type','<',5)->orWhere('id',$this->data['task']->customer_id)->orderBy('slug')->get();
                $this->data['bills_statuses'] = getBillsStatuses();

                if (Gate::denies('owner-or-user-task', $this->data['task'])) abort(403, __('You do not have the rights to perform this operation!'));

                $this->breadcrumbs['tasks?id='.$this->data['task']->id] = $this->data['task']->name;
                $this->checkTaskMessages();
                $this->data['convention_number'] = $this->getLastConventionNumber($this->data['task']->customer);
                return $this->showView('task');
            } elseif ($slug == 'add') {
                $this->data['customers'] = Customer::query()->where('type','<',5)->orderBy('slug')->get();
                if ($subSlug) {
                    $this->data['customer'] = Customer::query()->where('slug',$subSlug)->with('tasks')->first();
                    if (!$this->data['customer'] || $this->data['customer']->type == 5) abort(404);
                    $this->breadcrumbs['tasks/add/'.$subSlug] = __('Adding a task for').' '.$this->data['customer']->name;
                } else {
                    $this->breadcrumbs['tasks/add'] = __('Adding a task');
                }

                $this->data['convention_number'] = isset($this->data['customer']) ? $this->getLastConventionNumber($this->data['customer']) : 1;
                return $this->showView('task');
            }

        } else if ( ($slug && preg_match($this->regYear,$slug)) || ($subSlug && preg_match($this->regYear,$subSlug)) ) {
            $this->getBackUri(request()->path());
            $this->data['year'] = ($slug && preg_match($this->regYear,$slug)) ? (int)$slug : (int)$subSlug;

            if ($this->data['year'] < 2018) abort(404);
            $this->breadcrumbs['tasks/'.$this->data['year']] = __('Tasks by').' '.$this->data['year'].' '.__('year');

            $this->getTasks($slug);
            $this->getSidebar();
            $this->getFixTax();
            return $this->showView('tasks');
        } else {
            $this->getBackUri(request()->path());
            $this->data['year'] = date('Y');

            $this->getTasks($slug);
            $this->getSidebar();
            $this->getFixTax();

            if (!$this->data['fix_tax']) {
                $lastFixTax = FixTax::query()->orderBy('id','desc')->first()->year;
                $this->data['fix_tax'] = FixTax::query()->create([
                    'value' => $lastFixTax,
                    'year' => (int)date('Y')
                ]);
            }
            return $this->showView('tasks');
        }
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function subTask($slug=null): View
    {
        $this->breadcrumbs = ['tasks' => __('Tasks')];
        $this->getStatusesSimple();

        if ($slug == 'add') {
            $this->validate(request(), ['id' => $this->validationId.'tasks']);
            $this->data['task'] = Task::query()->where('id',request()->id)->with('customer')->first();
            if (!$this->data['task']) abort(404);
            $this->getStatuses();
            $this->breadcrumbs['tasks?id='.$this->data['task']->id] = $this->data['task']->name;
            $this->breadcrumbs['tasks/sub_task/add?id='.$this->data['task']->id] = __('Adding a subtask to a task').' '.$this->data['task']->name;
        } else {
            $this->data['sub_task'] = SubTask::query()->where('id',request()->id)->with('task')->first();
            if (!$this->data['sub_task']) abort(404);
            $this->getStatuses();
            $this->data['task'] = $this->data['sub_task']->task->load(['messages.owner','messages.user']);
            $this->breadcrumbs['tasks?id='.$this->data['sub_task']->task->id] = $this->data['sub_task']->task->name;
            $this->breadcrumbs['tasks/sub_task?id='.$this->data['sub_task']->id] = $this->data['sub_task']->name;
            $this->checkTaskMessages();
        }
        return $this->showView('sub_task');
    }

    public function messages(): View
    {
        $this->breadcrumbs = ['messages' => __('Messages')];
        $this->data['messages_list'] =
            Gate::allows('is-admin') ?
            Message::query()
                ->with(['owner','user','task.customer'])
                ->orderBy('id','desc')
                ->get() :
            Message::query()
                ->with(['owner','user','task.customer'])
                ->where('user_id',Auth::id())
                ->orWhere('owner_id',Auth::id())
                ->orderBy('id','desc')
                ->get();

        return $this->showView('messages');
    }

    public function customers($slug=null): View
    {
        $this->breadcrumbs = ['customers' => __('Customers')];
        $this->getBackUri(request()->path());

        if ($slug && $slug != 'add') {
            $this->data['banks'] = Bank::all();
            $this->data['customer'] = Customer::query()->where('slug',$slug)->with('tasks.subTasks')->first();
            if (!$this->data['customer']) abort(404);
            $this->getStatusesSimple();
            $this->breadcrumbs['customers/'.$this->data['customer']->slug] = $this->data['customer']->name;
            return $this->showView('customer');
        } else if ($slug) {
            $this->breadcrumbs['customers/add'] = __('Adding a client');
            $this->data['banks'] = Bank::all();
            return $this->showView('customer');
        } else {
            $customers = new Customer();
            $this->data['customers'] = Gate::allows('is-admin') ? $customers->query()->orderBy('type')->get() : $customers->whereIn('type',[1,2,3])->get();
            return $this->showView('customers');
        }
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function banks($slug=null): View
    {
        $this->breadcrumbs = ['banks' => __('Banks')];
        $this->data['banks'] = Bank::all();

        if (request()->has('id')) {;
            $this->validate(request(), ['id' => $this->validationId.'banks']);
            $this->data['bank'] = Bank::query()->where('id',request()->id)->first();
            $this->breadcrumbs['banks/?id='.$this->data['bank']->id] = $this->data['bank']->name;
            return $this->showView('bank');
        } else if ($slug == 'add') {
            $this->breadcrumbs['banks/add'] = __('Adding a bank');
            return $this->showView('bank');
        } else {
            return $this->showView('banks');
        }
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bills($slug=null): View
    {
        $this->breadcrumbs = ['bills' => __('Bills')];
        $this->data['statuses'] = getBillsStatuses();
        $this->getLastBillNumber();

        if (request()->has('id') && request()->id) {
            $this->validate(request(), ['id' => $this->validationId.'bills']);
            $this->data['bill'] = Bill::query()->where('id', request()->id)->with('task.customer')->first();
            if (
                ($this->data['bill']->task->status > 2 && $this->data['bill']->task->status < 6 && !($this->data['bill']->task->status == 3 && $this->data['bill']->task->paid_off)) ||
                Gate::denies('check-rights',[$this->data['bill'],'user_id']) ||
                $this->data['bill']->task->customer->ltd == 2
            ) abort(403, __('You do not have the rights to perform this operation!'));

            $this->data['year'] = date('Y',$this->data['bill']->task->start_time);
            $this->getStatusesSimple();
            $this->getDataForBill($this->data['bill']->task->id);
            $this->breadcrumbs['bills/'.$this->data['bill']->slug] = 'Счет №'.$this->data['bill']->number;
            return $this->showView('bill');
        } else if ($slug && $slug == 'add') {
            $this->breadcrumbs['bills/add'] = __('Adding a bill');
            $this->getDataForBill(request()->has('task_id') ? request()->task_id : null);
            if (!count($this->data['tasks'])) abort(403, __('You do not have the rights to perform this operation!'));
            return $this->showView('bill');
        } else if ($slug && $slug != 'add') {
            $customer = Customer::query()->where('slug',$slug)->first();
            if (!$customer) abort(404);
            elseif ($customer->ltd == 2) abort(403, __('You do not have the rights to perform this operation!'));

            $this->data['head'] = __('Bills').' '.$customer->name;
            $this->getDataForBill();
            $tasksIds = Task::query()->where('customer_id',$customer->id)->pluck('id')->toArray();
            $this->data['bills'] =
                Gate::allows('is-admin') ?
                Bill::query()->whereIn('task_id',$tasksIds)->orderBy('date','desc')->get() :
                Bill::query()->where('user_id',Auth::id())->whereIn('task_id',$tasksIds)->orderBy('date','desc')->get();
            return $this->showView('bills');
        } else {
            $this->data['head'] = __('Bills');
            $this->getDataForBill();
            $this->data['bills'] =
                Gate::allows('is-admin') ?
                Bill::query()->orderBy('number','desc')->with(['task.customer','task.bills'])->get() :
                Bill::query()->where('user_id',Auth::id())->orderBy('number','desc')->with(['task.customer','task.bills'])->get();
            return $this->showView('bills');
        }
    }

    public function printDoc(string $slug): View
    {
        if (request()->has('stamp') && request()->stamp) $signature = 'stamp.png';
        elseif (request()->has('signature') && request()->signature) $signature = 'signature.png';
        else $signature = false;

        $savedCustomerFields = [
            'saved_contract',
            'saved_additional',
        ];

        $savedTaskFields = [
            'saved_convention',
        ];

        $savedBillFields = [
            'saved_act',
            'saved_bill',
        ];

        if (!in_array($slug,array_merge(['contract','convention','act','bill'],$savedCustomerFields,$savedTaskFields,$savedBillFields))) abort(404);

        if (in_array($slug,$savedCustomerFields) || $slug == 'contract') {
            return $this->getDocument($slug, $signature, new Customer(), $savedCustomerFields);
        } elseif (in_array($slug,$savedTaskFields) || $slug == 'convention') {
            return $this->getDocument($slug, $signature, new Task(), $savedTaskFields);
        } else {
            return $this->getDocument($slug, $signature, new Bill(), $savedBillFields);
        }
    }

    public function editUser(EditUserRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        if (isset($fields['password'])) $fields['password'] = bcrypt($fields['password']);

        if (request()->has('id')) {
            $user = User::query()->find(request()->id);
            if (Gate::denies('user-edit',$user)) abort(403, __('You do not have the rights to perform this operation!'));

            if (Gate::denies('is-admin') && request()->password && !Hash::check(request()->old_password, $user->password))
                return redirect()->back()->withInput()->withErrors(['old_password' => __('Wrong old password')]);

            if (Gate::allows('is-admin') && $fields['is_admin'] != $user->is_admin) {
                $this->sendMessage($fields['email'], null,'new_user_status', ['status' => $fields['is_admin']]);
            }

            if ($fields['email'] != $user->email) {
                $this->sendMessage($fields['email'], null, 'new_user_email');
                $this->sendMessage($user->email, null, 'unbind_email');
            }
            $user->update($fields);
        } else {
            User::query()->create($fields);
            $this->sendMessage($fields['email'], null, 'new_user', ['password' => $request->password]);
        }

        $this->saveCompleteMessage();
        return redirect('/admin/users');
    }

    public function editTask(EditTaskRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        $fields = $this->convertTimeFields($fields, ['start_time', 'completion_time', 'convention_date', 'payment_time']);
        $fields = $this->convertCheckFields($fields, ['use_duty', 'send_email', 'use_payment_time', 'save_convention']);

        if ($request->has('id')) {
            $task = Task::query()->where('id',$request->id)->with(['customer','subTasks','bills','owner','user'])->first();
            if (Gate::denies('owner-or-user-task', $task)) abort(403, __('You do not have the rights to perform this operation!'));
            if ($this->checkStartCompletionTime($fields)) return $this->returnWrongCompletionTime();

            // Task messages
            if (
                $fields['status'] != $task->status ||
                $fields['completion_time'] != $task->completion_time ||
                (isset($fields['payment_time']) && $fields['payment_time'] != $task->payment_time)
            ) {
                $mailFields = $this->getBaseFieldsMailMessage($task);

                if ($fields['status'] != $task->status) {

                    $fields = $this->checkTaskStatus($fields);

                    $mailFields['status'] = $this->getSimpleTaskStatus($fields['status']);
                    $messageText = __('Task status changed');
                    $messageStatus = 4;
                    $mailView = 'new_task_status';

                    $this->changeTaskStatus($task, $fields['status']);

                } elseif ($fields['completion_time'] != $task->completion_time) {
                    $mailFields['time'] = date('d.m.Y',$fields['completion_time']);
                    $messageText =__('Task status changed');
                    $mailFields['time_type'] = __('implementations');
                    $messageStatus = 5;
                    $mailView = 'new_task_time';
                } else {
                    $mailFields['time'] = date('d.m.Y',$fields['payment_time']);
                    $messageText = __('Task status changed');
                    $mailFields['time_type'] = __('payments');
                    $messageStatus = 6;
                    $mailView = 'new_task_time';
                }

                $this->createTaskMessage($task, $mailView, $mailFields, $messageText, $messageStatus,$fields['send_email']);
            }

            // Checking paid off zeroing check
            if ($task->paid_off && !$fields['paid_off']) {
                foreach ($task->bills as $k => $bill) {
                    if (!$k && $bill->status == 2) {
                        $bill->status = 1;
                        $bill->save();
                    } elseif ($k) $bill->delete();
                }
            }
            $task->update($fields);

            // Checking subtasks time
            if (count($task->subTasks)) {
                foreach ($task->subTasks as $subTasks) {
                    if ($subTasks->completion_time > $task->completion_time) {
                        $subTasks->completion_time = $task->completion_time;
                        $subTasks->save();
                    }
                }
            }

            if ($task->status == 1 && count($task->bills)) {
                foreach ($task->bills as $bill) {
                    if ($bill->status != 1) $bill->update(['status' => 1]);
                }
            }
        } else {

            if (!isset($fields['owner_id']) && $fields['status'] < 6) $fields['owner_id'] = Auth::id();
            $task = Task::query()->create($fields);
            $task->load(['owner','user']);
            $this->updateStatistics($fields['status'], $task);

            // Task messages
            $this->sendNewTaskMessage($task->send_email, $task, $this->getNewTaskFieldsMailMessage($task,null, $fields));

            Message::query()->create([
                'message' => __('A new task has been created'),
                'owner_id' => $task->owner->id,
                'user_id' => $task->user->id,
                'task_id' => $task->id,
                'status' => 3,
                'active_to_owner' => 1,
                'active_to_user' => 1
            ]);
        }

        // Final redirect
        $this->saveCompleteMessage();
        return redirect(Session::has('back_uri') ? Session::get('back_uri') : url('/admin/tasks'));
    }

    public function editSubTask(EditSubTaskRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        $fields = $this->convertTimeFields($fields,['start_time','completion_time']);
        $fields = $this->convertCheckFields($fields, ['send_email']);

        if ($request->has('id')) {
            $subTask = SubTask::query()->where('id',$request->id)->with('task.customer')->first();
            if (Gate::denies('owner-or-user-task', $subTask->task)) abort(403, __('You do not have the rights to perform this operation!'));
            if ($this->checkSubTaskTime($fields['completion_time'], $subTask->task) || $this->checkStartCompletionTime($fields)) return $this->returnWrongCompletionTime();

            // Task messages
            if ($fields['status'] != $subTask->status || $fields['completion_time'] != $subTask->completion_time) {
                $mailFields = $this->getBaseFieldsMailMessage($subTask);

                if ($fields['status'] != $subTask->status) {

                    $fields = $this->checkTaskStatus($fields);

                    $mailFields['status'] = $this->getSimpleTaskStatus($fields['status']);
                    $messageText = __('Subtask status changed');
                    $messageStatus = 7;
                    $mailView = 'new_task_status';
                } else {
                    $mailFields['time'] = date('d.m.Y',$fields['completion_time']);
                    $messageText = __('Subtask execution time has been changed');
                    $mailFields['time_type'] = __('implementations');
                    $messageStatus = 8;
                    $mailView = 'new_task_time';
                }
                $this->createTaskMessage($subTask, $mailView, $mailFields, $messageText, $messageStatus, $fields['send_email']);
            }

            $subTask->update($fields);
        } else {
            $task = Task::query()->where('id', $request->parent_id)->with(['owner','user'])->first();
            if (Gate::denies('owner-or-user-task', $task)) abort(403, __('You do not have the rights to perform this operation!'));
            if ($this->checkSubTaskTime($fields['completion_time'], $task) || $this->checkStartCompletionTime($fields)) return $this->returnWrongCompletionTime();

            $fields['task_id'] = $fields['parent_id'];
            $subTask = SubTask::query()->create($fields);
            $this->sendNewTaskMessage($task->send_email, $task, $this->getNewTaskFieldsMailMessage($task, $subTask, $fields));
            Message::query()->create([
                'message' => __('A new subtask has been created'),
                'owner_id' => $task->owner->id,
                'user_id' => $task->user->id,
                'task_id' => $task->id,
                'sub_task_id' => $subTask->id,
                'status' => 9,
                'active_to_owner' => 1,
                'active_to_user' => 1
            ]);
        }
        $this->saveCompleteMessage();
        return redirect(url('/admin/tasks?id='.$subTask->task->id));
    }

    public function editBank(EditBankRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        if (request()->has('id')) Bank::query()->where('id',request()->id)->update($fields);
        else Bank::create($fields);

        $this->saveCompleteMessage();
        return redirect('/admin/banks');
    }

    public function editBill(EditBillRequest $request): RedirectResponse
    {
        $fields = $request->validated();
        $fields = $this->convertTimeFields($fields, ['date']);
        $fields = $this->convertCheckFields($fields, ['save_contract','save_convention','save_act','save_bill','send_email']);

        foreach (['contract','convention','act','bill'] as $item) {
            if (!$fields['save_'.$item]) $fields[$item] = null;
        }

        if (request()->has('id')) {
            $bill = Bill::query()->where('id',$request->id)->with(['task.customer','task.bills'])->first();
            if (Gate::denies('check-rights',[$bill, 'user_id']) || Gate::denies('check-rights',[$bill, 'owner_id']))
                abort(403,__('You do not have the rights to perform this operation!'));

            if (
                $fields['status'] == 1
                && $bill->task->status > 1
                && $bill->task->status < 7
                && isFinalBill($bill)
            ) {
                $mailFields = $this->getBaseFieldsMailMessage($bill->task);
                $mailFields['status'] = $this->getSimpleTaskStatus(1);

                $this->changeTaskStatus($bill->task,1);
                $this->createTaskMessage($bill->task,'new_task_status', $mailFields, __('Task status changed'),4, $fields['send_email']);

                $bill->task->status = $bill->task->status == 6 ? 7 : 1;
                $bill->task->save();
            }

            $this->changeBillsBrothersStatus($fields['signing'], $bill->task, $bill->id);
            $bill->update($fields);

            // Saving foreign documents
            $bill->task->customer->update($fields);
            $bill->task->update($fields);
        } else {
            $task = Task::query()->where('id',request()->task_id)->with(['customer','bills'])->first();
            if (
                Gate::denies('check-rights',[$task, 'owner_id'])
                || ($task->status != 2 && $task->status != 3 && $task->status != 6)
                || (($task->status == 2 || $task->status == 6) && count($task->bills) > 1)
                || ($task->status == 3 && !$task->paid_off)
                || ($task->status == 3 && $task->paid_off && count($task->bills))
            ) abort(403,__('You do not have the rights to perform this operation!'));
            $fields['status'] = $task->paid_off && !count($task->bills) && $task->paid_off != $task->value ? 3 : 2;
            $fields['user_id'] = Auth::id();

            $this->changeBillsBrothersStatus($fields['signing'], $task);
            Bill::query()->create($fields);
        }

        $this->saveCompleteMessage();
        return redirect(url('/admin/bills'));
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getBillsValue(): JsonResponse
    {
        $this->validate(request(), ['id' => $this->validationTaskId]);
        $task = $this->getTaskForBill(request()->id);
        if (!$task) return response()->json(['success' => false]);
        else return response()->json(['success' => true, 'value' => calculateTaskValForBill($task)]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getConventionNumber(): JsonResponse
    {
        $this->validate(request(), ['id' => $this->validationTaskId]);
        $task = $this->getTaskForBill(request()->id);
        if (!$task) return response()->json(['success' => false]);
        else return response()->json(['success' => true, 'number' => $this->getLastConventionNumber($task->customer)]);
    }

    public function deleteBank(): JsonResponse
    {
        Customer::query()->where('bank_id',request()->id)->update(['bank_id' => null]);
        return $this->deleteSomething(new Bank());
    }

    public function deleteBill(): JsonResponse
    {
        return $this->deleteSomething(new Bill(),  'user_id');
    }

    public function deleteMessage(): JsonResponse
    {
        $message = Message::query()->where('id',request()->id)->first();
        if ($message || Gate::allows('owner-or-user-message', $message)) {
            if (Gate::allows('is-admin')) {
                $message->active_to_owner = 2;
                $message->active_to_user = 2;
            } elseif (Gate::allows('owner-message-not-admin', $message)) {
                $message->active_to_owner = 2;
            } else {
                $message->active_to_user = 2;
            }
            $message->save();
            return response()->json(['success' => true]);
        } else return response()->json(['success' => false]);
    }

    public function deleteTask(): JsonResponse
    {
        $task = Task::query()->where('id',request()->id)->first();
        if (!$task || Gate::allows('owner-task', $task)) {
            $task->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function deleteSubTask(): JsonResponse
    {
        $subTask = SubTask::query()->where('id',request()->id)->with('task')->first();
        if ($subTask || Gate::allows('owner-or-user-task',$subTask->task)) {
            $subTask->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function seenAll(): JsonResponse
    {
        $messagesList = [];
        $messages = $this->getMessages();
        foreach ($messages as $message) {
            $this->setSeenMessage($message);
            $messagesList[] = $message->id;
        }
        return response()->json(['success' => true, 'messages' => $messagesList]);
    }

//    public function getNewMessages(): JsonResponse
//    {
//        $messages = $this->getMessages();
//        if (count($messages)) {
//            return response()->json(['success' => true, 'counter' => count($messages), 'messages' => view('layouts._messages_block',['messages' => $messages])->render()]);
//        } else {
//            return response()->json(['success' => false]);
//        }
//    }

    protected function getBackUri($path): void
    {
        Session::put('back_uri',$path);
    }

    protected function getFixTax(): void
    {
        $this->data['fix_tax'] = FixTax::query()->where('year', $this->data['year'] ?? date('Y'))->first();
        if (!$this->data['fix_tax']) $this->data['fix_tax'] = getSettings()['fix_tax'];
    }

    protected function getStatuses(): void
    {
        $this->data['statuses'] = [];
        $key = 1;
        foreach (getTaskConditions() as $description) {
            $this->data['statuses'][] = ['val' => $key, 'descript' => $description];
            if ($key == 5 && ( !isset($this->data['task']) || (isset($this->data['task']) && $this->data['task']->status <= 5) ) ) break;
            else $key++;
        }
    }

    protected function getStatusesSimple(): void
    {
        $this->data['statuses_simple'] = [];
        foreach (getTaskConditions() as $description) {
            $this->data['statuses_simple'][] = $description;
        }
    }

    protected function getTasksInWork($status=null): void
    {
        if ((int)$this->data['year'] == (int)date('Y') && (!$status || $status == 3)) {
            if (Gate::allows('is-admin')) $this->data['work_tasks'] = Task::query()->where('status',3)->count();
            else $this->data['work_tasks'] = Task::query()->where('status',3)->where('user_id',Auth::id())->orWhere('owner_id',Auth::id())->count();
        }
    }

    protected function deleteSomething(Model $model, $checkRightsField=null): JsonResponse
    {
        $table = $model->query()->where('id',request()->id)->first();
        if (
            ($model instanceof User && request()->id == 1) ||
            ($checkRightsField && Gate::denies('check-rights',[$table, $checkRightsField]))
        ) response()->json(['success' => false]);

        if ($model instanceof User) {
            $table->load(['tasks','ownTasks'.'bills']);
            if (count($table->tasks)) {
                $this->changeUserTask($table->tasks, 'user_id');
            }
            if (count($table->ownTasks)) {
                $this->changeUserTask($table->ownTasks, 'owner_id');
            }
            if (count($table->bills)) {
                foreach ($table->bills as $bill) {
                    $bill->user_id = 1;
                    $bill->save();
                }
            }
        }
        $table->delete();
        return response()->json(['success' => true]);
    }

    private function changeBillsBrothersStatus(int $signing, Task $task, int $billId=null): void
    {
        if ($signing == 3 && $task->paid_off && count($task->bills) > 1) {
            foreach ($task->bills as $bill) {
                if (!$billId || ($billId && $bill->id != $billId)) {
                    $bill->signing = 3;
                    $bill->save();
                }
            }
        }
    }

    private function checkTaskMessages()
    {
        foreach ($this->data['task']->messages as $message) {
            if ($message->owner && $message->user) {
                if (Gate::allows('owner-or-user-message-not-admin',$message)) {
                    $this->setSeenMessage($message);
                }
            } else $message->delete();
        }
    }

    #[Pure] private function getNewTaskFieldsMailMessage(Task $task, SubTask|null $subTask, array $fields): array
    {
        $fields['id'] = $subTask ? $subTask->id : $task->id;
        $fields['parent_id'] = $subTask?->task->id;
        $fields['parent_name'] = $subTask?->task->name;
        $fields['customer'] = $task->customer->name;
        $fields['email'] = $task->email ?: $task->customer->email;
        $fields['phone'] = $task->phone ?: $task->customer->phone;
        $fields['contact_person'] = $task->contact_person ? $task->contact_person : $task->customer->contact_person;
        $fields['status'] = $this->getSimpleTaskStatus($subTask ? $subTask->status : $task->status);
        $fields['owner'] = $task->owner->name;
        $fields['user'] = $task->user->name;
        return $fields;
    }

    private function sendNewTaskMessage(bool $sendMail, Task|SubTask $task, array $fields): void
    {
        if ($sendMail)
            $this->sendMessage(
                $task->owner->email,
                ($task->owner->email != $task->user->email && $task->user->send_email ? $task->user->email : null),
                'new_task',
                $fields
            );
    }

    private function returnWrongCompletionTime(): RedirectResponse
    {
        return redirect()->back()->withErrors(['completion_time' => __('Wrong completion time')])->withInput();
    }

    private function getTasks($slug): void
    {
        $startTimeStamp = strtotime('01-01-'.$this->data['year']);

        $model = new Task();
        $modelForYear = clone $model;

        $tasks = $model
            ->query()
            ->where(
                function($query) use ($startTimeStamp) {
                    $query
                        ->where('completion_time','>',$startTimeStamp)->where('completion_time','<',($startTimeStamp + $this->yearTime))
                        ->orWhere('start_time','>',$startTimeStamp)->where('start_time','<',($startTimeStamp + $this->yearTime));
                }
            )
            ->with(['subTasks','customer','statistics'])
            ->orderBy('customer_id');

        $tasksConditions = array_keys(getTaskConditions());

        if ($slug && in_array($slug, $tasksConditions)) {
            $status = array_search($slug, $tasksConditions)+1;
            $this->getTasksInWork($status);

            $this->breadcrumbs['tasks/'.$slug] = getTaskConditions()[$slug];

            $tasks = $tasks->where('status',$status);
            $modelForYear = $modelForYear->where('status',$status);
        } else {
            $this->getTasksInWork();
        }

        if (Gate::allows('is-admin')) {
            $this->data['tasks'] = $tasks->get();
            $this->getTaskYears($modelForYear->query()->orderBy('start_time')->pluck('start_time')->toArray());
        } else {
            $taskOwn = clone $tasks;
            $modelForYearOwn = clone $modelForYear;

            $this->getTaskYears($modelForYear->where('user_id',Auth::id())->orderBy('start_time')->pluck('start_time')->toArray());
            $this->getTaskYears($modelForYearOwn->where('owner_id',Auth::id())->orderBy('start_time')->pluck('start_time')->toArray());

            $this->data['tasks'] = $tasks->where('user_id',Auth::id())->get();
            $this->data['own_tasks'] = $taskOwn->where('owner_id',Auth::id())->get();
        }
    }

    private function getDocument(string $slug, string|bool $signature, Customer|Task|Bill $model, array $savedFields): View
    {
        $model = $model->query()->where('id',request()->id)->first();
        if (!$model) abort(404);

        if (in_array($slug,$savedFields)) return view('docs.empty', ['content' => $model[str_replace('saved_','',$slug)]]);
        else return view('docs.'.$slug, ['item' => $model, 'signature' => $signature]);
    }

    private function changeTaskStatus(Task|SubTask $task, int $status): void
    {
        $this->updateStatistics($status, $task);
        if ( ($status == 1 || $status == 2) && count($task->subTasks) ) {
            SubTask::query()->where('task_id',$task->id)->update(['status' => $status]);
        }

        if ($status == 1 && count($task->bills)) {
            Bill::query()->where('task_id',$task->id)->update(['status' => 1]);
        }
    }

    private function updateStatistics($status, $task): void
    {
        $update = true;
        if (count($task->statistics)) {
            foreach ($task->statistics as $statistic) {
                if ($statistic->status == $status && date('Y') == $statistic->created_at->format('Y') && date('m') == $statistic->created_at->format('m')) {
                    $update = false;
                    break;
                }
            }
        }
        if ($update) {
            Statistic::query()->create([
                'status' => $status,
                'task_id' => $task->id
            ]);
        }
    }

    private function changeUserTask($tasks, $field)
    {
        foreach ($tasks as $task) {
            $task[$field] = 1;
            $task->save();
        }
    }

    private function createTaskMessage(Task|SubTask $item, string $mailView, array $mailFields, string $messageText, int $messageStatus, bool $sendMail): void
    {
        if (isset($item->task)) {
            $taskId = $item->task->id;
            $subTaskId = $item->id;
            $owner = $item->task->owner;
            $user = $item->task->user;
        } else {
            $taskId = $item->id;
            $subTaskId = null;
            $owner = $item->owner;
            $user = $item->user;
        }

        if ($sendMail)
            $this->sendMessage(
                $owner->email,
                ($owner->email != $user->email && $user->send_email ? $user->email : null),
                $mailView,
                $mailFields
            );

        Message::query()->create([
            'message' => $messageText,
            'owner_id' => $owner->id,
            'user_id' => $user->id,
            'task_id' => $taskId,
            'sub_task_id' => $subTaskId,
            'status' => $messageStatus,
            'active_to_owner' => 1,
            'active_to_user' => 1
        ]);
    }

    private function setSeenMessage(Message $message): void
    {
        $userType = Gate::allows('owner-message-not-admin', $message) ? 'owner' : 'user';
        if (Gate::allows('owner-message-not-admin', $message)) {
            $message->active_to_owner = 0;
            $message->active_to_user = 0;
        } else $message['active_to_'.$userType] = 0;
        $message->save();
    }

    private function getBaseFieldsMailMessage(Task|SubTask $item): array
    {
        $mailFields = [];
        $mailFields['id'] = $item->id;
        $mailFields['name'] = $item->name;
        $mailFields['customer'] = isset($item->task) ? $item->task->customer->name : $item->customer->name;
        $mailFields['parent_id'] = isset($item->task) ? $item->task->id : null;
        $mailFields['parent_name'] = isset($item->task) ? $item->task->name : null;
        return $mailFields;
    }

    private function checkTaskStatus($fields)
    {
        if ($fields['status'] == 5) $fields['completion_time'] = time() + (60 * 60 * 24 * 2);
        return $fields;
    }

    private function getSimpleTaskStatus($taskStatus): string
    {
        $k = 1;
        $status = '';
        foreach (getTaskConditions() as $status => $description) {
            if ($taskStatus == $k) {
                $status = $description;
                break;
            }
            $k++;
        }
        return $status;
    }

    private function checkSubTaskTime(int $completionTime, Task $task): bool
    {
        return $completionTime > $task->completion_time;
    }

    private function checkStartCompletionTime(array $fields): bool
    {
        return $fields['start_time'] > $fields['completion_time'];
    }

    private function getTaskYears($times): void
    {
        if (!isset($this->data['years'])) $this->data['years'] = [];
        foreach ($times as $time) {
            $year = date('Y',$time);
            if ( !in_array($year,$this->data['years']) ) $this->data['years'][] = $year;
        }

        $currentYear = (int)date('Y');
        if (
            count($this->data['years']) &&
            (int)$this->data['years'][count($this->data['years'])-1] != (int)date('Y') &&
            !in_array($currentYear, $this->data['years'])
        ) $this->data['years'][] = $currentYear;
    }

    private function getDataForBill($id=null)
    {
        $this->data['tasks'] = [];
        $customers = Customer::query()->where('ltd','<',2)->orWhere('ltd',3)->orderBy('slug')->with(['tasks.bills'])->get();
        foreach ($customers as $customer) {
            $tasks = [];
            foreach ($customer->tasks as $task) {
                if (
                    $task->use_duty
                    && (
                        ( ($task->status == 2 || $task->status == 6) && !count($task->bills) )
                        || ( ($task->status == 2 || $task->status == 6) && $task->paid_off && count($task->bills) == 1)
                        || ($task->status == 3 && $task->paid_off && !count($task->bills))
                        || ($id && $task->id == $id)
                    )
                    && (Gate::allows('owner-task', $task))
                )
                {
                    $tasks[$task->id] = $task->name;
                }
            }
            if (count($tasks)) $this->data['tasks'][$customer->name] = $tasks;
        }
        $this->getFixTax();
    }

    private function getTaskForBill(int $id): Task|bool
    {
        $task = Task::query()->where('id',$id)->with(['subTasks','customer'])->first();
        return ($task->status != 2 && $task->status != 6) ? false : $task;
    }

    private function getLastConventionNumber($customer): int
    {
        $taskCounter = count($customer->tasks);
        $lastNumber = $customer->tasks[$taskCounter-1]->convention_number + 1;
        if ($taskCounter > 1) return $lastNumber ? $lastNumber + 1 : $taskCounter + 1;
        else return 1;
    }

    private function getLastBillNumber(): void
    {
        $this->data['last_number'] = Bill::orderBy('number','desc')->pluck('number')->first();
    }

    private function getCustomConvention($task): void
    {
        $this->data['convention'] = $task->convention ? $task->convention : view('docs.convention',['item' => $task,'noPrint' => true])->render();
    }

    protected function showView($view): View
    {
        $usersSubmenu = [];
        $users = User::all();
        foreach ($users as $user) {
            $usersSubmenu[] = ['href' => '?id='.$user->id, 'name' => $user->name];
        }

        $tasksSubmenu = [];
        foreach (getTaskConditions() as $href => $name) {
            $tasksSubmenu[] = ['href' => $href, 'name' => $name];
        }

        $menus = [
            ['href' => 'users', 'name' => (Gate::allows('is-admin') ? __('Users') : __('Users profile')), 'icon' => (Gate::allows('is-admin') ? 'icon-user' : 'icon-users'), 'submenu' => $usersSubmenu],
            ['href' => 'messages', 'name' => __('Messages'), 'icon' => 'icon-bubbles4'],
            ['href' => 'tasks', 'sub_href' => 'sub_task', 'name' => __('Tasks'), 'icon' => 'icon-calculator2', 'submenu' => $tasksSubmenu]
        ];

        if (Gate::allows('is-admin')) {
            $menus[] = ['href' => 'statistics', 'name' => __('Statistics'), 'icon' => 'icon-chart'];

            $chapters = Branch::all();

            $submenuChapters = [];
            foreach ($chapters as $chapter) {
                $submenuChapters[] = ['href' => $chapter->slug, 'name' => $chapter[App::getLocale()]];
            }

            $menus[] = ['href' => 'seo', 'name' => 'SEO', 'icon' => 'icon-price-tags'];
            $menus[] = ['href' => 'chapters', 'name' => __('Chapters'), 'icon' => 'icon-files-empty2', 'submenu' => $submenuChapters];
            $menus[] = ['href' => 'sent-emails', 'name' => __('Sent'), 'icon' => 'icon-mail-read'];
            $menus[] = ['href' => 'settings', 'name' => __('Settings'), 'icon' => 'icon-gear position-left'];
        }

        $menus[] = ['href' => 'customers', 'name' => __('Customers'), 'icon' => 'icon-theater'];
        $allBills = Gate::allows('is-admin')
            ? Bill::query()->with('task.customer')->get()
            : Bill::query()->where('user_id',Auth::id())->with('task.customer')->get();
        $billsSubMenu = [];
        $billsCustomers = [];
        foreach ($allBills as $bill) {
            $customerName = $bill->task->customer->name;
            if (!in_array($customerName,$billsCustomers)) {
                $billsSubMenu[] = ['href' => $bill->task->customer->slug, 'name' => $customerName];
                $billsCustomers[] = $customerName;
            }
        }
        $menus[] = ['href' => 'bills', 'name' => __('Bills'), 'icon' => 'fa fa-ticket', 'submenu' => $billsSubMenu];

        $banks = Bank::all();
        $subMenuBanks = [];
        foreach ($banks as $bank) {
            $subMenuBanks[] = ['href' => '?id='.$bank->id, 'name' => $bank->name];
        }

        $menus[] = ['href' => 'banks', 'name' => __('Banks'), 'icon' => 'icon-library2', 'submenu' => $subMenuBanks];
        $this->data['messages'] = $this->getMessages();

        return view('admin.'.$view, [
            'breadcrumbs' => $this->breadcrumbs,
            'data' => $this->data,
            'menus' => $menus
        ]);
    }

    private function getSidebar(): void
    {
        $this->data['sidebar'] = count($this->data['tasks']) || (isset($this->data['own_tasks']) && count($this->data['own_tasks']));
    }

    private function getMessages($addCondition=null): array
    {
        $result = [];
        $messages = Message::query()
            ->where('active_to_owner',1)
            ->orWhere('active_to_user',1)
            ->with(['owner','user','task.customer','subTask'])
            ->get();
        foreach ($messages as $message) {
            if (
                ($message->owner && Gate::allows('owner-message-not-admin', $message) && $message->active_to_owner) ||
                ($message->user && Gate::allows('user-message-not-admin', $message) && $message->active_to_user) ||
                $addCondition
            ) {
                $result[] = $message;
            }
        }
        return $result;
    }
}
