use library;
CREATE TABLE membership_form (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    age INT , -- Auto-calculates age
    occupation ENUM('Student', 'Professional', 'Business', 'Retired', 'Other') NOT NULL,
    residential_address TEXT NOT NULL,
    membership_plan ENUM('Monthly', 'Half-Yearly', 'Yearly') NOT NULL,
    membership_plan_id VARCHAR(10) NOT NULL,
    membership_amount DECIMAL(10, 2) NOT NULL,
    membership_id VARCHAR(12) UNIQUE NOT NULL,
    submission_date DATE,
    submission_day VARCHAR(10) ,
    submission_time TIME ,
    ip_address VARCHAR(45), -- Supports IPv4 and IPv6
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE membership_form 
ADD COLUMN temp_password VARCHAR(255) NOT NULL,
ADD COLUMN mail_sent ENUM('no', 'yes') DEFAULT 'no',
ADD COLUMN mail_sent_date DATETIME DEFAULT NULL,
ADD COLUMN mail_sent_status ENUM('sent', 'error') DEFAULT NULL;

select * from membership_form;
ALTER TABLE membership_form DROP COLUMN temp_password;

ALTER TABLE membership_form ADD COLUMN temp_password VARCHAR(255) NULL;




CREATE TABLE Transactions (
    MID INT NOT NULL,
    TID INT NOT NULL AUTO_INCREMENT,  -- Auto increment for unique transaction IDs
    Amount DECIMAL(10, 2) NOT NULL,   -- Assuming monetary amounts with two decimal places
    Plan VARCHAR(50),
    Method VARCHAR(50),
    Status VARCHAR(20),
    Date_of_Transaction DATE NOT NULL,
    Time_of_Transaction TIME NOT NULL,
    Next_Date_of_Transaction DATE,
    Account_Status ENUM('Active', 'Inactive') DEFAULT 'Active',
    Penalty_for_Late_Payment DECIMAL(10, 2) DEFAULT 0,  -- Store the penalty amount
    Penalty_Paid ENUM('YES', 'NO') DEFAULT 'NA',
    Date_of_Penalty_Payment DATE,
    Time_of_Penalty_Payment TIME,
    PTID INT,  -- Penalty transaction ID
    PRIMARY KEY (TID)
);
SET SQL_SAFE_UPDATES = 0;
UPDATE Transactions
SET Penalty_for_Late_Payment = 
    CASE 
        WHEN CURDATE() > Next_Date_of_Transaction THEN
            DATEDIFF(CURDATE(), Next_Date_of_Transaction) * 10
        ELSE 0
    END
WHERE Penalty_Paid = 'NO';
SET SQL_SAFE_UPDATES = 1;

select * from Transactions;
select * from membership_form;
select * from membership_form where membership_id = 'MID46226277';


TRUNCATE TABLE membership_form;
TRUNCATE TABLE Transactions;

ALTER TABLE membership_form
ADD PRIMARY KEY (membership_id);

ALTER TABLE Transactions
ADD CONSTRAINT fk_membership_id
FOREIGN KEY (MID) REFERENCES membership_form(membership_id)
ON DELETE CASCADE;
SELECT m.membership_id, t.Plan, t.Amount, t.Next_Date_of_Transaction
FROM membership_form m
JOIN Transactions t ON m.membership_id = t.MID
WHERE m.email = 'workpallavisurdi@gmail.com';


SHOW INDEX FROM membership_form WHERE Column_name = 'id';
ALTER TABLE membership_form
DROP COLUMN id;


CREATE TABLE UserStatistics (
    MID VARCHAR(255) NOT NULL,
    Books_Borrowed INT DEFAULT 0,
    Pending_Requests INT DEFAULT 0,
    Penalties DECIMAL(10, 2) DEFAULT 0.00,
    PRIMARY KEY (MID),
    FOREIGN KEY (MID) REFERENCES membership_form(membership_id)
);

INSERT INTO UserStatistics (MID)
SELECT membership_id
FROM membership_form;

select * from UserStatistics;
UPDATE UserStatistics
SET Books_Borrowed = 1
WHERE MID = 'MID46226277';


DELIMITER //

CREATE TRIGGER after_membership_form_insert
AFTER INSERT ON membership_form
FOR EACH ROW
BEGIN
    -- Insert the new MID into UserStatistics with default values
    INSERT INTO UserStatistics (MID)
    VALUES (NEW.membership_id);
END;

//

DELIMITER ;



CREATE TABLE Books (
    ISBN VARCHAR(20) PRIMARY KEY,            -- International Standard Book Number
    Library_id VARCHAR(20) UNIQUE NOT NULL,  -- Unique identifier for books in the library
    Name_of_Book VARCHAR(255) NOT NULL,      -- Title of the book
    Author VARCHAR(255) NOT NULL,            -- Author of the book
    Publisher VARCHAR(255),                  -- Publisher of the book
    Journal_Magazine_Other VARCHAR(255),     -- Specifies if it's a journal, magazine, etc.
    Genre VARCHAR(100),                      -- Genre of the book
    Suitable_for VARCHAR(100),              -- Age group or audience the book is suitable for
    Brief_Details TEXT,                      -- Brief details or summary of the book
    Who_Should_Read TEXT,                    -- Target audience or who should read this book
    For_What_You_Should_Read TEXT,           -- Purpose or what the book is for
    Publication_Year INT,                    -- Year of publication
    Edition VARCHAR(50),                     -- Edition of the book
    Language VARCHAR(50),                    -- Language of the book
    Pages INT,                               -- Number of pages
    Cover_Image_URL VARCHAR(255),            -- URL to the book's cover image
    Added_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date when the book was added to the system
);

INSERT INTO Books (ISBN, Library_id, Name_of_Book, Author, Publisher, Journal_Magazine_Other, Genre, Suitable_for, Brief_Details, Who_Should_Read, For_What_You_Should_Read, Publication_Year, Edition, Language, Pages, Cover_Image_URL)
VALUES
('978-0439708180', 'LIB001', 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'Scholastic', 'Book', 'Fantasy', '12 and up', 'A young wizard discovers his magical heritage at Hogwarts School.', 'Fans of fantasy and adventure.', 'For a magical and thrilling adventure.', 1997, '1st Edition', 'English', 309, 'https://example.com/harry-potter-cover.jpg'),

('978-0061120084', 'LIB002', 'To Kill a Mockingbird', 'Harper Lee', 'J.B. Lippincott & Co.', 'Book', 'Fiction', '14 and up', 'A novel about racial injustice in the Deep South during the 1930s.', 'Readers interested in social issues and classic literature.', 'To understand social justice and morality.', 1960, '1st Edition', 'English', 281, 'https://example.com/to-kill-a-mockingbird-cover.jpg'),

('978-0451524935', 'LIB003', '1984', 'George Orwell', 'Signet Classics', 'Book', 'Dystopian', '16 and up', 'A chilling portrayal of a totalitarian regime and its impact on individual freedom.', 'Fans of dystopian fiction and political commentary.', 'To explore themes of surveillance and government control.', 1949, '1st Edition', 'English', 328, 'https://example.com/1984-cover.jpg'),

('978-0140283334', 'LIB004', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Penguin Books', 'Book', 'Classic', '16 and up', 'A story of wealth, excess, and the American Dream in the 1920s.', 'Readers interested in classic American literature.', 'To delve into themes of decadence and disillusionment.', 1925, '1st Edition', 'English', 180, 'https://example.com/great-gatsby-cover.jpg'),

('978-0062315007', 'LIB005', 'The Alchemist', 'Paulo Coelho', 'HarperOne', 'Book', 'Adventure', '12 and up', 'A philosophical novel about a shepherd’s quest to realize his personal legend.', 'Readers seeking inspiration and spiritual growth.', 'To find motivation for personal dreams and goals.', 1988, '1st Edition', 'English', 208, 'https://example.com/the-alchemist-cover.jpg'),

('978-0385472579', 'LIB006', 'The Da Vinci Code', 'Dan Brown', 'Doubleday', 'Book', 'Mystery', '16 and up', 'A mystery thriller involving a secret society and hidden codes.', 'Fans of thrilling mysteries and conspiracies.', 'For an engaging and suspenseful read.', 2003, '1st Edition', 'English', 689, 'https://example.com/da-vinci-code-cover.jpg'),

('978-0140449136', 'LIB007', 'Crime and Punishment', 'Fyodor Dostoevsky', 'Penguin Classics', 'Book', 'Classic', '18 and up', 'A psychological drama about a young man who commits a murder and struggles with guilt.', 'Readers of psychological and philosophical novels.', 'To explore themes of morality and redemption.', 1866, '1st Edition', 'English', 430, 'https://example.com/crime-and-punishment-cover.jpg'),

('978-0316769488', 'LIB008', 'The Catcher in the Rye', 'J.D. Salinger', 'Little, Brown and Company', 'Book', 'Fiction', '14 and up', 'A novel about a disenchanted teenager’s experiences in New York City.', 'Those interested in adolescent psychology and classic literature.', 'To understand teenage angst and rebellion.', 1951, '1st Edition', 'English', 277, 'https://example.com/catcher-in-the-rye-cover.jpg'),

('978-0385350448', 'LIB009', 'Gone Girl', 'Gillian Flynn', 'Crown Publishing Group', 'Book', 'Thriller', '18 and up', 'A suspenseful novel about a woman who goes missing and the secrets that unravel.', 'Fans of psychological thrillers and dark fiction.', 'For a gripping and unpredictable story.', 2012, '1st Edition', 'English', 432, 'https://example.com/gone-girl-cover.jpg'),

('978-0374533557', 'LIB010', 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'Harper Perennial', 'Book', 'Non-Fiction', '16 and up', 'An exploration of the history and impact of Homo sapiens on the world.', 'Readers interested in history and anthropology.', 'To gain insight into human evolution and societal development.', 2011, '1st Edition', 'English', 464, 'https://example.com/sapiens-cover.jpg');

select * from Books;

CREATE TABLE Inventory (
    Inventory_ID INT AUTO_INCREMENT PRIMARY KEY,
    ISBN VARCHAR(20) NOT NULL,
    Library_ID VARCHAR(20) NOT NULL,
    Quantity INT NOT NULL,
    Location VARCHAR(100),
    Date_Added DATE,
    Status ENUM('Available', 'Checked Out', 'Reserved') DEFAULT 'Available',
    Book_Condition ENUM('New', 'Good', 'Fair', 'Poor') DEFAULT 'Good',
    Last_Updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ISBN) REFERENCES Books(ISBN),
    FOREIGN KEY (Library_ID) REFERENCES Books(Library_id)
);


INSERT INTO Inventory (ISBN, Library_ID, Quantity, Location, Date_Added, Status, Book_Condition)
VALUES
('978-0439708180', 'LIB001', 10, 'Shelf A1', '2024-09-01', 'Available', 'New'),
('978-0061120084', 'LIB002', 5, 'Shelf B2', '2024-09-01', 'Available', 'Good'),
('978-0451524935', 'LIB003', 8, 'Shelf C3', '2024-09-01', 'Available', 'Good'),
('978-0140283334', 'LIB004', 6, 'Shelf D4', '2024-09-01', 'Available', 'New'),
('978-0062315007', 'LIB005', 12, 'Shelf E5', '2024-09-01', 'Available', 'New'),
('978-0385472579', 'LIB006', 7, 'Shelf F6', '2024-09-01', 'Available', 'Good'),
('978-0140449136', 'LIB007', 4, 'Shelf G7', '2024-09-01', 'Available', 'Fair'),
('978-0316769488', 'LIB008', 9, 'Shelf H8', '2024-09-01', 'Available', 'Good'),
('978-0385350448', 'LIB009', 3, 'Shelf I9', '2024-09-01', 'Available', 'New'),
('978-0374533557', 'LIB010', 11, 'Shelf J10', '2024-09-01', 'Available', 'New');
select*from Books;


CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    announcement_type VARCHAR(50),
    subject VARCHAR(255),
    body TEXT,
    sequence_number INT,
    send_to ENUM('all', 'individual'),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Sent', 'Not Sent') DEFAULT 'Not Sent'
);

