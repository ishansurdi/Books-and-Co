// chatbot.js

// Define responses and keywords
const responses = {
    "hours": "Our library is open from 9 AM to 8 PM, Monday through Saturday.",
    "renew": "You can renew your book online or by visiting us in person.",
    "membership": "We offer monthly, quarterly, and annual memberships with various benefits.",
    "location": "Our library is located at 123 Book St., Booktown.",
    "late fees": "Late fees are charged at ₹2 per day after the due date.",
    "return": "You can return books during library hours at the main desk."
  };
  
  // Greeting message and keywords array for suggestions
  const keywords = Object.keys(responses);
  let chatInitialized = false;
  
  // Toggle chatbox visibility
  document.getElementById("chat-button").addEventListener("click", () => {
    const chatbox = document.getElementById("chatbox-container");
    chatbox.style.display = chatbox.style.display === "none" ? "flex" : "none";
    
    // Show initial message if chat is opened for the first time
    if (!chatInitialized) {
      addMessage("bot", "Hey, How can I help you?");
      chatInitialized = true;
    }
  });
  
  // Close button functionality
  document.getElementById("close-button").addEventListener("click", () => {
    document.getElementById("chatbox-container").style.display = "none";
  });
  
  // Handle user input and display response
  document.getElementById("send-button").addEventListener("click", handleUserInput);
  document.getElementById("chat-input").addEventListener("input", handleSuggestions);
  
  // Handle user input and show response
  function handleUserInput() {
    const chatInput = document.getElementById("chat-input");
    const userMessage = chatInput.value.trim();
    
    if (userMessage) {
      addMessage("user", userMessage);
      chatInput.value = "";
      const response = getResponse(userMessage);
      setTimeout(() => addMessage("bot", response), 500);
    }
  }
  
  // Display matching suggestions
  function handleSuggestions() {
    const userInput = document.getElementById("chat-input").value.toLowerCase();
    const suggestions = keywords.filter(keyword => userInput.includes(keyword) || keyword.includes(userInput));
    
    const suggestionsList = document.getElementById("suggestions-list");
    suggestionsList.innerHTML = ""; // Clear old suggestions
    
    suggestions.forEach(suggestion => {
      const suggestionItem = document.createElement("li");
      suggestionItem.textContent = suggestion.charAt(0).toUpperCase() + suggestion.slice(1);
      suggestionItem.addEventListener("click", () => {
        document.getElementById("chat-input").value = suggestion;
        handleUserInput();
      });
      suggestionsList.appendChild(suggestionItem);
    });
  }
  
  // Add message to chatbox content with "You" and "AI" labels
  function addMessage(sender, message) {
    const chatContent = document.getElementById("chatbox-content");
    
    // Label
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
  
  // Get response based on keywords in user message
  function getResponse(message) {
    for (const keyword in responses) {
      if (message.toLowerCase().includes(keyword)) {
        return responses[keyword];
      }
    }
    return "I'm sorry, I don't have an answer for that. Try asking another question! or contact support@booksandco.com";
  }
  