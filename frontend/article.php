<?php
session_start();

$pageTitle = 'Article - TQRS';
$pageDescription = 'Read detailed qualitative research articles with expert insights and methodologies.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'readTime' => 'min read',
    'published' => 'Published',
    'author' => 'Author',
    'category' => 'Category',
    'tags' => 'Tags',
    'share' => 'Share',
    'relatedArticles' => 'Related Articles',
    'comments' => 'Comments',
    'leaveComment' => 'Leave a Comment',
    'commentPlaceholder' => 'Share your thoughts...',
    'postComment' => 'Post Comment',
    'reply' => 'Reply',
    'like' => 'Like',
    'liked' => 'Liked',
    'bookmark' => 'Bookmark',
    'bookmarked' => 'Bookmarked',
    'download' => 'Download PDF',
    'print' => 'Print Article',
    'email' => 'Email Article',
    'followAuthor' => 'Follow Author',
    'authorBio' => 'Author Biography',
    'moreFromAuthor' => 'More from this Author',
    'subscribe' => 'Subscribe to Blog',
    'subscribeText' => 'Get the latest articles delivered to your inbox',
    'emailPlaceholder' => 'Enter your email address',
    'subscribeBtn' => 'Subscribe',
    'loginToComment' => 'Please log in to leave a comment',
    'loginBtn' => 'Log In',
    'registerBtn' => 'Register',
    'noComments' => 'No comments yet. Be the first to comment!',
    'loadMoreComments' => 'Load More Comments',
    'reportComment' => 'Report',
    'editComment' => 'Edit',
    'deleteComment' => 'Delete'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Get article ID from URL
$articleId = intval($_GET['id'] ?? 1);

