<?php
session_start();

$pageTitle = 'About Us - TQRS';
$pageDescription = 'Learn about TQRS mission, team, and commitment to advancing qualitative research methodologies.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'aboutTitle' => 'About TQRS',
    'aboutSubtitle' => 'Advancing Qualitative Research Through Innovation and Collaboration',
    'missionTitle' => 'Our Mission',
    'missionText' => 'To empower researchers worldwide with cutting-edge qualitative research methodologies, foster collaborative learning communities, and drive innovation in research practices through technology and education.',
    'visionTitle' => 'Our Vision',
    'visionText' => 'To become the leading global platform for qualitative research excellence, connecting researchers, practitioners, and students in a dynamic ecosystem of knowledge sharing and methodological advancement.',
    'valuesTitle' => 'Our Values',
    'excellence' => 'Excellence',
    'excellenceText' => 'We strive for the highest quality in all our research methodologies and educational content.',
    'collaboration' => 'Collaboration',
    'collaborationText' => 'We believe in the power of collective knowledge and collaborative learning.',
    'innovation' => 'Innovation',
    'innovationText' => 'We continuously explore new approaches and technologies to advance research practices.',
    'inclusivity' => 'Inclusivity',
    'inclusivityText' => 'We welcome researchers from all backgrounds and experience levels.',
    'teamTitle' => 'Our Team',
    'leadership' => 'Leadership',
    'research' => 'Research',
    'technology' => 'Technology',
    'education' => 'Education',
    'statsTitle' => 'TQRS by the Numbers',
    'webinars' => 'Webinars',
    'webinarsCount' => '500+',
    'webinarsText' => 'Expert-led sessions',
    'researchers' => 'Researchers',
    'researchersCount' => '10,000+',
    'researchersText' => 'Global community',
    'publications' => 'Publications',
    'publicationsCount' => '1,000+',
    'publicationsText' => 'Research papers',
    'countries' => 'Countries',
    'countriesCount' => '50+',
    'countriesText' => 'Worldwide reach'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}
?>

<!-- Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($texts['aboutTitle']) ?></h1>
                <p class="lead mb-4"><?= htmlspecialchars($texts['aboutSubtitle']) ?></p>
                <a href="contact.php?lang=<?= urlencode($lang) ?>" class="btn btn-light btn-lg">
                    <i class="bi bi-envelope"></i> Get in Touch
                </a>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/about-hero.jpg" alt="TQRS Team" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

<!-- Mission & Vision -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-primary mb-3">
                            <i class="bi bi-bullseye fs-1"></i>
                        </div>
                        <h3 class="card-title"><?= htmlspecialchars($texts['missionTitle']) ?></h3>
                        <p class="card-text"><?= htmlspecialchars($texts['missionText']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-primary mb-3">
                            <i class="bi bi-eye fs-1"></i>
                        </div>
                        <h3 class="card-title"><?= htmlspecialchars($texts['visionTitle']) ?></h3>
                        <p class="card-text"><?= htmlspecialchars($texts['visionText']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Values -->
<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?= htmlspecialchars($texts['valuesTitle']) ?></h2>
            <p class="lead text-muted">The principles that guide everything we do</p>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-award fs-2"></i>
                    </div>
                    <h5><?= htmlspecialchars($texts['excellence']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($texts['excellenceText']) ?></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                    <h5><?= htmlspecialchars($texts['collaboration']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($texts['collaborationText']) ?></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-lightbulb fs-2"></i>
                    </div>
                    <h5><?= htmlspecialchars($texts['innovation']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($texts['innovationText']) ?></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-heart fs-2"></i>
                    </div>
                    <h5><?= htmlspecialchars($texts['inclusivity']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($texts['inclusivityText']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?= htmlspecialchars($texts['statsTitle']) ?></h2>
        </div>
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="text-primary fw-bold"><?= htmlspecialchars($texts['webinarsCount']) ?></h2>
                        <h5><?= htmlspecialchars($texts['webinars']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($texts['webinarsText']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="text-primary fw-bold"><?= htmlspecialchars($texts['researchersCount']) ?></h2>
                        <h5><?= htmlspecialchars($texts['researchers']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($texts['researchersText']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="text-primary fw-bold"><?= htmlspecialchars($texts['publicationsCount']) ?></h2>
                        <h5><?= htmlspecialchars($texts['publications']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($texts['publicationsText']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="text-primary fw-bold"><?= htmlspecialchars($texts['countriesCount']) ?></h2>
                        <h5><?= htmlspecialchars($texts['countries']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($texts['countriesText']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?= htmlspecialchars($texts['teamTitle']) ?></h2>
            <p class="lead text-muted">Meet the experts behind TQRS</p>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <img src="assets/images/team-1.jpg" alt="Team Member" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5>Dr. Sarah Johnson</h5>
                        <p class="text-primary"><?= htmlspecialchars($texts['leadership']) ?></p>
                        <p class="text-muted small">Founder & CEO</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <img src="assets/images/team-2.jpg" alt="Team Member" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5>Dr. Michael Chen</h5>
                        <p class="text-primary"><?= htmlspecialchars($texts['research']) ?></p>
                        <p class="text-muted small">Head of Research</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <img src="assets/images/team-3.jpg" alt="Team Member" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5>Emily Rodriguez</h5>
                        <p class="text-primary"><?= htmlspecialchars($texts['technology']) ?></p>
                        <p class="text-muted small">CTO</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <img src="assets/images/team-4.jpg" alt="Team Member" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5>Dr. David Kim</h5>
                        <p class="text-primary"><?= htmlspecialchars($texts['education']) ?></p>
                        <p class="text-muted small">Director of Education</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-4">Join Our Community</h2>
                <p class="lead mb-4">Be part of the global qualitative research revolution. Connect, learn, and grow with researchers worldwide.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="register.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus"></i> Join Now
                    </a>
                    <a href="webinars.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-camera-video"></i> Browse Webinars
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 