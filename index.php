<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Maharashtra Building And Other Construction Worker's Welfare Board official portal for CESS, schemes, and worker services.">
    <meta name="keywords" content="MBOCWW, Maharashtra, Construction Worker Welfare, CESS Portal, Government Portal">
    <meta name="author" content="MBOCWW Board">

    <title>MBOCWCESS Portal</title>
    <link rel="icon" href="assets\img\favicon_io\favicon.ico" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Mukta', sans-serif;
        }

        /* Main Header */
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            padding: 10px 20px;
            border-bottom: 3px solid #B22222;
            background-color: #ffffff;
        }

        .header-left, .header-right {
            display: flex;
            align-items: center;
        }

        .header-left img,
        .header-right img {
            height: 70px;
            margin: 5px 10px;
        }

        .header-center {
            flex: 1;
            text-align: center;
        }

        .header-center h1 {
            font-size: 20px;
            margin: 0;
            color: #800000;
        }

        .header-center h2 {
            font-size: 15px;
            margin: 5px 0 0;
            color: #b03a2e;
        }

        /* Subheader Navigation */
        .subheader {
            background-color: #f9ffcc;
            padding: 5px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .subheader-left img {
            height: 50px;
            margin-right: 10px;
        }

        .subheader-title {
            font-weight: bold;
            font-size: 18px;
        }

        .subheader-menu {
            display: flex;
            gap: 20px;
            font-size: 16px;
        }

        .subheader-menu a {
            margin: 5px 0;
            text-decoration: none;
            color: #222;
            transition: color 0.3s;
        }

        .subheader-menu a:hover {
            color: #f57c00;
        }

        /* Scrolling Notice (Optional) */
        .scrolling-banner {
            background-color: #ff6d00;
            color: white;
            font-size: 15px;
            padding: 8px 20px;
            white-space: nowrap;
            overflow: hidden;
        }

        .scrolling-banner span {
            display: inline-block;
            animation: scroll-left 30s linear infinite; /* ⬅️ changed from 15s to 30s */
        }

        @keyframes scroll-left {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .carousel img {
            height: 500px;
            object-fit: cover;
        }

        @media (max-width: 768px) {
        .header-left img,
        .header-right img {
            height: 50px;
        }

        .header-center h1 { font-size: 16px; }
        .header-center h2 { font-size: 13px; }

        .subheader-menu {
            flex-direction: column;
            align-items: flex-start;
        }

        .carousel img {
            height: 300px;
        }
        }

        #backToTopBtn {
            display: none;
            position: fixed;
            bottom: 40px;
            right: 30px;
            z-index: 99;
            font-size: 22px;
            background-color: #f57c00;
            color: white;
            border: none;
            outline: none;
            padding: 12px 16px;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: opacity 0.3s, transform 0.3s;
        }

        #backToTopBtn:hover {
            background-color: #e65100;
        }

    </style>
