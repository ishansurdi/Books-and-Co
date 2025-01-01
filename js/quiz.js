let currentQuestionIndex = 0;
let score = 0;
let questions = []; // Array to hold quiz questions

// Fetch questions from an API or local source
async function fetchQuestions() {
    try {
        const response = await fetch('https://opentdb.com/api.php?amount=42&category=10&difficulty=hard&type=multiple'); // Replace with your question source URL
        questions = await response.json();
        showQuestion();
    } catch (error) {
        console.error('Error fetching the questions:', error);
    }
}

// Display the current question and options
function showQuestion() {
    const questionElem = document.getElementById('question');
    const optionsElem = document.getElementById('options');
    
    // Check if questions exist
    if (currentQuestionIndex >= questions.length) {
        showScore();
        return;
    }

    const currentQuestion = questions[currentQuestionIndex];

    // Ensure currentQuestion is defined
    if (!currentQuestion) {
        console.error('Current question is undefined. Index:', currentQuestionIndex);
        return;
    }

    const answers = [...currentQuestion.incorrect_answers];
    answers.splice(Math.floor(Math.random() * (answers.length + 1)), 0, currentQuestion.correct_answer); // Randomize options

    // Display question
    questionElem.innerHTML = `<h2>${currentQuestion.question}</h2>`;
    
    // Display options
    optionsElem.innerHTML = '';
    answers.forEach((answer) => {
        optionsElem.innerHTML += `<button class="option" onclick="selectAnswer('${answer}', '${currentQuestion.correct_answer}')">${answer}</button>`;
    });

    // Disable the 'Next' button initially
    document.getElementById('nextBtn').disabled = true; 
}

// Check if the selected answer is correct
function selectAnswer(selectedAnswer, correctAnswer) {
    if (selectedAnswer === correctAnswer) {
        score++;
    }

    // Disable all options after selection
    document.querySelectorAll('.option').forEach(btn => btn.disabled = true);
    
    // Enable the 'Next' button only after an answer is selected
    document.getElementById('nextBtn').disabled = false;
}

// Move to the next question or show the final score
function nextQuestion() {
    currentQuestionIndex++;
    showQuestion(); // Load the next question

    // Disable the 'Next' button again for the next question
    document.getElementById('nextBtn').disabled = true; 
}

// Display the final score
function showScore() {
    const scoreElem = document.getElementById('score');
    scoreElem.innerHTML = `Your score is: ${score} out of ${questions.length}`;
}

// Initialize the quiz on page load
window.onload = fetchQuestions;
