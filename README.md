# 🏛️ Athena Enterprise Library Cloud

![Status](https://img.shields.io/badge/Status-Production_Ready-success)
![Cloud](https://img.shields.io/badge/Cloud-AWS-orange)
![Backend](https://img.shields.io/badge/Backend-PHP_8.0-blue)
![Frontend](https://img.shields.io/badge/Frontend-TailwindCSS-38bdf8)
![Database](https://img.shields.io/badge/Database-MySQL_RDS-00758f)

> A highly available, secure, and scalable library management system re-architected for the cloud.

---

## 📖 Project Overview

**Athena v5.0** represents the evolution of a traditional local management system into a robust cloud-native application. The goal was to decouple the monolithic structure and leverage AWS managed services to ensure high availability, security, and scalability.

This project demonstrates a full migration strategy: moving from a local development environment to a **3-Tier Cloud Architecture** (Web Tier, App Logic, Data Tier).

---

## 🏗️ Cloud Architecture

The infrastructure was designed using the **AWS Well-Architected Framework**, focusing on security and reliability.

![Architecture Diagram](./architecture-diagram.jpg)
*(Note: Replace this path with your actual image file)*

### **Infrastructure Components:**

* **VPC (Virtual Private Cloud):** A custom isolated network (`10.0.0.0/16`) hosting all resources.
* **Public Subnet (Web Tier):** Hosts the **EC2 instance** running the Apache Web Server. It has direct access to the Internet Gateway (IGW) to serve traffic to users.
* **Private Subnet (Data Tier):** Hosts the **Amazon RDS (MySQL)** instance. This subnet is completely isolated from the internet for security.
* **Security Groups:**
  * **Web-SG:** Allows HTTP (80) and SSH (22) from specific IPs.
  * **DB-SG:** Only allows MySQL (3306) traffic originating from the **Web-SG**.
* **Hybrid Engine:** The frontend application features a smart environment detector that switches between "Simulation Mode" (Localhost) and "Production Mode" (AWS) automatically.

---

## 🛠️ Technology Stack

| Layer              | Technology              | Details                                              |
| :----------------- | :---------------------- | :--------------------------------------------------- |
| **Frontend** | HTML5, JavaScript (ES6) | Reactive UI with DOM manipulation.                   |
| **Styling**  | Tailwind CSS            | Modern "Glassmorphism" design system with Dark Mode. |
| **Backend**  | PHP 8.x                 | API-driven backend connecting to RDS.                |
| **Database** | Amazon RDS              | Managed MySQL instance.                              |
| **Compute**  | Amazon EC2              | t2.micro instance running Amazon Linux 2.            |
| **DevOps**   | SCP & SSH               | Secure deployment pipeline from macOS to Linux.      |

---

## 🚀 The Development Journey

### **Phase 1: Infrastructure as a Service (IaaS)**

We began by defining the network topology. A custom VPC was provisioned with Route Tables and Gateways to separate public facing assets from sensitive data stores.

### **Phase 2: Database Provisioning**

An RDS instance was launched in the private subnet. We verified security group rules to ensure "Least Privilege Access," allowing only the web server to communicate with the database.

### **Phase 3: The "Hybrid" Application Logic**

To solve the issue of local testing without a local PHP server, I developed a **"Hybrid Engine"** in JavaScript:

* **On Localhost:** The app detects connection failures and falls back to **Mock Data**, allowing for rapid UI/UX iteration.
* **On AWS:** The app successfully connects to `api.php`, switching the System Status indicator to **"Online (AWS)"** in green.

### **Phase 4: Deployment & Seeding**

We utilized `scp` (Secure Copy Protocol) to transfer the codebase. A custom `seed.php` script was written to initialize the production database with sample data, preventing the "Empty Dashboard" issue upon first launch.

---

## 🧗 Challenges & Solutions

| Challenge                         | Impact                                                                                 | Solution                                                                                                                |
| :-------------------------------- | :------------------------------------------------------------------------------------- | :---------------------------------------------------------------------------------------------------------------------- |
| **PHP Raw Text Error**      | Browsers cannot execute PHP locally without a server, causing code to spill on screen. | **Separation of Concerns:** Split the project into `index.html` (pure frontend) and `api.php` (pure backend). |
| **CORS / Avatar Blocking**  | External avatar APIs were blocked by browser security policies during local testing.   | **CSS Avatars:** Replaced external image dependencies with CSS-generated initials to ensure 0 console errors.     |
| **Permission Denied (SSH)** | MacOS security prevented the use of the `.pem` key file for transfer.                | **Key Hardening:** Applied `chmod 400 labsuser.pem` to lock down key permissions before connection.             |
| **Cold Start Database**     | The app worked but showed zero data after deployment.                                  | **Seeder Script:** Created `seed.php` to automate the schema creation and data population process.              |

---

## 📥 Deployment Instructions

If you wish to deploy this architecture, follow these steps:

1. **Configure API:**
   Update `api.php` with your RDS Endpoint.

   ```php
   $db_host = 'your-rds-endpoint.us-east-1.rds.amazonaws.com';
   ```
2. **Upload Files (via SCP):**

   ```bash
   chmod 400 your-key.pem
   scp -i your-key.pem index.html api.php seed.php ec2-user@YOUR_IP:/home/ec2-user/
   ```
3. **Install on Server:**
   SSH into your instance and move files to the web root.

   ```bash
   sudo mv /home/ec2-user/*.php /var/www/html/
   sudo mv /home/ec2-user/index.html /var/www/html/index.php
   ```
4. **Initialize:**
   Visit `http://YOUR_PUBLIC_IP/seed.php` to populate the database.
5. **Launch:**
   Visit `http://YOUR_PUBLIC_IP/` to see the live dashboard.

---

### 📜 License

This project is open-source and available under the **MIT License**.

*Developed by [Muhammad Hamza] - Software Engineering Student, 5th Semester.*
