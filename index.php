<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select RSS Feed</title>
</head>
<body>
    <h1>Hello</h1>

    <form action="fc.php" method="post">
        <label for="rss-feed">Chọn thể loại:</label>
        <select name="rss_url" id="rss-feed">
            <option value="https://vnexpress.net/rss/tin-moi-nhat.rss">Tin mới nhất</option>
            <option value="https://vnexpress.net/rss/the-gioi.rss">Thế giới</option>
            <option value="https://vnexpress.net/rss/thoi-su.rss">Thời sự</option>
            <option value="https://vnexpress.net/rss/kinh-doanh.rss">Kinh doanh</option>
            <option value="https://vnexpress.net/rss/giai-tri.rss">Giải trí</option>
            <option value="https://vnexpress.net/rss/the-thao.rss">Thể thao</option>
            <option value="https://vnexpress.net/rss/phap-luat.rss">Pháp luật</option>
            <option value="https://vnexpress.net/rss/giao-duc.rss">Giáo dục</option>
            <option value="https://vnexpress.net/rss/tin-noi-bat.rss">Tin nổi bật</option>
            <option value="https://vnexpress.net/rss/suc-khoe.rss">Sức khỏe</option>
            <option value="https://vnexpress.net/rss/gia-dinh.rss">Đời sống</option>
            <option value="https://vnexpress.net/rss/du-lich.rss">Du lịch</option>
            <option value="https://vnexpress.net/rss/khoa-hoc.rss">Khoa học</option>
            <option value="https://vnexpress.net/rss/so-hoa.rss">Số hóa</option>
            <option value="https://vnexpress.net/rss/oto-xe-may.rss">Xe</option>
            <option value="https://vnexpress.net/rss/y-kien.rss">Ý kiến</option>
            <option value="https://vnexpress.net/rss/tam-su.rss">Tâm sự</option>
            <option value="https://vnexpress.net/rss/cuoi.rss">Cười</option>
            <option value="https://vnexpress.net/rss/tin-xem-nhieu.rss">Tin xem nhiều</option>
        </select>
        <input type="submit" value="Chọn">
    </form>
</body>
</html>
