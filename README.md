# Internship Project - Docker Setup

A PHP web application using Docker with MySQL, MongoDB, and Redis.

## Prerequisites

- [Docker](https://www.docker.com/get-started) installed
- [Docker Compose](https://docs.docker.com/compose/install/) installed

## Quick Start

1. **Clone/Navigate to the project directory**
   ```bash
   cd internship-project-docker
   ```

2. **Start all services using Docker Compose**
   ```bash
   docker-compose up --build
   ```
   
   The `--build` flag ensures the Docker image is built with all PHP extensions.

3. **Access the application**
   - Web application: http://localhost:8080
   - MySQL: localhost:3306
   - MongoDB: localhost:27017
   - Redis: localhost:6379

## Services

The project includes 4 services:

- **web** (PHP 8.1 + Apache): Main web server on port 8080
- **mysql**: MySQL 8.0 database on port 3306
- **mongodb**: MongoDB 7.0 database on port 27017
- **redis**: Redis cache on port 6379

## Running in Background

To run the containers in detached mode (background):

```bash
docker-compose up -d --build
```

## Stopping the Project

To stop all services:

```bash
docker-compose down
```

To stop and remove volumes (this will delete database data):

```bash
docker-compose down -v
```

## Viewing Logs

To view logs from all services:

```bash
docker-compose logs -f
```

To view logs from a specific service:

```bash
docker-compose logs -f web
docker-compose logs -f mysql
docker-compose logs -f mongodb
docker-compose logs -f redis
```

## Database Setup

The MySQL database is automatically initialized with:
- Database: `internship_db`
- Root password: `root`
- Users table for authentication

MongoDB will automatically create the `internship_db` database and `profiles` collection when first used.

## Project Structure

```
.
├── app/                    # Application code
│   ├── php/               # PHP backend files
│   ├── *.html            # Frontend HTML files
│   └── composer.json      # PHP dependencies
├── docker-compose.yml     # Docker services configuration
├── Dockerfile            # PHP web server image
└── init.sql              # MySQL initialization script
```

## Troubleshooting

### Port Already in Use
If port 8080, 3306, 27017, or 6379 is already in use, you can modify the port mappings in `docker-compose.yml`.

### Container Build Issues
If you encounter build issues, try:
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up
```

### Database Connection Issues
Ensure all services are healthy before accessing the application:
```bash
docker-compose ps
```

All services should show as "healthy" or "running".

## Additional Documentation

- `DATABASE_USAGE.md` - Detailed database architecture and usage
- `QUICK_DATABASE_COMMANDS.md` - Quick database commands reference
- `VIEW_DATABASES.md` - How to view database contents

