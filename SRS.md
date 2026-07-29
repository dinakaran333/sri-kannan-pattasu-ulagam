# Software Requirements Specification (SRS)
## Online Cracker Shop Website

### 1. Introduction
#### 1.1 Purpose
The purpose of this document is to define the complete software requirements for the **Online Cracker Shop Website**. This web application provides an online platform where customers can browse, search, select, and purchase various festive fireworks and crackers, while store administrators manage inventory, orders, customer details, sales reports, and website settings.

#### 1.2 Scope
The scope of this project encompasses a web-based e-commerce platform built using modern Web standards (HTML5, CSS3, Bootstrap 5, JavaScript, AJAX, PHP 8, MySQL). The system supports user authentication, responsive UI design, interactive shopping cart, order placement, and an administrative dashboard for business operations.

#### 1.3 Definitions, Acronyms, and Abbreviations
- **SRS:** Software Requirements Specification
- **PDO:** PHP Data Objects
- **AJAX:** Asynchronous JavaScript and XML
- **COD:** Cash On Delivery
- **CRUD:** Create, Read, Update, Delete
- **XAMPP:** Cross-Platform, Apache, MySQL, PHP, and Perl package

---

### 2. General Description
#### 2.1 Product Perspective
The system is an independent, web-accessible e-commerce software package designed to run on Apache Web Server with PHP 8+ and MariaDB/MySQL database engine (such as XAMPP stack).

#### 2.2 System Functions Overview
- **Customer Portal:** User Registration, Login, Product Catalog, Category Filter, Product Detail View, Live AJAX Search, AJAX Cart Management, Address & Order Placement, Order History & Status Tracking, Contact Form.
- **Admin Panel:** Secure Admin Authentication, Real-Time Analytics Dashboard, Category Management, Product Inventory CRUD, Customer Management, Order Status Management (Pending, Processing, Shipped, Delivered, Cancelled), Sales Reporting, General Settings Configuration.

---

### 3. Functional Requirements

#### 3.1 Customer Functions
- **FR-01:** Customer account creation with password hashing (`password_hash`).
- **FR-02:** User login and session management.
- **FR-03:** Browse products with categories, MRP vs. Offer Price, stock availability.
- **FR-04:** Dynamic product search and multi-criteria filter (Category, Price Range, Sort Order).
- **FR-05:** Interactive cart with dynamic quantity adjustments via AJAX without page reload.
- **FR-06:** Secure checkout process supporting Billing & Shipping address collection and Cash on Delivery (COD).
- **FR-07:** Order tracking showing order status timeline.
- **FR-08:** Contact store with message logging in administrative portal.

#### 3.2 Admin Functions
- **FR-09:** Secure Admin login authentication with session validation.
- **FR-10:** Dashboard displaying Total Sales, Total Revenue, Today's Orders, Product Count, Category Count, Customer Count, and Recent Orders.
- **FR-11:** Category CRUD operations (Name, Slug, Description, Status, Image Upload).
- **FR-12:** Product CRUD operations (Category, Name, Description, MRP, Offer Price, Stock Quantity, Featured Flag, Product Image Upload).
- **FR-13:** Order management allowing admin to view details and update order status (Pending, Processing, Shipped, Delivered, Cancelled).
- **FR-14:** View customer profiles and historical order stats.
- **FR-15:** Generate sales reports by date range (Daily, Monthly, Custom range).
- **FR-16:** Store settings management (Store Name, Phone, Email, Address, Currency Symbol).

---

### 4. Non-Functional Requirements
- **NFR-01 Performance:** Page response time under 1.5 seconds for standard operations. AJAX endpoints execute within 300ms.
- **NFR-02 Security:** PDO Prepared Statements to block SQL Injection. Output escaping using `htmlspecialchars()` to prevent XSS. Password encryption via BCRYPT. CSRF token protection on sensitive forms.
- **NFR-03 Usability:** Modern responsive UI built with Bootstrap 5 and Google Font Poppins. Mobile-first flexbox layouts.
- **NFR-04 Availability:** 99.9% uptime when deployed on standard Apache/Nginx web servers.
- **NFR-05 Maintainability:** Modular PHP architecture separating configuration, includes, AJAX APIs, and presentation views.

