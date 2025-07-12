// TQRS Frontend Main JS

document.addEventListener('DOMContentLoaded', function () {
  // Newsletter signup
  const newsletterForm = document.getElementById('newsletterForm');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const email = document.getElementById('newsletterEmail').value;
      const msg = document.getElementById('newsletterMsg');
      msg.textContent = '';
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
    });
  }

  // Load featured research/blogs
  const featuredResearch = document.getElementById('featuredResearch');
  if (featuredResearch) {
    fetch('/api/blogs')
      .then(res => res.json())
      .then(blogs => {
        if (Array.isArray(blogs) && blogs.length > 0) {
          featuredResearch.innerHTML = blogs.slice(0, 3).map(blog => `
            <div class="col-md-4 mb-4">
              <div class="card h-100 shadow-sm">
                <div class="card-body">
                  <h5 class="card-title">${blog.title}</h5>
                  <p class="card-text">${blog.excerpt || ''}</p>
                  <a href="#" class="btn btn-outline-primary btn-sm">Read More</a>
                </div>
              </div>
            </div>
          `).join('');
        } else {
          featuredResearch.innerHTML = '<p class="text-center">No featured research available yet.</p>';
        }
      })
      .catch(() => {
        featuredResearch.innerHTML = '<p class="text-center text-danger">Failed to load research highlights.</p>';
      });
  }
}); 