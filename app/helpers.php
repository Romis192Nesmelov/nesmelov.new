<?php

use App\Models\Bill;
use App\Models\Task;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

function getSettingsXML(): SimpleXMLElement
{
    return simplexml_load_file(env('SETTINGS_XML'));
}

function getIncomeStatuses(): array
{
    return [__('Paid'),__('Completed'),__('Prepayment')];
}

function getBillsStatuses(): array
{
    return [__('Paid'),__('Issued for the full amount'),__('Issued for a portion of the amount')];
}

#[ArrayShape(['done' => "mixed", 'wait' => "mixed", 'work' => "mixed", 'hold' => "mixed", 'returned' => "mixed", 'fake_made' => "mixed", 'fake_done' => "mixed"])]
function getTaskConditions(): array
{
    return [
        'done' => __('Paid'),
        'wait' => __('Completed'),
        'work' => __('In progress'),
        'hold' => __('Postponed'),
        'returned' => __('Refinement'),
        'fake_made' => __('Fake created'),
        'fake_done' => __('Fake paid')
    ];
}

function getMetas(): array
{
    return [
        'meta_description' => ['name' => 'description', 'property' => false],
        'meta_keywords' => ['name' => 'keywords', 'property' => false],
        'meta_twitter_card' => ['name' => 'twitter:card', 'property' => false],
        'meta_twitter_size' => ['name' => 'twitter:size', 'property' => false],
        'meta_twitter_creator' => ['name' => 'twitter:creator', 'property' => false],
        'meta_og_url' => ['name' => false, 'property' => 'og:url'],
        'meta_og_type' => ['name' => false, 'property' => 'og:type'],
        'meta_og_title' => ['name' => false, 'property' => 'og:title'],
        'meta_og_description' => ['name' => false, 'property' => 'og:description'],
        'meta_og_image' => ['name' => false, 'property' => 'og:image'],
        'meta_robots' => ['name' => 'robots', 'property' => false],
        'meta_googlebot' => ['name' => 'googlebot', 'property' => false],
        'meta_google_site_verification' => ['name' => 'robots', 'property' => false],
    ];
}

function getSeoTags(): array
{
    $tags = ['title' => ''];
    $settings = getSettingsXML();
    $metas = getMetas();

    if ($settings->seo->title) $tags['title'] = (string)$settings->seo->title;
    foreach ($metas as $meta => $params) {
        $tags[$meta] = (string)$settings->seo->$meta;
    }
    return $tags;
}

function getSettings(): array
{
    return (array)getSettingsXML()->settings;
}

function getRequisites(): array
{
    return (array)getSettingsXML()->requisites;
}

function calculateTaskValForBill($item): string
{
    if ($item instanceof Bill) {
        if ($item->task->paid_off) {
            $billNumber = 1;
            foreach ($item->task->bills as $bill) {
                if ($item->id == $bill->id) break;
                else $billNumber++;
            }
            $value = $billNumber == 1 ? $item->task->paid_off : $item->task->value - $item->task->paid_off;
        } else $value = $item->task->value + calculateSubTasksValue($item->task);;
    } else {
        $task = $item instanceof Task ? $item : Task::query()->find($item);
        $value = $task->value + calculateSubTasksValue($task);
        if ($task->paid_off) {
            $value = count($task->bills) == 1 ? $value - $task->paid_off : $task->paid_off;
        };
    }
    return moneyFormat($value).'₽';
}

function calculateOverTaskVal($task, $fullVal=true, $percents=false, $duty=false, $checkFake=false): int
{
    $value = $task->value;

    if ($task instanceof Task) $value += calculateSubTasksValue($task,$percents);
    $baseValue = $value;

    if ($checkFake && $task->status == 7) $value = calculateMyPercent($task, $baseValue);
    elseif ( ($task->status == 3 || $task->status == 4) && $task->paid_off && !$fullVal) $value = $task->paid_off;

    if ($duty) $value -= calculateTaskDuty($value, $task);
    if ($percents && $task->percents) $value -= calculateTaskPercents($value,$task->percents);
    if ($task->paid_off && !$fullVal) $value -= $task->paid_off;

    return $value;
}

#[Pure] function calculateSubTasksValue($task, $percents=false): int
{
    $value = 0;
    if (isset($task->subTasks) && count($task->subTasks)) {
        foreach($task->subTasks as $subTask) {
            if ($subTask->status == 1 || $subTask->status == 2)
                $value += $percents && $subTask->percents ? $subTask->value - calculateTaskPercents($subTask->value,$subTask->percents) : $subTask->value;
        }
    }
    return $value;
}

