const API_URL = "api/tasks.php";
async function loadTasks() {
  const res = await fetch(`${API_URL}?action=list`);
  const tasks = await res.json();
  renderTasks(tasks);
}
function renderTask(task) {
  const priorityClass = `priority-${task.priority || 3}`;
  return `<li class="task-item" data-id="${task.id}">
        <span>${task.title}</span>
        <span class="badge ${priorityClass}">P${task.priority || 3}</span>
    </li>`;
}
function renderTasks(tasks) {
  const list = document.getElementById("task-list");
  list.innerHTML = tasks.map(renderTask).join("");
}

document.getElementById("task-form").addEventListener("submit", async (e) => {
  e.preventDefault();
  const title = document.getElementById("title").value;
  const priority = document.getElementById("priority").value;

  await fetch(`${API_URL}?action=add`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ title, priority }),
  });
  document.getElementById("title").value = "";
  loadTasks();
});
loadTasks();
