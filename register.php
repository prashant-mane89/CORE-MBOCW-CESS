<?php
require_once 'config/db.php';

// Fetch required dropdown values
$localAuthorityTypes = $conn->query("SELECT id, name FROM local_authority_types")->fetch_all(MYSQLI_ASSOC);
$districts = $conn->query("SELECT id, name FROM districts")->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Maharashtra Building And Other Construction Worker's Welfare Board official portal for CESS, schemes, and worker services.">
    <meta name="keywords" content="MBOCWW, Maharashtra, Construction Worker Welfare, CESS Portal, Government Portal">
    <meta name="author" content="MBOCWW Board">
    <meta name="csrf-token" content="VMGEYmacOGXZTpQsTWlDZ1UdSN6chYRFio7HncOk">
    <title>MBOCWCESS Portal</title>
    <!-- ================== Fevicons ==================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicon_io//site.webmanifest">

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
            <a href="register.php">Register</a>
        </div>
    </div>

    <!-- Optional Scrolling Banner -->
    <div class="scrolling-banner">
        <span>महाराष्ट्र इमारत व इतर बांधकाम कामगार कल्याणकारी मंडळाने सेस रक्कम जमा करण्याकरिता सदरचे अधिकृत वेबपोर्टल तयार केले आहे. तरी उपकर अदा करणारे, अंमलबजावणी करणाऱ्या ऐजंसी व सरकारी विभागांना विनंती करण्यात येत आहे की ऑनलाईन पद्धतीने सेस भरण्याकरिता सदर पोर्टलचा वापर करावा.</span>
        <span>  This is the official web portal of MBOCWW Board to collect the BOCW CESS Amount. All Cess Payee, Implementing Agencies and Government Departments are kindly requested to use this portal to complete the CESS payment through online mode.</span>
    </div>
    <section class="py-5 bg-light">
	<div class="container mb-3">
		<h2 class="text-center fw-bold mb-4">Local Authority With CAFO Registration</h2>

		<form action="http://localhost/CORE-MBOCW-CESS/save-register-form" method="POST" id="cafoRegistrationForm" class="row g-4">
			<input type="hidden" name="_token" value="VMGEYmacOGXZTpQsTWlDZ1UdSN6chYRFio7HncOk">
			<!-- Local Authority Details -->
			<h5 class="text-primary">Local Authority Details</h5>
			<div class="col-md-6">
				<label class="form-label">Local Authority Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control" value="" name="local_authority_name" id="local_authority_name" required>
			    <span id="local_authority_name_error" class="error invalid-feedback d-none">This board office name already taken</span>
			</div>
			<div class="col-md-6">
				<label class="form-label">Local Authority Type <span class="text-danger">*</span></label>
				<select class="form-select" name="local_authority_type" id="local_authority_type" required>
					<option value="">Select Local Authority Type</option>
                    <?php foreach ($localAuthorityTypes as $type): ?>
                        <option value="<?= htmlspecialchars($type['id']) ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
			
			<!-- Divider -->
			<hr class="my-4">

			<!-- CAFO Personal Details -->
			<h5 class="text-primary">CAFO Personal Details</h5>
			<div class="col-md-6">
				<label class="form-label">Full Name <span class="text-danger">*</span></label>
				<input type="text" value="" class="form-control" name="cafo_name" id="cafo_name" placeholder="Enter Full Name" required>
			</div>
			<div class="col-md-6">
				<label class="form-label">Email <span class="text-danger">*</span></label>
				<div class="input-group">
					<input type="email" class="form-control" id="cafo_email" value="" name="cafo_email" placeholder="Enter Email" required>
					<button class="btn btn-outline-secondary" type="button" id="verifyEmailBtn">Verify</button>
				</div>
				<div class="input-group d-none email_verifcation_code" id="div_email_verifcation_code">
					<input type="text" class="form-control" id="email_verifcation_code" name="email_verifcation_code" placeholder="Enter Verification code" required>
					<button class="btn btn-outline-secondary" type="button" id="email_verifcation_button">Verify Code</button>
				</div>
			</div>
			<div class="col-md-6">
				<label class="form-label">Mobile Number <span class="text-danger">*</span></label>
				<div class="input-group">
					<input type="tel" value="" class="form-control numeric" id="cafo_mobile" name="cafo_mobile" placeholder="Enter Mobile No" maxlength="10" required>
					<button class="btn btn-outline-secondary" type="button" id="verifyMobileBtn">Verify</button>
				</div>
				<div class="input-group d-none mobile_verifcation_code" id="div_mobile_verifcation_code">
					<input type="text" class="form-control" id="mobile_verifcation_code" name="mobile_verifcation_code" placeholder="Enter Verification code" required>
					<button class="btn btn-outline-secondary" type="button" id="mobile_verifcation_button">Verify Code</button>
				</div>
			</div>
			<div class="col-md-6">
				<label class="form-label">Gender <span class="text-danger">*</span></label>
				<select class="form-select" name="cafo_gender" id="cafo_gender" required>
					<option value="">Select Gender</option>
					<option value="M">Male</option>
					<option value="F">Female</option>
					<option value="O">Other</option>
				</select>
			</div>
			<div class="col-md-12">
				<label class="form-label">Address <span class="text-danger">*</span></label>
				<textarea class="form-control" value="" name="cafo_address" id="cafo_address" rows="2" placeholder="Enter Address" required> </textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">Aadhaar No <span class="text-danger">*</span></label>
				<input type="text" class="form-control" value="" name="aadhaar_no" id="aadhaar" maxlength="12" placeholder="Enter 12 digit Aadhaar" required>
                <div id="aadhaarError" class="error"></div>
			</div>
			<div class="col-md-6">
				<label class="form-label">PAN Card No <span class="text-danger">*</span></label>
				<input type="text" class="form-control" value="" name="pan_no" id="pancard" maxlength="10" placeholder="Enter 10 character PAN" required>
                <div id="panError" class="error"></div>
			</div>
			<div class="col-md-6">
				<label class="form-label">GSTN </label>
				<input type="text" class="form-control " value=""  name="gstn" id="gstn" maxlength="16" placeholder="Enter 15 character GSTN" required>
                <div id="gstnError" class="error"></div>
			</div>
			<div class="col-md-6">
				<label class="form-label">State <span class="text-danger">*</span></label>
				<select class="form-select stateChange" name="state" id="state" required>
					<option value="">Select State</option>
                    <option value="14" selected>Maharashtra</option>
                </select>
			</div>
			<div class="col-md-6">
				<label class="form-label">District <span class="text-danger">*</span></label>
				<select class="form-select districtChange" name="district" id="district" required>
					<option value="">Select District</option>
                    <?php foreach ($districts as $district): ?>
                        <option value="<?= htmlspecialchars($district['id']) ?>"><?= htmlspecialchars($district['name']) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-6">
				<label class="form-label">Taluka <span class="text-danger">*</span></label>
				<select class="form-select talukaChange" name="taluka" id="taluka" required>
					<option value="">Select Taluka</option>
				</select>
			</div>
			<div class="col-md-6">
				<label class="form-label">Village <span class="text-danger">*</span></label>
				<select class="form-select villageChange" name="village" id="village" required>
					<option value="">Select Village</option>
				</select>
			</div>

			<div class="col-12 text-center">
				<button type="submit" class="btn btn-primary px-4 py-2 register-button" disabled>Register</button>
			</div>
		</form>
	</div>
	<!-- Bottom Decorative Strip -->
	<img src="assets/img/homepage/about-footer.png" loading="lazy" alt="Registration Form Footer" class="img-fluid">