// Mock article data - in real app, fetch from database
$articles = [
    1 => [
        'id' => 1,
        'title' => 'Understanding Grounded Theory: A Comprehensive Guide',
        'excerpt' => 'Explore the fundamentals of grounded theory methodology and learn how to apply it effectively in your qualitative research projects.',
        'content' => '
            <h2>Introduction to Grounded Theory</h2>
            <p>Grounded theory is a systematic methodology that has been widely used in qualitative research since its development by Barney Glaser and Anselm Strauss in 1967. This approach allows researchers to develop theories that are grounded in data rather than preconceived hypotheses.</p>
            
            <h3>Key Principles of Grounded Theory</h3>
            <p>The methodology is based on several core principles:</p>
            <ul>
                <li><strong>Theoretical Sampling:</strong> Data collection is guided by emerging theoretical concepts</li>
                <li><strong>Constant Comparative Analysis:</strong> Continuous comparison of data to develop categories</li>
                <li><strong>Coding:</strong> Systematic process of categorizing and conceptualizing data</li>
                <li><strong>Memo Writing:</strong> Documentation of theoretical insights and ideas</li>
            </ul>
            
            <h3>The Research Process</h3>
            <p>Grounded theory research typically follows these stages:</p>
            <ol>
                <li><strong>Data Collection:</strong> Begin with open-ended interviews or observations</li>
                <li><strong>Initial Coding:</strong> Line-by-line analysis to identify concepts</li>
                <li><strong>Focused Coding:</strong> Develop categories from initial codes</li>
                <li><strong>Theoretical Coding:</strong> Integrate categories into a theoretical framework</li>
                <li><strong>Theory Development:</strong> Write the emerging theory</li>
            </ol>
            
            <h3>Practical Applications</h3>
            <p>Grounded theory has been successfully applied across various disciplines:</p>
            <ul>
                <li>Healthcare research and patient experiences</li>
                <li>Educational studies and learning processes</li>
                <li>Organizational behavior and management</li>
                <li>Social work and community development</li>
            </ul>
            
            <h3>Challenges and Considerations</h3>
            <p>While grounded theory offers valuable insights, researchers should be aware of:</p>
            <ul>
                <li>The time-intensive nature of the methodology</li>
                <li>The need for theoretical sensitivity</li>
                <li>Potential for researcher bias</li>
                <li>Complexity of the coding process</li>
            </ul>
            
            <h3>Conclusion</h3>
            <p>Grounded theory remains a powerful tool for qualitative researchers seeking to develop theories that emerge from data. When applied systematically and thoughtfully, it can provide deep insights into complex social phenomena.</p>
        ',
        'author' => 'Dr. Sarah Johnson',
        'author_avatar' => 'assets/images/authors/sarah.jpg',
        'author_bio' => 'Dr. Sarah Johnson is a leading expert in qualitative research methodologies with over 15 years of experience in educational research. She has published extensively on grounded theory and other qualitative approaches.',
        'published_date' => '2024-02-10',
        'read_time' => 8,
        'category' => 'methodology',
        'tags' => ['Grounded Theory', 'Methodology', 'Qualitative Research', 'Data Analysis'],
        'image' => 'assets/images/blog/grounded-theory.jpg',
        'views' => 1247,
        'likes' => 89,
        'comments_count' => 12,
        'featured' => true
    ],
    2 => [
        'id' => 2,
        'title' => 'Conducting Effective Qualitative Interviews',
        'excerpt' => 'Master the art of qualitative interviewing with practical tips and techniques for gathering rich, meaningful data.',
        'content' => '
            <h2>The Art of Qualitative Interviewing</h2>
            <p>Qualitative interviews are one of the most powerful tools in the researcher\'s toolkit, allowing for deep exploration of participants\' experiences, perspectives, and meanings.</p>
            
            <h3>Types of Qualitative Interviews</h3>
            <p>Researchers can choose from several interview formats:</p>
            <ul>
                <li><strong>Structured Interviews:</strong> Fixed questions with predetermined responses</li>
                <li><strong>Semi-structured Interviews:</strong> Flexible format with key questions and follow-ups</li>
                <li><strong>Unstructured Interviews:</strong> Open-ended conversations guided by participant responses</li>
            </ul>
            
            <h3>Preparing for Interviews</h3>
            <p>Effective preparation is crucial for successful interviews:</p>
            <ol>
                <li>Develop clear research questions</li>
                <li>Create an interview guide with key topics</li>
                <li>Pilot test your questions</li>
                <li>Prepare your recording equipment</li>
                <li>Choose an appropriate setting</li>
            </ol>
            
            <h3>Interview Techniques</h3>
            <p>Skilled interviewers use various techniques to encourage rich responses:</p>
            <ul>
                <li><strong>Probing:</strong> Asking follow-up questions for clarification</li>
                <li><strong>Reflecting:</strong> Paraphrasing to confirm understanding</li>
                <li><strong>Silence:</strong> Allowing participants time to think and respond</li>
                <li><strong>Neutral Responses:</strong> Avoiding judgment or leading questions</li>
            </ul>
            
            <h3>Common Challenges</h3>
            <p>Interviewers often face these challenges:</p>
            <ul>
                <li>Building rapport with participants</li>
                <li>Managing power dynamics</li>
                <li>Handling sensitive topics</li>
                <li>Ensuring data quality</li>
            </ul>
            
            <h3>Best Practices</h3>
            <p>Follow these guidelines for effective interviews:</p>
            <ul>
                <li>Begin with easy, non-threatening questions</li>
                <li>Use open-ended questions</li>
                <li>Listen actively and show interest</li>
                <li>Respect participant boundaries</li>
                <li>Maintain confidentiality</li>
            </ul>
        ',
        'author' => 'Dr. Michael Chen',
        'author_avatar' => 'assets/images/authors/michael.jpg',
        'author_bio' => 'Dr. Michael Chen specializes in interview methodologies and has conducted hundreds of qualitative interviews across diverse populations. He is known for his innovative approaches to data collection.',
        'published_date' => '2024-02-08',
        'read_time' => 12,
        'category' => 'interviews',
        'tags' => ['Interviews', 'Data Collection', 'Research Methods', 'Communication'],
        'image' => 'assets/images/blog/interviews.jpg',
        'views' => 892,
        'likes' => 67,
        'comments_count' => 8,
        'featured' => false
    ]
];

