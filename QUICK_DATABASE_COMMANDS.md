# Quick Database Viewing Commands

## 🗄️ **MySQL Database Commands**

### View All Databases:
```bash
docker exec internship-mysql mysql -uroot -proot -e "SHOW DATABASES;"
```

### View Tables in internship_db:
```bash
docker exec internship-mysql mysql -uroot -proot -e "SHOW TABLES FROM internship_db;"
```

### View All Users (Authentication Data):
```bash
docker exec internship-mysql mysql -uroot -proot -e "SELECT * FROM internship_db.users;"
```

### View Users with Formatted Output:
```bash
docker exec internship-mysql mysql -uroot -proot -e "SELECT id, email, created_at FROM internship_db.users \G"
```

### View Table Structure:
```bash
docker exec internship-mysql mysql -uroot -proot -e "DESCRIBE internship_db.users;"
```

### Count Total Users:
```bash
docker exec internship-mysql mysql -uroot -proot -e "SELECT COUNT(*) as total_users FROM internship_db.users;"
```

### View Specific User by Email:
```bash
docker exec internship-mysql mysql -uroot -proot -e "SELECT * FROM internship_db.users WHERE email='user@example.com';"
```

---

## 🍃 **MongoDB Database Commands**

### View All Databases:
```bash
docker exec internship-mongodb mongosh --eval "show dbs"
```

### View Collections in internship_db:
```bash
docker exec internship-mongodb mongosh internship_db --eval "show collections"
```

### View All Profiles (User Details):
```bash
docker exec internship-mongodb mongosh internship_db --eval "db.profiles.find().pretty()"
```

### Count Profiles:
```bash
docker exec internship-mongodb mongosh internship_db --eval "db.profiles.countDocuments()"
```

### View Specific Profile by Email:
```bash
docker exec internship-mongodb mongosh internship_db --eval "db.profiles.findOne({email: 'user@example.com'})"
```

### View Only Specific Fields:
```bash
docker exec internship-mongodb mongosh internship_db --eval "db.profiles.find({}, {email: 1, name: 1, age: 1}).pretty()"
```

---

## 📊 **View Complete User Data (MySQL + MongoDB)**

### Step 1: Get user from MySQL:
```bash
docker exec internship-mysql mysql -uroot -proot -e "SELECT * FROM internship_db.users WHERE email='juvanfk@gmail.com';"
```

### Step 2: Get profile from MongoDB:
```bash
docker exec internship-mongodb mongosh internship_db --eval "db.profiles.findOne({email: 'juvanfk@gmail.com'})"
```

---

## 🖥️ **Using GUI Tools (Recommended)**

### For MySQL - Use phpMyAdmin:
1. Add to docker-compose.yml (see VIEW_DATABASES.md)
2. Access at: http://localhost:8081
3. Login: root / root

### For MongoDB - Use MongoDB Compass:
1. Download: https://www.mongodb.com/products/compass
2. Connect to: `mongodb://localhost:27017`
3. Browse: `internship_db` → `profiles` collection

---

## ✅ **Current Database Status**

Based on the current data:

**MySQL (users table):**
- 1 user registered: `juvanfk@gmail.com`
- Password hash stored (BCRYPT encrypted)

**MongoDB (profiles collection):**
- 1 profile document
- Contains: name, age, dob, contact for the same user

---

## 🔍 **Interactive Shell Access**

### MySQL Interactive Shell:
```bash
docker exec -it internship-mysql mysql -uroot -proot internship_db
```
Then you can run SQL commands interactively:
```sql
SELECT * FROM users;
SHOW TABLES;
```

### MongoDB Interactive Shell:
```bash
docker exec -it internship-mongodb mongosh internship_db
```
Then you can run MongoDB commands:
```javascript
db.profiles.find().pretty()
db.profiles.countDocuments()
show collections
```

---

**Note:** Replace `-it` with just `-i` if you get TTY errors in PowerShell, or use the commands above without interactive flags.


