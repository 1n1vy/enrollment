<?php

session_start();
require '../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty']);

if (file_exists('../includes/db_connection.php')) {
    require_once '../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: http://localhost/capst/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <nav id="navbar-placeholder">
        <p>Loading navbar...</p>
    </nav>
    <div class="main-content" id="mainContent">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Advising</a></li>
                <li class="active">Advise Student</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>Payment Confirmation</h1>
        </section>

<!-- Form Section -->
<div class="container mx-auto mt-8 bg-white p-8 rounded-lg shadow-lg max-w-7xl">
    <form class="space-y-6">
        <div class="grid grid-cols-6 gap-4">
            <!-- Row 1 -->
            <div class="col-span-2">
            <label for="student_number" class="font-semibold">Student ID</label>
            <input type="text" id="student_number" name="student_number"class="border w-full p-2 rounded" placeholder="">
            </div>
            <div class="col-span-2">
                <label for="last-name" class="font-semibold">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="border w-full p-2 rounded">
            </div>
            <div class="col-span-2">
                <label for="first-name" class="font-semibold">First Name</label>
                <input type="text" id="first_name" name="first_name" class="border w-full p-2 rounded">
            </div>
            <div class="col-span-4 flex items-center space-x-4">
                <div>
                    <label for="sy-sem" class="font-semibold">SY-SEM</label>
                    <input type="text" id="sy-sem" class="border p-2 w-26 rounded" placeholder="2023-2024">
                </div>
            </div>

            <!-- Row 4 -->
            <div class="col-span-2">
                <label for="classification_code" class="font-semibold">YEAR LEVEL</label>
                <select id="classification_code" name="classification_code" class="border w-full p-2 rounded">
                <option value="">N/A</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>
        </div>

        <!-- Checkboxes -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <div>
                <input type="checkbox" id="advised-students" class="mr-2">
                <label for="advised-students" class="font-semibold">Awaiting Payment Confirmation</label>
            </div>
            <div>
                <input type="checkbox" id="downpayment" class="mr-2">
                <label for="downpayment" class="font-semibold">Show Fully Paid Students</label>
            </div>
            <div>
                <input type="checkbox" id="downpayment-awaiting" class="mr-2">
                <label for="downpayment-awaiting" class="font-semibold">Show Installment Payment Student </label>
            </div>
        </div>

    </form>
    <div class="border-b-4 border-black my-4 -mx-8"></div> 
    <div class="flex justify-center">
        <button id="proceed-btn" style="background-color: #174069;" class="bg-blue-800 text-white py-3 px-6 rounded-full hover:bg-blue-900">
            Proceed
        </button>
    </div>

    <!-- Table section (hidden by default) -->
    <div id="schedule-table" class="mt-6 hidden">

        <div style="background-color: #174069;" class="text-white p-3 text-center font-bold text-xl rounded-t-md">
            Search Result
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="text-gray-700">
                    <tr>
                        <th class="py-2 px-4 border" style="width: 150px;">STUDENT ID</th>
                        <th class="py-2 px-4 border">LNAME, FNAME, MI</th>
                        <th class="py-2 px-4 border">GENDER</th>
                        <th class="py-2 px-4 border">COURSE</th>
                        <th class="py-2 px-4 border">YEAR LEVEL</th>
                        <th class="py-2 px-4 border">Mode of Payment</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                <tr class="text-gray-700">
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                            </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
<div class="border-b-4 border-black my-4"></div>

    <script>
        // Load navbar dynamically
        (function loadNavbar() {
            fetch('../Components/Navbar.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Navbar.html does not exist or is inaccessible');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('navbar-placeholder').innerHTML = html;
                    // Dynamically load app.js if not already loaded
                    if (!document.querySelector('script[src="../../Components/app.js"]')) {
                        const script = document.createElement('script');
                        script.src = '../Components/app.js';
                        script.defer = true;
                        document.body.appendChild(script);
                    }
                })
                .catch(error => {
                    console.error('Error loading navbar:', error);
                    document.getElementById('navbar-placeholder').innerHTML =
                        '<p style="color: red; text-align: center;">Navbar could not be loaded.</p>';
                });
        })();

        document.getElementById('proceed-btn').addEventListener('click', function() {
            document.getElementById('schedule-table').classList.remove('hidden');
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let allStudents = [];

    // Load all students when the page loads
    fetch('SearchStudents.php')  // URL to the PHP script
        .then(res => res.json())
        .then(data => {
            allStudents = data;
        });

    document.getElementById('proceed-btn').addEventListener('click', function () {
        const studentNumber = document.getElementById('student_number') ? document.getElementById('student_number').value.toLowerCase() : '';
        const lastName = document.getElementById('last_name') ? document.getElementById('last_name').value.toLowerCase() : '';
        const firstName = document.getElementById('first_name') ? document.getElementById('first_name').value.toLowerCase() : '';
        const classification = document.getElementById('classification_code') ? document.getElementById('classification_code').value : '';
        const sySem = document.getElementById('sy-sem') ? document.getElementById('sy-sem').value.toLowerCase() : '';

        // Checkboxes for payment statuses
        const showFullyPaid = document.getElementById('downpayment') ? document.getElementById('downpayment').checked : false;
        const showInstallment = document.getElementById('downpayment-awaiting') ? document.getElementById('downpayment-awaiting').checked : false;
        const showAwaiting = document.getElementById('advised-students') ? document.getElementById('advised-students').checked : false;

        // Filter students based on input fields and checkboxes
        const filtered = allStudents.filter(student => {
            const matchesStudentNumber = studentNumber ? student.student_number.toLowerCase().includes(studentNumber) : true;
            const matchesLastName = lastName ? student.last_name.toLowerCase().includes(lastName) : true;
            const matchesFirstName = firstName ? student.first_name.toLowerCase().includes(firstName) : true;
            const matchesClassification = classification ? student.classification_code === classification : true;
            const matchesSySem = sySem ? student.school_year.toLowerCase().includes(sySem) : true;

            // Handle checkboxes for payment statuses
            const matchesPaymentStatus = 
                (showFullyPaid && student.payment_mode === 'fully-paid') ||
                (showInstallment && student.payment_mode === 'installment') ||
                (showAwaiting && student.payment_mode === 'Awaiting-Payment') ||
                (!showFullyPaid && !showInstallment && !showAwaiting);  // No checkbox = show all

            return matchesStudentNumber &&
                   matchesLastName &&
                   matchesFirstName &&
                   matchesClassification &&
                   matchesSySem &&
                   matchesPaymentStatus;
        });

        const tbody = document.querySelector('#table-body');
        tbody.innerHTML = ''; // Clear existing rows

        filtered.forEach(student => {
            const tr = document.createElement('tr');
            tr.className = "text-gray-700";
            tr.innerHTML = `
                <td class="py-2 px-4 border text-center">${student.student_number}</td>
                <td class="py-2 px-4 border text-center">${student.last_name.toUpperCase()}, ${student.first_name.toUpperCase()}</td>
                <td class="py-2 px-4 border text-center">${student.gender}</td>
                <td class="py-2 px-4 border text-center">${student.course_name}</td>
                <td class="py-2 px-4 border text-center">${student.classification_code}</td>
                <td class="py-2 px-4 border text-center">
                    <select class="border rounded py-1 px-2" onchange="updatePaymentMode('${student.student_number}', this)">
                        <option value="installment" ${student.payment_mode === 'installment' ? 'selected' : ''}>Installment</option>
                        <option value="fully-paid" ${student.payment_mode === 'fully-paid' ? 'selected' : ''}>Fully Paid</option>
                        <option value="Awaiting-Payment" ${student.payment_mode === 'Awaiting-Payment' ? 'selected' : ''}>Awaiting Payment</option>
                    </select>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('schedule-table').classList.remove('hidden');
    });
});


function updatePaymentMode(studentId, selectElement) {
    const paymentMode = selectElement.value;

    if (paymentMode && ['installment', 'fully-paid', 'Awaiting-Payment'].includes(paymentMode)) {
        fetch('UpdatePaymentMode.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                student_id: studentId,
                payment_mode: paymentMode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment mode updated successfully!');
            } else {
                alert(`Failed to update payment mode: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error updating payment mode:', error);
            alert('There was an error updating the payment mode. Please try again later.');
        });
    } else {
        alert('Invalid payment mode selected');
    }
}
</script>

</body>

</html>

<!-- CSS styling -->
<style scoped>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f8fa;
        margin: 0;
        padding: 0;
    }

    .breadcrumb-nav {
        margin: 0;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .breadcrumb {
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        padding: 0;
    }

    .breadcrumb li {
        margin-right: 10px;
    }

    .breadcrumb li a {
        color: #174069;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb li a:hover {
        color: #20568B;
    }

    .breadcrumb li.active {
        color: orange;
        pointer-events: none;
    }

    .breadcrumb li::after {
        content: ">";
        margin-left: 10px;
        color: #174069;
    }

    .breadcrumb li:last-child::after {
        content: "";
    }

    .section-header {
        background-color: #174069;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .section-header h1 {
        color: white;
        margin: 0;
    }

    .form-container {
        max-width: 1500px;
        margin: 20px auto;
        background-color: #f4f8fc;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    @media (max-width: 600px) {
        .form-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group input {
            width: 100%;
        }

        .bottom-btns {
            flex-direction: column;
            align-items: center;
        }

        .bottom-btn-group {
            margin-bottom: 15px;
        }
    }
</style>