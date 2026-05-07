-- ==========================================================
-- DATABASE INITIALIZATION (REVISED)
-- Project: Senusa Kopi Indonesia
-- Based on: Updated Attribute Domains & Logical ERD
-- ==========================================================

DROP DATABASE IF EXISTS db_senusa_kopi;
CREATE DATABASE db_senusa_kopi;
USE db_senusa_kopi;

-- ==========================================================
-- 1. INDEPENDENT TABLES
-- ==========================================================

-- Table: ProductCategory
-- ID Format: CT + 11 Digits
CREATE TABLE ProductCategory (
    category_id CHAR(13) NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    category_description VARCHAR(200) NOT NULL,
    PRIMARY KEY (category_id),
    CONSTRAINT chk_cat_id CHECK (category_id REGEXP '^CT[0-9]{11}$')
);

-- Table: Branch
-- ID Format: BR + 11 Digits
CREATE TABLE Branch (
    branch_id CHAR(13) NOT NULL,
    branch_name VARCHAR(100) NOT NULL,
    branch_address VARCHAR(100) NOT NULL,
    PRIMARY KEY (branch_id),
    CONSTRAINT chk_branch_id CHECK (branch_id REGEXP '^BR[0-9]{11}$')
);

-- Table: Supplier
-- ID Format: SP + 11 Digits
CREATE TABLE Supplier (
    supplier_id CHAR(13) NOT NULL,
    supplier_name VARCHAR(100) NOT NULL,
    supplier_phone VARCHAR(13) NOT NULL,
    supplier_email VARCHAR(100) NOT NULL,
    PRIMARY KEY (supplier_id),
    CONSTRAINT chk_supp_id CHECK (supplier_id REGEXP '^SP[0-9]{11}$')
);

-- Table: Ingredient
-- ID Format: IN + 11 Digits
CREATE TABLE Ingredient (
    ingredient_id CHAR(13) NOT NULL,
    ingredient_name VARCHAR(100) NOT NULL,
    PRIMARY KEY (ingredient_id),
    CONSTRAINT chk_ing_id CHECK (ingredient_id REGEXP '^IN[0-9]{11}$')
);

-- Table: Customer
-- ID Format: CU + 11 Digits
-- Email must contain '@' and '.'
CREATE TABLE Customer (
    customer_id CHAR(13) NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(13) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    PRIMARY KEY (customer_id),
    CONSTRAINT chk_cust_id CHECK (customer_id REGEXP '^CU[0-9]{11}$'),
    CONSTRAINT chk_cust_email CHECK (customer_email LIKE '%@%.%')
);

-- ==========================================================
-- 2. DEPENDENT TABLES (Level 1)
-- ==========================================================

-- Table: Product
-- ID Format: PR + 11 Digits
CREATE TABLE Product (
    product_id CHAR(13) NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    product_price FLOAT NOT NULL, 
    product_image VARCHAR(255) NOT NULL,
    category_id CHAR(13) NOT NULL,
    PRIMARY KEY (product_id),
    FOREIGN KEY (category_id) REFERENCES ProductCategory(category_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_prod_id CHECK (product_id REGEXP '^PR[0-9]{11}$')
);

-- Table: Staff
-- ID Format: ST + 11 Digits
-- Role Domain: Cashier, Manager, Admin
CREATE TABLE Staff (
    staff_id CHAR(13) NOT NULL,
    staff_username VARCHAR(100) NOT NULL,
    staff_password VARCHAR(25) NOT NULL,
    staff_role ENUM('Cashier', 'Manager', 'Admin') NOT NULL,
    branch_id CHAR(13) NOT NULL,
    PRIMARY KEY (staff_id),
    FOREIGN KEY (branch_id) REFERENCES Branch(branch_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_staff_id CHECK (staff_id REGEXP '^ST[0-9]{11}$')
);

-- Table: SupplyOrder
-- ID Format: SY + 11 Digits
-- Order Status Domain: Waiting For Payment, Preparing, Delivering, Done, Canceled
-- Payment Status Domain: Waiting for Verification, Successful, Failed
CREATE TABLE SupplyOrder (
    supply_order_id CHAR(13) NOT NULL,
    supply_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    supplier_id CHAR(13) NOT NULL,
    order_status ENUM('Waiting For Payment', 'Preparing', 'Delivering', 'Done', 'Canceled') NOT NULL,
    payment_status ENUM('Waiting for Verification', 'Successful', 'Failed') NOT NULL,
    PRIMARY KEY (supply_order_id),
    FOREIGN KEY (supplier_id) REFERENCES Supplier(supplier_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_supply_id CHECK (supply_order_id REGEXP '^SY[0-9]{11}$')
);

-- ==========================================================
-- 3. DEPENDENT TABLES (Level 2 & Transactions)
-- ==========================================================

-- Table: SalesOrder
-- ID Format: SO + 11 Digits
-- Order Type: Dine In, Take Out
-- Order Status: Waiting For Payment, Preparing, Done, Canceled (Note: No 'Delivering' for Sales)
-- Payment Status: Waiting for Verification, Successful, Failed
CREATE TABLE SalesOrder (
    sales_id CHAR(13) NOT NULL,
    sales_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    order_type ENUM('Dine In', 'Take Out') NOT NULL,
    customer_id CHAR(13) NOT NULL,
    staff_id CHAR(13) NOT NULL,
    order_status ENUM('Waiting For Payment', 'Preparing', 'Done', 'Canceled') NOT NULL,
    payment_status ENUM('Waiting for Verification', 'Successful', 'Failed') NOT NULL,
    PRIMARY KEY (sales_id),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_sales_id CHECK (sales_id REGEXP '^SO[0-9]{11}$')
);

-- Table: ProductRecipe
-- Many-to-Many: Product <-> Ingredient
CREATE TABLE ProductRecipe (
    product_id CHAR(13) NOT NULL,
    ingredient_id CHAR(13) NOT NULL,
    required_quantity INT NOT NULL,
    PRIMARY KEY (product_id, ingredient_id),
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES Ingredient(ingredient_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: BranchStock
-- Stok per cabang
CREATE TABLE BranchStock (
    branch_id CHAR(13) NOT NULL,
    ingredient_id CHAR(13) NOT NULL,
    stock_quantity INT NOT NULL,
    PRIMARY KEY (branch_id, ingredient_id),
    FOREIGN KEY (branch_id) REFERENCES Branch(branch_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES Ingredient(ingredient_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: SalesOrderDetail
-- Rincian Penjualan
CREATE TABLE SalesOrderDetail (
    sales_id CHAR(13) NOT NULL,
    product_id CHAR(13) NOT NULL,
    quantity_sold INT NOT NULL,
    PRIMARY KEY (sales_id, product_id),
    FOREIGN KEY (sales_id) REFERENCES SalesOrder(sales_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: SupplyOrderDetail
-- Rincian Pembelian Bahan
CREATE TABLE SupplyOrderDetail (
    supply_order_id CHAR(13) NOT NULL,
    branch_id CHAR(13) NOT NULL,
    ingredient_id CHAR(13) NOT NULL,
    quantity_bought INT NOT NULL,
    unit_price FLOAT NOT NULL,
    PRIMARY KEY (supply_order_id, ingredient_id),
    FOREIGN KEY (supply_order_id) REFERENCES SupplyOrder(supply_order_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES Branch(branch_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES Ingredient(ingredient_id) ON DELETE CASCADE ON UPDATE CASCADE
);