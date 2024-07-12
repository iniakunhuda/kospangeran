# Property Management (Aplikasi Kos-Kosan)

## About 📖

Kos Pangeran is full management tool to help homeowner to manage their property like house-rental, apartment, or boarding house. Designed to be simple and easy to use, Kos Pangeran is a perfect solution for those who want to manage their property without any hassle.

## Features 🚀

-   Manage building area
-   Manage room & room type
-   Manage member
-   Record member payment / transaction
-   Record room maintenance history
-   Create invoice for monthly or yearly
-   Multiple account for payment
-   Export payment history to excel & pdf

See the [screenshot](#screenshot-) below for more detail.

## Built with 🛠️

-   Laravel 11
-   Docker
-   MongoDB
-   Bootstrap 5
-   DataTables

## Installation 🖥️

1. Clone this repository

```bash
git clone https://github.com/iniakunhuda/kospangeran
```

2. Install dependencies

```bash
composer install
```

3. Copy `.env.example` to `.env`

```bash
cp .env.example .env
```

4. Generate application key

```bash
php artisan key:generate
```

5. Run docker

```bash
docker-compose up -d
```

6. Run migration

```bash
php artisan migrate
```

7. Run seeder

```bash
php artisan db:seed
```

8. Open the application in browser

```bash
http://localhost:8000
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contact

If you have any question or want to contribute, feel free to contact me:

-   Email: [iniakunhuda@gmail.com](mailto:iniakunhuda@gmail.com)
-   LinkedIn: [https://www.linkedin.com/in/iniakunhuda/](https://www.linkedin.com/in/iniakunhuda/)

<br><br>

# Screenshot 📸

### **Manage building area**

![Building Area](screenshot/area.png)
<em>Create, edit, delete area</em>

### **Manage room**

![Room](screenshot/room1.png)
<em>Create, edit, delete room</em>

![Room](screenshot/room2.png)
<em>Form create room</em>

![Room](screenshot/room3.png)
<em>Detail room information</em>

![Room](screenshot/room4.png)
<em>Detail room information - history member</em>

### **Manage room type**

![Room Type](screenshot/room-type.png)
<em>Create, edit, delete room type</em>

### **Manage member**

![Member](screenshot/member.png)
<em>Create, edit, delete member</em>

![Member](screenshot/member2.png)
<em>Detail member information</em>

![Member](screenshot/member3.png)
<em>Detail member room & invoice</em>

### **Manage invoice**

![Invoice](screenshot/invoice.png)
<em>List unpaid invoice</em>

![Invoice](screenshot/invoice1.png)
<em>Create automatic invoice based month & year</em>

![Invoice](screenshot/invoice2.png)
<em>Form to record payment (can be partial)</em>

**Payment history**

![Payment](screenshot/payment.png)
<em>List payment history (can be exported to excel & pdf)</em>

![Payment](screenshot/payment1.png)
<em>Detail payment history</em>
