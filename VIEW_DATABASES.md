# How to View MySQL and MongoDB Databases

This guide shows you how to access and view data in both MySQL and MongoDB databases.

---

## 🗄️ **MySQL Database Access**

### **Method 1: Using Docker Exec (Command Line)**

#### View MySQL Database and Tables:
```bash
docker exec -it internship-mysql mysql -uroot -proot -e "SHOW DATABASES;"
```

#### View the users table structure:
```bash
docker exec -it internship-mysql mysql -uroot -proot -e "DESCRIBE internship_db.users;"
```

#### View all users in the table:
```bash
docker exec -it internship-mysql mysql -uroot -proot -e "SELECT * FROM internship_db.users;"
```

#### Interactive MySQL Shell:
```bash
docker exec -it internship-mysql mysql -uroot -proot internship_db
```
Then you can run SQL queries:
```sql
SHOW TABLES;
SELECT * FROM users;
DESCRIBE users;
```

#### View users with formatted output:
```bash
docker exec -it internship-mysql mysql -uroot -proot -e "SELECT id, email, created_at FROM internship_db.users \G"
```

---

### **Method 2: Using MySQL Client (if installed locally)**

Connect from your local machine:
```bash
mysql -h localhost -P 3306 -u root -proot internship_db
```

**Note:** Password is `root`, database is `internship_db`, port is `3306`

---

### **Method 3: Using GUI Tools (Recommended)**

#### **Option A: phpMyAdmin (Web-based)**

1. Add phpMyAdmin service to `docker-compose.yml`:
```yaml
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: internship-phpmyadmin
    ports:
      - "8081:80"
    environment:
      PMA_HOST: internship-mysql
      PMA_PORT: 3306
      MYSQL_ROOT_PASSWORD: root
    networks:
      - app-network
```

2. Start it:
```bash
docker-compose up -d phpmyadmin
```

3. Access at: `http://localhost:8081`
   - Server: `internship-mysql`
   - Username: `root`
   - Password: `root`

#### **Option B: MySQL Workbench (Desktop App)**

1. Download MySQL Workbench: https://dev.mysql.com/downloads/workbench/
2. Create new connection:
   - Hostname: `localhost`
   - Port: `3306`
   - Username: `root`
   - Password: `root`
   - Default Schema: `internship_db`

#### **Option C: DBeaver (Universal Database Tool)**

1. Download DBeaver: https://dbeaver.io/download/
2. Create MySQL connection:
   - Host: `localhost`
   - Port: `3306`
   - Database: `internship_db`
   - Username: `root`
   - Password: `root`

---

## 🍃 **MongoDB Database Access**

### **Method 1: Using Docker Exec (Command Line)**

#### View all databases:
```bash
docker exec -it internship-mongodb mongosh --eval "show dbs"
```

#### Connect to internship_db:
```bash
docker exec -it internship-mongodb mongosh internship_db
```

Then run commands:
```javascript
show collections
db.profiles.find().pretty()
db.profiles.find().count()
```

#### View all profiles:
```bash
docker exec -it internship-mongodb mongosh internship_db --eval "db.profiles.find().pretty()"
```

#### Count documents in profiles collection:
```bash
docker exec -it internship-mongodb mongosh internship_db --eval "db.profiles.countDocuments()"
```

#### View specific profile by email:
```bash
docker exec -it internship-mongodb mongosh internship_db --eval "db.profiles.findOne({email: 'user@example.com'})"
```

#### View all profiles in JSON format:
```bash
docker exec -it internship-mongodb mongosh internship_db --eval "JSON.stringify(db.profiles.find().toArray(), null, 2)"
```

---

### **Method 2: Using MongoDB Compass (GUI Tool - Recommended)**

1. Download MongoDB Compass: https://www.mongodb.com/products/compass

2. Connect using:
   - Connection String: `mongodb://localhost:27017`
   - Or manually:
     - Host: `localhost`
     - Port: `27017`
     - Authentication: None (or add if configured)

3. Navigate to:
   - Database: `internship_db`
   - Collection: `profiles`

