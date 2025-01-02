# Books & Co

Books & Co is a comprehensive library management system designed to facilitate the management of books, users, and various library operations. This project is developed as a Project Based Learning initiative.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Additional Dependencies](#additional-dependencies) 
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Main Files and Their Functionality](#main-files-and-their-functionality)
- [Tables](#tables)
- [Updating Passwords](#updating-passwords)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## Features

- **Admin Module**: Manage books, users, and library operations.
- **User Dashboard**: User-friendly interface for library members.
- **AI Chatbot**: Provides assistance and answers queries.
- **PDF Generation**: Generate PDF documents for various reports.
- **Email System**: Send emails for notifications and updates.
- **Responsive Design**: Optimized for both desktop and mobile devices.

## Installation

### Prerequisites

- PHP 7.4 or higher
- Composer
- MySQL
- Web server (e.g., Apache, Nginx)

### Steps

1. Clone the repository:
    ```sh
    git clone https://github.com/ishansurdi/Books-and-Co.git
    cd Books-and-Co
    ```

2. Install dependencies:
    ```sh
    composer install
    ```

3. Set up the database:
    - Import the SQL file `Books_and_Co.sql` into your MySQL database.
    - Update the database configuration in `database-connection/db_connection.php`.

4. Configure the web server to point to the project directory.

## Additional Dependencies
> **Note:** These steps outline the ideal setup process and may vary depending on your operating system and environment. It is strongly recommended to refer to the official documentation and download guidelines for compatibility and best practices.

1. **FPDF (fpdf186):** FPDF is used for generating PDFs. If the library is not present in the project, follow these steps:
- Download the FPDF library:
    * Visit the official website: [http://www.fpdf.org](http://www.fpdf.org).
    * Download the latest version of the FPDF library.

- Extract the downloaded archive.

- Copy the extracted folder into the project directory, usually under `fpdf186/`.
2. **PHPMailer:** PHPMailer is used for sending emails. If the library is not working, follow these steps:
  - Install PHPMailer using Composer:
    ```sh
    composer require phpmailer/phpmailer
    ```

    2. Ensure the `vendor/` directory is present in the project and includes PHPMailer.

---
  

## Usage

### Running the Project

- Start your web server.
- Open your browser and navigate to the project URL (e.g., `http://localhost/Books-and-Co`).

### Admin Module

- Access the admin module at `http://localhost/Books-and-Co/adminmodule/adminlogin`.
- Use the admin credentials to log in and manage the library.

### User Dashboard

- Users can log in and access their dashboard at `http://localhost/Books-and-Co/userdashboardmodule`.

### AI Chatbot

- The AI chatbot can be accessed on the homepage to assist users with their queries.

## Project Structure

```plaintext
PBL_SourceCode/
├── adminmodule/
│   ├── adminlogin/
│   │   ├── index.php          # Admin login page
│   ├── adminmodule.php        # Main admin portal file
│   ├── adminportal.css        # CSS for admin portal styling
│   ├── css/
│   │   ├── admin_styles.css   # Additional CSS for admin module
│   ├── issue_book/
│   │   ├── issue_book_process.php  # Handles book issuing process
│   ├── pages/
│   │   ├── manage_books.php   # Page for managing books
│   │   ├── manage_users.php   # Page for managing users
│   ├── return_book/
│   │   ├── return_book_update.php  # Handles book return process
├── ai/
│   ├── aicss.css              # CSS for AI chatbot styling
│   ├── chatbot.js             # JavaScript file for AI chatbot functionality
│   ├── index.html             # AI chatbot interface
├── css/
│   ├── ai_css.css             # CSS for AI-related pages
│   ├── credits.css            # CSS for credits page
│   ├── dashboard.css          # CSS for user dashboard
│   ├── loginpage.css          # CSS for login page
│   ├── membership.css         # CSS for membership form
│   ├── slider.css             # CSS for image slider
│   ├── styles.css             # General styles for the project
│   ├── styles2.css            # Additional styles for the project
│   ├── typing.css             # CSS for typing effect
├── database-connection/
│   ├── db_connection.php      # Database connection configuration
├── farewell_email/
│   ├── createpdf.php          # Generates PDF for farewell emails
│   ├── send_email.php         # Sends farewell emails
├── fpdf186/                   # Library for generating PDFs
├── images/                    # Directory for storing images
├── index.html                 # Main entry point for the application
├── js/
│   ├── slider.js              # JavaScript for image slider
│   ├── typing.js              # JavaScript for typing effect
│   ├── userdashboardtyping.js # JavaScript for user dashboard typing effect
├── mailsystem/
│   ├── index.php              # Main email system file
│   ├── test_email.php         # Test email functionality
├── pages/
│   ├── credits.html           # Credits page
│   ├── login/
│   │   ├── login.php          # User login page
│   ├── membershipform/
│   │   ├── membershipform.html # Membership form page
├── PBL_SourceCode/            # Source code for the project
├── testcodes/                 # Directory for test codes
├── userdashboardmodule/
│   ├── userdashboard.php      # User dashboard interface
│   ├── ai/
│   │   ├── aicss.css          # CSS for user AI chatbot
│   │   ├── chatbot.js         # JavaScript for user AI chatbot
│   │   ├── userai_index.html  # User AI chatbot interface
│   ├── Zodiak/
│   │   ├── Fonts/
│   │   │   ├── WEB/
│   │   │   │   ├── README.md  # Readme for Zodiak fonts
├── vendor/                    # Composer dependencies
├── Books_and_Co-2.sql         # SQL file for setting up the database
├── Books_and_Co.sql           # SQL file for setting up the database
├── composer.json              # Composer configuration file
├── composer.lock              # Composer lock file
├── LICENSE.md                 # License file
├── readme.md                  # Project readme file
```

> **Note**: Quiz functionality is not implemented. All code files related to the quiz can be deleted or updated as per your preference.

## Main Files and Their Functionality

### Admin Module
- `adminmodule/adminmodule.php`: Main admin portal file.
- `adminmodule/adminlogin/index.php`: Admin login page.
- `adminmodule/issue_book/issue_book_process.php`: Handles book issuing process.
- `adminmodule/return_book/return_book_update.php`: Handles book return process.

### AI Chatbot
- `ai/chatbot.js`: JavaScript file for AI chatbot functionality.
- `ai/index.html`: AI chatbot interface.

### Database Connection
- `database-connection/db_connection.php`: Database connection configuration.

### Email System
- `mailsystem/index.php`: Main email system file.
- `mailsystem/test_email.php`: Test email functionality.

### User Dashboard
- `userdashboardmodule/userdashboard.php`: User dashboard interface.

### Main Entry Point
- `index.html`: Main entry point for the application, through which all other sections can be navigated.

## Tables
# Database Structure

## Table: admins

| Column Name    | Type          | Key |
|----------------|---------------|-----|
| admin_name     | varchar(100)  |     |
| mobile_number  | varchar(15)   |     |
| email          | varchar(100)  | UNI |
| aid            | varchar(20)   | PRI |
| admin_password | varchar(255)  |     |

No foreign key relations.

---

## Table: announcements

| Column Name        | Type              | Key |
|--------------------|-------------------|-----|
| id                 | int               | PRI |
| announcement_type  | varchar(50)       |     |
| subject            | varchar(255)      |     |
| body               | text              |     |
| sequence_number    | int               |     |
| send_to            | enum('all','individual') |     |
| timestamp          | timestamp         |     |
| status             | enum('Sent','Not Sent') |     |

No foreign key relations.

---

## Table: books

| Column Name              | Type          | Key |
|--------------------------|---------------|-----|
| ISBN                    | varchar(20)   | PRI |
| Library_id              | varchar(20)   | UNI |
| Name_of_Book            | varchar(255)  |     |
| Author                  | varchar(255)  |     |
| Publisher               | varchar(255)  |     |
| Journal_Magazine_Other  | varchar(255)  |     |
| Genre                   | varchar(100)  |     |
| Suitable_for            | varchar(100)  |     |
| Brief_Details           | text          |     |
| Who_Should_Read         | text          |     |
| For_What_You_Should_Read| text          |     |
| Publication_Year        | int           |     |
| Edition                 | varchar(50)   |     |
| Language                | varchar(50)   |     |
| Pages                   | int           |     |
| Cover_Image_URL         | varchar(255)  |     |
| Added_Date              | timestamp     |     |

No foreign key relations.

---

## Table: books_availability

| Column Name    | Type              | Key |
|----------------|-------------------|-----|
| ISBN           | varchar(20)       | PRI |
| library_id     | varchar(50)       | PRI |
| name_of_book   | varchar(255)      |     |
| quantities     | int               |     |
| borrowed       | int               |     |
| returned       | int               |     |
| status         | enum('Available','Unavailable','Reserved') |     |

**Foreign Key Relation:**
- `ISBN` → `books.ISBN`
- `library_id` → `books.Library_id`

---

## Table: inventory

| Column Name    | Type              | Key |
|----------------|-------------------|-----|
| Inventory_ID   | int               | PRI |
| ISBN           | varchar(20)       | MUL |
| Library_ID     | varchar(20)       | MUL |
| Quantity       | int               |     |
| Location       | varchar(100)      |     |
| Date_Added     | date              |     |
| Status         | enum('Available','Checked Out','Reserved') |     |
| Book_Condition | enum('New','Good','Fair','Poor') |     |
| Last_Updated   | timestamp         |     |

**Foreign Key Relation:**
- `ISBN` → `books.ISBN`
- `Library_ID` → `books.Library_id`

---

## Table: issue_books

| Column Name         | Type              | Key |
|---------------------|-------------------|-----|
| MID                 | varchar(50)       | MUL |
| AID                 | varchar(50)       | MUL |
| reference_number    | varchar(50)       | PRI |
| ISBN                | varchar(20)       |     |
| name_of_book        | varchar(255)      |     |
| date_of_book_issue  | datetime          |     |
| time_of_book_issue  | time              |     |
| expected_date_of_return | date          |     |
| actual_date_of_return | date            |     |
| fine_imposed        | enum('Yes','No')  |     |
| fine_amount         | decimal(10,2)     |     |
| fine_paid           | varchar(3)        |     |
| date_of_fine_paid   | date              |     |
| FTID                | int               |     |

**Foreign Key Relation:**
- `MID` → `member_details.MID`
- `AID` → `admins.aid`
- `ISBN` → `books.ISBN`

---

## Table: login_logs

| Column Name    | Type          | Key |
|----------------|---------------|-----|
| id             | int           | PRI |
| aid            | varchar(50)   | MUL |
| login_time     | datetime      |     |
| logout_time    | datetime      |     |
| session_status | enum('active','ended') |     |

**Foreign Key Relation:**
- `aid` → `admins.aid`

---

## Table: member_details

| Column Name        | Type          | Key |
|--------------------|---------------|-----|
| MID                | varchar(50)   |     |
| first_name         | varchar(50)   |     |
| last_name          | varchar(50)   |     |
| email              | varchar(50)   |     |
| account_status     | varchar(50)   |     |
| Date_of_update     | date          |     |
| Time_of_update     | time          |     |
| last_date_of_appeal| date          |     |
| appealed           | varchar(50)   |     |
| appealed_on        | varchar(50)   |     |

No foreign key relations.

---

## Table: membership_form

| Column Name          | Type          | Key |
|----------------------|---------------|-----|
| first_name           | varchar(50)   |     |
| middle_name          | varchar(50)   |     |
| last_name            | varchar(50)   |     |
| gender               | enum('Male','Female','Other') |     |
| mobile_number        | varchar(15)   |     |
| email                | varchar(100)  |     |
| dob                  | date          |     |
| age                  | int           |     |
| occupation           | enum('Student','Professional','Business','Retired','Other') |     |
| residential_address  | text          |     |
| membership_plan      | enum('Monthly','Half-Yearly','Yearly') |     |
| membership_plan_id   | varchar(10)   |     |
| membership_amount    | decimal(10,2) |     |
| membership_id        | varchar(12)   | PRI |
| submission_date      | date          |     |
| submission_day       | varchar(10)   |     |
| submission_time      | time          |     |
| ip_address           | varchar(45)   |     |
| created_at           | timestamp     |     |
| updated_at           | timestamp     |     |
| mail_sent            | enum('no','yes') |     |
| mail_sent_date       | datetime      |     |
| mail_sent_status     | enum('sent','error') |     |
| temp_password        | varchar(255)  |     |

No foreign key relations.

---

## Table: transactions

| Column Name             | Type          | Key |
|-------------------------|---------------|-----|
| MID                    | varchar(50)   | MUL |
| TID                    | bigint        | PRI |
| Amount                 | decimal(10,2) |     |
| Plan                   | varchar(50)   |     |
| Method                 | varchar(50)   |     |
| Status                 | varchar(20)   |     |
| Date_of_Transaction    | date          |     |
| Time_of_Transaction    | time          |     |
| Next_Date_of_Transaction| date          |     |
| Account_Status         | enum('Active','Inactive','Suspended') |     |
| Penalty_for_Late_Payment| decimal(10,2) |     |
| Penalty_Paid           | enum('YES','NO') |     |
| Date_of_Penalty_Payment| date          |     |
| Time_of_Penalty_Payment| time          |     |
| PTID                   | int           |     |

**Foreign Key Relation:**
- `MID` → `member_details.MID`

---

## Table: userdata

| Column Name    | Type          | Key |
|----------------|---------------|-----|
| username       | varchar(50)   |     |
| email          | varchar(50)   |     |
| userpassword   | varchar(50)   |     |

No foreign key relations.

---

## Table: userstatistics

| Column Name      | Type          | Key |
|------------------|---------------|-----|
| MID             | varchar(255)  | PRI |
| Books_Borrowed  | int           |     |
| Pending_Requests| int           |     |
| Penalties       | decimal(10,2) |     |

No foreign key relations.
> **Note:** Certain columns in some tables may not be utilized. Some tables might have been missed; kindly review the code for completeness.

## Updating Passwords

### Database Password
Update the database password in the following file:

```php
// database-connection/db_connection.php
$servername = "localhost";
$username = "root";
$password = "your_database_password"; // Update this line
$dbname = "library";
```

### Email Password
Update the email password in the following files:

- `mailsystem/index.php`:

```php
$password = 'your_email_password'; // Update this line
```

- `farewell_email/createpdf.php`:

```php
$password = 'your_email_password'; // Update this line
```

## Contributing

We welcome contributions from the community. To contribute:

1. Fork the repository.
2. Create a new branch:
    ```sh
    git checkout -b feature-branch
    ```
3. Make your changes.
4. Commit your changes:
    ```sh
    git commit -m 'Add some feature'
    ```
5. Push to the branch:
    ```sh
    git push origin feature-branch
    ```
6. Open a pull request.

## License

This project is licensed under the GNU Affero General Public License v3.0. See the `LICENSE.md` file for details.

## Contact

For any inquiries or issues, please contact:

- **Project Lead**: Ishan Surdi
- **Email**: [ishansurdi2105@gmail.com](ishansurdi2105@gmail.com)


---
Thank you for exploring this project. We look forward to you feedback and contributions!

