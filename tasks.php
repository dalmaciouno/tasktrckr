<?php
header("Content-Type: application/json");
$dataFile = __DIR__ . "/../data/tasks.json";
function loadTasks($dataFile) {
if (!file_exists($dataFile)) return [];
return json_decode(file_get_contents($dataFile), true);
}
function saveTasks($dataFile, $tasks) {
file_put_contents($dataFile, json_encode($tasks, JSON_PRETTY_PRINT));
}
function addTask(&$tasks, $title) {
$newId = count($tasks) ? max(array_column($tasks, 'id')) + 1 : 1;
$task = ["id" => $newId, "title" => $title, "status" => "open"];
$tasks[] = $task;
return $task;
}
$action = $_GET['action'] ?? '';
$tasks = loadTasks($dataFile);
switch ($action) {
case 'list':
echo json_encode($tasks);
break;
case 'add':
$input = json_decode(file_get_contents('php://input'), true);
$task = addTask($tasks, $input['title']);
saveTasks($dataFile, $tasks);
echo json_encode($task);
case 'search':
    $q = strtolower($_GET['q'] ?? '');
    $results = array_values(array_filter($tasks, function ($t) use ($q) {
    return strpos(strtolower($t['title']), $q) !== false;
    }));
    echo json_encode($results);
    break;
case 'done':
$input = json_decode(file_get_contents('php://input'), true);
foreach ($tasks as &$t) {
if ($t['id'] == $input['id']) $t['status'] = 'done';
}
saveTasks($dataFile, $tasks);
echo json_encode(["success" => true]);
break;
default:
http_response_code(400);
echo json_encode(["error" => "Unknown action"]);
}