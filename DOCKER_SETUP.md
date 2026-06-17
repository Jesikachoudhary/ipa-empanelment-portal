# Docker Setup Guide for EmpanelmentAdmin

This guide will help you set up and run the EmpanelmentAdmin Laravel application using Docker and Docker Compose.

## Prerequisites

Before you begin, ensure you have installed:
- Docker: https://www.docker.com/products/docker-desktop
- Docker Compose: https://docs.docker.com/compose/install/

## Project Structure

```
empanelmentadmin/
├── Dockerfile                 # PHP-FPM image configuration
├── docker-compose.yml         # Multi-container orchestration
├── .dockerignore              # Files to exclude from Docker builds
├── .env.docker.example        # Example environment variables for Docker
└── docker/
    ├── nginx/
    │   ├── conf.d/
    │   │   └── app.conf       # Nginx configuration
    │   └── ssl/               # SSL certificates (optional)
    ├── mysql/
    │   ├── conf.d/
    │   │   └── mysql.cnf      # MySQL configuration
    │   └── data/              # MySQL data volume (created automatically)
    └── redis/
        └── data/              # Redis data volume (created automatically)
```

## Services Included

1. **app** (PHP-FPM)
   - Runs the Laravel application
   - PHP 8.2 with essential extensions
   - Port: 9000 (internal)

2. **nginx**
   - Web server serving your application
   - Port: 80 (HTTP), 443 (HTTPS)
   - Configuration: `docker/nginx/conf.d/app.conf`

3. **db** (MySQL 8.0)
   - Database server
   - Port: 3306
   - Volume: `docker/mysql/data`

4. **redis**
   - Cache and session store
   - Port: 6379
   - Volume: `docker/redis/data`

## Setup Instructions

### 1. Clone or Navigate to Project
```bash
cd empanelmentadmin
```

### 2. Create Environment File
Copy the example environment file and configure it:

```bash
cp .env.docker.example .env.docker
```

Edit `.env.docker` and set your values:
- `APP_KEY` - Generate a new Laravel app key
- `DB_PASSWORD` - Set a strong database password
- `APP_URL` - Set your application URL

### 3. Generate Laravel App Key
```bash
docker-compose run --rm app php artisan key:generate
```

### 4. Build and Start Containers
```bash
docker-compose up -d
```

This will:
- Build the PHP-FPM image from the Dockerfile
- Pull Nginx, MySQL, and Redis images
- Create and start all containers
- Create necessary volumes for data persistence

### 5. Run Database Migrations
```bash
docker-compose exec app php artisan migrate
```

### 6. Seed Database (Optional)
```bash
docker-compose exec app php artisan db:seed
```

### 7. Set Correct Permissions
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### 8. Access Your Application

Once everything is running:
- **Application**: http://localhost (or your configured APP_URL)
- **MySQL**: localhost:3306 (or your configured DB_PORT)
  - Username: `empanelment_user`
  - Password: Check your `.env.docker`
- **Redis**: localhost:6379 (or your configured REDIS_PORT)

## Common Commands

### View Running Containers
```bash
docker-compose ps
```

### View Logs
```bash
# All services
docker-compose logs

# Specific service
docker-compose logs app
docker-compose logs nginx
docker-compose logs db
docker-compose logs redis

# Follow logs (like tail -f)
docker-compose logs -f app
```

### Execute Commands in Container
```bash
# Artisan commands
docker-compose exec app php artisan tinker
docker-compose exec app php artisan make:migration migration_name

# Composer commands
docker-compose exec app composer require package/name

# NPM/Yarn (if needed)
docker-compose exec app npm install
```

### Stop Containers
```bash
# Graceful stop
docker-compose down

# Stop with volume removal (WARNING: loses database data)
docker-compose down -v

# Stop without removing containers
docker-compose stop
```

### Restart Containers
```bash
docker-compose restart

# Or restart specific service
docker-compose restart app
```

### Rebuild Images
```bash
# If you modify Dockerfile
docker-compose up -d --build
```

