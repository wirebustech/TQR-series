# TQRS Backend API How-To

This guide provides an overview of the available API endpoints for The Qualitative Research Series (TQRS) backend, including authentication, resource management, and example requests.

---

## 1. Authentication

All protected endpoints require authentication via Laravel Sanctum. Obtain a Bearer token by logging in, and include it in the `Authorization` header for subsequent requests.

### Register
```
POST /api/register
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
```
POST /api/login
{
  "email": "jane@example.com",
  "password": "password123"
}
```
**Response:**
```
{
  "access_token": "...",
  "token_type": "Bearer",
  "user": { ... }
}
```

### Logout
```
POST /api/logout
Authorization: Bearer {access_token}
```

### Get Authenticated User
```
GET /api/user
Authorization: Bearer {access_token}
```

---

## 2. Core Resource Endpoints

All resource endpoints are available as RESTful API resources. Replace `{resource}` and `{id}` as needed.

### Standard Endpoints
- `GET    /api/{resource}`         # List all
- `GET    /api/{resource}/{id}`   # Get one
- `POST   /api/{resource}`        # Create
- `PUT    /api/{resource}/{id}`   # Update
- `DELETE /api/{resource}/{id}`   # Delete

### Available Resources
- pages
- sections
- blogs
- media-library
- social-media-links
- affiliate-partners
- external-videos
- webinar-courses
- beta-signups
- research-contributions
- support-donations
- newsletter-subscriptions

**Example: Create a Page**
```
POST /api/pages
Authorization: Bearer {access_token}
{
  "title": "About TQRS",
  "slug": "about-tqrs",
  "description": "About the Qualitative Research Series...",
  "content": "...",
  "is_published": true
}
```

**Example: List Blogs**
```
GET /api/blogs
Authorization: Bearer {access_token}
```

---

## 3. Notes
- All endpoints (except register/login) require a valid Bearer token.
- Use `PUT` for updates; `PATCH` is also supported.
- Validation errors return 422 with details.
- For file uploads, use `media-library` and send files as multipart/form-data.
- For relationships (e.g., blog tags), send arrays of IDs as documented in the BlogController.

---

## 4. Testing
- Use Postman, Insomnia, or curl to test endpoints.
- Always include the `Authorization: Bearer {access_token}` header for protected routes.

---

## 5. Further Steps
- See the README for project setup and deployment.
- Extend the API with new features as needed.
- Integrate with the frontend and admin portal for a complete platform.

---

**Built for The Qualitative Research Series (TQRS)** 