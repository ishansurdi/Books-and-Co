use library;
select*from userstatistics;
select*from transactions;

CREATE TABLE issue_books (
    MID VARCHAR(50) NOT NULL,
    AID VARCHAR(50) NOT NULL,
    reference_number VARCHAR(50) NOT NULL PRIMARY KEY,
    ISBN VARCHAR(20) NOT NULL,
    name_of_book VARCHAR(255) NOT NULL,
    date_of_book_issue DATETIME NOT NULL,
    time_of_book_issue TIME NOT NULL,
    expected_date_of_return DATE NOT NULL,
    actual_date_of_return DATE,
    fine_imposed ENUM('Yes', 'No') DEFAULT 'No',
    fine_amount DECIMAL(10, 2) DEFAULT 0.00,
    FOREIGN KEY (MID) REFERENCES membership_form(membership_id), -- Ensure you have a members table with MID
    FOREIGN KEY (AID) REFERENCES admins(AID)   -- Ensure you have an admins table with AID
);
select*from books;
select*from inventory;
DELETE FROM books WHERE ISBN='978-0140283334';









CREATE TABLE books_availability (
    ISBN VARCHAR(20) NOT NULL,
    library_id VARCHAR(50) NOT NULL,
    name_of_book VARCHAR(255) NOT NULL,
    quantities INT NOT NULL,
    borrowed INT DEFAULT 0,
    returned INT DEFAULT 0,
    status ENUM('Available', 'Unavailable', 'Reserved') DEFAULT 'Available',
    PRIMARY KEY (ISBN, library_id),
    FOREIGN KEY (ISBN) REFERENCES books(ISBN), -- Ensure you have a books table with ISBN
    FOREIGN KEY (library_id) REFERENCES books(library_id) -- Ensure you have a libraries table with library_id
);




select*from inventory;

DELIMITER //

CREATE TRIGGER after_books_insert
AFTER INSERT ON books
FOR EACH ROW
BEGIN
    INSERT INTO books_availability (ISBN, Library_ID, Name_of_Book, Quantities)
    VALUES (NEW.ISBN, NEW.Library_ID, NEW.Name_of_Book, 
            (SELECT Quantity FROM Inventory WHERE ISBN = NEW.ISBN AND Library_ID = NEW.Library_ID))
    ON DUPLICATE KEY UPDATE
        Name_of_Book = VALUES(Name_of_Book),
        Quantities = (SELECT Quantity FROM Inventory WHERE ISBN = VALUES(ISBN) AND Library_ID = VALUES(Library_ID));
END //

DELIMITER ;



DELIMITER //

CREATE TRIGGER after_books_update
AFTER UPDATE ON books
FOR EACH ROW
BEGIN
    INSERT INTO books_availability (ISBN, Library_ID, Name_of_Book, Quantities)
    VALUES (NEW.ISBN, NEW.Library_ID, NEW.Name_of_Book, 
            (SELECT Quantity FROM Inventory WHERE ISBN = NEW.ISBN AND Library_ID = NEW.Library_ID))
    ON DUPLICATE KEY UPDATE
        Name_of_Book = VALUES(Name_of_Book),
        Quantities = (SELECT Quantity FROM Inventory WHERE ISBN = VALUES(ISBN) AND Library_ID = VALUES(Library_ID));
END //

DELIMITER ;

select * from books_availability;

DELIMITER //

CREATE TRIGGER before_books_availability_update
BEFORE UPDATE ON books_availability
FOR EACH ROW
BEGIN
    IF NEW.Quantities = 0 THEN
        SET NEW.Status = 'Unavailable';
    ELSE
        SET NEW.Status = 'Available'; -- Adjust this logic if needed
    END IF;
END //

DELIMITER ;

select*from books_availability;
select * from admins;
select * from inventory;



DELIMITER $$

CREATE TRIGGER update_books_availability
AFTER INSERT ON inventory
FOR EACH ROW
BEGIN
    -- Update the books_availability quantities whenever a new inventory record is added
    IF EXISTS (SELECT 1 FROM books_availability WHERE ISBN = NEW.ISBN AND library_id = NEW.library_id) THEN
        UPDATE books_availability
        SET quantities = (SELECT SUM(quantity) FROM inventory WHERE ISBN = NEW.ISBN AND library_id = NEW.library_id)
        WHERE ISBN = NEW.ISBN AND library_id = NEW.library_id;
    ELSE
        -- Insert a new record in case it doesn't exist
        INSERT INTO books_availability (ISBN, library_id, name_of_book, quantities, status)
        VALUES (NEW.ISBN, NEW.library_id, (SELECT name FROM books WHERE ISBN = NEW.ISBN), NEW.quantity, 'Available');
    END IF;
END$$

DELIMITER ;
select * from login_logs;
select * from books_availability;
select * from issue_books;
select * from userstatistics;
select * from books;

select * from membership_form;
select * from issue_books;
select*from userstatistics;
select*from transactions;


truncate issue_books;
update books_availability set  quantities =  10  where ISBN = "978-0439708180";
update books_availability set  borrowed =  0  where ISBN = "978-0439708180";
update userstatistics set  Books_Borrowed =  0  where MID = "MID94713176";

select*from transactions;

select * from issue_books;
ALTER TABLE issue_books
ADD fine_paid VARCHAR(3),  -- assuming 'Yes' or 'No'
ADD date_of_fine_paid DATE, 
ADD FTID INT;  -- or the appropriate data type for FTID


select * from userstatistics;
select * from membership_form;

ALTER TABLE issue_books
MODIFY time_of_book_issue TIME DEFAULT CURRENT_TIME;