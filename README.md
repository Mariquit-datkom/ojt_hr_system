# OJT HR Management System
This is a personal project developed during my internship at **Hayakawa Electronics (Phils.) Corp.** The primary goal of this system was to practice and master core web development technologies while providing a functional tool for HR-related tasks.

## :pushpin: Project Overview
The OJT HR System is designed to streamline basic human resource functions, specifically focusing on data management and document generation. This project served as a hands-on learning experience to transition from theoretical knowledge to building a real-world application.

## :rocket: Features
* **Intern Records Management**: Add, update, and manage intern information.
* **Document Generation**: Export data into professional formatted PDF files.
* **Data Export / Import**: Generate and handle Excel spreadsheets for reporting.
* **Intern Referrals**: Operate intern referrals from employees.

## :hammer_and_wrench: Tech Stack
* **Backend**: PHP (core)
* **Frontend**: Javascript, CSS, HTML
* **Icons**: [Font Awesome](https://fontawesome.com/)
* **Libraries**:
    * [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) - Used for generating and reading Excel Files.
    * [mPDF](https://github.com/mpdf/mpdf) - Used for converting HTML to PDF documents.

## :open_file_folder: Installation
1. **Clone the repository**: Clone the repo inside your htdocs folder (XAMPP) or www (WAMP) directory.
```
git clone https://github.com/Mariquit-datkom/ojt_hr_system
```
2. **Install dependencies**: Download and install all external libraries used: (Ensure you have PHP v8.1 or newer)
    * [Font Awesome](https://fontawesome.com/download): Select _Download for Web_ and extract it to a folder named _libs_ inside the project folder.
    * PhpSpreadsheet and mPDF: If you do not have _composer_ installed, make sure to go to this [link](https://getcomposer.org/Composer-Setup.exe) to download the installation file. Run it and follow the installation process.
        * With composer installed, open terminal in your project's root folder and run the following:
        ```
        composer require phpoffice/phpspreadsheet
        composer require mpdf/mpdf
        ```

## :bulb: What I Learned
* Structuring a PHP-based application from scratch.
* Integrating third-party PHP libraries via Composer.
* Handling file streams and document rendering (PDF and Spreadsheet).
* Developing a dynamic UI for better user experience.

## :office: Acknowledgement
Special thanks to **Hayakawa Electronics (Phils.) Corp.** for the opportunity to learn and grow during my internship period.