</section>
    <!-- Footer Section -->
<footer style="background-color: #2c4a63; color: white; text-align: center; padding: 30px 20px;">
    <h4 style="margin: 0; font-weight: 600;">Terms & Conditions</h4>
    <p style="margin: 5px 0 15px;">Terms & Conditions</p>
    <p style="margin: 0; font-size: 14px;">
        © Content Owned by Maharashtra Building And Other Construction Workers Welfare Board.
    </p>
</footer>
<!-- Back to Top Button -->
<button onclick="scrollToTop()" id="backToTopBtn" title="Go to top">↑</button>

<script type="text/javascript">
    // Show button on scroll
    window.onscroll = function() {
        const btn = document.getElementById("backToTopBtn");
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            btn.style.display = "block";
        } else {
            btn.style.display = "none";
        }
    };

    // Scroll to top smoothly
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>    <script src="http://43.204.0.18/assets/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript">
	var emailVerification = false;
	var moblieVerification = false;
	$('.numeric').on('input', function(event) {
		this.value = this.value.replace(/[^0-9]/g, '');
	});
	
	$('.districtChange').on('change', function() {
		var district_id = this.value;
		if (!district_id) return;
		fetch("http://localhost/CORE-MBOCW-CESS/get-taluka.php?district_id=" + district_id, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok");
            return res.json();
        })
        .then(data => {
            $('.talukaChange').empty();
            var talukahmtl = '<option value="">Select taluka</option>';
            data.forEach(sub => {
                talukahmtl += `<option value="${sub.id}">${sub.name}</option>`;
            });
            $('.talukaChange').append(talukahmtl);
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('Could not load talukas. See console for details.');
        });
	});

	$('.talukaChange').on('change', function() {
		var taluka_id = this.value;
		if (!taluka_id) return;
		fetch("http://localhost/CORE-MBOCW-CESS/get-village.php?taluka_id=" + taluka_id, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok");
            return res.json();
        })
        .then(data => {
            $('.villageChange').empty();
            var villagehmtl = '<option value="">Select village</option>';
            data.forEach(sub => {
                villagehmtl += `<option value="${sub.id}">${sub.name}</option>`;
            });
            $('.villageChange').append(villagehmtl);
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('Could not load villages. See console for details.');
        });
	});

	// $('#verifyEmailBtn').on('click', function() {
	// 	let email = $('#cfo_email').val();
	// 	let cfo_name = $('#full_name').val();
	// 	let $thisButton = $(this);
	// 	if (!email || !email.includes('@') || !email.includes('.')) {
	// 		alert('Please enter a valid email address.');
	// 		return;
	// 	}
	// 	$.ajaxSetup({
	// 		headers: {
	// 			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 		}
	// 	});
	// 	$.ajax({
	// 		url: '/email-verification',
	// 		method: 'POST',
	// 		data: {
	// 			email: email,
	// 			full_name: cfo_name ? cfo_name : 'User'
	// 		},
	// 		success: function(response) {
	// 			if (response.success) {
	// 				$('#cfo_email').prop('readonly', true);
	// 				$('#div_email_verifcation_code').removeClass('d-none');
	// 			} else {
	// 				console.log(response);
	// 			}
	// 		},
	// 		error: function(xhr) {
	// 			if (xhr.status === 400) {
	// 				let errors = xhr.responseJSON.error;
	// 				alert(errors)
	// 			} else if (xhr.responseJSON && xhr.responseJSON.message) {
	// 				alert(xhr.responseJSON.message);
	// 			}
	// 		}
	// 	});
	// });

	// $('#email_verifcation_button').on('click', function() {
	// 	let code = $('#email_verifcation_code').val();
	// 	let email = $('#cfo_email').val();
	// 	$.ajaxSetup({
	// 		headers: {
	// 			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 		}
	// 	});
	// 	$.ajax({
	// 		url: '/register-email-verified',
	// 		method: 'POST',
	// 		data: {
	// 			code: code,
	// 			email: email
	// 		},
	// 		success: function(response) {
	// 			if (response.success) {
	// 				emailVerification = true;
	// 				$('#div_email_verifcation_code').addClass('d-none');
	// 				// $('#cfo_email').prop('disabled', false);
	// 				$('#verifyEmailBtn').removeClass('btn-outline-secondary').text('Verified').addClass('btn-outline-success').prop('disabled', true);
	// 			}
	// 		},
	// 		error: function(xhr) {
	// 			let errorMessage = 'An error occurred. Please try again.';
	// 			if (xhr.status === 400) {
	// 				let errors = xhr.responseJSON.error;
	// 				alert(errors)
	// 			} else if (xhr.responseJSON && xhr.responseJSON.message) {
	// 				alert(xhr.responseJSON.message);
	// 			}
	// 		}
	// 	});
	// });

	// $('#verifyMobileBtn').on('click', function() {
	// 	let email = $('#cfo_email').val();
	// 	let mobileNumber = $('#cfo_mobile').val();
	// 	let cfo_name = $('#full_name').val();
	// 	let $thisButton = $(this);
	// 	const mobileRegex = /^\d{10}$/;
	// 	if (!mobileRegex.test(mobileNumber)) {
	// 		alert('Please enter a valid 10-digit mobile number.');
	// 		$('#cfo_mobile').focus();
	// 		return;
	// 	}
	// 	$.ajaxSetup({
	// 		headers: {
	// 			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 		}
	// 	});
	// 	$.ajax({
	// 		url: '/mobile-verification',
	// 		method: 'POST',
	// 		data: {
	// 			mobile: mobileNumber,
	// 			email: email,
	// 			full_name: cfo_name ? cfo_name : 'User'
	// 		},
	// 		success: function(response) {
	// 			if (response.success) {
	// 				$('#cfo_mobile').prop('readonly', true);
	// 				$('#div_mobile_verifcation_code').removeClass('d-none');
	// 			}
	// 		},
	// 		error: function(xhr) {
	// 			let errorMessage = 'An error occurred. Please try again.';
	// 			if (xhr.status === 400) {
	// 				let errors = xhr.responseJSON.error;
	// 				alert(errors)
	// 			} else if (xhr.responseJSON && xhr.responseJSON.message) {
	// 				alert(xhr.responseJSON.message);
	// 			}
	// 		}
	// 	});
	// });

	// $('#mobile_verifcation_button').on('click', function() {
	// 	let code = $('#mobile_verifcation_code').val();
	// 	let mobile = $('#cfo_mobile').val();
	// 	$.ajaxSetup({
	// 		headers: {
	// 			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 		}
	// 	});
	// 	$.ajax({
	// 		url: '/register-mobile-verified',
	// 		method: 'POST',
	// 		data: {
	// 			code: code,
	// 			mobile: mobile
	// 		},
	// 		success: function(response) {
	// 			if (response.success) {
	// 				if (emailVerification) {
	// 					$('.register-button').prop('disabled', false);
	// 				}
	// 				$('#div_mobile_verifcation_code').addClass('d-none');
	// 				// $('#cfo_email').prop('disabled', false);
	// 				$('#verifyMobileBtn').removeClass('btn-outline-secondary').text('Verified').addClass('btn-outline-success').prop('disabled', true);
	// 			}
	// 		},
	// 		error: function(xhr) {
	// 			let errorMessage = 'An error occurred. Please try again.';
	// 			if (xhr.status === 400) {
	// 				let errors = xhr.responseJSON.error;
	// 				alert(errors)
	// 			} else if (xhr.responseJSON && xhr.responseJSON.message) {
	// 				alert(xhr.responseJSON.message);
	// 			}
	// 		}
	// 	});
	// });
