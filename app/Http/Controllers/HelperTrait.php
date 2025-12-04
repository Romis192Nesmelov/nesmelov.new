<?php

namespace App\Http\Controllers;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\Message;
use App\Jobs\SendMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

trait HelperTrait
{
    public string $validationPhone = 'regex:/^((\+)?(\d)(\s)?(\()?[0-9]{3}(\))?(\s)?([0-9]{3})(\-)?([0-9]{2})(\-)?([0-9]{2}))$/';
    public string $validationId = 'required|integer|exists:';
    public string $validationPassword = 'required|confirmed|min:3|max:50';
    public string $validationLoginPassword = 'required|min:3|max:50';
    public string $validationImage = 'nullable|mimes:jpeg|min:5|max:5000';
    public string $validationImageRequired = 'nullable|mimes:jpeg|min:5|max:5000';
    public string $validationContactString = 'nullable|min:3|max:255';
    public string $validationEmail = 'nullable|email';
    public string $validationName = 'required|min:5|max:255';
    public string $validationValue = 'required|integer|max:2000000';
    public string $validationDate = 'required|regex:/^((\d){2}\/(\d){2}\/(\d){4})$/';
    public string $validationBillNumber = 'required|integer|min:1|unique:bills,number';
    public string $validationTaskId = 'required|integer|exists:tasks,id';
    public string $validationCustomerId = 'required|integer|exists:customers,id';
    public string $validationBankName = 'required|min:10|max:255';
    public string $validationString = 'required|max:255';
    public string $validationBankId = 'required|size:9';
    public string $validationCheckingAccount = 'required|min:20|max:24';
    public string $validationCorrespondentAccount = 'required|min:20|max:24';

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changeLang(): RedirectResponse
    {
        $this->validate(request(), ['lang' => 'required|in:en,ru']);
        Cookie::queue(Cookie::make('lang', request()->lang, time()+(60*60*24*365)));
//        setcookie('lang', request()->lang, time()+(60*60*24*365));
        return redirect()->back();
    }

    public function putFile($file, string $addPath=''): string
    {
        $path = Storage::putFile('public/images/'.$addPath, $file);
        return str_replace('public/', 'storage/', $path);
    }

    public function deleteFile(string $path): void
    {
        Storage::delete(str_replace('storage/', 'public/', $path));
    }

    public function saveCompleteMessage(): void
    {
        Session::flash('message',__('Saving completed'));
    }

    public function sendMessage(string $mailTo, string|null $copyMail, string $template, array $fields=[], string $pathToFile=null): void
    {
        dispatch(new SendMessage($mailTo, $copyMail, $template, $fields, $pathToFile));
    }

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

    public function convertTimeFields(array $fields, array $values): array
    {
        foreach ($values as $value) {
            $time = explode('/', $fields[$value]);
            $fields[$value] = strtotime($time[1].'/'.$time[0].'/'.$time[2]);
        }
        return $fields;
    }

    public function convertCheckFields(array $fields, array $values): array
    {
        foreach ($values as $value) {
            isset($fields[$value]) && $fields[$value] == 'on' ? $fields[$value] = 1 : $fields[$value] = 0;
        }
        return $fields;
    }

    public function checkTasks(): void
    {
        $warningTime = (60*60*24);
        $tasksInWork = Task::query()->where('completion_time','<',(time() + $warningTime))->where( function($query){$query->where('status',3)->orWhere('status',5);} )->get();
        $completedTasks = Task::query()->where('payment_time','<',time())->where('status',2)->get();
        $subTasksInWork = SubTask::query()->where('completion_time','<',(time() + $warningTime))->where( function($query){$query->where('status',3)->orWhere('status',5);} )->with('task')->get();

        $this->checkTasksInWork($tasksInWork,$warningTime);
        $this->checkTasksInWork($subTasksInWork,$warningTime);

        foreach ($completedTasks as $task) {
            if ($task->payment_time) $this->checkMessage($task,7,__('payments'),__('expired'));
        }
    }

    public function sqlDump(): void
    {
        $dumpName = base_path('/storage/sql_dump').date('dmy').'.sql';
        echo shell_exec('mysqldump --user='.env('app.DB_USERNAME').' --password='.env('DB_PASSWORD').' --host='.env('DB_HOST').' '.env('DB_DATABASE').' > '.$dumpName);
        $this->sendMessage(env('MAIL_TO'),null, 'sql_dump', [], $dumpName);
        unlink($dumpName);
    }

    private function checkTasksInWork($tasks, $warningTime): void
    {
        foreach ($tasks as $task) {
            $timeType = __('implementations');
            if (isset($task->task) && $task->task->status != 3 && $task->task->status != 5) continue;
            if ($task->completion_time < time()) {
                $this->checkMessage($task,1,$timeType,__('expired'));
            } elseif ($task->completion_time < time() + $warningTime) {
                $this->checkMessage($task,2,$timeType,__('running out'));
            }
        }
    }

    private function checkMessage($task,$messageStatus,$timeType,$timeStatus): void
    {
        $messages = Message::query()->where('task_id',$task->id)->get();
        $matches = false;

        foreach ($messages as $message) {
            if ($message->status == $messageStatus) {
                $matches = true;
                break;
            }
        }

        if (!$matches) {
            $mailFields = $this->getBaseFieldsMailMessage($task);
            $mailFields['time_type'] = $timeType;
            $mailFields['time_status'] = $timeStatus;
            $this->createTaskMessage($task,'task_time_expires',$mailFields,(__('The time').' '.$timeType.' '.__('this').' '.(isset($task->task) ? __('subtasks') : __('for this task')).' '.$timeStatus),$messageStatus,true);
        }
    }
}
