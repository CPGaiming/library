document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.querySelector('.search-container .btn-accent');
    const searchInput = document.querySelector('.search-input');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const searchTerm = searchInput.value;
            if (searchTerm) {
                window.location.href = `catalog.php?search=${encodeURIComponent(searchTerm)}`;
            }
        });
    }
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchBtn?.click();
            }
        });
    }
    const categoryPills = document.querySelectorAll('.pill');
    categoryPills.forEach(pill => {
        pill.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            if (category) {
                window.location.href = `catalog.php?category=${category}`;
            }
        });
    });
});

function reserveBook(bookId) {
    fetch('api/reserve.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ book_id: bookId })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.success ? 'Book reserved successfully!' : 'Error: ' + data.message);
    })
    .catch(error => console.error('Error:', error));
}

function borrowBook(bookId) {
    fetch('api/borrow.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ book_id: bookId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Book borrowed successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function returnBook(borrowingId) {
    if (confirm('Are you sure you want to return this book?')) {
        fetch('api/return.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ borrowing_id: borrowingId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Book returned successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}