</script>
<script>
    document.getElementById("cafoRegistrationForm").addEventListener("submit", function(event) {
      event.preventDefault();

      let aadhaar = document.getElementById("aadhaar").value.trim();
      let pan = document.getElementById("pan").value.trim();
      let gstn = document.getElementById("gstn").value.trim();

      let aadhaarRegex = /^\d{12}$/;
      let panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i;
      let gstnRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i;

      let isValid = true;

      // Aadhaar Validation
      if (!aadhaarRegex.test(aadhaar)) {
        document.getElementById("aadhaarError").textContent = "❌ Aadhaar must be exactly 12 digits.";
        isValid = false;
      } else {
        document.getElementById("aadhaarError").textContent = "";
      }

      // PAN Validation
      if (!panRegex.test(pan)) {
        document.getElementById("panError").textContent = "❌ PAN must be 10 characters (e.g. ABCDE1234F).";
        isValid = false;
      } else {
        document.getElementById("panError").textContent = "";
      }

      // GSTN Validation
      if (!gstnRegex.test(gstn)) {
        document.getElementById("gstnError").textContent = "❌ GSTN must be 15 characters (e.g. 22AAAAA0000A1Z5).";
        isValid = false;
      } else {
        document.getElementById("gstnError").textContent = "";
      }

      if (isValid) {
        alert("✅ All inputs are valid!");
      }
    });
</script>
</body>

</html>