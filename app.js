const API_URL = "api/tasks.php";
async function loadTasks() {
  const res = await fetch(`${API_URL}?action=list`);
  const tasks = await res.json();
  renderTasks(tasks);
}
function renderTask(task) {


  await fetch(`${API_URL}?action=add`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
  });
  document.getElementById("title").value = "";
  loadTasks();
});
loadTasks();
