# 🎁 Lootbox Demo

A self-hosted PHP web app styled like a digital lootbox storefront. Built with Docker and MySQL, this demo features a dynamic UI, pre-populated product database, and classic web dev components.

> ⚠️ Requires Docker + Docker Compose installed locally.

---

## 🚀 Quickstart

Clone the repo and spin it up:

```bash
git clone https://github.com/denby/lootbox-demo.git
cd lootbox-demo
docker-compose up --build
```

Visit the app:

- Frontend: [http://localhost:8080](http://localhost:8080)
- phpMyAdmin: [http://localhost:8081](http://localhost:8081)
  - **Username:** `root`
  - **Password:** `root`

---

## 🗃️ Load the Product Database

The project includes a sample MySQL database pre-filled with games and product data.

Once your Docker containers are running, run this in a separate terminal to import the data:

```bash
docker exec -i mysql_db mysql -u root -proot gamestore < sd_db.sql
```

> 💡 Make sure `sd_db.sql` is in the root directory of the project. You can rename the file if you like, just adjust the command accordingly.

---

## 🧪 Features

- PHP-based product listing with item images, descriptions, and pricing
- Dynamic database-driven inventory
- UI inspired by digital storefronts
- Fully containerised via Docker and Docker Compose
- phpMyAdmin access for debugging or browsing the DB

---

## 🛠 Tech Stack

- PHP 8
- MySQL 8
- Docker & Docker Compose
- phpMyAdmin

---

## 🧼 Reset Everything (Optional)

If you want to reset the environment from scratch:

```bash
docker-compose down -v
docker-compose up --build
```

Then re-run the database import.

---

## 👋 Author

Made with 💻 by Denby. Built as part of a coursework project for CMS200.
