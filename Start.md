Staff Multipurpose Cooperative Society Portal for FCET Bichi (FCET Bichi Staff Multipurpose Society)
Overview
This document provides detailed instructions for developing a multi-role cooperative society portal using PHP and MySQL. The application will serve Superadmin, Admin, and Member roles, with each role having distinct permissions and functionalities. The portal will emphasize enhanced security, a responsive design for mobile, tablet, and desktop devices, and robust user features.

Table of Contents
Technology Stack & Environment Setup
Project Architecture & Directory Structure
Database Design
User Roles & Permissions
Authentication & Registration
Enhanced Security Measures
Notifications & Alerts
Member Features
Admin & Superadmin Features
Landing Page Enhancements
Responsive Design & Cross-Device Compatibility
Implementation Steps
Future Enhancements
Technology Stack & Environment Setup
Backend: PHP (preferably PHP 8.1 or above)
Database: MySQL
Frontend: HTML5, Tailwind CSS, JavaScript (using frameworks like Bootstrap for responsive design)
Version Control: Git
Development Environment: Local server (e.g., XAMPP, WAMP, or Docker-based PHP environment)

Environment Setup
Install PHP & MySQL: Ensure PHP and MySQL are installed on your local or server environment.
Configure Web Server: Set up Apache/Nginx with PHP support.
Version Control: Initialize a Git repository to manage your source code.
Project Architecture & Directory Structure


A suggested directory structure is as follows:

/COOPS_BICHI/
│
├── /public/               # Publicly accessible files (index.php, assets)
│   ├── /css/
│   ├── /js/
│   └── /images/
│
├── /app/                  # Application logic
│   ├── /controllers/      # Controller files (UserController, LoanController, etc.)
│   ├── /models/           # Database models (User.php, Loan.php, etc.)
│   ├── /views/            # HTML/PHP view templates
│   ├── /helpers/          # Utility functions (validation, email notifications)
│   └── /config/           # Configuration files (database.php, routes.php)
│
├── /logs/                 # Log files for audit and error logging
├── /uploads/              # Folder for bulk data upload files
└── README.md              # Project documentation

Database Design
Suggested Tables
Users Table

id (Primary Key)
coop_no (Unique, e.g., "COOPS/04/001")
name
email
password (hashed using bcrypt)
role (ENUM: 'superadmin', 'admin', 'member')
failed_attempts
is_locked (Boolean for account lockout)
created_at
updated_at
Loan Details Table

id
user_id (Foreign Key)
loan_limit
ip_no
ip_figure
total_rpmt
balance
created_at
updated_at
Household Purchase Table

Similar structure to Loan Details
Savings Table

id
user_id (Foreign Key)
monthly_deduction
cumulative_amount_saved
grand_total_deduction
created_at
updated_at
Transaction History Table

For recording transactions related to loans, purchases, and savings.
Audit Log Table

id
user_id (Who performed the action)
action (Description of the action)
timestamp
details (JSON or text field with additional info)
Bulk Upload Logs Table (Optional)

Track file upload details and processing status.
User Roles & Permissions
Superadmin
Permissions:
Full control over the system.
Add/revoke Admin roles.
Upload and manage member decution (loans, household purchases, savings).
manage loan and household purchase application 

Admin
Permissions:
View member records without editing rights.
Access dashboards with graphical reports and audit logs.
view members loan and household purchase records but can not take action 
view members loan, household purchase and savings record but can not edit 

Member
Permissions:
View personal financial details (Loan, Household Purchase, Savings).
Access Loan Repayment Calculator.
View Transaction History.
Edit profile (except Coop No.).
Download financial reports (PDF or Excel).
apply for loan and household purchase loan  

Authentication & Registration
Login
Credentials:
Members log in using their Coop Number (e.g., COOPS/04/001) and password.


Backend Process:
Validate credentials.
Use session management to handle logged-in users.
Implement password hashing (bcrypt).


Registration
Fields:
Coop No.
Name
Password
Email

Restriction:
Only pre-added Coop Numbers in the database can complete registration.
Verification:
Validate that the provided Coop No. exists and is inactive before allowing registration.
Enhanced Security Measures
Password Hashing:

Use PHP’s password_hash() function with bcrypt for secure password storage.

Account Lockout:
Implement a mechanism to lock accounts after a defined number of failed login attempts (e.g., 5 attempts).
Secure Sessions:

Use secure cookies and session management practices to prevent session hijacking.
Input Validation & Sanitization:

Validate all user inputs and use prepared statements (PDO or MySQLi) to avoid SQL injection.
Notifications & Alerts
Email Notifications:

