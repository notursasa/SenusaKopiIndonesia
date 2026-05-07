# Senusa Kopi - Point of Sale (POS) & Management System

A comprehensive web-based Point of Sale (POS) and inventory management system designed specifically for coffee shops. This system helps manage everything from ingredient stocks and recipes to staff, branches, and sales transactions.

## 🚀 Features

### 1. Dashboard
- Real-time overview of business performance.
- Quick navigation to key modules.

### 2. User Authentication & Authorization
- Secure login and logout system.
- Role-based access control (Staff management).

### 3. Master Data Management
Complete management of business entities including:
- **Staff**: Manage employees and their access levels.
- **Branches**: Manage multiple outlet locations.
- **Suppliers**: Keep track of ingredient sources.
- **Customers**: Maintain a customer database for loyalty or tracking.
- **Categories**: Organize products into logical groups.
- **Products**: Detailed menu management.
- **Ingredients**: Track raw materials and stock levels.
- **Recipes**: Link products to ingredients for automatic stock deduction.

### 4. Transactions
- **POS (Point of Sale)**: Easy-to-use interface for processing customer orders.
- **Supply**: Record and track incoming inventory from suppliers.

### 5. Reporting
- **Stock Reports**: Monitor current ingredient levels and movements.
- **Sales Reports**: Analyze daily, weekly, or monthly sales data.

## 🛠️ Tech Stack

- **Backend**: PHP (Native)
- **Database**: MySQL
- **Frontend**: HTML5, Custom CSS3
- **Icons**: FontAwesome 6.0
- **Server Environment**: XAMPP / Apache

## 📋 Installation Guide

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-username/senusa_kopi.git
   ```

2. **Setup Local Server**
   - Move the project folder to your local server directory (e.g., `C:/xampp/htdocs/` for XAMPP).

3. **Database Setup**
   - Open PHPMyAdmin (`http://localhost/phpmyadmin`).
   - Create a new database named `senusa_kopi`.
   - Import the provided `db.sql` file into the database.

4. **Configuration**
   - Open `config/database.php`.
   - Update the database credentials (host, username, password) if they differ from your local setup.

5. **Access the App**
   - Open your browser and navigate to `http://localhost/senusa_kopi`.
