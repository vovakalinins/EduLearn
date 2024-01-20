    <?php
    include 'Actions/get_user_courses.php'; 

    $courses = [];
    if (isset($_COOKIE["user_token"])) {
        $courses = getUserCourses($conn, $_COOKIE["user_token"]);
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EduLearn - Student Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>

    <body class="font-sans bg-gray-100">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <div class="w-64 bg-blue-600 text-white p-5">
                <h1 class="text-xl font-bold mb-4">EduLearn</h1>
                <!-- Menu items here -->
            </div>
            <!-- Main content -->
            <div class="flex-grow">
                <div class="bg-blue-500 p-4 flex justify-between items-center text-white">
                    <div>Welcome, Username</div>
                    <button id="joinClassButton" class="bg-black hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Join Class +</button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($courses as $course): ?>
                            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                                <img class="w-full h-48 object-cover" src="<?php echo htmlspecialchars("../../uploads/" . $course['image_url']); ?>" alt="Course Image">
                                <div class="p-4">
                                    <h3 class="font-bold text-lg mb-2">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </h3>
                                    <p>
                                        <?php echo htmlspecialchars($course['description']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Join Class Modal -->
        <div id="joinClassModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="modal-content bg-white w-full max-w-md mx-auto mt-20 p-4 rounded-lg shadow-lg">
                <span class="close text-gray-400 hover:text-black cursor-pointer float-right text-xl">&times;</span>
                <form id="joinClassForm" action="Actions/join_class.php" method="post">
                    <input type="text" name="course_code" placeholder="Enter Course Code" required class="border border-gray-300 p-2 w-full rounded">
                    <button type="submit" class="bg-black hover:bg-gray-700 text-white w-full mt-4 py-2 px-4 rounded">Join Class</button>
                </form>
            </div>
        </div>

        <script>
            // Modal JS
            var modal = document.getElementById("joinClassModal");
            var btn = document.getElementById("joinClassButton");
            var span = document.getElementsByClassName("close")[0];

            btn.onclick = function() {
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    </body>

    </html>