select*from announcements;


create table temp_email(email varchar(50));
insert into temp_email value ("workpallavisurdi@gmail.com");

select*from temp_email;

CREATE TABLE admins (
    admin_name VARCHAR(100),
    mobile_number VARCHAR(15),
    email VARCHAR(100) UNIQUE,
    aid VARCHAR(20) PRIMARY KEY,
    admin_password VARCHAR(255)
    
);

CREATE TABLE login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aid VARCHAR(50),
    login_time DATETIME,
    logout_time DATETIME,
    session_status ENUM('active', 'ended') DEFAULT 'active',
    FOREIGN KEY (aid) REFERENCES admins(aid) -- Adjust if you have a specific foreign key
);


select * from admins;
select *from login_logs;
truncate login_logs;

select current_time();
SHOW VARIABLES LIKE 'time_zone';





-- Drop existing triggers if they exist
DROP TRIGGER IF EXISTS before_insert_login_logs;
DROP TRIGGER IF EXISTS before_update_login_logs;

DELIMITER //
CREATE TRIGGER before_insert_login_logs
BEFORE INSERT ON login_logs
FOR EACH ROW
BEGIN
    -- Convert insertion times to IST assuming server is in UTC
    SET NEW.login_time = CONVERT_TZ(NEW.login_time, '+00:00', '+05:30');
    SET NEW.logout_time = CONVERT_TZ(NEW.logout_time, '+00:00', '+05:30');
END //

-- Create trigger for UPDATE
CREATE TRIGGER before_update_login_logs
BEFORE UPDATE ON login_logs
FOR EACH ROW
BEGIN
    -- Convert update times to IST assuming server is in UTC
    SET NEW.login_time = CONVERT_TZ(NEW.login_time, '+00:00', '+05:30');
    SET NEW.logout_time = CONVERT_TZ(NEW.logout_time, '+00:00', '+05:30');
END //

DELIMITER ;
select*from login_logs;




SELECT @@global.time_zone, @@session.time_zone;

select*from membership_form;


create table member_details (MID varchar(50), first_name varchar(50), last_name varchar(50), email varchar(50), account_status varchar(50), Date_of_update date, Time_of_update time, last_date_of_appeal date, appealed varchar(50), appealed_on varchar(50));

select*from member_details;
select*from transactions;



DESCRIBE membership_form;
DESCRIBE transactions;
