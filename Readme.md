# Scandiweb Junior Web Developer Assignment

## Project Description

This project is the assignment for the Junior Web Developer position at Scandiweb.

## Table of Contents

- [How to Install and Run the Project](#how-to-install-and-run-the-project)
- [How to Use the Project](#how-to-use-the-project)
- [Technologies](#technologies)

## How to Install and Run the Project

### 1. Clone the repository to your local machine:

```bash
git clone https://github.com/AbdelhamidYousef/Scandiweb
cd Scandiweb
```

### 2. Install Backend Dependencies:

``` bash
cd back
composer install
```

### 3. Set Up Local Server:

Ensure you have Apache and MySQL installed and running on your local machine. 
You may need to configure Apache to serve the project directory.

### 4. Set Up Database:

From the backend directory, run the following command to set up the database:

```bash
composer database
```
This command will create the necessary database and tables if they don't already exist.

### 5. Install Frontend Dependencies:

```bash
cd ../front
npm install
```

### 6. Run the Application:

From the frontend directory, run the development server:

```bash
npm run dev
```

## How to Use the Project

The project consists of two main pages:

### 1. Product List Page:

- It displays a list of all products available.
- You can browse through the products.
- To delete products, check the checkboxes next to each product, then click the "Mass Delete" button to remove them.

### 2. Product Creation Page:

- To add a new product, click the "Add Product" button available on the Product List page.
- You'll be redirected to the Product Creation page.
- Fill out the form with the necessary details for the new product.
- After filling out the form, click the "Submit" button to create the new product.
- Upon successful submission, you'll be redirected back to the Product List page, where you can see the newly added product in the list.
- You can also click the "Cancel" button to clear the from and redirect back to the product list page.

## Technologies

[![React](https://img.shields.io/badge/-React-61DAFB?logo=react&logoColor=white)](https://reactjs.org/)
[![SASS](https://img.shields.io/badge/-SASS-CC6699?logo=sass&logoColor=white)](https://sass-lang.com/)
[![Vite](https://img.shields.io/badge/-Vite-646CFF?logo=vite&logoColor=white)](https://vitejs.dev/)

[![PHP](https://img.shields.io/badge/-PHP-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/-MySQL-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Composer](https://img.shields.io/badge/-Composer-885630?logo=composer&logoColor=white)](https://getcomposer.org/)