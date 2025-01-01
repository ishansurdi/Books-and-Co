const books = [
    { isbn: "123456789", author: "John Doe", book: "JavaScript Essentials" },
    { isbn: "987654321", author: "Jane Smith", book: "CSS for Beginners" },
    { isbn: "112233445", author: "Alice Johnson", book: "HTML and You" },
    { isbn: "998877665", author: "Bob Brown", book: "Advanced Web Design" },
   {  isbn: "100235678", author: "S.Kumaresan", book:" Linear Algebra" },
     
];

function searchBooks() {
    const filterOption = document.getElementById('filter-option').value;
    const query = document.getElementById('search-bar').value.toLowerCase();
    const results = books.filter(book => {
        if (filterOption === "isbn") {
            return book.isbn.toLowerCase().includes(query);
        } else if (filterOption === "author") {
            return book.author.toLowerCase().includes(query);
        } else if (filterOption === "book") {
            return book.book.toLowerCase().includes(query);
        }
    });

    displayResults(results);
}

function displayResults(results) {
    const resultsList = document.getElementById('results-list');
    resultsList.innerHTML = '';

    if (results.length === 0) {
        resultsList.innerHTML = '<li>No results found</li>';
    } else {
        results.forEach(book => {
            const li = document.createElement('li');
            li.textContent = `ISBN: ${book.isbn}, Author: ${book.author}, Book: ${book.book}`;
            resultsList.appendChild(li);
        });
    }
}