function calculateTaskDuty($value, $task): float
{
    $settings = getSettings();
    if ((int)($task->tax_type)) return $value * $settings['tax'] * 0.01;
    else return $value * (int)($task->ltd != 2 ? $settings['tax1'] : $settings['tax2']) * 0.01;
}

function calculateTaskPercents($value, $percents): float
{
    return $value * $percents * 0.01;
}

function calculateMyPercent($task, $value): float
{
    return $value * ($task->my_percent ? $task->my_percent : (int)getSettings()['my_percent']) * 0.01;
}

function moneyFormat($value): string
{
    return number_format($value, 0, ',', ' ');
}

function ruNumScript($value): string
{
    # Все варианты написания чисел прописью от 0 до 999 скомпонуем в один небольшой массив
    $m = [
        ['ноль'],
        ['-','один','два','три','четыре','пять','шесть','семь','восемь','девять'],
        ['десять','одиннадцать','двенадцать','тринадцать','четырнадцать','пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать'],
        ['-','-','двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят','восемьдесят','девяносто'],
        ['-','сто','двести','триста','четыреста','пятьсот','шестьсот','семьсот','восемьсот','девятьсот'],
        ['-','одна','две']
    ];

    # Все варианты написания разрядов прописью скомпануем в один небольшой массив
    $r = [
        ['...ллион','','а','ов'], // используется для всех неизвестно больших разрядов
        ['тысяч','а','и',''],
        ['миллион','','а','ов'],
        ['миллиард','','а','ов'],
        ['триллион','','а','ов'],
        ['квадриллион','','а','ов'],
        ['квинтиллион','','а','ов']
        // ,[... список можно продолжить
    ];

    if ($value==0) return $m[0][0]; # Если число ноль, сразу сообщить об этом и выйти
    $o = []; # Сюда записываем все получаемые результаты преобразования

    # Разложим исходное число на несколько трехзначных чисел и каждое полученное такое число обработаем отдельно
    foreach (array_reverse(str_split(str_pad($value,ceil(strlen($value)/3)*3,'0',STR_PAD_LEFT),3))as$k=>$p) {
        $o[$k] = [];

        # Алгоритм, преобразующий трехзначное число в строку прописью
        foreach ($n = str_split($p) as $kk => $pp)
            if (!$pp) continue;
            else
                switch ($kk) {
                    case 0:$o[$k][]=$m[4][$pp];break;
                    case 1:if($pp==1){$o[$k][]=$m[2][$n[2]];break 2;}else$o[$k][]=$m[3][$pp];break;
                    case 2:if(($k==1)&&($pp<=2))$o[$k][]=$m[5][$pp];else$o[$k][]=$m[1][$pp];break;
                } $p*=1;if(!$r[$k])$r[$k]=reset($r);

        # Алгоритм, добавляющий разряд, учитывающий окончание руского языка
        if ($p&&$k) switch (true) {
            case preg_match("/^[1]$|^\\d*[0,2-9][1]$/",$p):$o[$k][]=$r[$k][0].$r[$k][1];break;
            case preg_match("/^[2-4]$|\\d*[0,2-9][2-4]$/",$p):$o[$k][]=$r[$k][0].$r[$k][2];break;
            default: $o[$k][]=$r[$k][0].$r[$k][3];break;
        }
        $o[$k] = implode(' ',$o[$k]);
    }

    return mbStrToUpper(implode(' ',array_reverse($o)));
}

function mbStrToUpper($str, $encoding = 'UTF8'): string
{
    return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
}

function mbFirstStrToLower($str, $encoding = 'UTF8'): string
{
    $fc = mb_strtolower(mb_substr($str, 0, 1, $encoding), $encoding);
    return $fc.mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
}

function isFinalBill(Bill $bill): bool
{
    return !$bill->task->paid_off || ($bill->task->paid_off && count($bill->task->bills) > 1 && $bill->task->bills[count($bill->task->bills)-1]->id == $bill->id);
}

function isFakeTask(array $data): bool
{
    return isset($data['task']) && $data['task']->status >= 6;
}

function isPrivatePersonTheCustomer($data): bool
{
    return !isset($data['customer']) || $data['customer']->ltd == 2;
}

function isPrivatePersonTheCustomerOfTask($data): bool
{
    return (!isset($data['task']) && $data['customers'][0]->ltd == 2) || (isset($data['task']) && $data['task']->customer->ltd == 2);
}

function isUsedDuty($data): bool
{
    return (!isset($data['task']) || (isset($data['task']) && $data['task']->use_duty));
}