$article = $articles[$articleId] ?? $articles[1];

// Mock comments data
$comments = [
    [
        'id' => 1,
        'user' => 'Dr. Emily Rodriguez',
        'avatar' => 'assets/images/authors/emily.jpg',
        'content' => 'Excellent overview of grounded theory! I particularly appreciated the section on theoretical sampling. This will be very helpful for my students.',
        'date' => '2024-02-12',
        'likes' => 5,
        'replies' => [
            [
                'id' => 2,
                'user' => 'Dr. Sarah Johnson',
                'avatar' => 'assets/images/authors/sarah.jpg',
                'content' => 'Thank you, Emily! I\'m glad you found it helpful. Theoretical sampling is indeed crucial for developing robust grounded theories.',
                'date' => '2024-02-12',
                'likes' => 3
            ]
        ]
    ],
    [
        'id' => 3,
        'user' => 'Marcus Thompson',
        'avatar' => 'assets/images/avatar-default.jpg',
        'content' => 'Great article! I\'m currently using grounded theory in my dissertation research. Could you recommend any additional resources for coding techniques?',
        'date' => '2024-02-11',
        'likes' => 2,
        'replies' => []
    ]
];

// Mock related articles
$relatedArticles = array_filter($articles, function($a) use ($article) {
    return $a['id'] !== $article['id'] && 
           ($a['category'] === $article['category'] || 
            array_intersect($a['tags'], $article['tags']));
});
?>

