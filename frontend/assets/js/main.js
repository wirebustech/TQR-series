// TQRS Frontend Main JS

document.addEventListener('DOMContentLoaded', function () {
  // Spinner utility
  function showSpinner(target) {
    target.innerHTML = '<div class="spinner"><div class="spinner-border text-primary" role="status" aria-label="Loading"></div></div>';
  }

  // Load featured research/blogs
  const featuredResearch = document.getElementById('featuredResearch');
  const blogModal = new bootstrap.Modal(document.getElementById('blogModal'));
  const blogModalBody = document.getElementById('blogModalBody');
  if (featuredResearch) {
    showSpinner(featuredResearch);
    fetch('/api/blogs')
      .then(res => res.json())
      .then(blogs => {
        if (Array.isArray(blogs) && blogs.length > 0) {
          featuredResearch.innerHTML = blogs.slice(0, 3).map(blog => `
            <div class="col-md-4 mb-4">
              <div class="card h-100 shadow-sm blog-card" data-blog-id="${blog.id}">
                <div class="card-body">
                  <h5 class="card-title">${blog.title}</h5>
                  <p class="card-text">${blog.excerpt || ''}</p>
                  <a href="#" class="btn btn-outline-primary btn-sm read-more" data-blog-id="${blog.id}">Read More</a>
                </div>
              </div>
            </div>
          `).join('');

          // Add click listeners for blog cards and read more buttons
          featuredResearch.querySelectorAll('.read-more, .blog-card').forEach(el => {
            el.addEventListener('click', function(e) {
              e.preventDefault();
              const blogId = this.getAttribute('data-blog-id');
              if (blogId) {
                fetch(`/api/blogs/${blogId}`)
                  .then(res => res.json())
                  .then(blog => {
                    blogModalBody.innerHTML = `
                      <h3>${blog.title}</h3>
                      <div class="mb-2 text-muted">${blog.published_at ? new Date(blog.published_at).toLocaleDateString() : ''}</div>
                      <div class="mb-3">${blog.excerpt || ''}</div>
                      <div>${blog.content || ''}</div>
                    `;
                    blogModal.show();
                  });
              }
            });
          });
        } else {
          featuredResearch.innerHTML = '<p class="text-center">No featured research available yet.</p>';
        }
      })
      .catch(() => {
        featuredResearch.innerHTML = '<p class="text-center text-danger">Failed to load research highlights.</p>';
      });
  }

  // Load webinars
  const webinarList = document.getElementById('webinarList');
  const webinarModal = new bootstrap.Modal(document.getElementById('webinarModal'));
  const webinarModalBody = document.getElementById('webinarModalBody');
  if (webinarList) {
    showSpinner(webinarList);
    fetch('/api/webinar-courses')
      .then(res => res.json())
      .then(webinars => {
        if (Array.isArray(webinars) && webinars.length > 0) {
          webinarList.innerHTML = webinars.slice(0, 3).map(webinar => `
            <div class="col-md-4 mb-4">
              <div class="card h-100 shadow-sm webinar-card" data-webinar-id="${webinar.id}">
                <div class="card-body">
                  <h5 class="card-title">${webinar.title}</h5>
                  <p class="card-text">${webinar.description ? webinar.description.substring(0, 80) + '...' : ''}</p>
                  <div class="mb-2 text-muted">${webinar.start_time ? new Date(webinar.start_time).toLocaleString() : ''}</div>
                  <a href="#" class="btn btn-outline-primary btn-sm webinar-details" data-webinar-id="${webinar.id}">Details</a>
                </div>
              </div>
            </div>
          `).join('');

          // Add click listeners for webinar cards and details buttons
          webinarList.querySelectorAll('.webinar-details, .webinar-card').forEach(el => {
            el.addEventListener('click', function(e) {
              e.preventDefault();
              const webinarId = this.getAttribute('data-webinar-id');
              if (webinarId) {
                fetch(`/api/webinar-courses/${webinarId}`)
                  .then(res => res.json())
                  .then(webinar => {
                    webinarModalBody.innerHTML = `
                      <h3>${webinar.title}</h3>
                      <div class="mb-2 text-muted">${webinar.start_time ? new Date(webinar.start_time).toLocaleString() : ''}</div>
                      <div class="mb-3">${webinar.description || ''}</div>
                      ${webinar.video_url ? `<div class='mb-2'><a href='${webinar.video_url}' target='_blank' class='btn btn-primary'>Watch Video</a></div>` : ''}
                    `;
                    webinarModal.show();
                  });
              }
            });
          });
        } else {
          webinarList.innerHTML = '<p class="text-center">No upcoming webinars at this time.</p>';
        }
      })
      .catch(() => {
        webinarList.innerHTML = '<p class="text-center text-danger">Failed to load webinars.</p>';
      });
  }

  // Newsletter signup
  const newsletterForm = document.getElementById('newsletterForm');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      const email = document.getElementById('newsletterEmail').value;
      const msg = document.getElementById('newsletterMsg');
      msg.textContent = '';
      const btn = newsletterForm.querySelector('button[type="submit"]');
      btn.disabled = true;
      try {
        const res = await fetch('/api/newsletter-subscriptions', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email })
        });
        if (res.ok) {
          msg.textContent = 'Thank you for subscribing!';
          msg.className = 'text-success';
          newsletterForm.reset();
        } else {
          const data = await res.json();
          msg.textContent = data.message || 'Subscription failed.';
          msg.className = 'text-danger';
        }
      } catch (err) {
        msg.textContent = 'Network error.';
        msg.className = 'text-danger';
      }
      btn.disabled = false;
    });
  }

  // Auth modal logic
  const loginBtn = document.getElementById('loginBtn');
  const authModal = new bootstrap.Modal(document.getElementById('authModal'));
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const showRegister = document.getElementById('showRegister');
  const loginMsg = document.getElementById('loginMsg');
  const registerMsg = document.getElementById('registerMsg');
  const navbar = document.querySelector('.navbar-nav');

  // Show/hide contribution button for logged-in users
  const contributeNav = document.getElementById('contributeNav');
  const contributeBtn = document.getElementById('contributeBtn');
  const contributionModal = new bootstrap.Modal(document.getElementById('contributionModal'));
  const contributionForm = document.getElementById('contributionForm');
  const contributionMsg = document.getElementById('contributionMsg');
  const userContributions = document.getElementById('userContributions');

  function setUserNav(user) {
    const profileNav = document.getElementById('profileNav');
    if (user) {
      loginBtn.style.display = 'none';
      profileNav.style.display = '';
      if (contributeNav) contributeNav.style.display = '';
      if (!document.getElementById('logoutBtn')) {
        const li = document.createElement('li');
        li.className = 'nav-item';
        li.innerHTML = '<a class="nav-link" href="#" id="logoutBtn">Logout</a>';
        navbar.appendChild(li);
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
          e.preventDefault();
          localStorage.removeItem('access_token');
          localStorage.removeItem('user');
          location.reload();
        });
      }
    } else {
      loginBtn.style.display = '';
      profileNav.style.display = 'none';
      if (contributeNav) contributeNav.style.display = 'none';
      const logoutBtn = document.getElementById('logoutBtn');
      if (logoutBtn) logoutBtn.parentElement.remove();
    }
  }

  // Show modal on login click
  if (loginBtn) {
    loginBtn.addEventListener('click', function(e) {
      e.preventDefault();
      loginForm.style.display = '';
      registerForm.style.display = 'none';
      loginMsg.textContent = '';
      registerMsg.textContent = '';
      authModal.show();
    });
  }

  // Switch to register form
  if (showRegister) {
    showRegister.addEventListener('click', function() {
      loginForm.style.display = 'none';
      registerForm.style.display = '';
      loginMsg.textContent = '';
      registerMsg.textContent = '';
      document.getElementById('authModalLabel').textContent = 'Register for TQRS';
    });
  }

  // Login form submit
  if (loginForm) {
    loginForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      loginMsg.textContent = '';
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;
      try {
        const res = await fetch('/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password })
        });
        const data = await res.json();
        if (res.ok && data.access_token) {
          localStorage.setItem('access_token', data.access_token);
          localStorage.setItem('user', JSON.stringify(data.user));
          setUserNav(data.user);
          authModal.hide();
        } else {
          loginMsg.textContent = data.message || 'Login failed.';
        }
      } catch (err) {
        loginMsg.textContent = 'Network error.';
      }
    });
  }

  // Register form submit
  if (registerForm) {
    registerForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      registerMsg.textContent = '';
      const name = document.getElementById('registerName').value;
      const email = document.getElementById('registerEmail').value;
      const password = document.getElementById('registerPassword').value;
      const password_confirmation = document.getElementById('registerPassword2').value;
      try {
        const res = await fetch('/api/register', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, email, password, password_confirmation })
        });
        const data = await res.json();
        if (res.ok && data.access_token) {
          localStorage.setItem('access_token', data.access_token);
          localStorage.setItem('user', JSON.stringify(data.user));
          setUserNav(data.user);
          authModal.hide();
        } else {
          registerMsg.textContent = data.message || 'Registration failed.';
        }
      } catch (err) {
        registerMsg.textContent = 'Network error.';
      }
    });
  }

  // Show contribution modal
  if (contributeBtn) {
    contributeBtn.addEventListener('click', function(e) {
      e.preventDefault();
      contributionForm.reset();
      contributionMsg.textContent = '';
      contributionMsg.className = '';
      contributionModal.show();
    });
  }

  // Submit contribution
  if (contributionForm) {
    contributionForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      contributionMsg.textContent = '';
      const btn = contributionForm.querySelector('button[type="submit"]');
      btn.disabled = true;
      const title = document.getElementById('contributionTitle').value;
      const description = document.getElementById('contributionDescription').value;
      const fileInput = document.getElementById('contributionFile');
      const formData = new FormData();
      formData.append('title', title);
      formData.append('description', description);
      if (fileInput.files[0]) formData.append('file_url', fileInput.files[0]);
      // Attach token
      const token = localStorage.getItem('access_token');
      try {
        const res = await fetch('/api/research-contributions', {
          method: 'POST',
          headers: { 'Authorization': 'Bearer ' + token },
          body: formData
        });
        if (res.ok) {
          contributionMsg.textContent = 'Contribution submitted!';
          contributionMsg.className = 'text-success';
          contributionForm.reset();
          setTimeout(() => contributionModal.hide(), 1200);
          loadUserContributions();
        } else {
          const data = await res.json();
          contributionMsg.textContent = data.message || 'Submission failed.';
          contributionMsg.className = 'text-danger';
        }
      } catch (err) {
        contributionMsg.textContent = 'Network error.';
        contributionMsg.className = 'text-danger';
      }
      btn.disabled = false;
    });
  }

  // Load user's contributions in dashboard
  function loadUserContributions() {
    if (!userContributions) return;
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const token = localStorage.getItem('access_token');
    if (!user.id || !token) {
      userContributions.innerHTML = '';
      return;
    }
    showSpinner(userContributions);
    fetch(`/api/research-contributions?user_id=${user.id}`, {
      headers: { 'Authorization': 'Bearer ' + token }
    })
      .then(res => res.json())
      .then(contributions => {
        if (Array.isArray(contributions) && contributions.length > 0) {
          userContributions.innerHTML = '<h6>Your Contributions</h6>' + contributions.map(c => `
            <div class="border rounded p-2 mb-2">
              <div><strong>${c.title}</strong></div>
              <div class="small text-muted">${c.status || 'pending'}</div>
              <div>${c.description || ''}</div>
              ${c.file_url ? `<a href="${c.file_url}" target="_blank">Download File</a>` : ''}
            </div>
          `).join('');
        } else {
          userContributions.innerHTML = '<h6>Your Contributions</h6><div class="text-muted">No contributions yet.</div>';
        }
      })
      .catch(() => {
        userContributions.innerHTML = '<div class="text-danger">Failed to load your contributions.</div>';
      });
  }

  // Profile modal logic
  const profileBtn = document.getElementById('profileBtn');
  const profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
  const profileInfo = document.getElementById('profileInfo');
  const logoutBtn2 = document.getElementById('logoutBtn2');

  if (profileBtn) {
    profileBtn.addEventListener('click', function(e) {
      e.preventDefault();
      const user = JSON.parse(localStorage.getItem('user') || '{}');
      profileInfo.innerHTML = `
        <div class="mb-2"><strong>Name:</strong> ${user.name || ''}</div>
        <div class="mb-2"><strong>Email:</strong> ${user.email || ''}</div>
      `;
      profileModal.show();
      loadUserContributions();
    });
  }
  if (logoutBtn2) {
    logoutBtn2.addEventListener('click', function() {
      localStorage.removeItem('access_token');
      localStorage.removeItem('user');
      location.reload();
    });
  }

  // Contact form
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      const name = document.getElementById('contactName').value;
      const email = document.getElementById('contactEmail').value;
      const message = document.getElementById('contactMessage').value;
      const msg = document.getElementById('contactMsg');
      msg.textContent = '';
      const btn = contactForm.querySelector('button[type="submit"]');
      btn.disabled = true;
      try {
        // You can POST to a backend endpoint like /api/contact or /api/support-donations (if adapted)
        const res = await fetch('/api/support-donations', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ donor_name: name, donor_email: email, message })
        });
        if (res.ok) {
          msg.textContent = 'Thank you for contacting us! We will get back to you soon.';
          msg.className = 'text-success';
          contactForm.reset();
        } else {
          const data = await res.json();
          msg.textContent = data.message || 'Message failed to send.';
          msg.className = 'text-danger';
        }
      } catch (err) {
        msg.textContent = 'Network error.';
        msg.className = 'text-danger';
      }
      btn.disabled = false;
    });
  }

  // On load, set user nav if logged in
  const user = localStorage.getItem('user');
  setUserNav(user ? JSON.parse(user) : null);
}); 