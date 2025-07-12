# How to View TQRS API Documentation (Swagger/OpenAPI)

This guide explains how to view and interact with the generated OpenAPI (Swagger) documentation for The Qualitative Research Series (TQRS) backend API.

---

## 1. Where is the OpenAPI File?

The OpenAPI (Swagger) JSON file is generated at:
```
backend/public/openapi.json
```

---

## 2. View with Swagger Editor (Online)

1. Go to [https://editor.swagger.io/](https://editor.swagger.io/)
2. Click on **File > Import File**
3. Select and upload `openapi.json` from your `backend/public` directory
4. Explore and interact with your API documentation in the browser

---

## 3. View with Swagger UI (Locally)

You can serve Swagger UI locally to view your API docs:

### Option A: Use Docker (Recommended)
1. Run the following command from your project root:
   ```bash
   docker run -p 8080:8080 -v $(pwd)/backend/public/openapi.json:/usr/share/nginx/html/openapi.json swaggerapi/swagger-ui
   ```
2. Open [http://localhost:8080](http://localhost:8080) in your browser
3. In the Swagger UI, set the URL to `/openapi.json` and click **Explore**

### Option B: Download Swagger UI Static Files
1. Download the latest [Swagger UI release](https://github.com/swagger-api/swagger-ui/releases)
2. Extract the files to a directory (e.g., `swagger-ui/`)
3. Copy `backend/public/openapi.json` into the same directory
4. Open `index.html` in your browser
5. Set the URL to `openapi.json` and click **Explore**

---

## 4. Regenerating the OpenAPI File

Whenever you update your API annotations, regenerate the file:
```bash
php backend/vendor/zircote/swagger-php/bin/openapi --bootstrap backend/app/Http/Controllers/Api/OpenApi.php backend/app/Http/Controllers/Api > backend/public/openapi.json
```

---

## 5. Tips
- You can annotate your controllers and models with `@OA` tags for richer documentation.
- The OpenAPI file can be used with other tools like Redoc, Postman, or Insomnia.
- For production, you can serve `openapi.json` from your public directory for external tools to consume.

---

**Built for The Qualitative Research Series (TQRS)** 