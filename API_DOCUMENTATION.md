# Shan Travel API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication

### Register a new user
```
POST /api/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Login
```
POST /api/login
```

**Request Body:**
```json
{
    "email": "admin@shantravel.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@shantravel.com",
            "email_verified_at": "2025-07-30T00:00:00.000000Z",
            "created_at": "2025-07-30T00:00:00.000000Z",
            "updated_at": "2025-07-30T00:00:00.000000Z"
        },
        "token": "1|abc123..."
    }
}
```

### Get authenticated user
```
GET /api/user
```

**Headers:**
```
Authorization: Bearer {token}
```

### Logout
```
POST /api/logout
```

**Headers:**
```
Authorization: Bearer {token}
```

## Bookings

All booking endpoints require authentication. Include the Bearer token in the Authorization header.

### Get all bookings
```
GET /api/bookings
```

### Create a new booking
```
POST /api/bookings
```

**Request Body:**
```json
{
    "name": "John Doe",
    "pickup_location": "Airport Terminal 1",
    "drop_location": "Downtown Hotel",
    "start_date": "2025-08-15",
    "end_date": "2025-08-20",
    "number_of_persons": 2,
    "contact": "+1234567890",
    "notes": "Please provide a comfortable vehicle"
}
```

### Get a specific booking
```
GET /api/bookings/{id}
```

### Update a booking
```
PUT /api/bookings/{id}
```

**Request Body:**
```json
{
    "name": "John Doe Updated",
    "pickup_location": "Airport Terminal 2",
    "drop_location": "Downtown Hotel",
    "start_date": "2025-08-16",
    "end_date": "2025-08-21",
    "number_of_persons": 3,
    "contact": "+1234567890",
    "notes": "Updated notes"
}
```

### Delete a booking
```
DELETE /api/bookings/{id}
```

## Default Users

The following default users are created when you run the seeder:

1. **Admin User**
   - Email: `admin@shantravel.com`
   - Password: `password123`

2. **Test User**
   - Email: `test@shantravel.com`
   - Password: `password123`

## Booking Fields

- **name** (required): Customer name
- **pickup_location** (required): Pickup location
- **drop_location** (required): Drop-off location
- **start_date** (required): Start date (YYYY-MM-DD)
- **end_date** (required): End date (YYYY-MM-DD, must be after or equal to start_date)
- **booking_date** (auto-generated): Date when booking was created
- **number_of_persons** (required): Number of persons (minimum 1)
- **contact** (required): Contact information
- **notes** (optional): Additional notes

## Running the Application

1. Install dependencies:
   ```bash
   composer install
   ```

2. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Configure database in `.env` file

4. Run migrations:
   ```bash
   php artisan migrate
   ```

5. Seed the database:
   ```bash
   php artisan db:seed
   ```

6. Start the server:
   ```bash
   php artisan serve
   ```

The API will be available at `http://localhost:8000/api` 