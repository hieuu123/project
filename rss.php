<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/d3b4b6d594.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <?php

    // Địa chỉ URL của RSS feed bạn muốn lấy dữ liệu
    $rssUrl = "https://vnexpress.net/rss/tin-moi-nhat.rss";

    // Tải nội dung XML từ RSS feed
    $xml = simplexml_load_file($rssUrl);

    // Kiểm tra xem việc tải XML có thành công không
    if ($xml === false) {
        die('Error fetching RSS feed');
    }

    // Duyệt qua từng <item> trong RSS feed
    foreach ($xml->channel->item as $item) {
        // Hiển thị tiêu đề của bài viết
        echo '<h2>' . $item->title . '</h2>';

        // Hiển thị thời gian đăng ngay dưới tiêu đề
        echo '<small>Đăng lúc: ' . $item->pubDate . '</small><br><br>';

        $description = $item->description;

        // Tạo một đối tượng DOMDocument để xử lý nội dung mô tả
        $doc = new DOMDocument();

        // Chuyển nội dung mô tả sang mã hóa HTML-ENTITIES
        $descriptionContent = mb_convert_encoding($description, 'HTML-ENTITIES', "UTF-8");

        // Tải nội dung đã được mã hóa vào DOMDocument
        @$doc->loadHTML($descriptionContent);

        // Loại bỏ các thẻ <a> trong nội dung mô tả
        foreach ($doc->getElementsByTagName('a') as $link) {
            $link->parentNode->removeChild($link);
        }

        // Loại bỏ các thẻ <img> trong nội dung mô tả
        foreach ($doc->getElementsByTagName('img') as $img) {
            $img->parentNode->removeChild($img);
        }

        // Lấy nội dung đã được làm sạch
        $cleanDescription = $doc->textContent;

        // Hiển thị nội dung đã được làm sạch
        echo '<p>' . trim($cleanDescription) . '</p>';

        // Thêm liên kết đến bài viết gốc
        echo '<a href="' . $item->link . '" target="_blank">Đọc bài viết gốc</a>';

        // Thêm một dòng kẻ phân cách giữa các bài viết
        echo '<hr>';
    }

    ?>

</body>

</html>