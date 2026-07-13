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
function addTask(&$tasks, $title, $priority = 3) {
$newId = count($tasks) ? max(array_column($tasks, 'id')) + 1 : 1;
$task = ["id" => $newId, "title" => $title, "status" => "open", "priority" =>
$priority];
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
$priority = $input['priority'] ?? 3;
$task = addTask($tasks, $input['title'], $priority);
saveTasks($dataFile, $tasks);
echo json_encode($task);
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
<?php
function parseDate($dateStr) {
return DateTime::createFromFormat('Y-m-d', $dateStr);
}
function isPastDue($dateStr) {
$date = parseDate($dateStr);
return $date && $date < new DateTime();
}
function formatRelativeDate($dateStr) {
$date = parseDate($dateStr);
if (!$date) return '';
$diff = (new DateTime())->diff($date);
$days = (int)$diff->format('%r%a');
if ($days < 0) return abs($days) . ' day(s) overdue';
if ($days === 0) return 'due today';
return "due in {$days} day(s)";
}
<?php
require __DIR__ . '/../api/tasks.php';
function assertTrue($cond, $message) {
echo ($cond ? 'PASS: ' : 'FAIL: ') . $message . PHP_EOL;
}
$tasks = [];
$task = addTask($tasks, 'Write report');
assertTrue($task['id'] === 1, 'First task gets id 1');
addTask($tasks, 'Review PR');
assertTrue(count($tasks) === 2, 'Two tasks added');
$tasks[0]['status'] = 'done';
assertTrue($tasks[0]['status'] === 'done', 'Task marked done');