</head>
<body>
    <!-- Main Header -->
    <div class="main-header">
        <div class="header-left">
            <img src="assets\img\homepage\mahaMBOCWLogo.jpg" loading="lazy" alt="Maharashtra Map Logo">
            <img src="assets\img\homepage\mbocw-logo.png" loading="lazy" alt="Board Logo">
        </div>
        <div class="header-center">
            <h1>महाराष्ट्र इमारत व इतर बांधकाम कामगार कल्याणकारी मंडळ</h1>
            <h2>Maharashtra Building And Other Construction Worker's Welfare Board</h2>
        </div>
        <div class="header-right">
            <img src="assets\img\homepage\Maharashtra-state-copy.png" loading="lazy" alt="Gov of Maharashtra Logo">
            <img src="assets\img\homepage\Ashok-Symbol.png" loading="lazy" alt="Indian Emblem">
        </div>
    </div>

    <!-- Subheader / Menu -->
    <div class="subheader">
        <div class="subheader-left">
            <img src="assets\img\homepage\g20.png" loading="lazy" alt="G20 Logo">
            <img src="assets\img\homepage\akam.png" loading="lazy" alt="Azadi Logo">
        </div>
        <div class="subheader-title">
            MBOCWW Board CESS Portal<br><small>MAHARASHTRA GOVERNMENT</small>
        </div>
        <div class="subheader-menu">
            <a href="#">Home</a>
            <a href="#">About Us</a>
            <a href="#">Functionaries</a>
            <a href="#">MAHGOV Resolution</a>
            <a href="#">Schemes</a>
            <a href="#">Contact Us</a>
            <a href="login.php">Login</a>
        </div>
    </div>

    <!-- Optional Scrolling Banner -->
    <div class="scrolling-banner">
        <span>महाराष्ट्र इमारत व इतर बांधकाम कामगार कल्याणकारी मंडळाने सेस रक्कम जमा करण्याकरिता सदरचे अधिकृत वेबपोर्टल तयार केले आहे. तरी उपकर अदा करणारे, अंमलबजावणी करणाऱ्या ऐजंसी व सरकारी विभागांना विनंती करण्यात येत आहे की ऑनलाईन पद्धतीने सेस भरण्याकरिता सदर पोर्टलचा वापर करावा.</span>
        <span>  This is the official web portal of MBOCWW Board to collect the BOCW CESS Amount. All Cess Payee, Implementing Agencies and Government Departments are kindly requested to use this portal to complete the CESS payment through online mode.</span>
    </div>

    <!-- Image Carousel -->
    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/img/homepage/slider/cpimg1.jpg" loading="lazy" class="d-block w-100" alt="Construction workers at site - banner 1">
            </div>
            <div class="carousel-item">
                <img src="assets/img/homepage/slider/cpimg2.jpg" loading="lazy" class="d-block w-100" alt="Construction workers at site - banner 2">
            </div>
            <div class="carousel-item">
                <img src="assets/img/homepage/slider/cpimg3.jpg" loading="lazy" class="d-block w-100" alt="Construction workers at site - banner 3">
            </div>
            <div class="carousel-item">
                <img src="assets/img/homepage/slider/cpimg4.png" loading="lazy" class="d-block w-100" alt="Construction workers at site - banner 4">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- About Us Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <!-- Text -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="fw-bold">About Us</h2>
                    <p style="text-align: justify;">
                    The Building and Other Construction Workers (BOCW) Board is established under the Building and Other Construction Workers Welfare Act 1996. Its primary role is to ensure the welfare and safety of construction workers. The main objective of the BOCW Board is to provide financial assistance and support to construction workers, who often face precarious working conditions. This includes funding for health, education, housing, and other welfare measures. In Maharashtra, the BOCW Board operates under the state's labor department and is tasked with implementing the provisions of the BOCW Act. It ensures that construction projects comply with the cess collection requirements and that the funds are used for worker welfare programs.
                    </p>
                </div>
                <!-- Image -->
                <div class="col-md-6 text-center">
                    <img src="assets\img\homepage\aboutusRgt.jpg" loading="lazy" alt="About Us Illustration" class="img-fluid" style="max-height: 400px;">
                </div>
            </div>
        </div>
        <!-- Bottom Decorative Strip -->
        <img src="assets\img\homepage\about-footer.png" loading="lazy" alt="About Us Footer" class="img-fluid" >
    </section>

    <!-- Key Functionaries Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Key Functionaries</h2>
            <div class="row g-4 justify-content-center">
            
                <!-- Card 1 -->
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center border border-warning shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <img src="assets\img\homepage\Deputi chf minstr Hon. Devendra Gangadhar Rao Fadnavis.jpeg" class="rounded-circle mb-3" width="100" height="100" loading="lazy" alt="Devendra Fadnavis">
                        <h5 class="card-title fw-bold">Hon. Devendra Fadnavis</h5>
                        <p class="card-text">Honourable Chief Minister,<br>Maharashtra State</p>
                    </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center border border-warning shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <img src="assets\img\homepage\chf mins Eknath Sambhaji Shinde.jpeg" class="rounded-circle mb-3" width="100" height="100" loading="lazy" alt="Eknath Shinde">
                        <h5 class="card-title fw-bold">Hon. Eknath Sambhaji Shinde</h5>
                        <p class="card-text">Honourable Deputy Chief Minister,<br>Maharashtra State</p>
                    </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center border border-warning shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <img src="assets\img\homepage\ajit pawar.jpeg" class="rounded-circle mb-3" width="100" height="100" loading="lazy" alt="Ajit Pawar">
                        <h5 class="card-title fw-bold">Hon. Ajit Pawar</h5>
                        <p class="card-text">Honourable Deputy Chief Minister,<br>Maharashtra State</p>
                    </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center border border-warning shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <img src="assets\img\homepage\labour_minister.jpg" class="rounded-circle mb-3" width="100" height="100" loading="lazy" alt="Aakash Fundkar">
                        <h5 class="card-title fw-bold">Hon. Aakash Sunita Pandurang Fundkar</h5>
                        <p class="card-text">Honourable Minister of Labour</p>
                    </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center border border-warning shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <img src="assets\img\homepage\I. A. Kundan.png" class="rounded-circle mb-3" width="100" height="100" loading="lazy" alt="I.A. Kundan">
                        <h5 class="card-title fw-bold">Smt I. A. Kundan</h5>
                        <p class="card-text">Principal Secretary,<br>Labour Department</p>
                    </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center border border-warning shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <img src="assets\img\homepage\Vivek-Shankar-Kumbhar-Sir.jpeg" class="rounded-circle mb-3" width="100" height="100" loading="lazy" alt="Vivek Kumbhar">
                        <h5 class="card-title fw-bold">Mr. Vivek Shankar Kumbhar</h5>
                        <p class="card-text">Secretary cum CEO,<br>MBOCWWB</p>
                    </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Bottom Decorative Strip -->
        <img src="assets\img\homepage\about-footer.png" loading="lazy" alt="About Us Footer" class="img-fluid" >
    </section>

    <!-- Schemes Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Schemes</h2>
            <div class="d-flex flex-column align-items-center gap-3">
                <a href="#" class="btn btn-primary w-75 w-md-50 fs-5 py-2">Social Security</a>
                <a href="#" class="btn btn-primary w-75 w-md-50 fs-5 py-2">Education</a>
                <a href="#" class="btn btn-primary w-75 w-md-50 fs-5 py-2">Healthcare</a>
                <a href="#" class="btn btn-primary w-75 w-md-50 fs-5 py-2">Financial</a>
            </div>
        </div>
        <!-- Bottom Decorative Strip -->
        <img src="assets\img\homepage\about-footer.png" loading="lazy" alt="About Us Footer" class="img-fluid" >
    </section>

    <!-- Contact Us Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Contact Us</h2>
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="border rounded p-4 h-100">
                    <h5 class="fw-bold mb-3">Head Office</h5>
                    <p class="mb-0">
                        Maharashtra Building And Other Construction Worker's Welfare Board. <br>
                        5th Floor, MMTC House, Plot C-22, E-Block, Bandra Kurla Complex, <br>
                        Bandra(E), Mumbai - 400051, <br>
                        Maharashtra
                    </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 h-100">
                    <h5 class="fw-bold mb-3">Mailing Address</h5>
                    <p class="mb-0">bocwwboardmaha@gmail.com</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 h-100">
                    <h5 class="fw-bold mb-3">Contact us</h5>
                    <p class="mb-0">(022) 2657-2631<br>(022) 2657-2632</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom Decorative Strip -->
        <img src="assets\img\homepage\about-footer.png" loading="lazy" alt="About Us Footer" class="img-fluid" >
    </section>

    <!-- Footer Section -->
    <footer style="background-color: #2c4a63; color: white; text-align: center; padding: 30px 20px;">
        <h4 style="margin: 0; font-weight: 600;">Terms & Conditions</h4>
        <p style="margin: 5px 0 15px;">Terms & Conditions</p>
        <p style="margin: 0; font-size: 14px;">
            © Content Owned by Maharashtra Building And Other Construction Workers Welfare Board |
            This is a W3C compliant website.
        </p>
    </footer>

    <!-- Back to Top Button -->
    <button onclick="scrollToTop()" id="backToTopBtn" title="Go to top">↑</button>

    <script>
        // Show button on scroll
        window.onscroll = function () {
            const btn = document.getElementById("backToTopBtn");
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            btn.style.display = "block";
            } else {
            btn.style.display = "none";
            }
        };

        // Scroll to top smoothly
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
