-- Sample Data for University Library Management System

USE unilibrary;

-- Insert Categories
INSERT INTO categories (name, description) VALUES
('Science', 'Science and Natural Philosophy books'),
('Technology', 'Information Technology and Computer Science'),
('Literature', 'Literary works and classics'),
('History', 'Historical texts and records'),
('Philosophy', 'Philosophical works and theories'),
('Mathematics', 'Mathematics and Quantitative Sciences');

-- Insert Sample Users
INSERT INTO users (first_name, last_name, email, password, role, status) VALUES
('John', 'Doe', 'john@university.edu', '$2y$10$YkQCmyJ5tMDGNn0qDmNhC.8NZsKhE7ByYYnQqpZ3pK0qJ8ZZqDqGm', 'student', 'active'),
('Jane', 'Smith', 'jane@university.edu', '$2y$10$YkQCmyJ5tMDGNn0qDmNhC.8NZsKhE7ByYYnQqpZ3pK0qJ8ZZqDqGm', 'student', 'active'),
('Admin', 'User', 'admin@university.edu', '$2y$10$YkQCmyJ5tMDGNn0qDmNhC.8NZsKhE7ByYYnQqpZ3pK0qJ8ZZqDqGm', 'admin', 'active');

-- Insert Sample Books
INSERT INTO books (title, author, isbn, description, category_id, publication_year, total_copies, available_copies) VALUES
('Physics for Scientists and Engineers', 'Raymond Serway', '978-0078661005', 'A comprehensive physics textbook for engineering students.', 1, 2013, 6, 6),
('Python Crash Course', 'Eric Matthes', '978-1593279288', 'A hands-on, practical introduction to programming in Python.', 2, 2019, 6, 6),
('Design Patterns: Elements of Reusable Object-Oriented Software', 'Erich Gamma', '978-0201633610', 'Essential patterns for software design and architecture.', 2, 1994, 4, 4),
('Clean Code: A Handbook of Agile Software Craftsmanship', 'Robert C. Martin', '978-0132350884', 'A guide to writing clean, maintainable code.', 2, 2008, 3, 3),
('Introduction to Algorithms', 'Thomas H. Cormen', '978-0262033848', 'Comprehensive introduction to algorithm design and analysis.', 2, 2009, 5, 5),
('Calculus: Early Transcendentals', 'James Stewart', '978-1285741550', 'A rigorous introduction to calculus with applications.', 6, 2015, 8, 8),
('1984', 'George Orwell', '978-0451524935', 'A dystopian novel exploring themes of power and society.', 3, 1949, 7, 7),
('Organic Chemistry', 'Paula Yurkanis Bruice', '978-0134042038', 'A comprehensive organic chemistry textbook.', 1, 2016, 5, 5);