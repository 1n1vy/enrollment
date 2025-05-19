<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content" id="mainContent" >
        <section class="section-header text-sm md:text-xl mt-6">
            <h1>SUBJECT SCHEDULE PAGE</h1>
        </section>
            <!-- Row 4: Year Level Entry, Current Year, School Year Term (merged on same row) -->
            <div class="form-row form-row-triple">
                <div class="form-group">
                    <label for="year-level">Code:</label>
                    <span class="static-text" id="year-level">CIT321-18</span> <!-- Static Text -->
                </div>

                <div class="form-group">
                    <label>Name:</label>
                    <span class="static-text">Capstone Project 1</span> <!-- Static Text -->
                </div>

                <div class="form-group">
                    <label>Units:</label>
                    <span class="static-text">3.0 (3.0 - Lec, 3.0 Lab)</span> <!-- Static Text -->
                </div>
            </div>
                    <!--Subject Available-->
                    <section class="section-header text-sm mt-6">
                        <h1>SUBJECT SCHEDULES</h1>
                    </section>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300">
                                    <thead style="background-color: #174069;" class="text-white">
                                        <tr>
                                            <th class="py-2 px-4 border" style="width: 150px;">SECTION (ROOM #)</th>
                                            <th class="py-2 px-4 border" style="width: 600px;">SCHEDULES (DAY/TIME)</th>
                                            <th class="py-2 px-4 border" style="width: 800px;">COURSE OFFERING THE SUBJECT</th>
                                            <th class="py-2 px-4 border">MAX. NO. OF STUDENTS</th>
                                            <th class="py-2 px-4 border">NO. OF STUDENTS ENROLLED</th>
                                            <th class="py-2 px-4 border" style="width: 200px;">STATUS</th>
                                            <th class="py-2 px-4 border" style="width: 200px;">SELECT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">3BIST-1 (B234)</td>
                                            <td class="py-2 px-4 border text-center">M 10:00AM-1:00PM</td>
                                            <td class="py-2 px-4 border text-center">Bachelor of Science in Information Technology</td>
                                            <td class="py-2 px-4 border text-center">28</td>
                                            <td class="py-2 px-4 border text-center">15</td>
                                            <td class="py-2 px-4 border text-center">Open</td>
                                            <td class="py-2 px-4 border text-center"><input type="checkbox"></td>
                                        </tr>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">3BIST-2 (B234)</td>
                                            <td class="py-2 px-4 border text-center">T 10:00AM-1:00PM</td>
                                            <td class="py-2 px-4 border text-center">Bachelor of Science in Information Technology</td>
                                            <td class="py-2 px-4 border text-center">28</td>
                                            <td class="py-2 px-4 border text-center">28</td>
                                            <td class="py-2 px-4 border text-center">Closed</td>
                                            <td class="py-2 px-4 border text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="bottom-btn-group flex justify-end text-center mt-6"> <!-- Added flex and justify-end -->
                                <a href="#" style="background-color: #174069;" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 shadow-lg rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Proceed
                                </a>
                            </div>                            
        </div>
    </div>
    <div class="border-b-4 border-black my-4"></div>
</body>
</html>

<style>
/* General Body Styles */
body {
    font-family: 'Poppins', Arial, sans-serif;
    background-color: #f7f8fa;
    margin: 0;
    padding: 0;
}

/* Section Header */
.section-header {
    background-color: #174069;
    padding: 15px;
    text-align: center;
    margin-bottom: 15px;
}

.section-header h1 {
    color: white;
    margin: 0;
    font-size: 24px;
}

/* Form Container */
.form-container {
    max-width: 1400px;
    margin: 20px auto;
    background-color: #f4f8fc;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* Form Row */
.form-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

/* Triple row (for Year Level Entry, Cur. Year, and School Year Term) */
.form-row-triple {
    display: flex;
    justify-content: space-between;
}

/* Form Group */
.form-group {
    display: flex;
    flex-direction: column;
    margin-right: 10px;
    flex: 1;
}

.form-group label {
    margin-bottom: 5px;
    font-weight: 600;
}

.form-group input, .form-group select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

/* Static Text (no bold) */
.form-group .static-text {
    font-weight: normal;
}

/* Special Elements */
.course-info, .cur-year, .school-year-term {
    font-size: 16px;
}

/* Checkbox Style */
.form-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-right: 5px;
}

/* Adjustments for smaller screens */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }

    .form-group {
        margin-right: 0;
    }

    .section-header h1 {
        font-size: 20px;
    }
}
</style>