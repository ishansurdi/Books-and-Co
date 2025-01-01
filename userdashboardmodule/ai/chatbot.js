// chatbot.js

// Add message with "You" and "AI" labels
function addMessage(sender, message) {
    const chatContent = document.getElementById("chatbox-content");
  
    // Message label (either "You" or "AI")
    const label = document.createElement("div");
    label.classList.add("message-label");
    label.textContent = sender === "user" ? "You" : "AI";
    chatContent.appendChild(label);
  
    // Message bubble
    const messageElement = document.createElement("div");
    messageElement.classList.add(sender === "user" ? "user-message" : "bot-message");
    messageElement.textContent = message;
    chatContent.appendChild(messageElement);
    
    chatContent.scrollTop = chatContent.scrollHeight; // Scroll to the bottom
  }
  
  // Function to fetch recommendations and display in chat
  function fetchRecommendations(MID) {
    fetch(`get_recommendations.php?MID=${MID}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                addMessage("bot", "Here are some book recommendations for you:");
                data.forEach(book => {
                    addMessage("bot", `📘 ${book.title} by ${book.author}`);
                });
            } else {
                addMessage("bot", "I couldn't find any recommendations based on your current reading history.");
            }
        })
        .catch(error => {
            console.error("Error fetching recommendations:", error);
            addMessage("bot", "Sorry, there was an error retrieving your recommendations.");
        });
  }
  
  // Example to trigger fetching with MID from your system
  fetchRecommendations("MID42311602");
  