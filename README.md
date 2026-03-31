Used Postman to do my APi testing-> can confirm all the models are working perfectly 

# Task Management API

A Laravel REST API for managing tasks with priority and status tracking.

## Requirements
- PHP 8.2+
- Composer
- MySQL

## Local Setup
```bash
git clone https://github.com/YOUR_USERNAME/task_Apiapi.git
cd task-management-api

composer install

cp .env.example .env
php artisan key:generate

# Update .env with your MySQL credentials, then:
php artisan migrate
php artisan db:seed --class=TaskSeeder

php artisan serve
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/tasks` | Create a task |
| GET | `/api/tasks` | List all tasks |
| PATCH | `/api/tasks/{id}/status` | Update task status |
| DELETE | `/api/tasks/{id}` | Delete a task |
| GET | `/api/tasks/report?date=YYYY-MM-DD` | Daily report |

## Example Requests

**Create Task**
```json
POST /api/tasks
{
    "title": "Fix login bug",
    "due_date": "2026-04-05",
    "priority": "high"
}
```

**List Tasks**
```
GET /api/tasks
GET /api/tasks?status=pending
```

**Update Status**
```
PATCH /api/tasks/1/status
```

**Delete Task**
```
DELETE /api/tasks/1
```

**Daily Report**
```
GET /api/tasks/report?date=2026-04-01
```

## Deployment
Deployed on Railway with MySQL. See live API at: `YOUR_RAILWAY_URL`