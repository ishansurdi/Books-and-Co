# Books & Co

Books & Co is a comprehensive library management system designed to facilitate the management of books, users, and various library operations. This project is developed as a Project Based Learning initiative.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Main Files and Their Functionality](#main-files-and-their-functionality)
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
- **LinkedIn**: [www.linkedin.com/in/ishansurdiofficial](www.linkedin.com/in/ishansurdiofficial)

---
Thank you for exploring this project. We look forward to you feedback and contributions!