Send emails for:
Loan approvals.
Household purchase approvals.
Savings updates.
Use PHP mailer libraries (e.g., PHPMailer).

Dashboard Alerts:

Display real-time alerts for pending loan approvals and financial changes.


Member Features
Loan Repayment Calculator:

Provide a tool for members to input loan parameters and calculate repayment schedules.

Transaction History:

List all transactions related to loans, household purchases, and savings.
Use paginated views and filters for better usability.

Member Profile Page:

Display contact details and allow members to update editable fields.
Keep Coop No. immutable.

Downloadable Reports:
Enable members to download reports in PDF or Excel format.
Utilize libraries like TCPDF for PDF generation or PHPExcel for Excel files.

Superadmin Features
Bulk Data Upload:

Implement CSV/Excel file upload functionality.
Parse files and insert data into respective tables.
Provide feedback on upload success/failure.
Audit Log System:

Log every significant action by Admin and Superadmin.
Include details such as user ID, action performed, and timestamp.

Role-Based Dashboard:

Create separate dashboard views:
Superadmin Dashboard: Access to user management,loan management, household purchase loan managment, savings management, bulk or single deduction upload, detailed audit logs, and system settings.
Admin Dashboard: Access to member records, view-only transaction details, and graphical financial reports.
Graphical Reports:

Use charting libraries (e.g., Chart.js) to display:
Loan distributions.
Savings trends.
Overall cooperative growth metrics.

Landing Page Enhancements
Modern Landing Page:

Design a responsive and modern landing page.
Include details about the multipurpose cooperative society and its benefits.

FAQ Section:
Provide answers to common queries about membership, loans, and other cooperative-related questions.

Contact Form:

Develop a contact form for inquiries or support requests.
Ensure submissions are sent to a designated email or stored in the database.

News & Announcements:
Create a section for cooperative news, updates, and announcements.
Allow Superadmin to post new announcements.
Responsive Design & Cross-Device Compatibility
To ensure an optimal user experience across various devices, incorporate the following responsive design features:

Mobile View
Simplified Navigation:
Implement a hamburger menu for compact navigation.
Optimize clickable elements for touch input.
Optimized Content Layout:

Use responsive grids to rearrange dashboard widgets and forms.
Minimize the amount of on-screen data to reduce clutter.

Performance Enhancements:

Lazy load images and defer non-critical JavaScript for faster load times on mobile devices.

Tablet View
Adaptive Layout:

Utilize multi-column layouts where possible, with appropriate spacing for touch interaction.
Ensure that forms and tables are scrollable horizontally if needed.
Enhanced Readability:
Increase font sizes and button dimensions to suit tablet screens.
Maintain a balance between detail and simplicity.

Desktop (Windows) View
Expanded Dashboard:

Leverage larger screen real estate to display more detailed dashboards and comprehensive reports.
Use modal windows for detailed record views without navigating away from the main dashboard.

Advanced Features:
Include advanced filtering, drag-and-drop widgets, and detailed audit logs.
Enable multi-window or split-screen views for enhanced multitasking.
Interactive Elements:

Utilize hover effects, tooltips, and interactive charts to enhance the user experience.

Implementation Recommendations
Frameworks:
Use responsive frameworks like Bootstrap or Foundation to streamline the responsive design process.


Implementation Steps
Planning & Design:

Finalize the database schema.
Create wireframes for each role-specific dashboard, landing page, and responsive views.
Define API endpoints and URL routing.
Database Setup:

Set up MySQL database and create required tables.
Populate the users table with pre-approved Coop Numbers for registration.

Authentication Module:
Develop the login and registration modules.
Implement password hashing, and account lockout logic.

Role Management:

Create middleware to check user roles and permissions.
Ensure route access is restricted based on roles.
Feature Development:

Build the Member features: profile management, loan calculator, transaction history, and report downloads.
Build the Admin features: view-only access to member records, and audit logging.
Build the Superadmin features: full control panels, user management, and system settings etc.

Responsive Design Integration:
Implement mobile, tablet, and desktop responsive layouts using tailwind CSS.
Adjust UI components based on device type to ensure usability.
Notifications & Alerts Integration:

Integrate email notification systems.
Develop a real-time dashboard alert system.
Landing Page & Public Interface:

Design and develop the landing page with all public information.
Implement the FAQ section, contact form, and announcements.


Note: seperate user (Superadmin and Admin) from Members, dont add them on the same table 
also the application form for Loan and Household purchase should contain (Fullname, Coops no,loan Amount, Ip figure (amount to pay every month) )