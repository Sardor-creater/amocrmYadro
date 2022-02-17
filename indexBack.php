<?php

require_once('../intr-sdk-test/vendor/autoload.php');

Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', '23bc075b710da43f0ffb50ff9e889aed');
$api = new Introvert\ApiClient();

$statuses = array(142,143);


$crm_user_id = null; // int[] | фильтр по id ответственного
$status = $statuses; // int[] | фильтр по id статуса
$id = null; // int[] | фильтр по id
$ifmodif = null; // string | фильтр по дате изменения. timestamp или строка в формате 'D, j M Y H:i:s'
$count = 25; // int | Количество запрашиваемых элементов
$offset = 0; // int | смещение, относительно которого нужно вернуть элементы
$leads = [];
$isUnFinish = true;
$page = 1;

while ($isUnFinish) {
    $result = $api->lead->getAll($crm_user_id, $status, $id, $ifmodif, $count, $offset);
    array_push($leads, ...$result['result']);
    $offset = $count * $page;
    $isUnFinish = $result['count'] == $count;
    $page++;
}

$myFunction = function($idDop, $time, $statuses) use ($leads)
{
    $leadWith = [];
    $num = 0;

    foreach ($leads as $lead){
        if (!empty($lead['custom_fields'])){
            foreach ($lead['custom_fields'] as $field){
                if ($field['id'] == $idDop){
                    array_push($leadWith, $lead);
                    foreach ($field['values'] as $value){
                        if (str_starts_with($value['value'], $time)){$num++;}
                    }
                }
            }
        }
    }

    return $num;
};

$getData = function () use($myFunction, $statuses){
    $date = date("Y-m-d");
    $arrBack = [];
    for ($i = 1; $i <= 30; $i++) {
        array_push($arrBack, $myFunction(1520103, $date, $statuses));
        $date = date("Y-m-d", strtotime($date . ' +1 day'));
    }
    return $arrBack;
};


header('Content-Type: application/json');
echo json_encode($getData());
exit;


?>
