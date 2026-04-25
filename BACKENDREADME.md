docker Run  with Ubuntu=   docker run -p 8000:8000 --name inventory-app inventory-backend

# Run the PostgreSQL Database Container
docker run -d \
  --name db \
  --network inventory-net \
  -e POSTGRES_DB=inventory_db \
  -e POSTGRES_USER=postgres \
  -e POSTGRES_PASSWORD=root \
  -p 5433:5432 \
  postgres:16-alpine


# Build the Backend Docker Image
  docker build -t inventory-backend .

# Run the Backend Container

docker run -d \
  --name inventory-api \
  --network inventory-net \
  -v "$PWD":/var/www/html \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST=db \
  -e DB_PORT=5432 \
  -e DB_DATABASE=inventory_db \
  -e DB_USERNAME=postgres \
  -e DB_PASSWORD=root \
  -p 8000:8000 \
  inventory-backend


# Database Migrations & Seeding

# Run migrations
docker exec -it inventory-api php artisan migrate:fresh

# Run the admin seeder
docker exec -it inventory-api php artisan db:seed --class=AdminUserSeeder


# Test the API
http://localhost:8000

Email: admin@inventory.com

Password: admin123


# Ubuntu Postman Open 
/opt/Postman/Postman &


# Models and  Migrations
docker exec -it inventory-api php artisan make:model Cupboard -m

docker exec -it inventory-api php artisan make:model Place -m
