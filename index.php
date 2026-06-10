<?php
require_once 'includes/config.php';
require_once 'includes/Session.php';
require_once 'includes/Database.php';
require_once 'includes/Book.php';
require_once 'includes/Category.php';
require_once 'includes/helpers.php';

Session::start();

$book = new Book();
$category = new Category();

$featured = $book->getFeaturedBooks(8);
$categories = $category->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Your Gateway to Knowledge</title>
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/style.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="logo">
                <div class="logo-icon">📚</div>
                <span><?php echo SITE_NAME; ?></span>
            </div>
            <ul class="nav-links">
                <li><a href="catalog.php">Catalog</a></li>
            </ul>
            <div class="nav-buttons">
                <?php if (Session::isLoggedIn()): ?>
                    <span style="color: white; margin-right: 1rem;">Hello, <?php echo Session::get('first_name'); ?></span>
                    <a href="dashboard.php" class="btn btn-primary btn-small">Dashboard</a>
                    <a href="logout.php" class="btn btn-secondary btn-small">Sign Out</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-small">Sign In</a>
                    <a href="register.php" class="btn btn-accent btn-small">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">📚 <?php echo SITE_NAME; ?> - University Library Management System</div>
            <h1>Your Gateway to <span class="highlight">Knowledge</span></h1>
            <p>Search, reserve, and borrow books from our extensive university library collection. Manage your reading journey all in one place.</p>
            
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search by title, author, ISBN, or keyword...">
                <button class="btn btn-accent">Search</button>
            </div>

            <div class="category-pills">
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <button class="pill" data-category="<?php echo $cat['id']; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </button>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">📖</div>
                <div class="stat-number">1,000+</div>
                <div class="stat-label">Books Available</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number">5,000+</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📤</div>
                <div class="stat-number">200+</div>
                <div class="stat-label">Daily Borrowings</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-number">98%</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Featured Books</h2>
                    <p class="section-subtitle">Discover our latest collection</p>
                </div>
                <a href="catalog.php" class="view-all">View all →</a>
            </div>

            <div class="books-grid">
                <?php while ($book_item = $featured->fetch_assoc()): ?>
                    <div class="book-card">
                        <div class="book-cover">
                            <div class="book-cover-icon">📖</div>
                            <div class="book-badges">
                                <span class="badge badge-category"><?php echo htmlspecialchars($book_item['category_name'] ?? 'General'); ?></span>
                                <span class="badge <?php echo $book_item['available_copies'] > 0 ? 'badge-available' : 'badge-unavailable'; ?>">
                                    <?php echo $book_item['available_copies'] > 0 ? 'Available' : 'Unavailable'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title"><?php echo htmlspecialchars($book_item['title']); ?></h3>
                            <p class="book-author"><?php echo htmlspecialchars($book_item['author']); ?></p>
                            <div class="book-meta">
                                <span class="book-meta-item">📅 <?php echo $book_item['publication_year']; ?></span>
                                <span class="book-meta-item">📚 <?php echo $book_item['available_copies']; ?>/<?php echo $book_item['total_copies']; ?> copies</span>
                            </div>
                            <a href="book.php?id=<?php echo $book_item['id']; ?>" class="btn btn-primary btn-view-details">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <script src="<?php echo ASSETS_PATH; ?>js/main.js"></script>
</body>
</html>
