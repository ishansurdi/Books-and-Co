let chatVisible = false;

function toggleChat() {
    const chatbox = document.getElementById('chatbox');
    const assistant = document.getElementById('ai-assistant');

    chatVisible = !chatVisible;

    if (chatVisible) {
        assistant.style.transform = 'scale(0)'; // Shrink effect
        setTimeout(() => {
            chatbox.classList.remove('hidden');
            chatbox.style.transform = 'translateY(-10px)'; // Slightly raise chatbox
        }, 300);
        setTimeout(() => {
            assistant.style.transform = 'scale(1)'; // Restore the assistant icon
        }, 400);
    } else {
        chatbox.style.transform = 'translateY(0)'; // Reset chatbox position
        setTimeout(() => {
            chatbox.classList.add('hidden');
        }, 300);
    }
}

// Function to send a request to the Gemini API
// Function to send a request to the Gemini API
async function sendMessageToGemini(message) {
    try {
        const response = await fetch('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=AIzaSyDca1GQZGYemvIKzls5WcM0lBOVeR3lquU', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                // Directly pass the message as a string
                prompt: message // Adjust this based on the actual API requirement
            })
        });

        if (!response.ok) {
            const errorDetails = await response.json();
            console.error('Error details:', errorDetails); // Log full error response for debugging
            throw new Error(`Network response was not ok: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        console.log('API response:', data); // Log the entire API response
        displayResponse(data.response.text); // Adjust based on actual response structure
    } catch (error) {
        displayResponse('Error: ' + error.message);
    }
}





// Function to display response in the chatbox
function displayResponse(response) {
    const chatContent = document.querySelector('.chatbox-content');
    chatContent.innerHTML += `<p>${response}</p>`;
    chatContent.scrollTop = chatContent.scrollHeight; // Scroll to the bottom
}

// Event listener for send button
document.querySelector('button').addEventListener('click', () => {
    const input = document.querySelector('input[type="text"]');
    const message = input.value.trim();
    if (message) {
        displayResponse('You: ' + message);
        sendMessageToGemini(message); // Send message to Gemini API
        input.value = ''; // Clear input
    }
});

// Event listener for Enter key
document.querySelector('input[type="text"]').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        document.querySelector('button').click(); // Trigger send button
    }
});
