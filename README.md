# Employee Management API (Laravel 9): Supercharge Your HR Processes! 

**Get started with a robust and user-friendly API to manage your employees, track attendance, and generate insightful reports.** 

**Key Features:**

* **Secure Authentication:** Sanctum safeguards your API with features like registration, login, logout, password reset, and more. 
* **Comprehensive Employee Management:** Create, read, update, and delete employee information seamlessly. Manage essential fields like names, email, employee ID, phone number, and more. 
* **⏱️ Streamlined Attendance Tracking:** Easily record employee arrival and departure times, providing valuable insights into their activity. 
* **Automated Email Notifications:** Stay informed by automatically sending email alerts to employees upon recording their attendance. ✉️
* **Flexible Reporting & Exports:** Generate detailed attendance reports in PDF and Excel formats, empowering you to make data-driven decisions.
* **Automated Testing:** PRs are automatically tested and have to pass 100%, ensuring code quality and functionality.

**️ Requirements:**

* **PHP 8.1 or higher:** The project requires PHP 8.1 or later for optimal performance and security. ⚙️
* **Composer:** Composer manages dependencies efficiently. 
* **Laravel 9:** The project is built upon Laravel 9, offering a modern and robust framework. ️
* **Database:** Choose your preferred database compatible with Laravel (e.g., MySQL). ️
* **Mailpit:** Leverage Mailpit for local email testing during development. 
* **wkhtmltoimage:** Generate visually appealing PDF reports with wkhtmltoimage. Update the `WKHTML_PDF_BINARY` path in `config/snappy.php`. 

***Getting Started:***

**1. Install Laravel:**

- Ensure you have PHP 8.1 or later installed. ️
- Download and install Composer from [https://getcomposer.org/](https://getcomposer.org/). 

**2. Clone the Repository:**

- Open your terminal and navigate to your desired project directory.
- Run the following command to clone the repository:
- `git clone https://github.com/ShejaEddy/Employees-Management-API.git`

**2. Set Up Environment:**

- Enter in the cloned project directory
- Copy the `.env.example` file to `.env` and configure your database, email, and other settings. 
- Run `php artisan key:generate` to generate an application key. 

**3. Install Dependencies:**

- Run `composer install` to install all required dependencies. 

**4. Migrate Database:**

- Run `php artisan migrate` to create database tables. 

**5. Seed Data (Optional):**

- We have sample data, run `php artisan migrate --seed` to populate the database. 

**6. Run Tests:**

- Run `php artisan test` to ensure code quality and functionality. 

**7. Start Queue Worker:**

- Run `php artisan queue:listen --timeout=0` to process queued jobs (e.g., email notifications). 

**8. Configure Mailpit:**

- Install Mailpit locally and configure its settings in `.env`. 

**9. Configure wkhtmltoimage:**

- Install wkhtmltoimage locally and update the `WKHTML_PDF_BINARY` path in `config/snappy.php`. 

***Documentation:***

Detailed documentation, including API reference and usage guides, can be found at **[http://localhost:8000/api/documentation This is our project API documentation](http://localhost:8000/api/documentation)**

**Additional Notes:**

- Admin credentials are: 
    - Email: admin@example.com
    - Password: password
