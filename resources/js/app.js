import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    // Trigger Enter key to submit form
    const textarea = document.querySelector("#textMessage");
    if (textarea) {
        textarea.addEventListener("keydown", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                this.form.requestSubmit();
            }
        });
    }

    // Trigger input textarea
    document.addEventListener("keydown", function (event) {
        if (event.key === "Tab") {
            event.preventDefault();
            const textareaElement = document.getElementById("textMessage");
            if (textareaElement) {
                textareaElement.focus();
            }
        }
    });
});
