const inputTask = document.getElementById("inputTask");
const priorityLevel = document.getElementById("priorityLevel");
const inputDate = document.getElementById("inputDate");
const addTask = document.getElementById("addTask");
const lists = document.getElementById("lists");

let tasks = JSON.parse(localStorage.getItem("tasks")) || [];

function updateLocalStorage() {
    localStorage.setItem("tasks", JSON.stringify(tasks));
}

function renderTasks() {
    lists.innerHTML = "";
    console.log(JSON.stringify(tasks));
    tasks.sort((a, b) => b.priority - a.priority);

    tasks.forEach((task, index) => {
        const li = document.createElement("li");
        const taskPriority = task.priority === "2" ? "!!!" : "!";
        const taskDueDate = task.dueDate ? `Due Date: ${task.dueDate}` : "";

        const completeButtonText = (task.completed > 0) ? "Undo" : "Complete";

        li.innerHTML = `
            <span>${task.taskDescription}</span>
            <span>Priority: ${taskPriority}</span>
            <span>${taskDueDate}</span>
            <span>
                <button class="delete" data-index="${index}"><img src="trashicon.png"/>  Delete </button>
                <button class="complete" data-index="${index}"><img src="checkmark.png"/>${completeButtonText}</button>
            </span>
        `;

        if (task.completed == 1) {
            li.classList.add("completed");
        }
        
                   

        lists.appendChild(li);
    });
}

function fetchTasks() {
    console.log("line 46")
    fetch('get_tasks.php')
    .then(response => response.json())
    .then(data => {
        tasks = data;
        renderTasks();
    })
    .catch(error => console.error('Error:', error));
}

// Call fetchTasks when the page loads
document.addEventListener('DOMContentLoaded', fetchTasks);


addTask.addEventListener("click", () => {
    const taskText = inputTask.value.trim();
    if (taskText === "") return;

    fetch('add_task.php', {
        method: 'POST',
        body: JSON.stringify({
            text: taskText,
            priority: priorityLevel.value,
            dueDate: inputDate.value
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            fetchTasks(); // Re-fetch tasks to update the list
        }
    })
    .catch(error => console.error('Error:', error));

    inputTask.value = "";
    inputDate.value = "";
});

lists.addEventListener("click", (e) => {
    if (e.target.classList.contains("delete")) {
        const index = e.target.getAttribute("data-index");
        const taskId = tasks[index].id; // Assuming your tasks have an 'id' property

        fetch('delete_task.php', {
            method: 'POST',
            body: JSON.stringify({ id: taskId }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                fetchTasks(); // Re-fetch tasks to update the list
            }
        })
        .catch(error => console.error('Error:', error));
    }
    else if (e.target.classList.contains("complete")) {
        console.log("line 108");
        const index = e.target.getAttribute("data-index");
        const taskId = tasks[index].id; 
        const completed = (tasks[index].completed == 0) ? 1 : 0;
        console.log("line 112 " + completed);

        fetch('complete_task.php', {
            method: 'POST',
            body: JSON.stringify({ 
                id: taskId,
                completed: completed
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log("line 126 " + data);
            if(data.success) {
                fetchTasks(); // Re-fetch tasks to update the list
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

renderTasks();
