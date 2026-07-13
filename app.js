const API_URL = "api/tasks.php";
async function loadTasks() {
const res = await fetch(`${API_URL}?action=list`);
const tasks = await res.json();
renderTasks(tasks);
}
function renderTask(task) {
return `<li class="task-item" data-id="${task.id}">
<span>${task.title}</span>
</li>`;
}
function renderTasks(tasks) {
const list = document.getElementById("task-list");
list.innerHTML = tasks.map(renderTask).join('');
}
document.getElementById("task-form").addEventListener("submit", async (e) => {
e.preventDefault();
const title = document.getElementById("title").value;
await fetch(`${API_URL}?action=add`, {
method: "POST",
headers: { "Content-Type": "application/json" },
body: JSON.stringify({ title })
});
document.getElementById("title").value = "";
loadTasks();
});
document.getElementById("search").addEventListener("input", async (e) => {
    const q = e.target.value;
    const res = await fetch(`${API_URL}?action=search&q=${encodeURIComponent(q)}`);
    const tasks = await res.json();
    renderTasks(tasks);
});
loadTasks();