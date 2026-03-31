# 1. Create a task
curl -X POST http://127.0.0.1:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Fix bug","due_date":"2026-04-05","priority":"high"}'

# 2. List all tasks
curl http://127.0.0.1:8000/api/tasks

# 2b. Filter by status
curl "http://127.0.0.1:8000/api/tasks?status=pending"

# 3. Advance task status (e.g. task ID 1)
curl -X PATCH http://127.0.0.1:8000/api/tasks/1/status

# 4. Delete a task (must be "done")
curl -X DELETE http://127.0.0.1:8000/api/tasks/3

# 5. Daily report
curl "http://127.0.0.1:8000/api/tasks/report?date=2026-03-31"

Used Postman to do my APi testing-> can confirm all the models are working perfectly 