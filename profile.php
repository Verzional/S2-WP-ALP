<?php
require_once "handler.php";

$userData = getUserData();
$userReviews = getUserReviews();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .content-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex-grow: 1;
        }

        .description {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="bg-gray-900">
    <!-- Navigation -->
    <header>
        <nav class="bg-gray-800 border-gray-200 px-4 lg:px-6 py-4">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                <a href="index.php" class="flex items-center">
                    <img src="https://dieng.blob.core.windows.net/webmaster/2021/09/LOGO-UC-FIX-SEP-2021-01.png"
                        class="mr-3 h-6 sm:h-9" alt="Logo" />
                    <span class="self-center text-xl text-white font-semibold whitespace-nowrap">Cravings</span>
                </a>
                <div class="flex items-center lg:order-2">
                    <div class="hidden lg:flex">
                        <a href="index.php"
                            class="text-white focus:ring-4 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 hover:bg-gray-700 focus:outline-none focus:ring-gray-800">
                            Home</a>
                        <a href="explore.php"
                            class="text-white focus:ring-4 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 hover:bg-gray-700 focus:outline-none focus:ring-gray-800">
                            Explore</a>
                        <a href="profile.php"
                            class="text-orange-500 focus:ring-4 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 hover:bg-gray-700 focus:outline-none focus:ring-gray-800">
                            Profile</a>
                    </div>
                    <button id="burger-btn"
                        class="lg:hidden text-white focus:ring-4 focus:outline-none focus:ring-gray-800"
                        aria-label="Open main menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="mobile-menu"
                class="hidden lg:hidden overflow-hidden transition-max-height duration-500 ease-in-out mt-4 text-center"
                style="max-height: 0;">
                <a href="index.php"
                    class="block text-white focus:ring-4 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 hover:bg-gray-700 focus:outline-none focus:ring-gray-800">
                    Home</a>
                <a href="explore.php"
                    class="block text-white focus:ring-4 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 hover:bg-gray-700 focus:outline-none focus:ring-gray-800">
                    Explore</a>
                <a href="profile.php"
                    class="block text-orange-500 focus:ring-4 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 hover:bg-gray-700 focus:outline-none focus:ring-gray-800">
                    Profile</a>
            </div>
        </nav>
    </header>

    <!-- Profile -->
    <section class="py-12 bg-gray-900 content">
        <div class="mx-auto max-w-screen-xl">
            <div class="flex flex-col items-center justify-center">
                <img src="<?php echo $userData['ProfilePicture']; ?>"
                    class="w-36 h-36 rounded-full object-cover border-4 border-orange-500" alt="Profile Picture" />
                <h1 class="mt-4 text-2xl font-semibold text-gray-900 dark:text-white">
                    <?php echo $userData['Username']; ?>
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    <?php echo $userData['Email']; ?>
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-semibold">Date Joined:</span>
                    <?php echo $userData['DateJoined']; ?>
                </p>
                <div class="mt-6">
                    <a href="update_profile.php"
                        class="bg-orange-500 hover:bg-orange-600 text-white rounded-md py-2 px-4">Update Profile
                    </a>
                </div>
                <div class="mt-6">
                    <a href="handler.php?sign_out"
                        class="bg-orange-500 hover:bg-orange-600 text-white rounded-md py-2 px-4">Sign Out</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Separator -->
    <div class="bg-gray-100 dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl">
            <div class="h-0.5 bg-gray-200 dark:bg-gray-700"></div>
        </div>
    </div>

    <!-- Your Reviews -->
    <section class="py-8 px-4 mx-auto max-w-screen-xl sm:py-12 lg:py-16 lg:px-12">
        <div class="flex items-center justify-between">
            <h2 class="mb-6 text-2xl font-semibold text-gray-900 dark:text-white">Newest Reviews</h2>
            <a href="index.php"
                class="mb-4 text-gray-800 dark:text-orange-500 hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
                See All</a>
        </div>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($userReviews as $review) {
                $username = getUsername($review['UserID']);
                ?>
                <div class="rounded-lg shadow-lg bg-gray-800">
                    <img src="<?php echo $review['ReviewImage']; ?>" class="object-cover w-full h-48 rounded-t-lg"
                        alt="Review" />
                    <div class="p-6">
                        <h3 class="mb-2 text-xl font-semibold text-white text-center"><?php echo $review['ReviewTitle']; ?>
                        </h3>
                        <p class="text-gray-400 text-center description mb-2">
                            <?php echo htmlspecialchars($review['ReviewText']); ?>
                        </p>
                        <p class="text-gray-400 text-center mb-2"> <?php echo $review['ReviewDate']; ?></p>
                        <p class="text-gray-400 text-center">Rating: <?php echo $review['Rating']; ?> ★</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Floating Button -->
    <div class="fixed bottom-8 right-8 z-50">
        <div class="relative">
            <button
                class="bg-orange-500 hover:bg-orange-600 text-white rounded-full w-16 h-16 flex items-center justify-center focus:outline-none"
                onclick="toggleMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <div id="menu"
                class="hidden absolute right-0 bottom-16 mb-4 mr-2 w-72 bg-white rounded-lg shadow-lg overflow-hidden z-10 sm:w-96"
                style="max-height: 0; transition: max-height 0.5s ease;">
                <div class="bg-orange-500 grid grid-cols-3 gap-2 p-4">
                    <div class="bg-orange-500 grid grid-rows-4">
                        <h1 class="text-white px-4 text-center underline text-sm sm:text-base">Add</h1>
                        <a href="add_shop.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">Shop</a>
                        <a href="add_fnb.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">FnB</a>
                        <a href="add_review.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">Review</a>
                    </div>
                    <div class="bg-orange-500 grid grid-rows-4">
                        <h1 class="text-white px-4 text-center underline text-sm sm:text-base">Update</h1>
                        <a href="update_shop.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">Shop</a>
                        <a href="update_fnb.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">FnB</a>
                        <a href="update_review.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">Review</a>
                    </div>
                    <div class="bg-orange-500 grid grid-rows-4">
                        <h1 class="text-white px-4 text-center underline text-sm sm:text-base">Remove</h1>
                        <a href="remove_shop.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">Shop</a>
                        <a href="remove_fnb.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">FnB</a>
                        <a href="remove_review.php"
                            class="bg-orange-500 hover:bg-orange-600 text-white rounded-md w-full py-2 px-4 text-center text-sm sm:text-base">Review</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        const burgerBtn = document.getElementById('burger-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        burgerBtn.addEventListener('click', () => {
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.style.maxHeight = mobileMenu.scrollHeight + "px";
            } else {
                mobileMenu.style.maxHeight = "0";
                mobileMenu.addEventListener('transitionend', function () {
                    mobileMenu.classList.add("hidden");
                }, { once: true });
            }
        });
    </script>

    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            if (menu.classList.contains("hidden")) {
                menu.classList.remove("hidden");
                menu.style.maxHeight = menu.scrollHeight + "px";
            } else {
                menu.style.maxHeight = "0";
                menu.addEventListener('transitionend', function () {
                    menu.classList.add("hidden");
                }, { once: true });
            }
        }
    </script>

    <!-- Footer -->
    <footer class="p-4 sm:p-6 bg-gray-800">
        <div class="mx-auto max-w-screen-xl">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="index.html" class="flex items-center">
                        <img src="https://dieng.blob.core.windows.net/webmaster/2021/09/LOGO-UC-FIX-SEP-2021-01.png"
                            class="mr-3 h-8" alt="Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">Cravings</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold uppercase text-white">Resources</h2>
                        <ul class="text-gray-400">
                            <li class="mb-4">
                                <a href="https://vuejs.org/" class="hover:underline">Vue JS</a>
                            </li>
                            <li>
                                <a href="https://tailwindcss.com/" class="hover:underline">Tailwind CSS</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase text-white">Universitas
                            Ciputra</h2>
                        <ul class="text-gray-400">
                            <li class="mb-4">
                                <a href="https://www.ciputra.ac.id/" class="hover:underline ">Website</a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/universitasciputra/?hl=en"
                                    class="hover:underline">Instagram</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase text-white">Navigation</h2>
                        <ul class="text-gray-400">
                            <li class="mb-4">
                                <a href="index.php" class="hover:underline">Home</a>
                            </li>
                            <li>
                                <a href="profile.php" class="hover:underline">Profile</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto border-gray-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-400 sm:text-center text-gray-400">© 2024 <a href="index.php"
                        class="hover:underline">UC Cravings</a>. All Rights Reserved.
                </span>
                <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0">
                    <a href="https://instagram.com/valentino.m.g/"
                        class="text-gray-400 hover:text-gray-900 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="https://github.com/Verzional" class="text-gray-400 hover:text-gray-900 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>