4. View documents, run queries, and see data structure visually!

---

### **Method 3: Using Mongo Express (Web-based)**

1. Add Mongo Express service to `docker-compose.yml`:
```yaml
  mongo-express:
    image: mongo-express
    container_name: internship-mongo-express
    ports:
      - "8082:8081"
    environment:
      ME_CONFIG_MONGODB_SERVER: internship-mongodb
      ME_CONFIG_MONGODB_PORT: 27017
      ME_CONFIG_BASICAUTH_USERNAME: admin
      ME_CONFIG_BASICAUTH_PASSWORD: admin
    networks:
      - app-network
    depends_on:
      - mongodb
```

2. Start it:
```bash
docker-compose up -d mongo-express
```

3. Access at: `http://localhost:8082`
   - Username: `admin`
   - Password: `admin`

---

## 🔍 **Quick Reference Commands**

### MySQL Quick Commands:
```bash
# Show databases
docker exec -it internship-mysql mysql -uroot -proot -e "SHOW DATABASES;"

# Show tables
docker exec -it internship-mysql mysql -uroot -proot -e "SHOW TABLES FROM internship_db;"

# Show all users
docker exec -it internship-mysql mysql -uroot -proot -e "SELECT * FROM internship_db.users;"

# Show user count
docker exec -it internship-mysql mysql -uroot -proot -e "SELECT COUNT(*) as total_users FROM internship_db.users;"

# Show users with created date
docker exec -it internship-mysql mysql -uroot -proot -e "SELECT id, email, created_at FROM internship_db.users;"
```

### MongoDB Quick Commands:
```bash
# Show databases
docker exec -it internship-mongodb mongosh --eval "show dbs"

# Show collections
docker exec -it internship-mongodb mongosh internship_db --eval "show collections"

# Show all profiles
docker exec -it internship-mongodb mongosh internship_db --eval "db.profiles.find().pretty()"

# Count profiles
docker exec -it internship-mongodb mongosh internship_db --eval "db.profiles.countDocuments()"

# Show profiles count by email
docker exec -it internship-mongodb mongosh internship_db --eval "db.profiles.find({}, {email: 1, name: 1}).pretty()"
```

---

## 📊 **Example: View Complete User Data**

### View user in MySQL:
```bash
docker exec -it internship-mysql mysql -uroot -proot -e "SELECT * FROM internship_db.users WHERE email='user@example.com';"
```

### View same user's profile in MongoDB:
```bash
docker exec -it internship-mongodb mongosh internship_db --eval "db.profiles.findOne({email: 'user@example.com'})"
```

---

## 🛠️ **Add GUI Tools to docker-compose.yml (Optional)**

If you want to add GUI tools permanently, here's a complete section to add:

```yaml
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: internship-phpmyadmin
    ports:
      - "8081:80"
    environment:
      PMA_HOST: internship-mysql
      PMA_PORT: 3306
      MYSQL_ROOT_PASSWORD: root
    networks:
      - app-network
    depends_on:
      - mysql

  mongo-express:
    image: mongo-express
    container_name: internship-mongo-express
    ports:
      - "8082:8081"
    environment:
      ME_CONFIG_MONGODB_SERVER: internship-mongodb
      ME_CONFIG_MONGODB_PORT: 27017
      ME_CONFIG_BASICAUTH_USERNAME: admin
      ME_CONFIG_BASICAUTH_PASSWORD: admin
    networks:
      - app-network
    depends_on:
      - mongodb
```

Then access:
- phpMyAdmin: http://localhost:8081
- Mongo Express: http://localhost:8082

---

## ✅ **Recommended Tools**

| Database | Best Tool | Why |
|----------|-----------|-----|
| **MySQL** | phpMyAdmin or MySQL Workbench | Easy web interface or powerful desktop tool |
| **MongoDB** | MongoDB Compass | Official GUI, excellent visualization |

---

**Note:** Make sure your Docker containers are running:
```bash
docker-compose ps
```

If not running, start them:
```bash
docker-compose up -d
```


