<?php
require_once('../intr-sdk-test/vendor/autoload.php');

$date_from = $_GET['date_from'];
$date_to = $_GET['date_to'];

function getClients() {
    return [
        [
            'id' => 1,
            'name' => 'intrdev',
            'api' => '23bc075b710da43f0ffb50ff9e889aed'
        ],
        [
            'id' => 2,
            'name' => 'artedegrass0',
            'api' => '23bc075b710da43f0ffb50ff9e889aes',
        ],
    ];
}
echo '<br>';
$clients = getClients();
$allBudget = 0;

echo '<table>
            <tr>
                <th>ID клиента</th>
                <th>Название клиента:</th>
                <th>Сумма его успешных сделок за период:</th>
            </tr>';

foreach ($clients as $client){
    Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', $client['api']);
    echo '<tr><td>'. $client['id'] . '</td>';
    echo ' <td>'. $client['name'] . '</td>';
    $clientBudget = getBudget($date_from, $date_to);
    echo '<td>'. $clientBudget . '</td></tr>';
    if (is_int($clientBudget)){
        $allBudget += $clientBudget;
    }

}
echo ' </table>';

echo 'Cумму по всем клиентам за период ----- <b>' .$allBudget. '</b>';


    function getBudget($date_from, $date_to)
    {
        $api = new Introvert\ApiClient();
        $crm_user_id = null; // int[] | фильтр по id ответственного
        $status = array(142); // int[] | фильтр по id статуса
        $id = null; // int[] | фильтр по id
        $ifmodif = $date_from; // string | фильтр по дате изменения. timestamp или строка в формате 'D, j M Y H:i:s'
        $count = 25; // int | Количество запрашиваемых элементов
        $offset = 0; // int | смещение, относительно которого нужно вернуть элементы
        $leads = [];
        $isUnFinish = true;
        $page = 1;
        $budget = 0;

        while ($isUnFinish) {

            try {
                $result = $api->lead->getAll($crm_user_id, $status, $id, $ifmodif, $count, $offset);
            } catch (Exception $e) {
                return 'invalid key';
            }
            array_push($leads, ...$result['result']);
            $offset = $count * $page;
            $isUnFinish = $result['count'] == $count;
            $page++;
        }
        foreach ($leads as $lead){
            if ($lead['date_create'] >=$date_from && $lead['date_close'] <= $date_to){
                $budget += $lead['price'];
            }
        }
        return $budget;
    }
?>




