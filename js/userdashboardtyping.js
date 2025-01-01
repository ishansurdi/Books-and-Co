document.addEventListener("DOMContentLoaded", function () {
    const messages = [`Welcome, ${firstName}!`, "What do you want to read?"];
    let currentMessageIndex = 0;
    let currentCharacterIndex = 0;
    let isDeleting = false;
    const welcomeMessageElement = document.getElementById("welcome-message");

    function type() {
        const currentMessage = messages[currentMessageIndex];
        const typedText = isDeleting
            ? currentMessage.substring(0, currentCharacterIndex - 1)
            : currentMessage.substring(0, currentCharacterIndex + 1);

        welcomeMessageElement.innerHTML = typedText;

        if (!isDeleting && currentCharacterIndex === currentMessage.length) {
            isDeleting = true;
            setTimeout(type, 2000); // Pause before deleting
        } else if (isDeleting && currentCharacterIndex === 0) {
            isDeleting = false;
            currentMessageIndex = (currentMessageIndex + 1) % messages.length;
            setTimeout(type, 1000); // Pause before typing the next message
        } else {
            setTimeout(type, isDeleting ? 100 : 200); // Speed of typing and deleting
        }

        currentCharacterIndex += isDeleting ? -1 : 1;
    }

    type();
});
