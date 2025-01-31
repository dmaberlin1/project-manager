import './bootstrap';
window.Echo.private("tasks")
    .listen("TaskCreated", (e) => {
        console.log("Новое задание создано:", e.task);
    });