---

### 5. System Architecture & Diagrams

#### 5.1 System Architecture
```
+-------------------------------------------------------------------+
|                            CLIENT                                 |
| Browser (HTML5 / CSS3 / Bootstrap 5 / JS ES6 / AJAX / Icons)      |
+---------------------------------+---------------------------------+
                                  | HTTP / HTTPS Requests
                                  v
+---------------------------------+---------------------------------+
|                        APACHE WEB SERVER                          |
|                                                                   |
| +-------------------------+     +-------------------------------+ |
| | Customer Portal         |     | Admin Panel                   | |
| | (index, products, cart) |     | (dashboard, products, orders) | |
| +------------+------------+     +---------------+---------------+ |
|              |                                  |                 |
|              +----------------+-----------------+                 |
|                               |                                   |
|                               v                                   |
|                  +--------------------------+                     |
|                  | PHP 8 Engine             |                     |
|                  | (PDO, Auth, Functions)   |                     |
|                  +------------+-------------+                     |
+-------------------------------+-----------------------------------+
                                | SQL Queries
                                v
+-------------------------------+-----------------------------------+
|                        MYSQL DATABASE                             |
| Tables: users, categories, products, orders, order_items, admin...|
+-------------------------------------------------------------------+
```

#### 5.2 ASCII Use Case Diagram
```
              +-------------------+
              |     Customer      |
              +---------+---------+
                        |
       +----------------+----------------+
       |                |                |
       v                v                v
+--------------+ +--------------+ +--------------+
| Browse &     | | Add to Cart  | | Place Order  |
| Search Items | | & Update Qty | | (COD/Online) |
+--------------+ +--------------+ +--------------+
                        |
                        v
              +-------------------+
              |    Admin Panel    |
              +---------+---------+
                        |
       +----------------+----------------+
       |                |                |
       v                v                v
+--------------+ +--------------+ +--------------+
| Manage Inventory| | Manage Orders| | Sales Reports|
| (CRUD)       | | & Statuses   | | & Analytics  |
+--------------+ +--------------+ +--------------+
```

#### 5.3 ASCII ER Diagram
```
[USERS] 1 ----- < Places > ----- N [ORDERS]
  |                                   |
  | 1                                 | 1
  |                                   |
  v N                                 v N
[REVIEWS/CONTACTS]              [ORDER_ITEMS] N ----- 1 [PRODUCTS]
                                                            |
                                                            | N
                                                            |
                                                            v 1
                                                       [CATEGORIES]
```

---

### 6. Database Design Overview
The relational database `cracker_shop` comprises 8 normalized tables:
1. `users` - Customer account details.
2. `admin` - System administrator logins.
3. `categories` - Product categories (Sparklers, Rockets, Flower Pots, Bombs, Gift Boxes).
4. `products` - Cracker inventory data with pricing, stock, images.
5. `orders` - Order header records with status tracking.
6. `order_items` - Line item details for every order.
7. `contacts` - Customer inquiry submissions.
8. `newsletter` - Email subscriptions.

---

### 7. Testing Strategy
- **Unit Testing:** Database query execution verification using PDO exceptions.
- **Integration Testing:** Order placement workflow starting from Add-to-Cart -> Checkout -> Order Table insertion -> Stock decrement.
- **Security Audit:** Form vulnerability scanning for SQL injection and cross-site scripting (XSS).
- **Responsive Layout Testing:** UI testing across mobile (375px), tablet (768px), and desktop (1440px) viewports.

---

### 8. Future Enhancements
- Integration of live Payment Gateways (Razorpay, Stripe, PayPal).
- SMS notifications for order status tracking (Twilio / SMS Gateway).
- Dynamic PDF Invoice Generator.
- Multi-language support (English, Regional languages).
