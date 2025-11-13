<?php

namespace App\Http\Controllers;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\FixTax;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\Message;
use App\Models\User;
use App\Jobs\SendMessage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use JetBrains\PhpStorm\Pure;

trait HelperTrait
{

//    public array $breadcrumbs = [];
//    public array $data = [];
//


//
    public string $validationPhone = 'regex:/^((\+)?(\d)(\s)?(\()?[0-9]{3}(\))?(\s)?([0-9]{3})(\-)?([0-9]{2})(\-)?([0-9]{2}))$/';
    public string $validationId = 'required|integer|exists:';
    public string $validationPassword = 'required|confirmed|min:3|max:50';
    public string $validationLoginPassword = 'required|min:3|max:50';
    public string $validationImage = 'mimes:jpeg|min:5|max:5000';
    public string $validationContactPerson = 'nullable|min:3|max:255';
    public string $validationEmail = 'nullable|email';
    public string $validationName = 'required|min:5|max:255';
    public string $validationValue = 'required|integer|max:2000000';
    public string $validationDate = 'required|regex:/^((\d){2}\/(\d){2}\/(\d){4})$/';
    public string $validationBillNumber = 'required|integer|min:1|unique:bills,number';
    public string $validationTaskId = 'required|integer|exists:tasks,id';
    public string $validationCustomerId = 'required|integer|exists:customers,id';
    public string $validationBankName = 'required|min:10|max:255';
    public string $validationBankId = 'required|size:9';
    public string $validationCheckingAccount = 'required|min:20|max:24';
    public string $validationCorrespondentAccount = 'required|min:20|max:24';



//    public function saveCompleteMessage(): void
//    {
//        Session::flash('message',__('Saving completed'));
//    }
//

//



//
//    public function getTaskValidationSomeFields($customerId, $validationArr, $timeFields): array
//    {
//        $customer = Customer::query()->findOrFail($customerId);
//        $validationArr['status'] = 'required|integer|min:1|max:5';
//        if ($customer->ltd != 2 && !in_array('convention_date', $timeFields)) $timeFields[] = 'convention_date';
//        return [$validationArr, $timeFields];
//    }
//
//    public function getSimpleTaskStatus($taskStatus): string
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

//
//    public function getBaseFieldsMailMessage($task): array
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
//    #[Pure] public function getNewTaskFieldsMailMessage($task, $subTask, $fields): array
//    {
//        $fields['id'] = $subTask ? $subTask->id : $task->id;
//        $fields['parent_id'] = $subTask?->task->id;
//        $fields['parent_name'] = $subTask?->task->name;
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
//    public function processingWorkImageFields($work): array
//    {
//        return array_merge(
//            $this->processingImage($work, 'preview', $work->branch->eng.'_'.$work->id.'_preview', 'storage/images/portfolio/'.$work->branch->eng),
//            $this->processingImage($work, 'full', $work->branch->eng.'_'.$work->id.'_full', 'storage/images/portfolio/'.$work->branch->eng)
//        );
//    }
//
//    public function processingImage(Model $model, $field, $name=null, $path=null): array
//    {
//        $imageField = [];
//        if (request()->hasFile($field)) {
//            $this->unlinkFile($model, $field);
//
//            $info = pathinfo($model[$field]);
//            $imageName = ($name ?? $info['filename']).'.'.request()->file($field)->getClientOriginalExtension();
//            $path = $path ?? $info['dirname'];
//
//            request()->file($field)->move(base_path('public/'.$path),$imageName);
//            $imageField[$field] = $path.'/'.$imageName;
//        }
//        return $imageField;
//    }

//    public function processingFields($checkboxFields=null, $ignoreFields=null, $timeFields=null): array
//    {
//        $exceptFields = ['id'];
//        if ($ignoreFields) {
//            if (is_array($ignoreFields)) $exceptFields = array_merge($exceptFields, $ignoreFields);
//            else $exceptFields[] = $ignoreFields;
//        }
//        $fields = request()->except($exceptFields);
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
//        return $fields;
//    }



//    protected function checkTaskEdit($task)
//    {
//        if (Helper::forbbidenTaskEdit($task)) abort(403);
//    }

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

//    public function checkTaskStatus($fields)
//    {
//        if ($fields['status'] == 5) $fields['completion_time'] = time() + (60 * 60 * 24 * 2);
//        return $fields;
//    }

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

//    public function deleteSomething(Model $model, $files=null, $checkRightsField=null): JsonResponse
//    {
//        $table = $model->query()->findOrFail(request()->id);
//        if (
//            ($model instanceof User && request()->id == 1) ||
//            ($checkRightsField && Gate::denies('check-rights',[$table, $checkRightsField]))
//        ) response()->json(['success' => false]);
//
//        if ($model instanceof User) {
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
//    public function createTaskMessage($task,$mailView,$mailFields,$messageText,$messageStatus,$sendMail): void
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
//        Message::query()->create([
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
//    public function sendMessage($mailTo, $copyMail, $template, array $fields=[], $pathToFile=null): void
//    {
//        dispatch(new SendMessage($mailTo, $copyMail, $template, $fields, $pathToFile));
//    }
//

//    public function wrongCompletionTime()
//    {
//        return redirect()->back()->withInput()->withErrors(['completion_time' => $this->wrongCompletionTime]);
//    }

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

//    public function convertTime($time): string
//    {
//        $time = explode('/', $time);
//        return $time[1].'/'.$time[0].'/'.$time[2];
//    }
//
//    public function checkTasks(): void
//    {
//        $warningTime = (60*60*24);
//        $tasksInWork = Task::query()->where('completion_time','<',(time() + $warningTime))->where( function($query){$query->where('status',3)->orWhere('status',5);} )->get();
//        $completedTasks = Task::query()->where('payment_time','<',time())->where('status',2)->get();
//        $subTasksInWork = SubTask::query()->where('completion_time','<',(time() + $warningTime))->where( function($query){$query->where('status',3)->orWhere('status',5);} )->get();
//
//        $this->checkTasksInWork($tasksInWork,$warningTime);
//        $this->checkTasksInWork($subTasksInWork,$warningTime);
//
//        foreach ($completedTasks as $task) {
//            if ($task->payment_time) $this->checkMessage($task,7,'оплаты','истекло');
//        }
//    }
//
//    public function ruNumeral($num=0): string
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
//    public function getMessages($addCondition=null): array
//    {
//        $result = [];
//        $messages = Message::query()->where('active_to_owner',1)->orWhere('active_to_user',1)->get();
//        foreach ($messages as $message) {
//            if (
//                ($message->owner && Gate::allows('owner-message-not-admin', $message) && $message->active_to_owner) ||
//                ($message->user && Gate::allows('user-message-not-admin', $message) && $message->active_to_user) ||
//                $addCondition
//            ) {
//                $result[] = $message;
//            }
//        }
//        return $result;
//    }
//
//    public function sqlDump(): void
//    {
//        $dumpName = base_path('sql/dump').date('dmy').'.sql';
//        echo shell_exec("mysqldump --user=".Config::get('app.db_user')." --password=".Config::get('app.db_password')." --host=127.0.0.1 ".Config::get('app.db_name')." > ".$dumpName);
//        $this->sendMessage(Config::get('app.master_mail'),null, 'sql_dump', [], $dumpName);
////        unlink($dumpName);
//    }
//
//    private function changeUserTask($tasks, $field): void
//    {
//        foreach ($tasks as $task) {
//            $task[$field] = 1;
//            $task->save();
//        }
//    }
//
//    private function seenAll(): JsonResponse
//    {
//        $messagesList = [];
//        $messages = $this->getMessages();
//        foreach ($messages as $message) {
//            $this->setSeenMessage($message);
//            $messagesList[] = $message->id;
//        }
//        return response()->json(['success' => true, 'messages' => $messagesList]);
//    }
//
//    private function unlinkFile($table, $file, $path=''): void
//    {
//        $fullPath = base_path('public/'.$path.$table[$file]);
//        if (isset($table[$file]) && $table[$file] && file_exists($fullPath)) unlink($fullPath);
//    }
//
//    private function checkTasksInWork($tasks, $warningTime): void
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
//    private function checkMessage($task,$messageStatus,$timeType,$timeStatus): void
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
