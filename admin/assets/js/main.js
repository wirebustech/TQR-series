// TQRS Admin Portal JS

document.addEventListener('DOMContentLoaded', function () {
  const adminLoginModal = new bootstrap.Modal(document.getElementById('adminLoginModal'));
  const adminLoginForm = document.getElementById('adminLoginForm');
  const adminLoginMsg = document.getElementById('adminLoginMsg');
  const adminContent = document.getElementById('adminContent');
  const adminInfo = document.getElementById('adminInfo');
  const adminLogout = document.getElementById('adminLogout');
  let adminUser = null;

  // Show login modal if not authenticated
  function requireAdmin() {
    const token = localStorage.getItem('admin_access_token');
    const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
    if (!token || !user || !(user.is_admin || user.role === 'admin')) {
      adminLoginModal.show();
      adminContent.innerHTML = '<div class="text-center text-muted">Please log in as admin.</div>';
      adminInfo.textContent = '';
      return false;
    } else {
      adminUser = user;
      adminInfo.textContent = user.name + ' (' + user.email + ')';
      return true;
    }
  }

  // Handle login
  if (adminLoginForm) {
    adminLoginForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      adminLoginMsg.textContent = '';
      const email = document.getElementById('adminEmail').value;
      const password = document.getElementById('adminPassword').value;
      try {
        const res = await fetch('/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password })
        });
        const data = await res.json();
        if (res.ok && data.access_token && (data.user.is_admin || data.user.role === 'admin')) {
          localStorage.setItem('admin_access_token', data.access_token);
          localStorage.setItem('admin_user', JSON.stringify(data.user));
          adminLoginModal.hide();
          adminUser = data.user;
          adminInfo.textContent = data.user.name + ' (' + data.user.email + ')';
          loadDashboard();
        } else {
          adminLoginMsg.textContent = 'Admin access required.';
        }
      } catch (err) {
        adminLoginMsg.textContent = 'Network error.';
      }
    });
  }

  // Logout
  if (adminLogout) {
    adminLogout.addEventListener('click', function(e) {
      e.preventDefault();
      localStorage.removeItem('admin_access_token');
      localStorage.removeItem('admin_user');
      location.reload();
    });
  }

  // Load dashboard content
  function loadDashboard() {
    if (!requireAdmin()) return;
    adminContent.innerHTML = `
      <h2>Welcome, ${adminUser.name}</h2>
      <div class="row mt-4">
        <div class="col-md-3">
          <div class="card text-bg-primary mb-3"><div class="card-body"><h5 class="card-title">Pages</h5><p class="card-text" id="statPages">...</p></div></div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-success mb-3"><div class="card-body"><h5 class="card-title">Blogs</h5><p class="card-text" id="statBlogs">...</p></div></div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-warning mb-3"><div class="card-body"><h5 class="card-title">Webinars</h5><p class="card-text" id="statWebinars">...</p></div></div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-info mb-3"><div class="card-body"><h5 class="card-title">Users</h5><p class="card-text" id="statUsers">...</p></div></div>
        </div>
      </div>
    `;
    // Fetch stats
    const token = localStorage.getItem('admin_access_token');
    fetch('/api/pages', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json()).then(data => {
        document.getElementById('statPages').textContent = Array.isArray(data) ? data.length : '...';
      });
    fetch('/api/blogs', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json()).then(data => {
        document.getElementById('statBlogs').textContent = Array.isArray(data) ? data.length : '...';
      });
    fetch('/api/users', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json()).then(data => {
        document.getElementById('statUsers').textContent = Array.isArray(data) ? data.length : '...';
      });
    fetch('/api/webinars/stats', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json()).then(data => {
        document.getElementById('statWebinars').textContent = data.data ? data.data.total : '...';
      });
  }

  // Pages Management
  const pagesTableArea = document.getElementById('pagesTableArea');
  const pagesTable = document.getElementById('pagesTable');
  const addPageBtn = document.getElementById('addPageBtn');
  const pageModal = new bootstrap.Modal(document.getElementById('pageModal'));
  const pageForm = document.getElementById('pageForm');
  const pageMsg = document.getElementById('pageMsg');

  function loadPages() {
    if (!requireAdmin()) return;
    pagesTableArea.style.display = '';
    adminContent.querySelectorAll('> *:not(#pagesTableArea)').forEach(el => el.style.display = 'none');
    const token = localStorage.getItem('admin_access_token');
    const tbody = pagesTable.querySelector('tbody');
    tbody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
    fetch('/api/pages', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json())
      .then(pages => {
        if (Array.isArray(pages) && pages.length > 0) {
          tbody.innerHTML = pages.map(page => `
            <tr>
              <td>${page.title}</td>
              <td>${page.slug}</td>
              <td>${page.is_published ? 'Published' : 'Draft'}</td>
              <td>
                <button class="btn btn-sm btn-outline-primary me-1 editPageBtn" data-id="${page.id}">Edit</button>
                <button class="btn btn-sm btn-outline-danger deletePageBtn" data-id="${page.id}">Delete</button>
              </td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="4" class="text-center">No pages found.</td></tr>';
        }
        // Attach edit/delete handlers
        tbody.querySelectorAll('.editPageBtn').forEach(btn => {
          btn.addEventListener('click', function() {
            editPage(this.getAttribute('data-id'));
          });
        });
        tbody.querySelectorAll('.deletePageBtn').forEach(btn => {
          btn.addEventListener('click', function() {
            deletePage(this.getAttribute('data-id'));
          });
        });
      });
  }

  // Show create page modal
  if (addPageBtn) {
    addPageBtn.addEventListener('click', function() {
      pageForm.reset();
      document.getElementById('pageId').value = '';
      pageMsg.textContent = '';
      pageModal.show();
    });
  }

  // Edit page
  function editPage(id) {
    const token = localStorage.getItem('admin_access_token');
    fetch(`/api/pages/${id}`, { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json())
      .then(page => {
        document.getElementById('pageId').value = page.id;
        document.getElementById('pageTitle').value = page.title;
        document.getElementById('pageSlug').value = page.slug;
        document.getElementById('pageContent').value = page.content || '';
        document.getElementById('pageStatus').value = page.is_published ? '1' : '0';
        pageMsg.textContent = '';
        pageModal.show();
      });
  }

  // Delete page
  function deletePage(id) {
    if (!confirm('Are you sure you want to delete this page?')) return;
    const token = localStorage.getItem('admin_access_token');
    fetch(`/api/pages/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': 'Bearer ' + token }
    })
      .then(res => {
        if (res.ok) loadPages();
        else alert('Failed to delete page.');
      });
  }

  // Save (create/edit) page
  if (pageForm) {
    pageForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      pageMsg.textContent = '';
      const btn = pageForm.querySelector('button[type="submit"]');
      btn.disabled = true;
      const id = document.getElementById('pageId').value;
      const title = document.getElementById('pageTitle').value;
      const slug = document.getElementById('pageSlug').value;
      const content = document.getElementById('pageContent').value;
      const is_published = document.getElementById('pageStatus').value === '1';
      const token = localStorage.getItem('admin_access_token');
      const payload = { title, slug, content, is_published };
      try {
        let res, data;
        if (id) {
          res = await fetch(`/api/pages/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify(payload)
          });
        } else {
          res = await fetch('/api/pages', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify(payload)
          });
        }
        data = await res.json();
        if (res.ok) {
          pageMsg.textContent = 'Saved!';
          pageMsg.className = 'text-success';
          setTimeout(() => pageModal.hide(), 1000);
          loadPages();
        } else {
          pageMsg.textContent = data.message || 'Save failed.';
          pageMsg.className = 'text-danger';
        }
      } catch (err) {
        pageMsg.textContent = 'Network error.';
        pageMsg.className = 'text-danger';
      }
      btn.disabled = false;
    });
  }

  // Blogs Management
  const blogsTableArea = document.getElementById('blogsTableArea');
  const blogsTable = document.getElementById('blogsTable');
  const addBlogBtn = document.getElementById('addBlogBtn');
  const blogModal = new bootstrap.Modal(document.getElementById('blogModal'));
  const blogForm = document.getElementById('blogForm');
  const blogMsg = document.getElementById('blogMsg');
  const blogCategory = document.getElementById('blogCategory');
  const blogTags = document.getElementById('blogTags');

  function loadBlogs() {
    if (!requireAdmin()) return;
    blogsTableArea.style.display = '';
    adminContent.querySelectorAll('> *:not(#blogsTableArea)').forEach(el => el.style.display = 'none');
    const token = localStorage.getItem('admin_access_token');
    const tbody = blogsTable.querySelector('tbody');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Loading...</td></tr>';
    fetch('/api/blogs', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json())
      .then(blogs => {
        if (Array.isArray(blogs) && blogs.length > 0) {
          tbody.innerHTML = blogs.map(blog => `
            <tr>
              <td>${blog.title}</td>
              <td>${blog.slug}</td>
              <td>${blog.category ? blog.category.name : ''}</td>
              <td>${blog.is_published ? 'Published' : 'Draft'}</td>
              <td>
                <button class="btn btn-sm btn-outline-primary me-1 editBlogBtn" data-id="${blog.id}">Edit</button>
                <button class="btn btn-sm btn-outline-danger deleteBlogBtn" data-id="${blog.id}">Delete</button>
              </td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="5" class="text-center">No blogs found.</td></tr>';
        }
        // Attach edit/delete handlers
        tbody.querySelectorAll('.editBlogBtn').forEach(btn => {
          btn.addEventListener('click', function() {
            editBlog(this.getAttribute('data-id'));
          });
        });
        tbody.querySelectorAll('.deleteBlogBtn').forEach(btn => {
          btn.addEventListener('click', function() {
            deleteBlog(this.getAttribute('data-id'));
          });
        });
      });
  }

  // Load categories and tags for blog modal
  function loadBlogCategoriesAndTags(selectedCategoryId, selectedTagIds) {
    const token = localStorage.getItem('admin_access_token');
    // Categories
    fetch('/api/blog-categories', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json())
      .then(categories => {
        blogCategory.innerHTML = '<option value="">-- Select Category --</option>' +
          (Array.isArray(categories) ? categories.map(cat => `<option value="${cat.id}"${selectedCategoryId == cat.id ? ' selected' : ''}>${cat.name}</option>`).join('') : '');
      });
    // Tags
    fetch('/api/blog-tags', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json())
      .then(tags => {
        blogTags.innerHTML = Array.isArray(tags) ? tags.map(tag => `<option value="${tag.id}"${selectedTagIds && selectedTagIds.includes(tag.id) ? ' selected' : ''}>${tag.name}</option>`).join('') : '';
      });
  }

  // Show create blog modal
  if (addBlogBtn) {
    addBlogBtn.addEventListener('click', function() {
      blogForm.reset();
      document.getElementById('blogId').value = '';
      blogMsg.textContent = '';
      loadBlogCategoriesAndTags();
      blogModal.show();
    });
  }

  // Edit blog
  function editBlog(id) {
    const token = localStorage.getItem('admin_access_token');
    fetch(`/api/blogs/${id}`, { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json())
      .then(blog => {
        document.getElementById('blogId').value = blog.id;
        document.getElementById('blogTitle').value = blog.title;
        document.getElementById('blogSlug').value = blog.slug;
        document.getElementById('blogContent').value = blog.content || '';
        document.getElementById('blogStatus').value = blog.is_published ? '1' : '0';
        loadBlogCategoriesAndTags(blog.category_id, blog.tags ? blog.tags.map(t => t.id) : []);
        blogMsg.textContent = '';
        blogModal.show();
      });
  }

  // Delete blog
  function deleteBlog(id) {
    if (!confirm('Are you sure you want to delete this blog?')) return;
    const token = localStorage.getItem('admin_access_token');
    fetch(`/api/blogs/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': 'Bearer ' + token }
    })
      .then(res => {
        if (res.ok) loadBlogs();
        else alert('Failed to delete blog.');
      });
  }

  // Save (create/edit) blog
  if (blogForm) {
    blogForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      blogMsg.textContent = '';
      const btn = blogForm.querySelector('button[type="submit"]');
      btn.disabled = true;
      const id = document.getElementById('blogId').value;
      const title = document.getElementById('blogTitle').value;
      const slug = document.getElementById('blogSlug').value;
      const content = document.getElementById('blogContent').value;
      const is_published = document.getElementById('blogStatus').value === '1';
      const category_id = blogCategory.value || null;
      const tagIds = Array.from(blogTags.selectedOptions).map(opt => opt.value);
      const token = localStorage.getItem('admin_access_token');
      const payload = { title, slug, content, is_published, category_id, tag_ids: tagIds };
      try {
        let res, data;
        if (id) {
          res = await fetch(`/api/blogs/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify(payload)
          });
        } else {
          res = await fetch('/api/blogs', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify(payload)
          });
        }
        data = await res.json();
        if (res.ok) {
          blogMsg.textContent = 'Saved!';
          blogMsg.className = 'text-success';
          setTimeout(() => blogModal.hide(), 1000);
          loadBlogs();
        } else {
          blogMsg.textContent = data.message || 'Save failed.';
          blogMsg.className = 'text-danger';
        }
      } catch (err) {
        blogMsg.textContent = 'Network error.';
        blogMsg.className = 'text-danger';
      }
      btn.disabled = false;
    });
  }

  // Sidebar navigation
  document.querySelectorAll('#sidebar [data-view]').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      if (!requireAdmin()) return;
      document.querySelectorAll('#sidebar .nav-link').forEach(l => l.classList.remove('active'));
      this.classList.add('active');
      const view = this.getAttribute('data-view');
      if (view === 'dashboard') loadDashboard();
      else if (view === 'pages') loadPages();
      else if (view === 'blogs') loadBlogs();
      else adminContent.innerHTML = `<div class='text-center text-muted'>${view.charAt(0).toUpperCase() + view.slice(1)} management coming soon...</div>`;
    });
  });

  // On load
  if (requireAdmin()) loadDashboard();
}); 