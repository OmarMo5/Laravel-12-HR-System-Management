# 🧑‍💼 HR Management System

## 📌 Overview
The HR Management System is a comprehensive web-based solution designed to streamline and manage all aspects of human resources operations داخل المؤسسات.

It helps organizations improve operational efficiency, reduce administrative workload, and ensure accurate tracking of employee-related data such as attendance, leaves, and performance.

---

## 🚀 Key Features

### 👥 Employee Management
- View and manage all employees in a structured and searchable list
- Add, update, and organize employee data بسهولة
- Advanced filtering and search capabilities
- Accurate and well-structured data handling

---

### ⏱️ Attendance & Time Tracking
- Electronic attendance system (check-in / check-out)
- Track daily working hours precisely
- Full transparency between employees and management
- Detailed attendance reports

---

### 📊 Attendance Records
- Advanced filtering options (by employee, date, etc.)
- Clean and easy-to-read data presentation
- Identify delays and attendance issues quickly
- Support data-driven decisions

---

### 📈 Attendance Reports
- Comprehensive report for all employees
- Overview of attendance patterns
- Helps identify trends and behavior
- Supports management decision-making

---

### 📝 Leave Management

#### 📂 Leave History
- Track all leave requests
- Status tracking (Approved, Rejected, Pending)
- Clear visibility of leave balance

#### ➕ Request Leave
- Submit leave requests بسهولة
- Define:
  - Leave type (Annual, Sick, etc.)
  - Start and end dates
  - Duration (Full day / Half day)
  - Reason for leave
- Display remaining leave balance أثناء الطلب

#### ✅ Approval System
- Approve or reject leave requests
- Automatic notifications sent to employees

#### 🎯 Direct Leave Assignment
- HR can assign leave directly without request
- Automatically deducted from balance
- Useful for special or emergency cases

---

### 🎉 Public Holidays
- Add company-wide holidays and official events
- Automatically applied to all employees
- Helps in better scheduling and planning

---

### 🏢 Department Management
- Manage company departments
- View number of employees per department
- Assign department managers
- Add and update departments بسهولة

---

### 📊 Dashboard
- Overview of total employees
- Quick access to system modules
- High-level insights into HR status

---

## 🔔 Notifications
- Automatic notifications for:
  - Leave approval
  - Leave rejection
- Enhances communication between HR and employees

---

## ⚙️ Tech Stack

- **Backend:** PHP (Laravel Framework)
- **Frontend:** Blade Template Engine
- **Database:** MySQL
- **Authentication & Authorization:** Laravel + Spatie Role & Permission
- **Notifications:** Laravel Notification System

---

## 🧩 Modules

- Employee Management
- Attendance System
- Leave Management
- Reports & Analytics
- Department Management
- Notification System

---

## 🔮 Future Enhancements

### 📌 Recruitment System
- Manage job applicants
- Store CVs and candidate data
- Compare candidates بسهولة
- Improve hiring process

---

### 📌 Performance Evaluation System
- Periodic employee evaluations
- Performance tracking
- Identify strengths and weaknesses
- Improve overall productivity

---

## 🛠️ Installation

```bash
# Clone the repository
git clone https://github.com/OmarMo5/Laravel-12-HR-System-Management.git

# Navigate into the project
cd hr-management-system

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database inside .env

# Run migrations
php artisan migrate

# (Optional) Seed database
php artisan db:seed

# Start development server
php artisan serve