### Access Container Shell
```bash
# PHP container
docker-compose exec app sh

# MySQL container
docker-compose exec db bash

# Nginx container
docker-compose exec nginx sh
```

### Clear Laravel Caches
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## Database Management

### Connect to MySQL from Host
```bash
mysql -h 127.0.0.1 -P 3306 -u empanelment_user -p empanelment
```

### Backup Database
```bash
docker-compose exec db mysqldump -u empanelment_user -p empanelment > backup.sql
```

### Restore Database
```bash
docker-compose exec -T db mysql -u empanelment_user -p empanelment < backup.sql
```

### Access MySQL CLI in Container
```bash
docker-compose exec db mysql -u empanelment_user -p empanelment
```

## Redis Cache

### Check Redis
```bash
docker-compose exec redis redis-cli ping
```

### Monitor Redis
```bash
docker-compose exec redis redis-cli monitor
```

### Clear Redis Cache
```bash
docker-compose exec app php artisan cache:clear
# Or directly
docker-compose exec redis redis-cli FLUSHALL
```

## Nginx SSL Configuration

To enable HTTPS:

1. Place your SSL certificates in `docker/nginx/ssl/`
2. Uncomment the SSL section in `docker/nginx/conf.d/app.conf`
3. Restart Nginx:
```bash
docker-compose restart nginx
```

## Environment Variables

Key variables in `docker-compose.yml`:

| Variable | Default | Description |
|----------|---------|-------------|
| APP_PORT | 80 | HTTP port for the application |
| APP_SSL_PORT | 443 | HTTPS port for the application |
| DB_PORT | 3306 | MySQL port |
| REDIS_PORT | 6379 | Redis port |
| DB_DATABASE | empanelment | Database name |
| DB_USERNAME | empanelment_user | Database user |
| DB_PASSWORD | empanelment_password | Database password |

Override these in `.env.docker`:
```bash
APP_PORT=8080
DB_PORT=3307
```

## Troubleshooting

### Container Won't Start
```bash
# Check logs
docker-compose logs app

# Rebuild and restart
docker-compose down
docker-compose up -d --build
```

### Database Connection Error
```bash
# Verify database is running
docker-compose ps db

# Check MySQL logs
docker-compose logs db

# Restart database
docker-compose restart db
```

### Permission Issues
```bash
# Fix storage permissions
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### Redis Connection Error
```bash
# Check Redis status
docker-compose exec redis redis-cli ping

# Should return: PONG
```

### Port Already in Use
Change the port in `.env.docker` or `docker-compose.yml`:
```yaml
ports:
  - "8080:80"  # Changed from 80 to 8080
```

### Out of Disk Space
```bash
# Clean up Docker
docker system prune

# Remove unused volumes
docker volume prune

# Remove all stopped containers
docker container prune
```

## Performance Tips

1. **Use named volumes** for better performance on Mac/Windows
2. **Enable BuildKit** for faster builds: `export DOCKER_BUILDKIT=1`
3. **Use .dockerignore** to exclude unnecessary files
4. **Set appropriate memory limits** in docker-compose.yml
5. **Enable Docker cache** for faster rebuilds

## Production Deployment

For production, consider:
1. Using specific image versions instead of latest
2. Enabling SSL/TLS
3. Setting up proper logging and monitoring
4. Using a secrets manager for sensitive data
5. Implementing backup strategies
6. Setting resource limits
7. Using a reverse proxy (like Traefik)

## Additional Resources

- Docker Documentation: https://docs.docker.com/
- Docker Compose Documentation: https://docs.docker.com/compose/
- Laravel Docker Documentation: https://laravel.com/docs/deployment
- Nginx Documentation: https://nginx.org/en/docs/

## Support

For issues or questions about this Docker setup, check:
1. Container logs: `docker-compose logs`
2. Docker documentation
3. Laravel documentation
4. Nginx documentation

---

**Last Updated**: December 23, 2025
**Version**: 1.0