<!-- Article Header -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?lang=<?= urlencode($lang) ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="blog.php?lang=<?= urlencode($lang) ?>">Blog</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($article['title']) ?></li>
                    </ol>
                </nav>
                
                <div class="mb-4">
                    <span class="badge bg-primary"><?= htmlspecialchars($texts[$article['category']] ?? $article['category']) ?></span>
                    <?php foreach ($article['tags'] as $tag): ?>
                        <span class="badge bg-light text-dark"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
                
                <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($article['title']) ?></h1>
                <p class="lead text-muted"><?= htmlspecialchars($article['excerpt']) ?></p>
                
                <div class="d-flex align-items-center mb-4">
                    <img src="<?= htmlspecialchars($article['author_avatar']) ?>" alt="<?= htmlspecialchars($article['author']) ?>" 
                         class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                    <div>
                        <div class="fw-bold"><?= htmlspecialchars($article['author']) ?></div>
                        <small class="text-muted">
                            <?= htmlspecialchars($texts['published']) ?> <?= date('M j, Y', strtotime($article['published_date'])) ?> • 
                            <?= htmlspecialchars($article['read_time']) ?> <?= htmlspecialchars($texts['readTime']) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Article Content -->
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Article Image -->
                <img src="<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" 
                     class="img-fluid rounded mb-4" style="width: 100%; height: 400px; object-fit: cover;">
                
                <!-- Article Body -->
                <div class="article-content mb-5">
                    <?= $article['content'] ?>
                </div>
                
                <!-- Article Actions -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="likeArticle()">
                            <i class="bi bi-heart"></i> <span id="likeCount"><?= $article['likes'] ?></span>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="bookmarkArticle()">
                            <i class="bi bi-bookmark"></i> <?= htmlspecialchars($texts['bookmark']) ?>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="shareArticle()">
                            <i class="bi bi-share"></i> <?= htmlspecialchars($texts['share']) ?>
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="downloadPDF()">
                            <i class="bi bi-download"></i> <?= htmlspecialchars($texts['download']) ?>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="printArticle()">
                            <i class="bi bi-printer"></i> <?= htmlspecialchars($texts['print']) ?>
                        </button>
                    </div>
                </div>
                
                <!-- Author Bio -->
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($texts['authorBio']) ?></h5>
                        <div class="d-flex align-items-start">
                            <img src="<?= htmlspecialchars($article['author_avatar']) ?>" alt="<?= htmlspecialchars($article['author']) ?>" 
                                 class="rounded-circle me-3" style="width: 64px; height: 64px; object-fit: cover;">
                            <div>
                                <h6 class="mb-2"><?= htmlspecialchars($article['author']) ?></h6>
                                <p class="text-muted mb-3"><?= htmlspecialchars($article['author_bio']) ?></p>
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-person-plus"></i> <?= htmlspecialchars($texts['followAuthor']) ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <?= htmlspecialchars($texts['comments']) ?> 
                            <span class="badge bg-secondary"><?= count($comments) ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Comment Form -->
                            <form class="mb-4">
                                <div class="mb-3">
                                    <textarea class="form-control" rows="3" placeholder="<?= htmlspecialchars($texts['commentPlaceholder']) ?>"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> <?= htmlspecialchars($texts['postComment']) ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- Login Prompt -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> <?= htmlspecialchars($texts['loginToComment']) ?>
                                <div class="mt-2">
                                    <a href="login.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary btn-sm">
                                        <?= htmlspecialchars($texts['loginBtn']) ?>
                                    </a>
                                    <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary btn-sm">
                                        <?= htmlspecialchars($texts['registerBtn']) ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Comments List -->
                        <?php if (empty($comments)): ?>
                            <p class="text-muted text-center py-4"><?= htmlspecialchars($texts['noComments']) ?></p>
                        <?php else: ?>
                            <div class="comments-list">
                                <?php foreach ($comments as $comment): ?>
                                    <div class="comment mb-4">
                                        <div class="d-flex">
                                            <img src="<?= htmlspecialchars($comment['avatar']) ?>" alt="<?= htmlspecialchars($comment['user']) ?>" 
                                                 class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="mb-1"><?= htmlspecialchars($comment['user']) ?></h6>
                                                        <small class="text-muted"><?= date('M j, Y', strtotime($comment['date'])) ?></small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#"><i class="bi bi-flag"></i> <?= htmlspecialchars($texts['reportComment']) ?></a></li>
                                                            <?php if (isset($_SESSION['user_id'])): ?>
                                                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> <?= htmlspecialchars($texts['editComment']) ?></a></li>
                                                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> <?= htmlspecialchars($texts['deleteComment']) ?></a></li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <p class="mb-2"><?= htmlspecialchars($comment['content']) ?></p>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-heart"></i> <?= htmlspecialchars($texts['like']) ?> <span class="badge bg-secondary"><?= $comment['likes'] ?></span>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-reply"></i> <?= htmlspecialchars($texts['reply']) ?>
                                                    </button>
                                                </div>
                                                
                                                <!-- Replies -->
                                                <?php if (!empty($comment['replies'])): ?>
                                                    <div class="replies mt-3 ms-4">
                                                        <?php foreach ($comment['replies'] as $reply): ?>
                                                            <div class="reply mb-3">
                                                                <div class="d-flex">
                                                                    <img src="<?= htmlspecialchars($reply['avatar']) ?>" alt="<?= htmlspecialchars($reply['user']) ?>" 
                                                                         class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                                            <div>
                                                                                <h6 class="mb-1 small"><?= htmlspecialchars($reply['user']) ?></h6>
                                                                                <small class="text-muted"><?= date('M j, Y', strtotime($reply['date'])) ?></small>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mb-1 small"><?= htmlspecialchars($reply['content']) ?></p>
                                                                        <button class="btn btn-sm btn-outline-secondary">
                                                                            <i class="bi bi-heart"></i> <span class="badge bg-secondary"><?= $reply['likes'] ?></span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (count($comments) > 5): ?>
                                <div class="text-center">
                                    <button class="btn btn-outline-primary">
                                        <?= htmlspecialchars($texts['loadMoreComments']) ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Newsletter Subscription -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h6><?= htmlspecialchars($texts['subscribe']) ?></h6>
                        <p class="text-muted small"><?= htmlspecialchars($texts['subscribeText']) ?></p>
                        <form class="d-flex gap-2">
                            <input type="email" class="form-control form-control-sm" placeholder="<?= htmlspecialchars($texts['emailPlaceholder']) ?>" required>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <?= htmlspecialchars($texts['subscribeBtn']) ?>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Related Articles -->
                <?php if (!empty($relatedArticles)): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0"><?= htmlspecialchars($texts['relatedArticles']) ?></h6>
                        </div>
                        <div class="card-body">
                            <?php foreach (array_slice($relatedArticles, 0, 3) as $related): ?>
                                <div class="d-flex mb-3">
                                    <img src="<?= htmlspecialchars($related['image']) ?>" alt="<?= htmlspecialchars($related['title']) ?>" 
                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="article.php?id=<?= $related['id'] ?>&lang=<?= urlencode($lang) ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($related['title']) ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($related['read_time']) ?> <?= htmlspecialchars($texts['readTime']) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Article interactions
