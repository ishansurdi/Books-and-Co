document.addEventListener("DOMContentLoaded", function () {
    const quotes = [
        "Reading is dreaming with open eyes.",
        "Books are a uniquely portable magic.",
        "A room without books is like a body without a soul.",
        "So many books, so little time.",
        "Books are a mirror to the soul.",
        "Reading gives us someplace to go when we have to stay where we are."
    ];

    const typingSpeed = 50; // Speed of typing in milliseconds
    const eraseSpeed = 30; // Speed of erasing in milliseconds
    const newQuoteDelay = 2000; // Delay before new quote starts typing

    let currentQuoteIndex = 0;
    let isTyping = true;

    function typeQuote() {
        const quote = quotes[currentQuoteIndex];
        let index = 0;
        document.getElementById('typed-text').textContent = '';

        function type() {
            if (index < quote.length) {
                document.getElementById('typed-text').textContent += quote[index];
                index++;
                setTimeout(type, typingSpeed);
            } else {
                isTyping = false;
                setTimeout(eraseQuote, newQuoteDelay);
            }
        }
        type();
    }

    function eraseQuote() {
        const quote = quotes[currentQuoteIndex];
        let index = quote.length - 1;

        function erase() {
            if (index >= 0) {
                document.getElementById('typed-text').textContent = quote.substring(0, index);
                index--;
                setTimeout(erase, eraseSpeed);
            } else {
                isTyping = true;
                currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
                setTimeout(typeQuote, newQuoteDelay);
            }
        }
        erase();
    }

    typeQuote(); // Start typing the first quote
});
