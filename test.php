<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chế độ xem ảnh toàn màn hình</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        #fullscreen-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
        }

        #fullscreen-image {
            max-width: 100%;
            max-height: 100%;
            margin: auto;
            display: block;
        }
    </style>
</head>

<body>

    <!-- Hiển thị danh sách ảnh -->
    <img src="images/gallery-img-1.jpg" alt="Image 1" class="image-preview"
        onclick="openFullscreen('images/gallery-img-1.jpg')">
    <img src="images/gallery-img-2.webp" alt="Image 2" class="image-preview"
        onclick="openFullscreen('images/gallery-img-2.webp')">
    <!-- Thêm thêm ảnh nếu cần -->

    <!-- Chế độ xem toàn màn hình -->
    <div id="fullscreen-container">
        <img src="" alt="Fullscreen Image" id="fullscreen-image">
        <span onclick="closeFullscreen()"
            style="position: fixed; top: 15px; right: 15px; color: #fff; cursor: pointer; font-size: 24px;">&#x2716;</span>
    </div>

    <script>
        function openFullscreen(imagePath) {
            var fullscreenContainer = document.getElementById('fullscreen-container');
            var fullscreenImage = document.getElementById('fullscreen-image');

            fullscreenImage.src = imagePath;
            fullscreenContainer.style.display = 'flex';

            document.addEventListener('keydown', closeOnEscape);
        }

        function closeFullscreen() {
            var fullscreenContainer = document.getElementById('fullscreen-container');
            fullscreenContainer.style.display = 'none';

            document.removeEventListener('keydown', closeOnEscape);
        }

        function closeOnEscape(event) {
            if (event.key === 'Escape') {
                closeFullscreen();
            }
        }
    </script>

</body>

</html>