function likeArticle() {
    const likeBtn = event.target.closest('button');
    const likeCount = document.getElementById('likeCount');
    const currentLikes = parseInt(likeCount.textContent);
    
    if (likeBtn.classList.contains('liked')) {
        likeBtn.classList.remove('liked', 'btn-primary');
        likeBtn.classList.add('btn-outline-primary');
        likeCount.textContent = currentLikes - 1;
    } else {
        likeBtn.classList.add('liked', 'btn-primary');
        likeBtn.classList.remove('btn-outline-primary');
        likeCount.textContent = currentLikes + 1;
    }
}

function bookmarkArticle() {
    const bookmarkBtn = event.target.closest('button');
    
    if (bookmarkBtn.classList.contains('bookmarked')) {
        bookmarkBtn.classList.remove('bookmarked', 'btn-primary');
        bookmarkBtn.classList.add('btn-outline-secondary');
        bookmarkBtn.innerHTML = '<i class="bi bi-bookmark"></i> <?= htmlspecialchars($texts['bookmark']) ?>';
        showToast('Success', 'Article removed from bookmarks');
    } else {
        bookmarkBtn.classList.add('bookmarked', 'btn-primary');
        bookmarkBtn.classList.remove('btn-outline-secondary');
        bookmarkBtn.innerHTML = '<i class="bi bi-bookmark-fill"></i> <?= htmlspecialchars($texts['bookmarked']) ?>';
        showToast('Success', 'Article added to bookmarks');
    }
}

function shareArticle() {
    const url = window.location.href;
    const title = '<?= addslashes($article['title']) ?>';
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            showToast('Success', 'Link copied to clipboard');
        });
    }
}

function downloadPDF() {
    // In a real app, this would generate and download a PDF
    showToast('Info', 'PDF download feature coming soon');
}

function printArticle() {
    window.print();
}

// Comment form handling
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.querySelector('form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const textarea = this.querySelector('textarea');
            if (textarea.value.trim()) {
                // In a real app, this would submit the comment via API
                showToast('Success', 'Comment posted successfully');
                textarea.value = '';
            }
        });
    }
});

// Social sharing buttons
function shareOnSocial(platform) {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('<?= addslashes($article['title']) ?>');
    
    const shareUrls = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        twitter: `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
        linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${url}`,
        email: `mailto:?subject=${title}&body=${url}`
    };
    
    if (shareUrls[platform]) {
        window.open(shareUrls[platform], '_blank', 'width=600,height=400');
    }
}
</script>

<style>
@media print {
    .navbar, .footer, .btn, .card-header, .comments-section {
        display: none !important;
    }
    
    .article-content {
        font-size: 12pt;
        line-height: 1.6;
    }
}

.article-content h2 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.article-content h3 {
    color: #34495e;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.article-content p {
    margin-bottom: 1rem;
    line-height: 1.7;
}

.article-content ul, .article-content ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.article-content li {
    margin-bottom: 0.5rem;
}

.article-content strong {
    color: #2c3e50;
}
</style>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 